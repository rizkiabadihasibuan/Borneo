<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FirmsHotspotService
{
    /**
     * Bounding box Pulau Kalimantan (min_lon, min_lat, max_lon, max_lat).
     */
    protected const KALIMANTAN_BBOX = '108.5,-4.5,119.5,4.5';

    /**
     * URL feed publik resmi NASA FIRMS NRT (tidak membutuhkan kunci API).
     */
    protected const PUBLIC_FEED_24H = 'https://firms.modaps.eosdis.nasa.gov/data/active_fire/noaa-20-viirs-c2/csv/J1_VIIRS_C2_SouthEast_Asia_24h.csv';
    protected const PUBLIC_FEED_7D  = 'https://firms.modaps.eosdis.nasa.gov/data/active_fire/noaa-20-viirs-c2/csv/J1_VIIRS_C2_SouthEast_Asia_7d.csv';

    /**
     * Ambil data hotspot aktif untuk wilayah Kalimantan dari NASA FIRMS.
     *
     * @param int $days Rentang hari (1-10 hari, default 1 hari)
     * @return array
     */
    public function getKalimantanHotspots(int $days = 1): array
    {
        $mapKey = config('services.firms.map_key');
        $source = config('services.firms.source', 'VIIRS_SNPP_NRT');
        $useOpenFeed = (bool) config('services.firms.use_open_feed', true);
        $cacheTtl = (int) config('services.firms.cache_ttl', 1800);

        // 1. Jika API Key tidak ada dan open feed dinonaktifkan
        if (empty($mapKey) && !$useOpenFeed) {
            return [
                'status' => 'unconfigured',
                'source_name' => 'NASA FIRMS (Fire Information for Resource Management System)',
                'product_name' => 'VIIRS 375m / MODIS 1km NRT Active Fire',
                'source_url' => 'https://firms.modaps.eosdis.nasa.gov/',
                'message' => 'Kunci API NASA FIRMS (MAP_KEY) belum dikonfigurasi pada file .env (NASA_FIRMS_MAP_KEY). Sistem membutuhkan MAP_KEY resmi dari NASA FIRMS untuk memanggil data observasi satelit aktif.',
                'config_needed' => 'NASA_FIRMS_MAP_KEY',
                'hotspots' => [],
                'count' => 0,
                'last_fetched_at' => null,
                'observation_time' => 'Informasi waktu tidak tersedia',
            ];
        }

        // 2. Tentukan cache key & URL sumber (Area API atau Public NRT Feed)
        $isUsingOpenFeed = empty($mapKey);
        $cacheKey = $isUsingOpenFeed
            ? "firms_hotspots_kalimantan_public_{$days}d"
            : "firms_hotspots_kalimantan_{$source}_{$days}d";

        return Cache::remember($cacheKey, $cacheTtl, function () use ($mapKey, $source, $days, $isUsingOpenFeed, $cacheKey) {
            $url = $isUsingOpenFeed
                ? ($days > 1 ? self::PUBLIC_FEED_7D : self::PUBLIC_FEED_24H)
                : "https://firms.modaps.eosdis.nasa.gov/api/area/csv/{$mapKey}/{$source}/" . self::KALIMANTAN_BBOX . "/{$days}";

            $sourceLabel = $isUsingOpenFeed
                ? 'NASA FIRMS (Sensor VIIRS NOAA-20 NRT Public Feed)'
                : 'NASA FIRMS (' . $source . ')';

            $productLabel = $isUsingOpenFeed
                ? 'VIIRS 375m NRT Active Fire (Collection 2 - NOAA-20)'
                : 'NASA FIRMS ' . $source . ' Active Fire Product';

            try {
                $response = Http::withoutVerifying()->timeout(20)->get($url);

                if ($response->status() === 429) {
                    $lastKnown = Cache::get($cacheKey . '_last_known');
                    if ($lastKnown && !empty($lastKnown['hotspots'])) {
                        $lastKnown['status'] = 'stale_warning';
                        $lastKnown['is_stale'] = true;
                        $lastKnown['message'] = 'Batas kuota pemanggilan NASA FIRMS terlampaui (Rate Limit 429). Menampilkan data observasi tersimpan sebelumnya.';
                        return $lastKnown;
                    }

                    return [
                        'status' => 'rate_limited',
                        'source_name' => 'NASA FIRMS',
                        'product_name' => $productLabel,
                        'source_url' => 'https://firms.modaps.eosdis.nasa.gov/',
                        'message' => 'Batas kuota pemanggilan NASA FIRMS terlampaui (Rate Limit). Silakan coba beberapa saat lagi.',
                        'hotspots' => [],
                        'count' => 0,
                        'last_fetched_at' => now()->toIso8601String(),
                        'observation_time' => 'Informasi waktu tidak tersedia',
                    ];
                }

                if (!$response->successful()) {
                    $lastKnown = Cache::get($cacheKey . '_last_known');
                    if ($lastKnown && !empty($lastKnown['hotspots'])) {
                        $lastKnown['status'] = 'stale_warning';
                        $lastKnown['is_stale'] = true;
                        $lastKnown['message'] = 'Gagal memperbarui data dari NASA FIRMS (HTTP ' . $response->status() . '). Menampilkan data observasi tersimpan sebelumnya.';
                        return $lastKnown;
                    }

                    return [
                        'status' => 'error',
                        'source_name' => 'NASA FIRMS',
                        'product_name' => $productLabel,
                        'source_url' => 'https://firms.modaps.eosdis.nasa.gov/',
                        'message' => 'Gagal mengambil data dari NASA FIRMS (HTTP ' . $response->status() . ').',
                        'hotspots' => [],
                        'count' => 0,
                        'last_fetched_at' => now()->toIso8601String(),
                        'observation_time' => 'Informasi waktu tidak tersedia',
                    ];
                }

                $body = trim($response->body());

                // Validasi format CSV
                if (empty($body) || str_starts_with($body, 'Invalid') || str_starts_with($body, 'Error')) {
                    $cleanBody = $mapKey ? str_replace($mapKey, '***REDACTED***', $body) : $body;
                    
                    $lastKnown = Cache::get($cacheKey . '_last_known');
                    if ($lastKnown && !empty($lastKnown['hotspots'])) {
                        $lastKnown['status'] = 'stale_warning';
                        $lastKnown['is_stale'] = true;
                        $lastKnown['message'] = 'Format data NASA FIRMS tidak sesuai. Menampilkan data observasi tersimpan sebelumnya.';
                        return $lastKnown;
                    }

                    return [
                        'status' => 'error',
                        'source_name' => 'NASA FIRMS',
                        'product_name' => $productLabel,
                        'source_url' => 'https://firms.modaps.eosdis.nasa.gov/',
                        'message' => 'Autentikasi NASA FIRMS gagal atau format respons tidak sesuai: ' . substr($cleanBody, 0, 120),
                        'hotspots' => [],
                        'count' => 0,
                        'last_fetched_at' => now()->toIso8601String(),
                        'observation_time' => 'Informasi waktu tidak tersedia',
                    ];
                }

                $parsed = $this->parseCsv($body);

                $result = [
                    'status' => 'success',
                    'source_name' => $sourceLabel,
                    'product_name' => $productLabel,
                    'source_url' => 'https://firms.modaps.eosdis.nasa.gov/',
                    'message' => 'Data titik panas berhasil ditarik secara langsung dari sensor satelit NASA FIRMS.',
                    'hotspots' => $parsed['hotspots'],
                    'count' => count($parsed['hotspots']),
                    'time_window' => $days . ' Hari Terakhir (Rolling ' . ($days * 24) . ' Jam)',
                    'last_fetched_at' => now()->toIso8601String(),
                    'observation_time' => $parsed['latest_time'] ?? 'Informasi waktu tidak tersedia',
                    'classification_disclaimer' => 'Pengelompokan provinsi menggunakan estimasi batas koordinat regional (bounding box). Konfirmasi batas administratif resmi mengacu pada BIG / Kemendagri.',
                ];

                // Simpan last known good data untuk ketahanan saat kegagalan jaringan mendatang
                Cache::put($cacheKey . '_last_known', $result, 86400 * 7);

                return $result;
            } catch (\Exception $e) {
                $cleanError = $mapKey ? str_replace($mapKey, '***REDACTED***', $e->getMessage()) : $e->getMessage();
                Log::warning('NASA FIRMS API Exception: ' . $cleanError);

                $lastKnown = Cache::get($cacheKey . '_last_known');
                if ($lastKnown && !empty($lastKnown['hotspots'])) {
                    $lastKnown['status'] = 'stale_warning';
                    $lastKnown['is_stale'] = true;
                    $lastKnown['message'] = 'Koneksi ke NASA FIRMS terputus (' . $cleanError . '). Menampilkan data observasi tersimpan sebelumnya.';
                    return $lastKnown;
                }

                return [
                    'status' => 'error',
                    'source_name' => 'NASA FIRMS',
                    'product_name' => $productLabel,
                    'source_url' => 'https://firms.modaps.eosdis.nasa.gov/',
                    'message' => 'Koneksi ke NASA FIRMS terputus atau timeout: ' . $cleanError,
                    'hotspots' => [],
                    'count' => 0,
                    'last_fetched_at' => now()->toIso8601String(),
                    'observation_time' => 'Informasi waktu tidak tersedia',
                ];
            }
        });
    }

    /**
     * Parse baris CSV hasil respons NASA FIRMS.
     */
    protected function parseCsv(string $csvContent): array
    {
        $lines = explode("\n", str_replace("\r", "", $csvContent));
        if (count($lines) < 2) {
            return ['hotspots' => [], 'latest_time' => null];
        }

        $header = str_getcsv(array_shift($lines));
        $headerMap = array_flip($header);

        $hotspots = [];
        $latestTime = null;

        foreach ($lines as $line) {
            if (empty(trim($line))) continue;

            $row = str_getcsv($line);
            if (count($row) < count($header)) continue;

            // Validasi tipe data numerik koordinat
            if (!isset($headerMap['latitude']) || !isset($headerMap['longitude'])) continue;
            $rawLat = $row[$headerMap['latitude']] ?? null;
            $rawLon = $row[$headerMap['longitude']] ?? null;

            if (!is_numeric($rawLat) || !is_numeric($rawLon)) continue;

            $lat = (float) $rawLat;
            $lon = (float) $rawLon;

            // Validasi koordinat bola bumi valid
            if ($lat < -90.0 || $lat > 90.0 || $lon < -180.0 || $lon > 180.0) continue;

            // Batas koordinat geografis Pulau Kalimantan (-4.5 s/d 4.5, 108.5 s/d 119.5)
            if ($lat < -4.5 || $lat > 4.5 || $lon < 108.5 || $lon > 119.5) {
                continue;
            }

            $acqDate = isset($headerMap['acq_date']) ? trim($row[$headerMap['acq_date']] ?? '') : '';
            $acqTime = isset($headerMap['acq_time']) ? trim($row[$headerMap['acq_time']] ?? '') : '';
            $confidence = isset($headerMap['confidence']) ? trim($row[$headerMap['confidence']] ?? 'nominal') : 'nominal';
            $instrument = isset($headerMap['instrument']) ? trim($row[$headerMap['instrument']] ?? 'VIIRS') : 'VIIRS';
            $satellite = isset($headerMap['satellite']) ? trim($row[$headerMap['satellite']] ?? 'NOAA-20') : 'NOAA-20';
            $frp = (isset($headerMap['frp']) && isset($row[$headerMap['frp']]) && is_numeric($row[$headerMap['frp']])) 
                ? (float) $row[$headerMap['frp']] 
                : null;

            // Ekstraksi nilai kecerahan suhu termal (brightness / bright_ti4)
            $brightness = null;
            if (isset($headerMap['bright_ti4']) && isset($row[$headerMap['bright_ti4']]) && is_numeric($row[$headerMap['bright_ti4']])) {
                $brightness = (float) $row[$headerMap['bright_ti4']];
            } elseif (isset($headerMap['brightness']) && isset($row[$headerMap['brightness']]) && is_numeric($row[$headerMap['brightness']])) {
                $brightness = (float) $row[$headerMap['brightness']];
            }

            $daynight = isset($headerMap['daynight']) ? trim($row[$headerMap['daynight']] ?? '') : null;

            // Klasifikasi provinsi berdasarkan koordinat Kalimantan
            $province = $this->classifyProvince($lat, $lon);

            // Format waktu akuisisi satelit dengan penegasan UTC
            $acqFormatted = null;
            if (!empty($acqDate)) {
                $timePart = !empty($acqTime) && strlen($acqTime) === 4 
                    ? substr($acqTime, 0, 2) . ':' . substr($acqTime, 2, 2) 
                    : ($acqTime ?: '00:00');
                $acqFormatted = $acqDate . ' ' . $timePart . ' UTC';
            }

            $hotspots[] = [
                'latitude' => $lat,
                'longitude' => $lon,
                'acq_date' => $acqDate ?: null,
                'acq_time' => $acqTime ?: null,
                'acq_formatted' => $acqFormatted ?: 'Informasi waktu tidak tersedia',
                'confidence' => $confidence,
                'instrument' => $instrument,
                'satellite' => $satellite,
                'frp' => $frp,
                'brightness' => $brightness,
                'daynight' => $daynight === 'D' ? 'Siang' : ($daynight === 'N' ? 'Malam' : $daynight),
                'province' => $province,
            ];

            if ($acqFormatted) {
                $latestTime = $acqFormatted;
            }
        }

        return [
            'hotspots' => $hotspots,
            'latest_time' => $latestTime,
        ];
    }

    /**
     * Klasifikasi estimasi provinsi Kalimantan berdasarkan batas koordinat geografis.
     * Catatan: Klasifikasi ini adalah perkiraan batas spasial dan bukan batas kadaster resmi.
     */
    protected function classifyProvince(float $lat, float $lon): string
    {
        if ($lat >= 1.0 && $lon >= 114.5 && $lon <= 118.0) {
            return 'Kalimantan Utara';
        } elseif ($lat >= -2.5 && $lat <= 2.9 && $lon >= 115.5) {
            return 'Kalimantan Timur';
        } elseif ($lat <= -1.2 && $lon >= 114.3 && $lon <= 116.6) {
            return 'Kalimantan Selatan';
        } elseif ($lon < 112.0) {
            return 'Kalimantan Barat';
        } else {
            return 'Kalimantan Tengah';
        }
    }
}
