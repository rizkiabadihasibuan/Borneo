<?php

namespace App\Http\Controllers;

use App\Services\ExternalNewsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NewsController extends Controller
{
    protected ExternalNewsService $newsService;

    public function __construct(ExternalNewsService $newsService)
    {
        $this->newsService = $newsService;
    }

    /**
     * Tampilkan halaman Kabar & Warta Karhutla.
     */
    public function index(Request $request): View
    {
        $region = (string) $request->get('region', 'all');
        $keyword = $request->get('q');
        $forceRefresh = (bool) $request->has('refresh');

        $news = $this->newsService->getKarhutlaNews($region, $keyword, 30, $forceRefresh);

        return view('pages.berita', [
            'news' => $news,
            'currentRegion' => $region,
            'keyword' => $keyword,
            'regions' => ExternalNewsService::REGION_NAMES,
        ]);
    }

    /**
     * Endpoint API JSON untuk pembaruan asinkron atau integrasi widget.
     */
    public function apiData(Request $request): JsonResponse
    {
        $region = (string) $request->get('region', 'all');
        $keyword = $request->get('q');
        $limit = min(50, max(1, (int) $request->get('limit', 20)));
        $forceRefresh = (bool) $request->has('refresh');

        $news = $this->newsService->getKarhutlaNews($region, $keyword, $limit, $forceRefresh);

        return response()->json($news);
    }
}
