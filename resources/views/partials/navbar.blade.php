<header class="site-header">
    <div class="container-editorial">
        <div class="site-nav-wrapper">
            <!-- Brand Logo & Identity -->
            <a href="{{ route('home') }}" class="brand-identity" aria-label="Beranda Borneo Siaga">
                <div class="brand-emblem" aria-hidden="true" style="overflow: hidden; padding: 0; background: #FFFFFF; border: 2.5px solid var(--color-earth-sand); border-radius: 50%; width: 52px; height: 52px; display: flex; align-items: center; justify-content: center; box-shadow: 0 3px 10px rgba(0,0,0,0.18); flex-shrink: 0;">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo Borneo Siaga" style="width: 100%; height: 100%; object-fit: cover; display: block;">
                </div>
                <div class="brand-titles">
                    <span class="brand-name">BORNEO SIAGA</span>
                    <span class="brand-tagline">Tanggap & Pantau Karhutla Kalimantan</span>
                </div>
            </a>

            <!-- Desktop Navigation Menu -->
            <nav class="nav-menu-desktop" aria-label="Navigasi Utama">
                <a href="{{ route('home') }}" 
                   class="nav-link-item {{ request()->is('/') ? 'active' : '' }}">
                    Beranda
                </a>
                <a href="{{ route('pantauan.karhutla') }}" 
                   class="nav-link-item {{ request()->is('pantauan-karhutla*') ? 'active' : '' }}">
                    Pantauan
                </a>
                <a href="{{ route('berita.index') }}" 
                   class="nav-link-item {{ request()->is('berita*') ? 'active' : '' }}">
                    Berita
                </a>
                <a href="{{ route('pengaduan') }}" 
                   class="nav-link-item {{ request()->is('pengaduan*') ? 'active' : '' }}">
                    Pelaporan
                </a>
                <a href="{{ route('lacak.pengaduan') }}" 
                   class="nav-link-item {{ request()->is('lacak-pengaduan*') ? 'active' : '' }}">
                    Lacak
                </a>
                <a href="{{ route('edukasi') }}" 
                   class="nav-link-item {{ request()->is('edukasi*') ? 'active' : '' }}">
                    Edukasi
                </a>
                <a href="{{ route('tentang.kami') }}" 
                   class="nav-link-item {{ request()->is('tentang-kami*') ? 'active' : '' }}">
                    Tentang Kami
                </a>
            </nav>

            <!-- Action Button & Mobile Toggle -->
            <div style="display: flex; align-items: center; gap: 0.75rem;">
                <div class="desktop-action">
                    <a href="{{ route('pengaduan') }}" class="btn-emergency">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M8.5 14.5A2.5 2.5 0 0 0 11 12c0-1.38-.5-2-1-3-1.072-2.143-.224-4.054 2-6 .5 2.5 2 4.9 4 6.5 2 1.6 3 3.5 3 5.5a7 7 0 1 1-14 0c0-1.153.433-2.294 1-3a2.5 2.5 0 0 0 2.5 3z"/>
                        </svg>
                        <span>Buat Laporan</span>
                    </a>
                </div>

                <button id="mobileNavToggle" class="btn-mobile-toggle" aria-expanded="false" aria-label="Buka navigasi" type="button">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="4" y1="6" x2="20" y2="6"></line>
                        <line x1="4" y1="12" x2="20" y2="12"></line>
                        <line x1="4" y1="18" x2="20" y2="18"></line>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Navigation Drawer -->
    <div id="mobileNavDrawer" class="mobile-nav-drawer" aria-label="Navigasi Seluler">
        <ul class="mobile-nav-list">
            <li>
                <a href="{{ route('home') }}" class="mobile-nav-link {{ request()->is('/') ? 'active' : '' }}">
                    <span>Beranda</span>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"></polyline></svg>
                </a>
            </li>
            <li>
                <a href="{{ route('pantauan.karhutla') }}" class="mobile-nav-link {{ request()->is('pantauan-karhutla*') ? 'active' : '' }}">
                    <span>Pantauan</span>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"></polyline></svg>
                </a>
            </li>
            <li>
                <a href="{{ route('berita.index') }}" class="mobile-nav-link {{ request()->is('berita*') ? 'active' : '' }}">
                    <span>Berita</span>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"></polyline></svg>
                </a>
            </li>
            <li>
                <a href="{{ route('pengaduan') }}" class="mobile-nav-link {{ request()->is('pengaduan*') ? 'active' : '' }}">
                    <span>Pelaporan</span>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"></polyline></svg>
                </a>
            </li>
            <li>
                <a href="{{ route('lacak.pengaduan') }}" class="mobile-nav-link {{ request()->is('lacak-pengaduan*') ? 'active' : '' }}">
                    <span>Lacak Laporan</span>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"></polyline></svg>
                </a>
            </li>
            <li>
                <a href="{{ route('edukasi') }}" class="mobile-nav-link {{ request()->is('edukasi*') ? 'active' : '' }}">
                    <span>Edukasi</span>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"></polyline></svg>
                </a>
            </li>
            <li>
                <a href="{{ route('tentang.kami') }}" class="mobile-nav-link {{ request()->is('tentang-kami*') ? 'active' : '' }}">
                    <span>Tentang Kami</span>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"></polyline></svg>
                </a>
            </li>
        </ul>
        <div style="padding-top: 0.5rem;">
            <a href="{{ route('pengaduan') }}" class="btn btn-forest" style="width: 100%; text-align: center;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M8.5 14.5A2.5 2.5 0 0 0 11 12c0-1.38-.5-2-1-3-1.072-2.143-.224-4.054 2-6 .5 2.5 2 4.9 4 6.5 2 1.6 3 3.5 3 5.5a7 7 0 1 1-14 0c0-1.153.433-2.294 1-3a2.5 2.5 0 0 0 2.5 3z"/>
                </svg>
                <span>Buat Laporan</span>
            </a>
        </div>
    </div>
</header>
