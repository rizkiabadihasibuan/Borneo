@extends('admin.layout')

@section('title', 'Ringkasan Operasional - Dashboard Posko BORNEO SIAGA')
@section('header_title', 'Ringkasan Operasional Posko')

@section('admin_content')
    <div style="max-width: 1200px; margin: 0 auto;">

        <!-- Header Summary Banner -->
        <div style="background: linear-gradient(135deg, var(--color-deep-forest) 0%, #1d2e25 100%); color: var(--color-warm-paper); border-radius: 12px; padding: 2rem 2.25rem; margin-bottom: 2rem; border: 1px solid rgba(214, 197, 163, 0.2); box-shadow: var(--shadow-editorial); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1.5rem;">
            <div>
                <span style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.1em; color: var(--color-earth-sand); font-weight: 700; display: block; margin-bottom: 0.35rem;">
                    Pusat Komando & Verifikasi
                </span>
                <h2 style="font-family: var(--font-heading); font-size: 2rem; font-weight: 700; color: #FFFFFF; margin: 0; line-height: 1.2;">
                    Pemantauan Laporan Karhutla Borneo
                </h2>
                <p style="margin: 0.5rem 0 0; color: #cbd9ce; font-size: 0.95rem; max-width: 650px;">
                    Data ringkasan berikut dihitung secara langsung dari basis data riil pelaporan masyarakat di seluruh penjuru Kalimantan.
                </p>
            </div>
            <div>
                <a href="{{ route('admin.reports.index') }}" class="btn btn-sand" style="padding: 0.75rem 1.4rem; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 0.5rem; text-decoration: none;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="8" y1="6" x2="21" y2="6"></line>
                        <line x1="8" y1="12" x2="21" y2="12"></line>
                        <line x1="8" y1="18" x2="21" y2="18"></line>
                        <line x1="3" y1="6" x2="3.01" y2="6"></line>
                        <line x1="3" y1="12" x2="3.01" y2="12"></line>
                        <line x1="3" y1="18" x2="3.01" y2="18"></line>
                    </svg>
                    <span>Buka Semua Laporan</span>
                </a>
            </div>
        </div>

        <!-- =====================================================================
             STATISTIK OPERASIONAL (QUERY DATABASE RIIL)
             ===================================================================== -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.25rem; margin-bottom: 2.5rem;">
            
            <!-- Stat 1: Total Laporan -->
            <div style="background: #FFFFFF; border: 1px solid var(--color-border-subtle); border-radius: 10px; padding: 1.5rem; box-shadow: var(--shadow-editorial);">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.75rem;">
                    <span style="font-size: 0.8rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: #526B4D;">
                        Total Laporan
                    </span>
                    <div style="width: 32px; height: 32px; border-radius: 6px; background: rgba(82, 107, 77, 0.1); color: var(--color-forest-moss); display: flex; align-items: center; justify-content: center;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                        </svg>
                    </div>
                </div>
                <div style="font-family: var(--font-heading); font-size: 2.25rem; font-weight: 700; color: var(--color-deep-forest); line-height: 1;">
                    {{ number_format($totalReports) }}
                </div>
                <span style="font-size: 0.76rem; color: #7F8B83; margin-top: 0.4rem; display: block;">
                    Total berkas pengaduan masuk
                </span>
            </div>

            <!-- Stat 2: Menunggu Verifikasi -->
            <div style="background: #FFFFFF; border: 1px solid #FCD34D; border-radius: 10px; padding: 1.5rem; box-shadow: var(--shadow-editorial);">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.75rem;">
                    <span style="font-size: 0.8rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: #B45309;">
                        Menunggu Verifikasi
                    </span>
                    <div style="width: 32px; height: 32px; border-radius: 6px; background: #FEF3C7; color: #B45309; display: flex; align-items: center; justify-content: center;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg>
                    </div>
                </div>
                <div style="font-family: var(--font-heading); font-size: 2.25rem; font-weight: 700; color: #B45309; line-height: 1;">
                    {{ number_format($waitingReports) }}
                </div>
                <span style="font-size: 0.76rem; color: #92400E; margin-top: 0.4rem; display: block;">
                    Perlu konfirmasi awal posko
                </span>
            </div>

            <!-- Stat 3: Sedang Ditangani -->
            <div style="background: #FFFFFF; border: 1px solid #FDBA74; border-radius: 10px; padding: 1.5rem; box-shadow: var(--shadow-editorial);">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.75rem;">
                    <span style="font-size: 0.8rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: #C2410C;">
                        Sedang Ditangani
                    </span>
                    <div style="width: 32px; height: 32px; border-radius: 6px; background: #FFEDD5; color: #C2410C; display: flex; align-items: center; justify-content: center;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon>
                        </svg>
                    </div>
                </div>
                <div style="font-family: var(--font-heading); font-size: 2.25rem; font-weight: 700; color: #C2410C; line-height: 1;">
                    {{ number_format($inProgressReports) }}
                </div>
                <span style="font-size: 0.76rem; color: #9A3412; margin-top: 0.4rem; display: block;">
                    Diverifikasi & regu lapangan aktif
                </span>
            </div>

            <!-- Stat 4: Selesai Ditangani -->
            <div style="background: #FFFFFF; border: 1px solid #6EE7B7; border-radius: 10px; padding: 1.5rem; box-shadow: var(--shadow-editorial);">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.75rem;">
                    <span style="font-size: 0.8rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: #047857;">
                        Selesai Ditangani
                    </span>
                    <div style="width: 32px; height: 32px; border-radius: 6px; background: #D1FAE5; color: #047857; display: flex; align-items: center; justify-content: center;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                            <polyline points="22 4 12 14.01 9 11.01"></polyline>
                        </svg>
                    </div>
                </div>
                <div style="font-family: var(--font-heading); font-size: 2.25rem; font-weight: 700; color: #047857; line-height: 1;">
                    {{ number_format($completedReports) }}
                </div>
                <span style="font-size: 0.76rem; color: #065F46; margin-top: 0.4rem; display: block;">
                    Padam total & tervalidasi
                </span>
            </div>

        </div>

        <!-- =====================================================================
             TABEL LAPORAN TERBARU
             ===================================================================== -->
        <div style="background: #FFFFFF; border: 1px solid var(--color-border-subtle); border-radius: 12px; overflow: hidden; box-shadow: var(--shadow-editorial);">
            <div style="padding: 1.25rem 1.75rem; border-bottom: 1px solid var(--color-border-subtle); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
                <div>
                    <h3 style="font-family: var(--font-heading); font-size: 1.35rem; font-weight: 700; color: var(--color-deep-forest); margin: 0;">
                        Laporan Pengaduan Terbaru
                    </h3>
                    <span style="font-size: 0.78rem; color: #6D7870;">
                        Daftar berkas laporan masuk yang terakhir kali dicatat sistem
                    </span>
                </div>
                <a href="{{ route('admin.reports.index') }}" style="font-size: 0.85rem; color: var(--color-forest-moss); font-weight: 600; text-decoration: none; display: flex; align-items: center; gap: 0.35rem;">
                    <span>Lihat Semua ({{ $totalReports }})</span>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="9 18 15 12 9 6"></polyline>
                    </svg>
                </a>
            </div>

            @if($latestReports->count() > 0)
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.88rem;">
                        <thead>
                            <tr style="background: #FAF9F5; border-bottom: 1px solid var(--color-border-subtle); color: #526B4D; font-weight: 600; font-size: 0.76rem; text-transform: uppercase; letter-spacing: 0.05em;">
                                <th style="padding: 0.9rem 1.5rem;">Nomor Tiket</th>
                                <th style="padding: 0.9rem 1rem;">Jenis Kejadian</th>
                                <th style="padding: 0.9rem 1rem;">Lokasi</th>
                                <th style="padding: 0.9rem 1rem;">Waktu Masuk</th>
                                <th style="padding: 0.9rem 1rem;">Status</th>
                                <th style="padding: 0.9rem 1.5rem; text-align: right;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($latestReports as $report)
                                <tr style="border-bottom: 1px solid var(--color-border-subtle); transition: background 0.15s ease;">
                                    <td style="padding: 1rem 1.5rem; font-family: monospace; font-weight: 600; color: var(--color-deep-forest);">
                                        {{ $report->ticket_number }}
                                    </td>
                                    <td style="padding: 1rem 1rem; color: var(--color-charcoal);">
                                        {{ $report->incident_type }}
                                    </td>
                                    <td style="padding: 1rem 1rem; color: #4B5563;">
                                        <span style="font-weight: 500; display: block; color: var(--color-charcoal);">{{ $report->regency }}, {{ $report->province }}</span>
                                        <span style="font-size: 0.78rem; color: #6B7280; display: block; max-width: 260px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                            {{ $report->incident_location }}
                                        </span>
                                    </td>
                                    <td style="padding: 1rem 1rem; color: #6D7870; font-size: 0.82rem;">
                                        {{ $report->created_at->format('d/m/Y H:i') }} WIB
                                    </td>
                                    <td style="padding: 1rem 1rem;">
                                        @php
                                            $badgeClass = match($report->status_label) {
                                                'Menunggu Verifikasi' => 'menunggu',
                                                'Sedang Diverifikasi' => 'diverifikasi',
                                                'Dalam Penanganan' => 'penanganan',
                                                'Selesai Ditangani' => 'selesai',
                                                'Ditolak' => 'ditolak',
                                                default => 'menunggu'
                                            };
                                        @endphp
                                        <span class="status-badge {{ $badgeClass }}">
                                            <span style="width: 6px; height: 6px; border-radius: 50%; background: currentColor; display: inline-block;"></span>
                                            {{ $report->status_label }}
                                        </span>
                                    </td>
                                    <td style="padding: 1rem 1.5rem; text-align: right;">
                                        <a href="{{ route('admin.reports.show', $report) }}" class="btn btn-outline" style="padding: 0.45rem 0.85rem; font-size: 0.8rem; text-decoration: none;">
                                            Periksa Detail
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div style="padding: 3rem 1.5rem; text-align: center; color: #7F8B83;">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="margin-bottom: 0.75rem; color: #A0ABA3;">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                    <p style="margin: 0; font-size: 0.95rem; font-weight: 500;">Belum ada laporan pengaduan yang tersimpan di sistem.</p>
                </div>
            @endif
        </div>

    </div>
@endsection
