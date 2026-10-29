<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    /**
     * Tampilkan halaman ringkasan operasional dashboard admin.
     * Semua statistik dihitung secara real-time dari query database riil.
     */
    public function index(): View
    {
        $totalReports = Report::count();

        $waitingReports = Report::whereIn('status', [
            'Menunggu Verifikasi', 
            'menunggu_verifikasi', 
            'diterima'
        ])->count();

        $inProgressReports = Report::whereIn('status', [
            'Sedang Diverifikasi', 
            'Dalam Penanganan', 
            'sedang_diverifikasi', 
            'dalam_penanganan', 
            'ditindaklanjuti'
        ])->count();

        $completedReports = Report::whereIn('status', [
            'Selesai Ditangani', 
            'selesai', 
            'selesai_ditangani'
        ])->count();

        $rejectedReports = Report::whereIn('status', [
            'Ditolak', 
            'ditolak'
        ])->count();

        $latestReports = Report::latest()
            ->take(6)
            ->get();

        return view('admin.dashboard', compact(
            'totalReports',
            'waitingReports',
            'inProgressReports',
            'completedReports',
            'rejectedReports',
            'latestReports'
        ));
    }
}
