<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Dynamic Metadata for SEO & WhatsApp Sharing --}}
    <title>{{ $metadata['title'] ?? 'Wedding Invitation' }}</title>
    <meta name="description" content="{{ $metadata['description'] ?? 'Join us in celebrating our special day!' }}">
    
    {{-- Open Graph / Facebook / WhatsApp --}}
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="{{ $metadata['title'] ?? 'Wedding Invitation' }}">
    <meta property="og:description" content="{{ $metadata['description'] ?? 'Join us in celebrating our special day!' }}">
    <meta property="og:image" content="{{ $metadata['image'] ?? asset('images/default-share.jpg') }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">

    {{-- Twitter --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $metadata['title'] ?? 'Wedding Invitation' }}">
    <meta name="twitter:description" content="{{ $metadata['description'] ?? 'Join us in celebrating our special day!' }}">
    <meta name="twitter:image" content="{{ $metadata['image'] ?? asset('images/default-share.jpg') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=catchy-mager:400|cormorant-garamond:400,500,600,700|great-vibes:400|outfit:300,400,500,600,700,800|playfair-display:400,500,600,700,800&display=swap" rel="stylesheet" />
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-gray-900 flex justify-center min-h-screen">
    <div class="w-full max-w-[480px] bg-white shadow-2xl relative min-h-screen">
        @yield('content')
    </div>
</body>
</html>
