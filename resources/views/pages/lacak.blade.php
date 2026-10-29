@extends('layouts.app')

@section('title', 'Lacak Status Pengaduan | BORNEO SIAGA')
@section('meta_description', 'Pantau status tindak lanjut penanganan laporan kejadian karhutla secara transparan dengan nomor tiket resmi dan kode akses.')

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
                    Transparansi Penanganan
                </span>
                <span style="display: inline-block; width: 20px; height: 1.5px; background-color: var(--color-earth-sand);"></span>
            </div>

            <h1 style="color: #FFFFFF; font-size: clamp(1.75rem, 3.2vw, 2.4rem); font-weight: 600; line-height: 1.2; margin-bottom: 0.45rem; letter-spacing: -0.015em;">
                Lacak Status Pengaduan
            </h1>

            <p style="color: #DDE7E1; font-size: 0.95rem; line-height: 1.55; margin: 0 auto; max-width: 640px;">
                Masukkan nomor tiket resmi dan kode akses yang Anda peroleh saat mengirim laporan untuk melihat progres verifikasi dan aksi lapangan tim posko siaga.
            </p>
        </div>
    </div>
</section>

<!-- Content Section -->
<section style="padding: 2.75rem 0 5rem; background-color: var(--color-warm-paper);">
    <div class="container-editorial">

        <!-- =========================================================================
             A. FORMULIR PENCARIAN TIKET DENGAN KODE AKSES
             ========================================================================= -->
        <div style="max-width: 760px; margin: 0 auto 3rem; background: #FFFFFF; border: 1px solid var(--color-border-subtle); border-radius: 14px; padding: 2.25rem 2rem; box-shadow: var(--shadow-editorial);">
            <div style="display: flex; align-items: center; gap: 0.85rem; margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 1px solid var(--color-border-subtle);">
                <div style="width: 42px; height: 42px; border-radius: 8px; background-color: var(--color-sand-soft); color: var(--color-deep-forest); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                </div>
                <div>
                    <h3 style="font-size: 1.3rem; margin: 0 0 0.2rem 0; color: var(--color-deep-forest);">Penelusuran Tiket Laporan</h3>
                    <p style="font-size: 0.86rem; color: #5B645D; margin: 0;">Verifikasi ganda nomor tiket dan kode akses rahasia (tanpa perlu masuk akun).</p>
                </div>
            </div>

            <!-- Generic Search Error Alert (Anti-Enumeration) -->
            @if(isset($searchError) && $searchError)
                <div style="background: #FDEDED; border: 1px solid #F5C2C7; border-radius: 8px; padding: 0.95rem 1.25rem; color: #842029; font-size: 0.88rem; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.75rem; line-height: 1.5;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink: 0;"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    <span>{{ $searchError }}</span>
                </div>
            @endif

            <!-- Validation Errors Alert -->
            @if ($errors->any())
                <div style="background: #FDEDED; border: 1px solid #F5C2C7; border-radius: 8px; padding: 0.95rem 1.25rem; color: #842029; font-size: 0.88rem; margin-bottom: 1.5rem;">
                    <div style="font-weight: 700; margin-bottom: 0.35rem;">Mohon lengkapi formulir pelacakan:</div>
                    <ul style="margin: 0; padding-left: 1.25rem; line-height: 1.55;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('lacak.pengaduan.track') }}" method="POST" novalidate id="formLacak">
                @csrf

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.25rem; margin-bottom: 1.5rem;">
                    <!-- Nomor Tiket -->
                    <div>
                        <label for="ticket_number" style="display: block; font-weight: 600; font-size: 0.88rem; color: var(--color-deep-forest); margin-bottom: 0.4rem;">
                            Nomor Tiket Pengaduan <span style="color: #B83B26;">*</span>
                        </label>
                        <input type="text" 
                               id="ticket_number" 
                               name="ticket_number" 
                               value="{{ old('ticket_number', $searchedTicket ?? '') }}" 
                               placeholder="Contoh: BS-20261027-XXXXX" 
                               required 
                               autocomplete="off"
                               style="width: 100%; padding: 0.75rem 1rem; border: 1px solid {{ $errors->has('ticket_number') ? '#B83B26' : 'var(--color-border-subtle)' }}; border-radius: 8px; font-family: monospace; font-size: 0.95rem; text-transform: uppercase; background: #FAF9F5;">
                        @error('ticket_number')
                            <span style="font-size: 0.78rem; color: #B83B26; margin-top: 0.3rem; display: block;">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Kode Akses -->
                    <div>
                        <label for="access_code" style="display: block; font-weight: 600; font-size: 0.88rem; color: var(--color-deep-forest); margin-bottom: 0.4rem;">
                            Kode Akses Rahasia <span style="color: #B83B26;">*</span>
                        </label>
                        <input type="text" 
                               id="access_code" 
                               name="access_code" 
                               value="" 
                               placeholder="Masukkan 8 karakter kode rahasia" 
                               required 
                               autocomplete="off"
                               style="width: 100%; padding: 0.75rem 1rem; border: 1px solid {{ $errors->has('access_code') ? '#B83B26' : 'var(--color-border-subtle)' }}; border-radius: 8px; font-family: monospace; font-size: 0.95rem; text-transform: uppercase; background: #FAF9F5;">
                        @error('access_code')
                            <span style="font-size: 0.78rem; color: #B83B26; margin-top: 0.3rem; display: block;">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
                    <span style="font-size: 0.78rem; color: #6D7870; max-width: 420px; line-height: 1.45;">
                        Kode akses diberikan saat pertama kali laporan Anda disimpan. Demi keamanan, kode akses tidak dikirimkan ke email publik.
                    </span>
                    <button type="submit" id="btnSubmitTrack" class="btn btn-forest" style="padding: 0.75rem 1.6rem; font-size: 0.92rem;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" id="iconTrackSearch">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                        <span id="textTrackSubmit">Lacak Laporan</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- =========================================================================
             B. HASIL PENELUSURAN RIIL DARI DATABASE
             Displayed only when report is verified
             ========================================================================= -->
        @if(isset($trackedReport) && $trackedReport)
            <div style="max-width: 820px; margin: 0 auto 3.5rem; background: #FFFFFF; border: 1px solid var(--color-border-subtle); border-radius: 14px; overflow: hidden; box-shadow: var(--shadow-editorial);">
                <!-- Header Status Panel -->
                <div style="background-color: var(--color-deep-forest); color: var(--color-warm-paper); padding: 1.75rem 2rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
                    <div>
                        <span style="font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.1em; color: var(--color-earth-sand); font-weight: 700; display: block; margin-bottom: 0.25rem;">
                            Laporan Terverifikasi
                        </span>
                        <div style="font-family: monospace; font-size: 1.45rem; font-weight: 700; color: #FFFFFF; letter-spacing: 0.04em;">
                            {{ $trackedReport->ticket_number }}
                        </div>
                    </div>

                    <div>
                        @php
                            $st = strtolower($trackedReport->status);
                        @endphp
                        @if($st === 'selesai' || $st === 'selesai ditangani')
                            <span class="badge badge-forest" style="font-size: 0.88rem; padding: 0.45rem 1.15rem; background: #2E5A44; color: #FFFFFF; border-color: #4E8668;">
                                Selesai Ditangani
                            </span>
                        @elseif($st === 'ditindaklanjuti' || $st === 'dalam penanganan')
                            <span class="badge badge-alert" style="font-size: 0.88rem; padding: 0.45rem 1.15rem; background: #B83B26; color: #FFFFFF; border-color: #B83B26;">
                                Dalam Penanganan
                            </span>
                        @elseif($st === 'dalam_verifikasi' || $st === 'sedang diverifikasi')
                            <span class="badge badge-sand" style="font-size: 0.88rem; padding: 0.45rem 1.15rem; color: #935B0D; border-color: #D4AC0D;">
                                Sedang Diverifikasi
                            </span>
                        @elseif($st === 'ditolak')
                            <span class="badge" style="font-size: 0.88rem; padding: 0.45rem 1.15rem; background: #FDEDED; color: #842029; border: 1px solid #F5C2C7;">
                                Ditolak
                            </span>
                        @else
                            <span class="badge badge-sand" style="font-size: 0.88rem; padding: 0.45rem 1.15rem; color: #935B0D; border-color: #D4AC0D;">
                                Menunggu Verifikasi
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Visual Timeline Stepper -->
                @php
                    $stepIndex = 1;
                    if ($st === 'dalam_verifikasi' || $st === 'sedang diverifikasi') $stepIndex = 2;
                    elseif ($st === 'ditindaklanjuti' || $st === 'dalam penanganan') $stepIndex = 3;
                    elseif ($st === 'selesai' || $st === 'selesai ditangani') $stepIndex = 4;
                    elseif ($st === 'ditolak') $stepIndex = -1;
                @endphp

                @if($stepIndex > 0)
                    <div style="background: var(--color-warm-paper); border-bottom: 1px solid var(--color-border-subtle); padding: 1.25rem 2rem;">
                        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 0.5rem; position: relative;">
                            @php
                                $steps = [
                                    1 => 'Menunggu Verifikasi',
                                    2 => 'Sedang Diverifikasi',
                                    3 => 'Dalam Penanganan',
                                    4 => 'Selesai Ditangani',
                                ];
                            @endphp
                            @foreach($steps as $idx => $label)
                                <div style="text-align: center;">
                                    <div style="width: 28px; height: 28px; border-radius: 50%; margin: 0 auto 0.35rem; display: flex; align-items: center; justify-content: center; font-size: 0.78rem; font-weight: 700;
                                        background-color: {{ $idx <= $stepIndex ? 'var(--color-deep-forest)' : '#E0DCD1' }};
                                        color: {{ $idx <= $stepIndex ? 'var(--color-earth-sand)' : '#808981' }};">
                                        @if($idx < $stepIndex)
                                            &#10003;
                                        @else
                                            {{ $idx }}
                                        @endif
                                    </div>
                                    <div style="font-size: 0.75rem; font-weight: {{ $idx === $stepIndex ? '700' : '500' }}; color: {{ $idx === $stepIndex ? 'var(--color-deep-forest)' : '#69746A' }};">
                                        {{ $label }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Detail Metadata -->
                <div style="padding: 2rem;">
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(230px, 1fr)); gap: 1.5rem; margin-bottom: 2rem; border-bottom: 1px solid var(--color-border-subtle); padding-bottom: 1.75rem;">
                        <!-- Lokasi Kejadian -->
                        <div>
                            <span style="font-size: 0.76rem; text-transform: uppercase; letter-spacing: 0.06em; color: var(--color-forest-moss); font-weight: 700; display: block; margin-bottom: 0.25rem;">
                                Lokasi Kejadian
                            </span>
                            <div style="font-weight: 600; font-size: 1.05rem; color: var(--color-deep-forest);">
                                {{ $trackedReport->incident_location }}
                            </div>
                            @if($trackedReport->province || $trackedReport->regency)
                                <span style="font-size: 0.8rem; color: #647065; display: block; margin-top: 0.2rem;">
                                    {{ $trackedReport->regency ? $trackedReport->regency . ', ' : '' }}{{ $trackedReport->province }}
                                </span>
                            @endif
                        </div>

                        <!-- Jenis Kejadian -->
                        <div>
                            <span style="font-size: 0.76rem; text-transform: uppercase; letter-spacing: 0.06em; color: var(--color-forest-moss); font-weight: 700; display: block; margin-bottom: 0.25rem;">
                                Jenis Kejadian
                            </span>
                            <div style="font-weight: 600; font-size: 1rem; color: var(--color-deep-forest);">
                                {{ $trackedReport->incident_type ?? 'Kebakaran Hutan / Lahan' }}
                            </div>
                        </div>

                        <!-- Waktu Kejadian -->
                        <div>
                            <span style="font-size: 0.76rem; text-transform: uppercase; letter-spacing: 0.06em; color: var(--color-forest-moss); font-weight: 700; display: block; margin-bottom: 0.25rem;">
                                Perkiraan Waktu Kejadian
                            </span>
                            <div style="font-weight: 500; font-size: 0.95rem; color: #434C44;">
                                {{ $trackedReport->incident_time ? $trackedReport->incident_time->translatedFormat('d F Y, H:i') . ' WIB' : '-' }}
                            </div>
                        </div>

                        <!-- Tanggal Laporan Dibuat -->
                        <div>
                            <span style="font-size: 0.76rem; text-transform: uppercase; letter-spacing: 0.06em; color: var(--color-forest-moss); font-weight: 700; display: block; margin-bottom: 0.25rem;">
                                Tanggal Laporan Dibuat
                            </span>
                            <div style="font-weight: 500; font-size: 0.95rem; color: #434C44;">
                                {{ $trackedReport->created_at->translatedFormat('d F Y, H:i') }} WIB
                            </div>
                        </div>

                        <!-- Koordinat GPS -->
                        <div>
                            <span style="font-size: 0.76rem; text-transform: uppercase; letter-spacing: 0.06em; color: var(--color-forest-moss); font-weight: 700; display: block; margin-bottom: 0.25rem;">
                                Koordinat GPS
                            </span>
                            <div style="font-weight: 500; font-size: 0.92rem; color: #434C44; font-family: monospace;">
                                @if($trackedReport->latitude && $trackedReport->longitude)
                                    {{ number_format($trackedReport->latitude, 4) }}, {{ number_format($trackedReport->longitude, 4) }}
                                @else
                                    <span style="color: #768378; font-family: var(--font-body);">Tercatat berdasarkan patokan alamat</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Deskripsi Situasi yang Aman Ditampilkan -->
                    <div style="margin-bottom: 2rem; background: var(--color-warm-paper); border: 1px solid var(--color-border-subtle); border-radius: 10px; padding: 1.25rem 1.5rem;">
                        <span style="font-size: 0.76rem; text-transform: uppercase; letter-spacing: 0.06em; color: var(--color-forest-moss); font-weight: 700; display: block; margin-bottom: 0.4rem;">
                            Deskripsi Situasi Kejadian
                        </span>
                        <p style="font-size: 0.92rem; color: #3B443D; line-height: 1.65; margin: 0;">
                            {{ $trackedReport->description }}
                        </p>
                    </div>

                    <!-- Informasi Koordinasi Tindak Lanjut Lapangan (Privasi Terjaga) -->
                    @if($trackedReport->dispatches && $trackedReport->dispatches->isNotEmpty())
                        <div style="margin-bottom: 2rem; background: #ECFDF5; border: 1px solid #A7F3D0; border-left: 4px solid #10B981; border-radius: 8px; padding: 1.25rem 1.5rem;">
                            <span style="font-size: 0.76rem; text-transform: uppercase; letter-spacing: 0.06em; color: #065F46; font-weight: 700; display: block; margin-bottom: 0.35rem;">
                                Koordinasi Lintas Instansi & Regu Lapangan
                            </span>
                            <p style="font-size: 0.9rem; color: #064E3B; margin: 0; line-height: 1.5;">
                                Laporan ini telah diteruskan oleh Posko Siaga ke instansi berwenang/regu patroli wilayah <strong>{{ $trackedReport->regency ?: $trackedReport->province }}</strong>. 
                                Tahap tindak lanjut lapangan saat ini: <em>{{ $trackedReport->dispatches->first()->recipient_status }}</em>.
                            </p>
                        </div>
                    @endif

                    <!-- =========================================================================
                         RIWAYAT STATUS TINDAK LANJUT RIIL DARI DATABASE
                         ========================================================================= -->
                    <div>
                        <h4 style="font-size: 1.15rem; color: var(--color-deep-forest); margin: 0 0 1.25rem 0;">
                            Riwayat Progres & Catatan Posko Siaga
                        </h4>

                        @if($trackedReport->statusHistories && $trackedReport->statusHistories->isNotEmpty())
                            <div style="display: flex; flex-direction: column; gap: 1rem; position: relative;">
                                @foreach($trackedReport->statusHistories as $history)
                                    <div style="display: flex; gap: 1rem; align-items: flex-start; background: #FFFFFF; border: 1px solid var(--color-border-subtle); border-radius: 8px; padding: 1rem 1.25rem;">
                                        <div style="width: 10px; height: 10px; border-radius: 50%; background-color: var(--color-forest-moss); margin-top: 6px; flex-shrink: 0;"></div>
                                        <div style="flex-grow: 1;">
                                            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.5rem; margin-bottom: 0.35rem;">
                                                <span style="font-weight: 700; color: var(--color-deep-forest); font-size: 0.95rem;">
                                                    Status: {{ $history->status_label }}
                                                </span>
                                                <span style="font-size: 0.8rem; color: #768378;">
                                                    {{ $history->created_at->translatedFormat('d M Y, H:i') }} WIB
                                                </span>
                                            </div>
                                            <p style="font-size: 0.88rem; color: #515B53; line-height: 1.55; margin: 0;">
                                                {{ $history->notes ?: 'Status diperbarui oleh sistem posko karhutla.' }}
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div style="background: var(--color-warm-paper); border: 1px dashed var(--color-border-subtle); border-radius: 8px; padding: 1.25rem; font-size: 0.88rem; color: #647065; text-align: center;">
                                Riwayat tindak lanjut belum tercatat di sistem posko. Laporan ini baru masuk dan segera diproses oleh tim verifikasi.
                            </div>
                        @endif
                    </div>

                    <!-- Privacy Assurance Footer -->
                    <div style="margin-top: 2rem; padding-top: 1.25rem; border-top: 1px solid var(--color-border-subtle); font-size: 0.8rem; color: #768378; display: flex; align-items: center; gap: 0.5rem;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink: 0;"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                        <span>Untuk melindungi privasi dan keselamatan pelapor, identitas pribadi pelapor (nama, nomor kontak) serta foto bukti teknis tidak diekspos pada modul pelacakan publik.</span>
                    </div>

                    <div style="margin-top: 1.5rem; display: flex; gap: 1rem; flex-wrap: wrap;">
                        <a href="{{ route('lacak.pengaduan') }}" class="btn btn-outline-forest" style="padding: 0.65rem 1.3rem; font-size: 0.88rem;">
                            <span>&larr; Lacak Laporan Lain</span>
                        </a>
                        <a href="{{ route('pantauan.karhutla') }}" class="btn btn-forest" style="padding: 0.65rem 1.3rem; font-size: 0.88rem;">
                            <span>Pantau Titik Panas di Peta</span>
                        </a>
                    </div>
                </div>
            </div>
        @endif

        <!-- Stage Timeline Overview Reference -->
        <div style="max-width: 860px; margin: 0 auto;">
            <h4 style="text-align: center; margin-bottom: 1.5rem; color: var(--color-deep-forest);">
                Tahapan Standar Penanganan Laporan Borneo Siaga
            </h4>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(190px, 1fr)); gap: 1rem;">
                <div style="background: var(--color-paper-card); padding: 1.25rem; border-radius: 10px; border: 1px solid var(--color-border-subtle); text-align: center;">
                    <span class="badge badge-sand" style="margin-bottom: 0.5rem;">Tahap 1</span>
                    <div style="font-weight: 600; color: var(--color-deep-forest); font-size: 0.95rem;">Menunggu Verifikasi</div>
                    <div style="font-size: 0.8rem; color: #666; margin-top: 0.25rem;">Validasi data awal dan pemeriksaan kelengkapan lokasi.</div>
                </div>

                <div style="background: var(--color-paper-card); padding: 1.25rem; border-radius: 10px; border: 1px solid var(--color-border-subtle); text-align: center;">
                    <span class="badge badge-sand" style="margin-bottom: 0.5rem;">Tahap 2</span>
                    <div style="font-weight: 600; color: var(--color-deep-forest); font-size: 0.95rem;">Sedang Diverifikasi</div>
                    <div style="font-size: 0.8rem; color: #666; margin-top: 0.25rem;">Pencocokan koordinat dengan satelit & konfirmasi posko.</div>
                </div>

                <div style="background: var(--color-paper-card); padding: 1.25rem; border-radius: 10px; border: 1px solid var(--color-border-subtle); text-align: center;">
                    <span class="badge badge-alert" style="margin-bottom: 0.5rem;">Tahap 3</span>
                    <div style="font-weight: 600; color: var(--color-deep-forest); font-size: 0.95rem;">Dalam Penanganan</div>
                    <div style="font-size: 0.8rem; color: #666; margin-top: 0.25rem;">Pengerahan regu Manggala Agni / BPBD ke lapangan.</div>
                </div>

                <div style="background: var(--color-paper-card); padding: 1.25rem; border-radius: 10px; border: 1px solid var(--color-border-subtle); text-align: center;">
                    <span class="badge badge-forest" style="margin-bottom: 0.5rem;">Tahap 4</span>
                    <div style="font-weight: 600; color: var(--color-deep-forest); font-size: 0.95rem;">Selesai Ditangani</div>
                    <div style="font-size: 0.8rem; color: #666; margin-top: 0.25rem;">Pemadaman bara gambut tuntas & penyusunan laporan akhir.</div>
                </div>
            </div>
        </div>

    </div>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const formLacak = document.getElementById('formLacak');
    const btnSubmitTrack = document.getElementById('btnSubmitTrack');
    const textTrackSubmit = document.getElementById('textTrackSubmit');
    const iconTrackSearch = document.getElementById('iconTrackSearch');

    if (formLacak && btnSubmitTrack) {
        formLacak.addEventListener('submit', function () {
            btnSubmitTrack.disabled = true;
            if (textTrackSubmit) textTrackSubmit.textContent = 'Memverifikasi...';
            if (iconTrackSearch) {
                iconTrackSearch.style.animation = 'spin 1s linear infinite';
            }
        });
    }
});
</script>
@endpush
