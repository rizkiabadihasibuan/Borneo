<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ReportDispatch;
use App\Models\ReportDispatchHistory;
use App\Models\WhatsAppWebhookLog;
use App\Services\WhatsApp\WhatsAppApiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WhatsAppWebhookController extends Controller
{
    public function __construct(protected WhatsAppApiService $whatsappService)
    {
    }

    /**
     * Handshake verifikasi webhook Meta Cloud API (GET).
     */
    public function verify(Request $request): Response
    {
        $mode = $request->query('hub_mode', $request->query('hub.mode'));
        $token = $request->query('hub_verify_token', $request->query('hub.verify_token'));
        $challenge = $request->query('hub_challenge', $request->query('hub.challenge'));

        $configuredToken = $this->whatsappService->getVerifyToken();

        if ($mode === 'subscribe' && !empty($configuredToken) && hash_equals($configuredToken, (string) $token)) {
            return response($challenge, 200)->header('Content-Type', 'text/plain');
        }

        return response('Forbidden', 403)->header('Content-Type', 'text/plain');
    }

    /**
     * Terima notifikasi status pengiriman dari Meta Cloud API (POST).
     * Memverifikasi tanda tangan HMAC, menjaga idempoten, dan memperbarui status kanal secara terpisah.
     */
    public function handle(Request $request): JsonResponse
    {
        // 1. Verifikasi tanda tangan X-Hub-Signature-256 jika app_secret dikonfigurasi
        $signatureHeader = $request->header('X-Hub-Signature-256');
        $rawPayload = $request->getContent();

        if (config('services.whatsapp.app_secret')) {
            if (!$this->whatsappService->verifyWebhookSignature($rawPayload, $signatureHeader)) {
                return response()->json(['error' => 'Tanda tangan webhook tidak valid.'], 403);
            }
        }

        $payload = $request->json()->all();

        // 2. Ekstrak event status
        $entries = $payload['entry'] ?? [];
        foreach ($entries as $entry) {
            $changes = $entry['changes'] ?? [];
            foreach ($changes as $change) {
                if (($change['field'] ?? '') !== 'messages') {
                    continue;
                }

                $value = $change['value'] ?? [];
                $statuses = $value['statuses'] ?? [];

                foreach ($statuses as $statusItem) {
                    $this->processStatusItem($statusItem);
                }
            }
        }

        return response()->json(['status' => 'EVENT_RECEIVED'], 200);
    }

    /**
     * Proses tiap item status dengan jaminan idempoten.
     */
    protected function processStatusItem(array $statusItem): void
    {
        $messageId = $statusItem['id'] ?? null;
        $eventStatus = $statusItem['status'] ?? null;
        $timestamp = $statusItem['timestamp'] ?? time();
        $recipientPhone = $statusItem['recipient_id'] ?? null;

        if (!$messageId || !$eventStatus) {
            return;
        }

        $idempotencyKey = $messageId . ':' . $eventStatus . ':' . $timestamp;

        // Cegah pemrosesan ganda (Idempotency check)
        if (WhatsAppWebhookLog::where('idempotency_key', $idempotencyKey)->exists()) {
            return;
        }

        DB::transaction(function () use ($messageId, $eventStatus, $timestamp, $recipientPhone, $idempotencyKey, $statusItem) {
            $log = WhatsAppWebhookLog::create([
                'message_id' => $messageId,
                'recipient_id' => $recipientPhone,
                'event_status' => $eventStatus,
                'idempotency_key' => $idempotencyKey,
                'raw_payload' => $statusItem,
                'is_processed' => true,
            ]);

            // Cari disposisi spesifik berdasarkan ID pesan penyedia Meta
            $dispatch = ReportDispatch::where('provider_message_id', $messageId)->first();
            if (!$dispatch) {
                return;
            }

            $previousStatus = $dispatch->status;
            $updateData = [];
            $historyNote = null;

            switch ($eventStatus) {
                case 'sent':
                    if (in_array($previousStatus, ['Menunggu Pengiriman', 'Diproses'])) {
                        $updateData['status'] = 'Diterima API';
                        $updateData['sent_at'] = now();
                        $historyNote = 'Pesan diterima server WhatsApp API Meta.';
                    }
                    break;

                case 'delivered':
                    $updateData['status'] = 'Terkirim ke Perangkat';
                    $updateData['delivered_at'] = now();
                    $historyNote = 'Pesan WhatsApp berhasil diterima oleh perangkat penerima.';
                    break;

                case 'read':
                    $updateData['status'] = 'Dibaca';
                    $updateData['read_at'] = now();
                    $historyNote = 'Pesan WhatsApp telah dibaca atau dibuka oleh penerima.';
                    break;

                case 'failed':
                    $errorTitle = 'Pengiriman gagal dari penyedia WhatsApp.';
                    if (!empty($statusItem['errors'][0])) {
                        $err = $statusItem['errors'][0];
                        $errorTitle = 'Ditolak WhatsApp: ' . ($err['title'] ?? 'Kode ' . ($err['code'] ?? ''));
                    }
                    $updateData['status'] = 'Ditolak Penyedia';
                    $updateData['delivery_error'] = $errorTitle;
                    $historyNote = "Pesan ditolak/gagal dikirim oleh WhatsApp: {$errorTitle}";
                    break;
            }

            if (!empty($updateData)) {
                $dispatch->update($updateData);

                ReportDispatchHistory::create([
                    'report_dispatch_id' => $dispatch->id,
                    'admin_id' => null, // Dipicu oleh sistem Webhook
                    'previous_status' => $previousStatus,
                    'new_status' => $updateData['status'] ?? $previousStatus,
                    'notes' => $historyNote ?: "Pembaruan otomatis webhook WhatsApp: {$eventStatus}",
                ]);
            }
        });
    }
}
