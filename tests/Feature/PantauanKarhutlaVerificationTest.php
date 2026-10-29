<?php

namespace Tests\Feature;

use App\Services\FirmsHotspotService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class PantauanKarhutlaVerificationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Cache::flush();
        config(['services.firms.map_key' => '']);
    }

    /**
     * 1. Audit Sumber Data & Keamanan MAP_KEY
     * Pastikan MAP_KEY tidak pernah bocor ke tampilan HTML maupun payload JSON API.
     */
    public function test_map_key_is_never_leaked_in_view_or_api(): void
    {
        // Set key rahasia untuk pengujian
        config(['services.firms.map_key' => 'secret_super_confidential_map_key_12345']);

        // Mock HTTP response dari NASA FIRMS
        Http::fake([
            'https://firms.modaps.eosdis.nasa.gov/*' => Http::response(
                "latitude,longitude,bright_ti4,scan,track,acq_date,acq_time,satellite,instrument,confidence,version,bright_ti5,frp,daynight\n" .
                "-0.1234,110.5678,335.2,0.4,0.4,2026-10-27,0530,N20,VIIRS,nominal,2.0NRT,298.1,5.4,D\n",
                200
            ),
            'https://api.open-meteo.com/*' => Http::response([
                'current' => ['temperature_2m' => 31.0, 'relative_humidity_2m' => 75, 'weather_code' => 1]
            ], 200),
        ]);

        $viewResponse = $this->get('/pantauan-karhutla');
        $viewResponse->assertStatus(200);
        $viewResponse->assertDontSee('secret_super_confidential_map_key_12345');

        $apiResponse = $this->get('/pantauan-karhutla/api');
        $apiResponse->assertStatus(200);
        $this->assertStringNotContainsString('secret_super_confidential_map_key_12345', $apiResponse->getContent());
    }

    /**
     * 2. Audit Nama Produk & Validitas Sumber Data
     */
    public function test_data_source_and_products_display_valid_metadata(): void
    {
        Http::fake([
            'https://firms.modaps.eosdis.nasa.gov/*' => Http::response(
                "latitude,longitude,bright_ti4,scan,track,acq_date,acq_time,satellite,instrument,confidence,version,bright_ti5,frp,daynight\n" .
                "-0.5500,111.2000,340.5,0.4,0.4,2026-10-27,0615,N20,VIIRS,high,2.0NRT,301.2,12.8,D\n",
                200
            ),
            'https://api.open-meteo.com/*' => Http::response([
                'current' => ['temperature_2m' => 30.5, 'relative_humidity_2m' => 70, 'weather_code' => 2]
            ], 200),
        ]);

        $service = app(FirmsHotspotService::class);
        $data = $service->getKalimantanHotspots(1);

        $this->assertEquals('success', $data['status']);
        $this->assertNotEmpty($data['product_name']);
        $this->assertStringContainsString('VIIRS', $data['product_name']);
        $this->assertCount(1, $data['hotspots']);

        $point = $data['hotspots'][0];
        $this->assertEquals(-0.5500, $point['latitude']);
        $this->assertEquals(111.2000, $point['longitude']);
        $this->assertEquals('high', $point['confidence']);
        $this->assertEquals(12.8, $point['frp']);
        $this->assertEquals(340.5, $point['brightness']);
        $this->assertEquals('Siang', $point['daynight']);
    }

    /**
     * 3. Verifikasi Timestamp & Zona Waktu
     * Memastikan acq_date & acq_time (UTC) dibedakan dari last_fetched_at (waktu server).
     */
    public function test_timestamps_distinction_and_utc_clarity(): void
    {
        Http::fake([
            'https://firms.modaps.eosdis.nasa.gov/*' => Http::response(
                "latitude,longitude,acq_date,acq_time,confidence\n" .
                "-1.2500,113.8000,2026-10-27,0745,nominal\n",
                200
            ),
        ]);

        $service = app(FirmsHotspotService::class);
        $data = $service->getKalimantanHotspots(1);

        $this->assertEquals('success', $data['status']);
        $point = $data['hotspots'][0];

        // Harus ada acq_date dan acq_time spesifik dari record satelit
        $this->assertEquals('2026-10-27', $point['acq_date']);
        $this->assertEquals('0745', $point['acq_time']);
        $this->assertEquals('2026-10-27 07:45 UTC', $point['acq_formatted']);

        // last_fetched_at merupakan waktu server backend
        $this->assertNotNull($data['last_fetched_at']);
        $this->assertNotEquals($point['acq_formatted'], $data['last_fetched_at']);
    }

    /**
     * Timestamp hilang atau kosong harus menampilkan keterangan 'Informasi waktu tidak tersedia'.
     */
    public function test_missing_timestamp_fallback_message(): void
    {
        Http::fake([
            'https://firms.modaps.eosdis.nasa.gov/*' => Http::response(
                "latitude,longitude,acq_date,acq_time,confidence\n" .
                "-1.2500,113.8000,,,nominal\n",
                200
            ),
        ]);

        $service = app(FirmsHotspotService::class);
        $data = $service->getKalimantanHotspots(1);

        $this->assertEquals('success', $data['status']);
        $point = $data['hotspots'][0];
        $this->assertEquals('Informasi waktu tidak tersedia', $point['acq_formatted']);
    }

    /**
     * 4. Uji Filter Wilayah & Validasi Koordinat Geografis
     * Marker di luar batas geografis Kalimantan harus dibuang.
     */
    public function test_hotspots_outside_kalimantan_bbox_are_rejected(): void
    {
        Http::fake([
            'https://firms.modaps.eosdis.nasa.gov/*' => Http::response(
                "latitude,longitude,acq_date,acq_time,confidence\n" .
                "-0.5,114.0,2026-10-27,0600,high\n" . // Valid (Kalteng)
                "-6.2,106.8,2026-10-27,0600,high\n" . // Luar BBOX (Jakarta/Jawa)
                "15.0,100.0,2026-10-27,0600,high\n" . // Luar BBOX (Thailand)
                "abc,def,2026-10-27,0600,high\n",     // Koordinat non-numerik
                200
            ),
        ]);

        $service = app(FirmsHotspotService::class);
        $data = $service->getKalimantanHotspots(1);

        $this->assertEquals('success', $data['status']);
        $this->assertCount(1, $data['hotspots']);
        $this->assertEquals(-0.5, $data['hotspots'][0]['latitude']);
        $this->assertEquals(114.0, $data['hotspots'][0]['longitude']);
    }

    /**
     * 5. Uji Kondisi Error API: Respon Kosong (0 record)
     */
    public function test_api_success_with_zero_hotspots(): void
    {
        Http::fake([
            'https://firms.modaps.eosdis.nasa.gov/*' => Http::response(
                "latitude,longitude,bright_ti4,scan,track,acq_date,acq_time,satellite,instrument,confidence,version,bright_ti5,frp,daynight\n",
                200
            ),
        ]);

        $service = app(FirmsHotspotService::class);
        $data = $service->getKalimantanHotspots(1);

        $this->assertEquals('success', $data['status']);
        $this->assertCount(0, $data['hotspots']);
        $this->assertEquals(0, $data['count']);
    }

    /**
     * 6. Uji Kondisi Error API: Rate Limit HTTP 429
     */
    public function test_api_rate_limit_429_handling(): void
    {
        Http::fake([
            'https://firms.modaps.eosdis.nasa.gov/*' => Http::response('Too Many Requests', 429),
        ]);

        $service = app(FirmsHotspotService::class);
        $data = $service->getKalimantanHotspots(1);

        $this->assertEquals('rate_limited', $data['status']);
        $this->assertStringContainsString('Rate Limit', $data['message']);
        $this->assertCount(0, $data['hotspots']);
    }

    /**
     * 7. Uji Kondisi Error API: Autentikasi Gagal atau Kunci Ditolak
     */
    public function test_api_auth_failure_redacts_key(): void
    {
        config(['services.firms.map_key' => 'invalid_secret_key_99999']);

        Http::fake([
            'https://firms.modaps.eosdis.nasa.gov/*' => Http::response('Invalid MAP_KEY invalid_secret_key_99999', 200),
        ]);

        $service = app(FirmsHotspotService::class);
        $data = $service->getKalimantanHotspots(1);

        $this->assertEquals('error', $data['status']);
        $this->assertStringNotContainsString('invalid_secret_key_99999', $data['message']);
        $this->assertStringContainsString('***REDACTED***', $data['message']);
    }

    /**
     * 8. Uji Kejujuran Cache Kedaluwarsa (Stale Cache) saat Pembaruan Gagal
     * Jika pembaruan API gagal, data cache lama tetap dapat ditampilkan dengan status jujur 'stale_warning'.
     */
    public function test_stale_cache_honesty_when_subsequent_request_fails(): void
    {
        // Gunakan Http::sequence untuk mensimulasikan permintaan pertama berhasil dan permintaan berikutnya gagal (HTTP 500)
        Http::fake([
            'https://firms.modaps.eosdis.nasa.gov/*' => Http::sequence()
                ->push(
                    "latitude,longitude,acq_date,acq_time,confidence\n" .
                    "-0.5,114.0,2026-10-27,0600,high\n",
                    200
                )
                ->push('Internal Server Error', 500),
        ]);

        $service = app(FirmsHotspotService::class);
        $firstCall = $service->getKalimantanHotspots(1);
        $this->assertEquals('success', $firstCall['status']);
        $this->assertCount(1, $firstCall['hotspots']);

        // Simulasikan cache utama kedaluwarsa
        Cache::forget('firms_hotspots_kalimantan_public_1d');

        $secondCall = $service->getKalimantanHotspots(1);
        $this->assertEquals('stale_warning', $secondCall['status']);
        $this->assertTrue($secondCall['is_stale']);
        $this->assertStringContainsString('Gagal memperbarui', $secondCall['message']);
        $this->assertCount(1, $secondCall['hotspots']);
    }
}
