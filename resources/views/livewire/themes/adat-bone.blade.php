@section('title', 'Wedding of ' . $invitation->groom_nickname . ' & ' . $invitation->bride_nickname)

@push('fonts')
<link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,100..900;1,9..144,100..900&family=Parisienne&family=Great+Vibes&display=swap" rel="stylesheet">
@endpush

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<style>
:root {
    --inv-bg: #5d071f;
    --inv-base: #ffffff;
    --inv-accent: #d4b051;
    --inv-border: #d4b051;
    --font-base: 'Fraunces', serif;
    --font-accent: 'Great Vibes', cursive;
    --font-latin: 'Parisienne', cursive;
    --menu-bg: #761332;
    --menu-inactive: #ffffff;
    --menu-active: #d4b051;
    --btn-color: #3d0d19;
}

*, *::before, *::after { box-sizing: border-box; }

body {
    background-color: var(--inv-bg);
    color: var(--inv-base);
    font-family: var(--font-base);
    overflow: hidden;
    -webkit-font-smoothing: antialiased;
}

/* Base animations */
.animate__slower { animation-duration: 2s; }
.animate__slow { animation-duration: 1.5s; }

/* Scroll Snap Container */
.satumomen_track {
    height: 100dvh;
    width: 100vw;
    overflow-y: auto;
    overflow-x: hidden;
    scroll-snap-type: y mandatory;
    scroll-behavior: smooth;
    position: relative;
    background-color: var(--inv-bg);
    -webkit-overflow-scrolling: touch;
}

.satumomen_slide {
    height: 100dvh;
    width: 100vw;
    scroll-snap-align: start;
    position: relative;
    display: flex;
    justify-content: center;
    align-items: center;
}

/* Mobile-like container */
.container-mobile {
    width: 100%;
    max-width: 480px;
    height: 100%;
    position: relative;
    margin: 0 auto;
    background-size: cover;
    background-position: center;
    background-color: var(--inv-bg);
    overflow: hidden;
    box-shadow: 0 0 30px rgba(0,0,0,0.6);
}

/* Decorative Frames â€” proportional sizing */
.frame {
    position: absolute;
    inset: 0;
    pointer-events: none;
    z-index: 10;
}
.frame img {
    position: absolute;
    z-index: 10;
}
.frame-tl { top: 0; left: 0; max-width: 40%; max-height: 30%; }
.frame-tr { top: 0; right: 0; max-width: 40%; max-height: 30%; }
.frame-bl { bottom: 0; left: 0; max-width: 40%; max-height: 30%; }
.frame-br { bottom: 0; right: 0; max-width: 40%; max-height: 30%; }
.frame-tc { top: 0; left: 50%; transform: translateX(-50%); width: 100%; max-height: 15%; }
.frame-bc { bottom: 0; left: 50%; transform: translateX(-50%); width: 100%; max-height: 15%; }
.frame-lc { left: 0; top: 0; height: 100%; width: auto; max-width: 12%; }
.frame-rc { right: 0; top: 0; height: 100%; width: auto; max-width: 12%; }

/* Bottom Nav Menu */
.satumomen_nav_wrap {
    position: fixed;
    bottom: 20px;
    left: 0;
    right: 0;
    z-index: 50;
    display: flex;
    justify-content: center;
    pointer-events: none;
}
.satumomen_menu {
    width: 260px;
    background: rgba(118, 19, 50, 0.95);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    padding: 8px 0;
    overflow: hidden;
    box-shadow: 0 4px 30px rgba(0,0,0,0.45), inset 0 0 0 1px rgba(212,176,81,0.2);
    border-radius: 50px;
    pointer-events: auto;
}
.satumomen_menu_inner {
    display: flex;
    flex-direction: row;
    transition: transform 0.35s cubic-bezier(0.4, 0, 0.2, 1);
}
.satumomen_menu_item {
    color: var(--menu-inactive);
    text-align: center;
    font-size: 9px;
    cursor: pointer;
    transition: color 0.3s, opacity 0.3s;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 2px;
    opacity: 0.45;
    flex: 0 0 65px;
    width: 65px;
    height: 42px;
    white-space: nowrap;
    position: relative;
}
.satumomen_menu_item.active {
    color: var(--menu-active);
    opacity: 1;
}
.satumomen_menu_item.active::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 5px;
    height: 5px;
    background: var(--inv-accent);
    border-radius: 50%;
}
.satumomen_menu_item svg { width: 16px; height: 16px; }

/* Typography */
.font-accent { font-family: var(--font-accent); }
.font-latin { font-family: var(--font-latin); }
.color-accent { color: var(--inv-accent); }

/* Form Elements */
.form-input {
    width: 100%;
    padding: 12px 15px;
    border-radius: 8px;
    background: rgba(255,255,255,0.08);
    border: 1px solid rgba(212,176,81,0.4);
    color: white;
    margin-bottom: 12px;
    font-family: var(--font-base);
    font-size: 14px;
    transition: border-color 0.3s ease;
    outline: none;
}
.form-input:focus {
    border-color: var(--inv-accent);
    background: rgba(255,255,255,0.12);
}
.form-input::placeholder { color: rgba(255,255,255,0.5); }

/* Custom Buttons */
.btn-primary {
    background-color: var(--btn-color);
    color: var(--inv-accent);
    border: 1px solid var(--inv-accent);
    padding: 12px 28px;
    border-radius: 50px;
    font-family: sans-serif;
    font-weight: 600;
    font-size: 13px;
    letter-spacing: 0.5px;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-block;
    width: 100%;
    text-align: center;
}
.btn-primary:hover {
    background-color: var(--inv-accent);
    color: var(--btn-color);
    box-shadow: 0 4px 15px rgba(212,176,81,0.3);
}

.slide-content {
    position: relative;
    z-index: 20;
    width: 100%;
    height: 100%;
    display: flex;
    flex-direction: column;
    padding: 60px 28px;
    overflow-y: auto;
}
.slide-center {
    justify-content: center;
    align-items: center;
}

#cover-overlay {
    position: fixed; inset: 0; z-index: 100;
    background-color: var(--inv-bg);
}

/* Photo frames â€” larger & crisper */
.photo-frame {
    width: 120px; height: 120px;
    margin: 0 auto;
    border: 2px solid var(--inv-accent);
    border-radius: 50%;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(212,176,81,0.2);
}
.photo-frame img { width: 100%; height: 100%; object-fit: cover; }
a { color: inherit; text-decoration: none; }

/* Scrollbar hide for cleaner look */
.satumomen_track::-webkit-scrollbar { display: none; }
.satumomen_track { -ms-overflow-style: none; scrollbar-width: none; }
.slide-content::-webkit-scrollbar { display: none; }
.slide-content { -ms-overflow-style: none; scrollbar-width: none; }
</style>
@endpush

<div style="background-color: var(--inv-bg); height: 100dvh;" x-data="{
    opened: false,
    activeSlide: 0,
    playing: false,
    audioEl: null,
    slides: ['cover', 'opening', 'couple', 'quote', 'lovestory', 'events', 'maps', 'gallery', 'gift', 'rsvp'],
    init() {
        this.setupIntersectionObserver();
    },
    open() {
        this.opened = true;
        this.$nextTick(() => {
            this.audioEl = document.getElementById('bgMusic');
            if(this.audioEl) {
                this.audioEl.play().then(() => this.playing = true).catch(() => {});
            }
            this.scrollToSlide(1);
        });
    },
    toggleAudio() {
        if(!this.audioEl) this.audioEl = document.getElementById('bgMusic');
        if(!this.audioEl) return;
        
        if(this.playing) { this.audioEl.pause(); this.playing = false; }
        else { this.audioEl.play().then(()=>this.playing=true).catch(()=>{}); }
    },
    scrollToSlide(index) {
        if(index < 0 || index >= this.slides.length) return;
        const target = document.getElementById('slide-' + index);
        if(target) {
            target.scrollIntoView({ behavior: 'smooth' });
            this.activeSlide = index;
        }
    },
    setupIntersectionObserver() {
        let observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if(entry.isIntersecting) {
                    this.activeSlide = parseInt(entry.target.dataset.index);
                    const animatedEls = entry.target.querySelectorAll('.animate__animated');
                    animatedEls.forEach(el => {
                        el.style.animation = 'none';
                        el.offsetHeight; 
                        el.style.animation = null; 
                    });
                }
            });
        }, { threshold: 0.5 });
        
        document.querySelectorAll('.satumomen_slide').forEach((slide) => {
            observer.observe(slide);
        });
    }
}">

    @if($invitation->music_url)
    <audio id="bgMusic" loop>
        <source src="{{ asset('storage/' . $invitation->music_url) }}" type="audio/mpeg">
    </audio>
    @endif

    {{-- COVER OVERLAY --}}
    <div x-show="!opened" x-transition.opacity.duration.1000ms id="cover-overlay" class="container-mobile" style="position: absolute; left: 50%; transform: translateX(-50%); text-shadow: 0 2px 8px rgba(0,0,0,0.9), 0 0 20px rgba(93,7,31,0.8), 0 0 4px rgba(0,0,0,1); background-image: url('{{ $invitation->cover_image ? asset('storage/' . $invitation->cover_image) : asset('assets/themes/adat-bone/bg_bone.webp') }}');">
        <div class="frame">
            <img class="frame-lc animate__animated animate__fadeInLeft animate__slower" src="{{ asset('assets/themes/adat-bone/left.webp') }}" alt="frame" style="width: auto;">
            <img class="frame-rc animate__animated animate__fadeInRight animate__slower" src="{{ asset('assets/themes/adat-bone/right.webp') }}" alt="frame" style="width: auto;">
            <img class="frame-tl animate__animated animate__fadeInTopLeft animate__slow" src="{{ asset('assets/themes/adat-bone/tl.webp') }}" alt="frame">
            <img class="frame-tr animate__animated animate__fadeInTopRight animate__slow" src="{{ asset('assets/themes/adat-bone/tr.webp') }}" alt="frame">
            <img class="frame-bl animate__animated animate__fadeInBottomLeft animate__slow" src="{{ asset('assets/themes/adat-bone/bl.webp') }}" alt="frame">
            <img class="frame-br animate__animated animate__fadeInBottomRight animate__slow" src="{{ asset('assets/themes/adat-bone/br.webp') }}" alt="frame">
        </div>
        
        {{-- Dark overlay for text readability --}}
        <div style="position: absolute; inset:0; background: linear-gradient(to bottom, rgba(93,7,31,0.75) 0%, rgba(93,7,31,0.45) 35%, rgba(93,7,31,0.35) 55%, rgba(93,7,31,0.92) 85%, rgba(93,7,31,0.98) 100%); z-index:1;"></div>

        <div class="slide-content" style="justify-content: space-between;">
            <div class="text-center w-full mt-10">
                <div class="mb-2 text-center animate__animated animate__fadeInDown animate__slower" style="letter-spacing:3px; text-transform: uppercase; font-size: 13px;">The Wedding Of</div>
                
                <div class="mb-2 color-accent text-center animate__animated animate__fadeInDown animate__slower font-latin" style="font-size:36px;line-height:1.2;">
                    {{ $invitation->groom_nickname }} & {{ $invitation->bride_nickname }}
                </div>
                
                @if($invitation->akad_date)
                <div class="text-center animate__animated animate__fadeInUp animate__slower" style="font-size:15px;line-height:1.4; letter-spacing: 2px;">
                    {{ $invitation->akad_date->format('d . m . y') }}
                </div>
                @endif
            </div>
            
            <div class="text-center w-full mb-6">
                <div class="mb-2 flex flex-col items-center animate__animated animate__fadeInUp animate__slower">
                    <span style="font-size:13px; opacity: 0.9;">Kepada Yth</span>
                    <span style="font-size:13px; opacity: 0.9;">Bapak/Ibu/Saudara/i</span>
                </div>
                <div class="mb-6 font-bold animate__animated animate__fadeInUp animate__slower" style="font-size:20px; letter-spacing: 0.5px;">
                    {{ request('kpd', 'Tamu Undangan') }}
                </div>
                <button type="button" @click="open()" class="btn-primary animate__animated animate__fadeInUp animate__slow shadow-xl" style="max-width: 220px;">Buka Undangan</button>
            </div>
        </div>
    </div>

    {{-- MAIN SLIDER --}}
    <div class="satumomen_track">
        
        {{-- Slide 1: OPENING --}}
        <div id="slide-1" data-index="1" class="satumomen_slide">
            <div class="container-mobile" style="background-image: url('{{ $invitation->cover_image ? asset('storage/' . $invitation->cover_image) : 'https://images.unsplash.com/photo-1519741497674-611481863552?w=600' }}');">
                <div class="frame">
                    <img class="frame-lc animate__animated animate__fadeInLeft animate__slower" src="{{ asset('assets/themes/adat-bone/left.webp') }}" alt="frame">
                    <img class="frame-rc animate__animated animate__fadeInRight animate__slower" src="{{ asset('assets/themes/adat-bone/right.webp') }}" alt="frame">
                    <img class="frame-tl animate__animated animate__fadeInTopLeft animate__slow" src="{{ asset('assets/themes/adat-bone/tl.webp') }}" alt="frame">
                    <img class="frame-tr animate__animated animate__fadeInTopRight animate__slow" src="{{ asset('assets/themes/adat-bone/tr.webp') }}" alt="frame">
                    <img class="frame-bl animate__animated animate__fadeInBottomLeft animate__slow" src="{{ asset('assets/themes/adat-bone/bl.webp') }}" alt="frame">
                    <img class="frame-br animate__animated animate__fadeInBottomRight animate__slow" src="{{ asset('assets/themes/adat-bone/br.webp') }}" alt="frame">
                </div>
                {{-- Dark overlay for readability --}}
                <div style="position: absolute; inset:0; background: linear-gradient(to bottom, rgba(93,7,31,0.6), rgba(93,7,31,0.9)); z-index:1;"></div>
                
                <div class="slide-content" style="justify-content: space-between; align-items: center; padding-top: 100px; padding-bottom: 80px;">
                    <div class="mb-auto text-center animate__animated animate__fadeInDown animate__slower font-semibold tracking-widest text-sm">
                        @if($invitation->akad_date)
                            {{ $invitation->akad_date->format('d . m . Y') }}
                        @endif
                    </div>
                    <div class="text-center animate__animated animate__fadeInUp animate__slower">
                        <div class="text-sm tracking-widest uppercase mb-2">The Wedding of</div>
                        <div class="color-accent font-latin text-4xl mb-5">
                            {{ $invitation->groom_nickname }} & {{ $invitation->bride_nickname }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Slide 2: MEMPELAI --}}
        <div id="slide-2" data-index="2" class="satumomen_slide">
            <div class="container-mobile">
                <div class="frame">
                    <img class="frame-lc animate__animated animate__fadeInLeft animate__slower" src="{{ asset('assets/themes/adat-bone/left.webp') }}" alt="frame">
                    <img class="frame-rc animate__animated animate__fadeInRight animate__slower" src="{{ asset('assets/themes/adat-bone/right.webp') }}" alt="frame">
                    <img class="frame-tl animate__animated animate__fadeInTopLeft animate__slow" src="{{ asset('assets/themes/adat-bone/tl.webp') }}" alt="frame">
                    <img class="frame-tr animate__animated animate__fadeInTopRight animate__slow" src="{{ asset('assets/themes/adat-bone/tr.webp') }}" alt="frame">
                    <img class="frame-bl animate__animated animate__fadeInBottomLeft animate__slow" src="{{ asset('assets/themes/adat-bone/bl.webp') }}" alt="frame">
                    <img class="frame-br animate__animated animate__fadeInBottomRight animate__slow" src="{{ asset('assets/themes/adat-bone/br.webp') }}" alt="frame">
                </div>
                
                <div class="slide-content slide-center text-center">
                    @php $order = $invitation->custom_styles['name_order'] ?? 'groom_first'; @endphp
                    
                    {{-- Person 1 --}}
                    @php 
                        $p1_role = $order === 'groom_first' ? 'Putra' : 'Putri';
                        $p1_name = $order === 'groom_first' ? $invitation->groom_name : $invitation->bride_name;
                        $p1_father = $order === 'groom_first' ? $invitation->groom_father : $invitation->bride_father;
                        $p1_mother = $order === 'groom_first' ? $invitation->groom_mother : $invitation->bride_mother;
                        $p1_photo = $order === 'groom_first' ? $invitation->groom_photo : $invitation->bride_photo;
                        $p1_fallback = 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=200';
                    @endphp
                    <div class="mb-4">
                        <div class="photo-frame mb-3 animate__animated animate__fadeInDown animate__slower">
                            <img src="{{ $p1_photo ? asset('storage/' . $p1_photo) : $p1_fallback }}" alt="Photo 1">
                        </div>
                        <div class="animate__animated animate__fadeInLeft animate__slower">
                            <div class="color-accent font-accent text-2xl mb-1">{{ $p1_name }}</div>
                            <div class="text-sm opacity-80 leading-relaxed">{{ $p1_role }} dari<br>{{ $p1_father }} & {{ $p1_mother }}</div>
                        </div>
                    </div>

                    {{-- AMPERSAND DECOR --}}
                    <div class="color-accent  animate__animated animate__fadeIn animate__slower my-2 flex justify-center">
                         <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 50 59.8" xml:space="preserve"><path d="M10.4 54.1c2.6 2.5 5.9 3.8 9.8 3.8 2.7 0 5.1-.6 7.4-1.8 2.3-1.2 3.8-2.6 4.6-4.3.8-1.7 1.2-3.6 1.2-5.7 0-4.2-1.3-7.8-4-10.8-.6-.8-2.1-1.5-4.4-2-1-.3-2-.5-3.2-.6 4-.4 6.5-.2 7.7.5 1.9 1.2 3.4 3 4.6 5.4s2.1 3.6 2.6 3.7l5.2.4c2.6.1 4.5.9 5.7 2.3 1.7 2.1 2.4 5.6 2.2 10.6-1-5.5-2.4-8.5-3.9-9.1-1.6-.6-3-.8-4.2-.8-3 0-5.1 1.7-6.3 5.2-3.3 5.9-8.1 8.8-14.4 8.8-5.3 0-10.2-2-14.5-5.9C2.2 49.9 0 45.3 0 40.2c0-3.2 1.6-6.7 4.9-10.5 3.6-4.2 7.8-6.6 12.6-7.2-3.8-.8-6.8-2.4-9.1-4.9C6.1 15.1 5 12.3 5 9.1c0-2.3 1.1-4.4 3.4-6.3C10.4.9 12.5 0 14.7 0c1 0 2.3.3 4.1 1 2.3.9 3.4 1.9 3.4 3 0 .9-.6 1.4-1.7 1.4-.1 0-.5-.4-1.4-1.3-.8-.8-1.8-1.3-2.9-1.4-1.2-.1-2.4.5-3.6 1.8-1.2 1.4-1.8 2.7-1.8 4.1 0 7.2 5.3 12.1 15.8 14.7 1.4.3 2.1.7 2.1 1.3-1.2-.2-2.5-.3-3.9-.3-5.1 0-9.3 1.1-12.6 3.4-4.2 2.9-6.3 7.2-6.3 13 0 5.9 1.5 10.4 4.5 13.4z" fill="currentColor"></path></svg>
                    </div>

                    {{-- Person 2 --}}
                    @php 
                        $p2_role = $order === 'groom_first' ? 'Putri' : 'Putra';
                        $p2_name = $order === 'groom_first' ? $invitation->bride_name : $invitation->groom_name;
                        $p2_father = $order === 'groom_first' ? $invitation->bride_father : $invitation->groom_father;
                        $p2_mother = $order === 'groom_first' ? $invitation->bride_mother : $invitation->groom_mother;
                        $p2_photo = $order === 'groom_first' ? $invitation->bride_photo : $invitation->groom_photo;
                        $p2_fallback = 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=200';
                    @endphp
                    <div class="mt-4">
                        <div class="animate__animated animate__fadeInRight animate__slower mb-3">
                            <div class="color-accent font-accent text-2xl mb-1">{{ $p2_name }}</div>
                            <div class="text-sm opacity-80 leading-relaxed">{{ $p2_role }} dari<br>{{ $p2_father }} & {{ $p2_mother }}</div>
                        </div>
                        <div class="photo-frame animate__animated animate__fadeInUp animate__slower">
                            <img src="{{ $p2_photo ? asset('storage/' . $p2_photo) : $p2_fallback }}" alt="Photo 2">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Slide 3: QUOTES --}}
        <div id="slide-3" data-index="3" class="satumomen_slide">
            <div class="container-mobile">
                <div class="frame">
                    <img class="frame-lc animate__animated animate__fadeInLeft animate__slower" src="{{ asset('assets/themes/adat-bone/left.webp') }}" alt="frame">
                    <img class="frame-rc animate__animated animate__fadeInRight animate__slower" src="{{ asset('assets/themes/adat-bone/right.webp') }}" alt="frame">
                    <img class="frame-tl animate__animated animate__fadeInTopLeft animate__slow" src="{{ asset('assets/themes/adat-bone/tl.webp') }}" alt="frame">
                    <img class="frame-tr animate__animated animate__fadeInTopRight animate__slow" src="{{ asset('assets/themes/adat-bone/tr.webp') }}" alt="frame">
                    <img class="frame-bl animate__animated animate__fadeInBottomLeft animate__slow" src="{{ asset('assets/themes/adat-bone/bl.webp') }}" alt="frame">
                    <img class="frame-br animate__animated animate__fadeInBottomRight animate__slow" src="{{ asset('assets/themes/adat-bone/br.webp') }}" alt="frame">
                </div>
                
                <div class="slide-content slide-center">
                    <div class="color-accent text-center animate__animated animate__fadeInDown animate__slower font-accent mb-4" style="font-size:36px;">QS. Ar-Rum 21</div>
                    <div class="text-center animate__animated animate__fadeInUp animate__slower text-sm italic opacity-90 leading-loose" style="padding: 0 50px 0 16px;">
                        "Dan di antara tanda-tanda (kebesaran)-Nya ialah Dia menciptakan pasangan-pasangan untukmu dari jenismu sendiri, agar kamu cenderung dan merasa tenteram kepadanya, dan Dia menjadikan di antaramu rasa kasih dan sayang. Sungguh, pada yang demikian itu benar-benar terdapat tanda-tanda (kebesaran Allah) bagi kaum yang berpikir."
                    </div>
                </div>
            </div>
        </div>

        {{-- Slide 4: LOVE STORY --}}
        @if($invitation->love_story && count($invitation->love_story) > 0)
        <div id="slide-4" data-index="4" class="satumomen_slide" style="height: auto; min-height: 100dvh;">
            <div class="container-mobile" style="height: auto; min-height: 100%;">
                <div class="frame">
                    <img class="frame-tl animate__animated animate__fadeInTopLeft animate__slow" src="{{ asset('assets/themes/adat-bone/tl.webp') }}" alt="frame">
                    <img class="frame-tr animate__animated animate__fadeInTopRight animate__slow" src="{{ asset('assets/themes/adat-bone/tr.webp') }}" alt="frame">
                    <img class="frame-bl animate__animated animate__fadeInBottomLeft animate__slow" src="{{ asset('assets/themes/adat-bone/bl.webp') }}" alt="frame">
                    <img class="frame-br animate__animated animate__fadeInBottomRight animate__slow" src="{{ asset('assets/themes/adat-bone/br.webp') }}" alt="frame">
                </div>
                
                <div class="slide-content" style="padding-top: 80px; padding-bottom: 80px;">
                    <div class="text-center mb-8 animate__animated animate__fadeInDown animate__slower">
                        <div class="font-accent color-accent" style="font-size: 36px;">Love Story</div>
                        <div class="mx-auto mt-2" style="width: 60px; height: 1px; background: var(--inv-accent); opacity: 0.5;"></div>
                    </div>
                    
                    <div style="position: relative; padding-left: 24px; border-left: 1px solid rgba(212,176,81,0.3); margin-left: 16px;">
                        @foreach($invitation->love_story as $index => $story)
                        <div class="animate__animated animate__fadeInUp animate__slower mb-8" style="animation-delay: {{ $index * 0.15 }}s; position: relative;">
                            {{-- Timeline dot --}}
                            <div style="position: absolute; left: -30px; top: 6px; width: 12px; height: 12px; border-radius: 50%; background: var(--inv-accent); border: 3px solid var(--inv-bg);"></div>
                            
                            <div style="background: rgba(255,255,255,0.06); border: 1px solid rgba(212,176,81,0.2); border-radius: 12px; padding: 20px;">
                                @if(!empty($story['date']))
                                <div class="color-accent font-accent" style="font-size: 14px; margin-bottom: 4px;">{{ $story['date'] ?? '' }}</div>
                                @endif
                                <div class="color-accent" style="font-size: 18px; font-weight: 600; margin-bottom: 8px; font-family: var(--font-base);">{{ $story['title'] ?? '' }}</div>
                                <div style="font-size: 13px; opacity: 0.8; line-height: 1.7;">{{ $story['description'] ?? '' }}</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- Slide 5: ACARA --}}
        <div id="slide-5" data-index="5" class="satumomen_slide">
            <div class="container-mobile">
                <div class="frame">
                    <img class="frame-lc animate__animated animate__fadeInLeft animate__slower" src="{{ asset('assets/themes/adat-bone/left.webp') }}" alt="frame">
                    <img class="frame-rc animate__animated animate__fadeInRight animate__slower" src="{{ asset('assets/themes/adat-bone/right.webp') }}" alt="frame">
                    <img class="frame-tl animate__animated animate__fadeInTopLeft animate__slow" src="{{ asset('assets/themes/adat-bone/tl.webp') }}" alt="frame">
                    <img class="frame-tr animate__animated animate__fadeInTopRight animate__slow" src="{{ asset('assets/themes/adat-bone/tr.webp') }}" alt="frame">
                    <img class="frame-bl animate__animated animate__fadeInBottomLeft animate__slow" src="{{ asset('assets/themes/adat-bone/bl.webp') }}" alt="frame">
                    <img class="frame-br animate__animated animate__fadeInBottomRight animate__slow" src="{{ asset('assets/themes/adat-bone/br.webp') }}" alt="frame">
                </div>
                
                <div class="slide-content slide-center">
                    {{-- Akad --}}
                    <div class="text-center animate__animated animate__fadeInDown animate__slower w-full">
                        <div class="color-accent font-latin text-3xl mb-2">Akad Nikah</div>
                        <div class="text-sm mb-1">{{ $invitation->akad_date?->translatedFormat('l, d F Y') }}</div>
                        <div class="text-sm mb-2">Pukul {{ $invitation->akad_date?->format('H:i') }} WITA - Selesai</div>
                        <div class="text-xs opacity-80 mt-2">
                            <strong>{{ $invitation->akad_venue }}</strong><br>
                            {{ $invitation->akad_address }}
                        </div>
                    </div>
                    
                    {{-- Ornament Separator --}}
                    <div class="color-accent text-center animate__animated animate__fadeIn animate__slower my-4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="100" height="auto" viewBox="0 0 308.5 43.6" style="opacity:0.5; margin: 0 auto;"><path d="M308.5 21.7c0-.9-.4-1.7-1.1-2.2-.7-.5-1.5-.8-2.3-.9-1.6-.2-3.2-.1-4.8.2-.8.1-1.6.3-2.4.5-.8.2-1.6.4-2.3.6-1.1.3-2.2.7-3.3 1.1 1.5-1 2.9-2.1 4-3.5.7-.9 1.2-1.9 1.4-3.1.2-1.1 0-2.4-.9-3.3-.9-.8-2-1.2-3.2-1.2-.6 0-1.1.1-1.7.3-.5.2-1.1.5-1.4 1-.3.5-.5 1.1-.6 1.6-.1.6-.1 1.2.1 1.7.3 1.1 1.3 1.9 2.2 2.4-.1.7-.5 1.2-1 1.7s-1.2.8-1.9 1.1c-1.3.5-2.7.9-4.1 1h-36.5c-1 0-2 .5-2.5 1.3s-.6 1.9-.3 2.9c.4 1 1.6 1.4 2.6 1.3h-250c-1.3.5-2.7.9-4.1 1h35z" fill="currentColor"/></svg>
                    </div>

                    {{-- Resepsi --}}
                    @if($invitation->resepsi_date)
                    <div class="text-center animate__animated animate__fadeInUp animate__slower w-full">
                        <div class="color-accent font-latin text-3xl mb-2">Resepsi</div>
                        <div class="text-sm mb-1">{{ $invitation->resepsi_date?->translatedFormat('l, d F Y') }}</div>
                        <div class="text-sm mb-2">Pukul {{ $invitation->resepsi_date?->format('H:i') }} WITA - Selesai</div>
                        <div class="text-xs opacity-80 mt-2">
                            <strong>{{ $invitation->resepsi_venue }}</strong><br>
                            {{ $invitation->resepsi_address }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Slide 6: MAPS --}}
        <div id="slide-6" data-index="6" class="satumomen_slide">
            <div class="container-mobile">
                <div class="frame">
                    <img class="frame-lc animate__animated animate__fadeInLeft animate__slower" src="{{ asset('assets/themes/adat-bone/left.webp') }}" alt="frame">
                    <img class="frame-rc animate__animated animate__fadeInRight animate__slower" src="{{ asset('assets/themes/adat-bone/right.webp') }}" alt="frame">
                </div>
                
                <div class="slide-content slide-center">
                    @if($invitation->akad_maps_link)
                    @php
                        $mapsQuery = $invitation->akad_address ?? $invitation->akad_venue ?? '';
                        $mapsEmbed = 'https://maps.google.com/maps?q=' . urlencode($mapsQuery) . '&z=15&output=embed';
                    @endphp
                    <div style="width:85%;margin:auto;border-radius:12px;overflow:hidden;margin-bottom:20px;" class="animate__animated animate__fadeInDown animate__slow">
                        <iframe src="{{ $mapsEmbed }}" width="100%" height="280" style="border:0; border-radius:12px;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                    @endif
                    
                    <div class="text-center animate__animated animate__fadeInUp animate__slow">
                        <div class="mb-4 text-sm opacity-90 mx-auto" style="max-width: 250px;">
                            {{ $invitation->akad_venue }}<br>
                            {{ $invitation->akad_address }}
                        </div>
                        <a href="{{ $invitation->akad_maps_link }}" class="btn-primary" target="_blank" rel="noreferrer noopener">Petunjuk Ke Lokasi</a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Slide 7: GALLERY --}}
        @if($invitation->enable_gallery)
        <div id="slide-7" data-index="7" class="satumomen_slide">
            <div class="container-mobile">
                <div class="frame">
                    <img class="frame-tl animate__animated animate__fadeInTopLeft animate__slow" src="{{ asset('assets/themes/adat-bone/tl.webp') }}" alt="frame">
                    <img class="frame-tr animate__animated animate__fadeInTopRight animate__slow" src="{{ asset('assets/themes/adat-bone/tr.webp') }}" alt="frame">
                    <img class="frame-bl animate__animated animate__fadeInBottomLeft animate__slow" src="{{ asset('assets/themes/adat-bone/bl.webp') }}" alt="frame">
                    <img class="frame-br animate__animated animate__fadeInBottomRight animate__slow" src="{{ asset('assets/themes/adat-bone/br.webp') }}" alt="frame">
                </div>
                
                <div class="slide-content pt-16">
                    <div class="text-center mb-6 animate__animated animate__fadeInDown animate__slower">
                        <div class="font-accent color-accent text-3xl">Galeri</div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-3 pb-20 justify-center">
                        @foreach($invitation->photos as $index => $photo)
                        <div class="animate__animated animate__zoomIn animate__slower mb-2" style="animation-delay: {{ $index * 0.1 }}s; border-radius: 12px; overflow: hidden; height: {{ $index % 2 == 0 ? '160px' : '120px' }};">
                            <img src="{{ $photo->url }}" alt="Gallery image" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- Slide 8: GIFT --}}
        @if($invitation->enable_gift)
        <div id="slide-8" data-index="8" class="satumomen_slide">
            <div class="container-mobile">
                <div class="frame">
                    <img class="frame-lc animate__animated animate__fadeInLeft animate__slower" src="{{ asset('assets/themes/adat-bone/left.webp') }}" alt="frame">
                    <img class="frame-rc animate__animated animate__fadeInRight animate__slower" src="{{ asset('assets/themes/adat-bone/right.webp') }}" alt="frame">
                </div>
                
                <div class="slide-content slide-center text-center">
                    <div class="color-accent text-3xl mb-2 animate__animated animate__fadeInDown animate__slower font-latin">Wedding Gift</div>
                    <div class="text-sm opacity-80 mb-6 animate__animated animate__fadeInDown animate__slower max-w-xs mx-auto">
                        Terima kasih telah menambah semangat kegembiraan pernikahan kami dengan kehadiran dan hadiah indah Anda.
                    </div>
                    
                    <div class="flex flex-col gap-4 items-center w-full max-w-sm">
                        @foreach($invitation->bank_accounts ?? [] as $acc)
                        <div class="animate__animated animate__zoomIn animate__slower p-4" style="background:linear-gradient(113deg, #d9d9d9 0%, #ffffff 23%, #e5e5e5 31%, #fdfdfd 61%, #bababa 100%);border-radius:1rem;color:#333;width:100%;text-align:left;">
                            <div class="font-bold mb-2">{{ $acc['bank'] ?? 'Bank' }}</div>
                            <div class="text-xl font-bold font-mono tracking-widest mb-1">{{ $acc['account_number'] }}</div>
                            <div class="text-sm opacity-80 mb-3">a.n {{ $acc['account_name'] }}</div>
                            
                            <button x-data="{copied: false}" @click="navigator.clipboard.writeText('{{ $acc['account_number'] }}'); copied=true; setTimeout(()=>copied=false, 2000)" class="btn-primary" style="padding: 6px 12px; font-size: 12px;">
                                <span x-text="copied ? 'Tersalin' : 'Salin Rekening'"></span>
                            </button>
                        </div>
                        @endforeach
                        
                        @if($invitation->bank_name && empty($invitation->bank_accounts))
                        <div class="animate__animated animate__zoomIn animate__slower p-4" style="background:linear-gradient(113deg, #d9d9d9 0%, #ffffff 23%, #e5e5e5 31%, #fdfdfd 61%, #bababa 100%);border-radius:1rem;color:#333;width:100%;text-align:left;">
                            <div class="font-bold mb-2">{{ $invitation->bank_name }}</div>
                            <div class="text-xl font-bold font-mono tracking-widest mb-1">{{ $invitation->bank_account }}</div>
                            <div class="text-sm opacity-80 mb-3">a.n {{ $invitation->bank_holder }}</div>
                            
                            <button x-data="{copied: false}" @click="navigator.clipboard.writeText('{{ $invitation->bank_account }}'); copied=true; setTimeout(()=>copied=false, 2000)" class="btn-primary" style="padding: 6px 12px; font-size: 12px;">
                                <span x-text="copied ? 'Tersalin' : 'Salin Rekening'"></span>
                            </button>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- Slide 9: RSVP & WISHES --}}
        @if($invitation->enable_rsvp || $invitation->enable_wishes)
        <div id="slide-9" data-index="9" class="satumomen_slide">
            <div class="container-mobile">
                <div class="frame">
                    <img class="frame-tl animate__animated animate__fadeInTopLeft animate__slow" src="{{ asset('assets/themes/adat-bone/tl.webp') }}" alt="frame">
                    <img class="frame-tr animate__animated animate__fadeInTopRight animate__slow" src="{{ asset('assets/themes/adat-bone/tr.webp') }}" alt="frame">
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

                    {{-- THANK YOU (after submit) --}}
                    <div x-show="submitted" x-transition class="mx-4 mb-4 p-5 rounded-xl text-center animate__animated animate__fadeIn" style="background: rgba(212,176,81,0.1); border: 1px solid rgba(212,176,81,0.25);">
                        <div class="color-accent text-3xl mb-2">&#10003;</div>
                        <div class="color-accent font-medium text-sm mb-1">Terima kasih!</div>
                        <div class="text-xs text-white/70">Ucapan dan doa Anda telah tersimpan.</div>
                    </div>

                    {{-- RSVP FORM --}}
                    <div x-show="!submitted" x-transition class="bg-[#3d0d19] p-5 rounded-xl border border-[var(--inv-accent)] mx-4 text-left animate__animated animate__fadeInUp animate__slower">
                        {{-- Error --}}
                        <div x-show="error" x-transition class="mb-4 p-3 rounded-lg text-center" style="background: rgba(220,38,38,0.15); border: 1px solid rgba(220,38,38,0.3);">
                            <span class="text-red-300 text-sm" x-text="error"></span>
                        </div>

                        <form @submit.prevent="submitForm">
                            {{-- Name --}}
                            <div class="mb-3">
                                <label class="block text-xs color-accent mb-1 font-medium">Nama Lengkap</label>
                                <input type="text" x-model="name" class="form-input" placeholder="Masukkan nama Anda">
                            </div>

                            {{-- Attendance Toggle --}}
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

                            {{-- Guests (show only if confirmed) --}}
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

                            {{-- Message --}}
                            <div class="mb-4">
                                <label class="block text-xs color-accent mb-1 font-medium">Ucapan & Doa</label>
                                <textarea x-model="message" class="form-input" rows="3" placeholder="Tulis ucapan dan doa terbaik Anda..."></textarea>
                            </div>

                            {{-- Submit --}}
                            <button type="submit" :disabled="loading" class="btn-primary w-full flex items-center justify-center gap-2">
                                <template x-if="!loading">
                                    <span class="flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                                        Kirim Ucapan
                                    </span>
                                </template>
                                <template x-if="loading">
                                    <span>Mengirim...</span>
                                </template>
                            </button>
                        </form>
                    </div>

                    {{-- Wishes List (always visible) --}}
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
                                        <template x-if="wish.attendance_status === 'confirmed'">
                                            <span>&#10003; Akan Hadir</span>
                                        </template>
                                        <template x-if="wish.attendance_status === 'declined'">
                                            <span>&#10007; Tidak Hadir</span>
                                        </template>
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

        {{-- Slide 10: CLOSING WATERMARK --}}
        <div id="slide-10" data-index="10" class="satumomen_slide">
            <div class="container-mobile">
                <div class="frame">
                    <img class="frame-tl animate__animated animate__fadeInTopLeft animate__slow" src="{{ asset('assets/themes/adat-bone/tl.webp') }}" alt="frame">
                    <img class="frame-tr animate__animated animate__fadeInTopRight animate__slow" src="{{ asset('assets/themes/adat-bone/tr.webp') }}" alt="frame">
                    <img class="frame-bl animate__animated animate__fadeInBottomLeft animate__slow" src="{{ asset('assets/themes/adat-bone/bl.webp') }}" alt="frame">
                    <img class="frame-br animate__animated animate__fadeInBottomRight animate__slow" src="{{ asset('assets/themes/adat-bone/br.webp') }}" alt="frame">
                </div>
                
                <div class="slide-content slide-center">
                    <div class="text-center px-4">
                        <div class="text-sm opacity-80 leading-relaxed italic mb-8 animate__animated animate__fadeInDown animate__slower">
                            Merupakan suatu kebahagiaan dan kehormatan bagi kami, apabila Bapak/Ibu/Saudara/i, berkenan hadir dan memberikan do'a restu kepada kedua mempelai.
                        </div>
                        
                        <div class="text-sm italic opacity-90 mb-2 animate__animated animate__fadeInDown animate__slow">Hormat Kami Yang Mengundang</div>
                        
                        <div class="color-accent font-accent text-4xl mb-12 animate__animated animate__fadeInDown animate__slow">
                            {{ $invitation->groom_nickname }} & {{ $invitation->bride_nickname }}
                        </div>

                        <div class="mt-10 opacity-50 text-[10px] uppercase font-sans animate__animated animate__fadeInUp animate__slower">
                            <div>Powered By</div>
                            <div class="font-bold text-xs mt-1">Exo Expanse Theme Engine</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- BOTTOM NAV MENU --}}
    <div x-show="opened" class="satumomen_nav_wrap animate__animated animate__fadeInUp animate__faster">
    <div class="satumomen_menu">
        <div class="satumomen_menu_inner" x-ref="navInner"
             x-effect="
                 let items = [...($refs.navInner?.children || [])];
                 let total = items.length;
                 let idx = items.findIndex(el => Number(el.dataset.slide) === activeSlide);
                 if (idx < 0) idx = 0;
                 let maxOff = Math.max(0, total - 4);
                 let off = Math.max(0, Math.min(idx - 1, maxOff));
                 if ($refs.navInner) $refs.navInner.style.transform = 'translateX(-' + (off * 65) + 'px)';
             ">
            {{-- 1. Cover --}}
            <div class="satumomen_menu_item" data-slide="1" :class="{'active': activeSlide === 1}" @click="scrollToSlide(1)">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                <span>Cover</span>
            </div>
            {{-- 2. Mempelai --}}
            <div class="satumomen_menu_item" data-slide="2" :class="{'active': activeSlide === 2}" @click="scrollToSlide(2)">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.35a4 4 0 110 5.3M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.2M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                <span>Mempelai</span>
            </div>
            {{-- 3. Ayat --}}
            <div class="satumomen_menu_item" data-slide="3" :class="{'active': activeSlide === 3}" @click="scrollToSlide(3)">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                <span>Ayat</span>
            </div>
            {{-- 4. Love Story --}}
            @if($invitation->love_story && count($invitation->love_story) > 0)
            <div class="satumomen_menu_item" data-slide="4" :class="{'active': activeSlide === 4}" @click="scrollToSlide(4)">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                <span>Cerita</span>
            </div>
            @endif
            {{-- 5. Acara --}}
            <div class="satumomen_menu_item" data-slide="5" :class="{'active': activeSlide === 5}" @click="scrollToSlide(5)">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <span>Acara</span>
            </div>
            {{-- 6. Lokasi --}}
            <div class="satumomen_menu_item" data-slide="6" :class="{'active': activeSlide === 6}" @click="scrollToSlide(6)">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <span>Lokasi</span>
            </div>
            {{-- 7. Galeri --}}
            @if($invitation->enable_gallery)
            <div class="satumomen_menu_item" data-slide="7" :class="{'active': activeSlide === 7}" @click="scrollToSlide(7)">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <span>Galeri</span>
            </div>
            @endif
            {{-- 8. Hadiah --}}
            @if($invitation->enable_gift)
            <div class="satumomen_menu_item" data-slide="8" :class="{'active': activeSlide === 8}" @click="scrollToSlide(8)">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/></svg>
                <span>Hadiah</span>
            </div>
            @endif
            {{-- 9. RSVP --}}
            @if($invitation->enable_rsvp || $invitation->enable_wishes)
            <div class="satumomen_menu_item" data-slide="9" :class="{'active': activeSlide === 9}" @click="scrollToSlide(9)">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                <span>RSVP</span>
            </div>
            @endif
            {{-- Musik --}}
            @if($invitation->music_url)
            <div class="satumomen_menu_item" data-slide="0" @click="toggleAudio()" :class="{'active': playing}">
                <svg x-show="playing" viewBox="0 0 24 24" fill="currentColor"><path d="M3 9v6h4l5 5V4L7 9H3zm13.5 3c0-1.77-1.02-3.29-2.5-4.03v8.05c1.48-.73 2.5-2.25 2.5-4.02z"/></svg>
                <svg x-show="!playing" viewBox="0 0 24 24" fill="currentColor"><path d="M16.5 12c0-1.77-1.02-3.29-2.5-4.03v2.21l2.45 2.45c.03-.2.05-.41.05-.63zm2.5 0c0 .94-.2 1.82-.54 2.64l1.51 1.51C20.63 14.91 21 13.5 21 12c0-4.28-2.99-7.86-7-8.77v2.06c2.89.86 5 3.54 5 6.71zM4.27 3L3 4.27 7.73 9H3v6h4l5 5v-6.73l4.25 4.25c-.67.52-1.42.93-2.25 1.18v2.06c1.38-.31 2.63-.95 3.69-1.81L19.73 21 21 19.73l-9-9L4.27 3zM12 4L9.91 6.09 12 8.18V4z"/></svg>
                <span x-text="playing ? 'Musik' : 'Mute'"></span>
            </div>
            @endif
        </div>
    </div>
    </div>
</div>
