<?php

namespace App\Http\Controllers;

use App\Models\ReportDispatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DispatchPhotoController extends Controller
{
    /**
     * Tampilkan file foto bukti secara aman melalui temporary signed URL.
     * Hanya dapat diakses jika tanda tangan kriptografis valid dan belum kedaluwarsa.
     */
    public function view(Request $request, ReportDispatch $dispatch): StreamedResponse
    {
        // 1. Validasi URL bertanda tangan (Cryptographically Signed URL)
        if (!$request->hasValidSignature()) {
            abort(403, 'Tautan akses bukti foto telah kedaluwarsa atau tanda tangan tidak valid.');
        }

        // 2. Pastikan disposisi ini memang mengizinkan pembagian foto
        if (!$dispatch->include_photo) {
            abort(403, 'Disposisi ini tidak mengizinkan akses ke bukti foto laporan.');
        }

        $report = $dispatch->report;
        if (!$report || !$report->photo_path || !Storage::disk('local')->exists($report->photo_path)) {
            abort(404, 'File bukti foto tidak ditemukan pada penyimpanan posko.');
        }

        return Storage::disk('local')->response($report->photo_path);
    }
}
