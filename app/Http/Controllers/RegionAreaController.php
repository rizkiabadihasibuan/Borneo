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
     * Request data dari sumber utama dengan fallback mirror dan penanganan error.
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
                    if (is_array($json)) {
                        return $json;
                    }
                }
            } catch (\Throwable $e) {
                Log::warning("Gagal mengambil data wilayah dari {$url}: " . $e->getMessage());
            }
        }

        return [];
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
