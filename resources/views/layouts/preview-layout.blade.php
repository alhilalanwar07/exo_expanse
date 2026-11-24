<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Theme Preview - ExoInvite</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    @stack('fonts')
    @stack('styles')
    @livewireStyles
</head>
<body style="margin:0; padding:0; overflow:hidden;">
    {{ $slot }}
    @stack('alpine-components')
    @livewireScripts
    @stack('scripts')
</body>
</html>
