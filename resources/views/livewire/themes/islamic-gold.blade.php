<div class="theme-islamic-gold">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;600&family=Great+Vibes&family=Montserrat:wght@300;500&display=swap');
        
        :root {
            --primary: #D4AF37; /* Emas Mewah */
            --dark: #1a1a1a;
            --glass: rgba(255, 255, 255, 0.05);
            --border: rgba(255, 255, 255, 0.2);
            --blur: blur(10px);
        }

        .theme-islamic-gold {
            font-family: 'Montserrat', sans-serif;
            color: #f0f0f0;
            background-color: #050505;
            min-height: 100vh;
            position: relative;
        }

        /* --- BACKGROUND PARTICLES --- */
        #canvas-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
            background: radial-gradient(circle at center, #1a2a3a 0%, #000000 100%);
            pointer-events: none;
        }

        /* --- COVER GATE --- */
        .cover-gate {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100vh;
            background: #000;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            align-items: center;
            padding-bottom: 80px;
            transition: transform 1s cubic-bezier(0.77, 0, 0.175, 1);
        }

        .cover-bg-img {
            position: absolute;
            inset: 0;
            background-size: cover; 
            background-position: center;
            background-image: url('{{ $invitation->cover_image ? asset('storage/' . $invitation->cover_image) : 'https://images.unsplash.com/photo-1511285560982-1356c11d4606?q=80&w=1000&auto=format&fit=crop' }}');
            opacity: 0.6;
        }

        .cover-gate::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(0,0,0,1) 10%, rgba(0,0,0,0.3) 100%);
        }

        .cover-content {
            position: relative;
            z-index: 2;
            text-align: center;
            width: 80%;
            max-width: 500px;
        }

        .btn-open {
            margin-top: 20px;
            padding: 12px 30px;
            border-radius: 50px;
            background: var(--primary);
            color: #000;
            font-weight: bold;
            border: none;
            cursor: pointer;
            box-shadow: 0 0 20px rgba(212, 175, 55, 0.5);
            animation: pulse 2s infinite;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        /* --- CONTAINER --- */
        .container {
            max-width: 500px;
            margin: 0 auto;
            position: relative;
            z-index: 10;
            padding-bottom: 50px;
        }

        /* --- GLASS CARDS --- */
        .card {
            background: var(--glass);
            backdrop-filter: var(--blur);
            -webkit-backdrop-filter: var(--blur);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 2rem;
            margin: 20px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        }

        /* --- TYPOGRAPHY --- */
        .font-script {
            font-family: 'Great Vibes', cursive;
            font-size: 3.5rem;
            color: var(--primary);
            line-height: 1.2;
            text-shadow: 0 0 10px rgba(212, 175, 55, 0.3);
        }

        .font-serif {
            font-family: 'Cormorant Garamond', serif;
            letter-spacing: 1px;
        }

        .bismillah {
            font-size: 1.8rem;
            margin-bottom: 20px;
            color: #fff;
        }

        /* --- PHOTO PROFILE --- */
        .couple-img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            border: 3px solid var(--primary);
            padding: 5px;
            margin: 20px auto;
            position: relative;
        }

        .couple-img img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
        }

        /* --- COUNTDOWN --- */
        .countdown-box {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 20px;
        }
        .time-unit {
            background: rgba(212, 175, 55, 0.1);
            padding: 10px;
            border-radius: 10px;
            min-width: 60px;
            border: 1px solid var(--border);
        }
        .time-val { font-size: 1.5rem; font-weight: bold; color: var(--primary); }
        .time-label { font-size: 0.7rem; text-transform: uppercase; }

        /* --- MUSIC FAB --- */
        .music-fab {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 50px;
            height: 50px;
            background: rgba(0,0,0,0.6);
            border: 1px solid var(--primary);
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 100;
            cursor: pointer;
            color: var(--primary);
        }
        .spin { animation: spin 4s linear infinite; }

        /* --- ANIMATIONS --- */
        @keyframes pulse {
            0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(212, 175, 55, 0.7); }
            70% { transform: scale(1.05); box-shadow: 0 0 0 15px rgba(212, 175, 55, 0); }
            100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(212, 175, 55, 0); }
        }
        @keyframes spin { 100% { transform: rotate(360deg); } }

        .gold-txt { color: var(--primary); }
        .mt-20 { margin-top: 20px; }
        .mb-20 { margin-bottom: 20px; }

        /* Helpers for Alpine transitions */
        [x-cloak] { display: none !important; }
    </style>

    {{-- Canvas Background --}}
    <canvas id="canvas-bg"></canvas>

    {{-- Main Component Logic with Alpine --}}
    <div x-data="islamicGoldTheme()" x-init="initTheme()">

        {{-- Audio Player --}}
        @if($invitation->background_music)
            <audio x-ref="bgMusic" loop>
                <source src="{{ str_starts_with($invitation->background_music, 'http') ? $invitation->background_music : asset('storage/' . $invitation->background_music) }}" type="audio/mp3">
            </audio>
            
            {{-- Music Toggle FAB --}}
            <div class="music-fab" 
                 :class="{ 'spin': isPlaying }" 
                 @click="toggleMusic()" 
                 x-show="opened" 
                 x-transition:enter="transition ease-out duration-500"
                 x-transition:enter-start="opacity-0 translate-y-10"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 style="display: none;">
                <i class="fas fa-compact-disc" style="font-size: 24px;"></i>
            </div>
        @endif

        {{-- COVER GATE --}}
        <div class="cover-gate" 
             :style="opened ? 'transform: translateY(-100%)' : ''">
            <div class="cover-bg-img"></div>
            <div class="cover-content">
                <h3 class="font-serif mb-20" style="letter-spacing: 3px;">THE WEDDING OF</h3>
                @php $order = $invitation->custom_styles['name_order'] ?? 'groom_first'; @endphp
                <h1 class="font-script mb-20">
                    @if($order === 'bride_first')
                        {{ $invitation->bride_nickname }} & {{ $invitation->groom_nickname }}
                    @else
                        {{ $invitation->groom_nickname }} & {{ $invitation->bride_nickname }}
                    @endif
                </h1>
                <p style="font-size: 0.9rem; margin-bottom: 20px;">Kepada Yth. Bapak/Ibu/Saudara/i</p>
                <div style="font-weight: bold; font-size: 1.1rem; margin-bottom: 30px;">{{ $guestName }}</div>
                
                <button class="btn-open" @click="openInvitation()">
                    <i class="fas fa-envelope-open"></i> Buka Undangan
                </button>
            </div>
        </div>

        {{-- MAIN CONTENT --}}
        <div class="container" x-show="opened" x-transition:enter="transition ease-out duration-1000 delay-500" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" style="display: none;">
            
            {{-- Quote Card --}}
            <div class="card" x-intersect="$el.classList.add('visible')">
                <div class="bismillah">﷽</div>
                <p class="font-serif">Maha Suci Allah yang telah menciptakan makhluk-Nya berpasang-pasangan.</p>
                <p class="mt-20" style="font-size: 0.9rem; line-height: 1.6;">"Dan di antara tanda-tanda kekuasaan-Nya ialah Dia menciptakan untukmu isteri-isteri dari jenismu sendiri, supaya kamu cenderung dan merasa tenteram kepadanya, dan dijadikan-Nya diantaramu rasa kasih dan sayang."</p>
                <p class="mt-20 font-serif" style="font-size: 0.8rem;">(QS. Ar-Rum: 21)</p>
            </div>

            {{-- Couple Card --}}
            <div class="card">
                @php
                    $nameOrder = $invitation->custom_styles['name_order'] ?? 'groom_first';
                @endphp

                @if($nameOrder === 'bride_first')
                    {{-- Bride --}}
                    <h2 class="font-script mb-20" style="font-size: 2.5rem;">Mempelai Wanita</h2>
                    <div class="couple-img">
                        <img src="{{ $invitation->bride_photo ? asset('storage/' . $invitation->bride_photo) : 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=300&q=80' }}" alt="Bride">
                    </div>
                    <h1 class="font-serif gold-txt" style="font-size: 2rem;">{{ $invitation->bride_name }}</h1>
                    <p style="font-size: 0.9rem; margin-top: 10px;">Putri dari Bpk. {{ $invitation->bride_father }} & Ibu {{ $invitation->bride_mother }}</p>

                    <div style="font-size: 2rem; margin: 20px 0; color: var(--primary);">&</div>

                    {{-- Groom --}}
                    <h2 class="font-script mb-20" style="font-size: 2.5rem;">Mempelai Pria</h2>
                    <div class="couple-img">
                        <img src="{{ $invitation->groom_photo ? asset('storage/' . $invitation->groom_photo) : 'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?auto=format&fit=crop&w=300&q=80' }}" alt="Groom">
                    </div>
                    <h1 class="font-serif gold-txt" style="font-size: 2rem;">{{ $invitation->groom_name }}</h1>
                    <p style="font-size: 0.9rem; margin-top: 10px;">Putra dari Bpk. {{ $invitation->groom_father }} & Ibu {{ $invitation->groom_mother }}</p>
                @else
                    {{-- Groom --}}
                    <h2 class="font-script mb-20" style="font-size: 2.5rem;">Mempelai Pria</h2>
                    <div class="couple-img">
                        <img src="{{ $invitation->groom_photo ? asset('storage/' . $invitation->groom_photo) : 'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?auto=format&fit=crop&w=300&q=80' }}" alt="Groom">
                    </div>
                    <h1 class="font-serif gold-txt" style="font-size: 2rem;">{{ $invitation->groom_name }}</h1>
                    <p style="font-size: 0.9rem; margin-top: 10px;">Putra dari Bpk. {{ $invitation->groom_father }} & Ibu {{ $invitation->groom_mother }}</p>
                    
                    <div style="font-size: 2rem; margin: 20px 0; color: var(--primary);">&</div>

                    {{-- Bride --}}
                    <h2 class="font-script mb-20" style="font-size: 2.5rem;">Mempelai Wanita</h2>
                    <div class="couple-img">
                        <img src="{{ $invitation->bride_photo ? asset('storage/' . $invitation->bride_photo) : 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=300&q=80' }}" alt="Bride">
                    </div>
                    <h1 class="font-serif gold-txt" style="font-size: 2rem;">{{ $invitation->bride_name }}</h1>
                    <p style="font-size: 0.9rem; margin-top: 10px;">Putri dari Bpk. {{ $invitation->bride_father }} & Ibu {{ $invitation->bride_mother }}</p>
                @endif
            </div>

            {{-- Events Card --}}
            <div class="card">
                <h2 class="font-serif mb-20" style="border-bottom: 1px solid var(--primary); display: inline-block; padding-bottom: 10px;">Rangkaian Acara</h2>
                
                <div class="mt-20">
                    <h3 class="gold-txt">Akad Nikah</h3>
                    <p>{{ $invitation->akad_date ? $invitation->akad_date->translatedFormat('l, d F Y') : 'TBA' }}</p>
                    <p>{{ $invitation->akad_date ? $invitation->akad_date->format('H:i') . ' WIB - Selesai' : '' }}</p>
                    <p class="mt-20" style="font-size: 0.9rem; opacity: 0.8;">
                        {{ $invitation->akad_venue }} <br>
                        {{ $invitation->akad_address }}
                    </p>
                    @if($invitation->akad_maps_link)
                        <a href="{{ $invitation->akad_maps_link }}" target="_blank" class="btn-open" style="margin-top: 15px; font-size: 0.8rem; padding: 8px 20px; text-decoration: none;">
                            <i class="fas fa-map-marker-alt"></i> Lihat Lokasi
                        </a>
                    @endif
                </div>
                
                @if($invitation->resepsi_date)
                <div class="mt-20" style="border-top: 1px solid var(--border); padding-top: 20px;">
                    <h3 class="gold-txt">Resepsi</h3>
                    <p>{{ $invitation->resepsi_date->translatedFormat('l, d F Y') }}</p>
                    <p>{{ $invitation->resepsi_date->format('H:i') }} WIB - Selesai</p>
                    <p class="mt-20" style="font-size: 0.9rem; opacity: 0.8;">
                        {{ $invitation->resepsi_venue }} <br>
                        {{ $invitation->resepsi_address }}
                    </p>
                    @if($invitation->resepsi_maps_link)
                        <a href="{{ $invitation->resepsi_maps_link }}" target="_blank" class="btn-open" style="margin-top: 15px; font-size: 0.8rem; padding: 8px 20px; text-decoration: none;">
                            <i class="fas fa-map-marker-alt"></i> Lihat Lokasi
                        </a>
                    @endif
                </div>
                @endif
            </div>

            {{-- Save The Date Card --}}
            @if($invitation->akad_date)
            <div class="card">
                <h2 class="font-script mb-20">Save The Date</h2>
                
                <div class="countdown-box" x-data="countdown('{{ $invitation->akad_date->format('Y-m-d H:i:s') }}')" x-init="init()">
                    <div class="time-unit"><div class="time-val" x-text="days">00</div><div class="time-label">Hari</div></div>
                    <div class="time-unit"><div class="time-val" x-text="hours">00</div><div class="time-label">Jam</div></div>
                    <div class="time-unit"><div class="time-val" x-text="minutes">00</div><div class="time-label">Menit</div></div>
                    <div class="time-unit"><div class="time-val" x-text="seconds">00</div><div class="time-label">Detik</div></div>
                </div>

                <div class="mt-20">
                    <button @click="
                        @php $order = $invitation->custom_styles['name_order'] ?? 'groom_first'; @endphp
                        const title = 'The Wedding of {{ $order === 'bride_first' ? $invitation->bride_nickname . ' & ' . $invitation->groom_nickname : $invitation->groom_nickname . ' & ' . $invitation->bride_nickname }}';
                        const location = '{{ $invitation->akad_venue }}';
                        const details = 'Tanpa Mengurangi Rasa Hormat, Kami Mengundang Bapak/Ibu/Saudara/i untuk Hadir di Acara Kami.';
                        const startDate = '{{ $invitation->akad_date ? $invitation->akad_date->format('Ymd\THis') : '' }}';
                        const endDate = '{{ $invitation->resepsi_date ? $invitation->resepsi_date->format('Ymd\THis') : ($invitation->akad_date ? $invitation->akad_date->addHours(2)->format('Ymd\THis') : '') }}';
                        const url = `https://calendar.google.com/calendar/render?action=TEMPLATE&text=${encodeURIComponent(title)}&details=${encodeURIComponent(details)}&location=${encodeURIComponent(location)}&dates=${startDate}/${endDate}`;
                        window.open(url, '_blank');
                    " class="btn-open" style="font-size: 0.9rem; margin-top: 25px;">
                        <i class="far fa-calendar-alt"></i> Simpan ke Kalender
                    </button>
                </div>
            </div>
            @endif

             {{-- Gift (Amplop Digital) --}}
             @if($invitation->enable_gift)
             <div class="card">
                 <h2 class="font-serif mb-20" style="border-bottom: 1px solid var(--primary); display: inline-block; padding-bottom: 10px;">Wedding Gift</h2>
                 <p style="font-size: 0.9rem; margin-bottom: 20px;">Doa restu Anda merupakan karunia yang sangat berarti bagi kami. Dan jika memberi adalah ungkapan tanda kasih, Anda dapat memberi kado secara cashless.</p>
                 
                 @if($invitation->bank_accounts)
                     @foreach($invitation->bank_accounts as $account)
                     <div style="background: rgba(255,255,255,0.1); padding: 15px; border-radius: 10px; margin-bottom: 10px; border: 1px solid var(--border);">
                         <p class="gold-txt" style="font-weight: bold;">{{ $account['bank'] }}</p>
                         <p style="font-size: 1.1rem; margin: 5px 0;" id="rek-{{ $loop->index }}">{{ $account['account_number'] }}</p>
                         <p>a.n {{ $account['account_name'] }}</p>
                         <button @click="navigator.clipboard.writeText('{{ $account['account_number'] }}'); alert('Berhasil disalin!')" 
                                 style="background: transparent; border: 1px solid var(--primary); color: var(--primary); padding: 5px 15px; border-radius: 20px; margin-top: 10px; cursor: pointer;">
                             <i class="fas fa-copy"></i> Salin
                         </button>
                     </div>
                     @endforeach
                 @elseif($invitation->bank_name)
                     <div style="background: rgba(255,255,255,0.1); padding: 15px; border-radius: 10px; margin-bottom: 10px; border: 1px solid var(--border);">
                         <p class="gold-txt" style="font-weight: bold;">{{ $invitation->bank_name }}</p>
                         <p style="font-size: 1.1rem; margin: 5px 0;">{{ $invitation->bank_account }}</p>
                         <p>a.n {{ $invitation->bank_holder }}</p>
                         <button @click="navigator.clipboard.writeText('{{ $invitation->bank_account }}'); alert('Berhasil disalin!')" 
                                 style="background: transparent; border: 1px solid var(--primary); color: var(--primary); padding: 5px 15px; border-radius: 20px; margin-top: 10px; cursor: pointer;">
                             <i class="fas fa-copy"></i> Salin
                         </button>
                     </div>
                 @endif
             </div>
             @endif

            {{-- GALLERY --}}
            @if($invitation->photos->count() > 0)
            <div class="card" x-data="{ lightboxOpen: false, imgUrl: '' }">
                <h2 class="font-serif mb-20" style="border-bottom: 1px solid var(--primary); display: inline-block; padding-bottom: 10px;">Galeri Foto</h2>
                
                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px;">
                    @foreach($invitation->photos as $photo)
                    <div @click="lightboxOpen = true; imgUrl = '{{ $photo->url }}'" style="cursor: pointer; overflow: hidden; border-radius: 10px; border: 1px solid var(--border); height: 150px;">
                        <img src="{{ $photo->url }}" alt="Gallery" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s;" class="hover:scale-110">
                    </div>
                    @endforeach
                </div>

                {{-- Lightbox --}}
                <div x-show="lightboxOpen" style="position: fixed; inset: 0; z-index: 99999; background: rgba(0,0,0,0.9); display: flex; align-items: center; justify-content: center; padding: 20px;" x-cloak>
                    <button @click="lightboxOpen = false" style="position: absolute; top: 20px; right: 20px; background: none; border: none; color: white; font-size: 2rem; cursor: pointer;">&times;</button>
                    <img :src="imgUrl" style="max-width: 100%; max-height: 90vh; border-radius: 10px; border: 2px solid var(--primary);">
                </div>
            </div>
            @endif

            {{-- RSVP & WISHES --}}
            <div class="card">
                <h2 class="font-serif mb-20" style="border-bottom: 1px solid var(--primary); display: inline-block; padding-bottom: 10px;">RSVP & Ucapan</h2>
                
                <form wire:submit="submitRSVP" style="text-align: left;">
                    <div style="margin-bottom: 15px;">
                        <label style="display: block; margin-bottom: 5px; font-size: 0.9rem;">Nama</label>
                        <input type="text" wire:model="rsvpName" style="width: 100%; padding: 10px; background: rgba(255,255,255,0.1); border: 1px solid var(--border); color: white; border-radius: 8px;" placeholder="Nama Anda" required>
                        @error('rsvpName') <span style="color: red; font-size: 0.8rem;">{{ $message }}</span> @enderror
                    </div>

                    <div style="margin-bottom: 15px;">
                        <label style="display: block; margin-bottom: 5px; font-size: 0.9rem;">Konfirmasi Kehadiran</label>
                        <select wire:model="rsvpStatus" style="width: 100%; padding: 10px; background: rgba(255,255,255,0.1); border: 1px solid var(--border); color: white; border-radius: 8px;">
                            <option value="Hadir" style="color: black;">Hadir</option>
                            <option value="Maaf, Tidak Bisa Hadir" style="color: black;">Maaf, Tidak Bisa Hadir</option>
                            <option value="Masih Ragu" style="color: black;">Masih Ragu</option>
                        </select>
                    </div>

                    <div style="margin-bottom: 15px;">
                        <label style="display: block; margin-bottom: 5px; font-size: 0.9rem;">Jumlah Tamu</label>
                        <select wire:model="rsvpGuests" style="width: 100%; padding: 10px; background: rgba(255,255,255,0.1); border: 1px solid var(--border); color: white; border-radius: 8px;">
                            <option value="1" style="color: black;">1 Orang</option>
                            <option value="2" style="color: black;">2 Orang</option>
                            <option value="3" style="color: black;">3 Orang</option>
                            <option value="4" style="color: black;">4 Orang</option>
                        </select>
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label style="display: block; margin-bottom: 5px; font-size: 0.9rem;">Ucapan & Doa</label>
                        <textarea wire:model="rsvpMessage" rows="3" style="width: 100%; padding: 10px; background: rgba(255,255,255,0.1); border: 1px solid var(--border); color: white; border-radius: 8px;" placeholder="Tulis ucapan selamat..."></textarea>
                    </div>

                    <button type="submit" class="btn-open" style="width: 100%; justify-content: center; margin-top: 0;">
                        <span wire:loading.remove>Kirim Ucapan</span>
                        <span wire:loading>Mengirim...</span>
                    </button>

                    @if (session()->has('message'))
                        <div style="margin-top: 15px; padding: 10px; background: rgba(40, 167, 69, 0.2); border: 1px solid #28a745; border-radius: 8px; text-align: center;">
                            {{ session('message') }}
                        </div>
                    @endif
                </form>

                <div style="margin-top: 30px; text-align: left; max-height: 300px; overflow-y: auto;">
                    @foreach($invitation->wishes()->latest()->get() as $wish)
                    <div style="background: rgba(255,255,255,0.05); padding: 15px; border-radius: 10px; margin-bottom: 10px; border-left: 3px solid var(--primary);">
                        <div style="font-weight: bold; color: var(--primary); margin-bottom: 5px;">{{ $wish->name }} <span style="font-size: 0.7rem; color: #aaa; font-weight: normal; float: right;">{{ $wish->created_at->diffForHumans() }}</span></div>
                        <div style="font-size: 0.8rem; margin-bottom: 5px; opacity: 0.8;">
                             <span style="background: rgba(255,255,255,0.1); padding: 2px 8px; border-radius: 10px; font-size: 0.7rem;">{{ $wish->status }}</span>
                        </div>
                        <p style="font-size: 0.9rem; font-style: italic;">"{{ $wish->message }}"</p>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Closing --}}
            <div class="card">
                <p class="font-serif">Merupakan suatu kehormatan dan kebahagiaan bagi kami apabila Bapak/Ibu/Saudara/i berkenan hadir untuk memberikan doa restu.</p>
                <div class="font-serif mt-20" style="font-size: 1.2rem;">Wassalamu'alaikum Warahmatullahi Wabarakatuh</div>
                @php $order = $invitation->custom_styles['name_order'] ?? 'groom_first'; @endphp
                <h2 class="font-script mt-20">
                    @if($order === 'bride_first')
                        {{ $invitation->bride_nickname }} & {{ $invitation->groom_nickname }}
                    @else
                        {{ $invitation->groom_nickname }} & {{ $invitation->bride_nickname }}
                    @endif
                </h2>
            </div>
            
            <div style="text-align: center; font-size: 0.8rem; opacity: 0.5; margin-top: 30px;">
                Built with ❤️ by ExoInvite
            </div>
        </div>

    </div>

    {{-- SCRIPTS --}}
    <script>
        function islamicGoldTheme() {
            return {
                opened: false,
                isPlaying: false,

                initTheme() {
                    this.initParticles();
                },

                openInvitation() {
                    this.opened = true;
                    this.isPlaying = true;
                    this.$nextTick(() => {
                        this.$refs.bgMusic.play().catch(e => {
                            console.log("Autoplay prevented:", e);
                            this.isPlaying = false;
                        });
                    });
                },

                toggleMusic() {
                    const audio = this.$refs.bgMusic;
                    if (audio.paused) {
                        audio.play();
                        this.isPlaying = true;
                    } else {
                        audio.pause();
                        this.isPlaying = false;
                    }
                },

                initParticles() {
                    const canvas = document.getElementById('canvas-bg');
                    const ctx = canvas.getContext('2d');
                    canvas.width = window.innerWidth;
                    canvas.height = window.innerHeight;

                    const particles = [];
                    class Particle {
                        constructor() {
                            this.x = Math.random() * canvas.width;
                            this.y = Math.random() * canvas.height;
                            this.size = Math.random() * 2;
                            this.speedY = Math.random() * 0.5 + 0.2;
                            this.opacity = Math.random() * 0.5 + 0.1;
                        }
                        update() {
                            this.y -= this.speedY;
                            if (this.y < 0) this.y = canvas.height;
                        }
                        draw() {
                            ctx.fillStyle = `rgba(212, 175, 55, ${this.opacity})`;
                            ctx.beginPath();
                            ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
                            ctx.fill();
                        }
                    }

                    for (let i = 0; i < 50; i++) particles.push(new Particle());

                    function animateParticles() {
                        ctx.clearRect(0, 0, canvas.width, canvas.height);
                        particles.forEach(p => { p.update(); p.draw(); });
                        requestAnimationFrame(animateParticles);
                    }
                    animateParticles();
                    
                    window.addEventListener('resize', () => {
                        canvas.width = window.innerWidth;
                        canvas.height = window.innerHeight;
                    });
                }
            }
        }

        function countdown(targetDateStr) {
            return {
                days: '00', hours: '00', minutes: '00',
                target: new Date(targetDateStr).getTime(),
                init() {
                    setInterval(() => {
                        const now = new Date().getTime();
                        const diff = this.target - now;
                        if(diff > 0) {
                            this.days = Math.floor(diff / (1000 * 60 * 60 * 24));
                            this.hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                            this.minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                            this.seconds = Math.floor((diff % (1000 * 60)) / 1000);
                        }
                    }, 1000);
                }
            }
        }
    </script>
</div>
