<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ $title ?? 'ExoInvite - Premium Digital Invitations' }}</title>
    <meta name="description" content="{{ $metaDescription ?? 'Create beautiful digital invitations for weddings, birthdays, and special events. Eco-friendly, customizable, and stunning.' }}">
    
    @if(isset($metaKeywords))
    <meta name="keywords" content="{{ $metaKeywords }}">
    @endif

    @if(isset($canonicalUrl))
    <link rel="canonical" href="{{ $canonicalUrl }}">
    @endif

    {{-- Open Graph --}}
    <meta property="og:type" content="{{ $ogType ?? 'website' }}">
    <meta property="og:title" content="{{ $title ?? 'ExoInvite - Premium Digital Invitations' }}">
    <meta property="og:description" content="{{ $metaDescription ?? 'Create beautiful digital invitations for weddings, birthdays, and special events.' }}">
    <meta property="og:url" content="{{ $canonicalUrl ?? url()->current() }}">
    <meta property="og:site_name" content="ExoInvite">
    @if(isset($ogImage))
    <meta property="og:image" content="{{ $ogImage }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    @endif

    {{-- Twitter Card --}}
    <meta name="twitter:card" content="{{ isset($ogImage) ? 'summary_large_image' : 'summary' }}">
    <meta name="twitter:title" content="{{ $title ?? 'ExoInvite' }}">
    <meta name="twitter:description" content="{{ $metaDescription ?? 'Create beautiful digital invitations for weddings, birthdays, and special events.' }}">
    @if(isset($ogImage))
    <meta name="twitter:image" content="{{ $ogImage }}">
    @endif

    @stack('seo')
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=catchy-mager:400|cormorant-garamond:400,500,600,700|great-vibes:400|outfit:300,400,500,600,700,800|playfair-display:400,500,600,700,800&display=swap" rel="stylesheet" />
    
    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-outfit antialiased">
    {{ $slot }}
</body>
</html>
