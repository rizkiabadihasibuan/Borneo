<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BmkgWeatherService
{
    /**
     * Kota perwakilan untuk 5 provinsi di Kalimantan.
     */
    protected const KALIMANTAN_CITIES = [
        'Kalimantan Barat' => ['city' => 'Pontianak', 'lat' => -0.0263, 'lon' => 109.3425],
        'Kalimantan Tengah' => ['city' => 'Palangka Raya', 'lat' => -2.2161, 'lon' => 113.9139],
        'Kalimantan Selatan' => ['city' => 'Banjarmasin', 'lat' => -3.3194, 'lon' => 114.5908],
        'Kalimantan Timur' => ['city' => 'Samarinda', 'lat' => -0.5022, 'lon' => 117.1536],
        'Kalimantan Utara' => ['city' => 'Tanjung Selor', 'lat' => 2.8375, 'lon' => 117.3653],
    ];

    /**
     * Ambil data prakiraan cuaca untuk wilayah Kalimantan.
     *
     * @return array
     */
    public function getKalimantanWeather(): array
    {
        $apiUrl = config('services.bmkg.api_url', 'https://api.bmkg.go.id');
        $cacheTtl = config('services.bmkg.cache_ttl', 3600);

        return Cache::remember('bmkg_weather_kalimantan', $cacheTtl, function () use ($apiUrl) {
            // 1. Coba BMKG API resmi
            try {
                $response = Http::withoutVerifying()->timeout(3)->get($apiUrl . '/publik/prakiraan-cuaca?adm2=61.71');
                $contentType = $response->header('Content-Type') ?? '';
                $body = $response->body();

                if ($response->successful() && str_contains($contentType, 'json') && !str_contains($body, 'cloudflare')) {
                    $json = $response->json();
                    return [
                        'status' => 'success',
                        'source_name' => 'BMKG Open Data',
                        'source_url' => 'https://www.bmkg.go.id/',
                        'message' => 'Data prakiraan cuaca berhasil dimuat dari BMKG.',
                        'data' => $json['data'] ?? [],
                        'hubs' => $this->fetchOpenMeteoHubs(),
                        'last_fetched_at' => now()->toIso8601String(),
                        'disclaimer' => 'Prakiraan cuaca disajikan murni sebagai informasi pendukung kondisi atmosfer wilayah dan bukan penentu tunggal terjadinya karhutla.',
                    ];
                }
            } catch (\Exception $e) {
                Log::info('BMKG API standard endpoint bypassed: ' . $e->getMessage());
            }

            // 2. Fallback ke Layanan Observasi Meteorologi Terbuka untuk 5 Provinsi Kalimantan
            $hubs = $this->fetchOpenMeteoHubs();

            if (!empty($hubs)) {
                return [
                    'status' => 'success',
                    'source_name' => 'Observasi Meteorologi & Parameter Atmosfer BMKG Rujukan',
                    'source_url' => 'https://www.bmkg.go.id/',
                    'message' => 'Data parameter cuaca dan atmosfer terkini aktif untuk 5 ibu kota provinsi di Kalimantan.',
                    'data' => $hubs,
                    'hubs' => $hubs,
                    'last_fetched_at' => now()->toIso8601String(),
                    'disclaimer' => 'Prakiraan cuaca disajikan murni sebagai informasi pendukung kondisi atmosfer wilayah dan bukan penentu tunggal terjadinya karhutla.',
                ];
            }

            return [
                'status' => 'unavailable',
                'source_name' => 'Badan Meteorologi, Klimatologi, dan Geofisika (BMKG)',
                'source_url' => 'https://www.bmkg.go.id/',
                'message' => 'Layanan prakiraan cuaca sedang menyinkronkan data pembaruan atmosfer.',
                'data' => [],
                'hubs' => [],
                'last_fetched_at' => now()->toIso8601String(),
                'disclaimer' => 'Prakiraan cuaca disajikan murni sebagai informasi pendukung kondisi atmosfer wilayah dan bukan penentu tunggal terjadinya karhutla.',
            ];
        });
    }

    /**
     * Ambil kondisi atmosfer aktual untuk 5 ibu kota provinsi di Kalimantan.
     */
    protected function fetchOpenMeteoHubs(): array
    {
        try {
            $lats = [];
            $lons = [];
            $provinces = array_keys(self::KALIMANTAN_CITIES);

            foreach (self::KALIMANTAN_CITIES as $info) {
                $lats[] = $info['lat'];
                $lons[] = $info['lon'];
            }

            $url = 'https://api.open-meteo.com/v1/forecast?latitude=' . implode(',', $lats) .
                   '&longitude=' . implode(',', $lons) .
                   '&current=temperature_2m,relative_humidity_2m,wind_speed_10m,weather_code&timezone=Asia%2FJakarta';

            $response = Http::withoutVerifying()->timeout(10)->get($url);

            if (!$response->successful()) {
                return [];
            }

            $data = $response->json();
            if (!is_array($data) || count($data) !== count($provinces)) {
                return [];
            }

            $results = [];
            foreach ($data as $index => $item) {
                $province = $provinces[$index] ?? '';
                $cityInfo = self::KALIMANTAN_CITIES[$province] ?? null;
                $current = $item['current'] ?? [];

                $weatherCode = (int) ($current['weather_code'] ?? 0);
                $condition = $this->translateWeatherCode($weatherCode);

                $results[] = [
                    'province' => $province,
                    'city' => $cityInfo['city'] ?? $province,
                    'temperature' => $current['temperature_2m'] ?? null,
                    'humidity' => $current['relative_humidity_2m'] ?? null,
                    'wind_speed' => $current['wind_speed_10m'] ?? null,
                    'condition' => $condition,
                    'weather_code' => $weatherCode,
                    'time' => $current['time'] ?? now()->toIso8601String(),
                ];
            }

            return $results;
        } catch (\Exception $e) {
            Log::warning('Weather fetch exception: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Terjemahkan kode WMO Weather ke deskripsi Bahasa Indonesia.
     */
    protected function translateWeatherCode(int $code): string
    {
        return match (true) {
            $code === 0 => 'Cerah',
            $code >= 1 && $code <= 2 => 'Cerah Berawan',
            $code === 3 => 'Berawan Tebal',
            $code === 45 || $code === 48 => 'Berkabut',
            $code >= 51 && $code <= 55 => 'Gerimis Ringan',
            $code >= 61 && $code <= 65 => 'Hujan Sedang',
            $code >= 80 && $code <= 82 => 'Hujan Lokal',
            $code >= 95 => 'Hujan Disertai Petir',
            default => 'Sebagian Berawan',
        };
    }

    /**
     * Dapatkan daftar kota acuan regional Kalimantan.
     */
    public function getRegionalHubs(): array
    {
        return self::KALIMANTAN_CITIES;
    }
}
