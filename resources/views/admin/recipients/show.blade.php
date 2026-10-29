@extends('admin.layout')

@section('title', 'Detail Penerima: ' . $recipient->name . ' - Posko BORNEO SIAGA')
@section('header_title', 'Detail Direktori Penerima Laporan')

@section('admin_content')
    <div style="max-width: 1000px; margin: 0 auto;">
        
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; margin-bottom: 1.5rem;">
            <a href="{{ route('admin.recipients.index') }}" style="display: inline-flex; align-items: center; gap: 0.4rem; color: var(--color-forest-moss); font-weight: 600; font-size: 0.88rem; text-decoration: none;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                <span>Kembali ke Direktori</span>
            </a>

            <div style="display: inline-flex; align-items: center; gap: 0.5rem;">
                <a href="{{ route('admin.recipients.edit', $recipient) }}" class="btn btn-outline" style="padding: 0.5rem 1rem; font-size: 0.85rem; text-decoration: none;">
                    Edit Data
                </a>
                <form action="{{ route('admin.recipients.toggle', $recipient) }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-forest" style="padding: 0.5rem 1rem; font-size: 0.85rem; background: {{ $recipient->is_active ? '#991B1B' : '#065F46' }};">
                        {{ $recipient->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                    </button>
                </form>
            </div>
        </div>

        <!-- Card Profil Penerima -->
        <div style="background: #FFFFFF; border: 1px solid var(--color-border-subtle); border-radius: 12px; overflow: hidden; box-shadow: var(--shadow-editorial); margin-bottom: 2rem;">
            <div style="background-color: var(--color-deep-forest); color: var(--color-warm-paper); padding: 1.5rem 2rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
                <div>
                    <span style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.08em; color: var(--color-earth-sand); font-weight: 700; display: block;">
                        {{ $recipient->category_label }} • {{ $recipient->agency_type }}
                    </span>
                    <h2 style="font-family: var(--font-heading); font-size: 1.75rem; font-weight: 700; color: #FFFFFF; margin: 0.25rem 0 0; line-height: 1.2;">
                        {{ $recipient->name }}
                    </h2>
                </div>
                <div>
                    @if($recipient->is_active)
                        <span style="display: inline-flex; align-items: center; gap: 0.4rem; font-size: 0.82rem; font-weight: 600; color: #065F46; background: #D1FAE5; padding: 0.35rem 0.85rem; border-radius: 9999px;">
                            <span style="width: 8px; height: 8px; border-radius: 50%; background: #10B981;"></span>
                            Siaga Aktif
                        </span>
                    @else
                        <span style="display: inline-flex; align-items: center; gap: 0.4rem; font-size: 0.82rem; font-weight: 600; color: #991B1B; background: #FEE2E2; padding: 0.35rem 0.85rem; border-radius: 9999px;">
                            <span style="width: 8px; height: 8px; border-radius: 50%; background: #EF4444;"></span>
                            Nonaktif
                        </span>
                    @endif
                </div>
            </div>

            <div style="padding: 2rem;">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-bottom: 1.5rem;">
                    <div>
                        <span style="font-size: 0.76rem; text-transform: uppercase; letter-spacing: 0.05em; color: #7F8B83; font-weight: 600; display: block;">Wilayah Kerja</span>
                        <span style="font-size: 0.95rem; font-weight: 600; color: var(--color-charcoal); display: block; margin-top: 0.2rem;">
                            {{ $recipient->province }}
                        </span>
                        <span style="font-size: 0.82rem; color: #6D7870;">{{ $recipient->regency ?: 'Mencakup Seluruh Kabupaten' }}</span>
                    </div>

                    <div>
                        <span style="font-size: 0.76rem; text-transform: uppercase; letter-spacing: 0.05em; color: #7F8B83; font-weight: 600; display: block;">Nomor Telepon Kantor</span>
                        <a href="tel:{{ $recipient->phone }}" style="font-size: 0.95rem; font-weight: 600; color: var(--color-forest-moss); display: block; margin-top: 0.2rem; text-decoration: none;">
                            📞 {{ $recipient->phone }}
                        </a>
                    </div>

                    <div>
                        <span style="font-size: 0.76rem; text-transform: uppercase; letter-spacing: 0.05em; color: #7F8B83; font-weight: 600; display: block;">Nomor WhatsApp Resmi</span>
                        @if($recipient->whatsapp_number)
                            <span style="font-size: 0.95rem; font-weight: 600; color: #128C7E; display: block; margin-top: 0.2rem;">
                                💬 {{ $recipient->clean_whatsapp_number }}
                            </span>
                        @else
                            <span style="font-size: 0.85rem; color: #9CA3AF; display: block; margin-top: 0.2rem; font-style: italic;">
                                Belum terdaftar
                            </span>
                        @endif
                    </div>

                    <div>
                        <span style="font-size: 0.76rem; text-transform: uppercase; letter-spacing: 0.05em; color: #7F8B83; font-weight: 600; display: block;">Surel Resmi Disposisi</span>
                        @if($recipient->email)
                            <a href="mailto:{{ $recipient->email }}" style="font-size: 0.95rem; font-weight: 600; color: var(--color-forest-moss); display: block; margin-top: 0.2rem; text-decoration: none;">
                                ✉️ {{ $recipient->email }}
                            </a>
                        @else
                            <span style="font-size: 0.85rem; color: #9CA3AF; display: block; margin-top: 0.2rem; font-style: italic;">
                                Belum ada surel terdaftar
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Status Verifikasi Kanal -->
                @php
                    $verified = $recipient->verified_channels ?: [];
                @endphp
                <div style="background: #FAF9F5; border: 1px solid var(--color-border-subtle); border-radius: 8px; padding: 1rem 1.25rem; margin-bottom: 1.5rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
                    <div>
                        <span style="font-size: 0.76rem; text-transform: uppercase; letter-spacing: 0.05em; color: #7F8B83; font-weight: 600; display: block;">Status Kanal Terverifikasi</span>
                        <div style="display: flex; gap: 0.5rem; margin-top: 0.35rem;">
                            @if(in_array('email', $verified))
                                <span style="font-size: 0.78rem; font-weight: 600; color: #1E40AF; background: #DBEAFE; padding: 0.25rem 0.65rem; border-radius: 6px;">
                                    ✓ Email Terverifikasi
                                </span>
                            @else
                                <span style="font-size: 0.78rem; color: #9CA3AF; background: #F3F4F6; padding: 0.25rem 0.65rem; border-radius: 6px;">
                                    ✗ Email Belum Terverifikasi
                                </span>
                            @endif

                            @if(in_array('whatsapp', $verified))
                                <span style="font-size: 0.78rem; font-weight: 600; color: #065F46; background: #D1FAE5; padding: 0.25rem 0.65rem; border-radius: 6px;">
                                    ✓ WhatsApp Terverifikasi
                                </span>
                            @else
                                <span style="font-size: 0.78rem; color: #9CA3AF; background: #F3F4F6; padding: 0.25rem 0.65rem; border-radius: 6px;">
                                    ✗ WhatsApp Belum Terverifikasi
                                </span>
                            @endif
                        </div>
                    </div>
                    <div>
                        <span style="font-size: 0.76rem; text-transform: uppercase; letter-spacing: 0.05em; color: #7F8B83; font-weight: 600; display: block;">Kanal Prioritas</span>
                        <span style="font-size: 0.88rem; font-weight: 600; color: var(--color-charcoal); display: block; margin-top: 0.2rem;">
                            {{ match($recipient->preferred_channel) { 'email' => 'Email Resmi', 'whatsapp' => 'WhatsApp Resmi', default => 'Posko Manual' } }}
                        </span>
                    </div>
                </div>

                @if($recipient->contact_person || $recipient->address || $recipient->admin_notes)
                    <div style="border-top: 1px solid var(--color-border-subtle); padding-top: 1.25rem;">
                        @if($recipient->contact_person)
                            <p style="margin: 0 0 0.5rem; font-size: 0.88rem;">
                                <strong>Penanggung Jawab / Petugas Narahubung:</strong> {{ $recipient->contact_person }}
                            </p>
                        @endif
                        @if($recipient->address)
                            <p style="margin: 0 0 0.5rem; font-size: 0.88rem; color: #4B5563;">
                                <strong>Alamat Kantor / Posko:</strong> {{ $recipient->address }}
                            </p>
                        @endif
                        @if($recipient->admin_notes)
                            <div style="margin-top: 0.75rem; background: #F3F4F6; border-left: 3px solid #526B4D; padding: 0.75rem 1rem; border-radius: 4px;">
                                <span style="font-size: 0.74rem; font-weight: 700; text-transform: uppercase; color: #526B4D; display: block;">Catatan Administratif Posko:</span>
                                <span style="font-size: 0.85rem; color: #374151;">{{ $recipient->admin_notes }}</span>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <!-- Riwayat Disposisi yang Diterima -->
        <div style="background: #FFFFFF; border: 1px solid var(--color-border-subtle); border-radius: 12px; overflow: hidden; box-shadow: var(--shadow-editorial);">
            <div style="padding: 1.25rem 1.75rem; border-bottom: 1px solid var(--color-border-subtle); background: #FAF9F5; display: flex; justify-content: space-between; align-items: center;">
                <h3 style="font-family: var(--font-heading); font-size: 1.25rem; font-weight: 700; color: var(--color-deep-forest); margin: 0;">
                    Riwayat Disposisi yang Pernah Diteruskan
                </h3>
                <span style="font-size: 0.78rem; color: #6D7870;">
                    Total {{ $recipient->dispatches->count() }} Kali Disposisi
                </span>
            </div>

            @if($recipient->dispatches->count() > 0)
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.88rem;">
                        <thead>
                            <tr style="border-bottom: 1px solid var(--color-border-subtle); color: #526B4D; font-weight: 600; font-size: 0.76rem; text-transform: uppercase;">
                                <th style="padding: 0.9rem 1.5rem;">Tiket Laporan</th>
                                <th style="padding: 0.9rem 1rem;">Lokasi Insiden</th>
                                <th style="padding: 0.9rem 1rem;">Waktu Disposisi</th>
                                <th style="padding: 0.9rem 1rem;">Kanal</th>
                                <th style="padding: 0.9rem 1rem;">Status Pengiriman</th>
                                <th style="padding: 0.9rem 1rem;">Status Tindak Lanjut</th>
                                <th style="padding: 0.9rem 1.5rem; text-align: right;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recipient->dispatches as $disp)
                                <tr style="border-bottom: 1px solid var(--color-border-subtle);">
                                    <td style="padding: 1rem 1.5rem; font-family: monospace; font-weight: 600; color: var(--color-deep-forest);">
                                        <a href="{{ route('admin.reports.show', $disp->report) }}" style="color: inherit; text-decoration: none;">
                                            {{ $disp->report->ticket_number }}
                                        </a>
                                    </td>
                                    <td style="padding: 1rem 1rem;">
                                        {{ $disp->report->regency }}, {{ $disp->report->province }}
                                    </td>
                                    <td style="padding: 1rem 1rem; font-size: 0.82rem; color: #6D7870;">
                                        {{ $disp->created_at->format('d/m/Y H:i') }} WIB
                                    </td>
                                    <td style="padding: 1rem 1rem; text-transform: uppercase; font-size: 0.78rem; font-weight: 600;">
                                        @if($disp->channel === 'whatsapp')
                                            <span style="color: #128C7E;">💬 WhatsApp</span>
                                        @elseif($disp->channel === 'email')
                                            <span style="color: #1E40AF;">✉️ Email</span>
                                        @else
                                            <span>{{ $disp->channel }}</span>
                                        @endif
                                    </td>
                                    <td style="padding: 1rem 1rem;">
                                        @php
                                            $st = $disp->status;
                                            $isSuccess = in_array($st, ['Berhasil Dikirim ke Penyedia', 'Berhasil Dikirim', 'Diterima API', 'Terkirim ke Perangkat', 'Dibaca']);
                                            $bg = $isSuccess ? '#D1FAE5' : '#FEE2E2';
                                            $color = $isSuccess ? '#065F46' : '#991B1B';
                                        @endphp
                                        <span style="font-size: 0.76rem; font-weight: 600; padding: 0.2rem 0.55rem; border-radius: 4px; background: {{ $bg }}; color: {{ $color }};">
                                            {{ $st }}
                                        </span>
                                    </td>
                                    <td style="padding: 1rem 1rem; font-size: 0.82rem; color: #292D29;">
                                        {{ $disp->recipient_status }}
                                    </td>
                                    <td style="padding: 1rem 1.5rem; text-align: right;">
                                        <a href="{{ route('admin.reports.show', $disp->report) }}" class="btn btn-outline" style="padding: 0.35rem 0.7rem; font-size: 0.78rem; text-decoration: none;">
                                            Periksa Laporan
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div style="padding: 2.5rem 1.5rem; text-align: center; color: #7F8B83; font-size: 0.9rem;">
                    Belum ada pengaduan karhutla yang diteruskan ke instansi / regu ini.
                </div>
            @endif
        </div>

    </div>
@endsection
