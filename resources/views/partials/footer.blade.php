<footer class="site-footer">
    <div class="container-editorial">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 2.5rem;">
            <!-- Col 1: Identity & Mission -->
            <div style="max-width: 320px;">
                <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.25rem;">
                    <div class="brand-emblem" style="width: 64px; height: 64px; border-radius: 50%; overflow: hidden; background: #FFFFFF; border: 2.5px solid var(--color-earth-sand); padding: 0; box-shadow: 0 4px 14px rgba(0,0,0,0.3); flex-shrink: 0;">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo Borneo Siaga" style="width: 100%; height: 100%; object-fit: cover; display: block;">
                    </div>
                    <div>
                        <span style="font-family: var(--font-heading); font-size: 1.45rem; font-weight: 700; color: var(--color-earth-sand); letter-spacing: 0.04em; display: block; line-height: 1.1;">BORNEO SIAGA</span>
                        <span style="font-size: 0.74rem; color: #a3b8aa; text-transform: uppercase; letter-spacing: 0.08em; display: block; margin-top: 0.25rem;">Sistem Tanggap Karhutla</span>
                    </div>
                </div>
                <p style="color: #c0d1c5; font-size: 0.92rem; line-height: 1.65; margin-bottom: 1.5rem;">
                    Platform sinergi pemantauan titik panas (hotspot), pelaporan cepat masyarakat, dan edukasi pencegahan kebakaran hutan serta lahan gambut di seluruh bentang alam Kalimantan.
                </p>
                <div style="display: inline-flex; align-items: center; gap: 0.5rem; background: rgba(214, 197, 163, 0.12); padding: 0.4rem 0.85rem; border-radius: 9999px; border: 1px solid rgba(214, 197, 163, 0.25);">
                    <span style="width: 8px; height: 8px; background-color: #6ee7b7; border-radius: 50%; display: inline-block;"></span>
                    <span style="font-size: 0.78rem; color: var(--color-earth-sand); font-weight: 600;">Sistem Siaga Aktif 24/7</span>
                </div>
            </div>

            <!-- Col 2: Navigation Links -->
            <div>
                <h5>Navigasi Utama</h5>
                <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 0.7rem; font-size: 0.92rem;">
                    <li><a href="{{ route('home') }}">Beranda Utama</a></li>
                    <li><a href="{{ route('pantauan.karhutla') }}">Pantauan Karhutla & Hotspot</a></li>
                    <li><a href="{{ route('berita.index') }}">Warta & Berita Terkini</a></li>
                    <li><a href="{{ route('pengaduan') }}">Kirim Pengaduan Kebakaran</a></li>
                    <li><a href="{{ route('lacak.pengaduan') }}">Lacak Status Tiket Laporan</a></li>
                    <li><a href="{{ route('edukasi') }}">Pusat Edukasi & Mitigasi</a></li>
                    <li><a href="{{ route('tentang.kami') }}">Profil & Visi Konservasi</a></li>
                </ul>
            </div>

            <!-- Col 3: Wilayah Pantauan -->
            <div>
                <h5>Wilayah Pantauan</h5>
                <p style="font-size: 0.88rem; color: #a9beaf; margin-bottom: 0.9rem;">
                    Pemantauan terpadu 5 provinsi di Pulau Kalimantan:
                </p>
                <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 0.55rem; font-size: 0.88rem; color: #d7e4db;">
                    <li style="display: flex; align-items: center; gap: 0.5rem;">
                        <span style="color: var(--color-earth-sand);">•</span> Kalimantan Barat (Pontianak)
                    </li>
                    <li style="display: flex; align-items: center; gap: 0.5rem;">
                        <span style="color: var(--color-earth-sand);">•</span> Kalimantan Tengah (Palangka Raya)
                    </li>
                    <li style="display: flex; align-items: center; gap: 0.5rem;">
                        <span style="color: var(--color-earth-sand);">•</span> Kalimantan Selatan (Banjarmasin)
                    </li>
                    <li style="display: flex; align-items: center; gap: 0.5rem;">
                        <span style="color: var(--color-earth-sand);">•</span> Kalimantan Timur (Samarinda & IKN)
                    </li>
                    <li style="display: flex; align-items: center; gap: 0.5rem;">
                        <span style="color: var(--color-earth-sand);">•</span> Kalimantan Utara (Tanjung Selor)
                    </li>
                </ul>
            </div>

            <!-- Col 4: Posko & Kontak Darurat -->
            <div>
                <h5>Posko & Bantuan Darurat</h5>
                <p style="font-size: 0.88rem; color: #a9beaf; margin-bottom: 1rem;">
                    Untuk kebakaran aktif dan situasi genting, hubungi posko darurat terdekat:
                </p>
                <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                    <div style="background: rgba(0, 0, 0, 0.22); padding: 0.75rem 1rem; border-radius: 8px; border: 1px solid rgba(214, 197, 163, 0.15);">
                        <span style="font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.08em; color: var(--color-earth-sand); font-weight: 700; display: block;">Manggala Agni / KLHK</span>
                        <span style="font-size: 1.05rem; font-weight: 600; color: #ffffff; font-family: monospace;">1500 093 / 0811-1000-093</span>
                    </div>
                    <div style="background: rgba(0, 0, 0, 0.22); padding: 0.75rem 1rem; border-radius: 8px; border: 1px solid rgba(214, 197, 163, 0.15);">
                        <span style="font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.08em; color: var(--color-earth-sand); font-weight: 700; display: block;">Layanan Darurat Nasional</span>
                        <span style="font-size: 1.05rem; font-weight: 600; color: #ffffff; font-family: monospace;">112 (Bebas Pulsa)</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Bottom -->
        <div class="footer-bottom">
            <div>
                <span>&copy; {{ date('Y') }} <strong>BORNEO SIAGA</strong>. Seluruh hak cipta dilindungi.</span>
            </div>
            <div style="display: flex; align-items: center; gap: 1.5rem;">
                <span style="color: var(--color-earth-sand); font-style: italic; font-family: var(--font-heading); font-size: 1.05rem;">
                    "Menjaga Paru-Paru Nusantara, Melindungi Bentang Alam Borneo"
                </span>
            </div>
        </div>
    </div>
</footer>
