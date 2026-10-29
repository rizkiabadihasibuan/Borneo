<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\ReportDispatchMailable;
use App\Models\Report;
use App\Models\ReportDispatch;
use App\Models\ReportDispatchHistory;
use App\Models\ReportRecipient;
use App\Services\WhatsApp\WhatsAppApiService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ReportDispatchController extends Controller
{
    public function __construct(protected WhatsAppApiService $whatsappService)
    {
    }

    /**
     * Tampilkan formulir persiapan penerusan / disposisi laporan.
     */
    public function create(Report $report): View
    {
        // Hanya tampilkan penerima yang terdaftar dan aktif
        $recipients = ReportRecipient::where('is_active', true)
            ->orderBy('province', 'asc')
            ->orderBy('category', 'asc')
            ->orderBy('name', 'asc')
            ->get();

        $whatsappConfigured = $this->whatsappService->isConfigured();

        return view('admin.dispatches.create', compact('report', 'recipients', 'whatsappConfigured'));
    }

    /**
     * Eksekusi penerusan laporan ke kanal resmi (Email dan/atau WhatsApp)
     * dengan pemisahan status per-penerima dan per-kanal secara akurat.
     */
    public function store(Request $request, Report $report): RedirectResponse
    {
        $isLegacyForm = $request->has('recipient_id') && !$request->has('recipient_ids');

        if ($isLegacyForm) {
            $validated = $request->validate([
                'recipient_id' => [
                    'required',
                    Rule::exists('report_recipients', 'id')->where('is_active', true),
                ],
                'channel' => ['required', Rule::in(['email', 'whatsapp', 'manual'])],
                'dispatch_notes' => ['required', 'string', 'min:5', 'max:2000'],
                'include_photo' => ['boolean'],
                'include_reporter_contact' => ['boolean'],
            ], [
                'recipient_id.required' => 'Penerima tujuan wajib dipilih dari direktori aktif.',
                'recipient_id.exists' => 'Penerima yang dipilih tidak valid atau sedang dinonaktifkan.',
                'channel.required' => 'Kanal pengiriman wajib ditentukan.',
                'dispatch_notes.required' => 'Catatan pengantar disposisi wajib diisi.',
                'dispatch_notes.min' => 'Catatan pengantar minimal 5 karakter.',
            ]);

            $recipientIds = [$validated['recipient_id']];
            $channels = [$validated['channel']];
        } else {
            $validated = $request->validate([
                'recipient_ids' => ['required', 'array', 'min:1'],
                'recipient_ids.*' => [
                    'required',
                    Rule::exists('report_recipients', 'id')->where('is_active', true),
                ],
                'channels' => ['required', 'array', 'min:1'],
                'channels.*' => ['required', Rule::in(['email', 'whatsapp', 'manual'])],
                'dispatch_notes' => ['required', 'string', 'min:5', 'max:2000'],
                'include_photo' => ['boolean'],
                'include_reporter_contact' => ['boolean'],
            ], [
                'recipient_ids.required' => 'Pilih minimal satu penerima laporan.',
                'recipient_ids.min' => 'Pilih minimal satu penerima laporan.',
                'recipient_ids.*.exists' => 'Salah satu penerima yang dipilih tidak valid atau dinonaktifkan.',
                'channels.required' => 'Pilih minimal satu kanal resmi (Email atau WhatsApp).',
                'channels.min' => 'Pilih minimal satu kanal resmi (Email atau WhatsApp).',
                'dispatch_notes.required' => 'Catatan pengantar disposisi wajib diisi.',
                'dispatch_notes.min' => 'Catatan pengantar minimal 5 karakter.',
            ]);

            $recipientIds = $validated['recipient_ids'];
            $channels = $validated['channels'];
        }
        $dispatchNotes = trim($validated['dispatch_notes']);
        $includePhoto = $request->boolean('include_photo');
        $includeReporter = $request->boolean('include_reporter_contact');
        $admin = Auth::user();

        $successCount = 0;
        $failedCount = 0;
        $resultMessages = [];

        $recipients = ReportRecipient::whereIn('id', $recipientIds)->get();

        foreach ($recipients as $recipient) {
            foreach ($channels as $channel) {
                $result = $this->createAndSendDispatch(
                    $report,
                    $recipient,
                    $channel,
                    $dispatchNotes,
                    $includePhoto,
                    $includeReporter,
                    $admin?->id
                );

                if ($result['success']) {
                    $successCount++;
                } else {
                    $failedCount++;
                }

                $channelName = strtoupper($channel);
                $resultMessages[] = "{$recipient->name} ({$channelName}): {$result['status_label']}";
            }
        }

        $summaryMessage = "Disposisi selesai diproses ({$successCount} terkirim/diterima, {$failedCount} gagal).";

        if ($failedCount > 0) {
            return redirect()->route('admin.reports.show', $report)
                ->with('warning_message', $summaryMessage)
                ->with('dispatch_results', $resultMessages);
        }

        return redirect()->route('admin.reports.show', $report)
            ->with('success_message', $summaryMessage)
            ->with('dispatch_results', $resultMessages);
    }

    /**
     * Kirim ulang (retry) untuk SATU kanal disposisi spesifik yang gagal,
     * tanpa mengirim ulang kanal atau penerima lain yang sudah berhasil.
     */
    public function retry(ReportDispatch $dispatch): RedirectResponse
    {
        $admin = Auth::user();
        $previousStatus = $dispatch->status;

        // Tambah penghitung percobaan kirim ulang
        $dispatch->increment('retry_count');

        $result = $this->executeDelivery($dispatch);

        // Catat riwayat audit percobaan kirim ulang
        ReportDispatchHistory::create([
            'report_dispatch_id' => $dispatch->id,
            'admin_id' => $admin?->id,
            'previous_status' => $previousStatus,
            'new_status' => $dispatch->status,
            'notes' => "Kirim ulang (Percobaan ke-{$dispatch->retry_count}) via " . strtoupper($dispatch->channel) .
                       ". Hasil: {$dispatch->status}. " . ($dispatch->delivery_error ? "Error: {$dispatch->delivery_error}" : ""),
        ]);

        if ($result['success']) {
            return back()->with('success_message', "Kanal {$dispatch->channel} ke {$dispatch->recipient->name} berhasil dikirim ulang.");
        }

        return back()->with('error_message', "Kirim ulang kanal {$dispatch->channel} gagal: " . ($dispatch->delivery_error ?: 'Kesalahan koneksi'));
    }

    /**
     * Buat rekaman disposisi terpisah dan eksekusi pengirimannya.
     */
    protected function createAndSendDispatch(
        Report $report,
        ReportRecipient $recipient,
        string $channel,
        string $notes,
        bool $includePhoto,
        bool $includeReporter,
        ?int $adminId
    ): array {
        $dispatch = ReportDispatch::create([
            'report_id' => $report->id,
            'recipient_id' => $recipient->id,
            'admin_id' => $adminId,
            'channel' => $channel,
            'dispatch_notes' => $notes,
            'include_photo' => $includePhoto && (bool) $report->photo_path,
            'include_reporter_contact' => $includeReporter,
            'status' => 'Menunggu Pengiriman',
            'recipient_status' => 'Menunggu Konfirmasi Penerima',
            'retry_count' => 0,
        ]);

        $result = $this->executeDelivery($dispatch);

        // Catat jejak audit riwayat awal
        ReportDispatchHistory::create([
            'report_dispatch_id' => $dispatch->id,
            'admin_id' => $adminId,
            'previous_status' => 'Menunggu Pengiriman',
            'new_status' => $dispatch->status,
            'notes' => "Pengiriman awal via kanal " . strtoupper($channel) . " ke {$recipient->name}. Status: {$dispatch->status}.",
        ]);

        return $result;
    }

    /**
     * Eksekusi pengiriman teknis berdasarkan kanal (Email / WhatsApp)
     * dan perbarui model ReportDispatch dengan status yang akurat.
     */
    protected function executeDelivery(ReportDispatch $dispatch): array
    {
        $recipient = $dispatch->recipient;
        $channel = $dispatch->channel;

        if ($channel === 'email') {
            return $this->deliverEmail($dispatch, $recipient);
        }

        if ($channel === 'whatsapp') {
            return $this->deliverWhatsApp($dispatch, $recipient);
        }

        // Fallback untuk kanal manual posko darat jika ada
        $dispatch->update([
            'status' => 'Berhasil Dikirim ke Penyedia',
            'sent_at' => now(),
            'reference_id' => 'POSKO-' . date('YmdHis') . '-' . strtoupper(Str::random(4)),
        ]);

        return ['success' => true, 'status_label' => 'Dicatat Posko Darat'];
    }

    /**
     * Eksekusi pengiriman email via Laravel Mailer.
     */
    protected function deliverEmail(ReportDispatch $dispatch, ReportRecipient $recipient): array
    {
        if (empty($recipient->email)) {
            $dispatch->update([
                'status' => 'Gagal Dikirim',
                'delivery_error' => 'Penerima tidak memiliki alamat email resmi yang terdaftar.',
            ]);

            return ['success' => false, 'status_label' => 'Gagal (Email tidak terdaftar)'];
        }

        try {
            Mail::to($recipient->email)->send(new ReportDispatchMailable($dispatch));

            $dispatch->update([
                'status' => 'Berhasil Dikirim ke Penyedia',
                'sent_at' => now(),
                'reference_id' => 'EML-' . date('YmdHis') . '-' . strtoupper(Str::random(4)),
                'delivery_error' => null,
            ]);

            return ['success' => true, 'status_label' => 'Berhasil Dikirim ke Penyedia'];
        } catch (\Exception $e) {
            $dispatch->update([
                'status' => 'Gagal Dikirim',
                'delivery_error' => 'Kegagalan SMTP/Mail: ' . $e->getMessage(),
            ]);

            return ['success' => false, 'status_label' => 'Gagal Dikirim'];
        }
    }

    /**
     * Eksekusi pengiriman WhatsApp via WhatsApp Business Cloud API Meta.
     */
    protected function deliverWhatsApp(ReportDispatch $dispatch, ReportRecipient $recipient): array
    {
        if (!$this->whatsappService->isConfigured()) {
            $dispatch->update([
                'status' => 'Gagal',
                'delivery_error' => 'Kredensial WhatsApp Business API belum dikonfigurasi pada server.',
            ]);

            return ['success' => false, 'status_label' => 'Gagal (Kredensial Belum Dikonfigurasi)'];
        }

        $cleanNumber = $recipient->clean_whatsapp_number;
        if (empty($cleanNumber)) {
            $dispatch->update([
                'status' => 'Gagal',
                'delivery_error' => 'Nomor WhatsApp penerima tidak tersedia atau format tidak valid.',
            ]);

            return ['success' => false, 'status_label' => 'Gagal (Nomor tidak valid)'];
        }

        $res = $this->whatsappService->sendDispatchMessage($dispatch);

        if ($res['success']) {
            $dispatch->update([
                'status' => 'Diterima API',
                'sent_at' => now(),
                'provider_message_id' => $res['message_id'],
                'reference_id' => 'WA-' . date('YmdHis') . '-' . strtoupper(Str::random(4)),
                'delivery_error' => null,
            ]);

            return ['success' => true, 'status_label' => 'Diterima API WhatsApp'];
        }

        $dispatch->update([
            'status' => 'Gagal',
            'delivery_error' => $res['error'],
        ]);

        return ['success' => false, 'status_label' => 'Gagal: ' . $res['error']];
    }

    /**
     * Update status konfirmasi atau tindak lanjut dari pihak penerima disposisi.
     */
    public function updateRecipientStatus(Request $request, ReportDispatch $dispatch): RedirectResponse
    {
        $allowedStatuses = [
            'Menunggu Konfirmasi Penerima',
            'Dikonfirmasi Penerima',
            'Sedang Ditindaklanjuti',
            'Selesai Ditindaklanjuti',
        ];

        $validated = $request->validate([
            'recipient_status' => ['required', Rule::in($allowedStatuses)],
            'recipient_feedback' => ['required', 'string', 'min:5', 'max:2000'],
        ], [
            'recipient_status.required' => 'Status tindak lanjut penerima wajib dipilih.',
            'recipient_feedback.required' => 'Catatan laporan tindak lanjut penerima wajib diisi.',
            'recipient_feedback.min' => 'Catatan minimal 5 karakter.',
        ]);

        $previousStatus = $dispatch->recipient_status;
        $newStatus = $validated['recipient_status'];
        $admin = Auth::user();

        DB::transaction(function () use ($dispatch, $previousStatus, $newStatus, $validated, $admin) {
            $updateData = [
                'recipient_status' => $newStatus,
                'recipient_feedback' => trim($validated['recipient_feedback']),
            ];

            if ($newStatus === 'Dikonfirmasi Penerima' && !$dispatch->confirmed_at) {
                $updateData['confirmed_at'] = now();
            }

            if ($newStatus === 'Selesai Ditindaklanjuti' && !$dispatch->completed_at) {
                $updateData['completed_at'] = now();
            }

            $dispatch->update($updateData);

            ReportDispatchHistory::create([
                'report_dispatch_id' => $dispatch->id,
                'admin_id' => $admin?->id,
                'previous_status' => $previousStatus,
                'new_status' => $newStatus,
                'notes' => "Perubahan status tindak lanjut penerima: {$newStatus}. Respon: " . trim($validated['recipient_feedback']),
            ]);
        });

        return back()->with('success_message', "Status tindak lanjut disposisi berhasil diperbarui menjadi '{$newStatus}'.");
    }
}
