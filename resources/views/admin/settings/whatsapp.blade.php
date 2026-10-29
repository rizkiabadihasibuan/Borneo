@extends('admin.layout')

@section('title', 'Pengaturan Integrasi WhatsApp Resmi & Email - Posko BORNEO SIAGA')
@section('header_title', 'Status & Panduan Integrasi WhatsApp Business Cloud API & Email')

@section('admin_content')
    <div style="max-width: 1000px; margin: 0 auto;">

        <!-- Header Intro -->
        <div style="margin-bottom: 2rem;">
            <span style="font-size: 0.8rem; color: #6D7870; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em;">Integrasi Kanal Resmi Komunikasi</span>
            <p style="margin: 0.25rem 0 0; color: #4B5563; font-size: 0.95rem;">
                Halaman pemantauan teknis dan panduan penyiapan kredensial WhatsApp Business Platform / Cloud API Meta serta pengiriman Surat Elektronik (SMTP) resmi Borneo Siaga.
            </p>
        </div>

        <div style="display: grid; grid-template-columns: 1fr; gap: 2rem;">

            <!-- Status WhatsApp Cloud API -->
            <div style="background: #FFFFFF; border: 1px solid var(--color-border-subtle); border-radius: 12px; overflow: hidden; box-shadow: var(--shadow-editorial);">
                <div style="background-color: var(--color-deep-forest); color: #FFFFFF; padding: 1.5rem 2rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
                    <div>
                        <span style="font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.1em; color: var(--color-earth-sand); font-weight: 700; display: block;">
                            Meta Business Platform
                        </span>
                        <h2 style="font-family: var(--font-heading); font-size: 1.5rem; font-weight: 700; margin: 0.25rem 0 0; color: #FFFFFF;">
                            WhatsApp Business Cloud API (Resmi)
                        </h2>
                    </div>
                    <div>
                        @if($whatsappStatus['is_configured'])
                            <span style="display: inline-flex; align-items: center; gap: 0.4rem; font-size: 0.85rem; font-weight: 700; color: #065F46; background: #D1FAE5; padding: 0.4rem 0.9rem; border-radius: 9999px;">
                                <span style="width: 8px; height: 8px; border-radius: 50%; background: #10B981;"></span>
                                Terkonfigurasi & Aktif
                            </span>
                        @else
                            <span style="display: inline-flex; align-items: center; gap: 0.4rem; font-size: 0.85rem; font-weight: 700; color: #9A3412; background: #FFEDD5; padding: 0.4rem 0.9rem; border-radius: 9999px;">
                                <span style="width: 8px; height: 8px; border-radius: 50%; background: #F97316;"></span>
                                Belum Dikonfigurasi
                            </span>
                        @endif
                    </div>
                </div>

                <div style="padding: 2rem;">
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.25rem; margin-bottom: 1.75rem;">
                        <div style="background: #FAF9F5; padding: 1rem; border-radius: 8px; border: 1px solid var(--color-border-subtle);">
                            <span style="font-size: 0.75rem; text-transform: uppercase; font-weight: 600; color: #6D7870; display: block;">Endpoint Base URL:</span>
                            <span style="font-family: monospace; font-size: 0.85rem; color: var(--color-deep-forest); font-weight: 600; display: block; margin-top: 0.25rem;">
                                {{ $whatsappStatus['base_url'] }}
                            </span>
                        </div>

                        <div style="background: #FAF9F5; padding: 1rem; border-radius: 8px; border: 1px solid var(--color-border-subtle);">
                            <span style="font-size: 0.75rem; text-transform: uppercase; font-weight: 600; color: #6D7870; display: block;">Phone Number ID:</span>
                            <span style="font-family: monospace; font-size: 0.85rem; color: var(--color-deep-forest); font-weight: 600; display: block; margin-top: 0.25rem;">
                                {{ $whatsappStatus['phone_number_id'] ?: 'Belum diisi (.env)' }}
                            </span>
                        </div>

                        <div style="background: #FAF9F5; padding: 1rem; border-radius: 8px; border: 1px solid var(--color-border-subtle);">
                            <span style="font-size: 0.75rem; text-transform: uppercase; font-weight: 600; color: #6D7870; display: block;">Access Token (Bearer):</span>
                            <span style="font-size: 0.85rem; font-weight: 600; color: {{ $whatsappStatus['has_access_token'] ? '#065F46' : '#991B1B' }}; display: block; margin-top: 0.25rem;">
                                {{ $whatsappStatus['has_access_token'] ? '✓ Tersedia (Kerahasiaan Terlindungi)' : '✗ Belum Tersedia' }}
                            </span>
                        </div>

                        <div style="background: #FAF9F5; padding: 1rem; border-radius: 8px; border: 1px solid var(--color-border-subtle);">
                            <span style="font-size: 0.75rem; text-transform: uppercase; font-weight: 600; color: #6D7870; display: block;">Signature & Webhook Token:</span>
                            <span style="font-size: 0.85rem; font-weight: 600; color: {{ $whatsappStatus['has_app_secret'] ? '#065F46' : '#6D7870' }}; display: block; margin-top: 0.25rem;">
                                App Secret: {{ $whatsappStatus['has_app_secret'] ? '✓ Ada' : 'Kosong' }} • Verify: {{ $whatsappStatus['has_verify_token'] ? '✓ Ada' : 'Kosong' }}
                            </span>
                        </div>
                    </div>

                    <!-- Webhook Endpoint Section -->
                    <div style="border-top: 1px solid var(--color-border-subtle); padding-top: 1.5rem; margin-bottom: 1.5rem;">
                        <h3 style="font-size: 1.1rem; color: var(--color-deep-forest); font-weight: 700; margin: 0 0 0.5rem;">
                            Endpoint Webhook Resmi untuk Konfirmasi Status (Delivery & Read)
                        </h3>
                        <p style="font-size: 0.85rem; color: #4B5563; margin: 0 0 1rem;">
                            Daftarkan URL di bawah ini pada portal <em>Meta for Developers &gt; WhatsApp &gt; Configuration &gt; Webhook</em>:
                        </p>
                        <div style="display: flex; gap: 0.5rem; align-items: center; background: #FAF9F5; padding: 0.75rem 1rem; border-radius: 8px; border: 1px solid #D6C5A3;">
                            <code style="font-family: monospace; font-size: 0.88rem; color: #1E40AF; flex: 1;">
                                {{ $webhookUrl }}
                            </code>
                            <span style="font-size: 0.75rem; background: #D1FAE5; color: #065F46; padding: 0.2rem 0.5rem; border-radius: 4px; font-weight: 600;">
                                GET (Handshake) & POST (Statuses)
                            </span>
                        </div>
                    </div>

                    <!-- Panduan Penyiapan Environment Variables -->
                    <div style="background: #FAF9F5; border: 1px solid var(--color-border-subtle); border-radius: 8px; padding: 1.25rem 1.5rem;">
                        <h4 style="margin: 0 0 0.5rem; font-size: 0.95rem; color: var(--color-deep-forest);">
                            Panduan Konfigurasi Variabel Lingkungan (.env):
                        </h4>
                        <p style="font-size: 0.82rem; color: #4B5563; margin: 0 0 0.75rem;">
                            Tambahkan parameter berikut pada berkas <code>.env</code> server produksi tanpa mempublikasikannya ke repositori kode:
                        </p>
                        <pre style="background: #263B30; color: #D6C5A3; padding: 1rem; border-radius: 6px; font-size: 0.82rem; overflow-x: auto; line-height: 1.6; margin: 0;"># Integrasi WhatsApp Business Cloud API Meta
WHATSAPP_API_BASE_URL=https://graph.facebook.com/v21.0
WHATSAPP_PHONE_NUMBER_ID=1092837465xxxxx
WHATSAPP_ACCESS_TOKEN=EAAxxxxxxxxxxxxxxxxxxxxxxxxxxxx
WHATSAPP_APP_SECRET=xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
WHATSAPP_VERIFY_TOKEN=borneo_siaga_webhook_token_rahasia</pre>
                    </div>
                </div>
            </div>

            <!-- Status Email Resmi (SMTP) -->
            <div style="background: #FFFFFF; border: 1px solid var(--color-border-subtle); border-radius: 12px; overflow: hidden; box-shadow: var(--shadow-editorial);">
                <div style="background-color: #2F4858; color: #FFFFFF; padding: 1.25rem 2rem; display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <span style="font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.1em; color: var(--color-earth-sand); font-weight: 700; display: block;">
                            Layanan Surat Elektronik
                        </span>
                        <h3 style="font-family: var(--font-heading); font-size: 1.35rem; font-weight: 700; margin: 0.2rem 0 0; color: #FFFFFF;">
                            Konfigurasi Email Resmi (SMTP)
                        </h3>
                    </div>
                    <span style="font-size: 0.82rem; font-weight: 600; color: #FFFFFF; background: rgba(255,255,255,0.15); padding: 0.35rem 0.8rem; border-radius: 9999px;">
                        Mailer: {{ strtoupper($mailConfig['mailer']) }}
                    </span>
                </div>

                <div style="padding: 1.75rem 2rem;">
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.25rem;">
                        <div>
                            <span style="font-size: 0.76rem; text-transform: uppercase; font-weight: 600; color: #6D7870; display: block;">Host SMTP:</span>
                            <span style="font-family: monospace; font-size: 0.88rem; font-weight: 600; color: var(--color-charcoal); display: block; margin-top: 0.2rem;">
                                {{ $mailConfig['host'] ?: '(Default / Mailer Log)' }}
                            </span>
                        </div>
                        <div>
                            <span style="font-size: 0.76rem; text-transform: uppercase; font-weight: 600; color: #6D7870; display: block;">Port / Enkripsi:</span>
                            <span style="font-family: monospace; font-size: 0.88rem; font-weight: 600; color: var(--color-charcoal); display: block; margin-top: 0.2rem;">
                                {{ $mailConfig['port'] ?: '-' }} ({{ $mailConfig['encryption'] ?: 'None' }})
                            </span>
                        </div>
                        <div>
                            <span style="font-size: 0.76rem; text-transform: uppercase; font-weight: 600; color: #6D7870; display: block;">Alamat Pengirim Resmi:</span>
                            <span style="font-size: 0.88rem; font-weight: 600; color: var(--color-forest-moss); display: block; margin-top: 0.2rem;">
                                {{ $mailConfig['from_name'] }} &lt;{{ $mailConfig['from_address'] }}&gt;
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Prosedur Keamanan & Privasi -->
            <div style="background: #FDFBF7; border: 1px solid #D6C5A3; border-radius: 12px; padding: 1.5rem 2rem;">
                <h4 style="margin: 0 0 0.5rem; font-size: 1rem; color: var(--color-deep-forest); font-weight: 700;">
                    🛡️ Kebijakan Privasi & Ketentuan Operasional Borneo Siaga:
                </h4>
                <ul style="margin: 0; padding-left: 1.25rem; font-size: 0.85rem; color: #4B5563; line-height: 1.6;">
                    <li>Jangan pernah membagikan atau memotret Access Token maupun App Secret. Seluruh kredensial wajib dibaca dari berkas <code>.env</code> server.</li>
                    <li>Sistem tidak akan mencetak token atau raw response berisi data sensitif pada log aplikasi.</li>
                    <li>Kontak pelapor dan bukti foto dibatasi secara ketat hanya untuk kepentingan darurat instansi penerima yang terverifikasi.</li>
                    <li>Status <em>"Diterima API"</em> tidak sama dengan status pesan telah terkirim ke perangkat penerima atau dibaca.</li>
                </ul>
            </div>

        </div>

    </div>
@endsection
