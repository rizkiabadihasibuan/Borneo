<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk Posko Admin - BORNEO SIAGA</title>
    <meta name="theme-color" content="#263B30">

    <!-- Favicon & Brand Icons -->
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body style="min-height: 100vh; margin: 0; background-color: var(--color-warm-paper); display: flex; flex-direction: column; justify-content: center; align-items: center; padding: 2rem 1rem; font-family: var(--font-body); color: var(--color-charcoal);">

    <!-- Card Login Editorial -->
    <div style="width: 100%; max-width: 440px; background: #FFFFFF; border-radius: 14px; border: 1px solid var(--color-border-subtle); box-shadow: var(--shadow-editorial); overflow: hidden;">
        
        <!-- Header Brand Panel -->
        <div style="background-color: var(--color-deep-forest); color: var(--color-warm-paper); padding: 2.5rem 2rem 2rem; text-align: center;">
            <div style="width: 115px; height: 115px; margin: 0 auto 1.5rem; border-radius: 50%; overflow: hidden; background: #FFFFFF; border: 3.5px solid var(--color-earth-sand); box-shadow: 0 6px 20px rgba(0,0,0,0.4); display: flex; align-items: center; justify-content: center;">
                <img src="{{ asset('images/logo.png') }}" alt="Logo Borneo Siaga" style="width: 100%; height: 100%; object-fit: cover; display: block;">
            </div>
            <span style="font-family: var(--font-heading); font-size: 1.65rem; font-weight: 700; color: var(--color-earth-sand); letter-spacing: 0.04em; display: block; line-height: 1.1;">
                BORNEO SIAGA
            </span>
            <span style="font-size: 0.78rem; color: #a4b8ab; text-transform: uppercase; letter-spacing: 0.1em; display: block; margin-top: 0.35rem;">
                Portal Autentikasi Posko
            </span>
        </div>

        <!-- Form Body -->
        <div style="padding: 2.25rem 2rem 2rem;">

            <!-- Error Alerts -->
            @if(session('error_message'))
                <div style="background-color: #FEF2F2; border: 1px solid #FCA5A5; border-radius: 8px; padding: 0.85rem 1rem; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.65rem; color: #991B1B; font-size: 0.88rem;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                    <span>{{ session('error_message') }}</span>
                </div>
            @endif

            @if(session('success_message'))
                <div style="background-color: #ECFDF5; border: 1px solid #6EE7B7; border-radius: 8px; padding: 0.85rem 1rem; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.65rem; color: #065F46; font-size: 0.88rem;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                    <span>{{ session('success_message') }}</span>
                </div>
            @endif

            <form action="{{ route('admin.login.submit') }}" method="POST">
                @csrf

                <!-- Email Input -->
                <div style="margin-bottom: 1.35rem;">
                    <label for="email" style="display: block; font-weight: 600; font-size: 0.88rem; color: var(--color-deep-forest); margin-bottom: 0.4rem;">
                        Alamat Email Posko <span style="color: #B83B26;">*</span>
                    </label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           value="{{ old('email') }}" 
                           placeholder="nama@borneosiaga.id" 
                           required 
                           autofocus
                           style="width: 100%; padding: 0.75rem 1rem; border: 1px solid {{ $errors->has('email') ? '#B83B26' : 'var(--color-border-subtle)' }}; border-radius: 8px; font-size: 0.95rem; background: #FAF9F5;">
                    @error('email')
                        <span style="font-size: 0.78rem; color: #B83B26; margin-top: 0.35rem; display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Password Input -->
                <div style="margin-bottom: 1.35rem;">
                    <label for="password" style="display: block; font-weight: 600; font-size: 0.88rem; color: var(--color-deep-forest); margin-bottom: 0.4rem;">
                        Kata Sandi <span style="color: #B83B26;">*</span>
                    </label>
                    <input type="password" 
                           id="password" 
                           name="password" 
                           placeholder="••••••••" 
                           required 
                           style="width: 100%; padding: 0.75rem 1rem; border: 1px solid {{ $errors->has('password') ? '#B83B26' : 'var(--color-border-subtle)' }}; border-radius: 8px; font-size: 0.95rem; background: #FAF9F5;">
                    @error('password')
                        <span style="font-size: 0.78rem; color: #B83B26; margin-top: 0.35rem; display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.75rem;">
                    <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.85rem; color: #526B4D; cursor: pointer;">
                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }} style="accent-color: var(--color-forest-moss); width: 16px; height: 16px;">
                        <span>Ingat sesi perangkat ini</span>
                    </label>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-forest" style="width: 100%; padding: 0.85rem 1rem; font-size: 0.95rem; display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path>
                        <polyline points="10 17 15 12 10 7"></polyline>
                        <line x1="15" y1="12" x2="3" y2="12"></line>
                    </svg>
                    <span>Masuk ke Dashboard</span>
                </button>
            </form>

            <div style="margin-top: 1.75rem; text-align: center; border-top: 1px solid var(--color-border-subtle); padding-top: 1.25rem;">
                <a href="{{ route('home') }}" style="font-size: 0.82rem; color: #6D7870; text-decoration: none; display: inline-flex; align-items: center; gap: 0.35rem;">
                    <span>&larr; Kembali ke Beranda Utama</span>
                </a>
            </div>
        </div>

    </div>

    <!-- Security Notice -->
    <div style="margin-top: 1.5rem; text-align: center; max-width: 440px;">
        <span style="font-size: 0.76rem; color: #7F8B83; line-height: 1.5; display: block;">
            Portal ini dibatasi hanya untuk staf posko dan personel tanggap darurat resmi. Akses tanpa otorisasi diawasi dan dilarang.
        </span>
    </div>

</body>
</html>
