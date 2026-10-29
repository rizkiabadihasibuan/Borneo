@extends('layouts.app')

@section('title', 'Pusat Edukasi & Panduan Praktis Karhutla | BORNEO SIAGA')
@section('meta_description', 'Panduan aksi praktis menghadapi kabut asap, pembuatan ruang aman mandiri (clean air room), teknik pengolahan lahan tanpa bakar (PLTB), dan warta karhutla Kalimantan.')

@section('content')
<!-- =========================================================================
     1. PAGE HEADER: EDUKASI & AKSI MITIGASI
     ========================================================================= -->
<section class="page-header-editorial">
    <div class="container-editorial">
        <div style="display: inline-flex; align-items: center; gap: 0.6rem; margin-bottom: 0.65rem;">
            <span style="display: inline-block; width: 8px; height: 8px; border-radius: 50%; background-color: #38A169;"></span>
            <span class="eyebrow" style="margin-bottom: 0;">Hub Aksi & Edukasi Praktis</span>
        </div>
        <h1>Pusat Edukasi & Panduan Mitigasi</h1>
        <p class="editorial-lead" style="max-width: 780px;">
            Kumpulan panduan teknis aplikatif yang dapat langsung dipraktikkan masyarakat di desa rawan gambut, keluarga yang terpapar kabut asap di perkotaan, serta kelompok tani di bentang alam Kalimantan.
        </p>
    </div>
</section>

<!-- =========================================================================
     2. 4 PILAR PEMBELAJARAN DASAR
     ========================================================================= -->
<section style="padding: 3rem 0 2rem; background-color: var(--color-warm-paper);">
    <div class="container-editorial">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
            <div class="card-editorial" style="background: #FFFFFF; border: 1px solid var(--color-border-subtle); border-radius: 12px; padding: 1.6rem; box-shadow: 0 4px 16px rgba(0,0,0,0.03);">
                <span class="badge badge-forest" style="margin-bottom: 0.75rem;">Ekologi Gambut</span>
                <h3 style="font-size: 1.15rem; margin-bottom: 0.5rem; color: var(--color-deep-forest);">Karakteristik Lahan Gambut</h3>
                <p style="font-size: 0.86rem; color: #586058; line-height: 1.6; margin: 0;">
                    Api bawah tanah gambut menyimpan panas hingga kedalaman 3–5 meter. Pemadaman membutuhkan metode injeksi air bertekanan (peat injection) serta pembasahan sekat kanal.
                </p>
            </div>

            <div class="card-editorial" style="background: #FFFFFF; border: 1px solid var(--color-border-subtle); border-radius: 12px; padding: 1.6rem; box-shadow: 0 4px 16px rgba(0,0,0,0.03);">
                <span class="badge badge-sand" style="margin-bottom: 0.75rem;">Kesehatan & Udara</span>
                <h3 style="font-size: 1.15rem; margin-bottom: 0.5rem; color: var(--color-deep-forest);">Bahaya Partikulat PM2.5</h3>
                <p style="font-size: 0.86rem; color: #586058; line-height: 1.6; margin: 0;">
                    Partikel mikroskopis dari asap gambut dapat menembus alveolus paru dan aliran darah. Gunakan masker respirator bersertifikasi N95/KN95, bukan masker kain biasa.
                </p>
            </div>

            <div class="card-editorial" style="background: #FFFFFF; border: 1px solid var(--color-border-subtle); border-radius: 12px; padding: 1.6rem; box-shadow: 0 4px 16px rgba(0,0,0,0.03);">
                <span class="badge badge-forest" style="margin-bottom: 0.75rem;">Hukum & Sanksi</span>
                <h3 style="font-size: 1.15rem; margin-bottom: 0.5rem; color: var(--color-deep-forest);">Regulasi & Pidana Membakar</h3>
                <p style="font-size: 0.86rem; color: #586058; line-height: 1.6; margin: 0;">
                    Berdasarkan UU No. 32/2009, pelaku pembakaran lahan sengaja diancam pidana penjara maksimal 10 tahun dan denda hingga Rp10 Miliar.
                </p>
            </div>

            <div class="card-editorial" style="background: #FFFFFF; border: 1px solid var(--color-border-subtle); border-radius: 12px; padding: 1.6rem; box-shadow: 0 4px 16px rgba(0,0,0,0.03);">
                <span class="badge badge-sand" style="margin-bottom: 0.75rem;">Aksi Komunitas</span>
                <h3 style="font-size: 1.15rem; margin-bottom: 0.5rem; color: var(--color-deep-forest);">Masyarakat Peduli Api (MPA)</h3>
                <p style="font-size: 0.86rem; color: #586058; line-height: 1.6; margin: 0;">
                    Kelompok relawan garda terdepan desa yang bertugas memantau tinggi muka air tanah gambut (TMAT), memelihara sekat kanal, serta merespons titik api dini.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- =========================================================================
     3. HUB PANDUAN PRAKTIS INTERAKTIF (FITUR UTAMA)
     Tab Interaktif: 
     1. Pertolongan Pertama Asap
     2. Ruang Aman Asap (Clean Air Room)
     3. Metode Pengolahan Lahan Tanpa Bakar (PLTB)
     ========================================================================= -->
<section style="padding: 4rem 0 5rem; background-color: #FFFFFF; border-top: 1px solid var(--color-border-subtle); border-bottom: 1px solid var(--color-border-subtle);">
    <div class="container-editorial">
        <div style="text-align: center; max-width: 760px; margin: 0 auto 3rem;">
            <div style="display: inline-flex; align-items: center; gap: 0.5rem; margin-bottom: 0.65rem; background: rgba(38, 59, 48, 0.08); padding: 0.35rem 0.95rem; border-radius: 999px;">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" style="color: var(--color-deep-forest);"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                <span style="font-size: 0.76rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.12em; color: var(--color-deep-forest);">
                    PANDUAN LANGKAH DEMI LANGKAH
                </span>
            </div>
            <h2 style="font-size: clamp(1.9rem, 3.4vw, 2.6rem); margin-bottom: 0.75rem; color: var(--color-deep-forest);">
                Hub Panduan Aksi Lapangan & Rumah Tangga
            </h2>
            <p style="font-size: 1.02rem; color: #586058; line-height: 1.65; margin: 0;">
                Pilih topik di bawah ini untuk melihat panduan langkah teknis yang praktis dan dapat segera diterapkan.
            </p>
        </div>

        <!-- Interactive Guide Tabs Navigation -->
        <div style="display: flex; justify-content: center; margin-bottom: 2.75rem;">
            <div style="background: #F4F1EA; padding: 0.45rem; border-radius: 12px; display: inline-flex; flex-wrap: wrap; gap: 0.35rem; border: 1px solid var(--color-border-subtle); max-width: 100%;">
                <button type="button" class="guide-tab-btn active" data-target="guide-ispa" onclick="switchGuideTab('guide-ispa', this)">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    <span>Pertolongan Pertama Asap & Masker</span>
                </button>
                <button type="button" class="guide-tab-btn" data-target="guide-safe-room" onclick="switchGuideTab('guide-safe-room', this)">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                    <span>Ruang Aman Asap Mandiri (Safe Room)</span>
                </button>
                <button type="button" class="guide-tab-btn" data-target="guide-pltb" onclick="switchGuideTab('guide-pltb', this)">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/><path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/></svg>
                    <span>Metode Petani: Lahan Tanpa Bakar (PLTB)</span>
                </button>
            </div>
        </div>

        <!-- =============================================================
             PANDUAN 1: PERTOLONGAN PERTAMA KABUT ASAP & ISPA
             ============================================================= -->
        <div id="guide-ispa" class="guide-content-panel active">
            <div style="background: #FAF8F5; border: 1px solid var(--color-border-subtle); border-radius: 16px; padding: 2.5rem 2rem; margin-bottom: 2rem;">
                <div style="max-width: 820px; margin: 0 auto;">
                    <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.5rem;">
                        <span class="badge badge-sand" style="font-size: 0.8rem; padding: 0.3rem 0.85rem;">Protokol Kesehatan Darurat</span>
                        <span style="font-size: 0.84rem; color: #6C7A70;">Rujukan Standar Kemenkes RI & WHO</span>
                    </div>

                    <h3 style="font-size: 1.7rem; color: var(--color-deep-forest); margin-bottom: 1rem; line-height: 1.3;">
                        Tindakan Cepat Saat Terpapar Kabut Asap Pekat (PM2.5)
                    </h3>
                    <p style="font-size: 1rem; color: #4B554D; line-height: 1.7; margin-bottom: 2rem;">
                        Asap kebakaran gambut mengandung gas beracun (CO, formaldehida, acrolein) serta partikulat mikron PM2.5. Ikuti 4 tindakan kunci berikut untuk melindungi pernapasan:
                    </p>

                    <!-- Step-by-Step Grid -->
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem;">
                        <!-- Step 1 -->
                        <div style="background: #FFFFFF; border: 1px solid var(--color-border-subtle); border-radius: 12px; padding: 1.5rem;">
                            <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.85rem;">
                                <div style="width: 32px; height: 32px; border-radius: 50%; background: var(--color-deep-forest); color: var(--color-earth-sand); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.95rem; flex-shrink: 0;">1</div>
                                <h4 style="font-size: 1.1rem; margin: 0; color: var(--color-deep-forest);">Gunakan Masker N95 / KN95</h4>
                            </div>
                            <p style="font-size: 0.88rem; color: #536056; line-height: 1.6; margin: 0;">
                                Masker kain atau masker bedah biasa <strong>tidak mampu</strong> menyaring partikel mikron PM2.5. Pastikan masker respirator terpasang rapat tanpa celah di hidung dan pipi.
                            </p>
                        </div>

                        <!-- Step 2 -->
                        <div style="background: #FFFFFF; border: 1px solid var(--color-border-subtle); border-radius: 12px; padding: 1.5rem;">
                            <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.85rem;">
                                <div style="width: 32px; height: 32px; border-radius: 50%; background: var(--color-deep-forest); color: var(--color-earth-sand); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.95rem; flex-shrink: 0;">2</div>
                                <h4 style="font-size: 1.1rem; margin: 0; color: var(--color-deep-forest);">Bilas Mata & Saluran Hidung</h4>
                            </div>
                            <p style="font-size: 0.88rem; color: #536056; line-height: 1.6; margin: 0;">
                                Jika mata perih atau tenggorokan gatal, bilas dengan cairan saline fisiologis (NaCl 0.9%) atau tetes mata steril. Hindari mengucek mata secara langsung agar kornea tidak iritasi.
                            </p>
                        </div>

                        <!-- Step 3 -->
                        <div style="background: #FFFFFF; border: 1px solid var(--color-border-subtle); border-radius: 12px; padding: 1.5rem;">
                            <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.85rem;">
                                <div style="width: 32px; height: 32px; border-radius: 50%; background: var(--color-deep-forest); color: var(--color-earth-sand); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.95rem; flex-shrink: 0;">3</div>
                                <h4 style="font-size: 1.1rem; margin: 0; color: var(--color-deep-forest);">Hidrasi Minimal 2.5 Liter Air</h4>
                            </div>
                            <p style="font-size: 0.88rem; color: #536056; line-height: 1.6; margin: 0;">
                                Minum air putih hangat dalam jumlah cukup menjaga kelembapan silia di saluran napas atas agar mampu menangkap partikulat debu dan racun sebelum turun ke paru-paru.
                            </p>
                        </div>

                        <!-- Step 4 -->
                        <div style="background: #FFFFFF; border: 1px solid var(--color-border-subtle); border-radius: 12px; padding: 1.5rem;">
                            <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.85rem;">
                                <div style="width: 32px; height: 32px; border-radius: 50%; background: var(--color-alert-amber); color: #FFFFFF; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.95rem; flex-shrink: 0;">!</div>
                                <h4 style="font-size: 1.1rem; margin: 0; color: var(--color-deep-forest);">Evakuasi Segera Jika Sesak</h4>
                            </div>
                            <p style="font-size: 0.88rem; color: #536056; line-height: 1.6; margin: 0;">
                                Apabila balita atau lansia mengalami napas berbunyi (mengi), bibir membiru, atau sesak berkepanjangan, segera bawa ke Puskesmas/Posko Oksigen terdekat.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- =============================================================
             PANDUAN 2: MEMBUAT RUANG AMAN ASAP MANDIRI (CLEAN AIR ROOM)
             ============================================================= -->
        <div id="guide-safe-room" class="guide-content-panel" style="display: none;">
            <div style="background: #FAF8F5; border: 1px solid var(--color-border-subtle); border-radius: 16px; padding: 2.5rem 2rem; margin-bottom: 2rem;">
                <div style="max-width: 820px; margin: 0 auto;">
                    <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.5rem;">
                        <span class="badge badge-forest" style="font-size: 0.8rem; padding: 0.3rem 0.85rem;">Mitigasi Rumah Tangga</span>
                        <span style="font-size: 0.84rem; color: #6C7A70;">Metode DIY Ruang Udara Bersih</span>
                    </div>

                    <h3 style="font-size: 1.7rem; color: var(--color-deep-forest); margin-bottom: 1rem; line-height: 1.3;">
                        Cara Membuat "Ruang Aman Asap" (Clean Air Room) di Rumah
                    </h3>
                    <p style="font-size: 1rem; color: #4B554D; line-height: 1.7; margin-bottom: 2rem;">
                        Ketika asap mengepung lingkungan, satu ruangan di dalam rumah harus dipersiapkan sebagai zona isolasi udara bersih untuk tidur dan istirahat anggota keluarga, terutama balita dan lansia.
                    </p>

                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem;">
                        <!-- Step 1 -->
                        <div style="background: #FFFFFF; border: 1px solid var(--color-border-subtle); border-radius: 12px; padding: 1.5rem;">
                            <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.85rem;">
                                <div style="width: 32px; height: 32px; border-radius: 50%; background: var(--color-deep-forest); color: var(--color-earth-sand); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.95rem; flex-shrink: 0;">1</div>
                                <h4 style="font-size: 1.1rem; margin: 0; color: var(--color-deep-forest);">Pilih Kamar Paling Terlindung</h4>
                            </div>
                            <p style="font-size: 0.88rem; color: #536056; line-height: 1.6; margin: 0;">
                                Pilih satu kamar tidur dengan ventilasi atau jendela paling sedikit. Hindari ruangan dapur karena aktivitas memasak dapat menambah partikulat asap di dalam ruangan.
                            </p>
                        </div>

                        <!-- Step 2 -->
                        <div style="background: #FFFFFF; border: 1px solid var(--color-border-subtle); border-radius: 12px; padding: 1.5rem;">
                            <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.85rem;">
                                <div style="width: 32px; height: 32px; border-radius: 50%; background: var(--color-deep-forest); color: var(--color-earth-sand); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.95rem; flex-shrink: 0;">2</div>
                                <h4 style="font-size: 1.1rem; margin: 0; color: var(--color-deep-forest);">Tutup Celah Pintu dengan Kain Basah</h4>
                            </div>
                            <p style="font-size: 0.88rem; color: #536056; line-height: 1.6; margin: 0;">
                                Gulung handuk atau kain tebal, basahi dengan air bersih, lalu letakkan di bawah celah pintu dan kusen jendela. Air pada kain membantu mengikat partikel debu agar tidak lolos masuk.
                            </p>
                        </div>

                        <!-- Step 3 -->
                        <div style="background: #FFFFFF; border: 1px solid var(--color-border-subtle); border-radius: 12px; padding: 1.5rem;">
                            <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.85rem;">
                                <div style="width: 32px; height: 32px; border-radius: 50%; background: var(--color-deep-forest); color: var(--color-earth-sand); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.95rem; flex-shrink: 0;">3</div>
                                <h4 style="font-size: 1.1rem; margin: 0; color: var(--color-deep-forest);">Air Purifier Mandiri DIY</h4>
                            </div>
                            <p style="font-size: 0.88rem; color: #536056; line-height: 1.6; margin: 0;">
                                Pasang filter HEPA atau filter AC pada bagian belakang kipas angin kotak (box fan) menggunakan lakban rapat. Udara yang terhisap melewati filter akan terbebas dari partikel PM2.5.
                            </p>
                        </div>

                        <!-- Step 4 -->
                        <div style="background: #FFFFFF; border: 1px solid var(--color-border-subtle); border-radius: 12px; padding: 1.5rem;">
                            <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.85rem;">
                                <div style="width: 32px; height: 32px; border-radius: 50%; background: var(--color-deep-forest); color: var(--color-earth-sand); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.95rem; flex-shrink: 0;">4</div>
                                <h4 style="font-size: 1.1rem; margin: 0; color: var(--color-deep-forest);">Pantangan di Dalam Ruang Aman</h4>
                            </div>
                            <p style="font-size: 0.88rem; color: #536056; line-height: 1.6; margin: 0;">
                                Dilarang keras merokok, menyalakan lilin, obat nyamuk bakar, atau dupa di dalam ruang aman asap. Jangan menyapu kering (gunakan lap basah/pel lembap).
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- =============================================================
             PANDUAN 3: METODE PENGOLAHAN LAHAN TANPA BAKAR (PLTB)
             ============================================================= -->
        <div id="guide-pltb" class="guide-content-panel" style="display: none;">
            <div style="background: #FAF8F5; border: 1px solid var(--color-border-subtle); border-radius: 16px; padding: 2.5rem 2rem; margin-bottom: 2rem;">
                <div style="max-width: 820px; margin: 0 auto;">
                    <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.5rem;">
                        <span class="badge badge-forest" style="font-size: 0.8rem; padding: 0.3rem 0.85rem;">Pertanian Gambut Berkelanjutan</span>
                        <span style="font-size: 0.84rem; color: #6C7A70;">Solusi Tanpa Asap & Bebas Sanksi Pidana</span>
                    </div>

                    <h3 style="font-size: 1.7rem; color: var(--color-deep-forest); margin-bottom: 1rem; line-height: 1.3;">
                        Teknik Pembukaan Lahan Tanpa Bakar (PLTB) untuk Petani
                    </h3>
                    <p style="font-size: 1rem; color: #4B554D; line-height: 1.7; margin-bottom: 2rem;">
                        Membakar gambut merusak lapisan humus dan memicu kebakaran bawah tanah yang sulit dikendalikan. Metode PLTB terbukti menyuburkan tanah lebih tahan lama tanpa risiko pidana:
                    </p>

                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem;">
                        <!-- Step 1 -->
                        <div style="background: #FFFFFF; border: 1px solid var(--color-border-subtle); border-radius: 12px; padding: 1.5rem;">
                            <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.85rem;">
                                <div style="width: 32px; height: 32px; border-radius: 50%; background: var(--color-deep-forest); color: var(--color-earth-sand); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.95rem; flex-shrink: 0;">1</div>
                                <h4 style="font-size: 1.1rem; margin: 0; color: var(--color-deep-forest);">Tebas & Cacah Serasah (Mulsa)</h4>
                            </div>
                            <p style="font-size: 0.88rem; color: #536056; line-height: 1.6; margin: 0;">
                                Rumput ilalang dan semak belukar ditebas lalu dicacah kasar. Tebarkan di permukaan tanah sebagai mulsa organik untuk menahan penguapan air gambut saat kemarau.
                            </p>
                        </div>

                        <!-- Step 2 -->
                        <div style="background: #FFFFFF; border: 1px solid var(--color-border-subtle); border-radius: 12px; padding: 1.5rem;">
                            <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.85rem;">
                                <div style="width: 32px; height: 32px; border-radius: 50%; background: var(--color-deep-forest); color: var(--color-earth-sand); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.95rem; flex-shrink: 0;">2</div>
                                <h4 style="font-size: 1.1rem; margin: 0; color: var(--color-deep-forest);">Aplikasi Dekomposer Hayati</h4>
                            </div>
                            <p style="font-size: 0.88rem; color: #536056; line-height: 1.6; margin: 0;">
                                Semprotkan mikroorganisme pengurai (dekomposer lokal seperti Trichoderma atau EM4). Dalam waktu 2–3 minggu, sisa tanaman membusuk menjadi pupuk kompos kaya nutrisi alami.
                            </p>
                        </div>

                        <!-- Step 3 -->
                        <div style="background: #FFFFFF; border: 1px solid var(--color-border-subtle); border-radius: 12px; padding: 1.5rem;">
                            <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.85rem;">
                                <div style="width: 32px; height: 32px; border-radius: 50%; background: var(--color-deep-forest); color: var(--color-earth-sand); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.95rem; flex-shrink: 0;">3</div>
                                <h4 style="font-size: 1.1rem; margin: 0; color: var(--color-deep-forest);">Produksi Biochar & Asap Cair</h4>
                            </div>
                            <p style="font-size: 0.88rem; color: #536056; line-height: 1.6; margin: 0;">
                                Ranting keras dapat diarangkan dalam drum pirolisis tertutup (tanpa api terbuka). Biochar memperbaiki porositas gambut, sedangkan asap cair berfungsi sebagai pestisida hayati.
                            </p>
                        </div>

                        <!-- Step 4 -->
                        <div style="background: #FFFFFF; border: 1px solid var(--color-border-subtle); border-radius: 12px; padding: 1.5rem;">
                            <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.85rem;">
                                <div style="width: 32px; height: 32px; border-radius: 50%; background: var(--color-deep-forest); color: var(--color-earth-sand); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.95rem; flex-shrink: 0;">4</div>
                                <h4 style="font-size: 1.1rem; margin: 0; color: var(--color-deep-forest);">Budidaya Paludikultur</h4>
                            </div>
                            <p style="font-size: 0.88rem; color: #536056; line-height: 1.6; margin: 0;">
                                Tanam komoditas yang hidup di gambut basah tanpa perlu mengeringkan parit: tanaman purun untuk anyaman, sagu, jelutung, rotan, dan tanaman obat endemik Kalimantan.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>

<!-- =========================================================================
     4. CHECKLIST KESIAPSIAGAAN RUMAH TANGGA INTERAKTIF
     Interactive checklist with persistent browser storage (localStorage)
     ========================================================================= -->
<section style="padding: 4rem 0 4.5rem; background-color: var(--color-warm-paper);">
    <div class="container-editorial">
        <div style="background: #FFFFFF; border: 1px solid var(--color-border-subtle); border-radius: 16px; padding: 2.75rem 2.25rem; max-width: 860px; margin: 0 auto; box-shadow: var(--shadow-editorial);">
            <div style="display: flex; align-items: flex-start; justify-content: space-between; gap: 1.5rem; flex-wrap: wrap; margin-bottom: 1.75rem;">
                <div>
                    <span class="eyebrow" style="color: var(--color-alert-amber);">Evaluasi Kesiapan Mandiri</span>
                    <h3 style="font-size: clamp(1.5rem, 2.8vw, 2rem); margin-bottom: 0.5rem; color: var(--color-deep-forest);">
                        Checklist Kesiapsiagaan Keluarga Hadapi Asap
                    </h3>
                    <p style="font-size: 0.92rem; color: #586058; margin: 0;">
                        Beri tanda centang pada persiapan yang sudah dilakukan di rumah Anda. Kemajuan akan tersimpan otomatis di browser:
                    </p>
                </div>
                <div id="checklistProgressBadge" style="background: rgba(38, 59, 48, 0.08); border: 1px solid var(--color-border-subtle); padding: 0.5rem 1rem; border-radius: 999px; font-weight: 700; font-size: 0.86rem; color: var(--color-deep-forest);">
                    Tercapai: <span id="checklistCount">0</span> / 6 Langkah
                </div>
            </div>

            <!-- 6 Interactive Checklist Items -->
            <div style="display: flex; flex-direction: column; gap: 1rem;" id="checklistContainer">
                <label class="checklist-item" style="display: flex; align-items: center; gap: 1rem; padding: 1rem 1.25rem; border-radius: 10px; border: 1px solid var(--color-border-subtle); background: #FAF9F5; cursor: pointer; transition: all 0.2s ease;">
                    <input type="checkbox" class="checklist-checkbox" data-key="check_masker" onchange="updateChecklistProgress()" style="width: 19px; height: 19px; accent-color: var(--color-deep-forest); cursor: pointer;">
                    <span style="font-size: 0.92rem; color: #333; line-height: 1.5;">
                        Tersedia cadangan masker respirator standar <strong>N95 atau KN95</strong> minimal 3 pcs untuk setiap anggota keluarga.
                    </span>
                </label>

                <label class="checklist-item" style="display: flex; align-items: center; gap: 1rem; padding: 1rem 1.25rem; border-radius: 10px; border: 1px solid var(--color-border-subtle); background: #FAF9F5; cursor: pointer; transition: all 0.2s ease;">
                    <input type="checkbox" class="checklist-checkbox" data-key="check_obat" onchange="updateChecklistProgress()" style="width: 19px; height: 19px; accent-color: var(--color-deep-forest); cursor: pointer;">
                    <span style="font-size: 0.92rem; color: #333; line-height: 1.5;">
                        Tersedia cairan tetes mata steril (saline/artificial tears) dan obat resep asma darurat bagi penderita alergi napas.
                    </span>
                </label>

                <label class="checklist-item" style="display: flex; align-items: center; gap: 1rem; padding: 1rem 1.25rem; border-radius: 10px; border: 1px solid var(--color-border-subtle); background: #FAF9F5; cursor: pointer; transition: all 0.2s ease;">
                    <input type="checkbox" class="checklist-checkbox" data-key="check_kamar" onchange="updateChecklistProgress()" style="width: 19px; height: 19px; accent-color: var(--color-deep-forest); cursor: pointer;">
                    <span style="font-size: 0.92rem; color: #333; line-height: 1.5;">
                        Sudah menentukan satu kamar tidur di rumah yang akan dijadikan <strong>Ruang Aman Asap (Clean Air Room)</strong>.
                    </span>
                </label>

                <label class="checklist-item" style="display: flex; align-items: center; gap: 1rem; padding: 1rem 1.25rem; border-radius: 10px; border: 1px solid var(--color-border-subtle); background: #FAF9F5; cursor: pointer; transition: all 0.2s ease;">
                    <input type="checkbox" class="checklist-checkbox" data-key="check_handuk" onchange="updateChecklistProgress()" style="width: 19px; height: 19px; accent-color: var(--color-deep-forest); cursor: pointer;">
                    <span style="font-size: 0.92rem; color: #333; line-height: 1.5;">
                        Menyiapkan handuk/kain basah atau lakban tebal untuk menutup ventilasi dan celah pintu saat kualitas udara berbahaya.
                    </span>
                </label>

                <label class="checklist-item" style="display: flex; align-items: center; gap: 1rem; padding: 1rem 1.25rem; border-radius: 10px; border: 1px solid var(--color-border-subtle); background: #FAF9F5; cursor: pointer; transition: all 0.2s ease;">
                    <input type="checkbox" class="checklist-checkbox" data-key="check_kontak" onchange="updateChecklistProgress()" style="width: 19px; height: 19px; accent-color: var(--color-deep-forest); cursor: pointer;">
                    <span style="font-size: 0.92rem; color: #333; line-height: 1.5;">
                        Menyimpan nomor darurat Posko Karhutla terdekat, Damkar, dan nomor darurat bebas pulsa <strong>112</strong> di kontak ponsel.
                    </span>
                </label>

                <label class="checklist-item" style="display: flex; align-items: center; gap: 1rem; padding: 1rem 1.25rem; border-radius: 10px; border: 1px solid var(--color-border-subtle); background: #FAF9F5; cursor: pointer; transition: all 0.2s ease;">
                    <input type="checkbox" class="checklist-checkbox" data-key="check_borneo" onchange="updateChecklistProgress()" style="width: 19px; height: 19px; accent-color: var(--color-deep-forest); cursor: pointer;">
                    <span style="font-size: 0.92rem; color: #333; line-height: 1.5;">
                        Menyimpan tautan <strong>Borneo Siaga</strong> untuk memantau sebaran titik panas satelit NASA FIRMS dan pelaporan titik api.
                    </span>
                </label>
            </div>
        </div>
    </div>
</section>

<!-- =========================================================================
     5. WARTA & ARTIKEL BERITA KARHUTLA (SINDIKASI OTOMATIS MEDIA EKSTERNAL)
     Berita dan artikel yang diambil secara real-time dari media berita eksternal
     ========================================================================= -->
<section id="artikel-berita" style="padding: 4.5rem 0 5.5rem; background-color: #FAF8F5; border-top: 1px solid var(--color-border-subtle);">
    <div class="container-editorial">
        <!-- Section Header with Real-time Status -->
        <div style="display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 1.5rem; margin-bottom: 2.5rem;">
            <div>
                <div style="display: inline-flex; align-items: center; gap: 0.55rem; margin-bottom: 0.6rem; background: rgba(38, 59, 48, 0.08); padding: 0.35rem 0.85rem; border-radius: 999px;">
                    <span style="width: 8px; height: 8px; border-radius: 50%; background-color: #38A169;"></span>
                    <span style="font-size: 0.76rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; color: var(--color-deep-forest);">
                        SINDIKASI OTOMATIS BERITA EKSTERNAL
                    </span>
                </div>
                <h2 style="font-size: clamp(1.85rem, 3.2vw, 2.5rem); margin-bottom: 0.5rem; color: var(--color-deep-forest);">
                    Warta & Artikel Karhutla Terkini
                </h2>
                <p class="editorial-lead" style="max-width: 680px; margin-bottom: 0;">
                    Laporan kabar lapangan, aksi pemadaman Manggala Agni, penegakan hukum, serta pemantauan kabut asap yang dihimpun otomatis dari berbagai redaksi berita terpercaya.
                </p>
            </div>

            <!-- Sync Meta & Refresh Action -->
            <div style="display: flex; align-items: center; gap: 0.75rem; flex-wrap: wrap;">
                <span style="font-size: 0.82rem; color: #69766D;">
                    Pembaruan: <strong>{{ $news['last_synced_human'] ?? 'Baru saja' }}</strong>
                </span>
                <a href="{{ route('edukasi', ['region' => $currentRegion, 'refresh' => 1]) }}#artikel-berita" 
                   class="btn btn-outline-forest" 
                   style="padding: 0.5rem 1rem; font-size: 0.82rem; display: inline-flex; align-items: center; gap: 0.4rem;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M21.5 2v6h-6M21.34 15.57a10 10 0 1 1-.57-8.38l5.67-5.67"/></svg>
                    <span>Segarkan Berita</span>
                </a>
            </div>
        </div>

        <!-- Filter & Search Controls Bar -->
        <div style="background: #FFFFFF; border: 1px solid var(--color-border-subtle); border-radius: 12px; padding: 1.25rem 1.5rem; margin-bottom: 2.5rem; box-shadow: 0 4px 16px rgba(0,0,0,0.03);">
            <div style="display: flex; flex-direction: column; gap: 1rem;">
                
                <!-- Region Filter Buttons -->
                <div style="display: flex; flex-wrap: wrap; gap: 0.5rem;" role="group" aria-label="Filter Wilayah">
                    @foreach($regions as $code => $label)
                        <a href="{{ route('edukasi', array_filter(['region' => $code, 'q' => $keyword])) }}#artikel-berita" 
                           class="news-filter-pill {{ $currentRegion === $code ? 'active' : '' }}"
                           style="padding: 0.45rem 0.95rem; border-radius: 8px; font-size: 0.84rem; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 0.4rem; border: 1px solid {{ $currentRegion === $code ? 'var(--color-deep-forest)' : 'var(--color-border-subtle)' }}; background-color: {{ $currentRegion === $code ? 'var(--color-deep-forest)' : '#F9F8F5' }}; color: {{ $currentRegion === $code ? 'var(--color-earth-sand)' : 'var(--color-forest-moss)' }}; transition: all 0.2s ease;">
                            @if($currentRegion === $code)
                                <span style="width: 6px; height: 6px; border-radius: 50%; background-color: var(--color-earth-sand);"></span>
                            @endif
                            <span>{{ $label }}</span>
                        </a>
                    @endforeach
                </div>

                <!-- Search Input Field -->
                <form action="{{ route('edukasi') }}#artikel-berita" method="GET" style="display: flex; gap: 0.75rem; flex-wrap: wrap; align-items: center; padding-top: 0.85rem; border-top: 1px dashed var(--color-border-subtle);">
                    <input type="hidden" name="region" value="{{ $currentRegion }}">
                    
                    <div style="position: relative; flex: 1 1 260px;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: #7B887F;">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                        <input type="text" 
                               name="q" 
                               value="{{ $keyword }}" 
                               placeholder="Cari kata kunci artikel (contoh: sumur bor, kabut asap, patroli, satgas, polda)..." 
                               style="width: 100%; padding: 0.6rem 1rem 0.6rem 2.6rem; border-radius: 8px; border: 1px solid var(--color-border-subtle); background: #FAF9F5; font-size: 0.88rem; outline: none;">
                    </div>

                    <button type="submit" class="btn btn-forest" style="padding: 0.6rem 1.35rem; font-size: 0.86rem; flex-shrink: 0;">
                        <span>Cari Artikel</span>
                    </button>

                    @if(!empty($keyword))
                        <a href="{{ route('edukasi', ['region' => $currentRegion]) }}#artikel-berita" class="btn btn-outline-forest" style="padding: 0.6rem 1rem; font-size: 0.86rem; flex-shrink: 0; color: #8C3A27; border-color: rgba(184, 83, 36, 0.4);">
                            <span>Reset</span>
                        </a>
                    @endif
                </form>

            </div>
        </div>

        <!-- News Cards Grid -->
        @if(empty($news['items']) || count($news['items']) === 0)
            <div style="background: #FFFFFF; border: 1px dashed var(--color-border-subtle); border-radius: 12px; padding: 3.5rem 2rem; text-align: center; max-width: 580px; margin: 0 auto;">
                <div style="width: 50px; height: 50px; border-radius: 50%; background: rgba(38, 59, 48, 0.08); display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; color: var(--color-forest-moss);">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                </div>
                <h4 style="font-size: 1.25rem; margin-bottom: 0.5rem; color: var(--color-deep-forest);">Tidak Menemukan Artikel</h4>
                <p style="font-size: 0.9rem; color: #647067; line-height: 1.6; margin-bottom: 1.25rem;">
                    Belum ditemukan warta yang cocok untuk kata kunci "{{ $keyword }}". Coba cari dengan kata kunci lain atau ubah filter wilayah Anda.
                </p>
                <a href="{{ route('edukasi') }}#artikel-berita" class="btn btn-forest" style="padding: 0.6rem 1.4rem;">
                    <span>Tampilkan Semua Berita</span>
                </a>
            </div>
        @else
            <!-- Headline / Featured Article Card -->
            @php
                $leadArticle = $news['items'][0];
                $otherArticles = array_slice($news['items'], 1);
            @endphp

            <div style="background: #FFFFFF; border: 1px solid var(--color-border-subtle); border-radius: 14px; overflow: hidden; margin-bottom: 2.5rem; box-shadow: 0 4px 20px rgba(0,0,0,0.04);">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));">
                    <!-- Left Hero Editorial Box -->
                    <div style="background: linear-gradient(135deg, #1A2921 0%, #263B30 65%, #324D3E 100%); padding: 2.75rem 2.25rem; color: #FFFFFF; display: flex; flex-direction: column; justify-content: space-between;">
                        <div>
                            <div style="display: flex; align-items: center; gap: 0.65rem; margin-bottom: 1.25rem; flex-wrap: wrap;">
                                <span style="background: rgba(214, 197, 163, 0.2); border: 1px solid rgba(214, 197, 163, 0.4); color: var(--color-earth-sand); padding: 0.25rem 0.75rem; border-radius: 999px; font-size: 0.74rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em;">
                                    SOROTAN UTAMA
                                </span>
                                <span style="background: rgba(255,255,255,0.12); padding: 0.25rem 0.65rem; border-radius: 4px; font-size: 0.76rem; color: #D7E4DC;">
                                    {{ $leadArticle['region_name'] }}
                                </span>
                                <span style="font-size: 0.78rem; color: #B9C8BD;">
                                    {{ $leadArticle['published_human'] }}
                                </span>
                            </div>

                            <span style="display: block; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.12em; color: var(--color-earth-sand); font-weight: 700; margin-bottom: 0.5rem;">
                                {{ $leadArticle['source'] }}
                            </span>

                            <h3 style="font-size: clamp(1.5rem, 2.5vw, 1.95rem); color: #FFFFFF; line-height: 1.28; margin-bottom: 1rem; font-weight: 600;">
                                <a href="{{ $leadArticle['link'] }}" target="_blank" rel="noopener noreferrer" style="color: #FFFFFF; text-decoration: none;" onmouseover="this.style.color='var(--color-earth-sand)'" onmouseout="this.style.color='#FFFFFF'">
                                    {{ $leadArticle['title'] }}
                                </a>
                            </h3>

                            <p style="font-size: 0.94rem; line-height: 1.65; color: #D5E2D9; margin-bottom: 1.75rem;">
                                {{ Str::limit($leadArticle['description'], 190) }}
                            </p>
                        </div>

                        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.75rem; padding-top: 1.25rem; border-top: 1px solid rgba(214, 197, 163, 0.2);">
                            <span style="font-size: 0.78rem; color: #B9C8BD;">
                                {{ $leadArticle['published_formatted'] }}
                            </span>
                            <a href="{{ $leadArticle['link'] }}" target="_blank" rel="noopener noreferrer" class="btn btn-sand" style="padding: 0.6rem 1.35rem; font-size: 0.86rem; display: inline-flex; align-items: center; gap: 0.4rem;">
                                <span>Baca di {{ $leadArticle['source'] }}</span>
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
                            </a>
                        </div>
                    </div>

                    <!-- Right Column: Quick Highlights of Next 3 Items -->
                    <div style="padding: 2.25rem 2rem; background: #FAF9F5; display: flex; flex-direction: column; justify-content: space-between;">
                        <div>
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; padding-bottom: 0.65rem; border-bottom: 1px solid var(--color-border-subtle);">
                                <span style="font-size: 0.78rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; color: var(--color-forest-moss);">
                                    Warta Terbaru Lainnya
                                </span>
                                <span style="font-size: 0.76rem; color: #7B887F;">
                                    Sindikasi Langsung
                                </span>
                            </div>

                            <div style="display: flex; flex-direction: column; gap: 1.25rem;">
                                @foreach(array_slice($otherArticles, 0, 3) as $side)
                                    <div style="display: flex; flex-direction: column; gap: 0.25rem; padding-bottom: 1rem; border-bottom: 1px dashed rgba(82, 107, 77, 0.2);">
                                        <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.74rem;">
                                            <span style="font-weight: 700; color: var(--color-forest-moss);">{{ $side['source'] }}</span>
                                            <span style="color: #9BA89F;">•</span>
                                            <span style="color: #6C786F;">{{ $side['published_human'] }}</span>
                                            <span style="color: #9BA89F;">•</span>
                                            <span class="badge badge-sand" style="font-size: 0.68rem; padding: 0.15rem 0.45rem;">{{ $side['region_name'] }}</span>
                                        </div>
                                        <h4 style="font-size: 1.02rem; margin: 0; line-height: 1.35; font-weight: 600;">
                                            <a href="{{ $side['link'] }}" target="_blank" rel="noopener noreferrer" style="color: var(--color-deep-forest); text-decoration: none;" onmouseover="this.style.color='var(--color-alert-amber)'" onmouseout="this.style.color='var(--color-deep-forest)'">
                                                {{ $side['title'] }}
                                            </a>
                                        </h4>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div style="margin-top: 1.5rem; background: #FFFFFF; border: 1px solid var(--color-border-subtle); border-radius: 8px; padding: 0.85rem 1rem; font-size: 0.82rem; color: #515B53; display: flex; align-items: center; gap: 0.65rem;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink: 0; color: var(--color-forest-moss);"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                            <span>
                                Berita dan artikel ini terhubung langsung ke media sumber publikasi resmi untuk verifikasi berita akurat.
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Articles Grid (Remaining Items) -->
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(310px, 1fr)); gap: 1.5rem;">
                @foreach(array_slice($otherArticles, 3) as $item)
                    <article style="background: #FFFFFF; border: 1px solid var(--color-border-subtle); border-radius: 12px; padding: 1.4rem; display: flex; flex-direction: column; justify-content: space-between; box-shadow: 0 2px 10px rgba(0,0,0,0.03); transition: transform 0.2s ease, box-shadow 0.2s ease;">
                        <div>
                            <!-- Header metadata -->
                            <div style="display: flex; justify-content: space-between; align-items: center; gap: 0.5rem; margin-bottom: 0.75rem;">
                                <span class="badge" style="background-color: {{ $item['topic_color'] ?? '#263B30' }}15; color: {{ $item['topic_color'] ?? '#263B30' }}; font-size: 0.72rem;">
                                    {{ $item['topic'] ?? 'Kabar Karhutla' }}
                                </span>
                                <span style="font-size: 0.74rem; color: #768378;">
                                    {{ $item['published_human'] }}
                                </span>
                            </div>

                            <div style="display: flex; align-items: center; gap: 0.45rem; margin-bottom: 0.55rem; font-size: 0.76rem;">
                                <span style="font-weight: 700; color: var(--color-forest-moss); text-transform: uppercase; letter-spacing: 0.06em;">
                                    {{ $item['source'] }}
                                </span>
                                <span style="color: #BAC5BD;">•</span>
                                <span style="color: #58635B; background: #ECE8DC; padding: 0.12rem 0.45rem; border-radius: 4px;">
                                    {{ $item['region_name'] }}
                                </span>
                            </div>

                            <h4 style="font-size: 1.1rem; line-height: 1.35; margin: 0 0 0.65rem 0; font-weight: 600;">
                                <a href="{{ $item['link'] }}" target="_blank" rel="noopener noreferrer" style="color: var(--color-deep-forest); text-decoration: none;" onmouseover="this.style.color='var(--color-alert-amber)'" onmouseout="this.style.color='var(--color-deep-forest)'">
                                    {{ $item['title'] }}
                                </a>
                            </h4>

                            <p style="font-size: 0.86rem; color: #515B53; line-height: 1.55; margin: 0 0 1.25rem 0;">
                                {{ Str::limit($item['description'], 130) }}
                            </p>
                        </div>

                        <div style="padding-top: 0.85rem; border-top: 1px solid var(--color-border-subtle); display: flex; justify-content: space-between; align-items: center; font-size: 0.82rem;">
                            <span style="color: #8C9990; font-size: 0.74rem;">
                                {{ Str::limit($item['published_formatted'], 16) }}
                            </span>
                            <a href="{{ $item['link'] }}" target="_blank" rel="noopener noreferrer" style="color: var(--color-deep-forest); font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 0.35rem;">
                                <span>Buka Berita</span>
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif

    </div>
</section>

@push('styles')
<style>
    .guide-tab-btn {
        background: transparent;
        border: none;
        padding: 0.65rem 1.2rem;
        border-radius: 8px;
        font-family: var(--font-body);
        font-size: 0.88rem;
        font-weight: 600;
        color: #556257;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s ease;
    }
    .guide-tab-btn:hover {
        color: var(--color-deep-forest);
        background: rgba(255, 255, 255, 0.6);
    }
    .guide-tab-btn.active {
        background: #FFFFFF;
        color: var(--color-deep-forest);
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        border: 1px solid var(--color-border-subtle);
    }
    .checklist-item:hover {
        background: #FFFFFF !important;
        border-color: var(--color-deep-forest) !important;
    }
    .checklist-item input:checked + span {
        text-decoration: line-through;
        color: #7B887F;
    }
</style>
@endpush

@push('scripts')
<script>
    // Tab Switcher untuk Panduan Praktis
    function switchGuideTab(targetId, clickedBtn) {
        // Matikan semua panel
        document.querySelectorAll('.guide-content-panel').forEach(panel => {
            panel.style.display = 'none';
            panel.classList.remove('active');
        });

        // Matikan semua tab button
        document.querySelectorAll('.guide-tab-btn').forEach(btn => {
            btn.classList.remove('active');
        });

        // Aktifkan panel target
        const targetPanel = document.getElementById(targetId);
        if (targetPanel) {
            targetPanel.style.display = 'block';
            targetPanel.classList.add('active');
        }

        // Aktifkan tombol yang diklik
        if (clickedBtn) {
            clickedBtn.classList.add('active');
        }
    }

    // Interaktivitas Checklist Kesiapsiagaan dengan localStorage
    function initChecklist() {
        const checkboxes = document.querySelectorAll('.checklist-checkbox');
        checkboxes.forEach(cb => {
            const key = 'borneo_check_' + cb.dataset.key;
            if (localStorage.getItem(key) === 'true') {
                cb.checked = true;
            }
        });
        updateChecklistProgress();
    }

    function updateChecklistProgress() {
        const checkboxes = document.querySelectorAll('.checklist-checkbox');
        let count = 0;
        checkboxes.forEach(cb => {
            const key = 'borneo_check_' + cb.dataset.key;
            localStorage.setItem(key, cb.checked ? 'true' : 'false');
            if (cb.checked) count++;
        });

        const countEl = document.getElementById('checklistCount');
        if (countEl) countEl.innerText = count;

        const badge = document.getElementById('checklistProgressBadge');
        if (badge) {
            if (count === checkboxes.length) {
                badge.style.background = '#E8F5E9';
                badge.style.borderColor = '#4CAF50';
                badge.style.color = '#2E7D32';
                badge.innerText = 'Sempurna! 6 / 6 Langkah Siaga';
            } else {
                badge.style.background = 'rgba(38, 59, 48, 0.08)';
                badge.style.borderColor = 'var(--color-border-subtle)';
                badge.style.color = 'var(--color-deep-forest)';
                badge.innerHTML = 'Tercapai: <span id="checklistCount">' + count + '</span> / 6 Langkah';
            }
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        initChecklist();
    });
</script>
@endpush
@endsection
