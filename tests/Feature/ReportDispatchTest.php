<?php

namespace Tests\Feature;

use App\Mail\ReportDispatchMailable;
use App\Models\Report;
use App\Models\ReportDispatch;
use App\Models\ReportDispatchHistory;
use App\Models\ReportRecipient;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class ReportDispatchTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $nonAdmin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Admin Posko Uji',
            'email' => 'admin.uji@borneosiaga.id',
            'password' => Hash::make('AdminUji123'),
            'is_admin' => true,
        ]);

        $this->nonAdmin = User::create([
            'name' => 'Warga Biasa',
            'email' => 'warga.uji@example.com',
            'password' => Hash::make('WargaUji123'),
            'is_admin' => false,
        ]);
    }

    /**
     * 1. Pengunjung tanpa login dan non-admin ditolak dari fitur direktori penerima & disposisi.
     */
    public function test_unauthenticated_and_non_admin_are_denied_access(): void
    {
        $recipient = ReportRecipient::create([
            'name' => 'BPBD Kubu Raya',
            'category' => 'pemerintah',
            'agency_type' => 'BPBD',
            'province' => 'Kalimantan Barat',
            'regency' => 'Kab. Kubu Raya',
            'phone' => '08123456789',
            'email' => 'bpbd@kuburaya.go.id',
            'preferred_channel' => 'email',
            'is_active' => true,
        ]);

        $report = Report::create([
            'ticket_number' => 'BS-20261027-DISP1',
            'access_code_hash' => Hash::make('CODE1234'),
            'reporter_name' => 'Pelapor Uji',
            'reporter_phone' => '081299998888',
            'province' => 'Kalimantan Barat',
            'regency' => 'Kab. Kubu Raya',
            'incident_location' => 'Kec. Rasau Jaya',
            'incident_time' => now(),
            'incident_type' => 'Kebakaran Lahan Gambut',
            'description' => 'Asap pekat di lahan gambut.',
            'status' => 'Sedang Diverifikasi',
        ]);

        // Tamu dialihkan ke login
        $this->get('/admin/penerima')->assertRedirect('/admin/login');
        $this->get('/admin/penerima/create')->assertRedirect('/admin/login');
        $this->get("/admin/pengaduan/{$report->id}/disposisi")->assertRedirect('/admin/login');

        // Non-admin ditolak dengan 403 Forbidden
        $this->actingAs($this->nonAdmin)->get('/admin/penerima')->assertStatus(403);
        $this->actingAs($this->nonAdmin)->get("/admin/pengaduan/{$report->id}/disposisi")->assertStatus(403);
    }

    /**
     * 2. Admin dapat membuat, melihat, mengedit, dan menonaktifkan penerima di direktori.
     */
    public function test_admin_can_manage_recipient_directory(): void
    {
        // 1. Tambah Penerima Baru
        $createResponse = $this->actingAs($this->admin)->post('/admin/penerima', [
            'name' => 'Posko Manggala Agni Daops Ketapang',
            'category' => 'pemerintah',
            'agency_type' => 'Manggala Agni (KLHK)',
            'province' => 'Kalimantan Barat',
            'regency' => 'Kab. Ketapang',
            'contact_person' => 'Kadaops Ketapang',
            'phone' => '081122334455',
            'email' => 'daops.ketapang@klhk.go.id',
            'address' => 'Jl. Lingkar Luar Ketapang',
            'preferred_channel' => 'email',
            'is_active' => '1',
        ]);

        $createResponse->assertRedirect('/admin/penerima');
        $this->assertDatabaseHas('report_recipients', [
            'name' => 'Posko Manggala Agni Daops Ketapang',
            'category' => 'pemerintah',
            'is_active' => true,
        ]);

        $recipient = ReportRecipient::where('email', 'daops.ketapang@klhk.go.id')->firstOrFail();

        // 2. Edit Penerima
        $updateResponse = $this->actingAs($this->admin)->put("/admin/penerima/{$recipient->id}", [
            'name' => 'Posko Manggala Agni Daops Ketapang Update',
            'category' => 'pemerintah',
            'agency_type' => 'Manggala Agni (KLHK)',
            'province' => 'Kalimantan Barat',
            'regency' => 'Kab. Ketapang',
            'contact_person' => 'Kadaops Ketapang Baru',
            'phone' => '081199998888',
            'email' => 'daops.ketapang@klhk.go.id',
            'preferred_channel' => 'email',
            'is_active' => '1',
        ]);

        $updateResponse->assertRedirect('/admin/penerima');
        $this->assertDatabaseHas('report_recipients', [
            'id' => $recipient->id,
            'name' => 'Posko Manggala Agni Daops Ketapang Update',
        ]);

        // 3. Toggle Status Aktif / Nonaktif
        $this->actingAs($this->admin)->post("/admin/penerima/{$recipient->id}/toggle");
        $this->assertDatabaseHas('report_recipients', [
            'id' => $recipient->id,
            'is_active' => false,
        ]);
    }

    /**
     * 3. Disposisi laporan berhasil dikirim via email nyata/mock dan tercatat di riwayat audit.
     */
    public function test_report_dispatch_via_email_success_and_recorded_in_history(): void
    {
        Mail::fake();

        $recipient = ReportRecipient::create([
            'name' => 'BPBD Pontianak',
            'category' => 'pemerintah',
            'agency_type' => 'BPBD',
            'province' => 'Kalimantan Barat',
            'regency' => 'Kota Pontianak',
            'phone' => '08123456789',
            'email' => 'bpbd@pontianak.go.id',
            'preferred_channel' => 'email',
            'is_active' => true,
        ]);

        $report = Report::create([
            'ticket_number' => 'BS-20261027-DISP2',
            'access_code_hash' => Hash::make('CODE1234'),
            'reporter_name' => 'Warga Pontianak',
            'reporter_phone' => '081299998888',
            'province' => 'Kalimantan Barat',
            'regency' => 'Kota Pontianak',
            'incident_location' => 'Kec. Pontianak Selatan',
            'incident_time' => now(),
            'incident_type' => 'Kebakaran Lahan Gambut',
            'description' => 'Titik api teramati di parit primer.',
            'status' => 'Sedang Diverifikasi',
        ]);

        $response = $this->actingAs($this->admin)->post("/admin/pengaduan/{$report->id}/disposisi", [
            'recipient_id' => $recipient->id,
            'channel' => 'email',
            'dispatch_notes' => 'Segera terjunkan regu pemadam api ke lokasi parit primer.',
            'include_photo' => 0,
            'include_reporter_contact' => 0,
        ]);

        $response->assertRedirect("/admin/pengaduan/{$report->id}");

        // Verifikasi Email Terkirim
        Mail::assertSent(ReportDispatchMailable::class, function ($mail) use ($recipient) {
            return $mail->hasTo($recipient->email);
        });

        // Verifikasi Catatan Disposisi
        $this->assertDatabaseHas('report_dispatches', [
            'report_id' => $report->id,
            'recipient_id' => $recipient->id,
            'admin_id' => $this->admin->id,
            'channel' => 'email',
            'status' => 'Berhasil Dikirim ke Penyedia',
            'recipient_status' => 'Menunggu Konfirmasi Penerima',
            'include_reporter_contact' => false,
        ]);

        // Verifikasi Jejak Audit Riwayat Disposisi
        $dispatch = ReportDispatch::where('report_id', $report->id)->firstOrFail();
        $this->assertDatabaseHas('report_dispatch_histories', [
            'report_dispatch_id' => $dispatch->id,
            'admin_id' => $this->admin->id,
            'new_status' => 'Berhasil Dikirim ke Penyedia',
        ]);
    }

    /**
     * 4. Penerusan ditolak jika penerima berstatus nonaktif atau tidak valid.
     */
    public function test_dispatch_fails_if_recipient_is_inactive(): void
    {
        $inactiveRecipient = ReportRecipient::create([
            'name' => 'MPA Nonaktif',
            'category' => 'komunitas_mpa',
            'agency_type' => 'MPA',
            'province' => 'Kalimantan Barat',
            'phone' => '08123456789',
            'preferred_channel' => 'manual',
            'is_active' => false,
        ]);

        $report = Report::create([
            'ticket_number' => 'BS-20261027-DISP3',
            'access_code_hash' => Hash::make('CODE1234'),
            'reporter_name' => 'Warga Uji',
            'reporter_phone' => '081299998888',
            'province' => 'Kalimantan Barat',
            'regency' => 'Kab. Mempawah',
            'incident_location' => 'Desa Peniraman',
            'incident_time' => now(),
            'incident_type' => 'Kebakaran Semak',
            'description' => 'Api membakar semak.',
            'status' => 'Menunggu Verifikasi',
        ]);

        $response = $this->actingAs($this->admin)->post("/admin/pengaduan/{$report->id}/disposisi", [
            'recipient_id' => $inactiveRecipient->id,
            'channel' => 'manual',
            'dispatch_notes' => 'Catatan uji ke penerima nonaktif.',
        ]);

        $response->assertSessionHasErrors(['recipient_id']);
        $this->assertDatabaseMissing('report_dispatches', [
            'report_id' => $report->id,
        ]);
    }

    /**
     * 5. Perlindungan Privasi: Kontak pelapor dan foto dilindungi sesuai konfigurasi disposisi.
     */
    public function test_privacy_protection_and_signed_photo_route(): void
    {
        Storage::fake('local');
        $storedPhoto = 'reports/rahasia_gambut_123.jpg';
        Storage::disk('local')->put($storedPhoto, 'fake-binary-image');

        $recipient = ReportRecipient::create([
            'name' => 'Regu MPA Harapan Maju',
            'category' => 'komunitas_mpa',
            'agency_type' => 'MPA',
            'province' => 'Kalimantan Barat',
            'phone' => '08123456789',
            'email' => 'mpa@desa.id',
            'preferred_channel' => 'manual',
            'is_active' => true,
        ]);

        $report = Report::create([
            'ticket_number' => 'BS-20261027-PRIVAT1',
            'access_code_hash' => Hash::make('CODE1234'),
            'reporter_name' => 'Bapak Warga Privasi',
            'reporter_phone' => '089988776655',
            'province' => 'Kalimantan Barat',
            'regency' => 'Kab. Sambas',
            'incident_location' => 'Desa Sebawi',
            'incident_time' => now(),
            'incident_type' => 'Kebakaran Hutan Gambut',
            'description' => 'Api mendekati pemukiman.',
            'photo_path' => $storedPhoto,
            'status' => 'Sedang Diverifikasi',
        ]);

        // Disposisi dengan foto diizinkan (Signed URL) tetapi kontak pelapor TIDAK diizinkan
        $this->actingAs($this->admin)->post("/admin/pengaduan/{$report->id}/disposisi", [
            'recipient_id' => $recipient->id,
            'channel' => 'manual',
            'dispatch_notes' => 'Tolong prioritaskan pendinginan lahan dekat permukiman.',
            'include_photo' => 1,
            'include_reporter_contact' => 0,
        ]);

        $dispatch = ReportDispatch::where('report_id', $report->id)->firstOrFail();

        // 1. Verifikasi signed URL ter-generate dengan aman
        $secureUrl = $dispatch->secure_photo_url;
        $this->assertNotNull($secureUrl);

        // 2. Akses foto via URL bertanda tangan valid berhasil (200)
        $validSignedResponse = $this->get($secureUrl);
        $validSignedResponse->assertStatus(200);

        // 3. Akses tanpa tanda tangan valid harus ditolak (403 Forbidden)
        $tamperedUrl = url("/disposisi-foto/{$dispatch->id}?signature=invalid");
        $invalidResponse = $this->get($tamperedUrl);
        $invalidResponse->assertStatus(403);
    }

    /**
     * 6. Pembaruan status tindak lanjut oleh penerima tercatat dan tidak merusak status pengaduan masyarakat.
     */
    public function test_recipient_followup_status_update_does_not_override_report_status(): void
    {
        $recipient = ReportRecipient::create([
            'name' => 'Manggala Agni Daops Pontianak',
            'category' => 'pemerintah',
            'agency_type' => 'Manggala Agni',
            'province' => 'Kalimantan Barat',
            'phone' => '08123456789',
            'preferred_channel' => 'manual',
            'is_active' => true,
        ]);

        $report = Report::create([
            'ticket_number' => 'BS-20261027-STAGE1',
            'access_code_hash' => Hash::make('CODE1234'),
            'reporter_name' => 'Warga Status',
            'reporter_phone' => '081299998888',
            'province' => 'Kalimantan Barat',
            'regency' => 'Kab. Kubu Raya',
            'incident_location' => 'Desa Rasau Tiga',
            'incident_time' => now(),
            'incident_type' => 'Kebakaran Gambut',
            'description' => 'Api membara di gambut.',
            'status' => 'Sedang Diverifikasi', // Status utama pengaduan
        ]);

        $dispatch = ReportDispatch::create([
            'report_id' => $report->id,
            'recipient_id' => $recipient->id,
            'admin_id' => $this->admin->id,
            'channel' => 'manual',
            'dispatch_notes' => 'Mohon regu 1 bergerak.',
            'status' => 'Berhasil Dikirim',
            'recipient_status' => 'Menunggu Konfirmasi Penerima',
        ]);

        // Admin mencatat respon penerima menjadi "Sedang Ditindaklanjuti"
        $response = $this->actingAs($this->admin)->post("/admin/disposisi/{$dispatch->id}/status", [
            'recipient_status' => 'Sedang Ditindaklanjuti',
            'recipient_feedback' => 'Regu Daops sedang mengoperasikan mesin pompa apung di titik api.',
        ]);

        $response->assertRedirect();

        // 1. Status tindak lanjut disposisi terupdate
        $this->assertDatabaseHas('report_dispatches', [
            'id' => $dispatch->id,
            'recipient_status' => 'Sedang Ditindaklanjuti',
            'recipient_feedback' => 'Regu Daops sedang mengoperasikan mesin pompa apung di titik api.',
        ]);

        // 2. Status utama pengaduan masyarakat TIDAK BOLEH berubah otomatis jadi "Selesai Ditangani"
        $report->refresh();
        $this->assertEquals('Sedang Diverifikasi', $report->status);
    }

    /**
     * 7. Halaman pelacakan publik menampilkan koordinasi lapangan secara umum tanpa membocorkan privasi.
     */
    public function test_tracking_page_shows_general_coordination_without_private_leakage(): void
    {
        $plainCode = 'TRACKMPA9';

        $recipient = ReportRecipient::create([
            'name' => 'Posko Gabungan BPBD & MPA',
            'category' => 'pemerintah',
            'agency_type' => 'BPBD',
            'province' => 'Kalimantan Barat',
            'regency' => 'Kab. Landak',
            'contact_person' => 'Rahasia Internal Petugas',
            'phone' => '081199991111',
            'email' => 'internal.rahasia@landak.go.id',
            'preferred_channel' => 'manual',
            'is_active' => true,
        ]);

        $report = Report::create([
            'ticket_number' => 'BS-20261027-TRACKDISP',
            'access_code_hash' => Hash::make($plainCode),
            'reporter_name' => 'Warga Pelacak Aman',
            'reporter_phone' => '087788990011',
            'province' => 'Kalimantan Barat',
            'regency' => 'Kab. Landak',
            'incident_location' => 'Kecamatan Mandor',
            'incident_time' => now(),
            'incident_type' => 'Kebakaran Lahan Gambut',
            'description' => 'Api membakar semak belukar gambut Mandor.',
            'status' => 'Dalam Penanganan',
        ]);

        ReportDispatch::create([
            'report_id' => $report->id,
            'recipient_id' => $recipient->id,
            'admin_id' => $this->admin->id,
            'channel' => 'manual',
            'dispatch_notes' => 'Catatan operasional internal posko.',
            'status' => 'Berhasil Dikirim',
            'recipient_status' => 'Sedang Ditindaklanjuti',
        ]);

        // Warga melacak tanpa login
        $trackResponse = $this->post('/lacak-pengaduan', [
            'ticket_number' => 'BS-20261027-TRACKDISP',
            'access_code' => $plainCode,
        ]);

        $trackResponse->assertStatus(200);

        // Informasi umum koordinasi tampil
        $trackResponse->assertSee('Koordinasi Lintas Instansi');
        $trackResponse->assertSee('Sedang Ditindaklanjuti');
        $trackResponse->assertSee('Kab. Landak');

        // STRICT PRIVACY: Kontak privat dan catatan internal tidak boleh bocor ke publik
        $trackResponse->assertDontSee('Rahasia Internal Petugas');
        $trackResponse->assertDontSee('081199991111');
        $trackResponse->assertDontSee('internal.rahasia@landak.go.id');
        $trackResponse->assertDontSee('Catatan operasional internal posko.');
    }
}
