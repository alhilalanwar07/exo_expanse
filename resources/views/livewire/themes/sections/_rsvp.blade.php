{{-- Section: RSVP + Countdown + Ucapan --}}
@if($invitation->enable_rsvp || $invitation->enable_wishes)
<div id="slide-{{ $slideIndex }}" data-index="{{ $slideIndex }}" class="satumomen_slide">
    <div class="container-mobile">
        <div class="frame">
            @if(!empty($frame['tl']))<img class="frame-tl animate__animated animate__fadeInTopLeft animate__slow" src="{{ asset($frame['tl']) }}" alt="frame">@endif
            @if(!empty($frame['tr']))<img class="frame-tr animate__animated animate__fadeInTopRight animate__slow" src="{{ asset($frame['tr']) }}" alt="frame">@endif
        </div>

        <div class="slide-content text-center py-10" x-data="{
            invitationId: {{ $invitation->id }},
            name: '{{ request('kpd', '') }}',
            message: '',
            status: 'confirmed',
            pax: 1,
            loading: false,
            submitted: false,
            error: '',
            wishes: [],

            async submitForm() {
                if (!this.name.trim() || !this.message.trim()) {
                    this.error = 'Mohon lengkapi nama dan ucapan Anda.';
                    return;
                }
                this.loading = true;
                this.error = '';
                try {
                    const csrfToken = document.querySelector('meta[name=csrf-token]')?.content || '';
                    await fetch(`/api/invitations/${this.invitationId}/rsvp`, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                        body: JSON.stringify({ name: this.name, status: this.status, pax: this.pax })
                    });
                    const wishRes = await fetch(`/api/invitations/${this.invitationId}/wishes`, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                        body: JSON.stringify({ name: this.name, message: this.message })
                    });
                    if (wishRes.ok) {
                        const data = await wishRes.json();
                        if (data.wish) {
                            data.wish.attendance_status = this.status;
                            this.wishes.unshift(data.wish);
                        }
                        this.message = '';
                        this.submitted = true;
                    }
                } catch (e) { this.error = 'Gagal mengirim. Periksa koneksi internet.'; }
                finally { this.loading = false; }
            },
            async loadWishes() {
                try {
                    const res = await fetch(`/api/invitations/${this.invitationId}/wishes`);
                    const data = await res.json();
                    this.wishes = data.wishes || [];
                } catch (e) {}
            },
            init() { this.loadWishes(); }
        }">
            <div class="color-accent text-2xl font-latin mb-4 animate__animated animate__fadeInDown animate__slower">Do'a Untuk Pengantin</div>

            {{-- COUNTDOWN --}}
            <div class="flex justify-center gap-2 mb-6 animate__animated animate__fadeInUp animate__slower mx-auto" style="max-width:280px;" x-data="{
                days: 0, hours: 0, minutes: 0, seconds: 0,
                target: new Date('{{ $invitation->akad_date?->format('Y-m-d H:i:s') ?? now()->addDays(30)->format('Y-m-d H:i:s') }}'),
                init() {
                    setInterval(() => {
                        const diff = this.target - new Date();
                        if(diff > 0) {
                            this.days = Math.floor(diff / 86400000);
                            this.hours = Math.floor((diff % 86400000) / 3600000);
                            this.minutes = Math.floor((diff % 3600000) / 60000);
                            this.seconds = Math.floor((diff % 60000) / 1000);
                        }
                    }, 1000);
                }
            }">
                <div class="border border-[var(--inv-accent)] color-accent text-center p-2 rounded" style="width: 60px;">
                    <div class="font-bold text-xl" x-text="days">0</div>
                    <div class="text-[10px] text-white">Hari</div>
                </div>
                <div class="border border-[var(--inv-accent)] color-accent text-center p-2 rounded" style="width: 60px;">
                    <div class="font-bold text-xl" x-text="hours">0</div>
                    <div class="text-[10px] text-white">Jam</div>
                </div>
                <div class="border border-[var(--inv-accent)] color-accent text-center p-2 rounded" style="width: 60px;">
                    <div class="font-bold text-xl" x-text="minutes">0</div>
                    <div class="text-[10px] text-white">Menit</div>
                </div>
                <div class="border border-[var(--inv-accent)] color-accent text-center p-2 rounded" style="width: 60px;">
                    <div class="font-bold text-xl" x-text="seconds">0</div>
                    <div class="text-[10px] text-white">Detik</div>
                </div>
            </div>

            {{-- THANK YOU --}}
            <div x-show="submitted" x-transition class="mx-4 mb-4 p-5 rounded-xl text-center animate__animated animate__fadeIn" style="background: rgba(212,176,81,0.1); border: 1px solid rgba(212,176,81,0.25);">
                <div class="color-accent text-3xl mb-2">&#10003;</div>
                <div class="color-accent font-medium text-sm mb-1">Terima kasih!</div>
                <div class="text-xs text-white/70">Ucapan dan doa Anda telah tersimpan.</div>
            </div>

            {{-- RSVP FORM --}}
            <div x-show="!submitted" x-transition class="bg-[var(--btn-color,#3d0d19)] p-5 rounded-xl border border-[var(--inv-accent)] mx-4 text-left animate__animated animate__fadeInUp animate__slower">
                <div x-show="error" x-transition class="mb-4 p-3 rounded-lg text-center" style="background: rgba(220,38,38,0.15); border: 1px solid rgba(220,38,38,0.3);">
                    <span class="text-red-300 text-sm" x-text="error"></span>
                </div>
                <form @submit.prevent="submitForm">
                    <div class="mb-3">
                        <label class="block text-xs color-accent mb-1 font-medium">Nama Lengkap</label>
                        <input type="text" x-model="name" class="form-input" placeholder="Masukkan nama Anda">
                    </div>
                    <div class="mb-3">
                        <label class="block text-xs color-accent mb-2 font-medium">Konfirmasi Kehadiran</label>
                        <div class="flex gap-2">
                            <button type="button" @click="status = 'confirmed'"
                                class="flex-1 py-2.5 px-3 rounded-lg text-sm font-medium flex items-center justify-center gap-2 transition-all duration-200 border"
                                :class="status === 'confirmed'
                                    ? 'bg-[rgba(212,176,81,0.25)] border-[var(--inv-accent)] color-accent'
                                    : 'bg-transparent border-[rgba(255,255,255,0.15)] text-white/50 hover:border-[rgba(255,255,255,0.3)]'">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Hadir
                            </button>
                            <button type="button" @click="status = 'declined'"
                                class="flex-1 py-2.5 px-3 rounded-lg text-sm font-medium flex items-center justify-center gap-2 transition-all duration-200 border"
                                :class="status === 'declined'
                                    ? 'bg-[rgba(220,38,38,0.2)] border-red-400/50 text-red-300'
                                    : 'bg-transparent border-[rgba(255,255,255,0.15)] text-white/50 hover:border-[rgba(255,255,255,0.3)]'">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                Tidak Hadir
                            </button>
                        </div>
                    </div>
                    <div class="mb-3" x-show="status === 'confirmed'" x-transition>
                        <label class="block text-xs color-accent mb-1 font-medium">Jumlah Tamu</label>
                        <select x-model="pax" class="form-input" style="color: var(--inv-accent); background: rgba(255,255,255,0.08);">
                            <option value="1" style="color:#000;">1 Orang</option>
                            <option value="2" style="color:#000;">2 Orang</option>
                            <option value="3" style="color:#000;">3 Orang</option>
                            <option value="4" style="color:#000;">4 Orang</option>
                            <option value="5" style="color:#000;">5 Orang</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-xs color-accent mb-1 font-medium">Ucapan & Doa</label>
                        <textarea x-model="message" class="form-input" rows="3" placeholder="Tulis ucapan dan doa terbaik Anda..."></textarea>
                    </div>
                    <button type="submit" :disabled="loading" class="btn-primary w-full flex items-center justify-center gap-2">
                        <template x-if="!loading">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                                Kirim Ucapan
                            </span>
                        </template>
                        <template x-if="loading"><span>Mengirim...</span></template>
                    </button>
                </form>
            </div>

            {{-- Wishes List --}}
            <h3 class="text-xs color-accent font-medium uppercase tracking-wider mt-5 mb-3 px-4 text-left" x-show="wishes.length > 0">Ucapan Terbaru</h3>
            <div class="mt-4 px-4 pb-20 text-left overflow-y-auto max-h-[30vh]">
                <template x-for="wish in wishes" :key="wish.id">
                    <div class="bg-[rgba(255,255,255,0.05)] border-l-2 border-[var(--inv-accent)] p-3 mb-3 rounded shadow-sm">
                        <div class="font-bold text-sm color-accent flex justify-between">
                            <span x-text="wish.name"></span>
                            <span class="text-xs opacity-50 text-white font-normal" x-text="wish.time || ''"></span>
                        </div>
                        <div class="text-sm opacity-90 mt-1 mb-2" x-text="wish.message"></div>
                        <template x-if="wish.attendance_status">
                            <span class="text-[10px] px-2 py-1 rounded inline-flex items-center gap-1"
                                :class="wish.attendance_status === 'confirmed' ? 'bg-[rgba(212,176,81,0.2)] color-accent' : 'bg-[rgba(220,38,38,0.15)] text-red-300'">
                                <template x-if="wish.attendance_status === 'confirmed'"><span>&#10003; Akan Hadir</span></template>
                                <template x-if="wish.attendance_status === 'declined'"><span>&#10007; Tidak Hadir</span></template>
                            </span>
                        </template>
                    </div>
                </template>
                <div x-show="wishes.length === 0" class="text-center opacity-50 py-4 text-sm">
                    Belum ada ucapan. Jadilah yang pertama!
                </div>
            </div>
        </div>
    </div>
</div>
@endif
