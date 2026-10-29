<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Dashboard Posko Admin - BORNEO SIAGA')</title>
    <meta name="theme-color" content="#263B30">

    <!-- Favicon & Brand Icons -->
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo.png') }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,500;0,600;0,700;1,400;1,600&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Stylesheets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .admin-sidebar {
            width: 280px;
            background-color: var(--color-deep-forest);
            color: var(--color-warm-paper);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 100;
            border-right: 1px solid rgba(214, 197, 163, 0.15);
            transition: transform 0.3s ease;
        }

        .admin-nav-item {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            padding: 0.85rem 1.25rem;
            color: #d1ded6;
            text-decoration: none;
            font-size: 0.92rem;
            font-weight: 500;
            border-radius: 8px;
            transition: all 0.2s ease;
            margin-bottom: 0.35rem;
        }

        .admin-nav-item:hover {
            background-color: rgba(214, 197, 163, 0.12);
            color: var(--color-earth-sand);
            transform: translateX(3px);
        }

        .admin-nav-item.active {
            background-color: var(--color-forest-moss);
            color: #FFFFFF;
            font-weight: 600;
            box-shadow: 0 2px 8px rgba(0,0,0,0.18);
        }

        .admin-content-area {
            margin-left: 280px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background-color: var(--color-warm-paper);
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.3rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.76rem;
            font-weight: 600;
            letter-spacing: 0.02em;
        }

        .status-badge.menunggu {
            background: #FEF3C7;
            color: #92400E;
            border: 1px solid #FCD34D;
        }

        .status-badge.diverifikasi {
            background: #E0E7FF;
            color: #3730A3;
            border: 1px solid #C7D2FE;
        }

        .status-badge.penanganan {
            background: #FFEDD5;
            color: #9A3412;
            border: 1px solid #FDBA74;
        }

        .status-badge.selesai {
            background: #D1FAE5;
            color: #065F46;
            border: 1px solid #6EE7B7;
        }

        .status-badge.ditolak {
            background: #FEE2E2;
            color: #991B1B;
            border: 1px solid #FCA5A5;
        }

        @media (max-width: 992px) {
            .admin-sidebar {
                transform: translateX(-100%);
            }
            .admin-sidebar.open {
                transform: translateX(0);
            }
            .admin-content-area {
                margin-left: 0;
            }
        }
    </style>
    @stack('admin_styles')
</head>
<body style="margin: 0; background-color: var(--color-warm-paper); color: var(--color-charcoal); font-family: var(--font-body);">

    <!-- =========================================================================
         1. SIDEBAR NAVIGASI ADMIN
         ========================================================================= -->
    <aside class="admin-sidebar" id="adminSidebar">
        <!-- Sidebar Brand -->
        <div style="padding: 1.5rem 1.25rem; border-bottom: 1px solid rgba(214, 197, 163, 0.15);">
            <a href="{{ route('admin.dashboard') }}" style="display: flex; align-items: center; gap: 0.95rem; text-decoration: none;">
                <div style="width: 54px; height: 54px; border-radius: 50%; overflow: hidden; background: #FFFFFF; border: 2.5px solid var(--color-earth-sand); box-shadow: 0 3px 10px rgba(0,0,0,0.3); flex-shrink: 0;">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo Borneo Siaga" style="width: 100%; height: 100%; object-fit: cover; display: block;">
                </div>
                <div>
                    <span style="font-family: var(--font-heading); font-size: 1.35rem; font-weight: 700; color: var(--color-earth-sand); letter-spacing: 0.04em; display: block; line-height: 1.1;">BORNEO SIAGA</span>
                    <span style="font-size: 0.72rem; color: #a4b8ab; text-transform: uppercase; letter-spacing: 0.08em; display: block; margin-top: 0.25rem;">Posko Administrasi</span>
                </div>
            </a>
        </div>

        <!-- Navigation Menu -->
        <div style="flex: 1; padding: 1.5rem 1rem; overflow-y: auto;">
            <div style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.1em; color: #7f9987; font-weight: 700; padding: 0 0.5rem 0.65rem;">
                Operasional
            </div>

            <!-- Menu: Dashboard -->
            <a href="{{ route('admin.dashboard') }}" class="admin-nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="3" width="7" height="9"></rect>
                    <rect x="14" y="3" width="7" height="5"></rect>
                    <rect x="14" y="12" width="7" height="9"></rect>
                    <rect x="3" y="16" width="7" height="5"></rect>
                </svg>
                <span>Dashboard</span>
            </a>

            <!-- Menu: Pengaduan -->
            <a href="{{ route('admin.reports.index') }}" class="admin-nav-item {{ request()->routeIs('admin.reports.*') || request()->routeIs('admin.dispatches.*') ? 'active' : '' }}">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <line x1="16" y1="13" x2="8" y2="13"></line>
                    <line x1="16" y1="17" x2="8" y2="17"></line>
                    <polyline points="10 9 9 9 8 9"></polyline>
                </svg>
                <span>Pengaduan</span>
            </a>

            <div style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.1em; color: #7f9987; font-weight: 700; padding: 1.25rem 0.5rem 0.65rem;">
                Integrasi & Publik
            </div>

            <!-- Menu: Pantauan Karhutla (links to public FIRMS map) -->
            <a href="{{ route('pantauan.karhutla') }}" target="_blank" class="admin-nav-item">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polygon points="1 6 1 22 8 18 16 22 23 18 23 2 16 6 8 2 1 6"></polygon>
                    <line x1="8" y1="2" x2="8" y2="18"></line>
                    <line x1="16" y1="6" x2="16" y2="22"></line>
                </svg>
                <span>Pantauan Karhutla</span>
                <span style="margin-left: auto; font-size: 0.68rem; background: rgba(255,255,255,0.15); padding: 0.15rem 0.4rem; border-radius: 4px;">FIRMS</span>
            </a>

            <!-- Menu: Artikel Edukasi -->
            <a href="{{ route('edukasi') }}" target="_blank" class="admin-nav-item">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                </svg>
                <span>Artikel Edukasi</span>
            </a>

            <!-- Menu: Lacak Pengaduan Publik -->
            <a href="{{ route('lacak.pengaduan') }}" target="_blank" class="admin-nav-item">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
                <span>Lacak Publik</span>
            </a>
        </div>

        <!-- Sidebar Footer: Admin Profile & Logout -->
        <div style="padding: 1.25rem; border-top: 1px solid rgba(214, 197, 163, 0.15); background: rgba(0,0,0,0.12);">
            <div style="display: flex; align-items: center; justify-content: space-between; gap: 0.75rem; margin-bottom: 0.85rem;">
                <div style="overflow: hidden;">
                    <span style="font-weight: 600; font-size: 0.88rem; color: #FFFFFF; display: block; text-overflow: ellipsis; white-space: nowrap; overflow: hidden;">
                        {{ Auth::user()->name ?? 'Administrator' }}
                    </span>
                    <span style="font-size: 0.72rem; color: var(--color-earth-sand); display: block;">
                        Petugas Posko Terotorisasi
                    </span>
                </div>
            </div>

            <!-- Logout Form (POST with CSRF) -->
            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit" style="width: 100%; display: flex; align-items: center; justify-content: center; gap: 0.5rem; background: rgba(184, 59, 38, 0.18); border: 1px solid rgba(184, 59, 38, 0.4); color: #FFAAA0; padding: 0.6rem 1rem; border-radius: 6px; font-size: 0.82rem; font-weight: 600; cursor: pointer; transition: all 0.2s ease;">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                        <polyline points="16 17 21 12 16 7"></polyline>
                        <line x1="21" y1="12" x2="9" y2="12"></line>
                    </svg>
                    <span>Keluar Sesi Admin</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- =========================================================================
         2. AREA KONTEN UTAMA
         ========================================================================= -->
    <div class="admin-content-area">
        <!-- Top Navigation Bar -->
        <header style="background: #FFFFFF; border-bottom: 1px solid var(--color-border-subtle); padding: 1rem 2rem; display: flex; align-items: center; justify-content: space-between; position: sticky; top: 0; z-index: 50;">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <button id="btnToggleSidebar" style="display: none; background: none; border: 1px solid var(--color-border-subtle); border-radius: 6px; padding: 0.5rem; cursor: pointer;" aria-label="Toggle Menu">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="3" y1="12" x2="21" y2="12"></line>
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <line x1="3" y1="18" x2="21" y2="18"></line>
                    </svg>
                </button>
                <div>
                    <span style="font-size: 0.8rem; color: #6D7870;">Portal Operasional Posko</span>
                    <h1 style="font-family: var(--font-heading); font-size: 1.45rem; font-weight: 700; color: var(--color-deep-forest); margin: 0; line-height: 1.2;">
                        @yield('header_title', 'Dashboard Posko Pengaduan')
                    </h1>
                </div>
            </div>

            <div style="display: flex; align-items: center; gap: 1.25rem;">
                <div style="display: inline-flex; align-items: center; gap: 0.45rem; background: rgba(82, 107, 77, 0.1); padding: 0.35rem 0.85rem; border-radius: 9999px; border: 1px solid rgba(82, 107, 77, 0.25);">
                    <span style="width: 8px; height: 8px; background-color: #10B981; border-radius: 50%; display: inline-block;"></span>
                    <span style="font-size: 0.76rem; color: var(--color-forest-moss); font-weight: 600;">Sistem Posko Online</span>
                </div>
                <a href="{{ route('home') }}" target="_blank" style="font-size: 0.82rem; color: var(--color-deep-forest); text-decoration: none; font-weight: 500; display: flex; align-items: center; gap: 0.35rem;">
                    <span>Lihat Web</span>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                        <polyline points="15 3 21 3 21 9"></polyline>
                        <line x1="10" y1="14" x2="21" y2="3"></line>
                    </svg>
                </a>
            </div>
        </header>

        <!-- Main Body -->
        <main style="flex: 1; padding: 2rem;">
            <!-- Flash Message Alerts -->
            @if(session('success_message'))
                <div style="background-color: #ECFDF5; border: 1px solid #6EE7B7; border-left: 4px solid #10B981; border-radius: 8px; padding: 1rem 1.25rem; margin-bottom: 1.75rem; display: flex; align-items: center; gap: 0.75rem; color: #065F46;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                    <span style="font-size: 0.92rem; font-weight: 500;">{{ session('success_message') }}</span>
                </div>
            @endif

            @if(session('error_message'))
                <div style="background-color: #FEF2F2; border: 1px solid #FCA5A5; border-left: 4px solid #EF4444; border-radius: 8px; padding: 1rem 1.25rem; margin-bottom: 1.75rem; display: flex; align-items: center; gap: 0.75rem; color: #991B1B;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                    <span style="font-size: 0.92rem; font-weight: 500;">{{ session('error_message') }}</span>
                </div>
            @endif

            @yield('admin_content')
        </main>

        <!-- Admin Footer -->
        <footer style="padding: 1.25rem 2rem; border-top: 1px solid var(--color-border-subtle); background: #FFFFFF; font-size: 0.8rem; color: #6D7870; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
            <span>&copy; {{ date('Y') }} <strong>BORNEO SIAGA</strong>. Sistem Manajemen Pengaduan Karhutla.</span>
            <span style="font-style: italic; color: var(--color-forest-moss);">Kerahasiaan data pelapor dilindungi undang-undang.</span>
        </footer>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const btnToggle = document.getElementById('btnToggleSidebar');
            const sidebar = document.getElementById('adminSidebar');
            if (btnToggle && sidebar) {
                btnToggle.addEventListener('click', function () {
                    sidebar.classList.toggle('open');
                });
            }
        });
    </script>
    @stack('admin_scripts')
</body>
</html>
