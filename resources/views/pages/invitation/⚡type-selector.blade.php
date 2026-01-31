<?php

use App\Enums\InvitationType;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts::app')] 
class extends Component
{
    public function select(string $type): void
    {
        $invitationType = InvitationType::from($type);
        
        if (!$invitationType->isAvailable()) {
            session()->flash('error', 'Mohon maaf, tipe undangan ini belum tersedia. Coming soon!');
            return;
        }
        
        $this->redirect(route('invitations.create', ['type' => $type]), navigate: false);
    }

    public function with()
    {
        $grouped = InvitationType::grouped();
        
        return [
            'availableTypes' => $grouped['available'],
            'comingSoonTypes' => $grouped['coming_soon'],
        ];
    }
}; ?>

<div>
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-slate-900 dark:text-white">Buat Acara Baru</h1>
        <p class="text-slate-600 dark:text-slate-400 mt-2">Pilih jenis acara yang ingin Anda buat undangannya</p>
    </div>

    @if(session('error'))
        <div class="mb-6 p-4 bg-amber-100 dark:bg-amber-900/30 border border-amber-300 dark:border-amber-700 rounded-xl text-amber-700 dark:text-amber-300">
            {{ session('error') }}
        </div>
    @endif

    <!-- Available Types Section -->
    <div class="mb-10">
        <h2 class="text-lg font-semibold text-slate-800 dark:text-slate-200 mb-4 flex items-center gap-2">
            <span class="w-2 h-2 bg-green-500 rounded-full"></span>
            Tersedia
        </h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
            @foreach($availableTypes as $type)
                <button 
                    wire:click="select('{{ $type->value }}')"
                    class="group relative overflow-hidden rounded-2xl text-left transition-all duration-300 hover:scale-[1.02] hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-rose-500 focus:ring-offset-2"
                >
                    <!-- Background Gradient -->
                    <div class="absolute inset-0 bg-gradient-to-br {{ $type->gradient() }} opacity-90"></div>
                    
                    <!-- Pattern Overlay -->
                    <div class="absolute inset-0 opacity-10" style="background-image: url('data:image/svg+xml,%3Csvg width=&quot;60&quot; height=&quot;60&quot; viewBox=&quot;0 0 60 60&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;%3E%3Cg fill=&quot;none&quot; fill-rule=&quot;evenodd&quot;%3E%3Cg fill=&quot;%23ffffff&quot; fill-opacity=&quot;0.4&quot;%3E%3Cpath d=&quot;M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2v-4h4v-2h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z&quot;/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
                    
                    <!-- Content -->
                    <div class="relative p-6">
                        <div class="text-4xl mb-3">{{ $type->emoji() }}</div>
                        <h3 class="text-xl font-bold text-white mb-2">{{ $type->label() }}</h3>
                        <p class="text-white/80 text-sm leading-relaxed">{{ $type->description() }}</p>
                        
                        <!-- Arrow indicator -->
                        <div class="absolute bottom-4 right-4 w-8 h-8 bg-white/20 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </div>
                </button>
            @endforeach
        </div>
    </div>

    <!-- Coming Soon Section -->
    <div>
        <h2 class="text-lg font-semibold text-slate-800 dark:text-slate-200 mb-4 flex items-center gap-2">
            <span class="w-2 h-2 bg-amber-500 rounded-full"></span>
            Segera Hadir
        </h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
            @foreach($comingSoonTypes as $type)
                <div class="group relative overflow-hidden rounded-2xl text-left transition-all duration-300 cursor-not-allowed opacity-60">
                    <!-- Background Gradient -->
                    <div class="absolute inset-0 bg-gradient-to-br {{ $type->gradient() }} opacity-50"></div>
                    
                    <!-- Pattern Overlay -->
                    <div class="absolute inset-0 opacity-5" style="background-image: url('data:image/svg+xml,%3Csvg width=&quot;60&quot; height=&quot;60&quot; viewBox=&quot;0 0 60 60&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;%3E%3Cg fill=&quot;none&quot; fill-rule=&quot;evenodd&quot;%3E%3Cg fill=&quot;%23ffffff&quot; fill-opacity=&quot;0.4&quot;%3E%3Cpath d=&quot;M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z&quot;/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
                    
                    <!-- Coming Soon Badge -->
                    <div class="absolute top-3 right-3 px-2 py-1 bg-white/20 backdrop-blur rounded-full text-xs font-medium text-white">
                        Coming Soon
                    </div>
                    
                    <!-- Content -->
                    <div class="relative p-6">
                        <div class="text-4xl mb-3 grayscale">{{ $type->emoji() }}</div>
                        <h3 class="text-xl font-bold text-white/90 mb-2">{{ $type->label() }}</h3>
                        <p class="text-white/60 text-sm leading-relaxed">{{ $type->description() }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Back Link -->
    <div class="mt-10">
        <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 text-slate-600 dark:text-slate-400 hover:text-rose-500 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali ke Dashboard
        </a>
    </div>
</div>
