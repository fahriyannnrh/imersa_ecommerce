<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'PT Imersa - Katalog Clothing & Fashion Original')</title>
    <meta name="description" content="@yield('meta_description', 'Katalog resmi pakaian dan fashion PT Imersa. Temukan koleksi kaos, kemeja, celana, jaket, dan pesan langsung melalui Official Shopee Store.')">
    
    {{-- Favicon / Brand Symbol --}}
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    
    {{-- Stylesheets --}}
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    @stack('styles')
</head>
<body>
    {{-- Header / Navbar --}}
    <x-navbar />

    {{-- Main Page Content --}}
    <main style="flex-grow: 1;">
        @yield('content')
    </main>

    {{-- Footer --}}
    <x-footer />

    {{-- Image Lightbox Modal --}}
    <div class="image-modal" id="imageModal">
        <button class="image-modal-close" id="modalClose" aria-label="Tutup">&times;</button>
        <img id="modalImage" src="" alt="Preview Foto">
    </div>

    {{-- Scripts --}}
    <script src="{{ asset('js/app.js') }}"></script>
    @stack('scripts')
</body>
</html>
