<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Wedding Invitation')</title>
    
    <!-- SEO Meta Tags -->
    @if(isset($metadata))
        <meta name="description" content="{{ $metadata['description'] ?? 'You are invited to our wedding celebration' }}">
        <meta property="og:title" content="{{ $metadata['title'] ?? 'Wedding Invitation' }}">
        <meta property="og:description" content="{{ $metadata['description'] ?? 'You are invited to our wedding celebration' }}">
        <meta property="og:image" content="{{ $metadata['image'] ?? '' }}">
        <meta property="og:type" content="website">
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="{{ $metadata['title'] ?? 'Wedding Invitation' }}">
        <meta name="twitter:description" content="{{ $metadata['description'] ?? 'You are invited to our wedding celebration' }}">
        <meta name="twitter:image" content="{{ $metadata['image'] ?? '' }}">
    @endif
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    
    @yield('meta')
    
    <!-- Preconnect to external resources -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="dns-prefetch" href="https://cdn.jsdelivr.net">
    
    <!-- Base Fonts (Always loaded) -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Sacramento&family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <!-- Additional Fonts Stack (Theme-specific fonts will be added here) -->
    @stack('fonts')
    
    <!-- Scripts and Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    

    
    <!-- Base Styles -->
    <style>
        /* Alpine.js cloak */
        [x-cloak] { 
            display: none !important; 
        }
        
        /* CSS Variables for Base Fonts */
        :root {
            --font-heading: 'Playfair Display', serif;
            --font-body: 'Inter', sans-serif;
            --font-accent: 'Sacramento', cursive;
            --font-outfit: 'Outfit', sans-serif;
        }
        
        /* Base Font Classes */
        .font-heading { font-family: var(--font-heading); }
        .font-body { font-family: var(--font-body); }
        .font-accent { font-family: var(--font-accent); }
        .font-outfit { font-family: var(--font-outfit); }
        
        /* Smooth Scrolling */
        html {
            scroll-behavior: smooth;
        }
        
        /* Prevent horizontal scroll */
        body {
            overflow-x: hidden;
        }
        
        /* Selection Color - Can be overridden by themes */
        ::selection {
            background-color: rgba(0, 0, 0, 0.1);
        }
        
        /* Accessibility - Focus visible */
        *:focus-visible {
            outline: 2px solid currentColor;
            outline-offset: 2px;
        }
        
        /* Print styles */
        @media print {
            .no-print {
                display: none !important;
            }
        }
    </style>
    
    <!-- Theme-specific Styles Stack -->
    @stack('styles')
    
    <!-- Analytics (if needed) -->
    @stack('analytics')


</head>

<body class="antialiased bg-slate-50 text-slate-900 font-body overflow-x-hidden">
    
    <!-- Main Content Slot -->
    {{ $slot }}
    
    <!-- Additional Scripts Stack -->
    @stack('scripts')


</body>
</html>