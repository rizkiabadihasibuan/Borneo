<?php

namespace Tests\Feature;

use App\Models\Report;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class RegionAreaApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test endpoint daftar provinsi menghasilkan status 200 dan format JSON yang valid.
     */
    public function test_provinces_endpoint_returns_json_list(): void
    {
        $response = $this->getJson(route('api.wilayah.provinces'));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => ['id', 'name']
        ]);

        $data = $response->json();
        $this->assertNotEmpty($data);

        // Pastikan nama provinsi diformat dengan baik
        $kalbar = collect($data)->firstWhere('id', '61');
        $this->assertNotNull($kalbar);
        $this->assertEquals('Kalimantan Barat', $kalbar['name']);
    }

    /**
     * Test endpoint kabupaten/kota mengembalikan daftar kab/kota berdasarkan kode provinsi.
     */
    public function test_regencies_endpoint_returns_data_for_province(): void
    {
        $response = $this->getJson(route('api.wilayah.regencies', ['provinceCode' => '61']));

        $response->assertStatus(200);
        $data = $response->json();
        $this->assertNotEmpty($data);

        $sambas = collect($data)->firstWhere('id', '6101');
        $this->assertNotNull($sambas);
        $this->assertEquals('Kab. Sambas', $sambas['name']);
    }

    /**
     * Test endpoint menolak kode provinsi berformat tidak valid.
     */
    public function test_regencies_endpoint_validates_code(): void
    {
        $response = $this->getJson(route('api.wilayah.regencies', ['provinceCode' => 'invalid-code!']));
        $response->assertStatus(400);
    }

    /**
     * Test endpoint kecamatan mengembalikan data untuk kode kabupaten valid.
     */
    public function test_districts_endpoint_returns_data_for_regency(): void
    {
        $response = $this->getJson(route('api.wilayah.districts', ['regencyCode' => '6101']));

        $response->assertStatus(200);
        $data = $response->json();
        $this->assertNotEmpty($data);
    }

    /**
     * Test endpoint desa/kelurahan mengembalikan data untuk kode kecamatan valid.
     */
    public function test_villages_endpoint_returns_data_for_district(): void
    {
        $response = $this->getJson(route('api.wilayah.villages', ['districtCode' => '6101010']));

        $response->assertStatus(200);
        $data = $response->json();
        $this->assertNotEmpty($data);
    }

    /**
     * Test pengiriman formulir dengan kode administratif tersimpan lengkap di database.
     */
    public function test_report_submission_stores_administrative_codes_and_names(): void
    {
        $payload = [
            'reporter_name' => 'Ahmad Dahlan',
            'reporter_phone' => '081298765432',
            'province_code' => '61',
            'province_name' => 'Kalimantan Barat',
            'province' => 'Kalimantan Barat',
            'regency_code' => '6101',
            'regency_name' => 'Kab. Sambas',
            'regency' => 'Kab. Sambas',
            'district_code' => '6101010',
            'district_name' => 'Selakau',
            'district' => 'Selakau',
            'village_code' => '6101010001',
            'village_name' => 'Semelagi Besar',
            'village' => 'Semelagi Besar',
            'location_detail' => 'Dusun Sungai Daun, sekitar 200m dari kanal primer',
            'incident_location' => 'Dusun Sungai Daun, sekitar 200m dari kanal primer',
            'latitude' => 1.0921,
            'longitude' => 108.9765,
            'incident_time' => now()->subHour()->format('Y-m-d\TH:i'),
            'incident_type' => 'Kebakaran Lahan Gambut',
            'description' => 'Titik api terlihat membesar di lahan semak belukar gambut, asap tebal mengarah ke pemukiman warga.',
        ];

        $response = $this->post(route('pengaduan.store'), $payload);

        $response->assertRedirect(route('pengaduan'));
        $response->assertSessionHas('report_created_success', true);

        $ticket = session('ticket_number');
        $report = Report::where('ticket_number', $ticket)->first();

        $this->assertNotNull($report);
        $this->assertEquals('61', $report->province_code);
        $this->assertEquals('Kalimantan Barat', $report->province_name);
        $this->assertEquals('6101', $report->regency_code);
        $this->assertEquals('Kab. Sambas', $report->regency_name);
        $this->assertEquals('6101010', $report->district_code);
        $this->assertEquals('Selakau', $report->district_name);
        $this->assertEquals('6101010001', $report->village_code);
        $this->assertEquals('Semelagi Besar', $report->village_name);
        $this->assertEquals('Dusun Sungai Daun, sekitar 200m dari kanal primer', $report->location_detail);
        $this->assertEquals('Dusun Sungai Daun, sekitar 200m dari kanal primer', $report->incident_location);
    }

    /**
     * Test admin dapat melihat detail lokasi terstruktur dan laporan lama tanpa kode tetap dapat dibuka.
     */
    public function test_admin_can_view_both_new_and_legacy_reports(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        // 1. Laporan baru dengan kode lengkap
        $newReport = Report::create([
            'ticket_number' => 'BS-20261103-0001',
            'access_code_hash' => bcrypt('CODE1234'),
            'reporter_name' => 'Warga Baru',
            'reporter_phone' => '081234567890',
            'province_code' => '61',
            'province_name' => 'Kalimantan Barat',
            'regency_code' => '6101',
            'regency_name' => 'Kab. Sambas',
            'district_code' => '6101010',
            'district_name' => 'Selakau',
            'village_code' => '6101010001',
            'village_name' => 'Semelagi Besar',
            'location_detail' => 'Dekat parit sawit RT 03',
            'province' => 'Kalimantan Barat',
            'regency' => 'Kab. Sambas',
            'district' => 'Selakau',
            'village' => 'Semelagi Besar',
            'incident_location' => 'Dekat parit sawit RT 03',
            'incident_time' => now(),
            'incident_type' => 'Kebakaran Lahan Gambut',
            'description' => 'Titik api menyebar di kebun warga dengan asap tebal.',
            'status' => 'Menunggu Verifikasi',
        ]);

        // 2. Laporan lama (legacy) tanpa kode wilayah administratif (null)
        $legacyReport = Report::create([
            'ticket_number' => 'BS-20261027-0099',
            'access_code_hash' => bcrypt('LEGACY99'),
            'reporter_name' => 'Warga Lama',
            'reporter_phone' => '081111222333',
            'province_code' => null,
            'province_name' => null,
            'regency_code' => null,
            'regency_name' => null,
            'district_code' => null,
            'district_name' => null,
            'village_code' => null,
            'village_name' => null,
            'location_detail' => null,
            'province' => 'Kalimantan Barat',
            'regency' => 'Kota Pontianak',
            'district' => null,
            'village' => null,
            'incident_location' => 'Jalan Purnama II No 15',
            'incident_time' => now()->subDays(5),
            'incident_type' => 'Kebakaran Semak Belukar / Alang-alang',
            'description' => 'Kebakaran semak belukar di pinggir jalan umum.',
            'status' => 'Dalam Penanganan',
        ]);

        // Test dashboard menampilkan kedua laporan
        $response = $this->actingAs($admin)->get(route('admin.dashboard'));
        $response->assertStatus(200);
        $response->assertSee($newReport->ticket_number);
        $response->assertSee($legacyReport->ticket_number);

        // Test halaman detail laporan baru menampilkan hierarki dan kode
        $responseNew = $this->actingAs($admin)->get(route('admin.reports.show', $newReport));
        $responseNew->assertStatus(200);
        $responseNew->assertSee('Semelagi Besar');
        $responseNew->assertSee('Selakau');
        $responseNew->assertSee('Kab. Sambas');
        $responseNew->assertSee('Kalimantan Barat');
        $responseNew->assertSee('6101010001');

        // Test halaman detail laporan lama tetap dibuka tanpa error
        $responseLegacy = $this->actingAs($admin)->get(route('admin.reports.show', $legacyReport));
        $responseLegacy->assertStatus(200);
        $responseLegacy->assertSee('Kota Pontianak');
        $responseLegacy->assertSee('Kalimantan Barat');
        $responseLegacy->assertSee('Jalan Purnama II No 15');
    }
}

