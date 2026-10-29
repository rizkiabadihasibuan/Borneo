@extends('admin.layout')

@section('title', 'Daftar Pengaduan Karhutla - Posko BORNEO SIAGA')
@section('header_title', 'Daftar Pengaduan Karhutla')

@section('admin_content')
    <div style="max-width: 1200px; margin: 0 auto;">

        <!-- Filter & Search Panel -->
        <div style="background: #FFFFFF; border: 1px solid var(--color-border-subtle); border-radius: 12px; padding: 1.25rem 1.75rem; margin-bottom: 1.75rem; box-shadow: var(--shadow-editorial);">
            <form action="{{ route('admin.reports.index') }}" method="GET" style="display: flex; gap: 1rem; flex-wrap: wrap; align-items: flex-end;">
                
                <!-- Pencarian Nomor Tiket -->
                <div style="flex: 2; min-width: 240px;">
                    <label for="q" style="display: block; font-size: 0.8rem; font-weight: 600; color: var(--color-deep-forest); margin-bottom: 0.35rem;">
                        Cari Nomor Tiket
                    </label>
                    <div style="position: relative;">
                        <input type="text" 
                               id="q" 
                               name="q" 
                               value="{{ request('q') }}" 
                               placeholder="Contoh: BS-20261027-..." 
                               style="width: 100%; padding: 0.65rem 1rem 0.65rem 2.25rem; border: 1px solid var(--color-border-subtle); border-radius: 8px; font-size: 0.9rem; font-family: monospace; background: #FAF9F5;">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="position: absolute; left: 0.75rem; top: 50%; transform: translateY(-50%); color: #6D7870;">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                    </div>
                </div>

                <!-- Filter Status -->
                <div style="flex: 1.5; min-width: 200px;">
                    <label for="status" style="display: block; font-size: 0.8rem; font-weight: 600; color: var(--color-deep-forest); margin-bottom: 0.35rem;">
                        Filter Status
                    </label>
                    <select id="status" name="status" style="width: 100%; padding: 0.65rem 1rem; border: 1px solid var(--color-border-subtle); border-radius: 8px; font-size: 0.88rem; background: #FAF9F5;">
                        <option value="">Semua Status Laporan</option>
                        @foreach($allowedStatuses as $st)
                            <option value="{{ $st }}" {{ request('status') == $st ? 'selected' : '' }}>
                                {{ $st }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Action Buttons -->
                <div style="display: flex; gap: 0.5rem;">
                    <button type="submit" class="btn btn-forest" style="padding: 0.65rem 1.25rem; font-size: 0.88rem; display: inline-flex; align-items: center; gap: 0.4rem;">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                        <span>Filter</span>
                    </button>

                    @if(request()->hasAny(['q', 'status']))
                        <a href="{{ route('admin.reports.index') }}" class="btn btn-outline" style="padding: 0.65rem 1rem; font-size: 0.88rem; text-decoration: none;">
                            Reset
                        </a>
                    @endif
                </div>

            </form>
        </div>

        <!-- Tabel Daftar Pengaduan -->
        <div style="background: #FFFFFF; border: 1px solid var(--color-border-subtle); border-radius: 12px; overflow: hidden; box-shadow: var(--shadow-editorial);">
            <div style="padding: 1rem 1.75rem; border-bottom: 1px solid var(--color-border-subtle); display: flex; justify-content: space-between; align-items: center; background: #FAF9F5;">
                <span style="font-size: 0.85rem; color: #526B4D; font-weight: 600;">
                    Menampilkan total {{ $reports->total() }} laporan masuk
                </span>
                <span style="font-size: 0.78rem; color: #6D7870;">
                    Halaman {{ $reports->currentPage() }} dari {{ $reports->lastPage() ?: 1 }}
                </span>
            </div>

            @if($reports->count() > 0)
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.88rem;">
                        <thead>
                            <tr style="border-bottom: 1px solid var(--color-border-subtle); color: #526B4D; font-weight: 600; font-size: 0.76rem; text-transform: uppercase; letter-spacing: 0.05em; background: #FFFFFF;">
                                <th style="padding: 1rem 1.5rem;">Nomor Tiket</th>
                                <th style="padding: 1rem 1rem;">Jenis Kejadian</th>
                                <th style="padding: 1rem 1rem;">Lokasi Kejadian</th>
                                <th style="padding: 1rem 1rem;">Tanggal Laporan</th>
                                <th style="padding: 1rem 1rem;">Status</th>
                                <th style="padding: 1rem 1.5rem; text-align: right;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reports as $report)
                                <tr style="border-bottom: 1px solid var(--color-border-subtle); transition: background 0.15s ease;">
                                    <td style="padding: 1rem 1.5rem; font-family: monospace; font-weight: 600; color: var(--color-deep-forest);">
                                        <a href="{{ route('admin.reports.show', $report) }}" style="color: inherit; text-decoration: none;">
                                            {{ $report->ticket_number }}
                                        </a>
                                    </td>
                                    <td style="padding: 1rem 1rem; color: var(--color-charcoal);">
                                        {{ $report->incident_type }}
                                    </td>
                                    <td style="padding: 1rem 1rem; color: #4B5563;">
                                        <span style="font-weight: 500; display: block; color: var(--color-charcoal);">
                                            {{ $report->regency }}, {{ $report->province }}
                                        </span>
                                        <span style="font-size: 0.78rem; color: #6B7280; display: block; max-width: 280px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
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
                                        <a href="{{ route('admin.reports.show', $report) }}" class="btn btn-outline" style="padding: 0.45rem 0.85rem; font-size: 0.8rem; text-decoration: none; display: inline-flex; align-items: center; gap: 0.35rem;">
                                            <span>Detail</span>
                                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <polyline points="9 18 15 12 9 6"></polyline>
                                            </svg>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Bar -->
                <div style="padding: 1.25rem 1.75rem; border-top: 1px solid var(--color-border-subtle); display: flex; justify-content: center;">
                    {{ $reports->links() }}
                </div>
            @else
                <div style="padding: 3.5rem 1.5rem; text-align: center; color: #7F8B83;">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="margin-bottom: 0.75rem; color: #A0ABA3;">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                    <p style="margin: 0; font-size: 0.95rem; font-weight: 500;">Tidak ditemukan laporan yang sesuai dengan kriteria filter.</p>
                </div>
            @endif
        </div>

    </div>
@endsection
