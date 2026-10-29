<?php

namespace App\Http\Controllers;

use App\Services\BmkgWeatherService;
use App\Services\FirmsHotspotService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PantauanKarhutlaController extends Controller
{
    protected FirmsHotspotService $hotspotService;
    protected BmkgWeatherService $weatherService;

    public function __construct(FirmsHotspotService $hotspotService, BmkgWeatherService $weatherService)
    {
        $this->hotspotService = $hotspotService;
        $this->weatherService = $weatherService;
    }

    /**
     * Tampilkan halaman Pantauan Karhutla.
     */
    public function index(Request $request): View
    {
        $days = (int) $request->get('days', 1);
        if ($days < 1 || $days > 7) {
            $days = 1;
        }

        $firmsData = $this->hotspotService->getKalimantanHotspots($days);
        $weatherData = $this->weatherService->getKalimantanWeather();

        return view('pages.pantauan', [
            'firms' => $firmsData,
            'weather' => $weatherData,
            'days' => $days,
        ]);
    }

    /**
     * Endpoint API JSON untuk peta interaktif & filter regional.
     */
    public function apiData(Request $request): JsonResponse
    {
        $days = (int) $request->get('days', 1);
        if ($days < 1 || $days > 7) {
            $days = 1;
        }

        $firmsData = $this->hotspotService->getKalimantanHotspots($days);
        $weatherData = $this->weatherService->getKalimantanWeather();

        return response()->json([
            'firms' => $firmsData,
            'weather' => $weatherData,
            'meta' => [
                'server_time' => now()->toIso8601String(),
                'bbox' => '108.5,-4.5,119.5,4.5',
            ]
        ]);
    }
}
