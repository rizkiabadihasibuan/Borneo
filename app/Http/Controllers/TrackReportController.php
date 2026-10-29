<?php

namespace App\Http\Controllers;

use App\Http\Requests\TrackReportRequest;
use App\Models\Report;
use Illuminate\View\View;

class TrackReportController extends Controller
{
    /**
     * Tampilkan formulir pelacakan status pengaduan.
     */
    public function show(): View
    {
        return view('pages.lacak');
    }

    /**
     * Verifikasi kode akses terhadap hash dan tampilkan status riwayat riil dari database.
     */
    public function track(TrackReportRequest $request): View
    {
        $validated = $request->validated();
        $ticketNumber = trim($validated['ticket_number']);
        $accessCode = trim($validated['access_code']);

        // Cari laporan berdasarkan nomor tiket
        $report = Report::where('ticket_number', $ticketNumber)
            ->with(['statusHistories', 'dispatches.recipient'])
            ->first();

        // Pesan kesalahan seragam untuk mencegah enumerasi tiket
        $invalidErrorMessage = 'Nomor tiket atau kode akses rahasia tidak valid. Silakan periksa kembali data yang Anda masukkan.';

        if (!$report || !$report->verifyAccessCode($accessCode)) {
            return view('pages.lacak', [
                'trackedReport' => null,
                'searchError' => $invalidErrorMessage,
                'searchedTicket' => $ticketNumber,
            ]);
        }

        // Laporan sah terverifikasi, tampilkan progres riil
        return view('pages.lacak', [
            'trackedReport' => $report,
            'searchError' => null,
            'searchedTicket' => $ticketNumber,
        ]);
    }
}
