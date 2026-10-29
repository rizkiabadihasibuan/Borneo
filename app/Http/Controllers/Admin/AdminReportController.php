<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\ReportStatusHistory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminReportController extends Controller
{
    /**
     * Tampilkan daftar pengaduan dengan filter status, pencarian nomor tiket, dan pagination.
     */
    public function index(Request $request): View
    {
        $query = Report::query()->latest();

        if ($request->filled('q')) {
            $search = trim($request->query('q'));
            $query->where('ticket_number', 'like', "%{$search}%");
        }

        if ($request->filled('status')) {
            $statusFilter = trim($request->query('status'));
            $query->where('status', $statusFilter);
        }

        $reports = $query->paginate(12)->withQueryString();

        $allowedStatuses = [
            'Menunggu Verifikasi',
            'Sedang Diverifikasi',
            'Dalam Penanganan',
            'Selesai Ditangani',
            'Ditolak',
        ];

        return view('admin.reports.index', compact('reports', 'allowedStatuses'));
    }

    /**
     * Tampilkan detail lengkap pengaduan untuk admin terotorisasi.
     */
    public function show(Report $report): View
    {
        $report->load([
            'statusHistories.admin',
            'dispatches.recipient',
            'dispatches.admin',
            'dispatches.histories.admin',
        ]);

        $allowedStatuses = [
            'Menunggu Verifikasi',
            'Sedang Diverifikasi',
            'Dalam Penanganan',
            'Selesai Ditangani',
            'Ditolak',
        ];

        return view('admin.reports.show', compact('report', 'allowedStatuses'));
    }

    /**
     * Update status pengaduan dengan transaksi database dan pencatatan riwayat resmi.
     */
    public function updateStatus(Request $request, Report $report): RedirectResponse
    {
        $allowedStatuses = [
            'Menunggu Verifikasi',
            'Sedang Diverifikasi',
            'Dalam Penanganan',
            'Selesai Ditangani',
            'Ditolak',
        ];

        $validated = $request->validate([
            'status' => ['required', 'string', Rule::in($allowedStatuses)],
            'notes' => ['required', 'string', 'min:5', 'max:1000'],
        ], [
            'status.required' => 'Pilihan status baru wajib ditentukan.',
            'status.in' => 'Status yang dipilih tidak valid dalam sistem posko.',
            'notes.required' => 'Catatan tindak lanjut wajib diisi oleh administrator.',
            'notes.min' => 'Catatan tindak lanjut minimal 5 karakter.',
            'notes.max' => 'Catatan tindak lanjut maksimal 1000 karakter.',
        ]);

        $previousStatus = $report->status;
        $newStatus = $validated['status'];
        $admin = Auth::user();

        DB::transaction(function () use ($report, $previousStatus, $newStatus, $validated, $admin) {
            $report->update([
                'status' => $newStatus,
            ]);

            ReportStatusHistory::create([
                'report_id' => $report->id,
                'previous_status' => $previousStatus,
                'new_status' => $newStatus,
                'notes' => trim($validated['notes']),
                'admin_id' => $admin ? $admin->id : null,
            ]);
        });

        return back()->with('success_message', "Status laporan {$report->ticket_number} berhasil diperbarui menjadi '{$newStatus}'.");
    }

    /**
     * Akses file foto bukti privat secara aman dengan otorisasi admin.
     * Tidak dapat diakses via URL penyimpanan publik langsung.
     */
    public function photo(Report $report): StreamedResponse
    {
        if (!$report->photo_path || !Storage::disk('local')->exists($report->photo_path)) {
            abort(404, 'Bukti foto tidak ditemukan atau belum dilampirkan pada laporan ini.');
        }

        return Storage::disk('local')->response($report->photo_path);
    }
}
