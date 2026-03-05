@props(['metadata' => null])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <!-- Essential Meta Tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#ffffff">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    
    <!-- Title & Description -->
    <title>{{ $metadata['title'] ?? $title ?? 'Wedding Invitation' }}</title>
    <meta name="description" content="{{ $metadata['description'] ?? 'You are invited to our wedding celebration' }}">
    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
    
    <!-- Open Graph & Social Meta Tags -->
    @if(isset($metadata))
        <meta property="og:title" content="{{ $metadata['title'] ?? 'Wedding Invitation' }}">
        <meta property="og:description" content="{{ $metadata['description'] ?? 'You are invited to our wedding celebration' }}">
        <meta property="og:image" content="{{ $metadata['image'] ?? asset('images/og-default.jpg') }}">
        <meta property="og:image:alt" content="{{ $metadata['image_alt'] ?? 'Wedding Invitation' }}">
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ $metadata['url'] ?? request()->url() }}">
        <meta property="og:locale" content="{{ str_replace('_', '-', app()->getLocale()) }}">
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="{{ $metadata['title'] ?? 'Wedding Invitation' }}">
        <meta name="twitter:description" content="{{ $metadata['description'] ?? 'You are invited to our wedding celebration' }}">
        <meta name="twitter:image" content="{{ $metadata['image'] ?? asset('images/og-default.jpg') }}">
    @else
        <meta property="og:type" content="website">
    @endif
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">
    
    <!-- Custom Meta & Links Slot -->
    @yield('meta')
    
    <!-- Resource Preconnection (Performance) -->
    <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="dns-prefetch" href="https://cdn.jsdelivr.net">
    
    <!-- Google Fonts (Optimized with font-display=swap) -->
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Sacramento&family=Outfit:wght@300;400;600;700&display=swap" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Sacramento&family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet"></noscript>
    
    <!-- Additional Fonts Stack (Theme-specific) -->
    @stack('fonts')
    
    <!-- Vite CSS & JS (Includes Tailwind) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Livewire Styles (Required for functionality) -->
    @livewireStyles
    
    <!-- Critical Inline Styles -->
    <style>
        /* Alpine.js + Livewire Cloak */
        [x-cloak] { display: none !important; }
        
        /* CSS Custom Properties for Fonts */
        :root {
            --font-heading: 'Playfair Display', serif;
            --font-body: 'Inter', sans-serif;
            --font-accent: 'Sacramento', cursive;
            --font-outfit: 'Outfit', sans-serif;
            
            /* Reduced motion respect */
            --transition-duration: 300ms;
        }
        
        @media (prefers-reduced-motion: reduce) {
            :root { --transition-duration: 0ms; }
            * { animation-duration: 0.01ms !important; animation-iteration-count: 1 !important; transition-duration: 0.01ms !important; }
        }
        
        /* Font Utility Classes */
        .font-heading { font-family: var(--font-heading); font-weight: 400; }
        .font-body { font-family: var(--font-body); }
        .font-accent { font-family: var(--font-accent); font-size: 1.5rem; }
        .font-outfit { font-family: var(--font-outfit); }
        
        /* Core Layout */
        html { scroll-behavior: smooth; }
        body { overflow-x: hidden; }
        
        /* Selection Styling */
        ::selection {
            background-color: rgba(0, 0, 0, 0.1);
            color: inherit;
        }
        
        /* Accessibility - Enhanced Focus Visible */
        *:focus-visible {
            outline: 2px solid currentColor;
            outline-offset: 2px;
        }
        button:focus-visible, a:focus-visible { outline-offset: 4px; }
        
        /* Print Utilities */
        @media print {
            .no-print { display: none !important; }
            body { background: white; }
        }
    </style>
    
    <!-- Theme-specific Styles Stack -->
    @stack('styles')
    
    <!-- Analytics Stack -->
    @stack('analytics')


</head>

<body class="antialiased bg-white text-slate-900 font-body overflow-x-hidden">
    
    <!-- Main Content Slot -->
    {{ $slot }}
    
    <!-- Livewire Scripts (Required for component interactivity) -->
    @livewireScripts
    
    <!-- Alpine.js Integration with Livewire -->
    @stack('alpine-components')
    
    <!-- Additional Scripts Stack -->
    @stack('scripts')

</body>
</html>