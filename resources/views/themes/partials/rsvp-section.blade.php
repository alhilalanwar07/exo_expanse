{{-- RSVP Section Partial --}}
@php
$themeStyles = [
    'rose' => ['bg' => 'bg-white', 'accent' => 'rose', 'text' => 'text-rose-dark'],
    'rustic' => ['bg' => 'rustic-gradient', 'accent' => 'amber', 'text' => 'text-rustic'],
    'minimal' => ['bg' => 'bg-gray-50', 'accent' => 'gray', 'text' => 'text-gray-900'],
    'royal' => ['bg' => 'bg-slate-900', 'accent' => 'yellow', 'text' => 'text-white'],
];
$style = $themeStyles[$theme] ?? $themeStyles['rose'];
@endphp

<section class="py-20 {{ $style['bg'] }}">
    <div class="max-w-2xl mx-auto px-6">
        <h2 class="text-center text-3xl font-bold {{ $style['text'] }} mb-4">Konfirmasi Kehadiran</h2>
        <p class="text-center opacity-60 {{ $style['text'] }} mb-10">Mohon konfirmasi kehadiran Anda</p>
        
        <livewire:invitation.rsvp-form 
            :invitation="$invitation" 
            :guest="$guest" 
            :theme="$theme" 
        />
    </div>
</section>
