<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Disposisi Laporan Karhutla - BORNEO SIAGA</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #F5F3EC; color: #292D29; margin: 0; padding: 24px; line-height: 1.6;">
    <div style="max-width: 650px; margin: 0 auto; background-color: #FFFFFF; border-radius: 10px; border: 1px solid #D6C5A3; overflow: hidden;">
        
        <!-- Header -->
        <div style="background-color: #263B30; color: #FFFFFF; padding: 24px; text-align: left;">
            <span style="font-size: 11px; text-transform: uppercase; letter-spacing: 2px; color: #D6C5A3; font-weight: bold; display: block; margin-bottom: 6px;">
                BORNEO SIAGA • SISTEM TANGGAP KARHUTLA
            </span>
            <h1 style="font-size: 20px; margin: 0; color: #FFFFFF; font-weight: bold;">
                Disposisi Pengaduan Karhutla
            </h1>
            <span style="font-size: 13px; color: #cbd9ce; display: block; margin-top: 4px;">
                Nomor Tiket: <strong>{{ $dispatch->report->ticket_number }}</strong>
            </span>
        </div>

        <!-- Body -->
        <div style="padding: 24px;">
            <p style="margin-top: 0; font-size: 15px;">
                Yth. <strong>{{ $dispatch->recipient->name }}</strong>,<br>
                Posko Tanggap Darurat Borneo Siaga meneruskan informasi pengaduan masyarakat mengenai dugaan kebakaran hutan dan lahan (karhutla) di wilayah kerja Anda untuk diverifikasi dan ditindaklanjuti.
            </p>

            <!-- Catatan Pengantar Admin -->
            <div style="background-color: #FAF9F5; border-left: 4px solid #526B4D; padding: 14px 18px; border-radius: 4px; margin: 18px 0;">
                <span style="font-size: 11px; text-transform: uppercase; font-weight: bold; color: #526B4D; display: block; margin-bottom: 4px;">
                    Arahan / Catatan Pengantar Posko:
                </span>
                <span style="font-size: 14px; color: #263B30; font-weight: 500;">
                    {{ $dispatch->dispatch_notes }}
                </span>
            </div>

            <!-- Detail Laporan -->
            <h3 style="font-size: 15px; color: #263B30; border-bottom: 1px solid #E5E7EB; padding-bottom: 6px; margin-top: 24px;">
                Ringkasan Informasi Lapangan
            </h3>
            
            <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
                <tr>
                    <td style="padding: 6px 0; color: #6B7280; width: 38%;">Jenis Insiden:</td>
                    <td style="padding: 6px 0; font-weight: bold; color: #292D29;">{{ $dispatch->report->incident_type }}</td>
                </tr>
                <tr>
                    <td style="padding: 6px 0; color: #6B7280;">Waktu Kejadian:</td>
                    <td style="padding: 6px 0; color: #292D29;">{{ $dispatch->report->incident_time ? $dispatch->report->incident_time->format('d/m/Y H:i') . ' WIB' : '-' }}</td>
                </tr>
                <tr>
                    <td style="padding: 6px 0; color: #6B7280;">Wilayah Administratif:</td>
                    <td style="padding: 6px 0; color: #292D29;">
                        {{ $dispatch->report->village ? 'Desa ' . $dispatch->report->village . ', ' : '' }}
                        {{ $dispatch->report->district ? 'Kec. ' . $dispatch->report->district . ', ' : '' }}
                        {{ $dispatch->report->regency }}, {{ $dispatch->report->province }}
                    </td>
                </tr>
                <tr>
                    <td style="padding: 6px 0; color: #6B7280;">Patokan / Detail Lokasi:</td>
                    <td style="padding: 6px 0; color: #292D29;">{{ $dispatch->report->incident_location }}</td>
                </tr>
                @if($dispatch->report->latitude && $dispatch->report->longitude)
                <tr>
                    <td style="padding: 6px 0; color: #6B7280;">Koordinat GPS:</td>
                    <td style="padding: 6px 0; font-family: monospace;">
                        <a href="https://www.google.com/maps?q={{ $dispatch->report->latitude }},{{ $dispatch->report->longitude }}" style="color: #526B4D; font-weight: bold; text-decoration: underline;">
                            {{ $dispatch->report->latitude }}, {{ $dispatch->report->longitude }} (Buka di Peta)
                        </a>
                    </td>
                </tr>
                @endif
                <tr>
                    <td style="padding: 6px 0; color: #6B7280; vertical-align: top;">Deskripsi Lapangan:</td>
                    <td style="padding: 6px 0; color: #292D29; line-height: 1.5;">{{ $dispatch->report->description }}</td>
                </tr>
            </table>

            <!-- Kontak Pelapor (Jika Diizinkan) -->
            <h3 style="font-size: 15px; color: #263B30; border-bottom: 1px solid #E5E7EB; padding-bottom: 6px; margin-top: 24px;">
                Identitas Kontak
            </h3>
            @if($dispatch->include_reporter_contact)
                <p style="font-size: 14px; margin: 8px 0;">
                    <strong>Nama Pelapor:</strong> {{ $dispatch->report->reporter_name }}<br>
                    <strong>Nomor Kontak / HP:</strong> <a href="tel:{{ $dispatch->report->reporter_phone }}" style="color: #526B4D; font-weight: bold;">{{ $dispatch->report->reporter_phone }}</a>
                </p>
            @else
                <p style="font-size: 13px; color: #6B7280; margin: 8px 0; font-style: italic;">
                    Kontak pelapor dirahasiakan oleh Posko Siaga untuk perlindungan privasi data masyarakat. Koordinasi lapangan dapat langsung berkoordinasi dengan Posko Siaga.
                </p>
            @endif

            <!-- Bukti Foto Lapangan (Jika Diizinkan) -->
            @if($dispatch->include_photo && $dispatch->secure_photo_url)
                <div style="margin-top: 24px; padding: 16px; background-color: #FAF9F5; border-radius: 6px; border: 1px solid #E5E7EB; text-align: center;">
                    <span style="font-size: 13px; font-weight: bold; color: #263B30; display: block; margin-bottom: 8px;">
                        Dokumentasi Bukti Foto Kejadian
                    </span>
                    <a href="{{ $dispatch->secure_photo_url }}" style="display: inline-block; background-color: #263B30; color: #FFFFFF; padding: 10px 18px; border-radius: 6px; text-decoration: none; font-size: 13px; font-weight: bold;">
                        Buka Foto Bukti Lapangan (Akses Terbatas)
                    </a>
                    <span style="font-size: 11px; color: #6B7280; display: block; margin-top: 8px;">
                        Tautan di atas dilindungi token kriptografis dan berlaku selama 7 hari.
                    </span>
                </div>
            @endif

            <div style="margin-top: 32px; padding-top: 16px; border-top: 1px solid #E5E7EB; font-size: 13px; color: #4B5563;">
                Mohon untuk segera mengonfirmasi penerimaan disposisi ini dan menyampaikan pembaruan status lapangan kepada Posko Borneo Siaga.<br><br>
                Salam tanggap siaga,<br>
                <strong>Posko Tanggap Darurat Karhutla BORNEO SIAGA</strong>
            </div>
        </div>

        <!-- Footer -->
        <div style="background-color: #FAF9F5; border-top: 1px solid #D6C5A3; padding: 14px 24px; font-size: 11px; color: #6B7280; text-align: center;">
            Surel ini dibuat dan dikirimkan secara otomatis oleh Sistem Manajemen Operasional Borneo Siaga. Kerahasiaan data dilindungi perundang-undangan.
        </div>
    </div>
</body>
</html>
