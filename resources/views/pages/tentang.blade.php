@extends('layouts.app')

@section('title', 'Tentang Kami | BORNEO SIAGA - Penjaga Hutan Kalimantan')
@section('meta_description', 'Profil dan komitmen Borneo Siaga dalam menjaga kelestarian hutan hujan tropis dan mencegah bencana kebakaran lahan gambut di Kalimantan.')

@section('content')
<!-- Page Header -->
<section class="page-header-editorial">
    <div class="container-editorial">
        <span class="eyebrow">Misi Konservasi & Siaga</span>
        <h1>Menjaga Hutan Hujan Tropis Nusantara</h1>
        <p class="editorial-lead" style="max-width: 760px;">
            Borneo Siaga hadir sebagai inisiatif digital untuk memperkuat koordinasi penanggulangan karhutla, mendorong transparansi informasi, dan memberdayakan komunitas akar rumput di seluruh Kalimantan.
        </p>
    </div>
</section>

<!-- Content Section -->
<section style="padding: 3.5rem 0 5rem;">
    <div class="container-editorial">
        <!-- Vision & Mission Grid -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 2rem; margin-bottom: 3.5rem;">
            <div class="card-editorial">
                <span class="eyebrow">Visi Utama</span>
                <h3 style="margin-bottom: 1rem;">Kalimantan Bebas Asap & Lestari</h3>
                <p style="color: #4b524c; line-height: 1.7;">
                    Terwujudnya bentang alam pulau Kalimantan yang tangguh terhadap ancaman kebakaran hutan dan lahan, terlindunginya keanekaragaman hayati endemik, serta terjaminnya hak masyarakat atas udara bersih yang sehat.
                </p>
            </div>

            <div class="card-editorial">
                <span class="eyebrow">Tiga Nilai Fondasi</span>
                <div style="display: flex; flex-direction: column; gap: 1rem;">
                    <div>
                        <strong style="color: var(--color-deep-forest); display: block; font-size: 1.05rem;">1. Kecepatan Respons</strong>
                        <span style="font-size: 0.88rem; color: #586058;">Memperpendek jeda waktu antara deteksi titik api dengan respons tim lapangan.</span>
                    </div>
                    <div>
                        <strong style="color: var(--color-deep-forest); display: block; font-size: 1.05rem;">2. Kolaborasi Lintas Batas</strong>
                        <span style="font-size: 0.88rem; color: #586058;">Menghubungkan pemerintah daerah, instansi kehutanan, relawan pemadam, dan masyarakat adat.</span>
                    </div>
                    <div>
                        <strong style="color: var(--color-deep-forest); display: block; font-size: 1.05rem;">3. Berbasis Pengetahuan Ilmiah</strong>
                        <span style="font-size: 0.88rem; color: #586058;">Mengedepankan data geospasial valid, peta kedalaman gambut, dan mitigasi berbasis sains.</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Brand Logo & Philosophy Section -->
        <div style="background: #FFFFFF; border: 1px solid var(--color-border-subtle); border-radius: 16px; padding: 2.5rem; margin-bottom: 3.5rem; box-shadow: var(--shadow-editorial);">
            <div style="display: flex; flex-direction: row; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 2.5rem;">
                <!-- Logo Emblem -->
                <div style="flex: 0 0 auto; margin: 0 auto; text-align: center;">
                    <div style="width: 190px; height: 190px; border-radius: 50%; overflow: hidden; background: #FFFFFF; border: 4px solid var(--color-earth-sand); box-shadow: 0 10px 30px rgba(38,59,48,0.22); display: flex; align-items: center; justify-content: center;">
                        <img src="{{ asset('images/logo.png') }}" alt="Lambang Resmi Borneo Siaga" style="width: 100%; height: 100%; object-fit: cover; display: block;">
                    </div>
                    <span style="display: block; margin-top: 1rem; font-family: var(--font-heading); font-size: 1.25rem; font-weight: 700; color: var(--color-deep-forest);">
                        BORNEO SIAGA
                    </span>
                    <span style="display: block; font-size: 0.78rem; color: #6D7870; text-transform: uppercase; letter-spacing: 0.1em; margin-top: 0.2rem;">
                        Lambang Resmi Gerakan
                    </span>
                </div>

                <!-- Philosophy Explanations -->
                <div style="flex: 1 1 450px;">
                    <span class="eyebrow">Makna & Filosofi Lambang</span>
                    <h3 style="margin-bottom: 1rem; font-size: 1.7rem;">
                        "Bersama Jaga Hutan Bebas Karhutla"
                    </h3>
                    <p style="color: #4b524c; line-height: 1.7; margin-bottom: 1.5rem; font-size: 0.96rem;">
                        Setiap elemen visual dalam lambang resmi Borneo Siaga mencerminkan komitmen perlindungan alam dan kesiapsiagaan menghadapi karhutla di bumi Kalimantan:
                    </p>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(210px, 1fr)); gap: 1rem;">
                        <div style="background: var(--color-warm-paper); padding: 0.9rem 1.1rem; border-radius: 10px; border: 1px solid var(--color-border-subtle);">
                            <strong style="color: var(--color-deep-forest); display: block; font-size: 0.92rem; margin-bottom: 0.25rem;">🛰️ Satelit Pemantau</strong>
                            <span style="font-size: 0.82rem; color: #586058; line-height: 1.5; display: block;">Teknologi deteksi dini titik panas (hotspot) terintegrasi sensor satelit NASA FIRMS.</span>
                        </div>
                        <div style="background: var(--color-warm-paper); padding: 0.9rem 1.1rem; border-radius: 10px; border: 1px solid var(--color-border-subtle);">
                            <strong style="color: var(--color-deep-forest); display: block; font-size: 0.92rem; margin-bottom: 0.25rem;">🌳 Siluet Pulau Kalimantan</strong>
                            <span style="font-size: 0.82rem; color: #586058; line-height: 1.5; display: block;">Bentang alam 5 provinsi dengan hutan hujan tropis & ekosistem gambut yang wajib dijaga.</span>
                        </div>
                        <div style="background: var(--color-warm-paper); padding: 0.9rem 1.1rem; border-radius: 10px; border: 1px solid var(--color-border-subtle);">
                            <strong style="color: var(--color-deep-forest); display: block; font-size: 0.92rem; margin-bottom: 0.25rem;">🔥 Kobaran Api & Daun</strong>
                            <span style="font-size: 0.82rem; color: #586058; line-height: 1.5; display: block;">Simbol kesiapsiagaan tanggap darurat untuk memadamkan api dan memulihkan kembali kehijauan.</span>
                        </div>
                        <div style="background: var(--color-warm-paper); padding: 0.9rem 1.1rem; border-radius: 10px; border: 1px solid var(--color-border-subtle);">
                            <strong style="color: var(--color-deep-forest); display: block; font-size: 0.92rem; margin-bottom: 0.25rem;">🤝 Gotong Royong Semesta</strong>
                            <span style="font-size: 0.82rem; color: #586058; line-height: 1.5; display: block;">Sinergi relawan Masyarakat Peduli Api (MPA), satgas posko, pemerintah, dan warga.</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stakeholders & Partners Placeholder Frame -->
        <div class="placeholder-frame" style="max-width: 860px; margin: 0 auto; padding: 4rem 2rem;">
            <div class="placeholder-icon-wrap" style="background-color: var(--color-sand-soft); color: var(--color-deep-forest);">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                </svg>
            </div>

            <span class="badge badge-sand" style="margin-bottom: 1rem;">Jaringan Kolaboratif</span>
            <h3 style="max-width: 580px; margin: 0.5rem auto 1rem;">Area Kontainer Mitra & Posko Lapangan</h3>
            <p style="max-width: 620px; margin: 0 auto 1.75rem; color: #586058;">
                Modul ini disiapkan untuk direktori posko lapangan di 5 provinsi, kontak penghubung Manggala Agni / Daops regional, BPBD provinsi, serta jaringan pos simpul relawan dan Masyarakat Peduli Api (MPA).
            </p>

            <div style="display: inline-flex; align-items: center; gap: 0.75rem; background: var(--color-warm-paper); padding: 0.65rem 1.25rem; border-radius: 8px; border: 1px solid var(--color-border-subtle); font-size: 0.86rem; color: var(--color-forest-moss);">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                <span>Tahap Fondasi: Profil kelembagaan dan jaringan kemitraan siap diperluas pada tahap selanjutnya.</span>
            </div>
        </div>
    </div>
</section>
@endsection
