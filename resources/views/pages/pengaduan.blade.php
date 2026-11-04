@extends('layouts.app')

@section('title', 'Form Pengaduan Karhutla | BORNEO SIAGA')
@section('meta_description', 'Kanal resmi pelaporan kejadian kebakaran hutan dan lahan gambut Kalimantan. Kirim informasi dengan bukti lokasi dan foto pendukung secara aman.')

@section('content')
<!-- Page Header -->
<section style="position: relative; background-color: var(--color-deep-forest); color: var(--color-warm-paper); padding: 2.5rem 0 2.25rem; overflow: hidden;">
    <div style="position: absolute; inset: 0; background-image: url('{{ asset('images/borneo-hero.jpg') }}'); background-size: cover; background-position: center; filter: saturate(1.1); opacity: 0.18;" role="img" aria-label="Kanopi hutan hujan Kalimantan"></div>
    <div style="position: absolute; inset: 0; background: linear-gradient(180deg, rgba(26, 41, 33, 0.85) 0%, rgba(38, 59, 48, 0.95) 100%);"></div>

    <div class="container-editorial" style="position: relative; z-index: 10;">
        <div style="max-width: 760px; margin: 0 auto; text-align: center;">
            <div style="display: inline-flex; align-items: center; justify-content: center; gap: 0.6rem; margin-bottom: 0.4rem;">
                <span style="display: inline-block; width: 20px; height: 1.5px; background-color: var(--color-earth-sand);"></span>
                <span style="font-family: var(--font-body); font-size: 0.75rem; font-weight: 700; letter-spacing: 0.14em; text-transform: uppercase; color: var(--color-earth-sand);">
                    Partisipasi Masyarakat
                </span>
                <span style="display: inline-block; width: 20px; height: 1.5px; background-color: var(--color-earth-sand);"></span>
            </div>

            <h1 style="color: #FFFFFF; font-size: clamp(1.75rem, 3.2vw, 2.4rem); font-weight: 600; line-height: 1.2; margin-bottom: 0.45rem; letter-spacing: -0.015em;">
                Pengaduan Karhutla & Titik Api
            </h1>

            <p style="color: #DDE7E1; font-size: 0.95rem; line-height: 1.55; margin: 0 auto; max-width: 640px;">
                Laporkan titik api atau kepulan asap di sekitar Anda. Setiap laporan diproses dengan nomor tiket dan kode akses unik untuk menjaga transparansi serta privasi pelapor.
            </p>
        </div>
    </div>
</section>

<!-- Content Section -->
<section style="padding: 2.5rem 0 5rem; background-color: var(--color-warm-paper);">
    <div class="container-editorial">

        <!-- =========================================================================
             A. SUCCESS NOTIFICATION: TICKET, ACCESS CODE & SUMMARY
             Only displayed after successful store() transaction
             ========================================================================= -->
        @if(session('report_created_success'))
            <div style="max-width: 820px; margin: 0 auto 3rem; background: #FFFFFF; border: 2px solid var(--color-forest-moss); border-radius: 14px; overflow: hidden; box-shadow: var(--shadow-editorial);">
                <div style="background-color: var(--color-deep-forest); color: var(--color-warm-paper); padding: 1.6rem 2rem; display: flex; align-items: center; gap: 1rem;">
                    <div style="width: 44px; height: 44px; border-radius: 50%; background: rgba(214, 197, 163, 0.2); display: flex; align-items: center; justify-content: center; flex-shrink: 0; color: var(--color-earth-sand);">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    </div>
                    <div>
                        <h3 style="color: #FFFFFF; font-size: 1.35rem; margin: 0 0 0.2rem 0;">Laporan Berhasil Disimpan</h3>
                        <p style="color: #DDE7E1; margin: 0; font-size: 0.88rem;">Laporan kejadian kebakaran hutan dan lahan telah tercatat dengan status awal <strong>Menunggu Verifikasi</strong>.</p>
                    </div>
                </div>

                <div style="padding: 2rem;">
                    <p style="font-size: 0.92rem; color: #434C44; margin-bottom: 1.5rem;">
                        Simpan informasi kredensial pelacakan berikut untuk memeriksa perkembangan tindakan regu pemadam di lapangan:
                    </p>

                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.25rem; margin-bottom: 1.5rem;">
                        <!-- Ticket Number -->
                        <div style="background: var(--color-warm-paper); border: 1px solid var(--color-border-subtle); border-radius: 10px; padding: 1.25rem;">
                            <span style="font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.08em; color: var(--color-forest-moss); font-weight: 700; display: block; margin-bottom: 0.4rem;">
                                Nomor Tiket Resmi
                            </span>
                            <div style="font-family: monospace; font-size: 1.35rem; font-weight: 700; color: var(--color-deep-forest); letter-spacing: 0.04em;">
                                {{ session('ticket_number') }}
                            </div>
                        </div>

                        <!-- Access Code -->
                        <div style="background: #FDF4E7; border: 1px solid rgba(184, 83, 36, 0.3); border-radius: 10px; padding: 1.25rem;">
                            <span style="font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.08em; color: var(--color-alert-amber); font-weight: 700; display: block; margin-bottom: 0.4rem;">
                                Kode Akses Rahasia (Simpan Sekarang)
                            </span>
                            <div style="font-family: monospace; font-size: 1.35rem; font-weight: 700; color: #B83B26; letter-spacing: 0.08em;">
                                {{ session('access_code') }}
                            </div>
                        </div>
                    </div>

                    <!-- Security Alert -->
                    <div style="background: #FFFDF8; border-left: 4px solid var(--color-alert-amber); padding: 1rem 1.25rem; border-radius: 0 8px 8px 0; margin-bottom: 1.75rem; font-size: 0.84rem; color: #4f5850; line-height: 1.6;">
                        <strong>Peringatan Keamanan:</strong> Nomor tiket dan kode akses ini hanya ditampilkan <em>satu kali</em> pada layar ini. Sistem menyimpan kode akses dalam bentuk hash terenkripsi dan tidak mengirimkan kode akses melalui URL publik ataupun menyimpannya dalam teks biasa. Pastikan Anda mencatat atau menyalinnya sekarang.
                    </div>

                    <!-- Safe Report Summary -->
                    @if(session('report_summary'))
                        <div style="background: var(--color-warm-paper); border: 1px solid var(--color-border-subtle); border-radius: 10px; padding: 1.25rem 1.5rem; margin-bottom: 1.75rem;">
                            <span style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.08em; color: var(--color-forest-moss); font-weight: 700; display: block; margin-bottom: 0.75rem;">
                                Ringkasan Laporan yang Dikirim
                            </span>
                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 0.75rem; font-size: 0.85rem; color: #3E4640;">
                                <div><strong>Nama Pelapor:</strong> {{ session('report_summary.reporter_name') }}</div>
                                <div><strong>Wilayah:</strong> {{ session('report_summary.village') ? 'Desa ' . session('report_summary.village') . ', ' : '' }}{{ session('report_summary.district') ? 'Kec. ' . session('report_summary.district') . ', ' : '' }}{{ session('report_summary.regency') }}, {{ session('report_summary.province') }}</div>
                                <div><strong>Jenis Kejadian:</strong> {{ session('report_summary.incident_type') }}</div>
                                <div><strong>Waktu Kejadian:</strong> {{ date('d M Y, H:i', strtotime(session('report_summary.incident_time'))) }} WIB</div>
                                <div style="grid-column: 1 / -1;"><strong>Lokasi / Patokan:</strong> {{ session('report_summary.location_detail') ?: session('report_summary.incident_location') }}</div>
                                <div><strong>Status Awal:</strong> <span class="badge badge-sand" style="color: #935B0D; border-color: #D4AC0D;">{{ session('report_summary.status') }}</span></div>
                                <div><strong>Foto Bukti:</strong> {{ session('report_summary.has_photo') ? 'Tersimpan (Privat Server)' : 'Tidak Dilampirkan' }}</div>
                            </div>
                        </div>
                    @endif

                    <div style="display: flex; flex-wrap: wrap; gap: 1rem;">
                        <a href="{{ route('lacak.pengaduan') }}" class="btn btn-forest" style="padding: 0.75rem 1.6rem;">
                            <span>Lacak Status Laporan Sekarang</span>
                            <span>&rarr;</span>
                        </a>
                        <a href="{{ route('pengaduan') }}" class="btn btn-outline-forest" style="padding: 0.75rem 1.4rem;">
                            <span>Kirim Laporan Baru</span>
                        </a>
                    </div>
                </div>
            </div>
        @endif

        <!-- Error Message Notification -->
        @if(session('error_message'))
            <div style="max-width: 820px; margin: 0 auto 2rem; background: #FDEDED; border: 1px solid #F5C2C7; border-radius: 10px; padding: 1rem 1.5rem; color: #842029; font-size: 0.9rem;">
                {{ session('error_message') }}
            </div>
        @endif

        <!-- Validation Errors Box -->
        @if ($errors->any())
            <div style="max-width: 820px; margin: 0 auto 2rem; background: #FDEDED; border: 1px solid #F5C2C7; border-radius: 10px; padding: 1.25rem 1.5rem; color: #842029;">
                <div style="font-weight: 700; margin-bottom: 0.5rem; font-size: 0.92rem;">Mohon periksa kembali formulir Anda:</div>
                <ul style="margin: 0; padding-left: 1.25rem; font-size: 0.86rem; line-height: 1.6;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- =========================================================================
             B. FORMULIR PENGADUAN RESMI
             ========================================================================= -->
        <div style="max-width: 820px; margin: 0 auto; background: #FFFFFF; border: 1px solid var(--color-border-subtle); border-radius: 14px; padding: 2.5rem 2.25rem; box-shadow: var(--shadow-editorial);">
            <div style="margin-bottom: 2rem; border-bottom: 1px solid var(--color-border-subtle); padding-bottom: 1.25rem;">
                <span class="badge badge-alert" style="margin-bottom: 0.6rem;">Formulir Laporan Lapangan</span>
                <h3 style="font-size: 1.55rem; color: var(--color-deep-forest); margin: 0 0 0.35rem 0;">
                    Formulir Laporan Titik Api & Karhutla
                </h3>
                <p style="color: #555E56; font-size: 0.9rem; margin: 0;">
                    Isi formulir berikut dengan informasi yang akurat. Kolom dengan tanda bintang (<span style="color: #B83B26;">*</span>) wajib diisi.
                </p>
            </div>

            <form action="{{ route('pengaduan.store') }}" method="POST" enctype="multipart/form-data" novalidate id="formPengaduan">
                @csrf

                <!-- SEKSI 1: IDENTITAS PELAPOR -->
                <div style="margin-bottom: 2rem;">
                    <h4 style="font-size: 1.05rem; color: var(--color-deep-forest); margin-bottom: 1rem; padding-bottom: 0.4rem; border-bottom: 1px solid var(--color-border-subtle);">
                        1. Identitas Pelapor
                    </h4>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.25rem;">
                        <!-- Nama Pelapor -->
                        <div>
                            <label for="reporter_name" style="display: block; font-weight: 600; font-size: 0.9rem; color: var(--color-deep-forest); margin-bottom: 0.4rem;">
                                Nama Lengkap Pelapor <span style="color: #B83B26;">*</span>
                            </label>
                            <input type="text" 
                                   id="reporter_name" 
                                   name="reporter_name" 
                                   value="{{ old('reporter_name') }}" 
                                   placeholder="Contoh: Budi Santoso" 
                                   required 
                                   style="width: 100%; padding: 0.75rem 1rem; border: 1px solid {{ $errors->has('reporter_name') ? '#B83B26' : 'var(--color-border-subtle)' }}; border-radius: 8px; font-family: var(--font-body); font-size: 0.92rem; background: #FAF9F5;">
                            @error('reporter_name')
                                <span style="font-size: 0.78rem; color: #B83B26; margin-top: 0.3rem; display: block;">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Nomor Kontak -->
                        <div>
                            <label for="reporter_phone" style="display: block; font-weight: 600; font-size: 0.9rem; color: var(--color-deep-forest); margin-bottom: 0.4rem;">
                                Nomor Kontak / WhatsApp <span style="color: #B83B26;">*</span>
                            </label>
                            <input type="tel" 
                                   id="reporter_phone" 
                                   name="reporter_phone" 
                                   value="{{ old('reporter_phone') }}" 
                                   placeholder="Contoh: 081234567890" 
                                   required 
                                   style="width: 100%; padding: 0.75rem 1rem; border: 1px solid {{ $errors->has('reporter_phone') ? '#B83B26' : 'var(--color-border-subtle)' }}; border-radius: 8px; font-family: var(--font-body); font-size: 0.92rem; background: #FAF9F5;">
                            @error('reporter_phone')
                                <span style="font-size: 0.78rem; color: #B83B26; margin-top: 0.3rem; display: block;">{{ $message }}</span>
                            @else
                                <span style="font-size: 0.78rem; color: #69766C; margin-top: 0.3rem; display: block;">Untuk verifikasi dan koordinasi regu pemadam lapangan.</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- SEKSI 2: LOKASI KEJADIAN KEBAKARAN -->
                <div style="margin-bottom: 2rem;">
                    <div style="display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 1rem; padding-bottom: 0.4rem; border-bottom: 1px solid var(--color-border-subtle); flex-wrap: wrap; gap: 0.5rem;">
                        <h4 style="font-size: 1.05rem; color: var(--color-deep-forest); margin: 0;">
                            2. Lokasi Kejadian Kebakaran
                        </h4>
                        <span id="regionLoadingGlobal" style="display: none; font-size: 0.78rem; color: var(--color-forest-moss); align-items: center; gap: 0.35rem;">
                            <svg class="spin-animation" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg>
                            <span>Memuat data wilayah...</span>
                        </span>
                    </div>

                    <!-- Hidden inputs untuk menyimpan Nama & Kode Wilayah serta sinkronisasi database -->
                    <input type="hidden" id="province_name" name="province_name" value="{{ old('province_name', old('province')) }}">
                    <input type="hidden" id="province_input" name="province" value="{{ old('province', old('province_name')) }}">

                    <input type="hidden" id="regency_name" name="regency_name" value="{{ old('regency_name', old('regency')) }}">
                    <input type="hidden" id="regency_input" name="regency" value="{{ old('regency', old('regency_name')) }}">

                    <input type="hidden" id="district_name" name="district_name" value="{{ old('district_name', old('district')) }}">
                    <input type="hidden" id="district_input" name="district" value="{{ old('district', old('district_name')) }}">

                    <input type="hidden" id="village_name" name="village_name" value="{{ old('village_name', old('village')) }}">
                    <input type="hidden" id="village_input" name="village" value="{{ old('village', old('village_name')) }}">

                    <!-- Urutan 1 & 2: Provinsi dan Kabupaten/Kota -->
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.25rem; margin-bottom: 1.25rem;">
                        <!-- 1. Provinsi -->
                        <div>
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.4rem;">
                                <label for="province_code" style="font-weight: 600; font-size: 0.9rem; color: var(--color-deep-forest);">
                                    1. Provinsi <span style="color: #B83B26;">*</span>
                                </label>
                                <span id="provinceLoading" style="display: none; font-size: 0.74rem; color: var(--color-forest-moss);">
                                    <svg class="spin-animation" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg> Memuat...
                                </span>
                            </div>
                            <select id="province_code" 
                                    name="province_code" 
                                    required 
                                    data-old-val="{{ old('province_code') }}"
                                    data-old-name="{{ old('province_name', old('province')) }}"
                                    style="width: 100%; padding: 0.75rem 1rem; border: 1px solid {{ $errors->has('province') || $errors->has('province_code') ? '#B83B26' : 'var(--color-border-subtle)' }}; border-radius: 8px; font-family: var(--font-body); font-size: 0.92rem; background: #FAF9F5; color: var(--color-charcoal);">
                                <option value="" disabled selected>Pilih Provinsi di Kalimantan</option>
                            </select>
                            @error('province')
                                <span style="font-size: 0.78rem; color: #B83B26; margin-top: 0.3rem; display: block;">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- 2. Kabupaten / Kota -->
                        <div>
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.4rem;">
                                <label for="regency_code" style="font-weight: 600; font-size: 0.9rem; color: var(--color-deep-forest);">
                                    2. Kabupaten / Kota <span style="color: #B83B26;">*</span>
                                </label>
                                <span id="regencyLoading" style="display: none; font-size: 0.74rem; color: var(--color-forest-moss);">
                                    <svg class="spin-animation" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg> Memuat...
                                </span>
                            </div>
                            <select id="regency_code" 
                                    name="regency_code" 
                                    required 
                                    disabled
                                    data-old-val="{{ old('regency_code') }}"
                                    data-old-name="{{ old('regency_name', old('regency')) }}"
                                    style="width: 100%; padding: 0.75rem 1rem; border: 1px solid {{ $errors->has('regency') || $errors->has('regency_code') ? '#B83B26' : 'var(--color-border-subtle)' }}; border-radius: 8px; font-family: var(--font-body); font-size: 0.92rem; background: #FAF9F5; color: var(--color-charcoal);">
                                <option value="" disabled selected>Pilih Provinsi terlebih dahulu</option>
                            </select>
                            @error('regency')
                                <span style="font-size: 0.78rem; color: #B83B26; margin-top: 0.3rem; display: block;">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Urutan 3 & 4: Kecamatan dan Desa/Kelurahan -->
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.25rem; margin-bottom: 1.25rem;">
                        <!-- 3. Kecamatan -->
                        <div>
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.4rem;">
                                <label for="district_code" style="font-weight: 600; font-size: 0.9rem; color: var(--color-deep-forest);">
                                    3. Kecamatan <span style="color: #B83B26;">*</span>
                                </label>
                                <span id="districtLoading" style="display: none; font-size: 0.74rem; color: var(--color-forest-moss);">
                                    <svg class="spin-animation" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg> Memuat...
                                </span>
                            </div>
                            <select id="district_code" 
                                    name="district_code" 
                                    required 
                                    disabled
                                    data-old-val="{{ old('district_code') }}"
                                    data-old-name="{{ old('district_name', old('district')) }}"
                                    style="width: 100%; padding: 0.75rem 1rem; border: 1px solid {{ $errors->has('district') || $errors->has('district_code') ? '#B83B26' : 'var(--color-border-subtle)' }}; border-radius: 8px; font-family: var(--font-body); font-size: 0.92rem; background: #FAF9F5; color: var(--color-charcoal);">
                                <option value="" disabled selected>Pilih Kab/Kota terlebih dahulu</option>
                            </select>
                            @error('district')
                                <span style="font-size: 0.78rem; color: #B83B26; margin-top: 0.3rem; display: block;">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- 4. Desa / Kelurahan -->
                        <div>
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.4rem;">
                                <label for="village_code" style="font-weight: 600; font-size: 0.9rem; color: var(--color-deep-forest);">
                                    4. Desa / Kelurahan <span style="color: #B83B26;">*</span>
                                </label>
                                <span id="villageLoading" style="display: none; font-size: 0.74rem; color: var(--color-forest-moss);">
                                    <svg class="spin-animation" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg> Memuat...
                                </span>
                            </div>
                            <select id="village_code" 
                                    name="village_code" 
                                    required 
                                    disabled
                                    data-old-val="{{ old('village_code') }}"
                                    data-old-name="{{ old('village_name', old('village')) }}"
                                    style="width: 100%; padding: 0.75rem 1rem; border: 1px solid {{ $errors->has('village') || $errors->has('village_code') ? '#B83B26' : 'var(--color-border-subtle)' }}; border-radius: 8px; font-family: var(--font-body); font-size: 0.92rem; background: #FAF9F5; color: var(--color-charcoal);">
                                <option value="" disabled selected>Pilih Kecamatan terlebih dahulu</option>
                            </select>
                            @error('village')
                                <span style="font-size: 0.78rem; color: #B83B26; margin-top: 0.3rem; display: block;">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- 5. Detail Lokasi Kejadian (Opsional) -->
                    <div style="margin-bottom: 1.25rem;">
                        <label for="location_detail" style="display: block; font-weight: 600; font-size: 0.9rem; color: var(--color-deep-forest); margin-bottom: 0.4rem;">
                            5. Detail Patokan Lokasi Kejadian <span style="font-size: 0.78rem; color: #69766C; font-weight: 400;">(Opsional)</span>
                        </label>
                        <input type="text" 
                               id="location_detail" 
                               name="location_detail" 
                               value="{{ old('location_detail', old('incident_location')) }}" 
                               placeholder="Contoh: Jl. Poros RT 04 RW 02, sekitar 300 meter di belakang batas kanal sawit / dekat jembatan kayu" 
                               style="width: 100%; padding: 0.75rem 1rem; border: 1px solid {{ $errors->has('incident_location') || $errors->has('location_detail') ? '#B83B26' : 'var(--color-border-subtle)' }}; border-radius: 8px; font-family: var(--font-body); font-size: 0.92rem; background: #FAF9F5;">
                        <input type="hidden" id="incident_location" name="incident_location" value="{{ old('incident_location', old('location_detail')) }}">
                        @error('incident_location')
                            <span style="font-size: 0.78rem; color: #B83B26; margin-top: 0.3rem; display: block;">{{ $message }}</span>
                        @else
                            <span style="font-size: 0.78rem; color: #69766C; margin-top: 0.3rem; display: block;">
                                Tuliskan patokan jembatan, parit batas konsesi, nama jalan, atau arah dari pemukiman warga terdekat.
                            </span>
                        @enderror
                    </div>

                    <!-- Koordinat Opsional (Latitude & Longitude) -->
                    <div style="background: var(--color-warm-paper); border: 1px solid var(--color-border-subtle); border-radius: 10px; padding: 1.25rem;">
                        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.75rem; margin-bottom: 0.75rem;">
                            <div>
                                <span style="font-weight: 600; font-size: 0.9rem; color: var(--color-deep-forest); display: block;">
                                    Koordinat Geografis (Opsional)
                                </span>
                                <span style="font-size: 0.78rem; color: #69766C;">
                                    Membantu tim posko mencocokkan laporan dengan anomali satelit NASA FIRMS.
                                </span>
                            </div>
                            <button type="button" id="btnGetGps" class="btn btn-outline-forest" style="padding: 0.4rem 0.85rem; font-size: 0.8rem; background: #FFFFFF;">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="2" x2="12" y2="6"/><line x1="12" y1="18" x2="12" y2="22"/><line x1="2" y1="12" x2="6" y2="12"/><line x1="18" y1="12" x2="22" y2="12"/></svg>
                                <span>Ambil GPS Perangkat</span>
                            </button>
                        </div>

                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                            <div>
                                <label for="latitude" style="display: block; font-size: 0.82rem; font-weight: 600; color: #4B544C; margin-bottom: 0.3rem;">Latitude (Lintang)</label>
                                <input type="number" 
                                       step="any" 
                                       id="latitude" 
                                       name="latitude" 
                                       value="{{ old('latitude') }}" 
                                       placeholder="Contoh: -0.0263" 
                                       style="width: 100%; padding: 0.65rem 0.85rem; border: 1px solid var(--color-border-subtle); border-radius: 6px; font-size: 0.88rem; background: #FFFFFF;">
                                @error('latitude')
                                    <span style="font-size: 0.78rem; color: #B83B26; margin-top: 0.3rem; display: block;">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label for="longitude" style="display: block; font-size: 0.82rem; font-weight: 600; color: #4B544C; margin-bottom: 0.3rem;">Longitude (Bujur)</label>
                                <input type="number" 
                                       step="any" 
                                       id="longitude" 
                                       name="longitude" 
                                       value="{{ old('longitude') }}" 
                                       placeholder="Contoh: 109.3425" 
                                       style="width: 100%; padding: 0.65rem 0.85rem; border: 1px solid var(--color-border-subtle); border-radius: 6px; font-size: 0.88rem; background: #FFFFFF;">
                                @error('longitude')
                                    <span style="font-size: 0.78rem; color: #B83B26; margin-top: 0.3rem; display: block;">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div id="gpsStatusText" style="font-size: 0.78rem; color: var(--color-forest-moss); margin-top: 0.5rem; display: none;"></div>
                    </div>
                </div>

                <!-- SEKSI 3: WAKTU & KARAKTERISTIK KEBAKARAN -->
                <div style="margin-bottom: 2rem;">
                    <h4 style="font-size: 1.05rem; color: var(--color-deep-forest); margin-bottom: 1rem; padding-bottom: 0.4rem; border-bottom: 1px solid var(--color-border-subtle);">
                        3. Waktu & Karakteristik Kejadian
                    </h4>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.25rem; margin-bottom: 1.25rem;">
                        <!-- Tanggal & Waktu Kejadian -->
                        <div>
                            <label for="incident_time" style="display: block; font-weight: 600; font-size: 0.9rem; color: var(--color-deep-forest); margin-bottom: 0.4rem;">
                                Tanggal & Perkiraan Waktu <span style="color: #B83B26;">*</span>
                            </label>
                            <input type="datetime-local" 
                                   id="incident_time" 
                                   name="incident_time" 
                                   value="{{ old('incident_time', now()->format('Y-m-d\TH:i')) }}" 
                                   max="{{ now()->format('Y-m-d\TH:i') }}" 
                                   required 
                                   style="width: 100%; padding: 0.75rem 1rem; border: 1px solid {{ $errors->has('incident_time') ? '#B83B26' : 'var(--color-border-subtle)' }}; border-radius: 8px; font-family: var(--font-body); font-size: 0.92rem; background: #FAF9F5;">
                            @error('incident_time')
                                <span style="font-size: 0.78rem; color: #B83B26; margin-top: 0.3rem; display: block;">{{ $message }}</span>
                            @else
                                <span style="font-size: 0.78rem; color: #69766C; margin-top: 0.3rem; display: block;">Waktu pertama kali api atau asap terlihat.</span>
                            @enderror
                        </div>

                        <!-- Jenis Kejadian -->
                        <div>
                            <label for="incident_type" style="display: block; font-weight: 600; font-size: 0.9rem; color: var(--color-deep-forest); margin-bottom: 0.4rem;">
                                Jenis Kejadian <span style="color: #B83B26;">*</span>
                            </label>
                            <select id="incident_type" 
                                    name="incident_type" 
                                    required 
                                    style="width: 100%; padding: 0.75rem 1rem; border: 1px solid {{ $errors->has('incident_type') ? '#B83B26' : 'var(--color-border-subtle)' }}; border-radius: 8px; font-family: var(--font-body); font-size: 0.92rem; background: #FAF9F5; color: var(--color-charcoal);">
                                <option value="" disabled {{ old('incident_type') ? '' : 'selected' }}>Pilih Jenis Kebakaran</option>
                                <option value="Kebakaran Lahan Gambut" {{ old('incident_type', 'Kebakaran Lahan Gambut') === 'Kebakaran Lahan Gambut' ? 'selected' : '' }}>Kebakaran Lahan Gambut</option>
                                <option value="Kebakaran Hutan Lindung / Konservasi" {{ old('incident_type') === 'Kebakaran Hutan Lindung / Konservasi' ? 'selected' : '' }}>Kebakaran Hutan Lindung / Konservasi</option>
                                <option value="Kebakaran Lahan Perkebunan" {{ old('incident_type') === 'Kebakaran Lahan Perkebunan' ? 'selected' : '' }}>Kebakaran Lahan Perkebunan</option>
                                <option value="Kebakaran Semak Belukar / Alang-alang" {{ old('incident_type') === 'Kebakaran Semak Belukar / Alang-alang' ? 'selected' : '' }}>Kebakaran Semak Belukar / Alang-alang</option>
                                <option value="Kebakaran Dekat Permukiman" {{ old('incident_type') === 'Kebakaran Dekat Permukiman' ? 'selected' : '' }}>Kebakaran Dekat Permukiman</option>
                                <option value="Lainnya" {{ old('incident_type') === 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                            @error('incident_type')
                                <span style="font-size: 0.78rem; color: #B83B26; margin-top: 0.3rem; display: block;">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Deskripsi Kejadian -->
                    <div style="margin-bottom: 1.25rem;">
                        <label for="description" style="display: block; font-weight: 600; font-size: 0.9rem; color: var(--color-deep-forest); margin-bottom: 0.4rem;">
                            Deskripsi Situasi Kejadian <span style="color: #B83B26;">*</span>
                        </label>
                        <textarea id="description" 
                                  name="description" 
                                  rows="5" 
                                  placeholder="Deskripsikan kondisi kebakaran: perkiraan luas lahan yang terbakar, arah tiupan angin dan rambatan api, ketebalan kabut asap, sumber air terdekat, serta ada tidaknya ancaman terhadap permukiman atau fasilitas warga." 
                                  required 
                                  style="width: 100%; padding: 0.85rem 1rem; border: 1px solid {{ $errors->has('description') ? '#B83B26' : 'var(--color-border-subtle)' }}; border-radius: 8px; font-family: var(--font-body); font-size: 0.92rem; line-height: 1.6; background: #FAF9F5;">{{ old('description') }}</textarea>
                        @error('description')
                            <span style="font-size: 0.78rem; color: #B83B26; margin-top: 0.3rem; display: block;">{{ $message }}</span>
                        @else
                            <span style="font-size: 0.78rem; color: #69766C; margin-top: 0.3rem; display: block;">
                                Minimal 15 karakter agar informasi memadai untuk asesmen prioritas regu penindak.
                            </span>
                        @enderror
                    </div>

                    <!-- Foto Bukti Kejadian -->
                    <div>
                        <label for="photo" style="display: block; font-weight: 600; font-size: 0.9rem; color: var(--color-deep-forest); margin-bottom: 0.4rem;">
                            Foto Bukti Kejadian <span style="font-size: 0.78rem; color: #69766C; font-weight: 400;">(Opsional)</span>
                        </label>
                        <input type="file" 
                               id="photo" 
                               name="photo" 
                               accept="image/jpeg,image/png,image/webp,image/jpg" 
                               style="width: 100%; padding: 0.65rem 0.85rem; border: 1px solid {{ $errors->has('photo') ? '#B83B26' : 'var(--color-border-subtle)' }}; border-radius: 8px; font-size: 0.88rem; background: #FAF9F5;">
                        @error('photo')
                            <span style="font-size: 0.78rem; color: #B83B26; margin-top: 0.3rem; display: block;">{{ $message }}</span>
                        @else
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 0.35rem; font-size: 0.78rem; color: #69766C; flex-wrap: wrap;">
                                <span>Format: JPG, JPEG, PNG, WEBP. Maksimal ukuran: 5 MB.</span>
                                <span style="color: var(--color-forest-moss); font-weight: 600;">Tersimpan Privat di Server</span>
                            </div>
                        @enderror
                    </div>
                </div>

                <!-- Jaminan Kerahasiaan Dokumen -->
                <div style="background-color: var(--color-warm-paper); border: 1px solid var(--color-border-subtle); border-radius: 8px; padding: 1rem 1.25rem; margin-bottom: 2rem; display: flex; gap: 0.75rem; align-items: flex-start; font-size: 0.82rem; color: #515B53; line-height: 1.6;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink: 0; color: var(--color-forest-moss); margin-top: 2px;"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                    <div>
                        <strong>Privasi & Keamanan Data:</strong> Identitas pelapor dan foto bukti disimpan pada penyimpanan privat server yang terisolasi dan tidak dapat diakses publik. Kode akses pelacakan diamankan dengan mekanisme hash satu arah (Bcrypt).
                    </div>
                </div>

                <!-- Submit Button -->
                <div>
                    <button type="submit" id="btnSubmitReport" class="btn btn-forest" style="width: 100%; padding: 0.95rem 1.5rem; font-size: 1rem;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                            <path d="M8.5 14.5A2.5 2.5 0 0 0 11 12c0-1.38-.5-2-1-3-1.072-2.143-.224-4.054 2-6 .5 2.5 2 4.9 4 6.5 2 1.6 3 3.5 3 5.5a7 7 0 1 1-14 0c0-1.153.433-2.294 1-3a2.5 2.5 0 0 0 2.5 3z"/>
                        </svg>
                        <span>Kirim Laporan Karhutla Sekarang</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Alur 4 Tahap Pengaduan -->
        <div style="max-width: 860px; margin: 4rem auto 0;">
            <h3 style="margin-bottom: 1.5rem; text-align: center;">Alur Penanganan Laporan Pengaduan</h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(190px, 1fr)); gap: 1.25rem;">
                <div style="background: #FFFFFF; padding: 1.25rem; border-radius: 10px; border: 1px solid var(--color-border-subtle);">
                    <div style="font-family: var(--font-heading); font-size: 1.5rem; color: var(--color-forest-moss); font-weight: 700;">01. Lapor</div>
                    <p style="font-size: 0.84rem; color: #555E56; margin: 0.4rem 0 0;">Pengisian formulir oleh masyarakat dengan nomor tiket & kode akses resmi.</p>
                </div>
                <div style="background: #FFFFFF; padding: 1.25rem; border-radius: 10px; border: 1px solid var(--color-border-subtle);">
                    <div style="font-family: var(--font-heading); font-size: 1.5rem; color: var(--color-forest-moss); font-weight: 700;">02. Verifikasi</div>
                    <p style="font-size: 0.84rem; color: #555E56; margin: 0.4rem 0 0;">Pengecekan koordinat dan validasi visual oleh tim posko karhutla.</p>
                </div>
                <div style="background: #FFFFFF; padding: 1.25rem; border-radius: 10px; border: 1px solid var(--color-border-subtle);">
                    <div style="font-family: var(--font-heading); font-size: 1.5rem; color: var(--color-forest-moss); font-weight: 700;">03. Tindak Lanjut</div>
                    <p style="font-size: 0.84rem; color: #555E56; margin: 0.4rem 0 0;">Pengerahan regu Manggala Agni / BPBD untuk pemadaman langsung.</p>
                </div>
                <div style="background: #FFFFFF; padding: 1.25rem; border-radius: 10px; border: 1px solid var(--color-border-subtle);">
                    <div style="font-family: var(--font-heading); font-size: 1.5rem; color: var(--color-forest-moss); font-weight: 700;">04. Selesai</div>
                    <p style="font-size: 0.84rem; color: #555E56; margin: 0.4rem 0 0;">Pendinginan bara api gambut dan dokumentasi penyelesaian laporan.</p>
                </div>
            </div>
        </div>

    </div>
</section>
@endsection

@push('styles')
<style>
@keyframes spin { 100% { transform: rotate(360deg); } }
.spin-animation { display: inline-block; animation: spin 0.8s linear infinite; vertical-align: middle; }
.region-select-loading { background-color: #F3F4F6 !important; opacity: 0.85; cursor: wait; }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // 1. Elements Wilayah Bertingkat
    const provinceSelect = document.getElementById('province_code');
    const provinceNameInput = document.getElementById('province_name');
    const provinceInput = document.getElementById('province_input');
    const provinceLoading = document.getElementById('provinceLoading');

    const regencySelect = document.getElementById('regency_code');
    const regencyNameInput = document.getElementById('regency_name');
    const regencyInput = document.getElementById('regency_input');
    const regencyLoading = document.getElementById('regencyLoading');

    const districtSelect = document.getElementById('district_code');
    const districtNameInput = document.getElementById('district_name');
    const districtInput = document.getElementById('district_input');
    const districtLoading = document.getElementById('districtLoading');

    const villageSelect = document.getElementById('village_code');
    const villageNameInput = document.getElementById('village_name');
    const villageInput = document.getElementById('village_input');
    const villageLoading = document.getElementById('villageLoading');

    const locationDetail = document.getElementById('location_detail');
    const incidentLocation = document.getElementById('incident_location');
    const regionLoadingGlobal = document.getElementById('regionLoadingGlobal');
    const formPengaduan = document.getElementById('formPengaduan');

    // Nilai awal jika reload setelah error validasi
    const oldProvinceVal = provinceSelect ? (provinceSelect.dataset.oldVal || '') : '';
    const oldProvinceName = provinceSelect ? (provinceSelect.dataset.oldName || '') : '';
    const oldRegencyVal = regencySelect ? (regencySelect.dataset.oldVal || '') : '';
    const oldRegencyName = regencySelect ? (regencySelect.dataset.oldName || '') : '';
    const oldDistrictVal = districtSelect ? (districtSelect.dataset.oldVal || '') : '';
    const oldDistrictName = districtSelect ? (districtSelect.dataset.oldName || '') : '';
    const oldVillageVal = villageSelect ? (villageSelect.dataset.oldVal || '') : '';
    const oldVillageName = villageSelect ? (villageSelect.dataset.oldName || '') : '';

    function showGlobalLoading(show) {
        if (regionLoadingGlobal) {
            regionLoadingGlobal.style.display = show ? 'inline-flex' : 'none';
        }
    }

    async function fetchApi(url) {
        const res = await fetch(url, {
            headers: { 'Accept': 'application/json' }
        });
        if (!res.ok) throw new Error('HTTP ' + res.status);
        return await res.json();
    }

    // Load Daftar Seluruh Provinsi di Indonesia
    async function initProvinces() {
        if (!provinceSelect) return;
        if (provinceLoading) provinceLoading.style.display = 'inline-block';
        showGlobalLoading(true);

        try {
            const data = await fetchApi('{{ route('api.wilayah.provinces') }}');
            provinceSelect.innerHTML = '<option value="" disabled selected>Pilih Provinsi di Kalimantan</option>';

            data.forEach(item => {
                const opt = document.createElement('option');
                opt.value = item.id;
                opt.textContent = item.name;
                opt.dataset.name = item.name;
                provinceSelect.appendChild(opt);
            });

            // Pulihkan old value jika ada
            if (oldProvinceVal || oldProvinceName) {
                let match = Array.from(provinceSelect.options).find(o => 
                    (oldProvinceVal && o.value === oldProvinceVal) || 
                    (oldProvinceName && o.dataset.name && o.dataset.name.toLowerCase() === oldProvinceName.toLowerCase())
                );
                if (match) {
                    provinceSelect.value = match.value;
                    provinceNameInput.value = match.dataset.name || match.text;
                    provinceInput.value = provinceNameInput.value;
                    loadRegencies(match.value, oldRegencyVal, oldRegencyName);
                }
            }
        } catch (err) {
            console.error('Gagal memuat provinsi:', err);
            provinceSelect.innerHTML = '<option value="" disabled selected>Gagal memuat daftar provinsi. Muat ulang halaman.</option>';
        } finally {
            if (provinceLoading) provinceLoading.style.display = 'none';
            showGlobalLoading(false);
        }
    }

    // Load Daftar Kabupaten / Kota berdasarkan Provinsi
    async function loadRegencies(provinceId, autoSelectVal = '', autoSelectName = '') {
        if (!regencySelect) return;
        regencySelect.disabled = true;
        regencySelect.classList.add('region-select-loading');
        regencySelect.innerHTML = '<option value="" disabled selected>Memuat daftar kabupaten/kota...</option>';
        if (regencyLoading) regencyLoading.style.display = 'inline-block';
        showGlobalLoading(true);

        resetDistrict();
        resetVillage();

        try {
            const data = await fetchApi('/api/wilayah/regencies/' + encodeURIComponent(provinceId));
            regencySelect.innerHTML = '<option value="" disabled selected>Pilih Kabupaten / Kota</option>';

            data.forEach(item => {
                const opt = document.createElement('option');
                opt.value = item.id;
                opt.textContent = item.name;
                opt.dataset.name = item.name;
                regencySelect.appendChild(opt);
            });

            regencySelect.disabled = false;
            regencySelect.classList.remove('region-select-loading');

            if (autoSelectVal || autoSelectName) {
                let match = Array.from(regencySelect.options).find(o => 
                    (autoSelectVal && o.value === autoSelectVal) || 
                    (autoSelectName && o.dataset.name && o.dataset.name.toLowerCase() === autoSelectName.toLowerCase())
                );
                if (match) {
                    regencySelect.value = match.value;
                    regencyNameInput.value = match.dataset.name || match.text;
                    regencyInput.value = regencyNameInput.value;
                    loadDistricts(match.value, oldDistrictVal, oldDistrictName);
                }
            }
        } catch (err) {
            console.error('Gagal memuat kabupaten/kota:', err);
            regencySelect.innerHTML = '<option value="" disabled selected>Gagal memuat data kab/kota. Coba lagi.</option>';
        } finally {
            if (regencyLoading) regencyLoading.style.display = 'none';
            showGlobalLoading(false);
        }
    }

    // Load Daftar Kecamatan berdasarkan Kabupaten/Kota
    async function loadDistricts(regencyId, autoSelectVal = '', autoSelectName = '') {
        if (!districtSelect) return;
        districtSelect.disabled = true;
        districtSelect.classList.add('region-select-loading');
        districtSelect.innerHTML = '<option value="" disabled selected>Memuat daftar kecamatan...</option>';
        if (districtLoading) districtLoading.style.display = 'inline-block';
        showGlobalLoading(true);

        resetVillage();

        try {
            const data = await fetchApi('/api/wilayah/districts/' + encodeURIComponent(regencyId));
            districtSelect.innerHTML = '<option value="" disabled selected>Pilih Kecamatan</option>';

            data.forEach(item => {
                const opt = document.createElement('option');
                opt.value = item.id;
                opt.textContent = item.name;
                opt.dataset.name = item.name;
                districtSelect.appendChild(opt);
            });

            districtSelect.disabled = false;
            districtSelect.classList.remove('region-select-loading');

            if (autoSelectVal || autoSelectName) {
                let match = Array.from(districtSelect.options).find(o => 
                    (autoSelectVal && o.value === autoSelectVal) || 
                    (autoSelectName && o.dataset.name && o.dataset.name.toLowerCase() === autoSelectName.toLowerCase())
                );
                if (match) {
                    districtSelect.value = match.value;
                    districtNameInput.value = match.dataset.name || match.text;
                    districtInput.value = districtNameInput.value;
                    loadVillages(match.value, oldVillageVal, oldVillageName);
                }
            }
        } catch (err) {
            console.error('Gagal memuat kecamatan:', err);
            districtSelect.innerHTML = '<option value="" disabled selected>Gagal memuat kecamatan. Coba lagi.</option>';
        } finally {
            if (districtLoading) districtLoading.style.display = 'none';
            showGlobalLoading(false);
        }
    }

    // Load Daftar Desa/Kelurahan berdasarkan Kecamatan
    async function loadVillages(districtId, autoSelectVal = '', autoSelectName = '') {
        if (!villageSelect) return;
        villageSelect.disabled = true;
        villageSelect.classList.add('region-select-loading');
        villageSelect.innerHTML = '<option value="" disabled selected>Memuat daftar desa/kelurahan...</option>';
        if (villageLoading) villageLoading.style.display = 'inline-block';
        showGlobalLoading(true);

        try {
            const data = await fetchApi('/api/wilayah/villages/' + encodeURIComponent(districtId));
            villageSelect.innerHTML = '<option value="" disabled selected>Pilih Desa / Kelurahan</option>';

            data.forEach(item => {
                const opt = document.createElement('option');
                opt.value = item.id;
                opt.textContent = item.name;
                opt.dataset.name = item.name;
                villageSelect.appendChild(opt);
            });

            villageSelect.disabled = false;
            villageSelect.classList.remove('region-select-loading');

            if (autoSelectVal || autoSelectName) {
                let match = Array.from(villageSelect.options).find(o => 
                    (autoSelectVal && o.value === autoSelectVal) || 
                    (autoSelectName && o.dataset.name && o.dataset.name.toLowerCase() === autoSelectName.toLowerCase())
                );
                if (match) {
                    villageSelect.value = match.value;
                    villageNameInput.value = match.dataset.name || match.text;
                    villageInput.value = villageNameInput.value;
                }
            }
        } catch (err) {
            console.error('Gagal memuat desa/kelurahan:', err);
            villageSelect.innerHTML = '<option value="" disabled selected>Gagal memuat desa/kelurahan. Coba lagi.</option>';
        } finally {
            if (villageLoading) villageLoading.style.display = 'none';
            showGlobalLoading(false);
        }
    }

    function resetDistrict() {
        if (!districtSelect) return;
        districtSelect.disabled = true;
        districtSelect.innerHTML = '<option value="" disabled selected>Pilih Kab/Kota terlebih dahulu</option>';
        districtNameInput.value = '';
        districtInput.value = '';
    }

    function resetVillage() {
        if (!villageSelect) return;
        villageSelect.disabled = true;
        villageSelect.innerHTML = '<option value="" disabled selected>Pilih Kecamatan terlebih dahulu</option>';
        villageNameInput.value = '';
        villageInput.value = '';
    }

    // Event Listeners Pergantian Dropdown (Pengguna dapat mengubah pilihan kapan saja)
    if (provinceSelect) {
        provinceSelect.addEventListener('change', function () {
            const selected = provinceSelect.options[provinceSelect.selectedIndex];
            if (selected && selected.value) {
                provinceNameInput.value = selected.dataset.name || selected.text;
                provinceInput.value = provinceNameInput.value;
                loadRegencies(selected.value);
            } else {
                provinceNameInput.value = '';
                provinceInput.value = '';
                if (regencySelect) {
                    regencySelect.disabled = true;
                    regencySelect.innerHTML = '<option value="" disabled selected>Pilih Provinsi terlebih dahulu</option>';
                }
                resetDistrict();
                resetVillage();
            }
        });
    }

    if (regencySelect) {
        regencySelect.addEventListener('change', function () {
            const selected = regencySelect.options[regencySelect.selectedIndex];
            if (selected && selected.value) {
                regencyNameInput.value = selected.dataset.name || selected.text;
                regencyInput.value = regencyNameInput.value;
                loadDistricts(selected.value);
            } else {
                regencyNameInput.value = '';
                regencyInput.value = '';
                resetDistrict();
                resetVillage();
            }
        });
    }

    if (districtSelect) {
        districtSelect.addEventListener('change', function () {
            const selected = districtSelect.options[districtSelect.selectedIndex];
            if (selected && selected.value) {
                districtNameInput.value = selected.dataset.name || selected.text;
                districtInput.value = districtNameInput.value;
                loadVillages(selected.value);
            } else {
                districtNameInput.value = '';
                districtInput.value = '';
                resetVillage();
            }
        });
    }

    if (villageSelect) {
        villageSelect.addEventListener('change', function () {
            const selected = villageSelect.options[villageSelect.selectedIndex];
            if (selected && selected.value) {
                villageNameInput.value = selected.dataset.name || selected.text;
                villageInput.value = villageNameInput.value;
            } else {
                villageNameInput.value = '';
                villageInput.value = '';
            }
        });
    }

    // Inisialisasi awal pemuatan provinsi
    initProvinces();

    // Validasi & Sinkronisasi Lokasi sebelum Submit Form
    if (formPengaduan) {
        formPengaduan.addEventListener('submit', function (e) {
            // Validasi dropdown wajib
            if (provinceSelect && !provinceSelect.value) {
                e.preventDefault();
                alert('Silakan pilih Provinsi lokasi kejadian kebakaran.');
                provinceSelect.focus();
                return false;
            }
            if (regencySelect && !regencySelect.value) {
                e.preventDefault();
                alert('Silakan pilih Kabupaten atau Kota lokasi kejadian kebakaran.');
                regencySelect.focus();
                return false;
            }
            if (districtSelect && !districtSelect.value) {
                e.preventDefault();
                alert('Silakan pilih Kecamatan lokasi kejadian kebakaran.');
                districtSelect.focus();
                return false;
            }
            if (villageSelect && !villageSelect.value) {
                e.preventDefault();
                alert('Silakan pilih Desa atau Kelurahan lokasi kejadian kebakaran.');
                villageSelect.focus();
                return false;
            }

            // Susun teks lokasi kejadian
            const detailText = locationDetail ? locationDetail.value.trim() : '';
            if (detailText) {
                if (incidentLocation) incidentLocation.value = detailText;
            } else {
                const parts = [];
                if (villageNameInput && villageNameInput.value) parts.push('Desa ' + villageNameInput.value);
                if (districtNameInput && districtNameInput.value) parts.push('Kec. ' + districtNameInput.value);
                if (regencyNameInput && regencyNameInput.value) parts.push(regencyNameInput.value);
                if (provinceNameInput && provinceNameInput.value) parts.push(provinceNameInput.value);
                if (incidentLocation) incidentLocation.value = parts.join(', ');
            }
        });
    }

    // GPS Geolocation Handler (Tetap dipertahankan)
    const btnGetGps = document.getElementById('btnGetGps');
    const latInput = document.getElementById('latitude');
    const lonInput = document.getElementById('longitude');
    const gpsStatusText = document.getElementById('gpsStatusText');

    if (btnGetGps && navigator.geolocation) {
        btnGetGps.addEventListener('click', function () {
            gpsStatusText.style.display = 'block';
            gpsStatusText.style.color = 'var(--color-forest-moss)';
            gpsStatusText.textContent = 'Mendeteksi titik koordinat perangkat...';

            navigator.geolocation.getCurrentPosition(
                function (position) {
                    latInput.value = position.coords.latitude.toFixed(6);
                    lonInput.value = position.coords.longitude.toFixed(6);
                    gpsStatusText.textContent = 'Koordinat GPS berhasil diperoleh (' + position.coords.latitude.toFixed(4) + ', ' + position.coords.longitude.toFixed(4) + ').';
                },
                function (error) {
                    gpsStatusText.style.color = '#B83B26';
                    switch(error.code) {
                        case error.PERMISSION_DENIED:
                            gpsStatusText.textContent = 'Izin akses lokasi GPS ditolak pada browser.';
                            break;
                        case error.POSITION_UNAVAILABLE:
                            gpsStatusText.textContent = 'Informasi lokasi tidak tersedia saat ini.';
                            break;
                        case error.TIMEOUT:
                            gpsStatusText.textContent = 'Waktu permintaan lokasi GPS habis (timeout).';
                            break;
                        default:
                            gpsStatusText.textContent = 'Gagal mendeteksi lokasi GPS.';
                    }
                },
                { timeout: 10000, enableHighAccuracy: true }
            );
        });
    }
});
</script>
@endpush
