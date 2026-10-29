@extends('admin.layout')

@section('title', 'Direktori Penerima Laporan - Posko BORNEO SIAGA')
@section('header_title', 'Direktori Instansi & Masyarakat Peduli Api (MPA)')

@section('admin_content')
    <div style="max-width: 1200px; margin: 0 auto;">

        <!-- Header Actions & Intro -->
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; margin-bottom: 1.75rem;">
            <div>
                <span style="font-size: 0.8rem; color: #6D7870; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em;">Mitra Operasional Penanganan Lapangan</span>
                <p style="margin: 0.25rem 0 0; color: #4B5563; font-size: 0.92rem;">
                    Daftar instansi pemerintah (BPBD, Manggala Agni, Dishut) dan Masyarakat Peduli Api (MPA) untuk penerusan disposisi via Email dan WhatsApp Resmi.
                </p>
            </div>
            <div style="display: flex; gap: 0.75rem; align-items: center;">
                <a href="{{ route('admin.settings.whatsapp') }}" class="btn btn-outline" style="padding: 0.65rem 1.1rem; font-size: 0.88rem; text-decoration: none; display: inline-flex; align-items: center; gap: 0.4rem;">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="3"></circle>
                        <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                    </svg>
                    <span>Status Integrasi API</span>
                </a>
                <a href="{{ route('admin.recipients.create') }}" class="btn btn-forest" style="padding: 0.65rem 1.25rem; font-size: 0.88rem; text-decoration: none; display: inline-flex; align-items: center; gap: 0.45rem;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    <span>Tambah Penerima</span>
                </a>
            </div>
        </div>

        <!-- Filter & Search Bar -->
        <div style="background: #FFFFFF; border: 1px solid var(--color-border-subtle); border-radius: 12px; padding: 1.25rem 1.75rem; margin-bottom: 1.75rem; box-shadow: var(--shadow-editorial);">
            <form action="{{ route('admin.recipients.index') }}" method="GET" style="display: flex; gap: 1rem; flex-wrap: wrap; align-items: flex-end;">
                
                <!-- Search input -->
                <div style="flex: 2; min-width: 220px;">
                    <label for="q" style="display: block; font-size: 0.8rem; font-weight: 600; color: var(--color-deep-forest); margin-bottom: 0.35rem;">
                        Cari Nama / Kontak / WhatsApp
                    </label>
                    <input type="text" 
                           id="q" 
                           name="q" 
                           value="{{ request('q') }}" 
                           placeholder="Nama instansi, nomor telepon/WA, email..." 
                           style="width: 100%; padding: 0.65rem 1rem; border: 1px solid var(--color-border-subtle); border-radius: 8px; font-size: 0.9rem; background: #FAF9F5;">
                </div>

                <!-- Filter Kategori -->
                <div style="flex: 1.2; min-width: 170px;">
                    <label for="category" style="display: block; font-size: 0.8rem; font-weight: 600; color: var(--color-deep-forest); margin-bottom: 0.35rem;">
                        Kategori
                    </label>
                    <select id="category" name="category" style="width: 100%; padding: 0.65rem 1rem; border: 1px solid var(--color-border-subtle); border-radius: 8px; font-size: 0.88rem; background: #FAF9F5;">
                        <option value="">Semua Kategori</option>
                        <option value="pemerintah" {{ request('category') == 'pemerintah' ? 'selected' : '' }}>Instansi Pemerintah</option>
                        <option value="komunitas_mpa" {{ request('category') == 'komunitas_mpa' ? 'selected' : '' }}>Komunitas MPA</option>
                    </select>
                </div>

                <!-- Filter Provinsi -->
                <div style="flex: 1.2; min-width: 170px;">
                    <label for="province" style="display: block; font-size: 0.8rem; font-weight: 600; color: var(--color-deep-forest); margin-bottom: 0.35rem;">
                        Wilayah Provinsi
                    </label>
                    <select id="province" name="province" style="width: 100%; padding: 0.65rem 1rem; border: 1px solid var(--color-border-subtle); border-radius: 8px; font-size: 0.88rem; background: #FAF9F5;">
                        <option value="">Semua Wilayah</option>
                        @foreach($provinces as $prov)
                            <option value="{{ $prov }}" {{ request('province') == $prov ? 'selected' : '' }}>{{ $prov }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Status -->
                <div style="flex: 1; min-width: 130px;">
                    <label for="is_active" style="display: block; font-size: 0.8rem; font-weight: 600; color: var(--color-deep-forest); margin-bottom: 0.35rem;">
                        Status Aktif
                    </label>
                    <select id="is_active" name="is_active" style="width: 100%; padding: 0.65rem 1rem; border: 1px solid var(--color-border-subtle); border-radius: 8px; font-size: 0.88rem; background: #FAF9F5;">
                        <option value="">Semua</option>
                        <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>

                <div style="display: flex; gap: 0.5rem;">
                    <button type="submit" class="btn btn-forest" style="padding: 0.65rem 1.2rem; font-size: 0.88rem;">
                        Filter
                    </button>
                    @if(request()->hasAny(['q', 'category', 'province', 'is_active', 'channel']))
                        <a href="{{ route('admin.recipients.index') }}" class="btn btn-outline" style="padding: 0.65rem 1rem; font-size: 0.88rem; text-decoration: none;">
                            Reset
                        </a>
                    @endif
                </div>

            </form>
        </div>

        <!-- Tabel Direktori -->
        <div style="background: #FFFFFF; border: 1px solid var(--color-border-subtle); border-radius: 12px; overflow: hidden; box-shadow: var(--shadow-editorial);">
            <div style="padding: 1rem 1.75rem; border-bottom: 1px solid var(--color-border-subtle); display: flex; justify-content: space-between; align-items: center; background: #FAF9F5;">
                <span style="font-size: 0.85rem; color: #526B4D; font-weight: 600;">
                    Total {{ $recipients->total() }} entitas penerima terdaftar
                </span>
                <span style="font-size: 0.78rem; color: #6D7870;">
                    Halaman {{ $recipients->currentPage() }} dari {{ $recipients->lastPage() ?: 1 }}
                </span>
            </div>

            @if($recipients->count() > 0)
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.88rem;">
                        <thead>
                            <tr style="border-bottom: 1px solid var(--color-border-subtle); color: #526B4D; font-weight: 600; font-size: 0.76rem; text-transform: uppercase; letter-spacing: 0.05em; background: #FFFFFF;">
                                <th style="padding: 1rem 1.5rem;">Nama Entitas</th>
                                <th style="padding: 1rem 1rem;">Kategori & Tipe</th>
                                <th style="padding: 1rem 1rem;">Wilayah Kerja</th>
                                <th style="padding: 1rem 1rem;">Kontak Resmi</th>
                                <th style="padding: 1rem 1rem;">Kanal Terverifikasi</th>
                                <th style="padding: 1rem 1rem;">Status</th>
                                <th style="padding: 1rem 1.5rem; text-align: right;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recipients as $item)
                                @php
                                    $verified = $item->verified_channels ?: [];
                                @endphp
                                <tr style="border-bottom: 1px solid var(--color-border-subtle); transition: background 0.15s ease;">
                                    <td style="padding: 1rem 1.5rem;">
                                        <a href="{{ route('admin.recipients.show', $item) }}" style="font-weight: 600; color: var(--color-deep-forest); text-decoration: none; display: block;">
                                            {{ $item->name }}
                                        </a>
                                        @if($item->contact_person)
                                            <span style="font-size: 0.78rem; color: #6D7870;">PJ: {{ $item->contact_person }}</span>
                                        @endif
                                    </td>
                                    <td style="padding: 1rem 1rem;">
                                        <span style="display: inline-block; font-size: 0.74rem; font-weight: 600; padding: 0.2rem 0.6rem; border-radius: 4px; background: {{ $item->category === 'pemerintah' ? '#E0E7FF' : '#FEF3C7' }}; color: {{ $item->category === 'pemerintah' ? '#3730A3' : '#92400E' }};">
                                            {{ $item->category_label }}
                                        </span>
                                        <span style="font-size: 0.78rem; color: #4B5563; display: block; margin-top: 0.2rem;">
                                            {{ $item->agency_type }}
                                        </span>
                                    </td>
                                    <td style="padding: 1rem 1rem; color: #4B5563;">
                                        <span style="font-weight: 500; display: block; color: var(--color-charcoal);">{{ $item->province }}</span>
                                        <span style="font-size: 0.78rem; color: #6D7870;">{{ $item->regency ?: 'Seluruh Wilayah Provinsi' }}</span>
                                    </td>
                                    <td style="padding: 1rem 1rem; font-size: 0.82rem;">
                                        @if($item->whatsapp_number)
                                            <span style="display: block; font-weight: 600; color: #128C7E;">💬 {{ $item->clean_whatsapp_number }}</span>
                                        @endif
                                        <span style="display: block; color: var(--color-charcoal);">📞 {{ $item->phone }}</span>
                                        @if($item->email)
                                            <span style="color: #526B4D; font-size: 0.78rem; display: block;">✉️ {{ $item->email }}</span>
                                        @endif
                                    </td>
                                    <td style="padding: 1rem 1rem;">
                                        <div style="display: flex; gap: 0.35rem; flex-wrap: wrap;">
                                            @if(in_array('email', $verified))
                                                <span style="font-size: 0.72rem; font-weight: 600; color: #1E40AF; background: #DBEAFE; padding: 0.15rem 0.45rem; border-radius: 4px;" title="Email Resmi Terverifikasi">
                                                    ✉️ Email
                                                </span>
                                            @endif
                                            @if(in_array('whatsapp', $verified))
                                                <span style="font-size: 0.72rem; font-weight: 600; color: #065F46; background: #D1FAE5; padding: 0.15rem 0.45rem; border-radius: 4px;" title="WhatsApp Resmi Terverifikasi">
                                                    💬 WhatsApp
                                                </span>
                                            @endif
                                            @if(empty($verified))
                                                <span style="font-size: 0.72rem; color: #9CA3AF; font-style: italic;">
                                                    Belum terverifikasi
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td style="padding: 1rem 1rem;">
                                        @if($item->is_active)
                                            <span style="display: inline-flex; align-items: center; gap: 0.35rem; font-size: 0.76rem; font-weight: 600; color: #065F46; background: #D1FAE5; padding: 0.25rem 0.65rem; border-radius: 9999px; border: 1px solid #A7F3D0;">
                                                <span style="width: 6px; height: 6px; border-radius: 50%; background: #10B981;"></span>
                                                Aktif
                                            </span>
                                        @else
                                            <span style="display: inline-flex; align-items: center; gap: 0.35rem; font-size: 0.76rem; font-weight: 600; color: #991B1B; background: #FEE2E2; padding: 0.25rem 0.65rem; border-radius: 9999px; border: 1px solid #FECACA;">
                                                <span style="width: 6px; height: 6px; border-radius: 50%; background: #EF4444;"></span>
                                                Nonaktif
                                            </span>
                                        @endif
                                    </td>
                                    <td style="padding: 1rem 1.5rem; text-align: right;">
                                        <div style="display: inline-flex; align-items: center; gap: 0.4rem;">
                                            <a href="{{ route('admin.recipients.show', $item) }}" class="btn btn-outline" style="padding: 0.35rem 0.65rem; font-size: 0.78rem; text-decoration: none;">
                                                Detail
                                            </a>
                                            <a href="{{ route('admin.recipients.edit', $item) }}" class="btn btn-outline" style="padding: 0.35rem 0.65rem; font-size: 0.78rem; text-decoration: none;">
                                                Edit
                                            </a>
                                            <form action="{{ route('admin.recipients.toggle', $item) }}" method="POST" style="display: inline;">
                                                @csrf
                                                <button type="submit" style="background: none; border: 1px solid var(--color-border-subtle); padding: 0.35rem 0.6rem; border-radius: 6px; font-size: 0.76rem; cursor: pointer; color: {{ $item->is_active ? '#B83B26' : '#526B4D' }};">
                                                    {{ $item->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div style="padding: 1.25rem 1.75rem; border-top: 1px solid var(--color-border-subtle); display: flex; justify-content: center;">
                    {{ $recipients->links() }}
                </div>
            @else
                <div style="padding: 3.5rem 1.5rem; text-align: center; color: #7F8B83;">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="margin-bottom: 0.75rem; color: #A0ABA3;">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                    </svg>
                    <p style="margin: 0; font-size: 0.95rem; font-weight: 500;">Belum ada penerima laporan terdaftar yang cocok dengan pencarian.</p>
                    <a href="{{ route('admin.recipients.create') }}" class="btn btn-forest" style="margin-top: 1rem; padding: 0.6rem 1.2rem; font-size: 0.85rem; text-decoration: none; display: inline-block;">
                        + Daftarkan Instansi / MPA Baru
                    </a>
                </div>
            @endif
        </div>

    </div>
@endsection
