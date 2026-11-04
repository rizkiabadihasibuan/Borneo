<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RegionAreaController extends Controller
{
    /**
     * Base URL dataset resmi wilayah Indonesia
     */
    protected const PRIMARY_BASE_URL = 'https://emsifa.github.io/api-wilayah-indonesia/api';
    protected const MIRROR_BASE_URL = 'https://www.emsifa.com/api-wilayah-indonesia/api';

    /**
     * Durasi cache data wilayah (30 hari karena kode administratif jarang berubah)
     */
    protected const CACHE_TTL_SECONDS = 2592000;

    /**
     * Dapatkan daftar provinsi Kalimantan (5 provinsi resmi wilayah Borneo Indonesia).
     * Data dikembalikan langsung tanpa pemanggilan API eksternal.
     */
    public function provinces(): JsonResponse
    {
        $data = [
            ['id' => '61', 'name' => 'Kalimantan Barat'],
            ['id' => '62', 'name' => 'Kalimantan Tengah'],
            ['id' => '63', 'name' => 'Kalimantan Selatan'],
            ['id' => '64', 'name' => 'Kalimantan Timur'],
            ['id' => '65', 'name' => 'Kalimantan Utara'],
        ];

        return response()->json($data);
    }

    /**
     * Dapatkan daftar kabupaten/kota berdasarkan kode provinsi.
     */
    public function regencies(string $provinceCode): JsonResponse
    {
        if (!preg_match('/^[0-9]{1,10}$/', $provinceCode)) {
            return response()->json(['error' => 'Format kode provinsi tidak valid.'], 400);
        }

        $cacheKey = "wilayah_regencies_{$provinceCode}_v1";

        $data = Cache::remember($cacheKey, self::CACHE_TTL_SECONDS, function () use ($provinceCode) {
            $raw = $this->fetchFromSource("/regencies/{$provinceCode}.json");

            if (empty($raw)) {
                return [];
            }

            return array_map(function ($item) {
                return [
                    'id' => (string) ($item['id'] ?? ''),
                    'province_id' => (string) ($item['province_id'] ?? ''),
                    'name' => $this->formatRegencyName((string) ($item['name'] ?? '')),
                ];
            }, $raw);
        });

        return response()->json($data);
    }

    /**
     * Dapatkan daftar kecamatan berdasarkan kode kabupaten/kota.
     */
    public function districts(string $regencyCode): JsonResponse
    {
        if (!preg_match('/^[0-9]{1,15}$/', $regencyCode)) {
            return response()->json(['error' => 'Format kode kabupaten/kota tidak valid.'], 400);
        }

        $cacheKey = "wilayah_districts_{$regencyCode}_v1";

        $data = Cache::remember($cacheKey, self::CACHE_TTL_SECONDS, function () use ($regencyCode) {
            $raw = $this->fetchFromSource("/districts/{$regencyCode}.json");

            if (empty($raw)) {
                return [];
            }

            return array_map(function ($item) {
                return [
                    'id' => (string) ($item['id'] ?? ''),
                    'regency_id' => (string) ($item['regency_id'] ?? ''),
                    'name' => $this->formatGeneralName((string) ($item['name'] ?? '')),
                ];
            }, $raw);
        });

        return response()->json($data);
    }

    /**
     * Dapatkan daftar desa/kelurahan berdasarkan kode kecamatan.
     */
    public function villages(string $districtCode): JsonResponse
    {
        if (!preg_match('/^[0-9]{1,20}$/', $districtCode)) {
            return response()->json(['error' => 'Format kode kecamatan tidak valid.'], 400);
        }

        $cacheKey = "wilayah_villages_{$districtCode}_v1";

        $data = Cache::remember($cacheKey, self::CACHE_TTL_SECONDS, function () use ($districtCode) {
            $raw = $this->fetchFromSource("/villages/{$districtCode}.json");

            if (empty($raw)) {
                return [];
            }

            return array_map(function ($item) {
                return [
                    'id' => (string) ($item['id'] ?? ''),
                    'district_id' => (string) ($item['district_id'] ?? ''),
                    'name' => $this->formatGeneralName((string) ($item['name'] ?? '')),
                ];
            }, $raw);
        });

        return response()->json($data);
    }

    /**
     * Request data dari sumber utama dengan fallback mirror, penanganan error,
     * dan fallback data lokal untuk Kalimantan jika semua sumber eksternal gagal.
     */
    protected function fetchFromSource(string $endpoint): array
    {
        $urls = [
            self::PRIMARY_BASE_URL . $endpoint,
            self::MIRROR_BASE_URL . $endpoint,
        ];

        foreach ($urls as $url) {
            try {
                $response = Http::withoutVerifying()
                    ->timeout(6)
                    ->retry(2, 150)
                    ->get($url);

                if ($response->successful()) {
                    $json = $response->json();
                    if (is_array($json) && count($json) > 0) {
                        return $json;
                    }
                }
            } catch (\Throwable $e) {
                Log::warning("Gagal mengambil data wilayah dari {$url}: " . $e->getMessage());
            }
        }

        // Fallback lokal untuk Kalimantan jika semua endpoint eksternal tidak bisa diakses
        return $this->localFallback($endpoint);
    }

    /**
     * Fallback data lokal kabupaten/kota Kalimantan.
     * Digunakan ketika server hosting tidak bisa mengakses API eksternal.
     */
    protected function localFallback(string $endpoint): array
    {
        $localData = [
            '/regencies/61.json' => [
                ['id'=>'6101','province_id'=>'61','name'=>'KABUPATEN SAMBAS'],
                ['id'=>'6102','province_id'=>'61','name'=>'KABUPATEN BENGKAYANG'],
                ['id'=>'6103','province_id'=>'61','name'=>'KABUPATEN LANDAK'],
                ['id'=>'6104','province_id'=>'61','name'=>'KABUPATEN MEMPAWAH'],
                ['id'=>'6105','province_id'=>'61','name'=>'KABUPATEN SANGGAU'],
                ['id'=>'6106','province_id'=>'61','name'=>'KABUPATEN KETAPANG'],
                ['id'=>'6107','province_id'=>'61','name'=>'KABUPATEN SINTANG'],
                ['id'=>'6108','province_id'=>'61','name'=>'KABUPATEN KAPUAS HULU'],
                ['id'=>'6109','province_id'=>'61','name'=>'KABUPATEN SEKADAU'],
                ['id'=>'6110','province_id'=>'61','name'=>'KABUPATEN MELAWI'],
                ['id'=>'6111','province_id'=>'61','name'=>'KABUPATEN KAYONG UTARA'],
                ['id'=>'6112','province_id'=>'61','name'=>'KABUPATEN KUBU RAYA'],
                ['id'=>'6171','province_id'=>'61','name'=>'KOTA PONTIANAK'],
                ['id'=>'6172','province_id'=>'61','name'=>'KOTA SINGKAWANG'],
            ],
            '/regencies/62.json' => [
                ['id'=>'6201','province_id'=>'62','name'=>'KABUPATEN KOTAWARINGIN BARAT'],
                ['id'=>'6202','province_id'=>'62','name'=>'KABUPATEN KOTAWARINGIN TIMUR'],
                ['id'=>'6203','province_id'=>'62','name'=>'KABUPATEN KAPUAS'],
                ['id'=>'6204','province_id'=>'62','name'=>'KABUPATEN BARITO SELATAN'],
                ['id'=>'6205','province_id'=>'62','name'=>'KABUPATEN BARITO UTARA'],
                ['id'=>'6206','province_id'=>'62','name'=>'KABUPATEN SUKAMARA'],
                ['id'=>'6207','province_id'=>'62','name'=>'KABUPATEN LAMANDAU'],
                ['id'=>'6208','province_id'=>'62','name'=>'KABUPATEN SERUYAN'],
                ['id'=>'6209','province_id'=>'62','name'=>'KABUPATEN KATINGAN'],
                ['id'=>'6210','province_id'=>'62','name'=>'KABUPATEN PULANG PISAU'],
                ['id'=>'6211','province_id'=>'62','name'=>'KABUPATEN GUNUNG MAS'],
                ['id'=>'6212','province_id'=>'62','name'=>'KABUPATEN BARITO TIMUR'],
                ['id'=>'6213','province_id'=>'62','name'=>'KABUPATEN MURUNG RAYA'],
                ['id'=>'6271','province_id'=>'62','name'=>'KOTA PALANGKA RAYA'],
            ],
            '/regencies/63.json' => [
                ['id'=>'6301','province_id'=>'63','name'=>'KABUPATEN TANAH LAUT'],
                ['id'=>'6302','province_id'=>'63','name'=>'KABUPATEN KOTABARU'],
                ['id'=>'6303','province_id'=>'63','name'=>'KABUPATEN BANJAR'],
                ['id'=>'6304','province_id'=>'63','name'=>'KABUPATEN BARITO KUALA'],
                ['id'=>'6305','province_id'=>'63','name'=>'KABUPATEN TAPIN'],
                ['id'=>'6306','province_id'=>'63','name'=>'KABUPATEN HULU SUNGAI SELATAN'],
                ['id'=>'6307','province_id'=>'63','name'=>'KABUPATEN HULU SUNGAI TENGAH'],
                ['id'=>'6308','province_id'=>'63','name'=>'KABUPATEN HULU SUNGAI UTARA'],
                ['id'=>'6309','province_id'=>'63','name'=>'KABUPATEN TABALONG'],
                ['id'=>'6310','province_id'=>'63','name'=>'KABUPATEN TANAH BUMBU'],
                ['id'=>'6311','province_id'=>'63','name'=>'KABUPATEN BALANGAN'],
                ['id'=>'6371','province_id'=>'63','name'=>'KOTA BANJARMASIN'],
                ['id'=>'6372','province_id'=>'63','name'=>'KOTA BANJARBARU'],
            ],
            '/regencies/64.json' => [
                ['id'=>'6401','province_id'=>'64','name'=>'KABUPATEN PASER'],
                ['id'=>'6402','province_id'=>'64','name'=>'KABUPATEN KUTAI BARAT'],
                ['id'=>'6403','province_id'=>'64','name'=>'KABUPATEN KUTAI KARTANEGARA'],
                ['id'=>'6404','province_id'=>'64','name'=>'KABUPATEN KUTAI TIMUR'],
                ['id'=>'6405','province_id'=>'64','name'=>'KABUPATEN BERAU'],
                ['id'=>'6409','province_id'=>'64','name'=>'KABUPATEN PENAJAM PASER UTARA'],
                ['id'=>'6411','province_id'=>'64','name'=>'KABUPATEN MAHAKAM ULU'],
                ['id'=>'6471','province_id'=>'64','name'=>'KOTA BALIKPAPAN'],
                ['id'=>'6472','province_id'=>'64','name'=>'KOTA SAMARINDA'],
                ['id'=>'6474','province_id'=>'64','name'=>'KOTA BONTANG'],
            ],
            '/regencies/65.json' => [
                ['id'=>'6501','province_id'=>'65','name'=>'KABUPATEN MALINAU'],
                ['id'=>'6502','province_id'=>'65','name'=>'KABUPATEN BULUNGAN'],
                ['id'=>'6503','province_id'=>'65','name'=>'KABUPATEN TANA TIDUNG'],
                ['id'=>'6504','province_id'=>'65','name'=>'KABUPATEN NUNUKAN'],
                ['id'=>'6571','province_id'=>'65','name'=>'KOTA TARAKAN'],
            ],
        ];

        return $localData[$endpoint] ?? [];
    }

    /**
     * Format nama provinsi menjadi Title Case dengan perlakuan khusus singkatan resmi.
     */
    protected function formatProvinceName(string $name): string
    {
        $name = trim($name);
        if ($name === '') return '';

        $formatted = ucwords(strtolower($name));
        $replacements = [
            'Dki Jakarta' => 'DKI Jakarta',
            'Di Yogyakarta' => 'DI Yogyakarta',
            'Kepulauan Bangka Belitung' => 'Kep. Bangka Belitung',
            'Kepulauan Riau' => 'Kep. Riau',
        ];

        return $replacements[$formatted] ?? $formatted;
    }

    /**
     * Format nama kabupaten/kota menjadi rapi (misal: Kab. Kubu Raya, Kota Pontianak).
     */
    protected function formatRegencyName(string $name): string
    {
        $name = trim($name);
        if ($name === '') return '';

        if (stripos($name, 'KABUPATEN ') === 0) {
            return 'Kab. ' . ucwords(strtolower(substr($name, 10)));
        }

        if (stripos($name, 'KOTA ') === 0) {
            return 'Kota ' . ucwords(strtolower(substr($name, 5)));
        }

        return ucwords(strtolower($name));
    }

    /**
     * Format nama umum (kecamatan / kelurahan).
     */
    protected function formatGeneralName(string $name): string
    {
        $name = trim($name);
        if ($name === '') return '';

        return ucwords(strtolower($name));
    }

    /**
     * Fallback daftar provinsi resmi Indonesia jika koneksi internet server terputus.
     */
    protected function fallbackProvinces(): array
    {
        return [
            ['id' => '61', 'name' => 'Kalimantan Barat'],
            ['id' => '62', 'name' => 'Kalimantan Tengah'],
            ['id' => '63', 'name' => 'Kalimantan Selatan'],
            ['id' => '64', 'name' => 'Kalimantan Timur'],
            ['id' => '65', 'name' => 'Kalimantan Utara'],
            ['id' => '11', 'name' => 'Aceh'],
            ['id' => '12', 'name' => 'Sumatera Utara'],
            ['id' => '13', 'name' => 'Sumatera Barat'],
            ['id' => '14', 'name' => 'Riau'],
            ['id' => '15', 'name' => 'Jambi'],
            ['id' => '16', 'name' => 'Sumatera Selatan'],
            ['id' => '17', 'name' => 'Bengkulu'],
            ['id' => '18', 'name' => 'Lampung'],
            ['id' => '19', 'name' => 'Kep. Bangka Belitung'],
            ['id' => '21', 'name' => 'Kep. Riau'],
            ['id' => '31', 'name' => 'DKI Jakarta'],
            ['id' => '32', 'name' => 'Jawa Barat'],
            ['id' => '33', 'name' => 'Jawa Tengah'],
            ['id' => '34', 'name' => 'DI Yogyakarta'],
            ['id' => '35', 'name' => 'Jawa Timur'],
            ['id' => '36', 'name' => 'Banten'],
            ['id' => '51', 'name' => 'Bali'],
            ['id' => '52', 'name' => 'Nusa Tenggara Barat'],
            ['id' => '53', 'name' => 'Nusa Tenggara Timur'],
            ['id' => '71', 'name' => 'Sulawesi Utara'],
            ['id' => '72', 'name' => 'Sulawesi Tengah'],
            ['id' => '73', 'name' => 'Sulawesi Selatan'],
            ['id' => '74', 'name' => 'Sulawesi Tenggara'],
            ['id' => '75', 'name' => 'Gorontalo'],
            ['id' => '76', 'name' => 'Sulawesi Barat'],
            ['id' => '81', 'name' => 'Maluku'],
            ['id' => '82', 'name' => 'Maluku Utara'],
            ['id' => '91', 'name' => 'Papua Barat'],
            ['id' => '94', 'name' => 'Papua'],
        ];
    }
}
