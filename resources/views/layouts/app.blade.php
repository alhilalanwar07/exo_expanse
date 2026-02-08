<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ $title ?? 'Dashboard - ExoInvite' }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=outfit:300,400,500,600,700,800&display=swap" rel="stylesheet" />
    
    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>
<body class="font-outfit antialiased bg-slate-50 dark:bg-slate-900">
    <div class="min-h-screen">
        <!-- Navigation -->
        <nav class="bg-white dark:bg-slate-800 border-b border-slate-200 dark:border-slate-700">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                            <span class="text-2xl font-bold bg-gradient-to-r from-rose-500 to-amber-500 bg-clip-text text-transparent">
                                ExoInvite
                            </span>
                        </a>
                    </div>
                    
                    <div class="flex items-center gap-4">
                        <a href="{{ route('invitations.create') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-rose-500 to-amber-500 text-white text-sm font-medium rounded-lg hover:opacity-90 transition-opacity">
                            + Buat Undangan
                        </a>
                        
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center gap-2 text-slate-700 dark:text-slate-200">
                                <span>{{ auth()->user()->name ?? 'User' }}</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            
                            <div x-show="open" @click.away="open = false" x-cloak
                                class="absolute right-0 mt-2 w-48 bg-white dark:bg-slate-800 rounded-lg shadow-lg border border-slate-200 dark:border-slate-700 py-1">
                                <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-700">
                                    Dashboard
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-700">
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main class="py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                {{ $slot }}
            </div>
        </main>
    </div>
    

</body>
</html>
