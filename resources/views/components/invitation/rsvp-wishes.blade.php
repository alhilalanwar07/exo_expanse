@props([
    'invitation',
    'theme' => 'default', // default, gold, rose, sage, dark
])

@php
$themes = [
    'default' => [
        'bg' => 'bg-slate-50',
        'card' => 'bg-white border-slate-200',
        'input' => 'border-slate-300 focus:border-slate-500 focus:ring-slate-500',
        'btn_primary' => 'bg-slate-800 hover:bg-slate-900 text-white',
        'btn_secondary' => 'bg-slate-100 hover:bg-slate-200 text-slate-700',
        'text' => 'text-slate-800',
        'text_muted' => 'text-slate-500',
        'accent' => 'text-slate-600',
        'avatar' => 'bg-slate-200 text-slate-600',
        'stats_bg' => 'bg-slate-100',
    ],
    'gold' => [
        'bg' => 'bg-amber-50/50',
        'card' => 'bg-white border-amber-200/50',
        'input' => 'border-amber-300 focus:border-amber-500 focus:ring-amber-500',
        'btn_primary' => 'bg-gradient-to-r from-amber-600 to-yellow-500 hover:from-amber-700 hover:to-yellow-600 text-white',
        'btn_secondary' => 'bg-amber-100 hover:bg-amber-200 text-amber-800',
        'text' => 'text-amber-900',
        'text_muted' => 'text-amber-700/70',
        'accent' => 'text-amber-600',
        'avatar' => 'bg-gradient-to-br from-amber-400 to-yellow-500 text-white',
        'stats_bg' => 'bg-amber-100/50',
    ],
    'rose' => [
        'bg' => 'bg-rose-50/50',
        'card' => 'bg-white border-rose-200/50',
        'input' => 'border-rose-300 focus:border-rose-500 focus:ring-rose-500',
        'btn_primary' => 'bg-gradient-to-r from-rose-500 to-pink-500 hover:from-rose-600 hover:to-pink-600 text-white',
        'btn_secondary' => 'bg-rose-100 hover:bg-rose-200 text-rose-800',
        'text' => 'text-rose-900',
        'text_muted' => 'text-rose-700/70',
        'accent' => 'text-rose-500',
        'avatar' => 'bg-gradient-to-br from-rose-400 to-pink-500 text-white',
        'stats_bg' => 'bg-rose-100/50',
    ],
    'sage' => [
        'bg' => 'bg-emerald-50/50',
        'card' => 'bg-white border-emerald-200/50',
        'input' => 'border-emerald-300 focus:border-emerald-500 focus:ring-emerald-500',
        'btn_primary' => 'bg-gradient-to-r from-emerald-600 to-teal-500 hover:from-emerald-700 hover:to-teal-600 text-white',
        'btn_secondary' => 'bg-emerald-100 hover:bg-emerald-200 text-emerald-800',
        'text' => 'text-emerald-900',
        'text_muted' => 'text-emerald-700/70',
        'accent' => 'text-emerald-600',
        'avatar' => 'bg-gradient-to-br from-emerald-400 to-teal-500 text-white',
        'stats_bg' => 'bg-emerald-100/50',
    ],
    'dark' => [
        'bg' => 'bg-slate-900',
        'card' => 'bg-slate-800 border-slate-700',
        'input' => 'bg-slate-700 border-slate-600 focus:border-amber-500 focus:ring-amber-500 text-white placeholder-slate-400',
        'btn_primary' => 'bg-gradient-to-r from-amber-500 to-yellow-500 hover:from-amber-600 hover:to-yellow-600 text-slate-900',
        'btn_secondary' => 'bg-slate-700 hover:bg-slate-600 text-slate-200',
        'text' => 'text-white',
        'text_muted' => 'text-slate-400',
        'accent' => 'text-amber-400',
        'avatar' => 'bg-gradient-to-br from-amber-400 to-yellow-500 text-slate-900',
        'stats_bg' => 'bg-slate-800',
    ],
];

$s = $themes[$theme] ?? $themes['default'];
@endphp

<section id="rsvp-wishes" class="py-16 {{ $s['bg'] }}"
    x-data="{
        invitationId: {{ $invitation->id }},
        
        // Form data
        name: '{{ request('kpd', '') }}',
        message: '',
        status: 'confirmed',
        pax: 1,
        
        // State
        loading: false,
        success: false,
        error: '',
        
        // Wishes
        wishes: [],
        totalWishes: 0,
        
        // Stats
        stats: { total_wishes: 0, total_confirmed: 0 },
        
        // Methods
        async submitForm() {
            if (!this.name.trim() || !this.message.trim()) {
                this.error = 'Mohon lengkapi nama dan ucapan Anda.';
                return;
            }
            
            this.loading = true;
            this.error = '';
            
            try {
                // Submit RSVP
                const rsvpRes = await fetch(`/api/invitations/${this.invitationId}/rsvp`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content || ''
                    },
                    body: JSON.stringify({
                        name: this.name,
                        status: this.status,
                        pax: this.pax
                    })
                });
                
                // Submit Wish
                const wishRes = await fetch(`/api/invitations/${this.invitationId}/wishes`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content || ''
                    },
                    body: JSON.stringify({
                        name: this.name,
                        message: this.message
                    })
                });
                
                if (wishRes.ok) {
                    const data = await wishRes.json();
                    this.wishes.unshift(data.wish);
                    this.totalWishes++;
                    this.stats.total_wishes++;
                    if (this.status === 'confirmed') {
                        this.stats.total_confirmed += this.pax;
                    }
                    this.message = '';
                    this.success = true;
                    setTimeout(() => this.success = false, 5000);
                } else {
                    const err = await wishRes.json();
                    this.error = err.message || 'Terjadi kesalahan. Silakan coba lagi.';
                }
            } catch (e) {
                this.error = 'Gagal mengirim. Periksa koneksi internet Anda.';
            } finally {
                this.loading = false;
            }
        },
        
        async loadWishes() {
            try {
                const res = await fetch(`/api/invitations/${this.invitationId}/wishes`);
                const data = await res.json();
                this.wishes = data.wishes || [];
                this.totalWishes = data.total || 0;
            } catch (e) {
                console.error('Failed to load wishes:', e);
            }
        },
        
        async loadStats() {
            try {
                const res = await fetch(`/api/invitations/${this.invitationId}/stats`);
                this.stats = await res.json();
            } catch (e) {
                console.error('Failed to load stats:', e);
            }
        },
        
        init() {
            this.loadWishes();
            this.loadStats();
        }
    }"
>
    <div class="max-w-lg mx-auto px-6">
        {{-- Header --}}
        <div class="text-center mb-10">
            <h2 class="font-serif text-3xl font-bold {{ $s['text'] }} mb-2">Ucapan & Kehadiran</h2>
            <p class="{{ $s['text_muted'] }} text-sm">Sampaikan ucapan dan konfirmasi kehadiran Anda</p>
        </div>

        {{-- Stats --}}
        <div class="grid grid-cols-2 gap-4 mb-8">
            <div class="{{ $s['stats_bg'] }} rounded-xl p-4 text-center">
                <div class="text-2xl font-bold {{ $s['accent'] }}" x-text="stats.total_wishes">0</div>
                <div class="{{ $s['text_muted'] }} text-xs uppercase tracking-wider">Ucapan</div>
            </div>
            <div class="{{ $s['stats_bg'] }} rounded-xl p-4 text-center">
                <div class="text-2xl font-bold {{ $s['accent'] }}" x-text="stats.total_confirmed">0</div>
                <div class="{{ $s['text_muted'] }} text-xs uppercase tracking-wider">Tamu Hadir</div>
            </div>
        </div>

        {{-- Form Card --}}
        <div class="{{ $s['card'] }} border rounded-2xl p-6 shadow-lg mb-8">
            {{-- Success Message --}}
            <div x-show="success" x-transition class="mb-6 p-4 bg-emerald-100 border border-emerald-300 rounded-xl text-emerald-700 text-sm flex items-center gap-2">
                <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                <span>Terima kasih! Ucapan dan konfirmasi Anda telah tersimpan.</span>
            </div>

            {{-- Error Message --}}
            <div x-show="error" x-transition class="mb-6 p-4 bg-red-100 border border-red-300 rounded-xl text-red-700 text-sm flex items-center gap-2">
                <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                <span x-text="error"></span>
            </div>

            <form @submit.prevent="submitForm" class="space-y-5">
                {{-- Name --}}
                <div>
                    <label class="block text-sm font-medium {{ $s['text'] }} mb-2">Nama Lengkap</label>
                    <input type="text" x-model="name" placeholder="Masukkan nama Anda"
                        class="w-full px-4 py-3 rounded-xl border {{ $s['input'] }} transition-all">
                </div>

                {{-- Message --}}
                <div>
                    <label class="block text-sm font-medium {{ $s['text'] }} mb-2">Ucapan & Doa</label>
                    <textarea x-model="message" rows="3" placeholder="Tulis ucapan dan doa Anda..."
                        class="w-full px-4 py-3 rounded-xl border {{ $s['input'] }} transition-all resize-none"></textarea>
                </div>

                {{-- Attendance --}}
                <div>
                    <label class="block text-sm font-medium {{ $s['text'] }} mb-3">Konfirmasi Kehadiran</label>
                    <div class="grid grid-cols-2 gap-3">
                        <button type="button" @click="status = 'confirmed'"
                            :class="status === 'confirmed' ? '{{ $s['btn_primary'] }} ring-2 ring-offset-2' : '{{ $s['btn_secondary'] }}'"
                            class="py-3 px-4 rounded-xl font-medium transition-all flex items-center justify-center gap-2">
                            <span>✓</span> Hadir
                        </button>
                        <button type="button" @click="status = 'declined'"
                            :class="status === 'declined' ? '{{ $s['btn_primary'] }} ring-2 ring-offset-2' : '{{ $s['btn_secondary'] }}'"
                            class="py-3 px-4 rounded-xl font-medium transition-all flex items-center justify-center gap-2">
                            <span>✗</span> Tidak Hadir
                        </button>
                    </div>
                </div>

                {{-- Number of Guests --}}
                <div x-show="status === 'confirmed'" x-transition class="pt-2">
                    <label class="block text-sm font-medium {{ $s['text'] }} mb-2">Jumlah Tamu</label>
                    <select x-model="pax" class="w-full px-4 py-3 rounded-xl border {{ $s['input'] }} transition-all">
                        <option value="1">1 Orang</option>
                        <option value="2">2 Orang</option>
                        <option value="3">3 Orang</option>
                        <option value="4">4 Orang</option>
                        <option value="5">5 Orang</option>
                    </select>
                </div>

                {{-- Submit --}}
                <button type="submit" :disabled="loading"
                    class="w-full py-4 {{ $s['btn_primary'] }} rounded-xl font-bold tracking-wide transition-all shadow-lg disabled:opacity-50">
                    <span x-show="!loading">Kirim Ucapan & Konfirmasi</span>
                    <span x-show="loading" class="flex items-center justify-center gap-2">
                        <svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                        </svg>
                        Mengirim...
                    </span>
                </button>
            </form>
        </div>

        {{-- Wishes List --}}
        <div class="space-y-4">
            <h3 class="font-medium {{ $s['text'] }} text-sm uppercase tracking-wider">Ucapan Terbaru</h3>
            
            <template x-for="wish in wishes" :key="wish.id">
                <div class="{{ $s['card'] }} border rounded-xl p-4 animate-fade-in">
                    <div class="flex gap-3">
                        <div class="w-10 h-10 rounded-full {{ $s['avatar'] }} flex items-center justify-center font-bold text-sm flex-shrink-0" x-text="wish.initial"></div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between mb-1">
                                <h4 class="font-semibold {{ $s['text'] }} text-sm truncate" x-text="wish.name"></h4>
                                <span class="{{ $s['text_muted'] }} text-xs" x-text="wish.time"></span>
                            </div>
                            <p class="{{ $s['text_muted'] }} text-sm leading-relaxed" x-text="wish.message"></p>
                        </div>
                    </div>
                </div>
            </template>

            <div x-show="wishes.length === 0" class="{{ $s['card'] }} border border-dashed rounded-xl p-8 text-center">
                <p class="{{ $s['text_muted'] }} text-sm">Belum ada ucapan. Jadilah yang pertama!</p>
            </div>
        </div>
    </div>
</section>

<style>
@keyframes fade-in {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}
.animate-fade-in {
    animation: fade-in 0.4s ease-out;
}
</style>
