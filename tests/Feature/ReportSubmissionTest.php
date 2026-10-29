<?php

namespace Tests\Feature;

use App\Models\Report;
use App\Models\ReportStatusHistory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ReportSubmissionTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test form pengaduan dapat diakses via GET dan merender semua field yang dipersyaratkan.
     */
    public function test_pengaduan_page_renders_form(): void
    {
        $response = $this->get('/pengaduan');
        $response->assertStatus(200);
        $response->assertSee('Formulir Laporan Titik Api');
        $response->assertSee('reporter_name');
        $response->assertSee('reporter_phone');
        $response->assertSee('province');
        $response->assertSee('regency');
        $response->assertSee('district');
        $response->assertSee('village');
        $response->assertSee('incident_location');
        $response->assertSee('incident_time');
        $response->assertSee('incident_type');
        $response->assertSee('description');
        $response->assertSee('photo');
    }

    /**
     * Test pengiriman laporan valid menghasilkan nomor tiket unik, hashing kode akses, dan status 'Menunggu Verifikasi'.
     */
    public function test_valid_report_is_saved_with_hash_and_history(): void
    {
        Storage::fake('local');

        $pngContent = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==');
        $photo = UploadedFile::fake()->createWithContent('bukti_kebakaran.png', $pngContent);

        $payload = [
            'reporter_name' => 'Budi Santoso',
            'reporter_phone' => '081234567890',
            'province' => 'Kalimantan Barat',
            'regency' => 'Kab. Kubu Raya',
            'district' => 'Kec. Rasau Jaya',
            'village' => 'Desa Rasau Jaya Tiga',
            'incident_location' => 'Desa Limbung, sekitar 300 meter dari kanal gambut',
            'latitude' => -0.1234567,
            'longitude' => 109.3456789,
            'incident_time' => now()->subHours(2)->format('Y-m-d\TH:i'),
            'incident_type' => 'Kebakaran Lahan Gambut',
            'description' => 'Terlihat kepulan asap pekat setinggi 20 meter dari area gambut semak belukar dekat batas kebun warga.',
            'photo' => $photo,
        ];

        $response = $this->post('/pengaduan', $payload);

        // Assert redirect kembali ke pengaduan dengan session flash
        $response->assertRedirect('/pengaduan');
        $response->assertSessionHas('report_created_success', true);
        $response->assertSessionHas('ticket_number');
        $response->assertSessionHas('access_code');
        $response->assertSessionHas('report_summary');

        $ticketNumber = session('ticket_number');
        $plainAccessCode = session('access_code');

        // Pastikan format tiket benar (BS-YYYYMMDD-XXXXX)
        $this->assertStringStartsWith('BS-', $ticketNumber);

        // Ambil laporan dari database
        $report = Report::where('ticket_number', $ticketNumber)->first();
        $this->assertNotNull($report);
        $this->assertEquals('Menunggu Verifikasi', $report->status);
        $this->assertEquals('Budi Santoso', $report->reporter_name);
        $this->assertEquals('081234567890', $report->reporter_phone);
        $this->assertEquals('Kalimantan Barat', $report->province);
        $this->assertEquals('Kab. Kubu Raya', $report->regency);
        $this->assertEquals('Kebakaran Lahan Gambut', $report->incident_type);

        // Pastikan kode akses TIDAK disimpan dalam bentuk plain text
        $this->assertNotEquals($plainAccessCode, $report->access_code_hash);
        $this->assertTrue(Hash::check($plainAccessCode, $report->access_code_hash));
        $this->assertTrue($report->verifyAccessCode($plainAccessCode));

        // Pastikan riwayat status awal terbuat dengan status 'Menunggu Verifikasi'
        $this->assertDatabaseHas('report_status_histories', [
            'report_id' => $report->id,
            'previous_status' => null,
            'new_status' => 'Menunggu Verifikasi',
        ]);

        // Pastikan foto tersimpan di private storage dengan nama acak (bukan nama asli client)
        $this->assertNotNull($report->photo_path);
        $this->assertStringNotContainsString('bukti_kebakaran.png', $report->photo_path);
        Storage::disk('local')->assertExists($report->photo_path);
    }

    /**
     * Test nomor tiket unik ketika terjadi multiple submission.
     */
    public function test_unique_ticket_number_generation(): void
    {
        $payload1 = [
            'reporter_name' => 'Warga Pertama',
            'reporter_phone' => '08111111111',
            'province' => 'Kalimantan Barat',
            'regency' => 'Kota Pontianak',
            'incident_location' => 'Kec. Pontianak Selatan',
            'incident_time' => now()->subHour()->format('Y-m-d\TH:i'),
            'incident_type' => 'Kebakaran Semak Belukar / Alang-alang',
            'description' => 'Asap tipis terlihat dari lahan tidur tak terurus di tepi jalan.',
        ];

        $payload2 = [
            'reporter_name' => 'Warga Kedua',
            'reporter_phone' => '08222222222',
            'province' => 'Kalimantan Tengah',
            'regency' => 'Kota Palangka Raya',
            'incident_location' => 'Jalan Tjilik Riwut Km 8',
            'incident_time' => now()->subMinutes(30)->format('Y-m-d\TH:i'),
            'incident_type' => 'Kebakaran Lahan Gambut',
            'description' => 'Kebakaran gambut merembet dari lahan kosong mendekati pagar pemukiman.',
        ];

        $res1 = $this->post('/pengaduan', $payload1);
        $res1->assertRedirect('/pengaduan');
        $ticket1 = session('ticket_number');

        $res2 = $this->post('/pengaduan', $payload2);
        $res2->assertRedirect('/pengaduan');
        $ticket2 = session('ticket_number');

        $this->assertNotEquals($ticket1, $ticket2);
        $this->assertDatabaseCount('reports', 2);
    }

    /**
     * Test penolakan input tidak valid (nama kosong, kontak tidak valid, lokasi kosong, waktu masa depan, deskripsi terlalu pendek).
     */
    public function test_invalid_input_is_rejected(): void
    {
        $payload = [
            'reporter_name' => '', // kosong
            'reporter_phone' => 'bukan-nomor', // tidak valid
            'province' => '', // kosong
            'regency' => '', // kosong
            'incident_location' => '', // kosong
            'incident_time' => now()->addDays(2)->format('Y-m-d\TH:i'), // waktu masa depan
            'incident_type' => '', // kosong
            'description' => 'api', // terlalu pendek (<15 chars)
        ];

        $response = $this->post('/pengaduan', $payload);

        $response->assertSessionHasErrors([
            'reporter_name',
            'reporter_phone',
            'province',
            'regency',
            'incident_location',
            'incident_time',
            'incident_type',
            'description',
        ]);
        $this->assertDatabaseCount('reports', 0);
    }

    /**
     * Test penolakan jenis file yang tidak diizinkan (misalnya berkas PDF atau teks).
     */
    public function test_disallowed_file_type_is_rejected(): void
    {
        Storage::fake('local');

        $fakePdf = UploadedFile::fake()->create('laporan.pdf', 100, 'application/pdf');

        $payload = [
            'reporter_name' => 'Pelapor Uji',
            'reporter_phone' => '081234567890',
            'province' => 'Kalimantan Barat',
            'regency' => 'Kab. Sambas',
            'incident_location' => 'Kecamatan Tebas, area perkebunan sawit rakyat',
            'incident_time' => now()->subHour()->format('Y-m-d\TH:i'),
            'incident_type' => 'Kebakaran Lahan Perkebunan',
            'description' => 'Api membakar sisa tebasan pelepah sawit kering dan berisiko merambat.',
            'photo' => $fakePdf,
        ];

        $response = $this->post('/pengaduan', $payload);

        $response->assertSessionHasErrors(['photo']);
        $this->assertDatabaseCount('reports', 0);
    }
}
