<?php

namespace App\Services\WhatsApp;

use App\Models\ReportDispatch;
use App\Models\ReportRecipient;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class WhatsAppApiService
{
    protected ?string $baseUrl;
    protected ?string $phoneNumberId;
    protected ?string $accessToken;
    protected ?string $appSecret;
    protected ?string $verifyToken;
    protected int $timeout;

    public function __construct()
    {
        $this->baseUrl = config('services.whatsapp.base_url', 'https://graph.facebook.com/v21.0');
        $this->phoneNumberId = config('services.whatsapp.phone_number_id');
        $this->accessToken = config('services.whatsapp.access_token');
        $this->appSecret = config('services.whatsapp.app_secret');
        $this->verifyToken = config('services.whatsapp.verify_token');
        $this->timeout = (int) config('services.whatsapp.timeout', 15);
    }

    /**
     * Memeriksa apakah kredensial WhatsApp Business Cloud API telah dikonfigurasi.
     */
    public function isConfigured(): bool
    {
        return !empty($this->phoneNumberId) && !empty($this->accessToken);
    }

    /**
     * Status konfigurasi untuk tampilan pengaturan admin yang aman (tanpa membocorkan token).
     */
    public function getConfigurationStatus(): array
    {
        return [
            'is_configured' => $this->isConfigured(),
            'base_url' => $this->baseUrl,
            'phone_number_id' => $this->phoneNumberId ? Str::mask($this->phoneNumberId, '*', 3, -3) : null,
            'has_phone_number_id' => !empty($this->phoneNumberId),
            'has_access_token' => !empty($this->accessToken),
            'has_app_secret' => !empty($this->appSecret),
            'has_verify_token' => !empty($this->verifyToken),
            'timeout' => $this->timeout,
        ];
    }

    /**
     * Dapatkan token verifikasi webhook untuk handshake Meta.
     */
    public function getVerifyToken(): ?string
    {
        return $this->verifyToken;
    }

    /**
     * Kirim pesan disposisi resmi ke nomor WhatsApp penerima via Meta Cloud API.
     *
     * @return array{success: bool, message_id: ?string, status: string, error: ?string}
     */
    public function sendDispatchMessage(ReportDispatch $dispatch): array
    {
        if (!$this->isConfigured()) {
            return [
                'success' => false,
                'message_id' => null,
                'status' => 'Gagal',
                'error' => 'Kredensial WhatsApp Business Cloud API belum dikonfigurasi pada server.',
            ];
        }

        $recipient = $dispatch->recipient;
        if (!$recipient) {
            return [
                'success' => false,
                'message_id' => null,
                'status' => 'Gagal',
                'error' => 'Data penerima disposisi tidak ditemukan.',
            ];
        }

        $cleanNumber = $recipient->clean_whatsapp_number;
        if (empty($cleanNumber)) {
            return [
                'success' => false,
                'message_id' => null,
                'status' => 'Gagal',
                'error' => 'Nomor WhatsApp penerima belum terdaftar atau format tidak valid.',
            ];
        }

        $url = rtrim($this->baseUrl, '/') . '/' . $this->phoneNumberId . '/messages';
        $messageBody = $this->buildMessageBody($dispatch);

        $payload = [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $cleanNumber,
            'type' => 'text',
            'text' => [
                'preview_url' => false,
                'body' => $messageBody,
            ],
        ];

        try {
            $response = Http::withToken($this->accessToken)
                ->timeout($this->timeout)
                ->withHeaders([
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ])
                ->post($url, $payload);

            if ($response->successful()) {
                $data = $response->json();
                $messageId = $data['messages'][0]['id'] ?? null;

                return [
                    'success' => true,
                    'message_id' => $messageId,
                    'status' => 'Diterima API',
                    'error' => null,
                ];
            }

            // Sanitasi pesan error dari respons API (hindari mencetak data kredensial)
            $errorData = $response->json('error');
            $sanitizedMessage = 'Respons Meta API (' . $response->status() . '): ' .
                ($errorData['message'] ?? 'Terjadi kesalahan saat memproses pesan WhatsApp.');

            return [
                'success' => false,
                'message_id' => null,
                'status' => 'Gagal',
                'error' => $sanitizedMessage,
            ];
        } catch (\Exception $e) {
            // Sanitasi exception (hindari stack trace yang memuat token)
            return [
                'success' => false,
                'message_id' => null,
                'status' => 'Gagal',
                'error' => 'Koneksi ke WhatsApp API gagal: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Bangun pesan teks resmi untuk disposisi lapangan Borneo Siaga.
     */
    public function buildMessageBody(ReportDispatch $dispatch): string
    {
        $report = $dispatch->report;
        $recipient = $dispatch->recipient;

        $lines = [];
        $lines[] = "🚨🚨 *[PENGUMUMAN SIAGA KARHUTLA]* 🚨🚨";
        $lines[] = "*POSKO TANGGAP CEPAT • BORNEO SIAGA*";
        $lines[] = "═══════════════════════════";
        $lines[] = "Yth. *{$recipient->name}*";
        $lines[] = "📢 *ATENSI DISPOSISI LAPANGAN RESMI*";
        $lines[] = "═══════════════════════════";
        $lines[] = "";
        $lines[] = "🎫 *NO. TIKET:* `#" . $report->ticket_number . "`";
        $lines[] = "⚠️ *STATUS:* *" . strtoupper($report->status_label) . "*";
        $lines[] = "🔥 *JENIS KEJADIAN:* " . $report->incident_type;
        if ($report->incident_time) {
            $lines[] = "⏱️ *WAKTU LAPOR:* " . $report->incident_time->format('d/m/Y H:i') . " WIB";
        }
        $lines[] = "";
        $lines[] = "📌 *ARAHAN / INSTRUKSI POSKO:*";
        $lines[] = "_{$dispatch->dispatch_notes}_";
        $lines[] = "";
        $lines[] = "📍 *LOKASI KEJADIAN:*";
        $lines[] = "🏛️ Provinsi: *" . $report->province . "*";
        $lines[] = "🏢 Kab/Kota: *" . $report->regency . "*";
        if ($report->district || $report->village) {
            $lines[] = "🏘️ Wilayah: *" . trim(($report->village ? "Desa {$report->village}, " : "") . ($report->district ? "Kec. {$report->district}" : "")) . "*";
        }
        $lines[] = "🧭 Alamat/Patokan: {$report->incident_location}";

        if ($report->latitude && $report->longitude) {
            $lat = number_format($report->latitude, 5);
            $lon = number_format($report->longitude, 5);
            $lines[] = "";
            $lines[] = "🗺️ *TITIK KOORDINAT (GPS):*";
            $lines[] = "📡 `{$lat}, {$lon}`";
            $lines[] = "🔗 *Peta Navigasi (Google Maps):*";
            $lines[] = "https://www.google.com/maps?q={$report->latitude},{$report->longitude}";
        }

        $lines[] = "";
        $lines[] = "📝 *DESKRIPSI KONDISI LAPANGAN:*";
        $lines[] = "“" . Str::limit($report->description, 350) . "”";

        if ($dispatch->include_reporter_contact && $report->reporter_phone) {
            $lines[] = "";
            $lines[] = "👤 *KONTAK PELAPOR:*";
            $lines[] = "• Nama: {$report->reporter_name}";
            $lines[] = "• No. Telp/WA: {$report->reporter_phone}";
        }

        if ($dispatch->include_photo && $dispatch->secure_photo_url) {
            $lines[] = "";
            $lines[] = "📷 *BUKTI FOTO LAPANGAN (Tautan Aman 7 Hari):*";
            $lines[] = $dispatch->secure_photo_url;
        }

        $lines[] = "";
        $lines[] = "═══════════════════════════";
        $lines[] = "🚒 *HIMBAUAN TINDAK LANJUT:*";
        $lines[] = "Mohon segera konfirmasi penerimaan disposisi ini dan laporkan perkembangan penanganan (*sitrep*) ke Posko Siaga.";
        $lines[] = "═══════════════════════════";
        $lines[] = "🌐 *Portal Siaga:* " . url('/');
        $lines[] = "_#BorneoSiaga #TanggapKarhutla_";

        return implode("\n", $lines);
    }

    /**
     * Verifikasi tanda tangan X-Hub-Signature-256 dari webhook Meta.
     */
    public function verifyWebhookSignature(string $rawPayload, ?string $signatureHeader): bool
    {
        if (empty($this->appSecret) || empty($signatureHeader)) {
            return false;
        }

        if (!str_starts_with($signatureHeader, 'sha256=')) {
            return false;
        }

        $expectedHash = hash_hmac('sha256', $rawPayload, $this->appSecret);
        $providedHash = substr($signatureHeader, 7);

        return hash_equals($expectedHash, $providedHash);
    }
}
