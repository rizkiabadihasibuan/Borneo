<?php

namespace Tests\Feature;

use App\Services\BmkgWeatherService;
use App\Services\FirmsHotspotService;
use Tests\TestCase;

class PantauanKarhutlaTest extends TestCase
{
    /**
     * Test route /pantauan-karhutla is accessible and renders interactive map layout.
     */
    public function test_pantauan_karhutla_page_renders_successfully(): void
    {
        $response = $this->get('/pantauan-karhutla');
        $response->assertStatus(200);
        $response->assertViewIs('pages.pantauan');

        // Assert Hero and Title
        $response->assertSee('Pantauan Karhutla di Kalimantan');
        $response->assertSee('Sistem Pemantauan Geospasial');

        // Assert Map Canvas Container & Leaflet integration
        $response->assertSee('id="mapCanvas"', false);
        $response->assertSee('leaflet.js');
        $response->assertSee('OpenStreetMap');

        // Assert Region Filter Controls
        $response->assertSee('Seluruh Kalimantan');
        $response->assertSee('Kalimantan Barat');
        $response->assertSee('Kalimantan Tengah');
        $response->assertSee('Kalimantan Selatan');
        $response->assertSee('Kalimantan Timur');
        $response->assertSee('Kalimantan Utara');

        // Assert External Data Sources & Transparency
        $response->assertSee('NASA FIRMS');
        $response->assertSee('BMKG');
        $response->assertSee('Legenda Simbol');
        $response->assertSee('Pemberitahuan Transparansi');
    }

    /**
     * Test JSON API endpoint /pantauan-karhutla/api returns valid structure.
     */
    public function test_pantauan_karhutla_api_returns_valid_json(): void
    {
        $response = $this->get('/pantauan-karhutla/api');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'firms' => [
                'status',
                'source_name',
                'hotspots',
                'count',
            ],
            'weather' => [
                'status',
                'source_name',
                'disclaimer',
            ],
            'meta' => [
                'server_time',
                'bbox',
            ]
        ]);
    }

    /**
     * Test FirmsHotspotService returns unconfigured status when no key is set.
     */
    public function test_firms_service_handles_missing_key_gracefully(): void
    {
        config(['services.firms.map_key' => '', 'services.firms.use_open_feed' => false]);
        $service = app(FirmsHotspotService::class);
        $result = $service->getKalimantanHotspots();

        $this->assertEquals('unconfigured', $result['status']);
        $this->assertEmpty($result['hotspots']);
        $this->assertStringContainsString('NASA_FIRMS_MAP_KEY', $result['config_needed']);
    }

    /**
     * Test BmkgWeatherService returns graceful structure and supporting disclaimer.
     */
    public function test_bmkg_service_provides_supporting_context_disclaimer(): void
    {
        $service = app(BmkgWeatherService::class);
        $result = $service->getKalimantanWeather();

        $this->assertArrayHasKey('status', $result);
        $this->assertArrayHasKey('disclaimer', $result);
        $this->assertStringContainsString('bukan penentu tunggal', $result['disclaimer']);
    }
}
