<div class="theme-page-router relative">
    @push('fonts')
        <link rel="stylesheet" href="{{ $this->googleFontsUrl }}">
    @endpush

    @push('styles')
        <style>
            {!! $this->themeCssVariables !!}
        </style>
    @endpush

    {{-- Demo Banner --}}
    <div class="fixed top-0 left-0 right-0 z-[999999] bg-gradient-to-r from-purple-600 to-indigo-600 text-white shadow-lg" id="demo-banner">
        <div class="max-w-7xl mx-auto px-4 py-2.5 flex items-center justify-between gap-4">
            <div class="flex items-center gap-3 min-w-0">
                <span class="hidden sm:inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold bg-white/20 backdrop-blur-sm whitespace-nowrap">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    DEMO
                </span>
                <span class="text-sm font-medium truncate">Preview Tema Undangan</span>
            </div>

            <div class="flex items-center gap-2">
                {{-- Theme Selector — full page redirect so JS re-initializes --}}
                <select
                    x-data
                    x-on:change="window.location.href = '{{ route('invitation.demo') }}?theme=' + $event.target.value"
                    class="text-xs bg-white/15 border border-white/30 rounded-lg px-3 py-1.5 text-white focus:ring-2 focus:ring-white/30 focus:border-white/50 max-w-[180px] backdrop-blur-sm [&>option]:text-slate-900"
                >
                    @foreach($themes as $t)
                        <option value="{{ $t['slug'] }}" @selected($t['slug'] === $themeSlug)>
                            {{ $t['name'] }}{{ $t['is_premium'] ? ' ★' : '' }}
                        </option>
                    @endforeach
                </select>

                <a href="{{ route('register') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white text-indigo-600 text-xs font-semibold rounded-lg hover:bg-indigo-50 transition-colors whitespace-nowrap">
                    Buat Undangan
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                </a>
            </div>
        </div>
    </div>

    {{-- Spacer for fixed banner --}}
    <div class="h-[44px]"></div>

    {{-- Theme Content --}}
    @livewire($themeComponent, ['invitation' => $invitation, 'metadata' => $metadata], key($themeSlug))
</div>
