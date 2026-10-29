@extends('admin.layout')

@section('title', 'Detail Pengaduan ' . $report->ticket_number . ' - Posko BORNEO SIAGA')
@section('header_title', 'Detail Berkas Laporan Pengaduan')

@section('admin_content')
    <div style="max-width: 1200px; margin: 0 auto;">

        <!-- Top Navigation & Ticket Bar -->
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; margin-bottom: 1.75rem;">
            <a href="{{ route('admin.reports.index') }}" style="display: inline-flex; align-items: center; gap: 0.4rem; color: var(--color-forest-moss); font-weight: 600; font-size: 0.88rem; text-decoration: none;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                <span>Kembali ke Daftar Pengaduan</span>
            </a>

            <div style="display: flex; align-items: center; gap: 0.75rem; flex-wrap: wrap;">
                @php
                    $locationDetails = [];
                    if ($report->village) $locationDetails[] = "Desa " . $report->village;
                    if ($report->district) $locationDetails[] = "Kec. " . $report->district;
                    $locationText = count($locationDetails) > 0 ? implode(', ', $locationDetails) : null;

                    $waGroupLines = [
                        "🚨🚨 *[PENGUMUMAN SIAGA KARHUTLA]* 🚨🚨",
                        "*POSKO TANGGAP CEPAT • BORNEO SIAGA*",
                        "═══════════════════════════",
                        "📢 *PERHATIAN: REGU MPA & RELAWAN PEMADAM*",
                        "Telah masuk laporan kebakaran lahan / hutan yang membutuhkan atensi dan tindakan segera!",
                        "═══════════════════════════",
                        "",
                        "🎫 *NO. TIKET:* `#" . $report->ticket_number . "`",
                        "⚠️ *STATUS:* *" . strtoupper($report->status_label) . "*",
                        "🔥 *JENIS KEJADIAN:* " . $report->incident_type,
                        "⏱️ *WAKTU LAPOR:* " . ($report->incident_time ? $report->incident_time->format('d/m/Y H:i') . ' WIB' : 'Baru saja'),
                        "",
                        "📍 *LOKASI KEJADIAN:*",
                        "🏛️ Provinsi: *" . $report->province . "*",
                        "🏢 Kab/Kota: *" . $report->regency . "*",
                    ];

                    if ($locationText) {
                        $waGroupLines[] = "🏘️ Wilayah: *" . $locationText . "*";
                    }

                    $waGroupLines[] = "🧭 Alamat/Patokan: " . $report->incident_location;

                    if ($report->latitude && $report->longitude) {
                        $lat = number_format($report->latitude, 5);
                        $lon = number_format($report->longitude, 5);
                        $waGroupLines[] = "";
                        $waGroupLines[] = "🗺️ *TITIK KOORDINAT (GPS):*";
                        $waGroupLines[] = "📡 `{$lat}, {$lon}`";
                        $waGroupLines[] = "🔗 *Peta Navigasi (Google Maps):*";
                        $waGroupLines[] = "https://www.google.com/maps?q={$report->latitude},{$report->longitude}";
                    }

                    $waGroupLines[] = "";
                    $waGroupLines[] = "📝 *DESKRIPSI KONDISI LAPANGAN:*";
                    $waGroupLines[] = "“" . \Illuminate\Support\Str::limit($report->description, 350) . "”";

                    if ($report->reporter_name || $report->reporter_phone) {
                        $waGroupLines[] = "";
                        $waGroupLines[] = "👤 *KONTAK PELAPOR:*";
                        $waGroupLines[] = "• Nama: " . $report->reporter_name;
                        if ($report->reporter_phone) {
                            $waGroupLines[] = "• No. Telp/WA: " . $report->reporter_phone;
                        }
                    }

                    $waGroupLines[] = "";
                    $waGroupLines[] = "═══════════════════════════";
                    $waGroupLines[] = "🚒 *HIMBAUAN & TINDAK LANJUT:*";
                    $waGroupLines[] = "1️⃣ Rekan MPA terdekat dimohon segera lakukan *ground check* ke titik lokasi.";
                    $waGroupLines[] = "2️⃣ Siapkan perlengkapan pemadaman (pompa apung, selang, sekat bakar).";
                    $waGroupLines[] = "3️⃣ Laporkan perkembangan situasi terkini (*sitrep*) di grup koordinasi ini.";
                    $waGroupLines[] = "═══════════════════════════";
                    $waGroupLines[] = "🌐 *Portal Siaga:* " . url('/');
                    $waGroupLines[] = "_#BorneoSiaga #PantauKarhutla #MasyarakatPeduliApi_";

                    $waGroupText = implode("\n", $waGroupLines);
                    $waShareUrl = "https://api.whatsapp.com/send?text=" . urlencode($waGroupText);
                @endphp

                <!-- Tombol Cepat Bagikan ke WhatsApp (Grup / Personal) -->
                <a href="{{ $waShareUrl }}" target="_blank" class="btn" style="background-color: #25D366; color: #FFFFFF; padding: 0.45rem 1rem; font-size: 0.85rem; text-decoration: none; display: inline-flex; align-items: center; gap: 0.45rem; font-weight: 700; border-radius: 6px; box-shadow: 0 2px 4px rgba(37,211,102,0.25);">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.007c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86s.275.072.376-.043c.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564.289.13.332.202c.043.072.043.419-.101.824z"/>
                    </svg>
                    <span>Bagikan ke Grup WA</span>
                </a>

                <a href="{{ route('admin.dispatches.create', $report) }}" class="btn btn-sand" style="padding: 0.45rem 1rem; font-size: 0.85rem; text-decoration: none; display: inline-flex; align-items: center; gap: 0.45rem;">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="22" y1="2" x2="11" y2="13"></line>
                        <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                    </svg>
                    <span>Teruskan Disposisi</span>
                </a>

                <span style="font-size: 0.82rem; color: #6D7870;">Status Laporan:</span>
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
                <span class="status-badge {{ $badgeClass }}" style="font-size: 0.82rem; padding: 0.35rem 0.85rem;">
                    <span style="width: 7px; height: 7px; border-radius: 50%; background: currentColor; display: inline-block;"></span>
                    {{ $report->status_label }}
                </span>
            </div>
        </div>

        <!-- 2 Columns Grid: Left (Report Information & Evidence) & Right (Status Action & History) -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(360px, 1fr)); gap: 2rem;">

            <!-- =================================================================
                 KOLOM KIRI: DETAIL LENGKAP PENGADUAN & BUKTI FOTO PRIVAT
                 ================================================================= -->
            <div style="display: flex; flex-direction: column; gap: 1.75rem;">

                <!-- Card 1: Informasi Berkas & Pelapor -->
                <div style="background: #FFFFFF; border: 1px solid var(--color-border-subtle); border-radius: 12px; overflow: hidden; box-shadow: var(--shadow-editorial);">
                    <div style="background-color: var(--color-deep-forest); color: var(--color-warm-paper); padding: 1.25rem 1.75rem;">
                        <span style="font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.1em; color: var(--color-earth-sand); font-weight: 700; display: block; margin-bottom: 0.2rem;">
                            Identitas Berkas Laporan
                        </span>
                        <h2 style="font-family: var(--font-heading); font-size: 1.6rem; font-weight: 700; color: #FFFFFF; margin: 0; line-height: 1.1;">
                            {{ $report->ticket_number }}
                        </h2>
                    </div>

                    <div style="padding: 1.75rem;">
                        <!-- Warning Privasi Administrator -->
                        <div style="background: rgba(82, 107, 77, 0.08); border-left: 3px solid var(--color-forest-moss); padding: 0.75rem 1rem; border-radius: 6px; margin-bottom: 1.5rem; font-size: 0.82rem; color: #43543f; line-height: 1.5;">
                            <strong>Akses Terbatas Petugas Posko:</strong> Informasi kontak dan nama pelapor di bawah ini bersifat privat dan hanya digunakan untuk verifikasi faktual di lapangan.
                        </div>

                        <!-- Data Fields Grid -->
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-bottom: 1.25rem;">
                            <div>
                                <span style="font-size: 0.76rem; text-transform: uppercase; letter-spacing: 0.05em; color: #7F8B83; font-weight: 600; display: block;">
                                    Nama Pelapor
                                </span>
                                <span style="font-size: 0.95rem; font-weight: 600; color: var(--color-charcoal); display: block; margin-top: 0.2rem;">
                                    {{ $report->reporter_name }}
                                </span>
                            </div>

                            <div>
                                <span style="font-size: 0.76rem; text-transform: uppercase; letter-spacing: 0.05em; color: #7F8B83; font-weight: 600; display: block;">
                                    Nomor Telepon / Kontak
                                </span>
                                <a href="tel:{{ $report->reporter_phone }}" style="font-size: 0.95rem; font-weight: 600; color: var(--color-forest-moss); text-decoration: none; display: inline-flex; align-items: center; gap: 0.35rem; margin-top: 0.2rem;">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                                    </svg>
                                    <span>{{ $report->reporter_phone }}</span>
                                </a>
                            </div>
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-bottom: 1.25rem; border-top: 1px solid var(--color-border-subtle); padding-top: 1.25rem;">
                            <div>
                                <span style="font-size: 0.76rem; text-transform: uppercase; letter-spacing: 0.05em; color: #7F8B83; font-weight: 600; display: block;">
                                    Waktu Kejadian Dilaporkan
                                </span>
                                <span style="font-size: 0.92rem; color: var(--color-charcoal); display: block; margin-top: 0.2rem;">
                                    {{ $report->incident_time ? $report->incident_time->format('d F Y, H:i') . ' WIB' : '-' }}
                                </span>
                            </div>

                            <div>
                                <span style="font-size: 0.76rem; text-transform: uppercase; letter-spacing: 0.05em; color: #7F8B83; font-weight: 600; display: block;">
                                    Jenis Insiden
                                </span>
                                <span style="font-size: 0.92rem; font-weight: 600; color: var(--color-charcoal); display: block; margin-top: 0.2rem;">
                                    {{ $report->incident_type }}
                                </span>
                            </div>
                        </div>

                        <div style="border-top: 1px solid var(--color-border-subtle); padding-top: 1.25rem; margin-bottom: 1.25rem;">
                            <span style="font-size: 0.76rem; text-transform: uppercase; letter-spacing: 0.05em; color: #7F8B83; font-weight: 600; display: block; margin-bottom: 0.4rem;">
                                Lokasi Administratif & Patokan Lapangan
                            </span>
                            <div style="background: #FAF9F5; border: 1px solid var(--color-border-subtle); border-radius: 8px; padding: 1rem;">
                                <div style="font-weight: 600; color: var(--color-deep-forest); font-size: 0.95rem; margin-bottom: 0.35rem;">
                                    {{ $report->village ? 'Desa ' . $report->village . ', ' : '' }}
                                    {{ $report->district ? 'Kec. ' . $report->district . ', ' : '' }}
                                    {{ $report->regency }}, {{ $report->province }}
                                </div>
                                <div style="font-size: 0.88rem; color: #4B5563; line-height: 1.5;">
                                    {{ $report->incident_location }}
                                </div>

                                @if($report->latitude && $report->longitude)
                                    <div style="margin-top: 0.75rem; padding-top: 0.75rem; border-top: 1px dashed rgba(41,45,41,0.15); display: flex; align-items: center; justify-content: space-between; flex-wrap: gap; gap: 0.5rem;">
                                        <span style="font-family: monospace; font-size: 0.82rem; color: #526B4D;">
                                            GPS: {{ number_format($report->latitude, 5) }}, {{ number_format($report->longitude, 5) }}
                                        </span>
                                        <a href="https://www.google.com/maps?q={{ $report->latitude }},{{ $report->longitude }}" target="_blank" style="font-size: 0.78rem; color: var(--color-forest-moss); font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 0.3rem;">
                                            <span>Buka di Peta Luar</span>
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                                                <polyline points="15 3 21 3 21 9"></polyline>
                                                <line x1="10" y1="14" x2="21" y2="3"></line>
                                            </svg>
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Deskripsi Laporan -->
                        <div style="border-top: 1px solid var(--color-border-subtle); padding-top: 1.25rem;">
                            <span style="font-size: 0.76rem; text-transform: uppercase; letter-spacing: 0.05em; color: #7F8B83; font-weight: 600; display: block; margin-bottom: 0.4rem;">
                                Deskripsi Kronologi / Kondisi Lapangan
                            </span>
                            <div style="background: #FAF9F5; border: 1px solid var(--color-border-subtle); border-radius: 8px; padding: 1rem; font-size: 0.92rem; line-height: 1.65; color: var(--color-charcoal); white-space: pre-wrap;">
                                {{ $report->description }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card: Pengumuman Siaga & Teruskan ke Grup WhatsApp Komunitas MPA -->
                <div style="background: #FFFFFF; border: 2px solid #25D366; border-radius: 14px; overflow: hidden; box-shadow: 0 4px 20px rgba(37,211,102,0.12); margin-bottom: 2rem;">
                    <!-- Card Header -->
                    <div style="background: linear-gradient(135deg, #075E54 0%, #128C7E 100%); color: #FFFFFF; padding: 1.25rem 1.75rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.75rem;">
                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                            <div style="width: 38px; height: 38px; background: #25D366; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #FFFFFF; box-shadow: 0 2px 6px rgba(0,0,0,0.2);">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.007c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86s.275.072.376-.043c.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564.289.13.332.202c.043.072.043.419-.101.824z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 style="font-family: var(--font-heading); font-size: 1.25rem; font-weight: 700; color: #FFFFFF; margin: 0; display: flex; align-items: center; gap: 0.5rem;">
                                    <span>Siarkan Pengumuman ke Grup WhatsApp MPA</span>
                                </h3>
                                <span style="font-size: 0.78rem; color: #D1FAE5;">
                                    Format darurat resmi siap diteruskan ke grup-grup relawan, satgas karhutla & posko desa
                                </span>
                            </div>
                        </div>
                        <span style="background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.3); color: #FFFFFF; font-size: 0.72rem; font-weight: 700; padding: 0.35rem 0.75rem; border-radius: 999px; text-transform: uppercase; letter-spacing: 0.05em; display: inline-flex; align-items: center; gap: 0.35rem;">
                            <span style="display: inline-block; width: 7px; height: 7px; border-radius: 50%; background: #25D366;"></span>
                            Format Siaga Eye-Catching
                        </span>
                    </div>

                    <!-- Card Body -->
                    <div style="padding: 1.5rem;">
                        <p style="font-size: 0.88rem; color: #4B5563; margin: 0 0 1.25rem; line-height: 1.6;">
                            Format pengumuman di bawah dirancang khusus agar <strong>langsung menarik perhatian (eye-catching)</strong> para relawan saat masuk ke dalam grup obrolan WhatsApp. Anda dapat langsung membuka WhatsApp atau menyalin teksnya.
                        </p>

                        <!-- WhatsApp Mockup Chat Frame -->
                        <div style="background-color: #ECE5DD; background-image: radial-gradient(#D6CBC1 1px, transparent 1px); background-size: 16px 16px; border-radius: 12px; padding: 1.25rem; border: 1px solid #D6C5A3; margin-bottom: 1.5rem;">
                            
                            <!-- Chat Bubble Header -->
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem;">
                                <div style="display: flex; align-items: center; gap: 0.4rem;">
                                    <span style="font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #075E54; background: #DCF8C6; padding: 0.2rem 0.6rem; border-radius: 4px; border: 1px solid #C1E7B2;">
                                        Preview Pesan Pengumuman
                                    </span>
                                </div>
                                <button type="button" id="btnCopyWaText" style="background: #FFFFFF; border: 1px solid var(--color-border-subtle); padding: 0.4rem 0.9rem; border-radius: 8px; font-size: 0.8rem; font-weight: 600; cursor: pointer; color: var(--color-deep-forest); display: inline-flex; align-items: center; gap: 0.4rem; box-shadow: 0 1px 3px rgba(0,0,0,0.06); transition: all 0.2s ease;">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                                        <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                                    </svg>
                                    <span id="copyStatusText">Salin Teks Format</span>
                                </button>
                            </div>

                            <!-- White WhatsApp Message Bubble -->
                            <div style="background: #FFFFFF; border-radius: 10px; border-left: 4px solid #25D366; padding: 1.25rem; box-shadow: 0 2px 6px rgba(0,0,0,0.08); position: relative;">
                                <pre id="waMessageText" style="margin: 0; font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, 'Liberation Mono', 'Courier New', monospace; font-size: 0.84rem; color: #1F2937; line-height: 1.6; white-space: pre-wrap; word-break: break-word;">{{ $waGroupText }}</pre>
                                
                                <div style="display: flex; justify-content: flex-end; align-items: center; gap: 0.3rem; margin-top: 0.75rem; font-size: 0.7rem; color: #9CA3AF;">
                                    <span>Sekarang</span>
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="#34B7F1">
                                        <path d="M18 7l-1.41-1.41-6.34 6.34 1.41 1.41L18 7zm4.24-1.41L11.66 16.17 7.41 11.93l-1.41 1.41 5.66 5.66 12-12-1.42-1.41zM.41 13.34l5.66 5.66 1.41-1.41-5.66-5.66-1.41 1.41z"/>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div style="display: flex; gap: 1rem; align-items: center; flex-wrap: wrap;">
                            <a href="{{ $waShareUrl }}" target="_blank" class="btn" style="background-color: #25D366; color: #FFFFFF; padding: 0.8rem 1.6rem; font-size: 0.95rem; font-weight: 700; text-decoration: none; border-radius: 10px; display: inline-flex; align-items: center; gap: 0.6rem; box-shadow: 0 3px 10px rgba(37,211,102,0.4); transition: transform 0.15s ease, box-shadow 0.15s ease;">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.007c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86s.275.072.376-.043c.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564.289.13.332.202c.043.072.043.419-.101.824z"/>
                                </svg>
                                <span>Buka WhatsApp & Pilih Grup MPA</span>
                            </a>
                            
                            <span style="font-size: 0.8rem; color: #6B7280;">
                                💡 Klik tombol untuk langsung membuka WhatsApp dan memilih 1 atau lebih grup relawan sekaligus.
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Foto Bukti Privat -->
                <div style="background: #FFFFFF; border: 1px solid var(--color-border-subtle); border-radius: 12px; overflow: hidden; box-shadow: var(--shadow-editorial);">
                    <div style="padding: 1.25rem 1.75rem; border-bottom: 1px solid var(--color-border-subtle); display: flex; justify-content: space-between; align-items: center;">
                        <h3 style="font-family: var(--font-heading); font-size: 1.25rem; font-weight: 700; color: var(--color-deep-forest); margin: 0;">
                            Dokumentasi Bukti Foto Lapangan
                        </h3>
                        <span style="font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.08em; color: #526B4D; font-weight: 600;">
                            Penyimpanan Privat
                        </span>
                    </div>

                    <div style="padding: 1.75rem;">
                        @if($report->photo_path)
                            <div style="border-radius: 8px; overflow: hidden; border: 1px solid var(--color-border-subtle); background: #263B30; text-align: center;">
                                <img src="{{ route('admin.reports.photo', $report) }}" 
                                     alt="Bukti Foto {{ $report->ticket_number }}" 
                                     style="max-width: 100%; max-height: 480px; object-fit: contain; display: block; margin: 0 auto;">
                            </div>
                            <div style="margin-top: 1rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.5rem;">
                                <span style="font-size: 0.78rem; color: #6D7870;">
                                    Foto diakses melalui rute terproteksi otorisasi administrator.
                                </span>
                                <a href="{{ route('admin.reports.photo', $report) }}" target="_blank" class="btn btn-outline" style="padding: 0.4rem 0.85rem; font-size: 0.8rem; text-decoration: none;">
                                    Buka Foto Ukuran Penuh
                                </a>
                            </div>
                        @else
                            <div style="padding: 2.5rem 1rem; text-align: center; color: #7F8B83; background: #FAF9F5; border-radius: 8px; border: 1px dashed var(--color-border-subtle);">
                                <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="margin-bottom: 0.5rem; color: #A0ABA3;">
                                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                    <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                    <polyline points="21 15 16 10 5 21"></polyline>
                                </svg>
                                <p style="margin: 0; font-size: 0.9rem;">Pelapor tidak melampirkan foto bukti pendukung.</p>
                            </div>
                        @endif
                    </div>
                </div>

            </div>

            <!-- =================================================================
                 KOLOM KANAN: FORMULIR UPDATE STATUS & RIWAYAT KRONOLOGIS
                 ================================================================= -->
            <div style="display: flex; flex-direction: column; gap: 1.75rem;">

                <!-- Card 3: Formulir Perubahan Status Resmi -->
                <div style="background: #FFFFFF; border: 1px solid var(--color-border-subtle); border-radius: 12px; overflow: hidden; box-shadow: var(--shadow-editorial);">
                    <div style="background: #FAF9F5; padding: 1.25rem 1.75rem; border-bottom: 1px solid var(--color-border-subtle);">
                        <h3 style="font-family: var(--font-heading); font-size: 1.35rem; font-weight: 700; color: var(--color-deep-forest); margin: 0;">
                            Pembaruan Status Penanganan
                        </h3>
                        <span style="font-size: 0.78rem; color: #6D7870;">
                            Setiap perubahan wajib disertai catatan resmi tindak lanjut posko
                        </span>
                    </div>

                    <div style="padding: 1.75rem;">
                        <form action="{{ route('admin.reports.update_status', $report) }}" method="POST">
                            @csrf

                            <!-- Pilihan Status Baru -->
                            <div style="margin-bottom: 1.25rem;">
                                <label for="status" style="display: block; font-weight: 600; font-size: 0.88rem; color: var(--color-deep-forest); margin-bottom: 0.4rem;">
                                    Pilih Status Baru <span style="color: #B83B26;">*</span>
                                </label>
                                <select id="status" name="status" required style="width: 100%; padding: 0.75rem 1rem; border: 1px solid {{ $errors->has('status') ? '#B83B26' : 'var(--color-border-subtle)' }}; border-radius: 8px; font-size: 0.92rem; background: #FAF9F5; font-weight: 500;">
                                    @foreach($allowedStatuses as $st)
                                        <option value="{{ $st }}" {{ old('status', $report->status) == $st ? 'selected' : '' }}>
                                            {{ $st }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('status')
                                    <span style="font-size: 0.78rem; color: #B83B26; margin-top: 0.35rem; display: block;">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Catatan Tindak Lanjut Resmi -->
                            <div style="margin-bottom: 1.5rem;">
                                <label for="notes" style="display: block; font-weight: 600; font-size: 0.88rem; color: var(--color-deep-forest); margin-bottom: 0.4rem;">
                                    Catatan Tindak Lanjut / Justifikasi <span style="color: #B83B26;">*</span>
                                </label>
                                <textarea id="notes" 
                                          name="notes" 
                                          rows="4" 
                                          required 
                                          placeholder="Tuliskan catatan tindak lanjut, regu lapangan yang dikerahkan, verifikasi citra satelit, atau alasan penolakan berkas..." 
                                          style="width: 100%; padding: 0.75rem 1rem; border: 1px solid {{ $errors->has('notes') ? '#B83B26' : 'var(--color-border-subtle)' }}; border-radius: 8px; font-size: 0.9rem; line-height: 1.5; background: #FAF9F5;">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <span style="font-size: 0.78rem; color: #B83B26; margin-top: 0.35rem; display: block;">{{ $message }}</span>
                                @enderror
                                <span style="font-size: 0.76rem; color: #6D7870; margin-top: 0.35rem; display: block;">
                                    Catatan ini akan tersimpan permanen di riwayat dan dapat dibaca pelapor pada halaman pelacakan publik.
                                </span>
                            </div>

                            <!-- Tombol Submit -->
                            <button type="submit" class="btn btn-forest" style="width: 100%; padding: 0.85rem 1rem; font-size: 0.95rem; display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                                <span>Simpan Perubahan Status</span>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Card 4: Riwayat Status Kronologis -->
                <div style="background: #FFFFFF; border: 1px solid var(--color-border-subtle); border-radius: 12px; overflow: hidden; box-shadow: var(--shadow-editorial);">
                    <div style="padding: 1.25rem 1.75rem; border-bottom: 1px solid var(--color-border-subtle); display: flex; justify-content: space-between; align-items: center;">
                        <h3 style="font-family: var(--font-heading); font-size: 1.25rem; font-weight: 700; color: var(--color-deep-forest); margin: 0;">
                            Riwayat Penanganan
                        </h3>
                        <span style="font-size: 0.78rem; color: #6D7870;">
                            {{ $report->statusHistories->count() }} Catatan
                        </span>
                    </div>

                    <div style="padding: 1.75rem;">
                        @if($report->statusHistories->count() > 0)
                            <div style="position: relative; padding-left: 1.5rem;">
                                <!-- Vertical Timeline Line -->
                                <div style="position: absolute; left: 6px; top: 8px; bottom: 8px; width: 2px; background: var(--color-border-subtle);"></div>

                                @foreach($report->statusHistories as $history)
                                    <div style="position: relative; margin-bottom: 1.75rem;">
                                        <!-- Bullet Point -->
                                        <div style="position: absolute; left: -1.5rem; top: 4px; width: 14px; height: 14px; border-radius: 50%; background: var(--color-forest-moss); border: 3px solid #FFFFFF; box-shadow: 0 0 0 1px var(--color-forest-moss);"></div>

                                        <div>
                                            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.5rem; margin-bottom: 0.35rem;">
                                                <span style="font-weight: 700; font-size: 0.92rem; color: var(--color-deep-forest);">
                                                    {{ $history->status_label }}
                                                </span>
                                                <span style="font-size: 0.76rem; color: #7F8B83;">
                                                    {{ $history->created_at->format('d M Y, H:i') }} WIB
                                                </span>
                                            </div>

                                            @if($history->previous_status)
                                                <span style="font-size: 0.74rem; color: #6D7870; display: block; margin-bottom: 0.35rem;">
                                                    Perubahan dari: <em>{{ $history->previous_status }}</em>
                                                </span>
                                            @endif

                                            <div style="background: #FAF9F5; border: 1px solid var(--color-border-subtle); border-radius: 6px; padding: 0.75rem 0.9rem; font-size: 0.85rem; color: var(--color-charcoal); line-height: 1.5;">
                                                {{ $history->notes ?: 'Tanpa catatan tertulis.' }}
                                            </div>

                                            <div style="margin-top: 0.4rem; font-size: 0.72rem; color: #7F8B83; display: flex; align-items: center; gap: 0.35rem;">
                                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                                    <circle cx="12" cy="7" r="4"></circle>
                                                </svg>
                                                <span>Dicatat oleh: <strong>{{ $history->admin ? $history->admin->name : 'Sistem Otomatis Posko' }}</strong></span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div style="padding: 1.5rem 0; text-align: center; color: #7F8B83; font-size: 0.88rem;">
                                Riwayat tindak lanjut belum tercatat di sistem.
                            </div>
                        @endif
                    </div>
                </div>

            </div>

        </div>

        <!-- =====================================================================
             SECTION: RIWAYAT DISPOSISI & PENERUSAN LINTAS INSTANSI / MPA
             ===================================================================== -->
        <div style="margin-top: 2.5rem; background: #FFFFFF; border: 1px solid var(--color-border-subtle); border-radius: 12px; overflow: hidden; box-shadow: var(--shadow-editorial);">
            <div style="padding: 1.5rem 2rem; background: var(--color-deep-forest); color: var(--color-warm-paper); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
                <div>
                    <span style="font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.1em; color: var(--color-earth-sand); font-weight: 700; display: block; margin-bottom: 0.2rem;">
                        Koordinasi Tindak Lanjut Lapangan
                    </span>
                    <h3 style="font-family: var(--font-heading); font-size: 1.5rem; font-weight: 700; color: #FFFFFF; margin: 0; line-height: 1.2;">
                        Riwayat Disposisi & Penerusan Laporan
                    </h3>
                </div>
                <a href="{{ route('admin.dispatches.create', $report) }}" class="btn btn-sand" style="padding: 0.5rem 1.1rem; font-size: 0.85rem; text-decoration: none; display: inline-flex; align-items: center; gap: 0.4rem;">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    <span>Teruskan ke Instansi / MPA</span>
                </a>
            </div>

            <div style="padding: 2rem;">
                @if($report->dispatches->count() > 0)
                    <div style="display: flex; flex-direction: column; gap: 2rem;">
                        @foreach($report->dispatches as $dispatch)
                            @php
                                $isWhatsApp = $dispatch->channel === 'whatsapp';
                                $isEmail = $dispatch->channel === 'email';
                                $isFailed = in_array($dispatch->status, ['Gagal Dikirim', 'Gagal', 'Ditolak Penyedia', 'Bounced']);
                                $isDelivered = in_array($dispatch->status, ['Berhasil Dikirim ke Penyedia', 'Berhasil Dikirim', 'Terkirim ke Perangkat', 'Dibaca']);
                            @endphp
                            <div style="border: 1px solid var(--color-border-subtle); border-radius: 10px; overflow: hidden; background: #FAF9F5; margin-bottom: 1.5rem;">
                                <!-- Header Disposisi -->
                                <div style="background: #FFFFFF; padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--color-border-subtle); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
                                    <div>
                                        <div style="display: flex; align-items: center; gap: 0.6rem; flex-wrap: wrap;">
                                            <span style="font-weight: 700; font-size: 1.05rem; color: var(--color-deep-forest);">
                                                {{ $dispatch->recipient->name }}
                                            </span>
                                            <span style="font-size: 0.72rem; font-weight: 600; padding: 0.15rem 0.55rem; border-radius: 4px; background: {{ $dispatch->recipient->category === 'pemerintah' ? '#E0E7FF' : '#FEF3C7' }}; color: {{ $dispatch->recipient->category === 'pemerintah' ? '#3730A3' : '#92400E' }};">
                                                {{ $dispatch->recipient->category_label }}
                                            </span>
                                            
                                            <!-- Kanal Badge -->
                                            @if($isWhatsApp)
                                                <span style="display: inline-flex; align-items: center; gap: 0.3rem; font-size: 0.76rem; font-weight: 700; color: #065F46; background: #D1FAE5; padding: 0.15rem 0.55rem; border-radius: 4px; border: 1px solid #A7F3D0;">
                                                    💬 WHATSAPP RESMI (Meta Cloud API)
                                                </span>
                                            @elseif($isEmail)
                                                <span style="display: inline-flex; align-items: center; gap: 0.3rem; font-size: 0.76rem; font-weight: 700; color: #1E40AF; background: #DBEAFE; padding: 0.15rem 0.55rem; border-radius: 4px; border: 1px solid #BFDBFE;">
                                                    ✉️ SUREL (EMAIL SMTP)
                                                </span>
                                            @else
                                                <span style="font-size: 0.76rem; font-weight: 600; text-transform: uppercase;">
                                                    {{ $dispatch->channel }}
                                                </span>
                                            @endif
                                        </div>

                                        <div style="font-size: 0.78rem; color: #7F8B83; margin-top: 0.35rem; display: flex; gap: 1rem; flex-wrap: wrap;">
                                            <span>Disposisi oleh <strong>{{ $dispatch->admin ? $dispatch->admin->name : 'Posko Admin' }}</strong></span>
                                            <span>Waktu Buat: {{ $dispatch->created_at->format('d M Y, H:i') }} WIB</span>
                                            @if($dispatch->reference_id)
                                                <span>Ref: <code style="background: #E5E7EB; padding: 0.1rem 0.35rem; border-radius: 4px;">{{ $dispatch->reference_id }}</code></span>
                                            @endif
                                            @if($dispatch->provider_message_id)
                                                <span>ID Meta: <code style="background: #E0E7FF; color: #3730A3; padding: 0.1rem 0.35rem; border-radius: 4px;">{{ Str::limit($dispatch->provider_message_id, 24) }}</code></span>
                                            @endif
                                            @if($dispatch->retry_count > 0)
                                                <span style="color: #B45309; font-weight: 600;">Percobaan ke-{{ $dispatch->retry_count + 1 }}</span>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Status Pengiriman Badge & Tombol Retry -->
                                    <div style="display: flex; align-items: center; gap: 0.75rem; flex-wrap: wrap;">
                                        @if($dispatch->status === 'Dibaca')
                                            <span style="font-size: 0.78rem; font-weight: 700; color: #4338CA; background: #EEF2FF; padding: 0.3rem 0.8rem; border-radius: 9999px; border: 1px solid #C7D2FE;">
                                                ✓✓ Dibaca Penerima
                                            </span>
                                        @elseif($dispatch->status === 'Terkirim ke Perangkat')
                                            <span style="font-size: 0.78rem; font-weight: 700; color: #065F46; background: #D1FAE5; padding: 0.3rem 0.8rem; border-radius: 9999px; border: 1px solid #A7F3D0;">
                                                ✓ Terkirim ke Perangkat
                                            </span>
                                        @elseif($dispatch->status === 'Diterima API')
                                            <span style="font-size: 0.78rem; font-weight: 700; color: #1E40AF; background: #DBEAFE; padding: 0.3rem 0.8rem; border-radius: 9999px; border: 1px solid #BFDBFE;">
                                                Diterima API WhatsApp
                                            </span>
                                        @elseif(in_array($dispatch->status, ['Berhasil Dikirim ke Penyedia', 'Berhasil Dikirim']))
                                            <span style="font-size: 0.78rem; font-weight: 700; color: #065F46; background: #D1FAE5; padding: 0.3rem 0.8rem; border-radius: 9999px; border: 1px solid #A7F3D0;">
                                                ✓ Berhasil Dikirim ke Penyedia
                                            </span>
                                        @elseif($isFailed)
                                            <span style="font-size: 0.78rem; font-weight: 700; color: #991B1B; background: #FEE2E2; padding: 0.3rem 0.8rem; border-radius: 9999px; border: 1px solid #FECACA;">
                                                ✕ {{ $dispatch->status }}
                                            </span>
                                        @else
                                            <span style="font-size: 0.78rem; font-weight: 700; color: #92400E; background: #FEF3C7; padding: 0.3rem 0.8rem; border-radius: 9999px; border: 1px solid #FCD34D;">
                                                {{ $dispatch->status }}
                                            </span>
                                        @endif

                                        <!-- Tombol Retry Hanya untuk Kanal Ini -->
                                        @if($isFailed)
                                            <form action="{{ route('admin.dispatches.retry', $dispatch) }}" method="POST" style="display: inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-outline" style="padding: 0.35rem 0.75rem; font-size: 0.78rem; border-color: #B83B26; color: #B83B26; background: #FFF5F5;" onclick="return confirm('Kirim ulang disposisi ini melalui kanal {{ strtoupper($dispatch->channel) }}?');">
                                                    🔄 Kirim Ulang Kanal Ini
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>

                                <div style="padding: 1.5rem;">
                                    <!-- Jika Ada Pesan Error Pengiriman -->
                                    @if($dispatch->delivery_error)
                                        <div style="background: #FEF2F2; border: 1px solid #FECACA; border-left: 4px solid #EF4444; border-radius: 6px; padding: 0.75rem 1rem; margin-bottom: 1.25rem; font-size: 0.84rem; color: #991B1B;">
                                            <strong>Catatan Kegagalan:</strong> {{ $dispatch->delivery_error }}
                                        </div>
                                    @endif

                                    <!-- Catatan Arahan Posko -->
                                    <div style="margin-bottom: 1.25rem;">
                                        <span style="font-size: 0.76rem; text-transform: uppercase; font-weight: 700; color: #526B4D; display: block; margin-bottom: 0.3rem;">
                                            Catatan Pengantar dari Posko:
                                        </span>
                                        <div style="background: #FFFFFF; border: 1px solid var(--color-border-subtle); border-radius: 6px; padding: 0.85rem 1rem; font-size: 0.9rem; color: var(--color-charcoal); line-height: 1.5;">
                                            {{ $dispatch->dispatch_notes }}
                                        </div>
                                    </div>

                                    <!-- Parameter Privasi & Waktu Pengiriman -->
                                    <div style="display: flex; gap: 1.5rem; flex-wrap: wrap; font-size: 0.82rem; margin-bottom: 1.5rem; color: #4B5563;">
                                        <div>
                                            <span>Foto Lapangan: </span>
                                            @if($dispatch->include_photo)
                                                <strong style="color: #065F46;">Dilampirkan (Signed URL 7 Hari)</strong>
                                            @else
                                                <span style="color: #6D7870;">Tidak Dilampirkan</span>
                                            @endif
                                        </div>
                                        <div>
                                            <span>Kontak Pelapor: </span>
                                            @if($dispatch->include_reporter_contact)
                                                <strong style="color: #9A3412;">Diteruskan</strong>
                                            @else
                                                <strong style="color: #065F46;">Dirahasiakan Posko</strong>
                                            @endif
                                        </div>
                                        @if($dispatch->delivered_at)
                                            <div>
                                                <span>Diterima Perangkat: </span>
                                                <strong style="color: #065F46;">{{ $dispatch->delivered_at->format('d/m/Y H:i') }} WIB</strong>
                                            </div>
                                        @endif
                                        @if($dispatch->read_at)
                                            <div>
                                                <span>Dibaca: </span>
                                                <strong style="color: #4338CA;">{{ $dispatch->read_at->format('d/m/Y H:i') }} WIB</strong>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Status Tindak Lanjut Penerima & Form Pembaruan -->
                                    <div style="background: #FFFFFF; border: 1px solid var(--color-border-subtle); border-radius: 8px; padding: 1.25rem;">
                                        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.75rem; margin-bottom: 1rem;">
                                            <div>
                                                <span style="font-size: 0.74rem; text-transform: uppercase; font-weight: 700; color: #6D7870; display: block;">
                                                    Status Tindak Lanjut Penanganan oleh {{ $dispatch->recipient->name }}:
                                                </span>
                                                <span style="font-weight: 700; font-size: 1rem; color: var(--color-deep-forest);">
                                                    {{ $dispatch->recipient_status }}
                                                </span>
                                            </div>
                                            @if($dispatch->recipient_feedback)
                                                <div style="font-size: 0.82rem; color: #4B5563; max-width: 450px;">
                                                    <strong>Catatan Terakhir:</strong> {{ $dispatch->recipient_feedback }}
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Form Update Status Tindak Lanjut -->
                                        <form action="{{ route('admin.dispatches.update_status', $dispatch) }}" method="POST" style="border-top: 1px dashed rgba(41,45,41,0.15); padding-top: 1rem;">
                                            @csrf
                                            <span style="font-size: 0.8rem; font-weight: 600; color: var(--color-deep-forest); display: block; margin-bottom: 0.5rem;">
                                                Perbarui Respon Lapangan & Tindak Lanjut Instansi:
                                            </span>

                                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 0.75rem; margin-bottom: 0.75rem;">
                                                <select name="recipient_status" required style="padding: 0.55rem 0.85rem; border: 1px solid var(--color-border-subtle); border-radius: 6px; font-size: 0.85rem; background: #FAF9F5; font-weight: 500;">
                                                    <option value="Menunggu Konfirmasi Penerima" {{ $dispatch->recipient_status === 'Menunggu Konfirmasi Penerima' ? 'selected' : '' }}>Menunggu Konfirmasi Penerima</option>
                                                    <option value="Dikonfirmasi Penerima" {{ $dispatch->recipient_status === 'Dikonfirmasi Penerima' ? 'selected' : '' }}>Dikonfirmasi Penerima</option>
                                                    <option value="Sedang Ditindaklanjuti" {{ $dispatch->recipient_status === 'Sedang Ditindaklanjuti' ? 'selected' : '' }}>Sedang Ditindaklanjuti</option>
                                                    <option value="Selesai Ditindaklanjuti" {{ $dispatch->recipient_status === 'Selesai Ditindaklanjuti' ? 'selected' : '' }}>Selesai Ditindaklanjuti</option>
                                                </select>

                                                <input type="text" 
                                                       name="recipient_feedback" 
                                                       value="{{ old('recipient_feedback') }}" 
                                                       placeholder="Catatan konfirmasi atau tindak lanjut lapangan (wajib)..." 
                                                       required 
                                                       style="padding: 0.55rem 0.85rem; border: 1px solid var(--color-border-subtle); border-radius: 6px; font-size: 0.85rem; background: #FAF9F5;">
                                            </div>

                                            <div style="display: flex; justify-content: flex-end;">
                                                <button type="submit" class="btn btn-forest" style="padding: 0.45rem 1rem; font-size: 0.82rem;">
                                                    Simpan Tindak Lanjut
                                                </button>
                                            </div>
                                        </form>
                                    </div>

                                    <!-- Audit Log Riwayat Disposisi -->
                                    @if($dispatch->histories->count() > 0)
                                        <div style="margin-top: 1.25rem; padding-top: 1rem; border-top: 1px solid var(--color-border-subtle); font-size: 0.78rem; color: #6D7870;">
                                            <span style="font-weight: 700; color: #526B4D; display: block; margin-bottom: 0.35rem;">
                                                Jejak Audit Status Disposisi:
                                            </span>
                                            <ul style="margin: 0; padding-left: 1.2rem; display: flex; flex-direction: column; gap: 0.25rem;">
                                                @foreach($dispatch->histories as $hist)
                                                    <li>
                                                        <strong>{{ $hist->created_at->format('d/m/Y H:i') }}</strong>: {{ $hist->notes }}
                                                        @if($hist->admin) (oleh {{ $hist->admin->name }}) @endif
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif

                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div style="padding: 2.5rem 1.5rem; text-align: center; color: #7F8B83;">
                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="margin-bottom: 0.5rem; color: #A0ABA3;">
                            <line x1="22" y1="2" x2="11" y2="13"></line>
                            <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                        </svg>
                        <p style="margin: 0; font-size: 0.95rem; font-weight: 500;">
                            Laporan ini belum pernah diteruskan ke instansi pemerintah atau kelompok MPA.
                        </p>
                        <a href="{{ route('admin.dispatches.create', $report) }}" class="btn btn-forest" style="margin-top: 1rem; padding: 0.6rem 1.3rem; font-size: 0.88rem; text-decoration: none; display: inline-flex; align-items: center; gap: 0.4rem;">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="22" y1="2" x2="11" y2="13"></line>
                                <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                            </svg>
                            <span>Teruskan Laporan Sekarang</span>
                        </a>
                    </div>
                @endif
            </div>
        </div>

    </div>

    @push('admin_scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const btnCopy = document.getElementById('btnCopyWaText');
            const waTextElement = document.getElementById('waMessageText');
            const statusText = document.getElementById('copyStatusText');

            if (btnCopy && waTextElement) {
                btnCopy.addEventListener('click', function () {
                    const text = waTextElement.textContent;
                    navigator.clipboard.writeText(text).then(function () {
                        if (statusText) statusText.textContent = '✓ Tersalin!';
                        btnCopy.style.background = '#D1FAE5';
                        btnCopy.style.color = '#065F46';
                        setTimeout(function () {
                            if (statusText) statusText.textContent = 'Salin Teks';
                            btnCopy.style.background = '#FFFFFF';
                            btnCopy.style.color = 'var(--color-deep-forest)';
                        }, 2500);
                    }).catch(function (err) {
                        // Fallback
                        const range = document.createRange();
                        range.selectNode(waTextElement);
                        window.getSelection().removeAllRanges();
                        window.getSelection().addRange(range);
                        document.execCommand('copy');
                        window.getSelection().removeAllRanges();
                        if (statusText) statusText.textContent = '✓ Tersalin!';
                    });
                });
            }
        });
    </script>
    @endpush
@endsection
