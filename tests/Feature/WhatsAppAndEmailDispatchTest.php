<?php

namespace Tests\Feature;

use App\Mail\ReportDispatchMailable;
use App\Models\Report;
use App\Models\ReportDispatch;
use App\Models\ReportDispatchHistory;
use App\Models\ReportRecipient;
use App\Models\User;
use App\Models\WhatsAppWebhookLog;
use App\Services\WhatsApp\WhatsAppApiService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class WhatsAppAndEmailDispatchTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $regularUser;
    private Report $report;
    private ReportRecipient $recipientBpbd;
    private ReportRecipient $recipientMpa;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Admin Posko Uji',
            'email' => 'admin.uji@borneosiaga.id',
            'password' => Hash::make('AdminSecret123'),
            'is_admin' => true,
        ]);

        $this->regularUser = User::create([
            'name' => 'Warga Masyarakat',
            'email' => 'warga@example.com',
            'password' => Hash::make('WargaSecret123'),
            'is_admin' => false,
        ]);

        $this->report = Report::create([
            'ticket_number' => 'BS-20261027-WA01',
            'access_code_hash' => Hash::make('CODE9999'),
            'reporter_name' => 'Agus Santoso',
            'reporter_phone' => '081234567890',
            'province' => 'Kalimantan Barat',
            'regency' => 'Kab. Kubu Raya',
            'incident_location' => 'Parit Baru KM 12',
            'latitude' => -0.051234,
            'longitude' => 109.345678,
            'incident_time' => now(),
            'incident_type' => 'Kebakaran Lahan Gambut',
            'description' => 'Kepulan asap tebal membakar semak belukar gambut seluas 2 hektar.',
            'status' => 'Sedang Diverifikasi',
        ]);

        $this->recipientBpbd = ReportRecipient::create([
            'name' => 'BPBD Provinsi Kalimantan Barat',
            'category' => 'pemerintah',
            'agency_type' => 'BPBD',
            'province' => 'Kalimantan Barat',
            'regency' => 'Semua Wilayah',
            'contact_person' => 'Piket Pusdalops',
            'phone' => '0561-739123',
            'whatsapp_number' => '081255556666',
            'email' => 'pusdalops@kalbarprov.go.id',
            'preferred_channel' => 'whatsapp',
            'verified_channels' => ['email', 'whatsapp'],
            'is_active' => true,
        ]);

        $this->recipientMpa = ReportRecipient::create([
            'name' => 'MPA Mandiri Desa Galang',
            'category' => 'komunitas_mpa',
            'agency_type' => 'Kelompok Relawan',
            'province' => 'Kalimantan Barat',
            'regency' => 'Kab. Kubu Raya',
            'contact_person' => 'Pak Burhan',
            'phone' => '082199887766',
            'whatsapp_number' => '6282199887766',
            'email' => 'mpa.galang@gmail.com',
            'preferred_channel' => 'whatsapp',
            'verified_channels' => ['whatsapp'],
            'is_active' => true,
        ]);
    }

    /**
     * 1. Otorisasi Admin: Pengunjung publik & non-admin ditolak dari akses disposisi dan pengaturan WhatsApp.
     */
    public function test_admin_authorization_and_guest_rejection(): void
    {
        // Tamu dialihkan ke login
        $this->get(route('admin.settings.whatsapp'))->assertRedirect(route('admin.login'));
        $this->get(route('admin.dispatches.create', $this->report))->assertRedirect(route('admin.login'));

        // Non-admin ditolak dengan 403 Forbidden
        $this->actingAs($this->regularUser)->get(route('admin.settings.whatsapp'))->assertStatus(403);
        $this->actingAs($this->regularUser)->get(route('admin.dispatches.create', $this->report))->assertStatus(403);

        // Admin diizinkan mengakses
        $this->actingAs($this->admin)->get(route('admin.settings.whatsapp'))->assertStatus(200);
        $this->actingAs($this->admin)->get(route('admin.dispatches.create', $this->report))->assertStatus(200);
    }

    /**
     * 2. Validasi & Format Internasional Nomor WhatsApp pada Direktori Penerima.
     */
    public function test_recipient_whatsapp_validation_and_clean_number(): void
    {
        $this->assertEquals('6281255556666', $this->recipientBpbd->clean_whatsapp_number);
        $this->assertEquals('6282199887766', $this->recipientMpa->clean_whatsapp_number);
        $this->assertTrue($this->recipientBpbd->isWhatsappAvailable());
        $this->assertTrue($this->recipientBpbd->isEmailAvailable());
        $this->assertTrue($this->recipientMpa->isWhatsappAvailable());
        $this->assertFalse($this->recipientMpa->isEmailAvailable()); // Hanya WhatsApp terverifikasi

        // Update penerima dengan nomor lokal 08xxx harus terformat jadi 628xxx
        $response = $this->actingAs($this->admin)->put(route('admin.recipients.update', $this->recipientBpbd), [
            'name' => 'BPBD Kalbar Updated',
            'category' => 'pemerintah',
            'agency_type' => 'BPBD',
            'province' => 'Kalimantan Barat',
            'phone' => '0561-739123',
            'whatsapp_number' => '0896-1234-5678',
            'email' => 'bpbd@kalbarprov.go.id',
            'preferred_channel' => 'whatsapp',
            'verified_channels' => ['email', 'whatsapp'],
            'is_active' => true,
        ]);

        $response->assertRedirect(route('admin.recipients.index'));
        $this->recipientBpbd->refresh();
        $this->assertEquals('6289612345678', $this->recipientBpbd->clean_whatsapp_number);
    }

    /**
     * 3. WhatsApp API Belum Dikonfigurasi: Status 'Belum Dikonfigurasi', pengiriman ditolak secara aman, tanpa mengarang hasil.
     */
    public function test_whatsapp_api_unconfigured_status_and_safety(): void
    {
        Config::set('services.whatsapp.phone_number_id', null);
        Config::set('services.whatsapp.access_token', null);

        $service = app(WhatsAppApiService::class);
        $this->assertFalse($service->isConfigured());

        $status = $service->getConfigurationStatus();
        $this->assertFalse($status['is_configured']);
        $this->assertFalse($status['has_access_token']);

        // Halaman pengaturan menampilkan status Belum Dikonfigurasi
        $viewResponse = $this->actingAs($this->admin)->get(route('admin.settings.whatsapp'));
        $viewResponse->assertStatus(200);
        $viewResponse->assertSee('Belum Dikonfigurasi');

        // Pengiriman via WhatsApp jika belum dikonfigurasi harus mencatat status Gagal
        $response = $this->actingAs($this->admin)->post(route('admin.dispatches.store', $this->report), [
            'recipient_ids' => [$this->recipientBpbd->id],
            'channels' => ['whatsapp'],
            'dispatch_notes' => 'Pengecekan kepulan asap di Rasau Jaya',
        ]);

        $response->assertRedirect(route('admin.reports.show', $this->report));

        $dispatch = ReportDispatch::where('report_id', $this->report->id)
            ->where('recipient_id', $this->recipientBpbd->id)
            ->where('channel', 'whatsapp')
            ->first();

        $this->assertNotNull($dispatch);
        $this->assertEquals('Gagal', $dispatch->status);
        $this->assertStringContainsString('Kredensial WhatsApp Business API belum dikonfigurasi', $dispatch->delivery_error);
    }

    /**
     * 4. Multi-Penerima & Multi-Kanal: Memilih 2 penerima dan 2 kanal (Email & WhatsApp)
     * menghasilkan 4 rekaman disposisi terpisah dengan pelacakan status independen.
     */
    public function test_multi_recipient_and_multi_channel_creates_separate_dispatches(): void
    {
        Mail::fake();
        Http::fake([
            'graph.facebook.com/*' => Http::response([
                'messaging_product' => 'whatsapp',
                'contacts' => [['input' => '6281255556666', 'wa_id' => '6281255556666']],
                'messages' => [['id' => 'wamid.HBgLMTIzNDU2Nzg5MA==']],
            ], 200),
        ]);

        Config::set('services.whatsapp.phone_number_id', '1234567890');
        Config::set('services.whatsapp.access_token', 'fake_valid_token');

        $response = $this->actingAs($this->admin)->post(route('admin.dispatches.store', $this->report), [
            'recipient_ids' => [$this->recipientBpbd->id, $this->recipientMpa->id],
            'channels' => ['email', 'whatsapp'],
            'dispatch_notes' => 'Perintah patroli verifikasi lapangan ke regu terdekat',
            'include_photo' => true,
            'include_reporter_contact' => false,
        ]);

        $response->assertRedirect(route('admin.reports.show', $this->report));

        // 2 penerima x 2 kanal = 4 rekaman disposisi terpisah
        $this->assertCount(4, ReportDispatch::where('report_id', $this->report->id)->get());

        // Verifikasi keberadaan masing-masing record
        $bpbdEmail = ReportDispatch::where('recipient_id', $this->recipientBpbd->id)->where('channel', 'email')->first();
        $bpbdWa = ReportDispatch::where('recipient_id', $this->recipientBpbd->id)->where('channel', 'whatsapp')->first();
        $mpaEmail = ReportDispatch::where('recipient_id', $this->recipientMpa->id)->where('channel', 'email')->first();
        $mpaWa = ReportDispatch::where('recipient_id', $this->recipientMpa->id)->where('channel', 'whatsapp')->first();

        $this->assertNotNull($bpbdEmail);
        $this->assertNotNull($bpbdWa);
        $this->assertNotNull($mpaEmail);
        $this->assertNotNull($mpaWa);

        // Status Email BPBD: Berhasil Dikirim ke Penyedia
        $this->assertEquals('Berhasil Dikirim ke Penyedia', $bpbdEmail->status);
        Mail::assertSent(ReportDispatchMailable::class, 2);

        // Status WhatsApp BPBD & MPA: Diterima API (dengan provider message ID)
        $this->assertEquals('Diterima API', $bpbdWa->status);
        $this->assertEquals('wamid.HBgLMTIzNDU2Nzg5MA==', $bpbdWa->provider_message_id);
    }

    /**
     * 5. Integrasi Email: Penanganan kegagalan exception mailer tanpa merusak flow.
     */
    public function test_email_dispatch_failure_handling(): void
    {
        Mail::shouldReceive('to')
            ->once()
            ->andThrow(new \Exception('Connection could not be established with host smtp.mailtrap.io:587'));

        $response = $this->actingAs($this->admin)->post(route('admin.dispatches.store', $this->report), [
            'recipient_ids' => [$this->recipientBpbd->id],
            'channels' => ['email'],
            'dispatch_notes' => 'Instruksi darurat posko',
        ]);

        $response->assertRedirect(route('admin.reports.show', $this->report));

        $dispatch = ReportDispatch::where('recipient_id', $this->recipientBpbd->id)->where('channel', 'email')->first();
        $this->assertEquals('Gagal Dikirim', $dispatch->status);
        $this->assertStringContainsString('smtp.mailtrap.io', $dispatch->delivery_error);
    }

    /**
     * 6. Integrasi WhatsApp: Penanganan respons error dari Meta Cloud API (400 Bad Request).
     */
    public function test_whatsapp_dispatch_api_error_handling(): void
    {
        Http::fake([
            'graph.facebook.com/*' => Http::response([
                'error' => [
                    'message' => 'Invalid OAuth access token - Cannot parse access token',
                    'type' => 'OAuthException',
                    'code' => 190,
                    'fbtrace_id' => 'A1B2C3D4E5',
                ],
            ], 400),
        ]);

        Config::set('services.whatsapp.phone_number_id', '1234567890');
        Config::set('services.whatsapp.access_token', 'invalid_expired_token');

        $response = $this->actingAs($this->admin)->post(route('admin.dispatches.store', $this->report), [
            'recipient_ids' => [$this->recipientBpbd->id],
            'channels' => ['whatsapp'],
            'dispatch_notes' => 'Pengecekan patroli karhutla',
        ]);

        $response->assertRedirect(route('admin.reports.show', $this->report));

        $dispatch = ReportDispatch::where('recipient_id', $this->recipientBpbd->id)->where('channel', 'whatsapp')->first();
        $this->assertEquals('Gagal', $dispatch->status);
        $this->assertStringContainsString('Respons Meta API (400)', $dispatch->delivery_error);
        $this->assertStringContainsString('OAuth access token', $dispatch->delivery_error);
    }

    /**
     * 7. Fitur Retry Independen: Mengirim ulang kanal yang gagal TANPA mengirim ulang kanal yang berhasil.
     */
    public function test_retry_action_only_retries_failed_channel(): void
    {
        Mail::fake();

        // Buat 1 kanal email yang sudah sukses
        $successEmailDispatch = ReportDispatch::create([
            'report_id' => $this->report->id,
            'recipient_id' => $this->recipientBpbd->id,
            'channel' => 'email',
            'dispatch_notes' => 'Arahan posko',
            'status' => 'Berhasil Dikirim ke Penyedia',
            'reference_id' => 'EML-20261027-OK',
        ]);

        // Buat 1 kanal WhatsApp yang gagal
        $failedWaDispatch = ReportDispatch::create([
            'report_id' => $this->report->id,
            'recipient_id' => $this->recipientBpbd->id,
            'channel' => 'whatsapp',
            'dispatch_notes' => 'Arahan posko',
            'status' => 'Gagal',
            'delivery_error' => 'Koneksi timeout',
            'retry_count' => 0,
        ]);

        // Sekarang kita mock WhatsApp API agar sukses saat di-retry
        Http::fake([
            'graph.facebook.com/*' => Http::response([
                'messages' => [['id' => 'wamid.RETRY_SUCCESS_999']],
            ], 200),
        ]);
        Config::set('services.whatsapp.phone_number_id', '1234567890');
        Config::set('services.whatsapp.access_token', 'valid_token');

        // Admin menekan tombol "Kirim Ulang Kanal Ini" pada $failedWaDispatch
        $response = $this->actingAs($this->admin)->post(route('admin.dispatches.retry', $failedWaDispatch));
        $response->assertSessionHas('success_message');

        $failedWaDispatch->refresh();
        $this->assertEquals('Diterima API', $failedWaDispatch->status);
        $this->assertEquals('wamid.RETRY_SUCCESS_999', $failedWaDispatch->provider_message_id);
        $this->assertEquals(1, $failedWaDispatch->retry_count);

        // Pastikan kanal Email TIDAK disentuh atau dikirim ulang
        Mail::assertNothingSent();
        $successEmailDispatch->refresh();
        $this->assertEquals('Berhasil Dikirim ke Penyedia', $successEmailDispatch->status);
        $this->assertEquals(0, $successEmailDispatch->retry_count);
    }

    /**
     * 8. Webhook Meta: Verifikasi Handshake GET (hub.challenge & hub.verify_token).
     */
    public function test_whatsapp_webhook_get_handshake_verification(): void
    {
        Config::set('services.whatsapp.verify_token', 'token_rahasia_posko_siaga');

        // Handshake valid
        $response = $this->get('/api/v1/whatsapp/webhook?hub_mode=subscribe&hub_verify_token=token_rahasia_posko_siaga&hub_challenge=1158201444');
        $response->assertStatus(200);
        $this->assertEquals('1158201444', $response->getContent());

        // Handshake dengan token salah ditolak 403 Forbidden
        $invalidResponse = $this->get('/api/v1/whatsapp/webhook?hub_mode=subscribe&hub_verify_token=token_salah&hub_challenge=1158201444');
        $invalidResponse->assertStatus(403);
    }

    /**
     * 9. Webhook Meta: Validasi Tanda Tangan HMAC-SHA256 (X-Hub-Signature-256).
     */
    public function test_whatsapp_webhook_post_signature_validation(): void
    {
        $appSecret = 'kunci_rahasia_meta_app';
        Config::set('services.whatsapp.app_secret', $appSecret);

        $payload = json_encode(['entry' => []]);
        $validHash = 'sha256=' . hash_hmac('sha256', $payload, $appSecret);

        // Request dengan tanda tangan valid diterima (200)
        $response = $this->call('POST', '/api/v1/whatsapp/webhook', [], [], [], [
            'HTTP_X_HUB_SIGNATURE_256' => $validHash,
            'CONTENT_TYPE' => 'application/json',
        ], $payload);
        $response->assertStatus(200);

        // Request dengan tanda tangan palsu ditolak 403 Forbidden
        $badResponse = $this->call('POST', '/api/v1/whatsapp/webhook', [], [], [], [
            'HTTP_X_HUB_SIGNATURE_256' => 'sha256=invalid_tampered_signature',
            'CONTENT_TYPE' => 'application/json',
        ], $payload);
        $badResponse->assertStatus(403);
    }

    /**
     * 10. Webhook Meta: Pembaruan Status 'delivered' dan 'read' secara Idempoten tanpa riwayat ganda.
     */
    public function test_whatsapp_webhook_status_processing_and_idempotency(): void
    {
        $messageId = 'wamid.HBgLMTIzNDU2Nzg5MA==';

        $dispatch = ReportDispatch::create([
            'report_id' => $this->report->id,
            'recipient_id' => $this->recipientBpbd->id,
            'channel' => 'whatsapp',
            'dispatch_notes' => 'Arahan posko',
            'status' => 'Diterima API',
            'provider_message_id' => $messageId,
            'reference_id' => 'WA-20261027-TEST',
        ]);

        $webhookPayload = [
            'entry' => [
                [
                    'changes' => [
                        [
                            'field' => 'messages',
                            'value' => [
                                'statuses' => [
                                    [
                                        'id' => $messageId,
                                        'status' => 'delivered',
                                        'timestamp' => '1730001234',
                                        'recipient_id' => '6281255556666',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        // 1. Notifikasi 'delivered' pertama
        $res1 = $this->postJson('/api/v1/whatsapp/webhook', $webhookPayload);
        $res1->assertStatus(200);

        $dispatch->refresh();
        $this->assertEquals('Terkirim ke Perangkat', $dispatch->status);
        $this->assertNotNull($dispatch->delivered_at);
        $this->assertCount(1, $dispatch->histories);

        // 2. Notifikasi duplikat yang sama (Idempotency check)
        $res2 = $this->postJson('/api/v1/whatsapp/webhook', $webhookPayload);
        $res2->assertStatus(200);

        // Pastikan tidak ada penambahan entri riwayat ganda
        $dispatch->refresh();
        $this->assertCount(1, $dispatch->histories);

        // 3. Notifikasi 'read' berikutnya
        $webhookPayload['entry'][0]['changes'][0]['value']['statuses'][0]['status'] = 'read';
        $webhookPayload['entry'][0]['changes'][0]['value']['statuses'][0]['timestamp'] = '1730005678';

        $res3 = $this->postJson('/api/v1/whatsapp/webhook', $webhookPayload);
        $res3->assertStatus(200);

        $dispatch->refresh();
        $this->assertEquals('Dibaca', $dispatch->status);
        $this->assertNotNull($dispatch->read_at);
        $this->assertCount(2, $dispatch->histories);
    }

    /**
     * 11. Perlindungan Privasi & Keamanan: Token tidak bocor ke view/log dan kontak pelapor dirahasiakan jika opsi dimatikan.
     */
    public function test_privacy_and_token_protection(): void
    {
        Config::set('services.whatsapp.phone_number_id', '1234567890');
        Config::set('services.whatsapp.access_token', 'EAAsecretTokenNotToLeak');

        $response = $this->actingAs($this->admin)->get(route('admin.settings.whatsapp'));
        $response->assertStatus(200);

        // Token rahasia TIDAK PERNAH boleh ada di HTML respons browser
        $response->assertDontSee('EAAsecretTokenNotToLeak');

        // Phone number ID dimasking
        $response->assertSee('***');

        // Kontak pelapor tidak dicantumkan di pesan jika include_reporter_contact = false
        $dispatch = ReportDispatch::create([
            'report_id' => $this->report->id,
            'recipient_id' => $this->recipientBpbd->id,
            'channel' => 'whatsapp',
            'dispatch_notes' => 'Catatan posko',
            'include_reporter_contact' => false,
            'status' => 'Menunggu Pengiriman',
        ]);

        $service = app(WhatsAppApiService::class);
        $messageBody = $service->buildMessageBody($dispatch);

        $this->assertStringNotContainsString($this->report->reporter_phone, $messageBody);
        $this->assertStringNotContainsString($this->report->reporter_name, $messageBody);
    }
}
