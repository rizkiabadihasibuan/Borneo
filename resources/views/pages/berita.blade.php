@extends('layouts.app')

@section('title', 'Kabar & Warta Karhutla Terkini Kalimantan | BORNEO SIAGA')
@section('meta_description', 'Agregasi berita dan laporan situasi kebakaran hutan dan lahan (karhutla) di Kalimantan secara real-time dari media berita nasional dan daerah terverifikasi.')

@section('content')
<!-- =========================================================================
     1. HERO HEADER: WARTA & KABAR KARHUTLA KALIMANTAN
     ========================================================================= -->
<section style="position: relative; background-color: var(--color-deep-forest); color: var(--color-warm-paper); padding: 3.25rem 0 3rem; overflow: hidden;">
    <!-- Nature Photo Background Overlay -->
    <div style="position: absolute; inset: 0; background-image: url('{{ asset('images/borneo-hero.jpg') }}'); background-size: cover; background-position: center; filter: saturate(1.1); opacity: 0.18;" role="img" aria-label="Kanopi hutan Kalimantan"></div>
    <div style="position: absolute; inset: 0; background: linear-gradient(180deg, rgba(22, 36, 28, 0.88) 0%, rgba(38, 59, 48, 0.96) 100%);"></div>

    <div class="container-editorial" style="position: relative; z-index: 10;">
        <div style="max-width: 820px; margin: 0 auto; text-align: center;">
            <!-- Eyebrow Badge -->
            <div style="display: inline-flex; align-items: center; gap: 0.65rem; margin-bottom: 1.15rem; background: rgba(214, 197, 163, 0.12); padding: 0.35rem 0.95rem; border-radius: 999px; border: 1px solid rgba(214, 197, 163, 0.28);">
                <span style="display: inline-block; width: 8px; height: 8px; border-radius: 50%; background-color: #48BB78; animation: pulse 2s infinite;"></span>
                <span style="font-family: var(--font-body); font-size: 0.78rem; font-weight: 700; letter-spacing: 0.16em; text-transform: uppercase; color: var(--color-earth-sand);">
                    INTEGRASI MEDIA & WARTA EKSTERNAL
                </span>
            </div>

            <!-- Page Title -->
            <h1 style="color: #FFFFFF; font-size: clamp(2rem, 3.8vw, 2.9rem); font-weight: 600; line-height: 1.18; margin-bottom: 0.85rem; letter-spacing: -0.018em;">
                Kabar & Berita Karhutla Terkini
            </h1>

            <!-- Subtitle Description -->
            <p style="color: #DDE7E1; font-size: clamp(0.98rem, 1.6vw, 1.12rem); line-height: 1.65; margin: 0 auto 1.5rem; max-width: 680px;">
                Pantauan kabar lapangan, penanganan titik api, kebijakan pemerintah daerah, serta perkembangan kabut asap di 5 provinsi Kalimantan yang dihimpun secara otomatis dari media terverifikasi.
            </p>

            <!-- Metadata Pills: Source & Sync Time -->
            <div style="display: inline-flex; flex-wrap: wrap; justify-content: center; align-items: center; gap: 1rem; font-size: 0.82rem; color: #CBD8D0;">
                <span style="display: inline-flex; align-items: center; gap: 0.4rem;">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
                    Sumber: <strong>Antara News, Media Nasional & Daerah</strong>
                </span>
                <span style="color: rgba(214, 197, 163, 0.5);">•</span>
                <span style="display: inline-flex; align-items: center; gap: 0.4rem;">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                    Pembaruan: <strong>{{ $news['last_synced_human'] ?? 'Baru saja' }}</strong>
                </span>
                <span style="color: rgba(214, 197, 163, 0.5);">•</span>
                <a href="{{ route('berita.index', ['region' => $currentRegion, 'refresh' => 1]) }}" style="color: var(--color-earth-sand); text-decoration: underline; font-weight: 600; display: inline-flex; align-items: center; gap: 0.35rem;">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M21.5 2v6h-6M21.34 15.57a10 10 0 1 1-.57-8.38l5.67-5.67"/></svg>
                    Segarkan Data
                </a>
            </div>
        </div>
    </div>
</section>

<!-- =========================================================================
     2. FILTER & KONTROL PENCARIAN BERITA
     ========================================================================= -->
<section style="background-color: var(--color-warm-paper); padding: 2rem 0 4.5rem; border-top: 1px solid var(--color-border-subtle);">
    <div class="container-editorial">

        <!-- Bar Kontrol & Filter -->
        <div style="background: #FFFFFF; border: 1px solid var(--color-border-subtle); border-radius: 14px; padding: 1.25rem 1.5rem; margin-bottom: 2.5rem; box-shadow: 0 4px 18px rgba(0,0,0,0.04);">
            <div style="display: flex; flex-direction: column; gap: 1.25rem;">
                
                <!-- Baris Atas: Tombol Filter Provinsi -->
                <div>
                    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.75rem; margin-bottom: 0.75rem;">
                        <span style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.12em; color: var(--color-forest-moss); font-weight: 700;">
                            Filter Wilayah Kalimantan
                        </span>
                        <span style="font-size: 0.82rem; color: #6F7B72;">
                            Menampilkan <strong>{{ count($news['items'] ?? []) }}</strong> artikel berita terverifikasi
                        </span>
                    </div>

                    <div style="display: flex; flex-wrap: wrap; gap: 0.5rem;" role="group" aria-label="Filter Wilayah Berita">
                        @foreach($regions as $code => $label)
                            <a href="{{ route('berita.index', array_filter(['region' => $code, 'q' => $keyword])) }}" 
                               class="news-region-btn {{ $currentRegion === $code ? 'active' : '' }}"
                               style="padding: 0.5rem 1rem; border-radius: 8px; font-size: 0.86rem; font-weight: 600; text-decoration: none; transition: all 0.2s ease; display: inline-flex; align-items: center; gap: 0.4rem; border: 1px solid {{ $currentRegion === $code ? 'var(--color-deep-forest)' : 'var(--color-border-subtle)' }}; background-color: {{ $currentRegion === $code ? 'var(--color-deep-forest)' : '#F9F8F5' }}; color: {{ $currentRegion === $code ? 'var(--color-earth-sand)' : 'var(--color-forest-moss)' }};">
                                @if($currentRegion === $code)
                                    <span style="width: 6px; height: 6px; border-radius: 50%; background-color: var(--color-earth-sand);"></span>
                                @endif
                                <span>{{ $label }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- Baris Bawah: Form Pencarian Kata Kunci -->
                <form action="{{ route('berita.index') }}" method="GET" style="display: flex; gap: 0.75rem; flex-wrap: wrap; align-items: center; padding-top: 1rem; border-top: 1px dashed var(--color-border-subtle);">
                    <input type="hidden" name="region" value="{{ $currentRegion }}">
                    
                    <div style="position: relative; flex: 1 1 280px;">
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: #78857C;">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                        <input type="text" 
                               name="q" 
                               id="newsSearchInput"
                               value="{{ $keyword }}" 
                               placeholder="Cari kata kunci (cth: water bombing, kabut asap, satgas, sekat kanal)..." 
                               style="width: 100%; padding: 0.65rem 1rem 0.65rem 2.6rem; border-radius: 8px; border: 1px solid var(--color-border-subtle); background: #FAF9F5; font-size: 0.9rem; color: #222; outline: none; transition: border-color 0.2s;"
                               onfocus="this.style.borderColor='var(--color-deep-forest)'"
                               onblur="this.style.borderColor='var(--color-border-subtle)'">
                    </div>

                    <button type="submit" class="btn btn-forest" style="padding: 0.65rem 1.4rem; font-size: 0.88rem; flex-shrink: 0;">
                        <span>Cari Berita</span>
                    </button>

                    @if(!empty($keyword))
                        <a href="{{ route('berita.index', ['region' => $currentRegion]) }}" class="btn btn-outline-forest" style="padding: 0.65rem 1.15rem; font-size: 0.88rem; flex-shrink: 0; color: #8C3A27; border-color: rgba(184, 83, 36, 0.4);">
                            <span>Reset Pencarian</span>
                        </a>
                    @endif
                </form>

            </div>
        </div>

        @if(empty($news['items']) || count($news['items']) === 0)
            <!-- State Kosong / Tidak Ada Berita -->
            <div style="background: #FFFFFF; border: 1px dashed var(--color-border-subtle); border-radius: 12px; padding: 4rem 2rem; text-align: center; max-width: 600px; margin: 0 auto;">
                <div style="width: 56px; height: 56px; border-radius: 50%; background: rgba(38, 59, 48, 0.08); display: flex; align-items: center; justify-content: center; margin: 0 auto 1.25rem; color: var(--color-forest-moss);">
                    <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                </div>
                <h3 style="font-size: 1.4rem; margin-bottom: 0.5rem; color: var(--color-deep-forest);">Tidak Menemukan Berita</h3>
                <p style="font-size: 0.92rem; color: #647067; line-height: 1.6; margin-bottom: 1.5rem;">
                    Tidak ada artikel yang cocok dengan filter atau kata kunci "{{ $keyword }}". Coba gunakan istilah umum atau reset pilihan wilayah Anda.
                </p>
                <a href="{{ route('berita.index') }}" class="btn btn-forest" style="padding: 0.65rem 1.5rem;">
                    <span>Lihat Semua Berita Karhutla</span>
                </a>
            </div>
        @else
            <!-- =========================================================================
                 3. BERITA UTAMA / HEADLINE TERPILIH (Artikel Pertama)
                 ========================================================================= -->
            @php
                $leadItem = $news['items'][0];
                $remainingItems = array_slice($news['items'], 1);
            @endphp

            <article style="background: #FFFFFF; border: 1px solid var(--color-border-subtle); border-radius: 16px; overflow: hidden; margin-bottom: 3rem; box-shadow: var(--shadow-editorial); transition: transform 0.25s ease;">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); align-items: stretch;">
                    
                    <!-- Sisi Kiri: Editorial Visual / Hero Canvas -->
                    <div style="background: linear-gradient(135deg, #1A2921 0%, #263B30 65%, #344F41 100%); padding: 3rem 2.5rem; color: var(--color-warm-paper); display: flex; flex-direction: column; justify-content: space-between; position: relative; overflow: hidden;">
                        <!-- Subtle Background Badge -->
                        <div style="position: absolute; right: -25px; bottom: -25px; opacity: 0.08; pointer-events: none;">
                            <svg width="240" height="240" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><polygon points="1 6 1 22 8 18 16 22 23 18 23 2 16 6 8 2 1 6"></polygon></svg>
                        </div>

                        <div>
                            <div style="display: flex; align-items: center; gap: 0.75rem; flex-wrap: wrap; margin-bottom: 1.5rem;">
                                <span style="background: rgba(214, 197, 163, 0.2); border: 1px solid rgba(214, 197, 163, 0.4); color: var(--color-earth-sand); padding: 0.3rem 0.8rem; border-radius: 999px; font-size: 0.76rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em;">
                                    SOROTAN UTAMA
                                </span>
                                <span style="background: rgba(255, 255, 255, 0.12); padding: 0.3rem 0.75rem; border-radius: 6px; font-size: 0.78rem; color: #E0EAE3;">
                                    {{ $leadItem['region_name'] }}
                                </span>
                                <span style="font-size: 0.8rem; color: #B9C9BF;">
                                    {{ $leadItem['published_human'] }}
                                </span>
                            </div>

                            <span style="display: inline-block; font-size: 0.82rem; text-transform: uppercase; letter-spacing: 0.12em; color: var(--color-earth-sand); font-weight: 700; margin-bottom: 0.65rem;">
                                {{ $leadItem['source'] }}
                            </span>

                            <h2 style="font-size: clamp(1.6rem, 2.8vw, 2.2rem); color: #FFFFFF; font-weight: 600; line-height: 1.25; margin-bottom: 1.25rem;">
                                <a href="{{ $leadItem['link'] }}" target="_blank" rel="noopener noreferrer" style="color: #FFFFFF; text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='var(--color-earth-sand)'" onmouseout="this.style.color='#FFFFFF'">
                                    {{ $leadItem['title'] }}
                                </a>
                            </h2>

                            <p style="font-size: 0.98rem; line-height: 1.65; color: #D7E3DC; margin-bottom: 2rem;">
                                {{ Str::limit($leadItem['description'], 220) }}
                            </p>
                        </div>

                        <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem; padding-top: 1.5rem; border-top: 1px solid rgba(214, 197, 163, 0.2);">
                            <span style="font-size: 0.82rem; color: #B9C9BF;">
                                Diterbitkan: {{ $leadItem['published_formatted'] }}
                            </span>

                            <a href="{{ $leadItem['link'] }}" target="_blank" rel="noopener noreferrer" class="btn btn-sand" style="padding: 0.65rem 1.4rem; font-size: 0.88rem; display: inline-flex; align-items: center; gap: 0.5rem;">
                                <span>Baca di {{ $leadItem['source'] }}</span>
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
                            </a>
                        </div>
                    </div>

                    <!-- Sisi Kanan: Cuplikan Berita Kilat (3 Berita Teratas Berikutnya) -->
                    <div style="padding: 2.25rem 2rem; background: #FAF9F5; display: flex; flex-direction: column; justify-content: space-between;">
                        <div>
                            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.25rem; padding-bottom: 0.75rem; border-bottom: 1px solid var(--color-border-subtle);">
                                <span style="font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.12em; color: var(--color-forest-moss); font-weight: 700;">
                                    Warta Terbaru Lainnya
                                </span>
                                <span style="font-size: 0.78rem; color: #768379;">
                                    Real-time Feed
                                </span>
                            </div>

                            <div style="display: flex; flex-direction: column; gap: 1.4rem;">
                                @foreach(array_slice($remainingItems, 0, 3) as $sideItem)
                                    <div style="display: flex; flex-direction: column; gap: 0.35rem; padding-bottom: 1.15rem; border-bottom: 1px dashed rgba(82, 107, 77, 0.2);">
                                        <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.75rem;">
                                            <span style="font-weight: 700; color: var(--color-forest-moss);">{{ $sideItem['source'] }}</span>
                                            <span style="color: #9AA69E;">•</span>
                                            <span style="color: #6F7B72;">{{ $sideItem['published_human'] }}</span>
                                            <span style="color: #9AA69E;">•</span>
                                            <span class="badge badge-sand" style="font-size: 0.68rem; padding: 0.15rem 0.5rem;">{{ $sideItem['region_name'] }}</span>
                                        </div>
                                        <h4 style="font-size: 1.05rem; margin: 0; line-height: 1.35; font-weight: 600;">
                                            <a href="{{ $sideItem['link'] }}" target="_blank" rel="noopener noreferrer" style="color: var(--color-deep-forest); text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='var(--color-alert-amber)'" onmouseout="this.style.color='var(--color-deep-forest)'">
                                                {{ $sideItem['title'] }}
                                            </a>
                                        </h4>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div style="margin-top: 1.5rem; background: #FFFFFF; border: 1px solid var(--color-border-subtle); border-radius: 8px; padding: 0.85rem 1rem; font-size: 0.82rem; color: #515B53; display: flex; align-items: center; gap: 0.65rem;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink: 0; color: var(--color-forest-moss);"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                            <span>
                                Klik tautan pada setiap berita untuk membaca laporan lengkap langsung pada situs redaksi penerbit resmi.
                            </span>
                        </div>
                    </div>

                </div>
            </article>

            <!-- =========================================================================
                 4. GRID DAFTAR LENGKAP ARTIKEL BERITA
                 ========================================================================= -->
            <div style="margin-bottom: 2rem;">
                <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 1.5rem;">
                    <div>
                        <span class="eyebrow">Daftar Lengkap</span>
                        <h3 style="font-size: 1.6rem; margin: 0; color: var(--color-deep-forest);">
                            Arsip & Kabar Wilayah {{ $regions[$currentRegion] ?? 'Kalimantan' }}
                        </h3>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 1.75rem;">
                    @foreach($remainingItems as $item)
                        <article class="article-editorial-card" style="display: flex; flex-direction: column; height: 100%; background: #FFFFFF; border: 1px solid var(--color-border-subtle); border-radius: 12px; overflow: hidden; box-shadow: 0 3px 14px rgba(0,0,0,0.03); transition: transform 0.2s ease, box-shadow 0.2s ease;">
                            
                            <!-- Card Header: Topic & Region -->
                            <div style="padding: 1.25rem 1.5rem 0.75rem; display: flex; justify-content: space-between; align-items: center; gap: 0.5rem; flex-wrap: wrap;">
                                <span class="badge" style="background-color: {{ $item['topic_color'] }}15; color: {{ $item['topic_color'] }}; border: 1px solid {{ $item['topic_color'] }}30; font-size: 0.74rem;">
                                    {{ $item['topic'] }}
                                </span>
                                <span style="font-size: 0.76rem; color: #768378; font-weight: 500;">
                                    {{ $item['published_human'] }}
                                </span>
                            </div>

                            <!-- Card Body -->
                            <div style="padding: 0.5rem 1.5rem 1.25rem; flex-grow: 1; display: flex; flex-direction: column;">
                                <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.65rem;">
                                    <span style="font-size: 0.78rem; font-weight: 700; color: var(--color-forest-moss); text-transform: uppercase; letter-spacing: 0.06em;">
                                        {{ $item['source'] }}
                                    </span>
                                    <span style="color: #BAC5BD;">•</span>
                                    <span style="font-size: 0.76rem; color: #5B665E; background: #ECE8DC; padding: 0.15rem 0.45rem; border-radius: 4px;">
                                        {{ $item['region_name'] }}
                                    </span>
                                </div>

                                <h4 style="font-size: 1.15rem; line-height: 1.35; margin: 0 0 0.75rem 0; font-weight: 600;">
                                    <a href="{{ $item['link'] }}" target="_blank" rel="noopener noreferrer" style="color: var(--color-deep-forest); text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='var(--color-alert-amber)'" onmouseout="this.style.color='var(--color-deep-forest)'">
                                        {{ $item['title'] }}
                                    </a>
                                </h4>

                                <p style="font-size: 0.88rem; color: #515B53; line-height: 1.55; margin: 0 0 1.25rem 0; flex-grow: 1;">
                                    {{ Str::limit($item['description'], 140) }}
                                </p>

                                <div style="padding-top: 0.85rem; border-top: 1px solid var(--color-border-subtle); display: flex; justify-content: space-between; align-items: center; font-size: 0.82rem;">
                                    <span style="color: #7A877E; font-size: 0.75rem;">
                                        {{ Str::limit($item['published_formatted'], 16) }}
                                    </span>

                                    <a href="{{ $item['link'] }}" target="_blank" rel="noopener noreferrer" style="color: var(--color-deep-forest); font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 0.35rem;" onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">
                                        <span>Buka Berita</span>
                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
                                    </a>
                                </div>
                            </div>

                        </article>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- =========================================================================
             5. DISCLAIMER & ATRIBUSI RESMI
             ========================================================================= -->
        <div style="background-color: #ECE7DA; border: 1px solid var(--color-border-subtle); border-radius: 12px; padding: 1.5rem 1.75rem; margin-top: 3.5rem; display: flex; align-items: flex-start; gap: 1rem;">
            <div style="width: 36px; height: 36px; border-radius: 50%; background: var(--color-deep-forest); color: var(--color-earth-sand); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
            </div>
            <div style="font-size: 0.84rem; color: #465048; line-height: 1.6;">
                <strong style="color: var(--color-deep-forest); display: block; margin-bottom: 0.25rem;">
                    Keterbukaan Sumber Informasi & Hak Cipta
                </strong>
                Seluruh warta dan laporan berita di atas dihimpun secara otomatis dari sindikasi publik media jurnalistik nasional dan daerah terverifikasi demi kepentingan keterbukaan informasi mitigasi bencana karhutla. Hak cipta penuh atas materi dan isi berita tetap berada pada masing-masing penerbit resmi.
            </div>
        </div>

    </div>
</section>
@endsection
