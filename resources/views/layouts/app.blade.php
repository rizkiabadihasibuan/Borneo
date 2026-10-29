<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    <title>@yield('title', 'BORNEO SIAGA - Sistem Informasi & Tanggap Karhutla Kalimantan')</title>
    <meta name="description" content="@yield('meta_description', 'Portal tanggap darurat, pantauan hotspot kebakaran hutan dan lahan, pengaduan masyarakat, serta edukasi mitigasi karhutla di Kalimantan.')">
    <meta name="theme-color" content="#263B30">

    <!-- Favicon & Brand Icons -->
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo.png') }}">

    <!-- Google Fonts: Cormorant Garamond & Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,500;0,600;0,700;1,400;1,600&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>
<body>
    <!-- Navbar Component -->
    @include('partials.navbar')

    <!-- Main Content Slot -->
    <main style="flex: 1 0 auto; display: flex; flex-direction: column;">
        @yield('content')
    </main>

    <!-- Footer Component -->
    @include('partials.footer')

    @stack('scripts')
</body>
</html>
