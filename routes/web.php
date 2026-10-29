<?php

use App\Http\Controllers\NewsController;
use App\Http\Controllers\PantauanKarhutlaController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TrackReportController;
use App\Services\ExternalNewsService;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - Borneo Siaga
|--------------------------------------------------------------------------
| Fondasi rute navigasi publik sistem informasi & tanggap karhutla Kalimantan.
*/

Route::get('/', function (ExternalNewsService $newsService) {
    $latestNews = $newsService->getKarhutlaNews('all', null, 4);
    return view('pages.home', [
        'latestNews' => $latestNews['items'] ?? [],
        'newsMeta' => $latestNews,
    ]);
})->name('home');

Route::get('/pantauan-karhutla', [PantauanKarhutlaController::class, 'index'])->name('pantauan.karhutla');
Route::get('/pantauan-karhutla/api', [PantauanKarhutlaController::class, 'apiData'])->name('pantauan.karhutla.api');

Route::get('/berita', [NewsController::class, 'index'])->name('berita.index');
Route::get('/berita/api', [NewsController::class, 'apiData'])->name('berita.api');

Route::get('/pengaduan', [ReportController::class, 'create'])->name('pengaduan');
Route::post('/pengaduan', [ReportController::class, 'store'])->middleware('throttle:10,1')->name('pengaduan.store');

Route::get('/lacak-pengaduan', [TrackReportController::class, 'show'])->name('lacak.pengaduan');
Route::post('/lacak-pengaduan', [TrackReportController::class, 'track'])->middleware('throttle:20,1')->name('lacak.pengaduan.track');

Route::get('/edukasi', function (\Illuminate\Http\Request $request, ExternalNewsService $newsService) {
    $region = (string) $request->get('region', 'all');
    $keyword = $request->get('q');
    $forceRefresh = (bool) $request->has('refresh');

    $news = $newsService->getKarhutlaNews($region, $keyword, 24, $forceRefresh);

    return view('pages.edukasi', [
        'news' => $news,
        'currentRegion' => $region,
        'keyword' => $keyword,
        'regions' => ExternalNewsService::REGION_NAMES,
    ]);
})->name('edukasi');

Route::get('/tentang-kami', function () {
    return view('pages.tentang');
})->name('tentang.kami');

// Route Tanda Tangan Kriptografis Akses Foto Disposisi (Signed URL)
Route::get('/disposisi-foto/{dispatch}', [\App\Http\Controllers\DispatchPhotoController::class, 'view'])
    ->middleware('signed')
    ->name('dispatch.photo.view');

/*
|--------------------------------------------------------------------------
| Admin & Posko Management Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->group(function () {
    // Autentikasi Admin
    Route::get('/login', [\App\Http\Controllers\Admin\AdminAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [\App\Http\Controllers\Admin\AdminAuthController::class, 'login'])->middleware('throttle:5,1')->name('admin.login.submit');
    Route::post('/logout', [\App\Http\Controllers\Admin\AdminAuthController::class, 'logout'])->name('admin.logout');

    // Area Terproteksi Administrator
    Route::middleware(['admin'])->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'index'])->name('admin.dashboard');
        
        // Manajemen Pengaduan
        Route::get('/pengaduan', [\App\Http\Controllers\Admin\AdminReportController::class, 'index'])->name('admin.reports.index');
        Route::get('/pengaduan/{report}', [\App\Http\Controllers\Admin\AdminReportController::class, 'show'])->name('admin.reports.show');
        Route::post('/pengaduan/{report}/status', [\App\Http\Controllers\Admin\AdminReportController::class, 'updateStatus'])->name('admin.reports.update_status');
        Route::get('/pengaduan/{report}/foto', [\App\Http\Controllers\Admin\AdminReportController::class, 'photo'])->name('admin.reports.photo');

        // Direktori Penerima Laporan (Instansi Pemerintah & MPA)
        Route::get('/penerima', [\App\Http\Controllers\Admin\ReportRecipientController::class, 'index'])->name('admin.recipients.index');
        Route::get('/penerima/create', [\App\Http\Controllers\Admin\ReportRecipientController::class, 'create'])->name('admin.recipients.create');
        Route::post('/penerima', [\App\Http\Controllers\Admin\ReportRecipientController::class, 'store'])->name('admin.recipients.store');
        Route::get('/penerima/{recipient}', [\App\Http\Controllers\Admin\ReportRecipientController::class, 'show'])->name('admin.recipients.show');
        Route::get('/penerima/{recipient}/edit', [\App\Http\Controllers\Admin\ReportRecipientController::class, 'edit'])->name('admin.recipients.edit');
        Route::put('/penerima/{recipient}', [\App\Http\Controllers\Admin\ReportRecipientController::class, 'update'])->name('admin.recipients.update');
        Route::post('/penerima/{recipient}/toggle', [\App\Http\Controllers\Admin\ReportRecipientController::class, 'toggle'])->name('admin.recipients.toggle');

        // Disposisi dan Penerusan Laporan
        Route::get('/pengaduan/{report}/disposisi', [\App\Http\Controllers\Admin\ReportDispatchController::class, 'create'])->name('admin.dispatches.create');
        Route::post('/pengaduan/{report}/disposisi', [\App\Http\Controllers\Admin\ReportDispatchController::class, 'store'])->name('admin.dispatches.store');
        Route::post('/disposisi/{dispatch}/status', [\App\Http\Controllers\Admin\ReportDispatchController::class, 'updateRecipientStatus'])->name('admin.dispatches.update_status');
        Route::post('/disposisi/{dispatch}/retry', [\App\Http\Controllers\Admin\ReportDispatchController::class, 'retry'])->name('admin.dispatches.retry');

        // Pengaturan Integrasi Resmi
        Route::get('/pengaturan/whatsapp', [\App\Http\Controllers\Admin\AdminSettingsController::class, 'whatsapp'])->name('admin.settings.whatsapp');
    });
});

/*
|--------------------------------------------------------------------------
| Webhook Resmi WhatsApp Business Cloud API (Meta)
|--------------------------------------------------------------------------
*/
Route::prefix('api/v1/whatsapp')->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class])->group(function () {
    Route::get('/webhook', [\App\Http\Controllers\Api\WhatsAppWebhookController::class, 'verify'])->name('whatsapp.webhook.verify');
    Route::post('/webhook', [\App\Http\Controllers\Api\WhatsAppWebhookController::class, 'handle'])->name('whatsapp.webhook.handle');
});

