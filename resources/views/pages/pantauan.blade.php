@extends('layouts.app')

@section('title', 'Pantauan Karhutla di Kalimantan | BORNEO SIAGA')
@section('meta_description', 'Peta interaktif pemantauan titik panas (hotspot) di 5 provinsi Kalimantan berbasis data satelit NASA FIRMS dan informasi cuaca BMKG.')

@push('styles')
<!-- Leaflet CSS 1.9.4 -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
@endpush

@section('content')
<!-- =========================================================================
     1. HERO HEADER: Pantauan Karhutla di Kalimantan
     ========================================================================= -->
<section style="position: relative; background-color: var(--color-deep-forest); color: var(--color-warm-paper); padding: 2rem 0 1.85rem; overflow: hidden;">
    <!-- Nature Photo Background Overlay -->
    <div style="position: absolute; inset: 0; background-image: url('{{ asset('images/borneo-hero.jpg') }}'); background-size: cover; background-position: center; filter: saturate(1.1); opacity: 0.2;" role="img" aria-label="Kanopi hutan hujan Kalimantan"></div>
    <div style="position: absolute; inset: 0; background: linear-gradient(180deg, rgba(26, 41, 33, 0.85) 0%, rgba(38, 59, 48, 0.95) 100%);"></div>

    <div class="container-editorial" style="position: relative; z-index: 10;">
        <div style="max-width: 760px; margin: 0 auto; text-align: center;">
            <div style="display: inline-flex; align-items: center; justify-content: center; gap: 0.6rem; margin-bottom: 0.4rem;">
                <span style="display: inline-block; width: 20px; height: 1.5px; background-color: var(--color-earth-sand);"></span>
                <span style="font-family: var(--font-body); font-size: 0.75rem; font-weight: 700; letter-spacing: 0.14em; text-transform: uppercase; color: var(--color-earth-sand);">
                    Sistem Pemantauan Geospasial
                </span>
                <span style="display: inline-block; width: 20px; height: 1.5px; background-color: var(--color-earth-sand);"></span>
            </div>

            <h1 style="color: #FFFFFF; font-size: clamp(1.75rem, 3.2vw, 2.4rem); font-weight: 600; line-height: 1.2; margin-bottom: 0.45rem; letter-spacing: -0.015em;">
                Pantauan Karhutla di Kalimantan
            </h1>

            <p style="color: #DDE7E1; font-size: 0.95rem; line-height: 1.55; margin: 0 auto; max-width: 620px;">
                Pemantauan sebaran titik panas (hotspot) satelit dan status kerawanan karhutla di 5 provinsi Kalimantan secara berkala.
            </p>
        </div>
    </div>
</section>

<!-- =========================================================================
     2. AREA PETA INTERAKTIF & FILTER WILAYAH
     Large focal interactive map with region filters and Leaflet OpenStreetMap
     ========================================================================= -->
<section style="background-color: var(--color-warm-paper); padding: 1.75rem 0 4.5rem;">
    <div class="container-editorial">
        <!-- Interactive Controls Bar: Region Filters & Map Controls -->
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1.25rem; margin-bottom: 1.5rem;">
            <div>
                <span style="font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.1em; color: var(--color-forest-moss); font-weight: 700; display: block; margin-bottom: 0.25rem;">
                    Filter Wilayah Kalimantan
                </span>
                <p style="font-size: 0.75rem; color: #69766c; margin: 0 0 0.6rem 0; line-height: 1.4;">
                    * Catatan: Pengelompokan filter provinsi menggunakan estimasi batas koordinat spasial (bounding box). Batas kadaster resmi mengacu pada BIG / Kemendagri.
                </p>
                <div class="map-filter-group" id="regionFilters" role="group" aria-label="Filter Wilayah Provinsi">
                    <button type="button" class="map-filter-btn active" data-region="all" data-name="Seluruh Kalimantan">
                        Seluruh Kalimantan
                    </button>
                    <button type="button" class="map-filter-btn" data-region="kalbar" data-name="Kalimantan Barat">
                        Kalimantan Barat
                    </button>
                    <button type="button" class="map-filter-btn" data-region="kalteng" data-name="Kalimantan Tengah">
                        Kalimantan Tengah
                    </button>
                    <button type="button" class="map-filter-btn" data-region="kalsel" data-name="Kalimantan Selatan">
                        Kalimantan Selatan
                    </button>
                    <button type="button" class="map-filter-btn" data-region="kaltim" data-name="Kalimantan Timur">
                        Kalimantan Timur
                    </button>
                    <button type="button" class="map-filter-btn" data-region="kaltara" data-name="Kalimantan Utara">
                        Kalimantan Utara
                    </button>
                </div>
            </div>

            <!-- Focus Reset & Refresh -->
            <div style="display: flex; align-items: center; gap: 0.75rem; flex-wrap: wrap;">
                <button type="button" id="btnRefreshData" class="btn btn-outline-forest" style="padding: 0.5rem 1rem; font-size: 0.84rem;">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" id="iconRefreshSpin"><path d="M21.5 2v6h-6M21.34 15.57a10 10 0 1 1-.57-8.38l5.67-5.67"/></svg>
                    <span id="textBtnRefresh">Sinkronkan Satelit</span>
                </button>
                <button type="button" id="btnResetView" class="btn btn-outline-forest" style="padding: 0.5rem 1rem; font-size: 0.84rem;">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg>
                    <span>Reset Posisi Peta</span>
                </button>
            </div>
        </div>

        <!-- The Focal Interactive Leaflet Map Canvas -->
        <div class="map-canvas-container" id="mapCanvas" aria-label="Peta Geospasial Titik Panas Kalimantan" style="position: relative;">
            <!-- Map Status & Feedback Overlay -->
            <div id="mapStatusOverlay" style="display: none; position: absolute; top: 1rem; right: 1rem; z-index: 1000; background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(8px); border: 1px solid var(--color-border-subtle); border-radius: 8px; padding: 0.75rem 1.15rem; box-shadow: var(--shadow-editorial); font-size: 0.84rem; max-width: 340px; pointer-events: auto;">
                <div id="mapOverlayContent" style="display: flex; align-items: center; gap: 0.65rem;"></div>
            </div>

            <!-- Fallback noscript -->
            <noscript>
                <div style="padding: 3rem; text-align: center;">
                    Peta interaktif membutuhkan JavaScript aktif untuk memuat peta OpenStreetMap dan marker titik panas.
                </div>
            </noscript>
        </div>

        <!-- Notification Bar: Data Status & Active Counts -->
        <div style="margin-top: 1.25rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; font-size: 0.86rem; color: #515B53;">
            <div id="hotspotSummaryText" style="display: flex; align-items: center; gap: 0.5rem;">
                <span style="width: 9px; height: 9px; border-radius: 50%; background-color: {{ count($firms['hotspots']) > 0 ? '#B83B26' : '#6E8967' }}; display: inline-block;"></span>
                <span>
                    @if($firms['status'] === 'success')
                        Terdeteksi <strong>{{ count($firms['hotspots']) }} titik panas satelit</strong> pada wilayah terpilih.
                    @elseif($firms['status'] === 'unconfigured')
                        Status data: <strong>Integrasi NASA FIRMS Menunggu Kunci API (MAP_KEY)</strong>. Tidak ada marker tiruan yang ditampilkan.
                    @else
                        Status data: <strong>{{ $firms['message'] }}</strong>.
                    @endif
                </span>
            </div>

            <div style="font-size: 0.8rem; color: #6E7970;">
                Atribusi Peta: &copy; <a href="https://www.openstreetmap.org/copyright" target="_blank" rel="noopener" style="text-decoration: underline; color: var(--color-deep-forest);">OpenStreetMap</a> contributors
            </div>
        </div>

        <!-- Map Legend and Detailed Guidelines -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem; margin-top: 2rem;">
            <!-- Legend Card -->
            <div class="map-legend-card">
                <h4 style="font-size: 1.05rem; margin: 0 0 0.85rem 0; color: var(--color-deep-forest);">
                    Legenda Simbol & Warna Titik Panas
                </h4>
                <div style="display: flex; flex-direction: column; gap: 0.65rem; font-size: 0.86rem;">
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                        <span class="legend-color-dot" style="background-color: #B83B26; box-shadow: 0 0 6px rgba(184, 59, 38, 0.5);"></span>
                        <span><strong>Tingkat Kepercayaan Tinggi (High):</strong> Probabilitas kuat adanya anomali suhu ekstrem di permukaan.</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                        <span class="legend-color-dot" style="background-color: #D35400;"></span>
                        <span><strong>Tingkat Kepercayaan Sedang (Nominal):</strong> Titik panas terdeteksi dengan parameter standar sensor satelit.</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                        <span class="legend-color-dot" style="background-color: #D4AC0D;"></span>
                        <span><strong>Tingkat Kepercayaan Rendah (Low):</strong> Anomali termal lemah atau potensi gangguan pantulan awan/air.</span>
                    </div>
                </div>
            </div>

            <!-- Parameters Card -->
            <div class="map-legend-card">
                <h4 style="font-size: 1.05rem; margin: 0 0 0.85rem 0; color: var(--color-deep-forest);">
                    Parameter Observasi Satelit
                </h4>
                <p style="font-size: 0.85rem; color: #525C54; line-height: 1.6; margin: 0 0 0.65rem 0;">
                    Ketika marker dipilih pada peta, sistem akan menampilkan rincian parameter berikut secara transparan sesuai data sumber:
                </p>
                <ul style="padding-left: 1.25rem; margin: 0; font-size: 0.84rem; color: #525C54; line-height: 1.5;">
                    <li><strong>Koordinat Presisi:</strong> Latitude dan Longitude deteksi.</li>
                    <li><strong>Waktu Observasi:</strong> Jam lintasan satelit saat sensor mendeteksi panas.</li>
                    <li><strong>Instrumen & Sensor:</strong> Sensor VIIRS (SNPP / NOAA-20) atau MODIS (Terra / Aqua).</li>
                    <li><strong>FRP (Fire Radiative Power):</strong> Estimasi energi radiasi termal (MW) bila disediakan.</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- =========================================================================
     3. INTEGRASI DATA EKSTERNAL: NASA FIRMS & BMKG
     Transparent reporting on external provider configuration and timestamps
     ========================================================================= -->
<section style="background-color: #ECE7DA; padding: 4.5rem 0 5rem; border-top: 1px solid var(--color-border-subtle); border-bottom: 1px solid var(--color-border-subtle);">
    <div class="container-editorial">
        <div style="margin-bottom: 2.5rem;">
            <span class="eyebrow">Transparansi Sumber Data</span>
            <h2 style="font-size: clamp(1.8rem, 3.2vw, 2.4rem); margin-bottom: 0.5rem;">Status Penyedia Data Eksternal</h2>
            <p class="editorial-lead" style="max-width: 720px; margin-bottom: 0;">
                Borneo Siaga tidak membuat data tiruan. Seluruh titik panas dan prakiraan cuaca dievaluasi dari API penyedia resmi dengan pencatatan waktu yang transparan.
            </p>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 2rem;">
            <!-- Column 1: NASA FIRMS -->
            <div style="background: #FFFFFF; border: 1px solid var(--color-border-subtle); border-radius: 12px; padding: 2rem;">
                <div style="display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 1rem;">
                    <div>
                        <span style="font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.08em; color: var(--color-forest-moss); font-weight: 700; display: block;">Deteksi Titik Panas</span>
                        <h4 style="margin: 0.2rem 0 0; font-size: 1.2rem; color: var(--color-deep-forest);">NASA FIRMS</h4>
                    </div>
                    @if($firms['status'] === 'success')
                        <span class="badge badge-forest">Aktif</span>
                    @elseif($firms['status'] === 'stale_warning')
                        <span class="badge badge-sand" style="color: #935B0D; border-color: #D4AC0D;">Data Tersimpan (Pembaruan Terkendala)</span>
                    @elseif($firms['status'] === 'unconfigured')
                        <span class="badge badge-sand">Kunci Belum Diisi</span>
                    @else
                        <span class="badge badge-alert">Koneksi Terkendala</span>
                    @endif
                </div>

                <p style="font-size: 0.88rem; color: #4b544d; line-height: 1.6; margin-bottom: 1.25rem;">
                    {{ $firms['message'] }}
                </p>

                @if($firms['status'] === 'unconfigured')
                    <div style="background: var(--color-warm-paper); border: 1px dashed rgba(82, 107, 77, 0.4); border-radius: 8px; padding: 0.85rem 1rem; font-size: 0.82rem; color: #4a544c; margin-bottom: 1.25rem;">
                        <strong>Konfigurasi yang Diperlukan:</strong><br>
                        Dapatkan MAP_KEY gratis di <a href="https://firms.modaps.eosdis.nasa.gov/api/map_key" target="_blank" rel="noopener" style="text-decoration: underline; color: var(--color-deep-forest);">firms.modaps.eosdis.nasa.gov</a> lalu masukkan pada file <code>.env</code>:
                        <pre style="margin: 0.4rem 0 0; background: #e5dfd2; padding: 0.35rem 0.6rem; border-radius: 4px; font-family: monospace; font-size: 0.78rem;">NASA_FIRMS_MAP_KEY=masukkan_map_key_anda</pre>
                    </div>
                @endif

                <div style="border-top: 1px solid var(--color-border-subtle); padding-top: 1rem; font-size: 0.8rem; color: #69766c; display: flex; flex-direction: column; gap: 0.35rem;">
                    <div><strong>Waktu Observasi Satelit:</strong> {{ $firms['observation_time'] ?? 'Belum ada data' }}</div>
                    <div><strong>Waktu Sinkronisasi Sistem:</strong> {{ $firms['last_fetched_at'] ? date('d M Y H:i T', strtotime($firms['last_fetched_at'])) : 'Belum dilakukan' }}</div>
                    <div><strong>Sumber Resmi:</strong> <a href="{{ $firms['source_url'] }}" target="_blank" rel="noopener" style="text-decoration: underline; color: var(--color-deep-forest);">NASA FIRMS Official Portal</a></div>
                </div>
            </div>

            <!-- Column 2: BMKG Weather -->
            <div style="background: #FFFFFF; border: 1px solid var(--color-border-subtle); border-radius: 12px; padding: 2rem;">
                <div style="display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 1rem;">
                    <div>
                        <span style="font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.08em; color: var(--color-forest-moss); font-weight: 700; display: block;">Informasi Pendukung</span>
                        <h4 style="margin: 0.2rem 0 0; font-size: 1.2rem; color: var(--color-deep-forest);">Prakiraan Cuaca BMKG</h4>
                    </div>
                    @if($weather['status'] === 'success')
                        <span class="badge badge-forest">Aktif</span>
                    @else
                        <span class="badge badge-sand">Informasi Pendukung</span>
                    @endif
                </div>

                <p style="font-size: 0.88rem; color: #4b544d; line-height: 1.6; margin-bottom: 1.25rem;">
                    {{ $weather['message'] }}
                </p>

                @if(!empty($weather['hubs']))
                    <div style="margin-bottom: 1.25rem;">
                        <span style="font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.08em; color: var(--color-forest-moss); font-weight: 700; display: block; margin-bottom: 0.5rem;">
                            Parameter Atmosfer Regional (5 Ibu Kota Provinsi):
                        </span>
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(130px, 1fr)); gap: 0.5rem;">
                            @foreach($weather['hubs'] as $hub)
                                <div style="background: var(--color-warm-paper); border: 1px solid var(--color-border-subtle); border-radius: 6px; padding: 0.55rem 0.65rem; font-size: 0.78rem;">
                                    <div style="font-weight: 700; color: var(--color-deep-forest);">{{ $hub['city'] }}</div>
                                    <div style="font-size: 0.7rem; color: #5B665D;">{{ $hub['province'] }}</div>
                                    <div style="font-size: 1.1rem; font-weight: 700; color: var(--color-deep-forest); margin: 0.15rem 0;">
                                        {{ $hub['temperature'] }}&deg;C
                                    </div>
                                    <div style="font-size: 0.72rem; color: #4B544D;">
                                        {{ $hub['condition'] }} &bull; RH {{ $hub['humidity'] }}%
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div style="background: var(--color-warm-paper); border: 1px solid var(--color-border-subtle); border-radius: 8px; padding: 0.85rem 1rem; font-size: 0.82rem; color: #4a544c; margin-bottom: 1.25rem;">
                    <strong>Penegasan Ilmiah:</strong><br>
                    {{ $weather['disclaimer'] ?? 'Prakiraan cuaca disajikan murni sebagai konteks atmosfer pendukung.' }}
                </div>

                <div style="border-top: 1px solid var(--color-border-subtle); padding-top: 1rem; font-size: 0.8rem; color: #69766c; display: flex; flex-direction: column; gap: 0.35rem;">
                    <div><strong>Waktu Pengecekan Sistem:</strong> {{ date('d M Y H:i T', strtotime($weather['last_fetched_at'])) }}</div>
                    <div><strong>Penyedia Resmi:</strong> <a href="{{ $weather['source_url'] }}" target="_blank" rel="noopener" style="text-decoration: underline; color: var(--color-deep-forest);">BMKG (Badan Meteorologi, Klimatologi, dan Geofisika)</a></div>
                </div>
            </div>
        </div>

        <!-- Transparency Scientific Caution Banner -->
        <div style="margin-top: 2.5rem; background-color: var(--color-warm-paper); border-left: 4px solid var(--color-forest-moss); border-radius: 0 8px 8px 0; padding: 1.25rem 1.5rem; box-shadow: var(--shadow-editorial);">
            <div style="display: flex; gap: 0.85rem; align-items: flex-start;">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink: 0; color: var(--color-forest-moss); margin-top: 2px;"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                <div style="font-size: 0.88rem; color: #454E46; line-height: 1.65;">
                    <strong style="color: var(--color-deep-forest);">Pemberitahuan Transparansi & Keterbatasan Data Satelit:</strong><br>
                    Titik panas (*hotspot*) yang terdeteksi merupakan anomali suhu di atas ambang batas normal pada piksel citra satelit dan merupakan indikasi awal yang membutuhkan konfirmasi verifikasi langsung di lapangan. <em>Ketiadaan titik panas pada peta bukan jaminan bahwa wilayah tersebut sepenuhnya aman</em>, karena sensor termal satelit dapat terhalang oleh tutupan awan tebal, kanopi hutan rapat, atau kebakaran gambut yang membara di bawah permukaan tanah (*subsurface smoldering peat fire*).
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<!-- Leaflet JS 1.9.4 -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<style>
@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // 1. Data Hotspots yang dikirim dari Backend
    let rawHotspots = @json($firms['hotspots'] ?? []);
    let firmsStatus = @json($firms['status'] ?? 'unconfigured');
    let firmsMessage = @json($firms['message'] ?? '');
    let currentFilter = 'all';

    // 2. Inisialisasi Peta Leaflet berfokus pada Pulau Kalimantan
    const map = L.map('mapCanvas', {
        center: [-0.5, 114.0],
        zoom: 6,
        minZoom: 5,
        maxZoom: 14,
        zoomControl: true,
        scrollWheelZoom: true
    });

    // 3. Tile Layer OpenStreetMap dengan atribusi sah
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 18,
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright" target="_blank" rel="noopener">OpenStreetMap</a> contributors'
    }).addTo(map);

    // 4. Koordinat Batas Wilayah 5 Provinsi Kalimantan
    const regionBounds = {
        all: {
            center: [-0.5, 114.0],
            zoom: 6,
            name: 'Seluruh Kalimantan'
        },
        kalbar: {
            bounds: [[-3.1, 108.5], [2.1, 111.5]],
            name: 'Kalimantan Barat'
        },
        kalteng: {
            bounds: [[-3.5, 111.0], [0.8, 115.8]],
            name: 'Kalimantan Tengah'
        },
        kalsel: {
            bounds: [[-4.2, 114.3], [-1.2, 116.6]],
            name: 'Kalimantan Selatan'
        },
        kaltim: {
            bounds: [[-2.5, 115.5], [2.9, 119.0]],
            name: 'Kalimantan Timur'
        },
        kaltara: {
            bounds: [[1.0, 114.5], [4.4, 118.0]],
            name: 'Kalimantan Utara'
        }
    };

    // Layer Group untuk menyimpan marker aktif
    const markersLayer = L.layerGroup().addTo(map);

    // Helper overlay status pesan peta
    const mapStatusOverlay = document.getElementById('mapStatusOverlay');
    const mapOverlayContent = document.getElementById('mapOverlayContent');

    function showMapOverlay(type, message) {
        if (!mapStatusOverlay || !mapOverlayContent) return;

        let icon = '';
        let textColor = '#292D29';

        if (type === 'loading') {
            icon = `<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="animation: spin 1s linear infinite; color: var(--color-deep-forest); flex-shrink: 0;"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg>`;
        } else if (type === 'empty') {
            icon = `<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: var(--color-forest-moss); flex-shrink: 0;"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/></svg>`;
        } else if (type === 'error') {
            icon = `<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: #B83B26; flex-shrink: 0;"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>`;
            textColor = '#B83B26';
        }

        mapOverlayContent.innerHTML = `${icon}<span style="color: ${textColor}; line-height: 1.4;">${message}</span>`;
        mapStatusOverlay.style.display = 'block';
    }

    function hideMapOverlay() {
        if (mapStatusOverlay) {
            mapStatusOverlay.style.display = 'none';
        }
    }

    // Helper sanitasi teks untuk keamanan dari XSS
    function escapeHtml(str) {
        if (str === null || str === undefined) return '';
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    // Helper warna marker berdasarkan confidence
    function getConfidenceColor(conf) {
        const c = String(conf).toLowerCase();
        if (c === 'h' || c === 'high' || parseInt(c) >= 80) {
            return '#B83B26'; // High / Tinggi
        } else if (c === 'n' || c === 'nominal' || c === 'medium' || (parseInt(c) >= 50 && parseInt(c) < 80)) {
            return '#D35400'; // Nominal / Sedang
        } else {
            return '#D4AC0D'; // Low / Rendah
        }
    }

    // Render Marker pada Peta (HANYA bila ada data aktual dari API)
    function renderHotspots(filterRegion = 'all') {
        currentFilter = filterRegion;
        markersLayer.clearLayers();

        let filteredCount = 0;
        const regionName = regionBounds[filterRegion] ? regionBounds[filterRegion].name : 'Wilayah Terpilih';

        // Validasi ketersediaan data
        if (!Array.isArray(rawHotspots) || rawHotspots.length === 0) {
            if (firmsStatus === 'unconfigured') {
                showMapOverlay('error', 'Kunci API NASA FIRMS (MAP_KEY) belum dikonfigurasi pada file .env.');
            } else if (firmsStatus === 'error') {
                showMapOverlay('error', firmsMessage || 'Gagal mengambil data dari NASA FIRMS.');
            } else {
                showMapOverlay('empty', 'Tidak ada titik panas satelit terdeteksi pada seluruh wilayah Kalimantan.');
            }
            updateSummary(0, regionName);
            return;
        }

        rawHotspots.forEach(function (pt) {
            // Validasi ketat koordinat numerik
            const lat = Number(pt.latitude);
            const lon = Number(pt.longitude);
            if (isNaN(lat) || isNaN(lon)) return;

            // Filter provinsi jika bukan 'all'
            if (filterRegion !== 'all') {
                const targetName = regionBounds[filterRegion] ? regionBounds[filterRegion].name : '';
                if (targetName && pt.province !== targetName) {
                    return;
                }
            }

            filteredCount++;
            const color = getConfidenceColor(pt.confidence);

            const circleMarker = L.circleMarker([lat, lon], {
                radius: 7,
                fillColor: color,
                color: '#FFFFFF',
                weight: 1.5,
                opacity: 1,
                fillOpacity: 0.85
            });

            // Format Popup Detail yang informatif dan aman (XSS-safe)
            const popupTime = pt.acq_formatted 
                ? escapeHtml(pt.acq_formatted) 
                : (pt.acq_date ? escapeHtml(pt.acq_date) + (pt.acq_time ? ' ' + escapeHtml(pt.acq_time) + ' UTC' : '') : 'Informasi waktu tidak tersedia');

            const popupContent = `
                <div style="padding: 1rem 1.15rem; min-width: 230px;">
                    <div style="font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.08em; color: var(--color-forest-moss); font-weight: 700; margin-bottom: 0.25rem;">
                        ${escapeHtml(pt.province || 'Pulau Kalimantan')}
                    </div>
                    <div style="font-family: var(--font-heading); font-size: 1.15rem; font-weight: 700; color: var(--color-deep-forest); margin-bottom: 0.65rem;">
                        Titik Panas Satelit
                    </div>
                    <div style="display: flex; flex-direction: column; gap: 0.35rem; font-size: 0.82rem; color: #4A544C;">
                        <div><strong>Koordinat:</strong> ${lat.toFixed(4)}, ${lon.toFixed(4)}</div>
                        <div><strong>Waktu Observasi:</strong> ${popupTime}</div>
                        <div><strong>Instrumen / Satelit:</strong> ${escapeHtml(pt.instrument || 'Sensor VIIRS')} (${escapeHtml(pt.satellite || 'Satelit')})</div>
                        <div><strong>Kepercayaan:</strong> <span style="font-weight: 600; color: ${color};">${escapeHtml(pt.confidence || 'Nominal')}</span></div>
                        ${(pt.frp !== null && pt.frp !== undefined) ? `<div><strong>FRP (Energi Radiasi):</strong> ${escapeHtml(pt.frp)} MW</div>` : ''}
                        ${(pt.brightness !== null && pt.brightness !== undefined) ? `<div><strong>Suhu Kecerahan:</strong> ${escapeHtml(pt.brightness)} K</div>` : ''}
                        ${pt.daynight ? `<div><strong>Lintasan:</strong> ${escapeHtml(pt.daynight)}</div>` : ''}
                    </div>
                    <div style="margin-top: 0.65rem; padding-top: 0.5rem; border-top: 1px solid rgba(0,0,0,0.06); font-size: 0.72rem; color: #7A867C;">
                        * Estimasi wilayah berbasis koordinat regional
                    </div>
                </div>
            `;

            circleMarker.bindPopup(popupContent);
            markersLayer.addLayer(circleMarker);
        });

        if (filteredCount === 0) {
            showMapOverlay('empty', `Tidak ada anomali titik panas satelit terdeteksi di <strong>${regionName}</strong>.`);
        } else {
            hideMapOverlay();
        }

        updateSummary(filteredCount, regionName);
    }

    function updateSummary(count, regionName) {
        const summaryTextEl = document.getElementById('hotspotSummaryText');
        if (!summaryTextEl) return;

        if (count > 0) {
            if (firmsStatus === 'stale_warning') {
                summaryTextEl.innerHTML = `
                    <span style="width: 9px; height: 9px; border-radius: 50%; background-color: #D4AC0D; display: inline-block;"></span>
                    <span>Terdeteksi <strong>${count} titik panas satelit</strong> pada ${regionName} (Data Cache Tersimpan - Pembaruan API Terkendala).</span>
                `;
            } else {
                summaryTextEl.innerHTML = `
                    <span style="width: 9px; height: 9px; border-radius: 50%; background-color: #B83B26; display: inline-block;"></span>
                    <span>Terdeteksi <strong>${count} titik panas satelit aktif</strong> pada ${regionName}.</span>
                `;
            }
        } else if (firmsStatus === 'unconfigured') {
            summaryTextEl.innerHTML = `
                <span style="width: 9px; height: 9px; border-radius: 50%; background-color: #D4AC0D; display: inline-block;"></span>
                <span>Status data: <strong>Integrasi NASA FIRMS Menunggu Kunci API (NASA_FIRMS_MAP_KEY)</strong>.</span>
            `;
        } else if (firmsStatus === 'error') {
            summaryTextEl.innerHTML = `
                <span style="width: 9px; height: 9px; border-radius: 50%; background-color: #B83B26; display: inline-block;"></span>
                <span>Status data: <strong>${escapeHtml(firmsMessage) || 'Kendala koneksi NASA FIRMS'}</strong>.</span>
            `;
        } else {
            summaryTextEl.innerHTML = `
                <span style="width: 9px; height: 9px; border-radius: 50%; background-color: #526B4D; display: inline-block;"></span>
                <span>Tidak ada anomali titik panas terdeteksi pada ${regionName}.</span>
            `;
        }
    }

    // Inisialisasi render pertama
    renderHotspots('all');

    // 5. Interaksi Tombol Filter Wilayah
    const filterButtons = document.querySelectorAll('.map-filter-btn');
    filterButtons.forEach(function (btn) {
        btn.addEventListener('click', function () {
            filterButtons.forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            const regionKey = this.getAttribute('data-region');
            const regionInfo = regionBounds[regionKey];

            if (regionInfo) {
                if (regionInfo.bounds) {
                    map.flyToBounds(regionInfo.bounds, { padding: [30, 30], duration: 1.2 });
                } else if (regionInfo.center) {
                    map.flyTo(regionInfo.center, regionInfo.zoom, { duration: 1.2 });
                }
            }

            renderHotspots(regionKey);
        });
    });

    // 6. Reset Posisi Peta
    const btnResetView = document.getElementById('btnResetView');
    if (btnResetView) {
        btnResetView.addEventListener('click', function () {
            const allBtn = document.querySelector('.map-filter-btn[data-region="all"]');
            if (allBtn) {
                allBtn.click();
            } else {
                map.flyTo([-0.5, 114.0], 6, { duration: 1.2 });
                renderHotspots('all');
            }
        });
    }

    // 7. Tombol Sinkronisasi / Pembaruan Data API Asinkron
    const btnRefreshData = document.getElementById('btnRefreshData');
    if (btnRefreshData) {
        btnRefreshData.addEventListener('click', function () {
            const iconSpin = document.getElementById('iconRefreshSpin');
            const textRefresh = document.getElementById('textBtnRefresh');

            btnRefreshData.disabled = true;
            if (iconSpin) iconSpin.style.animation = 'spin 1s linear infinite';
            if (textRefresh) textRefresh.textContent = 'Menyinkronkan...';
            showMapOverlay('loading', 'Menghubungkan ke layanan satelit NASA FIRMS...');

            fetch('{{ route('pantauan.karhutla.api') }}')
                .then(res => {
                    if (!res.ok) throw new Error('HTTP ' + res.status);
                    return res.json();
                })
                .then(data => {
                    if (data && data.firms) {
                        rawHotspots = data.firms.hotspots || [];
                        firmsStatus = data.firms.status || 'success';
                        firmsMessage = data.firms.message || '';
                        renderHotspots(currentFilter);
                    }
                })
                .catch(err => {
                    showMapOverlay('error', 'Gagal menyinkronkan data: ' + err.message);
                })
                .finally(() => {
                    btnRefreshData.disabled = false;
                    if (iconSpin) iconSpin.style.animation = '';
                    if (textRefresh) textRefresh.textContent = 'Sinkronkan Satelit';
                });
        });
    }
});
</script>
@endpush
