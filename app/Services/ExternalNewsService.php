<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ExternalNewsService
{
    /**
     * Pemetaan kata kunci pencarian berita berdasarkan wilayah.
     * Query fokus HANYA pada kebakaran hutan/lahan (karhutla) di Kalimantan.
     * Versi NewsAPI lebih pendek (NewsAPI max ~500 karakter query).
     * Versi RSS lebih panjang untuk Google News.
     */
    protected const REGION_QUERIES = [
        'all'     => 'karhutla kalimantan OR "kebakaran hutan" kalimantan OR "kebakaran lahan" kalimantan',
        'kalbar'  => 'karhutla "kalimantan barat" OR "kebakaran" pontianak OR "kebakaran" ketapang OR "kebakaran" "kubu raya"',
        'kalteng' => 'karhutla "kalimantan tengah" OR "kebakaran" "palangka raya" OR "kebakaran" kotim OR "kebakaran" kapuas',
        'kalsel'  => 'karhutla "kalimantan selatan" OR "kebakaran" banjarbaru OR "kebakaran" banjarmasin OR "kebakaran" "tanah laut"',
        'kaltim'  => 'karhutla "kalimantan timur" OR "kebakaran" samarinda OR "kebakaran" balikpapan OR "kebakaran" kutai',
        'kaltara' => 'karhutla "kalimantan utara" OR "kebakaran" tarakan OR "kebakaran" bulungan OR "kebakaran" nunukan',
    ];

    // Query pendek khusus NewsAPI (lebih simple = lebih banyak hasil)
    protected const NEWSAPI_QUERIES = [
        'all'     => 'karhutla kalimantan OR kebakaran kalimantan',
        'kalbar'  => 'karhutla "kalimantan barat" OR kebakaran pontianak OR kebakaran ketapang',
        'kalteng' => 'karhutla "kalimantan tengah" OR kebakaran "palangka raya" OR karhutla kotim',
        'kalsel'  => 'karhutla "kalimantan selatan" OR kebakaran banjarbaru OR kebakaran banjarmasin',
        'kaltim'  => 'karhutla "kalimantan timur" OR kebakaran samarinda OR kebakaran balikpapan',
        'kaltara' => 'karhutla "kalimantan utara" OR kebakaran tarakan OR kebakaran bulungan',
    ];

    /**
     * Kata kunci wajib — artikel HARUS mengandung salah satu dari ini.
     * Artikel yang tidak menyebut kebakaran/karhutla akan dibuang.
     */
    protected const REQUIRED_FIRE_KEYWORDS = [
        'karhutla', 'kebakaran', 'kebakaran hutan', 'kebakaran lahan',
        'titik api', 'titik panas', 'hotspot', 'asap', 'kabut asap',
        'api', 'terbakar', 'membakar', 'pembakaran', 'lahan terbakar',
        'hutan terbakar', 'gambut terbakar',
    ];

    /** Label nama provinsi. */
    public const REGION_NAMES = [
        'all'     => 'Seluruh Kalimantan',
        'kalbar'  => 'Kalimantan Barat',
        'kalteng' => 'Kalimantan Tengah',
        'kalsel'  => 'Kalimantan Selatan',
        'kaltim'  => 'Kalimantan Timur',
        'kaltara' => 'Kalimantan Utara',
    ];

    // -------------------------------------------------------------------------

    public function getKarhutlaNews(string $region = 'all', ?string $keyword = null, int $limit = 30, bool $forceRefresh = false): array
    {
        $region   = array_key_exists($region, self::REGION_QUERIES) ? $region : 'all';
        $cacheTtl = (int) config('services.news.cache_ttl', 900);

        // Sertakan tanggal hari ini dalam cache key → cache otomatis beda setiap hari
        $today    = now()->setTimezone('Asia/Jakarta')->format('Y-m-d');
        $cacheKey = 'external_news_karhutla_' . $region . '_' . md5((string) $keyword) . '_' . $limit . '_' . $today;

        if ($forceRefresh) {
            Cache::forget($cacheKey);
        }

        return Cache::remember($cacheKey, $cacheTtl, function () use ($region, $keyword, $limit, $today) {
            // Gabungkan dari dua sumber: NewsAPI (ada foto) + Google RSS (lebih banyak & fresh)
            // Hasilnya digabung, deduplikasi, dan diurutkan terbaru dulu
            $newsApiItems  = $this->fetchFromNewsApi($region, $keyword, $limit, $today);
            $rssItems      = $this->fetchFromGoogleNews($region, $keyword, $limit);

            $newsApiList   = $newsApiItems['items'] ?? [];
            $rssList       = $rssItems['items'] ?? [];

            // Gabungkan: NewsAPI duluan (punya foto asli), lalu RSS untuk melengkapi
            $merged = $this->mergeAndDeduplicate($newsApiList, $rssList, $limit);

            // Jika keduanya gagal total, pakai fallback kurator
            if (empty($merged)) {
                return $this->getFallbackCuratedNews($region);
            }
            $source = !empty($newsApiList) && !empty($rssList)
                ? 'NewsAPI.org + Google News RSS'
                : (!empty($newsApiList) ? 'NewsAPI.org' : 'Google News RSS');

            return [
                'status'            => 'success',
                'region'            => $region,
                'region_name'       => self::REGION_NAMES[$region] ?? 'Kalimantan',
                'total'             => count($merged),
                'items'             => $merged,
                'last_synced'       => now()->toIso8601String(),
                'last_synced_human' => now()->locale('id')->diffForHumans(),
                'source'            => 'Situs Berita Terverifikasi via ' . $source,
            ];
        });
    }

    /**
     * Gabungkan dua list berita, deduplikasi berdasarkan judul mirip,
     * urutkan terbaru dulu, potong sesuai limit.
     */
    protected function mergeAndDeduplicate(array $primary, array $secondary, int $limit): array
    {
        $all   = [];
        $seen  = []; // key: slug pendek judul untuk deteksi duplikat

        foreach (array_merge($primary, $secondary) as $item) {
            // Buat fingerprint dari 40 karakter pertama judul (lowercase, alfanumerik)
            $fp = substr(preg_replace('/[^a-z0-9]/i', '', strtolower($item['title'] ?? '')), 0, 40);
            if (empty($fp) || isset($seen[$fp])) continue;
            $seen[$fp] = true;
            $all[] = $item;
        }

        // Urutkan: terbaru dulu berdasarkan published_at
        usort($all, function ($a, $b) {
            return strcmp($b['published_at'] ?? '', $a['published_at'] ?? '');
        });

        return array_slice($all, 0, $limit);
    }

    // =========================================================================
    // Sumber 1: NewsAPI.org — foto nyata dari artikel sumber
    // =========================================================================

    protected function fetchFromNewsApi(string $region, ?string $keyword, int $limit, string $today): ?array
    {
        $keys = array_values(array_filter((array) config('services.news.api_keys', [])));
        if (empty($keys)) {
            return null;
        }

        // Pakai query pendek NewsAPI untuk maksimalkan hasil
        $baseQuery = self::NEWSAPI_QUERIES[$region] ?? self::NEWSAPI_QUERIES['all'];
        $query     = !empty($keyword)
            ? '(' . $baseQuery . ') AND (' . trim(strip_tags($keyword)) . ')'
            : $baseQuery;

        // Ambil dari 2 hari ke belakang — cukup untuk dapat berita terkini
        $from = now()->setTimezone('Asia/Jakarta')->subDays(2)->format('Y-m-d');

        foreach ($keys as $apiKey) {
            $params = [
                'q'        => $query,
                'language' => 'id',
                'sortBy'   => 'publishedAt',
                'from'     => $from,
                'pageSize' => 100,
                'apiKey'   => $apiKey,
            ];

            try {
                $response = Http::withoutVerifying()
                    ->timeout(10)
                    ->withHeaders([
                        'User-Agent' => 'BorneoSiaga/1.0 (Laravel; karhutla monitoring)',
                        'Accept'     => 'application/json',
                    ])
                    ->get('https://newsapi.org/v2/everything', $params);

                if (!$response->successful()) {
                    Log::info('NewsAPI HTTP ' . $response->status() . ' key=' . substr($apiKey, 0, 8));
                    // Jika 426 (free tier limit), coba tanpa parameter 'from'
                    if ($response->status() === 426 || $response->status() === 401) {
                        unset($params['from']);
                        $params['sortBy'] = 'publishedAt';
                        $response2 = Http::withoutVerifying()->timeout(10)
                            ->get('https://newsapi.org/v2/everything', $params);
                        if ($response2->successful()) {
                            $response = $response2;
                        }
                    }
                    if (!$response->successful()) continue;
                }

                $data = $response->json();

                if (($data['status'] ?? '') !== 'ok' || empty($data['articles'])) {
                    Log::info('NewsAPI response: ' . ($data['message'] ?? 'no articles'));
                    continue;
                }

                $items = [];
                foreach ($data['articles'] as $article) {
                    $parsed = $this->parseNewsApiArticle($article, $region);
                    if ($parsed !== null) {
                        $items[] = $parsed;
                    }
                }

                if (empty($items)) continue;

                return ['items' => $items];

            } catch (\Throwable $e) {
                Log::warning('NewsAPI exception (' . substr($apiKey, 0, 8) . '): ' . $e->getMessage());
            }
        }

        return null;
    }

    protected function parseNewsApiArticle(array $article, string $selectedRegion): ?array
    {
        $title       = trim($article['title'] ?? '');
        $description = trim($article['description'] ?? '');
        $url         = trim($article['url'] ?? '');
        $imageUrl    = trim($article['urlToImage'] ?? '');
        $sourceName  = trim($article['source']['name'] ?? 'Media Terverifikasi');
        $publishedAt = $article['publishedAt'] ?? null;

        // Buang artikel placeholder / kosong
        if (empty($title) || $title === '[Removed]' || empty($url) || $url === 'https://removed.com') {
            return null;
        }

        // ── FILTER WAJIB: artikel HARUS menyebut kata terkait kebakaran ──────
        $combinedText = strtolower($title . ' ' . $description);
        $hasFireKeyword = false;
        foreach (self::REQUIRED_FIRE_KEYWORDS as $kw) {
            if (str_contains($combinedText, $kw)) {
                $hasFireKeyword = true;
                break;
            }
        }
        if (!$hasFireKeyword) {
            return null; // buang — tidak ada unsur kebakaran
        }
        // ─────────────────────────────────────────────────────────────────────

        // Pisahkan nama sumber dari judul jika ada " - Nama Media"
        if (!empty($sourceName) && str_ends_with($title, ' - ' . $sourceName)) {
            $title = trim(substr($title, 0, -strlen(' - ' . $sourceName)));
        }

        if (empty($description) || $description === '[Removed]') {
            $description = 'Informasi seputar perkembangan pemantauan karhutla, mitigasi kabut asap, dan kesiapsiagaan tim tanggap di bentang alam Kalimantan.';
        }

        // Normalisasi tanggal
        try {
            $date          = Carbon::parse($publishedAt)->setTimezone('Asia/Jakarta');
            $formattedDate = $date->locale('id')->isoFormat('D MMMM Y, HH:mm') . ' WIB';
            $humanDiff     = $date->locale('id')->diffForHumans();
        } catch (\Exception) {
            $date          = now();
            $formattedDate = $date->locale('id')->isoFormat('D MMMM Y, HH:mm') . ' WIB';
            $humanDiff     = 'Baru saja';
        }

        $detectedRegion = $this->detectRegionTag($title . ' ' . $description, $selectedRegion);
        $topic          = $this->detectTopicTag($title . ' ' . $description);

        // Gambar: pakai urlToImage jika ada dan bukan null/placeholder, fallback ke lokal
        $image = (!empty($imageUrl) && str_starts_with($imageUrl, 'http'))
            ? $imageUrl
            : $this->getTopicImage($topic['name']);

        return [
            'title'               => $title,
            'source'              => $sourceName,
            'source_url'          => $article['source']['url'] ?? '',
            'link'                => $url,
            'description'         => $description,
            'image'               => $image,
            'published_at'        => $date->toIso8601String(),
            'published_formatted' => $formattedDate,
            'published_human'     => $humanDiff,
            'region_code'         => $detectedRegion['code'],
            'region_name'         => $detectedRegion['name'],
            'topic'               => $topic['name'],
            'topic_color'         => $topic['color'],
        ];
    }

    // =========================================================================
    // Sumber 2: Google News RSS — fallback jika NewsAPI gagal
    // =========================================================================

    /**
     * Mengambil berita dari feed Google News RSS Indonesia.
     */
    protected function fetchFromGoogleNews(string $region, ?string $keyword, int $limit): array
    {
        $baseQuery = self::REGION_QUERIES[$region] ?? self::REGION_QUERIES['all'];

        if (!empty($keyword)) {
            $cleanedKeyword = trim(strip_tags($keyword));
            $query = "({$baseQuery}) AND ({$cleanedKeyword})";
        } else {
            $query = $baseQuery;
        }

        $feedUrl = 'https://news.google.com/rss/search?q=' . urlencode($query) . '&hl=id&gl=ID&ceid=ID:id';

        try {
            $response = Http::withoutVerifying()
                ->timeout(7)
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36 BorneoSiagaBot/1.0',
                    'Accept' => 'application/rss+xml, application/xml, text/xml',
                ])
                ->get($feedUrl);

            if ($response->successful()) {
                $body = $response->body();
                $xml = @simplexml_load_string($body, 'SimpleXMLElement', LIBXML_NOCDATA);

                if ($xml && isset($xml->channel->item)) {
                    $items = [];
                    foreach ($xml->channel->item as $xmlItem) {
                        $parsed = $this->parseXmlItem($xmlItem, $region);
                        if ($parsed !== null) {
                            $items[] = $parsed;
                        }

                        if (count($items) >= $limit) {
                            break;
                        }
                    }

                    // Gambar sudah di-set per item di parseXmlItem berdasarkan topik

                    return ['items' => $items];
                }
            }
        } catch (\Throwable $e) {
            Log::warning('ExternalNewsService Google News error: ' . $e->getMessage());
        }

        // Jika koneksi gagal, sediakan kurasi berita rujukan darurat
        return ['items' => []];
    }

    /**
     * Parsing dan normalisasi setiap item XML berita.
     */
    protected function parseXmlItem(\SimpleXMLElement $item, string $selectedRegion): ?array
    {
        $rawTitle = (string) $item->title;
        $link = (string) $item->link;
        $pubDateRaw = (string) $item->pubDate;
        $rawDescription = (string) $item->description;
        $sourceName = (string) ($item->source ?? '');
        $sourceUrl = (string) ($item->source['url'] ?? '');

        // Pisahkan nama media dari judul jika ada akhiran " - Nama Media"
        $title = $rawTitle;
        if (empty($sourceName) && str_contains($rawTitle, ' - ')) {
            $parts = explode(' - ', $rawTitle);
            $sourceName = trim(array_pop($parts));
            $title = trim(implode(' - ', $parts));
        } elseif (!empty($sourceName) && str_ends_with($rawTitle, ' - ' . $sourceName)) {
            $title = trim(substr($rawTitle, 0, -strlen(' - ' . $sourceName)));
        }

        if (empty($sourceName)) {
            $sourceName = 'Media Terverifikasi';
        }

        // Bersihkan deskripsi dari tag HTML/link Google News
        $cleanDesc = strip_tags(html_entity_decode($rawDescription));
        if (str_ends_with($cleanDesc, $sourceName)) {
            $cleanDesc = trim(substr($cleanDesc, 0, -strlen($sourceName)));
        }
        if (empty($cleanDesc) || $cleanDesc === $title) {
            $cleanDesc = 'Informasi seputar perkembangan pemantauan karhutla, mitigasi kabut asap, dan kesiapsiagaan tim tanggap di bentang alam Kalimantan.';
        }

        // ── FILTER WAJIB: hanya artikel yang mengandung unsur kebakaran ─────
        $combinedText = strtolower($title . ' ' . $cleanDesc);
        $hasFireKeyword = false;
        foreach (self::REQUIRED_FIRE_KEYWORDS as $kw) {
            if (str_contains($combinedText, $kw)) {
                $hasFireKeyword = true;
                break;
            }
        }
        if (!$hasFireKeyword) {
            return null;
        }
        // ─────────────────────────────────────────────────────────────────────

        // Normalisasi tanggal publikasi
        try {
            $date = Carbon::parse($pubDateRaw)->setTimezone('Asia/Jakarta');
            $formattedDate = $date->locale('id')->isoFormat('D MMMM Y, HH:mm') . ' WIB';
            $humanDiff = $date->locale('id')->diffForHumans();
        } catch (\Exception) {
            $date = now();
            $formattedDate = $date->locale('id')->isoFormat('D MMMM Y, HH:mm') . ' WIB';
            $humanDiff = 'Baru saja';
        }

        // Tentukan tag wilayah provinsi
        $detectedRegion = $this->detectRegionTag($title . ' ' . $cleanDesc, $selectedRegion);

        // Kategori topik tematik
        $topic = $this->detectTopicTag($title . ' ' . $cleanDesc);

        // Gambar: coba dari RSS dulu, fallback ke gambar lokal berdasarkan topik
        $imageUrl = $this->extractImageUrl($item, $rawDescription) ?? $this->getTopicImage($topic['name']);

        return [
            'title' => $title,
            'source' => $sourceName,
            'source_url' => $sourceUrl,
            'link' => $link,
            'description' => $cleanDesc,
            'image' => $imageUrl,
            'published_at' => $date->toIso8601String(),
            'published_formatted' => $formattedDate,
            'published_human' => $humanDiff,
            'region_code' => $detectedRegion['code'],
            'region_name' => $detectedRegion['name'],
            'topic' => $topic['name'],
            'topic_color' => $topic['color'],
        ];
    }

    /**
     * Ekstrak URL gambar dari item RSS.
     * Urutan prioritas: <enclosure> → <img> dalam <description> CDATA → null.
     */
    protected function extractImageUrl(\SimpleXMLElement $item, string $rawDescription): ?string
    {
        // 1. Cek elemen <enclosure url="..." type="image/...">
        if (isset($item->enclosure)) {
            $encUrl  = (string) ($item->enclosure['url'] ?? '');
            $encType = (string) ($item->enclosure['type'] ?? '');
            if (!empty($encUrl) && str_starts_with($encType, 'image/')) {
                return $encUrl;
            }
        }

        // 2. Cek <media:content> atau <media:thumbnail> (namespace media)
        $namespaces = $item->getNameSpaces(true);
        if (isset($namespaces['media'])) {
            $media = $item->children($namespaces['media']);
            if (isset($media->content)) {
                $url = (string) ($media->content['url'] ?? '');
                if (!empty($url)) return $url;
            }
            if (isset($media->thumbnail)) {
                $url = (string) ($media->thumbnail['url'] ?? '');
                if (!empty($url)) return $url;
            }
        }

        // 3. Ekstrak <img src="..."> pertama dari <description> CDATA
        //    Google News biasanya menyertakan thumbnail di sini
        if (!empty($rawDescription)) {
            if (preg_match('/<img[^>]+src=["\']([^"\']+)["\'][^>]*>/i', $rawDescription, $matches)) {
                $src = $matches[1];
                // Hanya terima URL http/https, tolak data URI
                if (str_starts_with($src, 'http://') || str_starts_with($src, 'https://')) {
                    return $src;
                }
            }
        }

        return null;
    }

    /**
     * Tentukan gambar ilustrasi berdasarkan topik berita.
     * Menggunakan gambar lokal yang sudah tersedia agar cepat dan andal.
     */
    protected function getTopicImage(string $topic): string
    {
        return match (true) {
            str_contains($topic, 'Pemadaman') => asset('images/news-karhutla-patrol.jpg'),
            str_contains($topic, 'Kabut')     => asset('images/news-karhutla-smoke.jpg'),
            str_contains($topic, 'Hotspot')   => asset('images/news-karhutla-satellite.jpg'),
            str_contains($topic, 'Hukum')     => asset('images/news-karhutla-law.jpg'),
            str_contains($topic, 'Kebijakan') => asset('images/news-karhutla-forest.jpg'),
            default                           => asset('images/news-karhutla-fire.jpg'),
        };
    }

    /**
     * Deteksi wilayah provinsi dari teks judul atau deskripsi.
     */
    protected function detectRegionTag(string $text, string $defaultRegion): array
    {
        $textLower = strtolower($text);

        if (str_contains($textLower, 'kalbar') || str_contains($textLower, 'kalimantan barat') || str_contains($textLower, 'pontianak') || str_contains($textLower, 'kubu raya') || str_contains($textLower, 'ketapang') || str_contains($textLower, 'sambas')) {
            return ['code' => 'kalbar', 'name' => 'Kalimantan Barat'];
        }

        if (str_contains($textLower, 'kalteng') || str_contains($textLower, 'kalimantan tengah') || str_contains($textLower, 'palangka raya') || str_contains($textLower, 'kotim') || str_contains($textLower, 'kapuas') || str_contains($textLower, 'kobar')) {
            return ['code' => 'kalteng', 'name' => 'Kalimantan Tengah'];
        }

        if (str_contains($textLower, 'kalsel') || str_contains($textLower, 'kalimantan selatan') || str_contains($textLower, 'banjarmasin') || str_contains($textLower, 'banjarbaru') || str_contains($textLower, 'tanah laut')) {
            return ['code' => 'kalsel', 'name' => 'Kalimantan Selatan'];
        }

        if (str_contains($textLower, 'kaltim') || str_contains($textLower, 'kalimantan timur') || str_contains($textLower, 'samarinda') || str_contains($textLower, 'balikpapan') || str_contains($textLower, 'kutai') || str_contains($textLower, 'ikn')) {
            return ['code' => 'kaltim', 'name' => 'Kalimantan Timur'];
        }

        if (str_contains($textLower, 'kaltara') || str_contains($textLower, 'kalimantan utara') || str_contains($textLower, 'tarakan') || str_contains($textLower, 'bulungan') || str_contains($textLower, 'nunukan')) {
            return ['code' => 'kaltara', 'name' => 'Kalimantan Utara'];
        }

        if ($defaultRegion !== 'all' && isset(self::REGION_NAMES[$defaultRegion])) {
            return ['code' => $defaultRegion, 'name' => self::REGION_NAMES[$defaultRegion]];
        }

        return ['code' => 'all', 'name' => 'Lintas Kalimantan'];
    }

    /**
     * Deteksi kategori topik karhutla.
     */
    protected function detectTopicTag(string $text): array
    {
        $textLower = strtolower($text);

        if (str_contains($textLower, 'padam') || str_contains($textLower, 'manggala agni') || str_contains($textLower, 'water bombing') || str_contains($textLower, 'helikopter') || str_contains($textLower, 'sumur bor') || str_contains($textLower, 'patroli')) {
            return ['name' => 'Aksi Pemadaman', 'color' => '#B85324'];
        }

        if (str_contains($textLower, 'ispu') || str_contains($textLower, 'asap') || str_contains($textLower, 'masker') || str_contains($textLower, 'kualitas udara') || str_contains($textLower, 'pm2.5') || str_contains($textLower, 'ispa')) {
            return ['name' => 'Kabut Asap & Kesehatan', 'color' => '#8C5A2B'];
        }

        if (str_contains($textLower, 'hotspot') || str_contains($textLower, 'titik panas') || str_contains($textLower, 'satelit') || str_contains($textLower, 'bmkg') || str_contains($textLower, 'cuaca') || str_contains($textLower, 'kemarau')) {
            return ['name' => 'Pantauan Hotspot', 'color' => '#A33E2B'];
        }

        if (str_contains($textLower, 'hukum') || str_contains($textLower, 'polisi') || str_contains($textLower, 'polda') || str_contains($textLower, 'tersangka') || str_contains($textLower, 'segel') || str_contains($textLower, 'pidana')) {
            return ['name' => 'Penegakan Hukum', 'color' => '#445E4C'];
        }

        if (str_contains($textLower, 'gubernur') || str_contains($textLower, 'bantuan') || str_contains($textLower, 'sarpras') || str_contains($textLower, 'siaga darurat') || str_contains($textLower, 'anggaran') || str_contains($textLower, 'pemprov')) {
            return ['name' => 'Kebijakan & Siaga', 'color' => '#2D4E3C'];
        }

        return ['name' => 'Kabar Karhutla', 'color' => '#263B30'];
    }

    /**
     * Fallback kurasi jika jaringan eksternal bermasalah.
     */
    protected function getFallbackCuratedNews(string $region): array
    {
        $items = [
            [
                'title' => 'Penguatan Kesiapsiagaan Posko dan Satgas Karhutla di Wilayah Rentan Gambut',
                'source' => 'Pusat Tanggap Karhutla',
                'source_url' => 'https://sipongi.menlhk.go.id/',
                'link' => 'https://sipongi.menlhk.go.id/',
                'description' => 'Satgas gabungan BPBD, Manggala Agni, dan Masyarakat Peduli Api terus mengintensifkan patroli mandiri serta pembasahan sekat kanal gambut.',
                'published_at' => now()->subHours(2)->toIso8601String(),
                'published_formatted' => now()->subHours(2)->locale('id')->isoFormat('D MMMM Y, HH:mm') . ' WIB',
                'published_human' => '2 jam yang lalu',
                'region_code' => $region === 'all' ? 'kalteng' : $region,
                'region_name' => self::REGION_NAMES[$region] ?? 'Kalimantan Tengah',
                'topic' => 'Aksi Pemadaman',
                'topic_color' => '#B85324',
                'image' => asset('images/news-karhutla-patrol.jpg'),
            ],
            [
                'title' => 'BMKG Rilis Prakiraan Wilayah Berpotensi Kering dan Kerawanan Karhutla Kalimantan',
                'source' => 'BMKG Rujukan Publik',
                'source_url' => 'https://www.bmkg.go.id/',
                'link' => 'https://www.bmkg.go.id/',
                'description' => 'Masyarakat diimbau tidak melakukan pembukaan lahan pertanian dengan cara membakar selama indeks kelembapan tanah masih di level waspada.',
                'published_at' => now()->subHours(5)->toIso8601String(),
                'published_formatted' => now()->subHours(5)->locale('id')->isoFormat('D MMMM Y, HH:mm') . ' WIB',
                'published_human' => '5 jam yang lalu',
                'region_code' => 'all',
                'region_name' => 'Lintas Kalimantan',
                'topic' => 'Pantauan Hotspot',
                'topic_color' => '#A33E2B',
                'image' => asset('images/news-karhutla-satellite.jpg'),
            ],
            [
                'title' => 'Peningkatan Patroli Sekat Kanal dan Pengawasan Lahan Gambut Bersama Komunitas Warga',
                'source' => 'Portal Lingkungan Nusantara',
                'source_url' => 'https://menlhk.go.id/',
                'link' => 'https://menlhk.go.id/',
                'description' => 'Pendekatan berbasis kearifan lokal terus didorong demi menjaga ekosistem hutan tropis dan meminimalisir risiko kebakaran bawah tanah.',
                'published_at' => now()->subDay()->toIso8601String(),
                'published_formatted' => now()->subDay()->locale('id')->isoFormat('D MMMM Y, HH:mm') . ' WIB',
                'published_human' => '1 hari yang lalu',
                'region_code' => 'kalbar',
                'region_name' => 'Kalimantan Barat',
                'topic' => 'Kebijakan & Siaga',
                'topic_color' => '#2D4E3C',
                'image' => asset('images/news-karhutla-forest.jpg'),
            ],
        ];

        return [
            'status'            => 'fallback',
            'region'            => $region,
            'region_name'       => self::REGION_NAMES[$region] ?? 'Kalimantan',
            'total'             => count($items),
            'items'             => $items,
            'last_synced'       => now()->toIso8601String(),
            'last_synced_human' => 'Baru saja',
            'source'            => 'Warta Rujukan Resmi Borneo Siaga',
        ];
    }
}
