<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Theme Preview</title>
    @stack('fonts')
    @stack('styles')
    @livewireStyles
</head>
<body style="margin:0; padding:0; overflow:hidden;">
    {{ $slot }}
    @livewireScripts
</body>
</html>
