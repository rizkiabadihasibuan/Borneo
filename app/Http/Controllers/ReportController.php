<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReportRequest;
use App\Models\Report;
use App\Models\ReportStatusHistory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ReportController extends Controller
{
    /**
     * Tampilkan formulir pengaduan masyarakat.
     */
    public function create(): View
    {
        return view('pages.pengaduan');
    }

    /**
     * Simpan laporan baru dengan transaksi database, hashing kode akses, dan penyimpanan foto privat.
     */
    public function store(StoreReportRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        // 1. Generate nomor tiket berurutan (format: BS-YYYYMMDD-0001, BS-YYYYMMDD-0002, ...)
        $today = date('Ymd');
        $prefix = "BS-{$today}-";

        // Cari nomor urut tertinggi untuk hari ini
        $lastReportToday = Report::where('ticket_number', 'like', "{$prefix}%")
            ->orderBy('id', 'desc')
            ->first();

        $nextSeq = 1;
        if ($lastReportToday && preg_match('/BS-\d{8}-(\d+)/', $lastReportToday->ticket_number, $matches)) {
            $nextSeq = intval($matches[1]) + 1;
        } else {
            $countToday = Report::whereDate('created_at', today())->count();
            $nextSeq = $countToday + 1;
        }

        do {
            $ticketNumber = sprintf('BS-%s-%04d', $today, $nextSeq);
            $exists = Report::where('ticket_number', $ticketNumber)->exists();
            if ($exists) {
                $nextSeq++;
            }
        } while ($exists);

        // 2. Generate kode akses acak yang kuat (8 karakter alfanumerik)
        $plainAccessCode = strtoupper(Str::random(8));

        // 3. Simpan foto pendukung pada penyimpanan privat dengan nama acak jika dilampirkan
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $extension = $request->file('photo')->guessExtension() ?: 'jpg';
            $randomFileName = Str::uuid() . '.' . $extension;
            // Disimpan pada disk privat 'local' (storage/app/private/reports), tidak dapat diakses publik via URL
            $photoPath = $request->file('photo')->storeAs('reports', $randomFileName, 'local');
        }

        // 4. Eksekusi penyimpanan dalam transaksi database
        try {
            DB::transaction(function () use ($validated, $ticketNumber, $plainAccessCode, $photoPath) {
                $report = Report::create([
                    'ticket_number' => $ticketNumber,
                    'access_code_hash' => Hash::make($plainAccessCode),
                    'reporter_name' => $validated['reporter_name'],
                    'reporter_phone' => $validated['reporter_phone'],
                    'province' => $validated['province'],
                    'regency' => $validated['regency'],
                    'district' => $validated['district'] ?? null,
                    'village' => $validated['village'] ?? null,
                    'incident_location' => $validated['incident_location'],
                    'latitude' => $validated['latitude'] ?? null,
                    'longitude' => $validated['longitude'] ?? null,
                    'incident_time' => $validated['incident_time'],
                    'incident_type' => $validated['incident_type'],
                    'description' => $validated['description'],
                    'photo_path' => $photoPath,
                    'status' => 'Menunggu Verifikasi',
                ]);

                // Buat riwayat status awal secara otomatis
                ReportStatusHistory::create([
                    'report_id' => $report->id,
                    'previous_status' => null,
                    'new_status' => 'Menunggu Verifikasi',
                    'notes' => 'Laporan pengaduan masyarakat berhasil diterima sistem dan sedang menunggu verifikasi tim posko siaga.',
                    'admin_id' => null,
                ]);
            });
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Report submission failed: ' . $e->getMessage());

            return back()
                ->withInput()
                ->with('error_message', 'Terjadi kendala teknis saat menyimpan laporan Anda. Data yang Anda masukkan telah kami pertahankan. Silakan coba kembali.');
        }

        // 5. Tampilkan nomor tiket, kode akses, & ringkasan aman HANYA via session flash
        return redirect()->route('pengaduan')
            ->with('report_created_success', true)
            ->with('ticket_number', $ticketNumber)
            ->with('access_code', $plainAccessCode)
            ->with('report_summary', [
                'reporter_name' => $validated['reporter_name'],
                'province' => $validated['province'],
                'regency' => $validated['regency'],
                'incident_location' => $validated['incident_location'],
                'incident_type' => $validated['incident_type'],
                'incident_time' => $validated['incident_time'],
                'has_photo' => !empty($photoPath),
                'status' => 'Menunggu Verifikasi',
            ]);
    }
}
