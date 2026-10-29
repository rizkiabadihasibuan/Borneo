<?php

namespace Tests\Feature;

use App\Models\Report;
use App\Models\ReportStatusHistory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 1. Pengunjung tanpa login ditolak dari dashboard dan route administratif.
     */
    public function test_unauthenticated_visitor_cannot_access_admin_dashboard_or_reports(): void
    {
        $report = Report::create([
            'ticket_number' => 'BS-20261027-GUEST1',
            'access_code_hash' => Hash::make('ACCESS01'),
            'reporter_name' => 'Warga Anonim',
            'reporter_phone' => '08123456789',
            'province' => 'Kalimantan Barat',
            'regency' => 'Kab. Kubu Raya',
            'incident_location' => 'Kecamatan Rasau Jaya',
            'incident_time' => now(),
            'incident_type' => 'Kebakaran Lahan Gambut',
            'description' => 'Laporan uji akses tamu.',
            'status' => 'Menunggu Verifikasi',
        ]);

        $this->get('/admin')->assertRedirect('/admin/login');
        $this->get('/admin/pengaduan')->assertRedirect('/admin/login');
        $this->get("/admin/pengaduan/{$report->id}")->assertRedirect('/admin/login');
        $this->get("/admin/pengaduan/{$report->id}/foto")->assertRedirect('/admin/login');
    }

    /**
     * 2. Pengguna non-admin yang login tidak dapat mengakses fitur administratif (403 Forbidden).
     */
    public function test_non_admin_user_cannot_access_admin_dashboard_and_receives_403(): void
    {
        $nonAdmin = User::create([
            'name' => 'Pengguna Biasa',
            'email' => 'user@example.com',
            'password' => Hash::make('password123'),
            'is_admin' => false,
        ]);

        $response = $this->actingAs($nonAdmin)->get('/admin');
        $response->assertStatus(403);

        $response = $this->actingAs($nonAdmin)->get('/admin/pengaduan');
        $response->assertStatus(403);
    }

    /**
     * 3. Admin dapat login dengan kredensial valid dan melakukan logout.
     */
    public function test_admin_can_login_with_valid_credentials_and_logout(): void
    {
        $admin = User::create([
            'name' => 'Komandan Posko',
            'email' => 'admin@borneosiaga.id',
            'password' => Hash::make('AdminRahasia123'),
            'is_admin' => true,
        ]);

        $loginResponse = $this->post('/admin/login', [
            'email' => 'admin@borneosiaga.id',
            'password' => 'AdminRahasia123',
        ]);

        $loginResponse->assertRedirect('/admin');
        $this->assertAuthenticatedAs($admin);

        $logoutResponse = $this->post('/admin/logout');
        $logoutResponse->assertRedirect('/admin/login');
        $this->assertGuest();
    }

    /**
     * 4. Pengguna non-admin tidak dapat masuk lewat portal admin login.
     */
    public function test_non_admin_cannot_login_through_admin_portal(): void
    {
        User::create([
            'name' => 'Warga Biasa',
            'email' => 'warga@example.com',
            'password' => Hash::make('PasswordWarga123'),
            'is_admin' => false,
        ]);

        $response = $this->post('/admin/login', [
            'email' => 'warga@example.com',
            'password' => 'PasswordWarga123',
        ]);

        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }

    /**
     * 5. Ringkasan dashboard berasal dari data database riil.
     */
    public function test_admin_dashboard_summary_reflects_real_database_counts(): void
    {
        $admin = User::create([
            'name' => 'Admin Analis',
            'email' => 'analis@borneosiaga.id',
            'password' => Hash::make('Analis12345'),
            'is_admin' => true,
        ]);

        Report::create([
            'ticket_number' => 'BS-20261027-STAT1',
            'access_code_hash' => Hash::make('PASS1'),
            'reporter_name' => 'Pelapor 1',
            'reporter_phone' => '08111111111',
            'province' => 'Kalimantan Barat',
            'regency' => 'Kab. Pontianak',
            'incident_location' => 'Desa A',
            'incident_time' => now(),
            'incident_type' => 'Kebakaran Hutan Gambut',
            'description' => 'Asap pekat di kebun.',
            'status' => 'Menunggu Verifikasi',
        ]);

        Report::create([
            'ticket_number' => 'BS-20261027-STAT2',
            'access_code_hash' => Hash::make('PASS2'),
            'reporter_name' => 'Pelapor 2',
            'reporter_phone' => '08222222222',
            'province' => 'Kalimantan Tengah',
            'regency' => 'Kota Palangka Raya',
            'incident_location' => 'Desa B',
            'incident_time' => now(),
            'incident_type' => 'Titik Api Terdeteksi',
            'description' => 'Api membara dekat semak.',
            'status' => 'Dalam Penanganan',
        ]);

        Report::create([
            'ticket_number' => 'BS-20261027-STAT3',
            'access_code_hash' => Hash::make('PASS3'),
            'reporter_name' => 'Pelapor 3',
            'reporter_phone' => '08333333333',
            'province' => 'Kalimantan Timur',
            'regency' => 'Kab. Kutai Kartanegara',
            'incident_location' => 'Desa C',
            'incident_time' => now(),
            'incident_type' => 'Lainnya',
            'description' => 'Padam total setelah dibasahi.',
            'status' => 'Selesai Ditangani',
        ]);

        $response = $this->actingAs($admin)->get('/admin');

        $response->assertStatus(200);
        $response->assertSee('Ringkasan Operasional Posko');
        $response->assertSee('BS-20261027-STAT1');
        $response->assertSee('BS-20261027-STAT2');
        $response->assertSee('BS-20261027-STAT3');
    }

    /**
     * 6. Pencarian, filter status, dan pagination pada daftar pengaduan.
     */
    public function test_admin_can_search_and_filter_reports(): void
    {
        $admin = User::create([
            'name' => 'Admin Filter',
            'email' => 'filter@borneosiaga.id',
            'password' => Hash::make('Filter12345'),
            'is_admin' => true,
        ]);

        Report::create([
            'ticket_number' => 'BS-SPECIAL-TICKET-99',
            'access_code_hash' => Hash::make('PASS1'),
            'reporter_name' => 'Pelapor Khusus',
            'reporter_phone' => '08111111111',
            'province' => 'Kalimantan Barat',
            'regency' => 'Kab. Ketapang',
            'incident_location' => 'Desa Khusus',
            'incident_time' => now(),
            'incident_type' => 'Kebakaran Hutan',
            'description' => 'Laporan target pencarian.',
            'status' => 'Sedang Diverifikasi',
        ]);

        Report::create([
            'ticket_number' => 'BS-OTHER-TICKET-01',
            'access_code_hash' => Hash::make('PASS2'),
            'reporter_name' => 'Pelapor Lain',
            'reporter_phone' => '08222222222',
            'province' => 'Kalimantan Selatan',
            'regency' => 'Kab. Banjar',
            'incident_location' => 'Desa Lain',
            'incident_time' => now(),
            'incident_type' => 'Lainnya',
            'description' => 'Laporan lain.',
            'status' => 'Selesai Ditangani',
        ]);

        // Uji Pencarian Nomor Tiket
        $searchResponse = $this->actingAs($admin)->get('/admin/pengaduan?q=SPECIAL-TICKET');
        $searchResponse->assertStatus(200);
        $searchResponse->assertSee('BS-SPECIAL-TICKET-99');
        $searchResponse->assertDontSee('BS-OTHER-TICKET-01');

        // Uji Filter Status
        $filterResponse = $this->actingAs($admin)->get('/admin/pengaduan?status=Selesai+Ditangani');
        $filterResponse->assertStatus(200);
        $filterResponse->assertSee('BS-OTHER-TICKET-01');
        $filterResponse->assertDontSee('BS-SPECIAL-TICKET-99');
    }

    /**
     * 7. Detail laporan menampilkan identitas pelapor untuk admin berwenang.
     */
    public function test_admin_can_view_full_report_details_including_reporter_identity(): void
    {
        $admin = User::create([
            'name' => 'Admin Detail',
            'email' => 'detail@borneosiaga.id',
            'password' => Hash::make('Detail12345'),
            'is_admin' => true,
        ]);

        $report = Report::create([
            'ticket_number' => 'BS-20261027-DETAIL',
            'access_code_hash' => Hash::make('PASS1234'),
            'reporter_name' => 'Bapak Sudirman Pontianak',
            'reporter_phone' => '081298765432',
            'province' => 'Kalimantan Barat',
            'regency' => 'Kota Pontianak',
            'district' => 'Pontianak Barat',
            'village' => 'Sungai Jawi',
            'incident_location' => 'Jl. Komyos Sudarso Gang Jagung No 12',
            'latitude' => -0.0123,
            'longitude' => 109.3245,
            'incident_time' => now()->subHours(2),
            'incident_type' => 'Kebakaran Lahan Gambut',
            'description' => 'Semak gambut di pekarangan belakang terbakar merambat.',
            'status' => 'Menunggu Verifikasi',
        ]);

        $response = $this->actingAs($admin)->get("/admin/pengaduan/{$report->id}");

        $response->assertStatus(200);
        $response->assertSee('Bapak Sudirman Pontianak');
        $response->assertSee('081298765432');
        $response->assertSee('Sungai Jawi');
        $response->assertSee('Semak gambut di pekarangan belakang terbakar merambat.');
    }

    /**
     * 8. Perubahan status hanya oleh admin, memerlukan catatan, dan tersimpan di riwayat.
     */
    public function test_status_update_requires_notes_updates_report_and_records_history(): void
    {
        $admin = User::create([
            'name' => 'Admin Penangan',
            'email' => 'penangan@borneosiaga.id',
            'password' => Hash::make('Penangan123'),
            'is_admin' => true,
        ]);

        $report = Report::create([
            'ticket_number' => 'BS-20261027-UPDATE',
            'access_code_hash' => Hash::make('PASSUPDATE'),
            'reporter_name' => 'Pelapor Status',
            'reporter_phone' => '08123456789',
            'province' => 'Kalimantan Tengah',
            'regency' => 'Kab. Pulang Pisau',
            'incident_location' => 'Desa Gohong',
            'incident_time' => now(),
            'incident_type' => 'Kebakaran Lahan Gambut',
            'description' => 'Gambut berasap tebal.',
            'status' => 'Menunggu Verifikasi',
        ]);

        // 1. Submit tanpa catatan harus gagal validasi
        $invalidResponse = $this->actingAs($admin)->post("/admin/pengaduan/{$report->id}/status", [
            'status' => 'Sedang Diverifikasi',
            'notes' => '',
        ]);
        $invalidResponse->assertSessionHasErrors(['notes']);

        // 2. Submit dengan status dan catatan valid
        $validResponse = $this->actingAs($admin)->post("/admin/pengaduan/{$report->id}/status", [
            'status' => 'Dalam Penanganan',
            'notes' => 'Regu 2 Manggala Agni dikerahkan membawa pompa apung.',
        ]);

        $validResponse->assertRedirect();
        $this->assertDatabaseHas('reports', [
            'id' => $report->id,
            'status' => 'Dalam Penanganan',
        ]);

        $this->assertDatabaseHas('report_status_histories', [
            'report_id' => $report->id,
            'previous_status' => 'Menunggu Verifikasi',
            'new_status' => 'Dalam Penanganan',
            'notes' => 'Regu 2 Manggala Agni dikerahkan membawa pompa apung.',
            'admin_id' => $admin->id,
        ]);
    }

    /**
     * 9. Foto bukti privat tidak dapat diakses pengguna publik langsung.
     */
    public function test_private_photo_cannot_be_accessed_by_public(): void
    {
        Storage::fake('local');
        $storedPath = 'reports/test_secret_photo.jpg';
        Storage::disk('local')->put($storedPath, 'fake-binary-image-content');

        $report = Report::create([
            'ticket_number' => 'BS-20261027-PHOTO1',
            'access_code_hash' => Hash::make('PASSPHOTO'),
            'reporter_name' => 'Pelapor Foto',
            'reporter_phone' => '08123456789',
            'province' => 'Kalimantan Barat',
            'regency' => 'Kab. Kubu Raya',
            'incident_location' => 'Kec. Rasau Jaya',
            'incident_time' => now(),
            'incident_type' => 'Kebakaran Hutan Gambut',
            'description' => 'Foto bukti api pekat.',
            'photo_path' => $storedPath,
            'status' => 'Menunggu Verifikasi',
        ]);

        // Pengunjung tanpa login ditolak
        $publicResponse = $this->get("/admin/pengaduan/{$report->id}/foto");
        $publicResponse->assertRedirect('/admin/login');

        // Admin yang login dapat mengakses foto
        $admin = User::create([
            'name' => 'Admin Foto',
            'email' => 'foto@borneosiaga.id',
            'password' => Hash::make('Foto12345'),
            'is_admin' => true,
        ]);

        $adminResponse = $this->actingAs($admin)->get("/admin/pengaduan/{$report->id}/foto");
        $adminResponse->assertStatus(200);
    }

    /**
     * 10. Halaman pelacakan publik secara konsisten menampilkan status terbaru dari dashboard admin.
     */
    public function test_tracking_page_reflects_status_updated_by_admin(): void
    {
        $plainCode = 'TRACK889';

        $report = Report::create([
            'ticket_number' => 'BS-20261027-SYNC1',
            'access_code_hash' => Hash::make($plainCode),
            'reporter_name' => 'Pelapor Sinkron',
            'reporter_phone' => '08123456789',
            'province' => 'Kalimantan Barat',
            'regency' => 'Kab. Sambas',
            'incident_location' => 'Desa Sebawi',
            'incident_time' => now(),
            'incident_type' => 'Kebakaran Lahan Gambut',
            'description' => 'Api merambat di lahan semak.',
            'status' => 'Menunggu Verifikasi',
        ]);

        $admin = User::create([
            'name' => 'Admin Sinkron',
            'email' => 'sync@borneosiaga.id',
            'password' => Hash::make('Sync12345'),
            'is_admin' => true,
        ]);

        // Admin mengubah status menjadi "Selesai Ditangani"
        $this->actingAs($admin)->post("/admin/pengaduan/{$report->id}/status", [
            'status' => 'Selesai Ditangani',
            'notes' => 'Pemadaman total telah selesai dilakukan oleh tim gabungan BPBD.',
        ]);

        // Warga melacak laporan tanpa login
        $trackResponse = $this->post('/lacak-pengaduan', [
            'ticket_number' => 'BS-20261027-SYNC1',
            'access_code' => $plainCode,
        ]);

        $trackResponse->assertStatus(200);
        $trackResponse->assertSee('Selesai Ditangani');
        $trackResponse->assertSee('Pemadaman total telah selesai dilakukan oleh tim gabungan BPBD.');
    }
}
