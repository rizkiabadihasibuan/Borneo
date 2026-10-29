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
                                <div><strong>Wilayah:</strong> {{ session('report_summary.regency') }}, {{ session('report_summary.province') }}</div>
                                <div><strong>Jenis Kejadian:</strong> {{ session('report_summary.incident_type') }}</div>
                                <div><strong>Waktu Kejadian:</strong> {{ date('d M Y, H:i', strtotime(session('report_summary.incident_time'))) }} WIB</div>
                                <div style="grid-column: 1 / -1;"><strong>Lokasi:</strong> {{ session('report_summary.incident_location') }}</div>
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

                <!-- SEKSI 2: WILAYAH ADMINISTRASI -->
                <div style="margin-bottom: 2rem;">
                    <h4 style="font-size: 1.05rem; color: var(--color-deep-forest); margin-bottom: 1rem; padding-bottom: 0.4rem; border-bottom: 1px solid var(--color-border-subtle);">
                        2. Wilayah Administrasi Kejadian
                    </h4>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.25rem; margin-bottom: 1.25rem;">
                        <!-- Provinsi -->
                        <div>
                            <label for="province" style="display: block; font-weight: 600; font-size: 0.9rem; color: var(--color-deep-forest); margin-bottom: 0.4rem;">
                                Provinsi <span style="color: #B83B26;">*</span>
                            </label>
                            <select id="province" 
                                    name="province" 
                                    required 
                                    style="width: 100%; padding: 0.75rem 1rem; border: 1px solid {{ $errors->has('province') ? '#B83B26' : 'var(--color-border-subtle)' }}; border-radius: 8px; font-family: var(--font-body); font-size: 0.92rem; background: #FAF9F5; color: var(--color-charcoal);">
                                <option value="" disabled {{ old('province') ? '' : 'selected' }}>Pilih Provinsi Kalimantan</option>
                                <option value="Kalimantan Barat" {{ old('province') === 'Kalimantan Barat' ? 'selected' : '' }}>Kalimantan Barat</option>
                                <option value="Kalimantan Tengah" {{ old('province') === 'Kalimantan Tengah' ? 'selected' : '' }}>Kalimantan Tengah</option>
                                <option value="Kalimantan Selatan" {{ old('province') === 'Kalimantan Selatan' ? 'selected' : '' }}>Kalimantan Selatan</option>
                                <option value="Kalimantan Timur" {{ old('province') === 'Kalimantan Timur' ? 'selected' : '' }}>Kalimantan Timur</option>
                                <option value="Kalimantan Utara" {{ old('province') === 'Kalimantan Utara' ? 'selected' : '' }}>Kalimantan Utara</option>
                            </select>
                            @error('province')
                                <span style="font-size: 0.78rem; color: #B83B26; margin-top: 0.3rem; display: block;">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Kabupaten / Kota -->
                        <div>
                            <label for="regency" style="display: block; font-weight: 600; font-size: 0.9rem; color: var(--color-deep-forest); margin-bottom: 0.4rem;">
                                Kabupaten / Kota <span style="color: #B83B26;">*</span>
                            </label>
                            <input type="text" 
                                   id="regency" 
                                   name="regency" 
                                   value="{{ old('regency') }}" 
                                   placeholder="Contoh: Kab. Kubu Raya" 
                                   required 
                                   style="width: 100%; padding: 0.75rem 1rem; border: 1px solid {{ $errors->has('regency') ? '#B83B26' : 'var(--color-border-subtle)' }}; border-radius: 8px; font-family: var(--font-body); font-size: 0.92rem; background: #FAF9F5;">
                            @error('regency')
                                <span style="font-size: 0.78rem; color: #B83B26; margin-top: 0.3rem; display: block;">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.25rem;">
                        <!-- Kecamatan (Opsional) -->
                        <div>
                            <label for="district" style="display: block; font-weight: 600; font-size: 0.9rem; color: var(--color-deep-forest); margin-bottom: 0.4rem;">
                                Kecamatan <span style="font-size: 0.78rem; color: #69766C; font-weight: 400;">(Opsional)</span>
                            </label>
                            <input type="text" 
                                   id="district" 
                                   name="district" 
                                   value="{{ old('district') }}" 
                                   placeholder="Contoh: Kec. Rasau Jaya" 
                                   style="width: 100%; padding: 0.75rem 1rem; border: 1px solid var(--color-border-subtle); border-radius: 8px; font-family: var(--font-body); font-size: 0.92rem; background: #FAF9F5;">
                        </div>

                        <!-- Desa / Kelurahan (Opsional) -->
                        <div>
                            <label for="village" style="display: block; font-weight: 600; font-size: 0.9rem; color: var(--color-deep-forest); margin-bottom: 0.4rem;">
                                Desa / Kelurahan <span style="font-size: 0.78rem; color: #69766C; font-weight: 400;">(Opsional)</span>
                            </label>
                            <input type="text" 
                                   id="village" 
                                   name="village" 
                                   value="{{ old('village') }}" 
                                   placeholder="Contoh: Desa Rasau Jaya Tiga" 
                                   style="width: 100%; padding: 0.75rem 1rem; border: 1px solid var(--color-border-subtle); border-radius: 8px; font-family: var(--font-body); font-size: 0.92rem; background: #FAF9F5;">
                        </div>
                    </div>
                </div>

                <!-- SEKSI 3: LOKASI KEJADIAN & KOORDINAT -->
                <div style="margin-bottom: 2rem;">
                    <h4 style="font-size: 1.05rem; color: var(--color-deep-forest); margin-bottom: 1rem; padding-bottom: 0.4rem; border-bottom: 1px solid var(--color-border-subtle);">
                        3. Lokasi Spesifik Kejadian
                    </h4>

                    <!-- Alamat / Patokan Lokasi -->
                    <div style="margin-bottom: 1.25rem;">
                        <label for="incident_location" style="display: block; font-weight: 600; font-size: 0.9rem; color: var(--color-deep-forest); margin-bottom: 0.4rem;">
                            Alamat / Keterangan Patokan Lokasi <span style="color: #B83B26;">*</span>
                        </label>
                        <input type="text" 
                               id="incident_location" 
                               name="incident_location" 
                               value="{{ old('incident_location') }}" 
                               placeholder="Contoh: Jl. Poros Desa RT 04 RW 02, sekitar 300 meter di belakang batas kanal sawit" 
                               required 
                               style="width: 100%; padding: 0.75rem 1rem; border: 1px solid {{ $errors->has('incident_location') ? '#B83B26' : 'var(--color-border-subtle)' }}; border-radius: 8px; font-family: var(--font-body); font-size: 0.92rem; background: #FAF9F5;">
                        @error('incident_location')
                            <span style="font-size: 0.78rem; color: #B83B26; margin-top: 0.3rem; display: block;">{{ $message }}</span>
                        @else
                            <span style="font-size: 0.78rem; color: #69766C; margin-top: 0.3rem; display: block;">
                                Tuliskan nama jalan, patokan jembatan, batas parit/kanal, atau arah dari pemukiman warga terdekat.
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

                <!-- SEKSI 4: WAKTU & KARAKTERISTIK KEBAKARAN -->
                <div style="margin-bottom: 2rem;">
                    <h4 style="font-size: 1.05rem; color: var(--color-deep-forest); margin-bottom: 1rem; padding-bottom: 0.4rem; border-bottom: 1px solid var(--color-border-subtle);">
                        4. Waktu & Karakteristik Kejadian
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
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
