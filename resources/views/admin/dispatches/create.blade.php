@extends('admin.layout')

@section('title', 'Teruskan Laporan ' . $report->ticket_number . ' - Posko BORNEO SIAGA')
@section('header_title', 'Penerusan Laporan Karhutla Lintas Kanal')

@section('admin_content')
    <div style="max-width: 950px; margin: 0 auto;">
        
        <!-- Back Link -->
        <div style="margin-bottom: 1.5rem;">
            <a href="{{ route('admin.reports.show', $report) }}" style="display: inline-flex; align-items: center; gap: 0.4rem; color: var(--color-forest-moss); font-weight: 600; font-size: 0.88rem; text-decoration: none;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                <span>Kembali ke Detail Pengaduan ({{ $report->ticket_number }})</span>
            </a>
        </div>

        <!-- Form Card -->
        <div style="background: #FFFFFF; border: 1px solid var(--color-border-subtle); border-radius: 12px; overflow: hidden; box-shadow: var(--shadow-editorial);">
            <div style="background-color: var(--color-deep-forest); color: var(--color-warm-paper); padding: 1.75rem 2rem;">
                <span style="font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.1em; color: var(--color-earth-sand); font-weight: 700; display: block; margin-bottom: 0.25rem;">
                    Sistem Penerusan Resmi Borneo Siaga
                </span>
                <h2 style="font-family: var(--font-heading); font-size: 1.75rem; font-weight: 700; color: #FFFFFF; margin: 0; line-height: 1.2;">
                    Penerusan Laporan: {{ $report->ticket_number }}
                </h2>
                <span style="font-size: 0.82rem; color: #cbd9ce; display: block; margin-top: 0.35rem;">
                    Teruskan ringkasan informasi lapangan secara resmi ke instansi pemerintah dan kelompok MPA melalui Email dan WhatsApp Cloud API
                </span>
            </div>

            <!-- Ringkasan Informasi Laporan Asal -->
            <div style="background: #FAF9F5; border-bottom: 1px solid var(--color-border-subtle); padding: 1.5rem 2rem;">
                <span style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.08em; color: #526B4D; font-weight: 700; display: block; margin-bottom: 0.75rem;">
                    Ringkasan Data Kejadian yang Akan Diteruskan:
                </span>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; font-size: 0.88rem;">
                    <div>
                        <span style="color: #6D7870; font-size: 0.76rem; display: block;">Jenis Kejadian:</span>
                        <strong style="color: var(--color-deep-forest);">{{ $report->incident_type }}</strong>
                    </div>
                    <div>
                        <span style="color: #6D7870; font-size: 0.76rem; display: block;">Waktu Kejadian:</span>
                        <span style="color: var(--color-charcoal);">{{ $report->incident_time ? $report->incident_time->format('d/m/Y H:i') . ' WIB' : '-' }}</span>
                    </div>
                    <div>
                        <span style="color: #6D7870; font-size: 0.76rem; display: block;">Wilayah Administratif:</span>
                        <span style="color: var(--color-charcoal); font-weight: 600;">{{ $report->regency }}, {{ $report->province }}</span>
                    </div>
                    <div>
                        <span style="color: #6D7870; font-size: 0.76rem; display: block;">Koordinat GPS:</span>
                        <span style="font-family: monospace; font-size: 0.82rem; color: #526B4D;">
                            {{ $report->latitude && $report->longitude ? $report->latitude . ', ' . $report->longitude : 'Belum tercatat' }}
                        </span>
                    </div>
                </div>
                <div style="margin-top: 0.75rem; font-size: 0.85rem; color: #4B5563;">
                    <strong>Lokasi / Patokan:</strong> {{ $report->incident_location }}
                </div>
            </div>

            <form action="{{ route('admin.dispatches.store', $report) }}" method="POST" id="formDispatch" style="padding: 2rem;">
                @csrf

                <!-- Pilihan Kanal Pengiriman Resmi -->
                <div style="margin-bottom: 2rem;">
                    <label style="display: block; font-weight: 700; font-size: 0.92rem; color: var(--color-deep-forest); margin-bottom: 0.5rem;">
                        1. Pilih Kanal Penerusan Resmi <span style="color: #B83B26;">*</span>
                    </label>
                    <p style="font-size: 0.82rem; color: #6D7870; margin: 0 0 1rem 0;">
                        Pilih satu atau kedua kanal resmi di bawah ini. Jika memilih kedua kanal, sistem akan membuat catatan pengiriman dan pemantauan status terpisah untuk masing-masing kanal.
                    </p>

                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 1rem;">
                        <!-- Kanal Email -->
                        <label style="display: flex; align-items: flex-start; gap: 0.75rem; padding: 1.15rem; border: 2px solid #D6C5A3; border-radius: 10px; background: #FAF9F5; cursor: pointer; transition: all 0.15s ease;">
                            <input type="checkbox" name="channels[]" value="email" {{ in_array('email', old('channels', ['email'])) ? 'checked' : '' }} style="accent-color: var(--color-forest-moss); width: 18px; height: 18px; margin-top: 0.2rem;" class="channel-check">
                            <div>
                                <span style="font-weight: 700; font-size: 0.95rem; color: #1E40AF; display: flex; align-items: center; gap: 0.4rem;">
                                    ✉️ Surat Elektronik (Email)
                                </span>
                                <span style="font-size: 0.78rem; color: #4B5563; display: block; margin-top: 0.25rem; line-height: 1.4;">
                                    Template resmi Borneo Siaga via SMTP dengan ringkasan laporan dan tautan verifikasi lapangan.
                                </span>
                            </div>
                        </label>

                        <!-- Kanal WhatsApp Resmi -->
                        <label style="display: flex; align-items: flex-start; gap: 0.75rem; padding: 1.15rem; border: 2px solid {{ $whatsappConfigured ? '#A7F3D0' : '#E5E7EB' }}; border-radius: 10px; background: {{ $whatsappConfigured ? '#F0FDF4' : '#F9FAFB' }}; cursor: pointer; transition: all 0.15s ease;">
                            <input type="checkbox" name="channels[]" value="whatsapp" {{ in_array('whatsapp', old('channels', [])) ? 'checked' : '' }} style="accent-color: #25D366; width: 18px; height: 18px; margin-top: 0.2rem;" class="channel-check">
                            <div>
                                <span style="font-weight: 700; font-size: 0.95rem; color: #065F46; display: flex; align-items: center; gap: 0.4rem;">
                                    💬 WhatsApp Resmi (Meta Cloud API)
                                </span>
                                <span style="font-size: 0.78rem; color: #4B5563; display: block; margin-top: 0.25rem; line-height: 1.4;">
                                    Pesan tanggap darurat resmi ke WhatsApp Business/Penerima dengan status terkirim dan webhook konfirmasi baca.
                                </span>
                                @if(!$whatsappConfigured)
                                    <span style="font-size: 0.72rem; color: #B45309; background: #FEF3C7; padding: 0.15rem 0.45rem; border-radius: 4px; display: inline-block; margin-top: 0.35rem; font-weight: 600;">
                                        ⚠️ API WhatsApp Belum Dikonfigurasi
                                    </span>
                                @endif
                            </div>
                        </label>
                    </div>
                    @error('channels')
                        <span style="font-size: 0.78rem; color: #B83B26; margin-top: 0.4rem; display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Pemilihan Satu atau Beberapa Penerima -->
                <div style="margin-bottom: 2rem;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem; flex-wrap: wrap; gap: 0.5rem;">
                        <div>
                            <label style="font-weight: 700; font-size: 0.92rem; color: var(--color-deep-forest);">
                                2. Pilih Penerima Laporan yang Sesuai Wilayah Kejadian <span style="color: #B83B26;">*</span>
                            </label>
                            <span style="font-size: 0.8rem; color: #6D7870; display: block;">
                                Direkomendasikan memilih instansi/regu yang membawahi wilayah <strong>{{ $report->province }}</strong> ({{ $report->regency }}).
                            </span>
                        </div>
                        <a href="{{ route('admin.recipients.create') }}" target="_blank" style="font-size: 0.78rem; color: var(--color-forest-moss); font-weight: 600; text-decoration: underline;">
                            + Tambah Penerima Baru
                        </a>
                    </div>

                    @if($recipients->count() > 0)
                        <div style="border: 1px solid var(--color-border-subtle); border-radius: 10px; max-height: 320px; overflow-y: auto; background: #FAF9F5; padding: 0.75rem;">
                            @php
                                $matchedRecipients = $recipients->filter(fn($r) => $r->province === $report->province || $r->province === 'Semua Wilayah');
                                $otherRecipients = $recipients->reject(fn($r) => $r->province === $report->province || $r->province === 'Semua Wilayah');
                            @endphp

                            @if($matchedRecipients->count() > 0)
                                <div style="font-size: 0.74rem; font-weight: 700; color: #526B4D; text-transform: uppercase; padding: 0.35rem 0.5rem; letter-spacing: 0.05em;">
                                    📌 Rekomendasi Wilayah ({{ $report->province }}):
                                </div>
                                @foreach($matchedRecipients as $rec)
                                    @php
                                        $verified = $rec->verified_channels ?: [];
                                    @endphp
                                    <label style="display: flex; align-items: flex-start; gap: 0.75rem; padding: 0.75rem; background: #FFFFFF; border: 1px solid #D6C5A3; border-radius: 8px; margin-bottom: 0.5rem; cursor: pointer; transition: background 0.15s ease;">
                                        <input type="checkbox" name="recipient_ids[]" value="{{ $rec->id }}" {{ in_array($rec->id, old('recipient_ids', [])) ? 'checked' : '' }} style="accent-color: var(--color-forest-moss); width: 17px; height: 17px; margin-top: 0.2rem;" class="recipient-check">
                                        <div style="flex: 1;">
                                            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.4rem;">
                                                <span style="font-weight: 700; font-size: 0.9rem; color: var(--color-deep-forest);">
                                                    {{ $rec->name }}
                                                </span>
                                                <span style="font-size: 0.72rem; font-weight: 600; padding: 0.15rem 0.45rem; border-radius: 4px; background: {{ $rec->category === 'pemerintah' ? '#E0E7FF' : '#FEF3C7' }}; color: {{ $rec->category === 'pemerintah' ? '#3730A3' : '#92400E' }};">
                                                    {{ $rec->category_label }}
                                                </span>
                                            </div>
                                            <div style="font-size: 0.78rem; color: #4B5563; margin-top: 0.2rem;">
                                                Wilayah: <strong>{{ $rec->province }}</strong> ({{ $rec->regency ?: 'Seluruh Kabupaten' }})
                                                @if($rec->clean_whatsapp_number)
                                                    • WA: <span style="color: #128C7E; font-weight: 600;">{{ $rec->clean_whatsapp_number }}</span>
                                                @endif
                                                @if($rec->email)
                                                    • Email: <span style="color: #1E40AF;">{{ $rec->email }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </label>
                                @endforeach
                            @endif

                            @if($otherRecipients->count() > 0)
                                <div style="font-size: 0.74rem; font-weight: 700; color: #6D7870; text-transform: uppercase; padding: 0.5rem 0.5rem 0.35rem; letter-spacing: 0.05em; border-top: 1px dashed var(--color-border-subtle); margin-top: 0.5rem;">
                                    Entitas Wilayah Lain:
                                </div>
                                @foreach($otherRecipients as $rec)
                                    @php
                                        $verified = $rec->verified_channels ?: [];
                                    @endphp
                                    <label style="display: flex; align-items: flex-start; gap: 0.75rem; padding: 0.65rem 0.75rem; background: #FFFFFF; border: 1px solid var(--color-border-subtle); border-radius: 8px; margin-bottom: 0.5rem; cursor: pointer; opacity: 0.85;">
                                        <input type="checkbox" name="recipient_ids[]" value="{{ $rec->id }}" {{ in_array($rec->id, old('recipient_ids', [])) ? 'checked' : '' }} style="accent-color: var(--color-forest-moss); width: 17px; height: 17px; margin-top: 0.2rem;" class="recipient-check">
                                        <div style="flex: 1;">
                                            <span style="font-weight: 600; font-size: 0.88rem; color: var(--color-charcoal);">
                                                {{ $rec->name }}
                                            </span>
                                            <span style="font-size: 0.74rem; color: #6D7870; display: block;">
                                                {{ $rec->province }} • {{ $rec->clean_whatsapp_number ?: $rec->email ?: $rec->phone }}
                                            </span>
                                        </div>
                                    </label>
                                @endforeach
                            @endif
                        </div>
                    @else
                        <div style="padding: 1.25rem; background: #FEF3C7; border: 1px solid #FCD34D; border-radius: 8px; font-size: 0.88rem; color: #92400E;">
                            Belum ada entitas penerima aktif di direktori. Silakan daftarkan instansi BPBD, posko Manggala Agni, atau MPA terlebih dahulu.
                        </div>
                    @endif

                    @error('recipient_ids')
                        <span style="font-size: 0.78rem; color: #B83B26; margin-top: 0.4rem; display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Catatan Arahan Pengantar -->
                <div style="margin-bottom: 2rem;">
                    <label for="dispatch_notes" style="display: block; font-weight: 700; font-size: 0.92rem; color: var(--color-deep-forest); margin-bottom: 0.4rem;">
                        3. Catatan Pengantar / Arahan Posko Tanggap Siaga <span style="color: #B83B26;">*</span>
                    </label>
                    <textarea id="dispatch_notes" 
                              name="dispatch_notes" 
                              rows="4" 
                              required 
                              placeholder="Tuliskan arahan resmi tindak lanjut, misalnya: Mohon regu patroli darat terdekat segera melakukan pengecekan kepulan asap di koordinat terlampir dan berkoordinasi dengan posko..." 
                              style="width: 100%; padding: 0.75rem 1rem; border: 1px solid {{ $errors->has('dispatch_notes') ? '#B83B26' : 'var(--color-border-subtle)' }}; border-radius: 8px; font-size: 0.9rem; line-height: 1.5; background: #FAF9F5;">{{ old('dispatch_notes') }}</textarea>
                    @error('dispatch_notes')
                        <span style="font-size: 0.78rem; color: #B83B26; margin-top: 0.35rem; display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Opsi Privasi dan Lampiran -->
                <div style="background: #FAF9F5; border: 1px solid var(--color-border-subtle); border-radius: 10px; padding: 1.25rem 1.5rem; margin-bottom: 2rem;">
                    <span style="font-size: 0.78rem; text-transform: uppercase; font-weight: 700; color: var(--color-deep-forest); display: block; margin-bottom: 0.75rem;">
                        4. Pengendalian Privasi & Dokumen
                    </span>

                    <!-- Lampirkan Foto (Signed URL) -->
                    <div style="margin-bottom: 0.85rem;">
                        <label style="display: flex; align-items: flex-start; gap: 0.6rem; cursor: pointer;">
                            <input type="checkbox" name="include_photo" value="1" {{ old('include_photo', true) ? 'checked' : '' }} {{ !$report->photo_path ? 'disabled' : '' }} style="accent-color: var(--color-forest-moss); width: 17px; height: 17px; margin-top: 0.15rem;">
                            <div>
                                <span style="font-size: 0.88rem; font-weight: 600; color: var(--color-charcoal); display: block;">
                                    Sertakan Bukti Foto Lapangan
                                </span>
                                <span style="font-size: 0.78rem; color: #6D7870; display: block;">
                                    @if($report->photo_path)
                                        Foto dibagikan melalui tautan bertanda tangan kriptografis (*Signed URL*) berbatas waktu 7 hari.
                                    @else
                                        Pelapor tidak melampirkan foto bukti pada pengaduan ini.
                                    @endif
                                </span>
                            </div>
                        </label>
                    </div>

                    <!-- Lampirkan Kontak Pelapor (Default OFF) -->
                    <div>
                        <label style="display: flex; align-items: flex-start; gap: 0.6rem; cursor: pointer;">
                            <input type="checkbox" name="include_reporter_contact" value="1" {{ old('include_reporter_contact') ? 'checked' : '' }} style="accent-color: var(--color-forest-moss); width: 17px; height: 17px; margin-top: 0.15rem;">
                            <div>
                                <span style="font-size: 0.88rem; font-weight: 600; color: #9A3412; display: block;">
                                    Sertakan Identitas & Nomor Kontak Pelapor
                                </span>
                                <span style="font-size: 0.78rem; color: #6D7870; display: block;">
                                    Hanya aktifkan jika regu lapangan memerlukan komunikasi langsung dengan pelapor di tempat kejadian. Jangan dibagikan tanpa kebutuhan operasional sah.
                                </span>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Live Summary Box Sebelum Submit -->
                <div id="dispatchSummaryBox" style="background: #F0F4F1; border: 1px solid #D1DED6; border-radius: 8px; padding: 1.15rem 1.5rem; margin-bottom: 2rem;">
                    <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 0.75rem;">
                        <div>
                            <span style="font-size: 0.74rem; text-transform: uppercase; font-weight: 700; color: #526B4D; letter-spacing: 0.05em; display: block;">
                                Konfirmasi Distribusi Pengiriman:
                            </span>
                            <div id="summaryText" style="font-size: 0.92rem; font-weight: 600; color: var(--color-deep-forest); margin-top: 0.25rem;">
                                Memilih 0 penerima dan 0 kanal.
                            </div>
                        </div>
                        <span id="badgeEstimated" style="font-size: 0.8rem; font-weight: 700; color: #1E40AF; background: #DBEAFE; padding: 0.3rem 0.7rem; border-radius: 6px;">
                            0 Disposisi Terpisah
                        </span>
                    </div>
                </div>

                <!-- Submit Button -->
                <div style="display: flex; justify-content: flex-end; gap: 1rem;">
                    <a href="{{ route('admin.reports.show', $report) }}" class="btn btn-outline" style="padding: 0.75rem 1.4rem; font-size: 0.9rem; text-decoration: none;">
                        Batal
                    </a>
                    <button type="submit" id="btnSubmit" class="btn btn-forest" {{ $recipients->isEmpty() ? 'disabled' : '' }} style="padding: 0.75rem 1.75rem; font-size: 0.92rem; display: inline-flex; align-items: center; gap: 0.5rem;" onclick="return confirm('Konfirmasi: Anda yakin ingin meneruskan laporan pengaduan ini ke penerima dan kanal yang dipilih?');">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="22" y1="2" x2="11" y2="13"></line>
                            <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                        </svg>
                        <span>Konfirmasi & Teruskan Laporan</span>
                    </button>
                </div>

            </form>
        </div>

    </div>

    @push('admin_scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const recipientChecks = document.querySelectorAll('.recipient-check');
            const channelChecks = document.querySelectorAll('.channel-check');
            const summaryText = document.getElementById('summaryText');
            const badgeEstimated = document.getElementById('badgeEstimated');
            const btnSubmit = document.getElementById('btnSubmit');

            function updateSummary() {
                let recipientCount = 0;
                recipientChecks.forEach(c => { if (c.checked) recipientCount++; });

                let channelCount = 0;
                channelChecks.forEach(c => { if (c.checked) channelCount++; });

                const totalDispatches = recipientCount * channelCount;

                summaryText.textContent = `Akan dikirimkan ke ${recipientCount} penerima melalui ${channelCount} kanal terpilih.`;
                badgeEstimated.textContent = `${totalDispatches} Pengiriman Terpisah`;

                if (totalDispatches === 0) {
                    btnSubmit.disabled = true;
                    btnSubmit.style.opacity = '0.6';
                } else {
                    btnSubmit.disabled = false;
                    btnSubmit.style.opacity = '1';
                }
            }

            recipientChecks.forEach(c => c.addEventListener('change', updateSummary));
            channelChecks.forEach(c => c.addEventListener('change', updateSummary));
            updateSummary();
        });
    </script>
    @endpush
@endsection
