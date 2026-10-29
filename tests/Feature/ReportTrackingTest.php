<?php

namespace Tests\Feature;

use App\Models\Report;
use App\Models\ReportStatusHistory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ReportTrackingTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 1. Test halaman lacak pengaduan dapat diakses publik tanpa login.
     */
    public function test_lacak_pengaduan_page_renders_form(): void
    {
        $response = $this->get('/lacak-pengaduan');
        $response->assertStatus(200);
        $response->assertSee('Penelusuran Tiket Laporan');
        $response->assertSee('ticket_number');
        $response->assertSee('access_code');
        $response->assertSee('Lacak Laporan');
        $response->assertDontSee('Login');
        $response->assertDontSee('Masuk Akun');
    }

    /**
     * 2. Test pelacakan berhasil dengan nomor tiket dan kode akses yang benar.
     * Memastikan data aman ditampilkan dan data pribadi TIDAK BOCOR.
     */
    public function test_tracking_succeeds_with_valid_credentials_and_no_data_leakage(): void
    {
        $plainCode = '8K2P9X4M';
        $secretName = 'Rahasia Pribadi Warga Pontianak';
        $secretPhone = '081299998888';
        $secretPhoto = 'reports/rahasia_privat_12345.jpg';

        $report = Report::create([
            'ticket_number' => 'BS-20261027-TEST1',
            'access_code_hash' => Hash::make($plainCode),
            'reporter_name' => $secretName,
            'reporter_phone' => $secretPhone,
            'province' => 'Kalimantan Barat',
            'regency' => 'Kab. Mempawah',
            'district' => 'Kec. Mempawah Hilir',
            'village' => 'Desa Galang',
            'incident_location' => 'Desa Galang, sekitar 300 meter dari batas parit',
            'latitude' => 0.2500,
            'longitude' => 109.1500,
            'incident_time' => now()->subDay(),
            'incident_type' => 'Kebakaran Lahan Gambut',
            'description' => 'Titik api teramati di semak belukar gambut belakang kebun warga.',
            'photo_path' => $secretPhoto,
            'status' => 'Sedang Diverifikasi',
        ]);

        ReportStatusHistory::create([
            'report_id' => $report->id,
            'previous_status' => 'Menunggu Verifikasi',
            'new_status' => 'Sedang Diverifikasi',
            'notes' => 'Tim verifikasi posko sedang mengonfirmasi koordinat GPS ke regu lapangan.',
        ]);

        $response = $this->post('/lacak-pengaduan', [
            'ticket_number' => 'BS-20261027-TEST1',
            'access_code' => $plainCode,
        ]);

        $response->assertStatus(200);

        // Assert data aman tampil
        $response->assertSee('Laporan Terverifikasi');
        $response->assertSee('BS-20261027-TEST1');
        $response->assertSee('Sedang Diverifikasi');
        $response->assertSee('Desa Galang, sekitar 300 meter dari batas parit');
        $response->assertSee('Kebakaran Lahan Gambut');
        $response->assertSee('Titik api teramati di semak belukar gambut belakang kebun warga.');
        $response->assertSee('Tim verifikasi posko sedang mengonfirmasi koordinat GPS');

        // ASSERT PRIVASI KETAT: Data pribadi TIDAK BOLEH tampil di halaman pelacakan publik
        $response->assertDontSee($secretName);
        $response->assertDontSee($secretPhone);
        $response->assertDontSee($secretPhoto);
        $response->assertDontSee($plainCode);
    }

    /**
     * 3. Test pelacakan gagal dengan kode akses salah.
     * Menggunakan pesan kesalahan umum seragam (anti-enumerasi) dan tidak menampilkan data laporan.
     */
    public function test_tracking_fails_with_invalid_access_code(): void
    {
        Report::create([
            'ticket_number' => 'BS-20261027-TEST2',
            'access_code_hash' => Hash::make('CORRECT1'),
            'reporter_name' => 'Warga Kubu Raya',
            'reporter_phone' => '08123456789',
            'province' => 'Kalimantan Barat',
            'regency' => 'Kab. Kubu Raya',
            'incident_location' => 'Kec. Rasau Jaya Tiga',
            'incident_time' => now()->subHours(3),
            'incident_type' => 'Kebakaran Lahan Gambut',
            'description' => 'Kebakaran lahan gambut pekat.',
            'status' => 'Menunggu Verifikasi',
        ]);

        $response = $this->post('/lacak-pengaduan', [
            'ticket_number' => 'BS-20261027-TEST2',
            'access_code' => 'WRONGCODE',
        ]);

        $response->assertStatus(200);
        $response->assertSee('Nomor tiket atau kode akses rahasia tidak valid');
        $response->assertDontSee('Laporan Terverifikasi');
        $response->assertDontSee('Kec. Rasau Jaya Tiga');
        $response->assertDontSee('Kebakaran lahan gambut pekat.');
    }

    /**
     * 4. Test pelacakan gagal dengan tiket yang tidak terdaftar.
     * Pesan kesalahan harus identik dengan kegagalan kode akses salah (anti-enumerasi).
     */
    public function test_tracking_fails_with_nonexistent_ticket(): void
    {
        $response = $this->post('/lacak-pengaduan', [
            'ticket_number' => 'BS-99999999-NOTFOUND',
            'access_code' => 'ANYCODE1',
        ]);

        $response->assertStatus(200);
        $response->assertSee('Nomor tiket atau kode akses rahasia tidak valid');
        $response->assertDontSee('Laporan Terverifikasi');
    }

    /**
     * 5. Test validasi input kosong pada form pelacakan.
     */
    public function test_empty_inputs_trigger_validation_errors(): void
    {
        $response = $this->post('/lacak-pengaduan', [
            'ticket_number' => '',
            'access_code' => '',
        ]);

        $response->assertSessionHasErrors(['ticket_number', 'access_code']);
    }

    /**
     * 6. Test kode akses tidak bocor ke URL atau parameter route.
     */
    public function test_access_code_is_not_exposed_in_url(): void
    {
        $plainCode = 'SECRET88';

        $report = Report::create([
            'ticket_number' => 'BS-20261027-SAFE1',
            'access_code_hash' => Hash::make($plainCode),
            'reporter_name' => 'Warga Aman',
            'reporter_phone' => '08123456789',
            'province' => 'Kalimantan Barat',
            'regency' => 'Kota Pontianak',
            'incident_location' => 'Pontianak Selatan',
            'incident_time' => now()->subHour(),
            'incident_type' => 'Lainnya',
            'description' => 'Deskripsi laporan uji keamanan URL.',
            'status' => 'Menunggu Verifikasi',
        ]);

        $response = $this->post('/lacak-pengaduan', [
            'ticket_number' => 'BS-20261027-SAFE1',
            'access_code' => $plainCode,
        ]);

        $response->assertStatus(200);
        $this->assertStringNotContainsString('access_code=' . $plainCode, $response->headers->get('Location') ?? '');
    }

    /**
     * 7. Test riwayat status kronologis menampilkan beberapa tahap penanganan.
     */
    public function test_status_history_chronological_rendering(): void
    {
        $plainCode = 'CHRONO99';

        $report = Report::create([
            'ticket_number' => 'BS-20261027-STAGE1',
            'access_code_hash' => Hash::make($plainCode),
            'reporter_name' => 'Pelapor Tahapan',
            'reporter_phone' => '08123456789',
            'province' => 'Kalimantan Tengah',
            'regency' => 'Kota Palangka Raya',
            'incident_location' => 'Kelurahan Petuk Katimpun',
            'incident_time' => now()->subDays(2),
            'incident_type' => 'Kebakaran Lahan Gambut',
            'description' => 'Api gambut membara di bawah tanah dekat parit primer.',
            'status' => 'Selesai Ditangani',
        ]);

        ReportStatusHistory::create([
            'report_id' => $report->id,
            'previous_status' => null,
            'new_status' => 'Menunggu Verifikasi',
            'notes' => 'Tahap 1: Laporan masuk ke sistem antrean.',
            'created_at' => now()->subDays(2),
        ]);

        ReportStatusHistory::create([
            'report_id' => $report->id,
            'previous_status' => 'Menunggu Verifikasi',
            'new_status' => 'Sedang Diverifikasi',
            'notes' => 'Tahap 2: Citra satelit dan lokasi telah dikonfirmasi.',
            'created_at' => now()->subDay(),
        ]);

        ReportStatusHistory::create([
            'report_id' => $report->id,
            'previous_status' => 'Sedang Diverifikasi',
            'new_status' => 'Dalam Penanganan',
            'notes' => 'Tahap 3: Regu Manggala Agni melakukan pembasahan gambut.',
            'created_at' => now()->subHours(10),
        ]);

        ReportStatusHistory::create([
            'report_id' => $report->id,
            'previous_status' => 'Dalam Penanganan',
            'new_status' => 'Selesai Ditangani',
            'notes' => 'Tahap 4: Bara api padam total dan pendinginan selesai.',
            'created_at' => now()->subHour(),
        ]);

        $response = $this->post('/lacak-pengaduan', [
            'ticket_number' => 'BS-20261027-STAGE1',
            'access_code' => $plainCode,
        ]);

        $response->assertStatus(200);
        $response->assertSee('Selesai Ditangani');
        $response->assertSee('Tahap 1: Laporan masuk ke sistem antrean.');
        $response->assertSee('Tahap 2: Citra satelit dan lokasi telah dikonfirmasi.');
        $response->assertSee('Tahap 3: Regu Manggala Agni melakukan pembasahan gambut.');
        $response->assertSee('Tahap 4: Bara api padam total dan pendinginan selesai.');
    }

    /**
     * 8. Test jika riwayat belum tersedia, menampilkan pesan fallback yang jelas.
     */
    public function test_empty_status_history_fallback(): void
    {
        $plainCode = 'NOHIST11';

        Report::create([
            'ticket_number' => 'BS-20261027-NOHIST',
            'access_code_hash' => Hash::make($plainCode),
            'reporter_name' => 'Pelapor Baru',
            'reporter_phone' => '08123456789',
            'province' => 'Kalimantan Selatan',
            'regency' => 'Kab. Banjar',
            'incident_location' => 'Kecamatan Gambut',
            'incident_time' => now()->subMinutes(15),
            'incident_type' => 'Kebakaran Lahan Gambut',
            'description' => 'Laporan baru masuk belum ada riwayat status.',
            'status' => 'Menunggu Verifikasi',
        ]);

        $response = $this->post('/lacak-pengaduan', [
            'ticket_number' => 'BS-20261027-NOHIST',
            'access_code' => $plainCode,
        ]);

        $response->assertStatus(200);
        $response->assertSee('Riwayat tindak lanjut belum tercatat');
    }

    /**
     * 9. Test rate limiting pada endpoint pelacakan (throttle:20,1).
     */
    public function test_tracking_endpoint_rate_limiting(): void
    {
        for ($i = 0; $i < 20; $i++) {
            $this->post('/lacak-pengaduan', [
                'ticket_number' => 'BS-20261027-RATELIMIT',
                'access_code' => 'TESTCODE',
            ]);
        }

        // Request ke-21 harus terkena HTTP 429 Too Many Requests
        $response = $this->post('/lacak-pengaduan', [
            'ticket_number' => 'BS-20261027-RATELIMIT',
            'access_code' => 'TESTCODE',
        ]);

        $response->assertStatus(429);
    }
}
