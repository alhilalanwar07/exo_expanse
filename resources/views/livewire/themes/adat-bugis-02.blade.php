@include('livewire.themes.3d-adat-bugis-02', [
    'invitation' => $invitation,
    'metadata' => $metadata ?? null,
    'wishes' => $wishes ?? $invitation->wishes()->latest()->take(20)->get(),
    'totalWishesCount' => $totalWishesCount ?? $invitation->wishes()->count(),
    'guestName' => $guestName ?? request('to', request('kpd', 'Tamu Undangan'))
])
