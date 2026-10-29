@extends('layouts.app')

@section('title', 'BORNEO SIAGA - Jaga Hutan Hari Ini, Selamatkan Masa Depan')
@section('meta_description', 'Portal tanggap darurat dan pemantauan kebakaran hutan dan lahan gambut di seluruh bentang alam Kalimantan. Informasi, pelaporan, dan kolaborasi multipihak.')

@section('content')
<!-- =========================================================================
     1. HERO SECTION
     Full-width dominant hero approaching viewport height on laptop screens
     with Kalimantan rainforest and river photography
     ========================================================================= -->
<section style="position: relative; background-color: var(--color-deep-forest); color: var(--color-warm-paper); min-height: calc(100vh - 76px); min-height: calc(100svh - 76px); display: flex; align-items: center; overflow: hidden; padding: 4.5rem 0 5rem;">
    <!-- Wide Nature Photography Background (Kalimantan River & Rainforest Canopy) -->
    <div style="position: absolute; inset: 0; background-image: url('{{ asset('images/borneo-hero.jpg') }}'); background-size: cover; background-position: center; filter: saturate(1.12);" role="img" aria-label="Pemandangan kanopi hutan hujan tropis dan sungai berkelok di Kalimantan Borneo"></div>
    
    <!-- Dark Editorial Contrast Gradient Overlay -->
    <div style="position: absolute; inset: 0; background: linear-gradient(180deg, rgba(22, 36, 28, 0.74) 0%, rgba(38, 59, 48, 0.88) 58%, rgba(38, 59, 48, 0.97) 100%);"></div>

    <div class="container-editorial" style="position: relative; z-index: 10; width: 100%;">
        <div style="display: flex; flex-direction: row; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 3.5rem;">
            <!-- Left Narrative Column -->
            <div style="flex: 1 1 540px; max-width: 680px;">
                <!-- Small Eyebrow Text -->
                <div style="display: inline-flex; align-items: center; gap: 0.65rem; margin-bottom: 1.5rem; background: rgba(214,197,163,0.12); padding: 0.35rem 0.85rem; border-radius: 999px; border: 1px solid rgba(214,197,163,0.25);">
                    <img src="{{ asset('images/logo.png') }}" alt="" style="width: 20px; height: 20px; border-radius: 50%; object-fit: cover;">
                    <span style="font-family: var(--font-body); font-size: 0.82rem; font-weight: 700; letter-spacing: 0.16em; text-transform: uppercase; color: var(--color-earth-sand);">
                        UNTUK KALIMANTAN YANG LEBIH AMAN
                    </span>
                </div>

                <!-- Large Heading -->
                <h1 style="color: #FFFFFF; font-size: clamp(2.3rem, 4.8vw, 4.1rem); font-weight: 600; line-height: 1.12; letter-spacing: -0.02em; margin-bottom: 1.4rem;">
                    Jaga Hutan Hari Ini, Selamatkan Masa Depan.
                </h1>

                <!-- Short Purpose Description -->
                <p style="color: #E2ECE5; font-size: clamp(1.05rem, 1.7vw, 1.22rem); line-height: 1.7; font-weight: 400; margin-bottom: 2.5rem; max-width: 640px;">
                    BORNEO SIAGA hadir sebagai platform terpadu untuk pemantauan titik panas (hotspot), pelaporan cepat kejadian kebakaran hutan dan lahan gambut oleh masyarakat, serta kolaborasi aktif demi melindungi paru-paru hijau Nusantara.
                </p>

                <!-- Action Buttons -->
                <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 1rem;">
                    <a href="{{ route('pantauan.karhutla') }}" class="btn btn-sand" style="padding: 0.9rem 1.9rem; font-size: 0.96rem;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true">
                            <polygon points="1 6 1 22 8 18 16 22 23 18 23 2 16 6 8 2 1 6"></polygon>
                            <line x1="8" y1="2" x2="8" y2="18"></line>
                            <line x1="16" y1="6" x2="16" y2="22"></line>
                        </svg>
                        <span>Pantau Karhutla</span>
                    </a>

                    <a href="{{ route('pengaduan') }}" class="btn btn-outline-sand" style="padding: 0.9rem 1.9rem; font-size: 0.96rem; background: rgba(38, 59, 48, 0.45); backdrop-filter: blur(4px);">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                            <line x1="12" y1="18" x2="12" y2="12"></line>
                            <line x1="9" y1="15" x2="15" y2="15"></line>
                        </svg>
                        <span>Buat Laporan</span>
                    </a>
                </div>
            </div>

            <!-- Right Visual: Logo Lencana Resmi Borneo Siaga -->
            <div style="flex: 0 0 auto; margin: 0 auto; text-align: center;">
                <div style="position: relative; display: inline-block;">
                    <!-- Outer Glow Halo -->
                    <div style="position: absolute; inset: -24px; border-radius: 50%; background: radial-gradient(circle, rgba(214,197,163,0.45) 0%, rgba(38,59,48,0) 72%); filter: blur(18px); z-index: 0;"></div>
                    
                    <!-- Circular Glass Frame -->
                    <div style="position: relative; z-index: 1; width: clamp(290px, 32vw, 420px); height: clamp(290px, 32vw, 420px); border-radius: 50%; padding: 10px; background: rgba(255,255,255,0.08); backdrop-filter: blur(12px); border: 3px solid rgba(214,197,163,0.65); box-shadow: 0 20px 50px rgba(0,0,0,0.6); transition: transform 0.3s ease;">
                        <img src="{{ asset('images/logo.png') }}" 
                             alt="Logo Resmi Borneo Siaga - Bersama Jaga Hutan Bebas Karhutla" 
                             style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%; display: block; box-shadow: 0 6px 24px rgba(0,0,0,0.4);">
                    </div>
                </div>
                <div style="margin-top: 1.5rem;">
                    <span style="font-family: var(--font-heading); font-size: 1.3rem; color: var(--color-earth-sand); letter-spacing: 0.12em; text-transform: uppercase; font-weight: 700; display: block;">
                        BERSAMA JAGA HUTAN
                    </span>
                    <span style="font-size: 0.85rem; color: #D1E0D6; letter-spacing: 0.16em; text-transform: uppercase; display: block; margin-top: 0.3rem;">
                        Bebas Karhutla • Kalimantan
                    </span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- =========================================================================
     2. AKSES CEPAT: "Layanan untuk masyarakat"
     Fine hairline dividers with 01-04 editorial index (No uniform bulky cards)
     ========================================================================= -->
<section style="background-color: var(--color-warm-paper); padding: 5rem 0 5.5rem;">
    <div class="container-editorial">
        <!-- Section Title & Lead -->
        <div style="margin-bottom: 2.75rem;">
            <span class="eyebrow">Akses Cepat & Tindakan</span>
            <h2 style="font-size: clamp(1.9rem, 3.6vw, 2.6rem); margin-bottom: 0.75rem;">Layanan untuk masyarakat</h2>
            <p class="editorial-lead" style="max-width: 720px; margin-bottom: 0;">
                Empat kanal penting yang dirancang untuk memudahkan warga, relawan, dan pemangku kepentingan dalam mengantisipasi ancaman karhutla.
            </p>
        </div>

        <!-- 4 Refined Editorial Service Columns with Hairline Borders -->
        <div class="service-editorial-grid">
            <!-- 1. Pantauan Karhutla -->
            <a href="{{ route('pantauan.karhutla') }}" class="service-editorial-item">
                <div>
                    <span class="service-number">01</span>
                    <div class="service-icon-box">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <polygon points="1 6 1 22 8 18 16 22 23 18 23 2 16 6 8 2 1 6"></polygon>
                            <line x1="8" y1="2" x2="8" y2="18"></line>
                            <line x1="16" y1="6" x2="16" y2="22"></line>
                        </svg>
                    </div>
                    <h3 class="service-title">Pantauan Karhutla</h3>
                    <p class="service-desc">
                        Akses modul pemantauan geospasial sebaran titik panas di 5 provinsi Kalimantan yang diperbarui secara terpadu.
                    </p>
                </div>
                <div class="service-arrow">
                    <span>Buka Pantauan</span>
                    <span aria-hidden="true">&rarr;</span>
                </div>
            </a>

            <!-- 2. Buat Laporan -->
            <a href="{{ route('pengaduan') }}" class="service-editorial-item">
                <div>
                    <span class="service-number">02</span>
                    <div class="service-icon-box" style="background-color: rgba(184, 83, 36, 0.12); color: var(--color-alert-amber); border-color: rgba(184, 83, 36, 0.25);">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path d="M8.5 14.5A2.5 2.5 0 0 0 11 12c0-1.38-.5-2-1-3-1.072-2.143-.224-4.054 2-6 .5 2.5 2 4.9 4 6.5 2 1.6 3 3.5 3 5.5a7 7 0 1 1-14 0c0-1.153.433-2.294 1-3a2.5 2.5 0 0 0 2.5 3z"/>
                        </svg>
                    </div>
                    <h3 class="service-title">Buat Laporan</h3>
                    <p class="service-desc">
                        Kirimkan temuan titik api dan asap di lingkungan sekitar dengan melampirkan lokasi dan bukti dokumentasi lapangan.
                    </p>
                </div>
                <div class="service-arrow">
                    <span>Form Pelaporan</span>
                    <span aria-hidden="true">&rarr;</span>
                </div>
            </a>

            <!-- 3. Lacak Laporan -->
            <a href="{{ route('lacak.pengaduan') }}" class="service-editorial-item">
                <div>
                    <span class="service-number">03</span>
                    <div class="service-icon-box" style="background-color: var(--color-sand-soft); border-color: rgba(214, 197, 163, 0.6);">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                    </div>
                    <h3 class="service-title">Lacak Laporan</h3>
                    <p class="service-desc">
                        Pantau progres penanganan aduan masyarakat secara transparan menggunakan nomor tiket resmi yang diterbitkan.
                    </p>
                </div>
                <div class="service-arrow">
                    <span>Lacak Tiket</span>
                    <span aria-hidden="true">&rarr;</span>
                </div>
            </a>

            <!-- 4. Edukasi -->
            <a href="{{ route('edukasi') }}" class="service-editorial-item">
                <div>
                    <span class="service-number">04</span>
                    <div class="service-icon-box">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                            <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                        </svg>
                    </div>
                    <h3 class="service-title">Edukasi & Mitigasi</h3>
                    <p class="service-desc">
                        Pelajari panduan kesiapsiagaan menghadapi kabut asap, konservasi tanah gambut, dan metode pengolahan lahan tanpa bakar.
                    </p>
                </div>
                <div class="service-arrow">
                    <span>Pelajari Materi</span>
                    <span aria-hidden="true">&rarr;</span>
                </div>
            </a>
        </div>
    </div>
</section>

<!-- =========================================================================
     3. PENGENALAN MANFAAT PLATFORM
     Clear platform benefits explanation supported by local documentary photo
     ========================================================================= -->
<section style="background-color: #ECE7DA; padding: 5.5rem 0; border-top: 1px solid var(--color-border-subtle); border-bottom: 1px solid var(--color-border-subtle);">
    <div class="container-editorial">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 3.5rem; align-items: center;">
            <!-- Narrative Column: Platform Benefits -->
            <div>
                <span class="eyebrow">Manfaat Platform</span>
                <h2 style="font-size: clamp(1.9rem, 3.4vw, 2.6rem); margin-bottom: 1.25rem;">
                    Sinergi Cepat untuk Menyelamatkan Ekosistem Borneo
                </h2>
                <p style="color: #404741; line-height: 1.7; margin-bottom: 2rem; font-size: 1.05rem;">
                    Bencana kebakaran hutan dan lahan gambut di Kalimantan membutuhkan tindakan dini. BORNEO SIAGA hadir untuk menjawab tiga kebutuhan krusial:
                </p>

                <div style="display: flex; flex-direction: column; gap: 1.4rem; margin-bottom: 2rem;">
                    <!-- Benefit 1 -->
                    <div style="display: flex; gap: 1rem; align-items: flex-start;">
                        <div style="width: 32px; height: 32px; border-radius: 50%; background: var(--color-deep-forest); color: var(--color-earth-sand); display: flex; align-items: center; justify-content: center; font-family: var(--font-heading); font-size: 1rem; font-weight: 700; flex-shrink: 0; margin-top: 2px;">1</div>
                        <div>
                            <strong style="color: var(--color-deep-forest); font-size: 1.05rem; display: block; margin-bottom: 0.25rem;">Kecepatan Deteksi & Keterbukaan Informasi</strong>
                            <p style="font-size: 0.92rem; color: #4a544c; margin: 0; line-height: 1.6;">
                                Menghubungkan pantauan titik panas satelit dengan visualisasi wilayah rentan, memangkas jeda waktu konfirmasi lapangan sebelum api menjalar ke kedalaman gambut.
                            </p>
                        </div>
                    </div>

                    <!-- Benefit 2 -->
                    <div style="display: flex; gap: 1rem; align-items: flex-start;">
                        <div style="width: 32px; height: 32px; border-radius: 50%; background: var(--color-deep-forest); color: var(--color-earth-sand); display: flex; align-items: center; justify-content: center; font-family: var(--font-heading); font-size: 1rem; font-weight: 700; flex-shrink: 0; margin-top: 2px;">2</div>
                        <div>
                            <strong style="color: var(--color-deep-forest); font-size: 1.05rem; display: block; margin-bottom: 0.25rem;">Pelaporan Warga yang Terstruktur & Transparan</strong>
                            <p style="font-size: 0.92rem; color: #4a544c; margin: 0; line-height: 1.6;">
                                Memberikan akses langsung kepada masyarakat untuk melaporkan insiden dengan koordinat presisi serta memantau status tindak lanjut melalui nomor tiket resmi.
                            </p>
                        </div>
                    </div>

                    <!-- Benefit 3 -->
                    <div style="display: flex; gap: 1rem; align-items: flex-start;">
                        <div style="width: 32px; height: 32px; border-radius: 50%; background: var(--color-deep-forest); color: var(--color-earth-sand); display: flex; align-items: center; justify-content: center; font-family: var(--font-heading); font-size: 1rem; font-weight: 700; flex-shrink: 0; margin-top: 2px;">3</div>
                        <div>
                            <strong style="color: var(--color-deep-forest); font-size: 1.05rem; display: block; margin-bottom: 0.25rem;">Kolaborasi Lintas Batas Tanpa Sekat</strong>
                            <p style="font-size: 0.92rem; color: #4a544c; margin: 0; line-height: 1.6;">
                                Memperkuat koordinasi antara posko siaga BPBD, regu Manggala Agni, relawan pemadam, hingga kelompok Masyarakat Peduli Api (MPA) di tingkat desa.
                            </p>
                        </div>
                    </div>
                </div>

                <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                    <a href="{{ route('tentang.kami') }}" class="btn btn-outline-forest" style="padding: 0.75rem 1.6rem;">
                        <span>Kenali Profil Borneo Siaga</span>
                        <span aria-hidden="true">&rarr;</span>
                    </a>
                </div>
            </div>

            <!-- Visual Column: Community Rangers Photo & Status Note -->
            <div>
                <div style="background: #FFFFFF; border: 1px solid var(--color-border-subtle); border-radius: 14px; overflow: hidden; box-shadow: var(--shadow-editorial);">
                    <img src="{{ asset('images/borneo-patrol.jpg') }}" 
                         alt="Regu relawan dan petugas Masyarakat Peduli Api berpatroli di tepian sungai hutan Kalimantan" 
                         style="width: 100%; height: 260px; object-fit: cover; display: block;">
                    
                    <div style="padding: 1.75rem;">
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.75rem;">
                            <span class="badge badge-forest">Aksi Terpadu</span>
                            <span style="font-size: 0.78rem; color: #6d7870;">5 Provinsi Kalimantan</span>
                        </div>
                        <h4 style="font-size: 1.25rem; margin: 0 0 0.5rem 0; color: var(--color-deep-forest);">
                            Kesiapsiagaan Posko dan Patroli Lapangan
                        </h4>
                        <p style="font-size: 0.88rem; color: #535d55; line-height: 1.6; margin: 0;">
                            Kolaborasi aktif warga lokal dan regu siaga menjadi kunci utama dalam menjaga sekat kanal gambut dan mendeteksi api sebelum asap mencemari udara.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- =========================================================================
     3.5 WARTA & KABAR KARHUTLA TERKINI (SINDIKASI BERITA EKSTERNAL)
     Real-time external news feed from verified media sources
     ========================================================================= -->
<section style="background-color: #FAF8F5; padding: 5rem 0; border-bottom: 1px solid var(--color-border-subtle);">
    <div class="container-editorial">
        <div style="display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 1.5rem; margin-bottom: 2.75rem;">
            <div>
                <div style="display: inline-flex; align-items: center; gap: 0.5rem; margin-bottom: 0.65rem;">
                    <span style="display: inline-block; width: 8px; height: 8px; border-radius: 50%; background-color: #38A169;"></span>
                    <span class="eyebrow" style="margin-bottom: 0;">Satelit & Sindikasi Berita Terkini</span>
                </div>
                <h2 style="font-size: clamp(1.85rem, 3.2vw, 2.5rem); margin-bottom: 0.5rem;">
                    Warta & Kabar Karhutla Terkini
                </h2>
                <p class="editorial-lead" style="max-width: 680px; margin-bottom: 0;">
                    Perkembangan situasi lapangan, aksi penanganan titik api, dan kabut asap di 5 provinsi Kalimantan yang terhimpun langsung dari media jurnalistik terverifikasi.
                </p>
            </div>
            <div>
                <a href="{{ route('berita.index') }}" class="btn btn-forest" style="padding: 0.75rem 1.6rem; display: inline-flex; align-items: center; gap: 0.5rem;">
                    <span>Lihat Seluruh Berita</span>
                    <span aria-hidden="true">&rarr;</span>
                </a>
            </div>
        </div>

        @if(!empty($latestNews) && count($latestNews) > 0)
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem; margin-bottom: 1.5rem;">
                @foreach($latestNews as $item)
                    <article style="background: #FFFFFF; border: 1px solid var(--color-border-subtle); border-radius: 12px; padding: 1.5rem; display: flex; flex-direction: column; justify-content: space-between; box-shadow: 0 2px 10px rgba(0,0,0,0.03); transition: transform 0.2s ease, box-shadow 0.2s ease;">
                        <div>
                            <div style="display: flex; justify-content: space-between; align-items: center; gap: 0.5rem; margin-bottom: 0.85rem; font-size: 0.76rem;">
                                <span style="font-weight: 700; color: var(--color-forest-moss); text-transform: uppercase; letter-spacing: 0.06em;">
                                    {{ $item['source'] }}
                                </span>
                                <span style="color: #768378;">
                                    {{ $item['published_human'] }}
                                </span>
                            </div>

                            <span class="badge" style="background-color: {{ $item['topic_color'] ?? '#263B30' }}15; color: {{ $item['topic_color'] ?? '#263B30' }}; font-size: 0.72rem; margin-bottom: 0.75rem; display: inline-block;">
                                {{ $item['region_name'] }} • {{ $item['topic'] ?? 'Kabar Karhutla' }}
                            </span>

                            <h3 style="font-size: 1.12rem; line-height: 1.35; margin: 0 0 0.75rem 0; font-weight: 600;">
                                <a href="{{ $item['link'] }}" target="_blank" rel="noopener noreferrer" style="color: var(--color-deep-forest); text-decoration: none;" onmouseover="this.style.color='var(--color-alert-amber)'" onmouseout="this.style.color='var(--color-deep-forest)'">
                                    {{ $item['title'] }}
                                </a>
                            </h3>

                            <p style="font-size: 0.86rem; color: #515B53; line-height: 1.55; margin: 0 0 1.25rem 0;">
                                {{ Str::limit($item['description'], 115) }}
                            </p>
                        </div>

                        <div style="padding-top: 0.85rem; border-top: 1px solid var(--color-border-subtle); display: flex; justify-content: space-between; align-items: center; font-size: 0.82rem;">
                            <span style="color: #8C9990; font-size: 0.74rem;">
                                {{ Str::limit($item['published_formatted'], 16) }}
                            </span>
                            <a href="{{ $item['link'] }}" target="_blank" rel="noopener noreferrer" style="color: var(--color-deep-forest); font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 0.3rem;">
                                <span>Baca Sumber</span>
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </div>
</section>

<!-- =========================================================================
     4. ARTIKEL EDUKASI PILIHAN
     Featured educational articles pointing to /edukasi with authentic imagery
     ========================================================================= -->
<section style="background-color: var(--color-warm-paper); padding: 5.5rem 0 6rem;">
    <div class="container-editorial">
        <!-- Section Header -->
        <div style="display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 1.5rem; margin-bottom: 3rem;">
            <div>
                <span class="eyebrow">Literasi & Mitigasi</span>
                <h2 style="font-size: clamp(1.85rem, 3.2vw, 2.5rem); margin-bottom: 0.5rem;">Artikel Edukasi Pilihan</h2>
                <p class="editorial-lead" style="max-width: 680px; margin-bottom: 0;">
                    Pelajari panduan lapangan tentang konservasi gambut, perlindungan kesehatan keluarga dari kabut asap, serta teknik pengolahan lahan tanpa bakar.
                </p>
            </div>
            <div>
                <a href="{{ route('edukasi') }}" class="btn btn-outline-forest" style="padding: 0.75rem 1.5rem;">
                    <span>Buka Pustaka Edukasi</span>
                    <span aria-hidden="true">&rarr;</span>
                </a>
            </div>
        </div>

        <!-- 3 Featured Article Cards Pointing to /edukasi -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.75rem; margin-bottom: 2rem;">
            <!-- Article 1: Peatland Conservation -->
            <article class="article-editorial-card">
                <div style="height: 190px; overflow: hidden; position: relative;">
                    <img src="{{ asset('images/borneo-peatland.jpg') }}" 
                         alt="Hutan rawa gambut tropis Kalimantan dengan perahu tradisional menyusuri sungai berair gelap alami" 
                         style="width: 100%; height: 100%; object-fit: cover; display: block;">
                </div>
                <div style="padding: 1.75rem; display: flex; flex-direction: column; flex-grow: 1;">
                    <span class="badge badge-forest" style="align-self: flex-start; margin-bottom: 0.85rem;">Konservasi Gambut</span>
                    <h3 style="font-size: 1.25rem; margin: 0 0 0.75rem 0; line-height: 1.3;">
                        Karakteristik Lahan Gambut & Mengapa Api Bawah Tanah Sulit Dipadamkan
                    </h3>
                    <p style="font-size: 0.88rem; color: #555E56; line-height: 1.6; margin-bottom: 1.5rem; flex-grow: 1;">
                        Memahami sifat bahan organik gambut yang menyimpan panas di bawah permukaan tanah dan metode pembasahan kembali (rewetting) untuk menjaga kelembapan.
                    </p>
                    <div style="padding-top: 1rem; border-top: 1px solid var(--color-border-subtle); font-size: 0.84rem; display: flex; justify-content: space-between; align-items: center;">
                        <span style="color: #768378;">Modul Edukasi</span>
                        <a href="{{ route('edukasi') }}" style="font-weight: 600; color: var(--color-deep-forest);">
                            Baca di Edukasi &rarr;
                        </a>
                    </div>
                </div>
            </article>

            <!-- Article 2: Health Protection -->
            <article class="article-editorial-card">
                <div style="height: 190px; background-color: #E5E0D2; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 1.5rem; text-align: center; border-bottom: 1px solid var(--color-border-subtle);">
                    <div style="width: 44px; height: 44px; border-radius: 50%; background: rgba(38, 59, 48, 0.08); display: flex; align-items: center; justify-content: center; color: var(--color-forest-moss); margin-bottom: 0.5rem;">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    </div>
                    <span style="font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.08em; color: var(--color-forest-moss); font-weight: 700;">Panduan Kesehatan</span>
                    <span style="font-size: 0.84rem; color: #505951; margin-top: 0.25rem;">Protokol Pencegahan Bahaya Asap</span>
                </div>
                <div style="padding: 1.75rem; display: flex; flex-direction: column; flex-grow: 1;">
                    <span class="badge badge-sand" style="align-self: flex-start; margin-bottom: 0.85rem;">Kesehatan Publik</span>
                    <h3 style="font-size: 1.25rem; margin: 0 0 0.75rem 0; line-height: 1.3;">
                        Protokol Perlindungan Kesehatan Keluarga dari Paparan Partikulat PM2.5
                    </h3>
                    <p style="font-size: 0.88rem; color: #555E56; line-height: 1.6; margin-bottom: 1.5rem; flex-grow: 1;">
                        Langkah kesiapsiagaan menghadapi kabut asap, standar pemilihan masker penahan partikel mikron, serta perlindungan ekstra bagi balita dan lansia.
                    </p>
                    <div style="padding-top: 1rem; border-top: 1px solid var(--color-border-subtle); font-size: 0.84rem; display: flex; justify-content: space-between; align-items: center;">
                        <span style="color: #768378;">Modul Edukasi</span>
                        <a href="{{ route('edukasi') }}" style="font-weight: 600; color: var(--color-deep-forest);">
                            Baca di Edukasi &rarr;
                        </a>
                    </div>
                </div>
            </article>

            <!-- Article 3: Community Patrol & PLTB -->
            <article class="article-editorial-card">
                <div style="height: 190px; overflow: hidden; position: relative;">
                    <img src="{{ asset('images/borneo-patrol.jpg') }}" 
                         alt="Kelompok Masyarakat Peduli Api siap menjalankan patroli pemantauan kawasan hutan rawan" 
                         style="width: 100%; height: 100%; object-fit: cover; display: block;">
                </div>
                <div style="padding: 1.75rem; display: flex; flex-direction: column; flex-grow: 1;">
                    <span class="badge badge-forest" style="align-self: flex-start; margin-bottom: 0.85rem;">Praktik Komunitas</span>
                    <h3 style="font-size: 1.25rem; margin: 0 0 0.75rem 0; line-height: 1.3;">
                        Gotong Royong Masyarakat Peduli Api dalam Perawatan Sekat Kanal
                    </h3>
                    <p style="font-size: 0.88rem; color: #555E56; line-height: 1.6; margin-bottom: 1.5rem; flex-grow: 1;">
                        Prakarsa mandiri warga desa di Kalimantan dalam membangun dan merawat sekat kanal kayu untuk menahan laju penurunan air gambut di musim kemarau.
                    </p>
                    <div style="padding-top: 1rem; border-top: 1px solid var(--color-border-subtle); font-size: 0.84rem; display: flex; justify-content: space-between; align-items: center;">
                        <span style="color: #768378;">Modul Edukasi</span>
                        <a href="{{ route('edukasi') }}" style="font-weight: 600; color: var(--color-deep-forest);">
                            Baca di Edukasi &rarr;
                        </a>
                    </div>
                </div>
            </article>
        </div>

        <!-- Transparent Editorial Note -->
        <div style="background-color: #FAF9F5; border: 1px dashed rgba(82, 107, 77, 0.35); border-radius: 8px; padding: 1rem 1.5rem; display: flex; align-items: center; gap: 0.75rem; font-size: 0.84rem; color: #505A52;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink: 0; color: var(--color-forest-moss);" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
            <span>
                <strong>Catatan Integrasi:</strong> Pilihan artikel di atas disiapkan untuk menampung publikasi kurasi resmi. Seluruh materi edukasi lengkap dapat diakses langsung pada halaman <a href="{{ route('edukasi') }}" style="text-decoration: underline; font-weight: 600; color: var(--color-deep-forest);">Edukasi & Mitigasi</a>.
            </span>
        </div>
    </div>
</section>

<!-- =========================================================================
     5. CALL TO ACTION / PENUTUP EDITORIAL
     ========================================================================= -->
<section style="background-color: var(--color-deep-forest); color: var(--color-warm-paper); padding: 4.5rem 0; border-top: 1px solid rgba(214, 197, 163, 0.2);">
    <div class="container-editorial">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 2rem;">
            <div style="max-width: 680px;">
                <span style="font-size: 0.74rem; text-transform: uppercase; letter-spacing: 0.14em; color: var(--color-earth-sand); font-weight: 700; display: block; margin-bottom: 0.65rem;">
                    Partisipasi Perlindungan Lingkungan
                </span>
                <h2 style="color: #FFFFFF; font-size: clamp(1.8rem, 3.2vw, 2.4rem); margin-bottom: 0.75rem; line-height: 1.2;">
                    Melihat Kepulan Asap atau Titik Api di Wilayah Anda?
                </h2>
                <p style="color: #D3DFD7; font-size: 1rem; line-height: 1.65; margin: 0;">
                    Laporan cepat Anda membantu tim regu pemadam tiba di lokasi sebelum api menjalar ke kedalaman lapisan gambut.
                </p>
            </div>
            <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                <a href="{{ route('pengaduan') }}" class="btn btn-sand" style="padding: 0.85rem 1.75rem;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true">
                        <path d="M8.5 14.5A2.5 2.5 0 0 0 11 12c0-1.38-.5-2-1-3-1.072-2.143-.224-4.054 2-6 .5 2.5 2 4.9 4 6.5 2 1.6 3 3.5 3 5.5a7 7 0 1 1-14 0c0-1.153.433-2.294 1-3a2.5 2.5 0 0 0 2.5 3z"/>
                    </svg>
                    <span>Kirim Pengaduan Sekarang</span>
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
