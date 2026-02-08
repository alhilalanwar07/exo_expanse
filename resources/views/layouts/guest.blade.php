<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ $title ?? 'ExoInvite - Premium Digital Invitations' }}</title>
    <meta name="description" content="Create beautiful digital invitations for weddings, birthdays, and special events. Eco-friendly, customizable, and stunning.">
    
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
