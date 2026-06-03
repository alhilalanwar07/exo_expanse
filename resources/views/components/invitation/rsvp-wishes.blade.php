@props(['invitation', 'theme' => 'default'])

<section class="py-24 {{ $theme === 'dark' ? 'bg-slate-900 text-white' : 'bg-slate-50' }}">
    <div class="container mx-auto px-6 max-w-4xl">
        <div class="text-center mb-16">
            <h2 class="font-heading text-5xl {{ $theme === 'dark' ? 'text-white' : 'text-slate-900' }} mb-4">RSVP & Ucapan</h2>
            <p class="font-body {{ $theme === 'dark' ? 'text-slate-400' : 'text-slate-500' }}">Konfirmasi kehadiran dan kirimkan ucapan & doa terbaik Anda</p>
            <div class="w-16 h-1 {{ $theme === 'dark' ? 'bg-white/20' : 'bg-slate-200' }} mx-auto mt-4"></div>
        </div>

        @if($invitation->enable_rsvp)
            <div class="mb-12">
                <livewire:invitation.rsvp-form :invitation="$invitation" :theme="$theme" />
            </div>
        @endif

        @if($invitation->enable_wishes)
            <livewire:invitation.wishes :invitation="$invitation" :theme="$theme" />
        @endif
    </div>
</section>
