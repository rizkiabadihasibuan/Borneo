<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
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
     * @param int $days Rentang hari (1–7 hari, default 7)
     * @return array
     */
    public function getKalimantanHotspots(int $days = 7): array
    {
        $mapKey     = config('services.firms.map_key');
        $source     = config('services.firms.source', 'VIIRS_SNPP_NRT');
        $useOpenFeed = (bool) config('services.firms.use_open_feed', true);
        $cacheTtl   = (int) config('services.firms.cache_ttl', 1800);

        // Jika API Key tidak ada dan open feed dinonaktifkan
        if (empty($mapKey) && !$useOpenFeed) {
            return $this->unconfiguredResponse();
        }

        $isUsingOpenFeed = empty($mapKey);
        $cacheKey = $isUsingOpenFeed
            ? "firms_hotspots_kalimantan_public_{$days}d"
            : "firms_hotspots_kalimantan_{$source}_{$days}d";

        return Cache::remember($cacheKey, $cacheTtl, function () use ($mapKey, $source, $days, $isUsingOpenFeed, $cacheKey) {
            // Coba MAP_KEY API terlebih dahulu jika tersedia
            if (!$isUsingOpenFeed) {
                $apiUrl = "https://firms.modaps.eosdis.nasa.gov/api/area/csv/{$mapKey}/{$source}/"
                    . self::KALIMANTAN_BBOX . "/{$days}";
                $result = $this->downloadAndParseUrl($apiUrl, $days, 'NASA FIRMS (' . $source . ')',
                    'NASA FIRMS ' . $source . ' Active Fire Product', $cacheKey, $mapKey);

                if ($result !== null) {
                    return $result;
                }
                Log::info('NASA FIRMS API key tidak menghasilkan data, beralih ke public feed.');
            }

            // Public feed NOAA-20 (stream langsung, tanpa muat ke string)
            $feedUrl = ($days > 1) ? self::PUBLIC_FEED_7D : self::PUBLIC_FEED_24H;
            $result  = $this->downloadAndParseUrl($feedUrl, $days,
                'NASA FIRMS (VIIRS NOAA-20 NRT Public Feed)',
                'VIIRS 375m NRT Active Fire (Collection 2 - NOAA-20)',
                $cacheKey, null);

            if ($result !== null) {
                return $result;
            }

            // Last-known fallback
            $lastKnown = Cache::get($cacheKey . '_last_known');
            if ($lastKnown && !empty($lastKnown['hotspots'])) {
                $lastKnown['status']    = 'stale_warning';
                $lastKnown['is_stale']  = true;
                $lastKnown['message']   = 'Gagal mengambil data terbaru dari NASA FIRMS. Menampilkan data observasi tersimpan sebelumnya.';
                return $lastKnown;
            }

            return [
                'status'       => 'error',
                'source_name'  => 'NASA FIRMS',
                'product_name' => 'VIIRS 375m NRT Active Fire',
                'source_url'   => 'https://firms.modaps.eosdis.nasa.gov/',
                'message'      => 'Gagal mengambil data dari semua sumber NASA FIRMS.',
                'hotspots'     => [],
                'count'        => 0,
                'last_fetched_at'    => now()->toIso8601String(),
                'observation_time'   => 'Informasi waktu tidak tersedia',
            ];
        });
    }

    // -------------------------------------------------------------------------
    // Inti: download CSV via cURL langsung ke file temp, parse line-by-line
    // -------------------------------------------------------------------------

    /**
     * Download URL ke file temp dengan cURL (tidak masuk ke PHP string),
     * lalu parse baris per baris dengan fgetcsv. Mengembalikan null jika
     * tidak ada data hotspot Kalimantan (caller melakukan fallback).
     */
    protected function downloadAndParseUrl(
        string  $url,
        int     $days,
        string  $sourceLabel,
        string  $productLabel,
        string  $cacheKey,
        ?string $mapKey
    ): ?array {
        // Buat file temp di disk (bukan php://memory) agar tidak ada di RAM
        $tmpFile = tempnam(sys_get_temp_dir(), 'firms_');
        if ($tmpFile === false) {
            Log::warning('FirmsHotspotService: gagal membuat file temp.');
            return null;
        }

        $fp = fopen($tmpFile, 'w+b');
        if ($fp === false) {
            @unlink($tmpFile);
            return null;
        }

        // --- Download dengan cURL stream ke file ---
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_FILE           => $fp,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_TIMEOUT        => 45,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_USERAGENT      => 'BorneoSiaga/1.0 (Laravel; +https://borneo-siaga.id)',
            CURLOPT_ENCODING       => '', // terima gzip jika server kirim
        ]);
        curl_exec($ch);
        $httpCode  = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);
        fclose($fp);

        // Cek kode HTTP sebelum lanjut
        if (!empty($curlError) || $httpCode < 200 || $httpCode >= 300) {
            @unlink($tmpFile);
            $logKey = $mapKey ? str_replace($mapKey, '***', $url) : $url;
            Log::info("NASA FIRMS fetch gagal [{$httpCode}] {$curlError} untuk {$logKey}");
            return null;
        }

        // Baca baris pertama untuk validasi (header CSV atau pesan error NASA)
        $checkFp    = fopen($tmpFile, 'r');
        $firstLine  = fgets($checkFp);
        fclose($checkFp);

        if ($firstLine === false
            || str_starts_with(trim($firstLine), 'Invalid')
            || str_starts_with(trim($firstLine), 'Error')
        ) {
            @unlink($tmpFile);
            return null;
        }

        // --- Parse baris per baris ---
        $parsed = $this->parseCsvFile($tmpFile);
        @unlink($tmpFile); // Hapus file temp segera

        if (empty($parsed['hotspots'])) {
            return null; // Tidak ada titik panas → caller lanjut ke fallback
        }

        $result = [
            'status'                  => 'success',
            'source_name'             => $sourceLabel,
            'product_name'            => $productLabel,
            'source_url'              => 'https://firms.modaps.eosdis.nasa.gov/',
            'message'                 => 'Data titik panas berhasil ditarik dari sensor satelit NASA FIRMS.',
            'hotspots'                => $parsed['hotspots'],
            'count'                   => count($parsed['hotspots']),
            'time_window'             => $days . ' Hari Terakhir (Rolling ' . ($days * 24) . ' Jam)',
            'last_fetched_at'         => now()->toIso8601String(),
            'observation_time'        => $parsed['latest_time'] ?? 'Informasi waktu tidak tersedia',
            'classification_disclaimer' => 'Pengelompokan provinsi menggunakan estimasi batas koordinat regional (bounding box). Konfirmasi batas administratif resmi mengacu pada BIG / Kemendagri.',
        ];

        Cache::put($cacheKey . '_last_known', $result, 86400 * 7);
        return $result;
    }

    /**
     * Parse file CSV NASA FIRMS dari path file temp, baris per baris.
     * Hanya baris dalam bbox Kalimantan yang disimpan, dibatasi MAX_HOTSPOTS titik.
     * Field per item diminimalkan agar tidak membebani memori & JSON output.
     */
    protected const MAX_HOTSPOTS = 1500;

    protected function parseCsvFile(string $filePath): array
    {
        $hotspots   = [];
        $latestTime = null;
        $headerMap  = null;

        $fp = fopen($filePath, 'r');
        if ($fp === false) {
            return ['hotspots' => [], 'latest_time' => null];
        }

        while (($row = fgetcsv($fp)) !== false) {
            // Baris pertama → header
            if ($headerMap === null) {
                $headerMap = array_flip($row);
                continue;
            }

            // Sudah capai batas maksimum — berhenti baca
            if (count($hotspots) >= self::MAX_HOTSPOTS) break;

            if (!isset($headerMap['latitude'], $headerMap['longitude'])) continue;

            $rawLat = $row[$headerMap['latitude']] ?? null;
            $rawLon = $row[$headerMap['longitude']] ?? null;

            if (!is_numeric($rawLat) || !is_numeric($rawLon)) continue;

            $lat = (float) $rawLat;
            $lon = (float) $rawLon;

            if ($lat < -90.0 || $lat > 90.0 || $lon < -180.0 || $lon > 180.0) continue;

            // Filter bbox Kalimantan — baris di luar langsung dibuang
            if ($lat < -4.5 || $lat > 4.5 || $lon < 108.5 || $lon > 119.5) continue;

            $acqDate    = isset($headerMap['acq_date'])   ? trim($row[$headerMap['acq_date']]   ?? '') : '';
            $acqTime    = isset($headerMap['acq_time'])   ? trim($row[$headerMap['acq_time']]   ?? '') : '';
            $confidence = isset($headerMap['confidence']) ? trim($row[$headerMap['confidence']] ?? 'nominal') : 'nominal';
            $instrument = isset($headerMap['instrument']) ? trim($row[$headerMap['instrument']] ?? 'VIIRS')   : 'VIIRS';
            $satellite  = isset($headerMap['satellite'])  ? trim($row[$headerMap['satellite']]  ?? 'NOAA-20') : 'NOAA-20';
            $daynight   = isset($headerMap['daynight'])   ? trim($row[$headerMap['daynight']]   ?? '') : null;

            $frp = null;
            if (isset($headerMap['frp'], $row[$headerMap['frp']]) && is_numeric($row[$headerMap['frp']])) {
                $frp = round((float) $row[$headerMap['frp']], 2);
            }

            $brightness = null;
            if (isset($headerMap['bright_ti4'], $row[$headerMap['bright_ti4']]) && is_numeric($row[$headerMap['bright_ti4']])) {
                $brightness = round((float) $row[$headerMap['bright_ti4']], 1);
            } elseif (isset($headerMap['brightness'], $row[$headerMap['brightness']]) && is_numeric($row[$headerMap['brightness']])) {
                $brightness = round((float) $row[$headerMap['brightness']], 1);
            }

            $acqFormatted = null;
            if (!empty($acqDate)) {
                $timePart     = (!empty($acqTime) && strlen($acqTime) === 4)
                    ? substr($acqTime, 0, 2) . ':' . substr($acqTime, 2, 2)
                    : ($acqTime ?: '00:00');
                $acqFormatted = $acqDate . ' ' . $timePart . ' UTC';
            }

            // Simpan field minimal yang dibutuhkan marker peta (hemat memori)
            $hotspots[] = [
                'la' => round($lat, 5),   // latitude (disingkat)
                'lo' => round($lon, 5),   // longitude (disingkat)
                'c'  => $confidence,      // confidence
                'p'  => $this->classifyProvince($lat, $lon), // province
                't'  => $acqFormatted ?: null,               // acq_formatted
                's'  => $satellite,
                'i'  => $instrument,
                'f'  => $frp,
                'b'  => $brightness,
                'd'  => $daynight === 'D' ? 'Siang' : ($daynight === 'N' ? 'Malam' : $daynight),
            ];

            if ($acqFormatted) {
                $latestTime = $acqFormatted;
            }
        }

        fclose($fp);

        return ['hotspots' => $hotspots, 'latest_time' => $latestTime];
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

    // -------------------------------------------------------------------------
    // Helper
    // -------------------------------------------------------------------------

    protected function unconfiguredResponse(): array
    {
        return [
            'status'       => 'unconfigured',
            'source_name'  => 'NASA FIRMS (Fire Information for Resource Management System)',
            'product_name' => 'VIIRS 375m / MODIS 1km NRT Active Fire',
            'source_url'   => 'https://firms.modaps.eosdis.nasa.gov/',
            'message'      => 'Kunci API NASA FIRMS (MAP_KEY) belum dikonfigurasi pada file .env (NASA_FIRMS_MAP_KEY).',
            'config_needed'    => 'NASA_FIRMS_MAP_KEY',
            'hotspots'         => [],
            'count'            => 0,
            'last_fetched_at'  => null,
            'observation_time' => 'Informasi waktu tidak tersedia',
        ];
    }
}
