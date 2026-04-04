
@section('title', 'The Wedding of ' . $invitation->groom_nickname . ' & ' . $invitation->bride_nickname)

@push('fonts')
{{-- Fonts: Playfair Display (Judul Mewah), Dancing Script (Latin), Lato (Bacaan) --}}
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=Dancing+Script:wght@400;600&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
@endpush

@push('styles')
<style>
:root {
    --sage-dark: #4A5D46;
    --sage-medium: #6F8B68;
    --sage-light: #E9F0E7;
    --dusty-pink: #D6C0B3;
    --sand: #F9F7F2;
    --text-main: #3D403D;
    --white: #FFFFFF;
}

* { margin: 0; padding: 0; box-sizing: border-box; }

html {
    scroll-behavior: smooth;
    -webkit-tap-highlight-color: transparent;
}

body { 
    font-family: 'Lato', sans-serif; 
    background-color: var(--sand);
    color: var(--text-main); 
    overflow-x: hidden;
    /* Subtle paper texture */
    background-image: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%236f8b68' fill-opacity='0.05' fill-rule='evenodd'/%3E%3C/svg%3E");
}

.font-serif { font-family: 'Playfair Display', serif; }
.font-script { font-family: 'Dancing Script', cursive; }

/* === HELPERS === */
.arch-top { border-radius: 200px 200px 0 0; }
.arch-full { border-radius: 200px; }
.rounded-box { border-radius: 24px; }

/* === ANIMATIONS === */
@keyframes slideUp { from { transform: translateY(50px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
@keyframes sway { 0%, 100% { transform: rotate(-5deg); } 50% { transform: rotate(5deg); } }
@keyframes float { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-10px); } }
@keyframes fadeUp {
    from { opacity: 0; transform: translateY(30px); }
    to { opacity: 1; transform: translateY(0); }
}
@keyframes fadeInScale {
    from { opacity: 0; transform: scale(0.85); }
    to { opacity: 1; transform: scale(1); }
}
@keyframes leafDrift {
    0% { transform: translateY(-10vh) rotate(0deg) translateX(0); opacity: 0; }
    10% { opacity: 0.7; }
    50% { transform: translateY(50vh) rotate(180deg) translateX(30px); opacity: 0.5; }
    100% { transform: translateY(110vh) rotate(360deg) translateX(-20px); opacity: 0; }
}
@keyframes twinkle {
    0%, 100% { opacity: 0.2; transform: scale(0.8); }
    50% { opacity: 1; transform: scale(1.3); }
}
@keyframes breathe {
    0%, 100% { transform: scale(1); opacity: 0.4; }
    50% { transform: scale(1.15); opacity: 0.7; }
}
@keyframes shimmer {
    0% { background-position: -200% 0; }
    100% { background-position: 200% 0; }
}
@keyframes gentleSway {
    0%, 100% { transform: rotate(-3deg); }
    50% { transform: rotate(3deg); }
}
@keyframes pulseGlow {
    0%, 100% { box-shadow: 0 0 15px rgba(111,139,104,0.2); }
    50% { box-shadow: 0 0 30px rgba(111,139,104,0.4); }
}
@keyframes borderShine {
    0% { border-color: rgba(111,139,104,0.3); }
    50% { border-color: rgba(196,163,90,0.6); }
    100% { border-color: rgba(111,139,104,0.3); }
}
@keyframes scrollBounce {
    0%, 100% { transform: translateY(0); opacity: 0.6; }
    50% { transform: translateY(8px); opacity: 1; }
}
@keyframes butterflyFly {
    0% { transform: translate(0, 0) rotate(5deg) scale(0.8); opacity: 0; }
    10% { opacity: 0.8; }
    25% { transform: translate(60px, -40px) rotate(-10deg) scale(1); }
    50% { transform: translate(20px, -80px) rotate(15deg) scale(0.9); }
    75% { transform: translate(-40px, -50px) rotate(-5deg) scale(1.1); }
    90% { opacity: 0.6; }
    100% { transform: translate(-80px, -100px) rotate(10deg) scale(0.7); opacity: 0; }
}
@keyframes butterflyFly2 {
    0% { transform: translate(0, 0) rotate(-5deg) scale(0.7); opacity: 0; }
    10% { opacity: 0.7; }
    25% { transform: translate(-50px, -60px) rotate(10deg) scale(1); }
    50% { transform: translate(-20px, -30px) rotate(-15deg) scale(0.85); }
    75% { transform: translate(50px, -70px) rotate(5deg) scale(1); }
    90% { opacity: 0.5; }
    100% { transform: translate(70px, -110px) rotate(-10deg) scale(0.6); opacity: 0; }
}
@keyframes wingFlap {
    0%, 100% { transform: scaleX(1); }
    50% { transform: scaleX(0.3); }
}

.animate-slide-up { animation: slideUp 1s ease forwards; }
.animate-sway { animation: sway 4s ease-in-out infinite; transform-origin: bottom center; }
.animate-fade-up { animation: fadeUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; opacity: 0; }
.animate-fade-scale { animation: fadeInScale 0.6s ease forwards; opacity: 0; }
.animate-float { animation: float 5s ease-in-out infinite; }
.animate-twinkle { animation: twinkle 3s ease-in-out infinite; }
.animate-breathe { animation: breathe 4s ease-in-out infinite; }

/* === FLOATING LEAF PARTICLES === */
.leaf-particles {
    position: absolute;
    inset: 0;
    pointer-events: none;
    overflow: hidden;
    z-index: 1;
}
.leaf-particle {
    position: absolute;
    animation: leafDrift linear infinite;
    opacity: 0;
}
.leaf-particle svg {
    fill: var(--sage-medium);
}
.leaf-particle:nth-child(1) { left: 10%; animation-duration: 12s; animation-delay: 0s; }
.leaf-particle:nth-child(2) { left: 25%; animation-duration: 16s; animation-delay: 3s; }
.leaf-particle:nth-child(3) { left: 45%; animation-duration: 14s; animation-delay: 5s; }
.leaf-particle:nth-child(4) { left: 65%; animation-duration: 18s; animation-delay: 2s; }
.leaf-particle:nth-child(5) { left: 80%; animation-duration: 13s; animation-delay: 7s; }
.leaf-particle:nth-child(6) { left: 90%; animation-duration: 15s; animation-delay: 4s; }
.leaf-particle:nth-child(odd) svg { width: 16px; height: 16px; fill: var(--sage-light); }
.leaf-particle:nth-child(even) svg { width: 22px; height: 22px; fill: var(--dusty-pink); opacity: 0.6; }

/* === SPARKLE DOTS === */
.sparkle-dot {
    position: absolute;
    width: 4px;
    height: 4px;
    background: var(--dusty-pink);
    border-radius: 50%;
    animation: twinkle ease-in-out infinite;
    pointer-events: none;
    z-index: 2;
}
.sparkle-dot:nth-child(1) { top: 15%; left: 12%; animation-duration: 2.5s; animation-delay: 0s; }
.sparkle-dot:nth-child(2) { top: 25%; left: 85%; animation-duration: 3s; animation-delay: 0.8s; }
.sparkle-dot:nth-child(3) { top: 60%; left: 8%; animation-duration: 2.8s; animation-delay: 1.5s; }
.sparkle-dot:nth-child(4) { top: 75%; left: 92%; animation-duration: 3.2s; animation-delay: 0.3s; }
.sparkle-dot:nth-child(5) { top: 45%; left: 90%; animation-duration: 2.2s; animation-delay: 2s; }

/* === GLOW ORBS === */
.glow-orb {
    position: absolute;
    border-radius: 50%;
    filter: blur(60px);
    pointer-events: none;
    animation: breathe 6s ease-in-out infinite;
}
.glow-orb.sage { background: rgba(111,139,104,0.15); }
.glow-orb.pink { background: rgba(214,192,179,0.2); }

/* === BUTTERFLIES === */
.butterfly-container {
    position: absolute;
    inset: 0;
    pointer-events: none;
    overflow: hidden;
    z-index: 2;
}
.butterfly {
    position: absolute;
    animation: butterflyFly linear infinite;
}
.butterfly svg {
    width: 24px;
    height: 24px;
    animation: wingFlap 0.3s ease-in-out infinite;
}
.butterfly:nth-child(1) { bottom: 30%; left: 15%; animation-duration: 10s; animation-delay: 0s; }
.butterfly:nth-child(2) { bottom: 50%; right: 20%; animation-duration: 13s; animation-delay: 2s; animation-name: butterflyFly2; }
.butterfly:nth-child(3) { bottom: 20%; left: 40%; animation-duration: 11s; animation-delay: 5s; }
.butterfly:nth-child(4) { bottom: 60%; right: 10%; animation-duration: 14s; animation-delay: 3s; animation-name: butterflyFly2; }
.butterfly:nth-child(5) { bottom: 40%; left: 70%; animation-duration: 12s; animation-delay: 7s; }
.butterfly:nth-child(odd) svg { fill: var(--sage-medium); }
.butterfly:nth-child(even) svg { fill: var(--dusty-pink); }
.butterfly:nth-child(3n) svg { width: 18px; height: 18px; fill: var(--sage-light); }

/* === COVER === */
.cover { 
    position: fixed; inset: 0; z-index: 100; 
    background: linear-gradient(180deg, #F9F7F2 0%, #EEF2EC 40%, #F9F7F2 100%);
    display: flex; flex-direction: column; align-items: center; justify-content: center;
    padding: 24px;
    padding-bottom: max(24px, env(safe-area-inset-bottom));
    overflow-y: auto;
    gap: 16px;
}
.cover-top {
    display: flex;
    flex-direction: column;
    align-items: center;
    width: 100%;
    position: relative;
    z-index: 10;
}
.cover-label {
    font-family: 'Lato', sans-serif;
    font-size: 11px;
    letter-spacing: 4px;
    text-transform: uppercase;
    color: var(--sage-medium);
    margin-bottom: 16px;
    font-weight: 600;
}
.cover-arch {
    width: 60%; max-width: 240px; 
    aspect-ratio: 3/4;
    border-radius: 200px 200px 0 0;
    overflow: hidden;
    position: relative;
    border: 5px solid white;
    box-shadow: 0 15px 40px rgba(74, 93, 70, 0.15), 0 0 0 1px rgba(111,139,104,0.1);
    animation: pulseGlow 4s ease-in-out infinite;
    flex-shrink: 0;
    background: var(--sage-light);
}
.cover-arch::after {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(180deg, transparent 60%, rgba(74,93,70,0.1) 100%);
    pointer-events: none;
}
.cover-img { width: 100%; height: 100%; object-fit: cover; display: block; }
.cover-names {
    font-family: 'Playfair Display', serif;
    font-size: 2.2rem;
    color: var(--sage-dark);
    line-height: 1.15;
    margin-top: 20px;
    text-align: center;
}
.cover-names .cover-and {
    font-family: 'Dancing Script', cursive;
    color: var(--dusty-pink);
    font-size: 2rem;
    display: block;
    margin: 4px 0;
}
.cover-bottom {
    flex-shrink: 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 20px;
    padding-bottom: 8px;
    width: 100%;
    position: relative;
    z-index: 10;
}
.cover-guest-card {
    background: rgba(255,255,255,0.85);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    padding: 16px 32px;
    border-radius: 20px;
    border: 1px solid rgba(111,139,104,0.15);
    box-shadow: 0 8px 30px rgba(0,0,0,0.04);
    text-align: center;
    min-width: 220px;
}
.cover-guest-label {
    font-size: 0.75rem;
    color: #999;
    margin-bottom: 4px;
}
.cover-guest-name {
    font-family: 'Dancing Script', cursive;
    font-size: 1.6rem;
    color: var(--sage-dark);
}
.cover-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 14px 36px;
    background: linear-gradient(135deg, var(--sage-medium), var(--sage-dark));
    color: white;
    border: none;
    border-radius: 50px;
    font-family: 'Lato', sans-serif;
    font-size: 0.8rem;
    font-weight: 700;
    letter-spacing: 2px;
    text-transform: uppercase;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 6px 20px rgba(74,93,70,0.3);
}
.cover-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 30px rgba(74,93,70,0.4);
}
.cover-btn:active {
    transform: translateY(0);
}
.cover-btn svg { width: 16px; height: 16px; }
.cover-content {
    text-align: center;
    width: 100%;
    max-width: 400px;
    z-index: 101;
}

/* === DECORATIVE CORNER FLOWERS === */
.corner-leaf {
    position: absolute;
    pointer-events: none;
    z-index: 3;
    width: 160px;
    height: 160px;
    background: url('/assets/themes/bunga_matahari_pinggir_atas.png') no-repeat;
    background-size: contain;
}

/* Kanan Atas (Normal) */
.corner-leaf.tr { 
    top: -10px; 
    right: -10px; 
    animation: gentleSway 7s ease-in-out infinite; 
}

/* Kiri Atas (Mirror) */
.corner-leaf.tl { 
    top: -10px; 
    left: -10px; 
    /* Gunakan transform-origin agar posisi tidak geser saat di-scale */
    transform: scaleX(-1); 
    animation: gentleSway 6s ease-in-out infinite; 
}

/* Tambahkan ini jika animasi Anda membuat gambar goyang tidak karuan */
@keyframes gentleSway {
    0%, 100% { transform: rotate(0deg) scaleX(1); }
    50% { transform: rotate(3deg) scaleX(1); }
}

/* Khusus TL agar tetap mirror saat animasi jalan */
.corner-leaf.tl {
    animation: gentleSwayLeft 6s ease-in-out infinite;
}

@keyframes gentleSwayLeft {
    0%, 100% { transform: scaleX(-1) rotate(0deg); }
    50% { transform: scaleX(-1) rotate(-3deg); }
}
/* === BUTTONS === */
.btn {
    display: inline-block; padding: 14px 35px;
    background: var(--sage-medium); color: white;
    border-radius: 50px; border: none; cursor: pointer;
    font-family: 'Lato', sans-serif; font-weight: 700; letter-spacing: 1px;
    text-transform: uppercase; font-size: 12px;
    transition: all 0.3s;
    box-shadow: 0 4px 15px rgba(111, 139, 104, 0.3);
}
.btn:hover { background: var(--sage-dark); transform: translateY(-2px); }
.btn-outline {
    background: transparent; border: 1px solid var(--sage-medium); color: var(--sage-medium);
}

/* === HERO === */
.hero-section {
    min-height: 100vh;
    min-height: 100svh;
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 50px 20px 40px;
    text-align: center;
    background: linear-gradient(180deg, #F9F7F2 0%, #EEF2EC 50%, #F9F7F2 100%);
    overflow: hidden;
}
/* Flower Decoration */
.flower-decor {
    position: absolute;
    width: 150px;
    height: 150px;
    background: url('/assets/themes/bunga_matahari_pinggir_atas.png') no-repeat;
    background-size: contain;
    pointer-events: none;
    z-index: 1;
}

/* Kanan Atas (Normal) */
.flower-tr { 
    top: -10px; 
    right: -10px; 
    animation: gentleSway 6s ease-in-out infinite; 
}

/* Kiri Atas (Mirror) */
.flower-tl { 
    top: -10px; 
    left: -10px; 
    transform: scaleX(-1); 
    animation: gentleSwayLeft 7s ease-in-out infinite; 
}

.hero-photo-wrapper {
    position: relative;
    z-index: 10;
    margin-bottom: 12px;
}
.hero-photo-frame {
    width: 140px;
    height: 140px;
    border-radius: 50%;
    overflow: hidden;
    position: relative;
    border: 4px solid white;
    box-shadow: 0 8px 30px rgba(74,93,70,0.15);
    animation: pulseGlow 4s ease-in-out infinite;
}
.hero-photo-frame img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.hero-names {
    font-family: 'Playfair Display', serif;
    font-size: 2.4rem; color: var(--sage-dark);
    line-height: 1.1; margin: 8px 0;
    position: relative;
    z-index: 10;
}
.hero-and {
    font-family: 'Dancing Script', cursive;
    color: var(--dusty-pink); font-size: 1.8rem;
    display: block;
    margin: 2px 0;
}
.hero-date {
    font-family: 'Lato', sans-serif;
    font-size: 0.8rem;
    letter-spacing: 3px;
    text-transform: uppercase;
    color: var(--sage-medium);
    margin-bottom: 16px;
    position: relative;
    z-index: 10;
}
.hero-subtitle {
    font-family: 'Dancing Script', cursive;
    font-size: 1.2rem;
    color: var(--sage-medium);
    margin-bottom: 4px;
    position: relative;
    z-index: 10;
}

/* Botanical Divider */
.botanical-divider {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    margin: 8px 0;
    position: relative;
    z-index: 10;
}
.botanical-divider::before,
.botanical-divider::after {
    content: '';
    width: 50px;
    height: 1px;
    background: linear-gradient(90deg, transparent, var(--sage-medium), transparent);
}
.botanical-divider svg {
    width: 20px;
    height: 20px;
    fill: var(--sage-medium);
    animation: gentleSway 3s ease-in-out infinite;
}

/* Scroll Indicator */
.scroll-indicator {
    position: relative;
    margin-top: 8px;
    color: var(--sage-medium);
    z-index: 10;
    animation: scrollBounce 2s ease-in-out infinite;
}
.scroll-indicator svg {
    width: 18px;
    height: 18px;
}

/* === COUNTDOWN === */
.countdown-container {
    display: flex; justify-content: center; gap: 10px; margin-top: 12px;
    position: relative;
    z-index: 10;
}
.countdown-circle {
    width: 58px; height: 58px;
    background: white; 
    border: 2px solid rgba(111,139,104,0.2);
    border-radius: 50%;
    display: flex; flex-direction: column; align-items: center; justify-content: center;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    animation: borderShine 4s ease-in-out infinite;
}
.countdown-num { font-family: 'Playfair Display', serif; font-weight: 700; color: var(--sage-dark); font-size: 1.1rem; line-height: 1; }
.countdown-label { font-size: 0.5rem; text-transform: uppercase; color: #999; letter-spacing: 0.5px; margin-top: 2px; }

/* === SAVE CALENDAR BTN === */
.save-date-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 10px 20px;
    background: white;
    border: 2px solid rgba(111,139,104,0.2);
    border-radius: 50px;
    color: var(--sage-dark);
    font-family: 'Lato', sans-serif;
    font-size: 0.75rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    position: relative;
    z-index: 10;
    margin-top: 12px;
}
.save-date-btn:hover {
    background: var(--sage-medium);
    color: white;
    border-color: var(--sage-medium);
}
.save-date-btn svg {
    width: 16px;
    height: 16px;
}

/* === MOBILE OPTIMIZATIONS === */
@media (max-width: 480px) {
    .cover-arch { width: 55%; }
    .cover-names { font-size: 1.8rem; }
    .hero-names { font-size: 2rem; }
    .corner-leaf { width: 130px; height: 130px; }
    .flower-decor { width: 120px; height: 120px; }
    .hero-photo-frame { width: 120px; height: 120px; }
    .butterfly svg { width: 20px; height: 20px; }
    .countdown-circle { width: 52px; height: 52px; }
    .countdown-num { font-size: 1rem; }
    .hero-section { padding: 0px 16px 30px; }
}

/* === SECTIONS === */
.section { padding: 60px 20px; position: relative; }
.section-title { text-align: center; margin-bottom: 40px; }
.section-title h2 {
    font-family: 'Dancing Script', cursive;
    font-size: 2.5rem; color: var(--sage-medium);
    margin-bottom: 10px;
}
.title-leaf {
    width: 40px; height: 2px; background: var(--sage-medium); margin: 0 auto;
    position: relative;
}
.title-leaf::after {
    content: '❀'; color: var(--sage-medium); position: absolute;
    top: -12px; left: 50%; transform: translateX(-50%); font-size: 14px;
}

/* === COUPLE === */
.couple-grid {
    display: flex; flex-direction: column; gap: 40px; max-width: 600px; margin: 0 auto;
}
.couple-card {
    background: white; border-radius: 20px; padding: 30px;
    text-align: center; position: relative;
    box-shadow: 0 10px 30px rgba(0,0,0,0.03);
}
.couple-img-frame {
    width: 150px; height: 200px;
    border-radius: 100px 100px 0 0; /* Arch Shape */
    overflow: hidden; margin: -60px auto 20px;
    border: 5px solid white;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}
.couple-img { width: 100%; height: 100%; object-fit: cover; }
.couple-name-text {
    font-family: 'Playfair Display', serif; font-size: 1.8rem; color: var(--sage-dark); margin-bottom: 5px;
}

/* === EVENTS (Timeline) === */
.timeline {
    position: relative; max-width: 500px; margin: 0 auto;
    border-left: 2px dashed var(--sage-medium); margin-left: 20px;
}
.event-item {
    margin-left: 30px; margin-bottom: 40px; position: relative;
    background: white; padding: 25px; border-radius: 0 20px 20px 20px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.05);
}
.event-dot {
    position: absolute; left: -41px; top: 20px;
    width: 20px; height: 20px; background: var(--sage-medium);
    border-radius: 50%; border: 4px solid var(--sand);
}
.event-name { font-family: 'Playfair Display', serif; font-size: 1.5rem; color: var(--sage-dark); }
.event-meta { display: flex; align-items: center; gap: 8px; color: #777; margin: 5px 0; font-size: 0.9rem; }

/* === GIFT CARD === */
.atm-card {
    background: linear-gradient(135deg, var(--sage-medium), var(--sage-dark));
    color: white; border-radius: 15px; padding: 25px;
    max-width: 400px; margin: 0 auto 20px;
    box-shadow: 0 10px 25px rgba(74, 93, 70, 0.3);
    position: relative; overflow: hidden;
}
.atm-card::before {
    content: ''; position: absolute; top: -50px; right: -50px;
    width: 150px; height: 150px; background: rgba(255,255,255,0.1);
    border-radius: 50%;
}
.copy-pill {
    background: rgba(255,255,255,0.2); padding: 5px 15px;
    border-radius: 20px; font-size: 0.8rem; cursor: pointer;
    display: inline-flex; align-items: center; gap: 5px;
    backdrop-filter: blur(5px); transition: 0.3s;
}
.copy-pill:hover { background: white; color: var(--sage-dark); }

/* === RSVP FORM === */
.rsvp-box {
    background: white; border-radius: 20px; padding: 30px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.05);
}
.input-field {
    width: 100%; padding: 15px; background: var(--sand); border: 1px solid transparent;
    border-radius: 10px; margin-bottom: 15px; font-family: 'Lato', sans-serif;
    transition: 0.3s;
}
.input-field:focus { outline: none; border-color: var(--sage-medium); background: white; }

/* === NAVBAR === */
.glass-nav {
    position: fixed; bottom: 20px; left: 50%; transform: translateX(-50%);
    background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(10px);
    border-radius: 50px; padding: 10px 30px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    display: flex; gap: 25px; z-index: 999;
    border: 1px solid rgba(255,255,255,0.5);
}
.nav-icon {
    color: #999; font-size: 1.2rem; transition: 0.3s;
    position: relative; display: flex; flex-direction: column; align-items: center;
}
.nav-icon.active { color: var(--sage-dark); transform: translateY(-3px); }
.nav-icon.active::after {
    content: ''; width: 4px; height: 4px; background: var(--sage-dark);
    border-radius: 50%; margin-top: 3px;
}

/* === MUSIC === */
.music-trigger {
    position: fixed; top: 20px; right: 20px;
    width: 40px; height: 40px; background: white;
    border-radius: 50%; box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    display: flex; align-items: center; justify-content: center;
    color: var(--sage-dark); z-index: 90; cursor: pointer;
}
.music-trigger.playing { animation: spin 4s linear infinite; }
@keyframes spin { 100% { transform: rotate(360deg); } }

/* === LIGHTBOX === */
.lightbox-overlay {
    background: rgba(255,255,255,0.95); backdrop-filter: blur(5px);
}
</style>
@endpush

<div x-data="{
    opened: false,
    playing: false,
    audioEl: null,
    activeSection: 'home',
    
    init() {
        document.body.style.overflow = 'hidden';
        this.$watch('opened', (value) => { if(value) this.$nextTick(() => this.setupScrollSpy()); });
    },
    setupScrollSpy() {
        const sections = ['home', 'couple', 'events', 'gift', 'rsvp'];
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => { if(entry.isIntersecting) this.activeSection = entry.target.id; });
        }, { threshold: 0.3 });
        sections.forEach(id => {
            const el = document.getElementById(id);
            if(el) observer.observe(el);
        });
    },
    open() {
        this.opened = true;
        document.body.style.overflow = 'auto';
        this.$nextTick(() => {
            this.audioEl = document.getElementById('bgMusic');
            if(this.audioEl) {
                this.audioEl.volume = 0.6;
                this.audioEl.play().then(() => this.playing = true).catch(() => {});
            }
        });
    },
    toggleAudio() {
        if(!this.audioEl) return;
        if(this.playing) { this.audioEl.pause(); this.playing = false; }
        else { this.audioEl.play(); this.playing = true; }
    },
    scrollTo(id) {
        this.activeSection = id;
        document.getElementById(id)?.scrollIntoView({ behavior: 'smooth' });
    }
}">
    @if($invitation->background_music)
    <audio id="bgMusic" loop preload="auto">
        <source src="{{ str_starts_with($invitation->background_music, 'http') ? $invitation->background_music : img_url($invitation->background_music) }}" type="audio/mpeg">
    </audio>
    @endif

    {{-- COVER --}}
    <div x-show="!opened" x-transition:leave="transition duration-1000 transform" x-transition:leave-end="-translate-y-full" class="cover">
        {{-- Decorative Corner Flowers --}}
        <div class="corner-leaf tr"></div>
        <div class="corner-leaf tl"></div>
        
        {{-- Sparkle Dots --}}
        <div class="sparkle-dot"></div>
        <div class="sparkle-dot"></div>
        <div class="sparkle-dot"></div>
        <div class="sparkle-dot"></div>
        <div class="sparkle-dot"></div>
        
        {{-- Glow Orbs --}}
        <div class="glow-orb sage" style="width: 180px; height: 180px; top: 10%; left: -40px;"></div>
        <div class="glow-orb pink" style="width: 150px; height: 150px; bottom: 15%; right: -30px;"></div>

        {{-- Butterflies --}}
        <div class="butterfly-container">
            <div class="butterfly"><svg viewBox="0 0 24 24"><path d="M12 2C9 2 6.5 4 5.5 7c-1.5-1-3.5-1-4.5 0s-1 3.5 0 4.5c-2 2-2 5 0 7s5 2 7 0c1.5 1 3.5 1 4.5 0 1-1.5 1-3.5 0-4.5 2-2 2-5 0-7s-5-2-7 0z"/></svg></div>
            <div class="butterfly"><svg viewBox="0 0 24 24"><path d="M12 2C9 2 6.5 4 5.5 7c-1.5-1-3.5-1-4.5 0s-1 3.5 0 4.5c-2 2-2 5 0 7s5 2 7 0c1.5 1 3.5 1 4.5 0 1-1.5 1-3.5 0-4.5 2-2 2-5 0-7s-5-2-7 0z"/></svg></div>
            <div class="butterfly"><svg viewBox="0 0 24 24"><path d="M12 2C9 2 6.5 4 5.5 7c-1.5-1-3.5-1-4.5 0s-1 3.5 0 4.5c-2 2-2 5 0 7s5 2 7 0c1.5 1 3.5 1 4.5 0 1-1.5 1-3.5 0-4.5 2-2 2-5 0-7s-5-2-7 0z"/></svg></div>
            <div class="butterfly"><svg viewBox="0 0 24 24"><path d="M12 2C9 2 6.5 4 5.5 7c-1.5-1-3.5-1-4.5 0s-1 3.5 0 4.5c-2 2-2 5 0 7s5 2 7 0c1.5 1 3.5 1 4.5 0 1-1.5 1-3.5 0-4.5 2-2 2-5 0-7s-5-2-7 0z"/></svg></div>
            <div class="butterfly"><svg viewBox="0 0 24 24"><path d="M12 2C9 2 6.5 4 5.5 7c-1.5-1-3.5-1-4.5 0s-1 3.5 0 4.5c-2 2-2 5 0 7s5 2 7 0c1.5 1 3.5 1 4.5 0 1-1.5 1-3.5 0-4.5 2-2 2-5 0-7s-5-2-7 0z"/></svg></div>
        </div>

        {{-- Top Section: Label + Arch + Names --}}
        <div class="cover-top">
            <p class="cover-label animate-fade-up">{{ $invitation->custom_styles['cover_subtitle'] ?? 'THE WEDDING OF' }}</p>
            
            <div class="cover-arch">
                <img src="{{ $invitation->cover_image ? img_url($invitation->cover_image) : 'https://images.unsplash.com/photo-1515934751635-c81c6bc9a2d8?w=500' }}" class="cover-img" alt="Cover">
            </div>
            
            @php $order = $invitation->custom_styles['name_order'] ?? 'groom_first'; @endphp
            <div class="cover-names animate-fade-up" style="animation-delay: 0.2s;">
                @if($order === 'bride_first')
                    {{ $invitation->bride_nickname }}
                    <span class="cover-and">&</span>
                    {{ $invitation->groom_nickname }}
                @else
                    {{ $invitation->groom_nickname }}
                    <span class="cover-and">&</span>
                    {{ $invitation->bride_nickname }}
                @endif
            </div>
        </div>
        
        {{-- Bottom Section: Guest + Button --}}
        <div class="cover-bottom">
            <div class="cover-guest-card animate-fade-up" style="animation-delay: 0.3s;">
                <p class="cover-guest-label">Kepada Yth.</p>
                <p class="cover-guest-name">{{ $guestName }}</p>
            </div>
            <button @click="open()" class="cover-btn animate-fade-up" style="animation-delay: 0.4s;">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                Buka Undangan
            </button>
        </div>
    </div>

    {{-- MAIN CONTENT --}}
    <main x-show="opened" x-transition:enter="transition ease-out duration-1000" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
        
        {{-- MUSIC BTN --}}
        <div class="music-trigger" :class="{'playing': playing}" @click="toggleAudio()">
            <svg x-show="playing" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path d="M12 13c0 1.105-1.12 2-2.5 2S7 14.105 7 13s1.12-2 2.5-2 2.5.895 2.5 2z"/><path fill-rule="evenodd" d="M12 3v10h-1V3h1z"/><path d="M11 2.82a1 1 0 0 1 .804-.98l3-.6A1 1 0 0 1 16 2.22V4l-5 1V2.82z"/><path fill-rule="evenodd" d="M0 11.5a.5.5 0 0 1 .5-.5H4a.5.5 0 0 1 0 1H.5a.5.5 0 0 1-.5-.5zm0-4A.5.5 0 0 1 .5 7H8a.5.5 0 0 1 0 1H.5a.5.5 0 0 1-.5-.5zm0-4A.5.5 0 0 1 .5 3H8a.5.5 0 0 1 0 1H.5a.5.5 0 0 1-.5-.5z"/></svg>
            <svg x-show="!playing" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path d="M5 6.25a1.25 1.25 0 1 1 2.5 0v3.5a1.25 1.25 0 1 1-2.5 0v-3.5zm3.5 0a1.25 1.25 0 1 1 2.5 0v3.5a1.25 1.25 0 1 1-2.5 0v-3.5z"/></svg>
        </div>

        {{-- HERO --}}
        <section id="home" class="hero-section">
            {{-- Floating Leaf Particles --}}
            <div class="leaf-particles">
                <div class="leaf-particle"><svg viewBox="0 0 24 24"><path d="M17 8C8 10 5.9 16.17 3.82 21.34l1.89.66L7 19.5l.98 1.5 2-2 .5 2 2-1.5.5 2 2-1.5 1-2.5 1 2.5 1.5-2.5.5 2 2-2 .5 2 1.5-2L22 18c0-3-1-6-5-10z"/></svg></div>
                <div class="leaf-particle"><svg viewBox="0 0 24 24"><path d="M17 8C8 10 5.9 16.17 3.82 21.34l1.89.66L7 19.5l.98 1.5 2-2 .5 2 2-1.5.5 2 2-1.5 1-2.5 1 2.5 1.5-2.5.5 2 2-2 .5 2 1.5-2L22 18c0-3-1-6-5-10z"/></svg></div>
                <div class="leaf-particle"><svg viewBox="0 0 24 24"><path d="M17 8C8 10 5.9 16.17 3.82 21.34l1.89.66L7 19.5l.98 1.5 2-2 .5 2 2-1.5.5 2 2-1.5 1-2.5 1 2.5 1.5-2.5.5 2 2-2 .5 2 1.5-2L22 18c0-3-1-6-5-10z"/></svg></div>
                <div class="leaf-particle"><svg viewBox="0 0 24 24"><path d="M17 8C8 10 5.9 16.17 3.82 21.34l1.89.66L7 19.5l.98 1.5 2-2 .5 2 2-1.5.5 2 2-1.5 1-2.5 1 2.5 1.5-2.5.5 2 2-2 .5 2 1.5-2L22 18c0-3-1-6-5-10z"/></svg></div>
                <div class="leaf-particle"><svg viewBox="0 0 24 24"><path d="M17 8C8 10 5.9 16.17 3.82 21.34l1.89.66L7 19.5l.98 1.5 2-2 .5 2 2-1.5.5 2 2-1.5 1-2.5 1 2.5 1.5-2.5.5 2 2-2 .5 2 1.5-2L22 18c0-3-1-6-5-10z"/></svg></div>
                <div class="leaf-particle"><svg viewBox="0 0 24 24"><path d="M17 8C8 10 5.9 16.17 3.82 21.34l1.89.66L7 19.5l.98 1.5 2-2 .5 2 2-1.5.5 2 2-1.5 1-2.5 1 2.5 1.5-2.5.5 2 2-2 .5 2 1.5-2L22 18c0-3-1-6-5-10z"/></svg></div>
            </div>
            
            {{-- Glow Orbs --}}
            <div class="glow-orb sage" style="width: 200px; height: 200px; top: 5%; right: -40px;"></div>
            <div class="glow-orb pink" style="width: 160px; height: 160px; bottom: 10%; left: -30px;"></div>

            {{-- Flower Decorations --}}
            <div class="flower-decor flower-tr"></div>
            <div class="flower-decor flower-tl"></div>

            {{-- Subtitle --}}
            <p class="hero-subtitle animate-fade-up">We Are Getting Married</p>

            {{-- Botanical Divider --}}
            <div class="botanical-divider animate-fade-up" style="animation-delay: 0.05s;">
                <svg viewBox="0 0 24 24"><path d="M17 8C8 10 5.9 16.17 3.82 21.34l1.89.66L7 19.5l.98 1.5 2-2 .5 2 2-1.5.5 2 2-1.5 1-2.5 1 2.5 1.5-2.5.5 2 2-2 .5 2 1.5-2L22 18c0-3-1-6-5-10z"/></svg>
            </div>
            
            {{-- Photo Frame --}}
            <div class="hero-photo-wrapper animate-fade-up" style="animation-delay: 0.1s;">
                <div class="hero-photo-frame">
                    <img src="{{ $invitation->cover_image ? img_url($invitation->cover_image) : 'https://images.unsplash.com/photo-1515934751635-c81c6bc9a2d8?w=500' }}" alt="Couple">
                </div>
            </div>

            {{-- Names --}}
            <div class="hero-names animate-fade-up" style="animation-delay: 0.15s;">
                {{ $order === 'bride_first' ? $invitation->bride_nickname : $invitation->groom_nickname }}
                <div class="hero-and">&</div>
                {{ $order === 'bride_first' ? $invitation->groom_nickname : $invitation->bride_nickname }}
            </div>

            {{-- Date --}}
            <p class="hero-date animate-fade-up" style="animation-delay: 0.2s;">{{ $invitation->akad_date?->translatedFormat('d F Y') }}</p>

            {{-- Countdown --}}
            <div x-data="{
                days:0, hours:0, minutes:0, seconds:0,
                target: new Date('{{ $invitation->akad_date?->format('Y-m-d H:i:s') }}'),
                init() { this.update(); setInterval(() => this.update(), 1000); },
                update() {
                    const diff = this.target - new Date();
                    if(diff>0){
                        this.days=Math.floor(diff/86400000);
                        this.hours=Math.floor((diff%86400000)/3600000);
                        this.minutes=Math.floor((diff%3600000)/60000);
                        this.seconds=Math.floor((diff%60000)/1000);
                    }
                }
            }" class="countdown-container animate-fade-up" style="animation-delay: 0.25s;">
                <div class="countdown-circle"><span class="countdown-num" x-text="days">0</span><span class="countdown-label">Hari</span></div>
                <div class="countdown-circle"><span class="countdown-num" x-text="hours">0</span><span class="countdown-label">Jam</span></div>
                <div class="countdown-circle"><span class="countdown-num" x-text="minutes">0</span><span class="countdown-label">Menit</span></div>
                <div class="countdown-circle"><span class="countdown-num" x-text="seconds">0</span><span class="countdown-label">Detik</span></div>
            </div>

            {{-- Save Calendar --}}
            <button class="save-date-btn animate-fade-up" style="animation-delay: 0.3s;" @click="
                const title = '{{ $order === 'bride_first' ? $invitation->bride_nickname . ' & ' . $invitation->groom_nickname : $invitation->groom_nickname . ' & ' . $invitation->bride_nickname }} Wedding';
                const date = '{{ $invitation->akad_date?->format('Ymd\THis') }}';
                const location = '{{ $invitation->akad_venue }}';
                window.open(`https://calendar.google.com/calendar/render?action=TEMPLATE&text=${encodeURIComponent(title)}&dates=${date}/${date}&location=${encodeURIComponent(location)}`, '_blank');
            ">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                Simpan Tanggal
            </button>

            {{-- Scroll Indicator --}}
            <div class="scroll-indicator">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
            </div>
        </section>

        {{-- COUPLE --}}
        <section id="couple" class="section">
            <div class="section-title">
                <h2>Groom & Bride</h2>
                <div class="title-leaf"></div>
                <p style="margin-top: 15px; font-size: 0.9rem; color: #666;">"Dan di antara tanda-tanda (kebesaran)-Nya ialah Dia menciptakan pasangan-pasangan untukmu dari jenismu sendiri..." (Ar-Rum: 21)</p>
            </div>

            <div class="couple-grid">
                {{-- First --}}
                <div class="couple-card">
                    <div class="couple-img-frame">
                        <img src="{{ ($order === 'bride_first' ? $invitation->bride_photo : $invitation->groom_photo) ? img_url(($order === 'bride_first' ? $invitation->bride_photo : $invitation->groom_photo)) : 'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?w=400' }}" class="couple-img">
                    </div>
                    <h3 class="couple-name-text">{{ $order === 'bride_first' ? $invitation->bride_name : $invitation->groom_name }}</h3>
                    <p style="font-size: 0.9rem; color: #888;">Putra/Putri Bpk. {{ $order === 'bride_first' ? $invitation->bride_father : $invitation->groom_father }} & Ibu {{ $order === 'bride_first' ? $invitation->bride_mother : $invitation->groom_mother }}</p>
                </div>

                {{-- Second --}}
                <div class="couple-card">
                    <div class="couple-img-frame">
                        <img src="{{ ($order === 'bride_first' ? $invitation->groom_photo : $invitation->bride_photo) ? img_url(($order === 'bride_first' ? $invitation->groom_photo : $invitation->bride_photo)) : 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=400' }}" class="couple-img">
                    </div>
                    <h3 class="couple-name-text">{{ $order === 'bride_first' ? $invitation->groom_name : $invitation->bride_name }}</h3>
                    <p style="font-size: 0.9rem; color: #888;">Putra/Putri Bpk. {{ $order === 'bride_first' ? $invitation->groom_father : $invitation->bride_father }} & Ibu {{ $order === 'bride_first' ? $invitation->groom_mother : $invitation->bride_mother }}</p>
                </div>
            </div>
        </section>

        {{-- EVENTS --}}
        <section id="events" class="section" style="background: white;">
            <div class="section-title">
                <h2>Wedding Event</h2>
                <div class="title-leaf"></div>
            </div>

            <div class="timeline">
                {{-- Akad --}}
                <div class="event-item">
                    <div class="event-dot"></div>
                    <h3 class="event-name">Akad Nikah</h3>
                    <div class="event-meta">
                        <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16"><path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/></svg>
                        {{ $invitation->akad_date?->translatedFormat('l, d F Y') }}
                    </div>
                    <div class="event-meta">
                        <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16"><path d="M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71V3.5z"/><path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0z"/></svg>
                        {{ $invitation->akad_date?->format('H:i') }} WIB
                    </div>
                    <p style="margin: 10px 0; font-size: 0.95rem;">{{ $invitation->akad_venue }}</p>
                    <a href="{{ $invitation->akad_maps_link }}" target="_blank" class="btn btn-outline" style="padding: 8px 20px; font-size: 10px;">Google Maps</a>
                </div>

                {{-- Resepsi --}}
                @if($invitation->resepsi_date)
                <div class="event-item">
                    <div class="event-dot"></div>
                    <h3 class="event-name">Resepsi</h3>
                    <div class="event-meta">
                        <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16"><path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/></svg>
                        {{ $invitation->resepsi_date?->translatedFormat('l, d F Y') }}
                    </div>
                    <div class="event-meta">
                        <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16"><path d="M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71V3.5z"/><path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0z"/></svg>
                        {{ $invitation->resepsi_date?->format('H:i') }} WIB - Selesai
                    </div>
                    <p style="margin: 10px 0; font-size: 0.95rem;">{{ $invitation->resepsi_venue }}</p>
                    <a href="{{ $invitation->resepsi_maps_link }}" target="_blank" class="btn btn-outline" style="padding: 8px 20px; font-size: 10px;">Google Maps</a>
                </div>
                @endif
            </div>
        </section>

        {{-- LOVE STORY --}}
        @if($invitation->love_story && count($invitation->love_story) > 0)
        <section id="lovestory" class="section" style="background: var(--sand);">
            <div class="section-title">
                <h2>Kisah Cinta Kami</h2>
                <div class="title-leaf"></div>
            </div>

            <div style="position: relative; max-width: 500px; margin: 0 auto; padding: 0 16px;">
                <div style="position: absolute; left: 31px; top: 0; bottom: 0; width: 2px; background: linear-gradient(180deg, transparent, var(--sage-medium), var(--sage-medium), transparent);"></div>

                @foreach($invitation->love_story as $index => $story)
                <div class="animate-fade-up" style="display: flex; align-items: flex-start; margin-bottom: {{ $loop->last ? '0' : '28px' }}; position: relative; opacity: 0; animation-delay: {{ $index * 0.15 }}s;">
                    <div style="flex-shrink: 0; width: 32px; display: flex; justify-content: center; position: relative; z-index: 2; padding-top: 20px;">
                        <div style="width: 14px; height: 14px; background: white; border: 3px solid var(--sage-medium); border-radius: 50%; box-shadow: 0 0 0 4px rgba(111,139,104,0.15);"></div>
                    </div>
                    <div style="flex: 1; margin-left: 12px; padding: 20px; background: white; border-radius: 20px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); border-left: 3px solid var(--dusty-pink); position: relative; overflow: hidden;">
                        <div style="display: inline-block; padding: 4px 14px; background: var(--sage-light); color: var(--sage-dark); border-radius: 20px; font-size: 11px; font-weight: 600; letter-spacing: 1px; margin-bottom: 10px;">{{ $story['date'] ?? '' }}</div>
                        <h3 class="font-serif" style="font-size: 1.15rem; color: var(--sage-dark); font-weight: 600; margin-bottom: 6px; line-height: 1.3;">{{ $story['title'] ?? '' }}</h3>
                        <p style="font-size: 13px; line-height: 1.75; color: var(--text-main);">{{ $story['description'] ?? '' }}</p>
                        <div class="font-script" style="position: absolute; bottom: -4px; right: 8px; font-size: 3rem; color: var(--dusty-pink); opacity: 0.3; pointer-events: none; transform: rotate(-12deg);">❦</div>
                    </div>
                </div>
                @endforeach
            </div>
        </section>
        @endif

        {{-- GIFT --}}
        @if($invitation->enable_gift)
        <section id="gift" class="section">
            <div class="section-title">
                <h2>Wedding Gift</h2>
                <div class="title-leaf"></div>
            </div>
            
            <div style="text-align: center;">
                @if($invitation->bank_accounts)
                    @foreach($invitation->bank_accounts as $account)
                    <div class="atm-card">
                        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 30px;">
                            <span style="font-weight: 700; font-size: 1.2rem;">{{ $account['bank'] }}</span>
                            <svg width="30" height="30" fill="currentColor" viewBox="0 0 16 16"><path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4zm2-1a1 1 0 0 0-1 1v1h14V4a1 1 0 0 0-1-1H2zm13 4H1v5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V7z"/></svg>
                        </div>
                        <p style="font-family: monospace; font-size: 1.4rem; letter-spacing: 2px; margin-bottom: 20px;">{{ $account['account_number'] }}</p>
                        <div style="display: flex; justify-content: space-between; align-items: flex-end;">
                            <div style="text-align: left;">
                                <span style="font-size: 0.7rem; opacity: 0.8;">Account Name</span><br>
                                <span>{{ $account['account_name'] }}</span>
                            </div>
                            <div x-data="{ copied: false }" @click="navigator.clipboard.writeText('{{ $account['account_number'] }}'); copied=true; setTimeout(()=>copied=false, 2000)" class="copy-pill">
                                <span x-text="copied ? 'Copied' : 'Copy'"></span>
                                <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @elseif($invitation->bank_name)
                    <div class="atm-card">
                        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 30px;">
                            <span style="font-weight: 700; font-size: 1.2rem;">{{ $invitation->bank_name }}</span>
                            <svg width="30" height="30" fill="currentColor" viewBox="0 0 16 16"><path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4zm2-1a1 1 0 0 0-1 1v1h14V4a1 1 0 0 0-1-1H2zm13 4H1v5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V7z"/></svg>
                        </div>
                        <p style="font-family: monospace; font-size: 1.4rem; letter-spacing: 2px; margin-bottom: 20px;">{{ $invitation->bank_account }}</p>
                        <div style="display: flex; justify-content: space-between; align-items: flex-end;">
                            <div style="text-align: left;">
                                <span style="font-size: 0.7rem; opacity: 0.8;">Account Name</span><br>
                                <span>{{ $invitation->bank_holder }}</span>
                            </div>
                            <div x-data="{ copied: false }" @click="navigator.clipboard.writeText('{{ $invitation->bank_account }}'); copied=true; setTimeout(()=>copied=false, 2000)" class="copy-pill">
                                <span x-text="copied ? 'Copied' : 'Copy'"></span>
                                <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </section>
        @endif

        {{-- RSVP & WISHES --}}
        @if($invitation->enable_rsvp || $invitation->enable_wishes)
        <section id="rsvp" class="section" style="background: white;"
            x-data="{
                invitationId: {{ $invitation->id }},
                name: '{{ request('kpd', '') }}',
                message: '',
                status: 'confirmed',
                pax: 1,
                loading: false,
                success: false,
                error: '',
                wishes: [],
                stats: { total_wishes: 0, total_confirmed: 0 },
                
                async submitForm() {
                    if (!this.name.trim() || !this.message.trim()) {
                        this.error = 'Mohon lengkapi nama dan ucapan Anda.';
                        return;
                    }
                    this.loading = true;
                    this.error = '';
                    try {
                        await fetch(`/api/invitations/${this.invitationId}/rsvp`, {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content || '' },
                            body: JSON.stringify({ name: this.name, status: this.status, pax: this.pax })
                        });
                        const wishRes = await fetch(`/api/invitations/${this.invitationId}/wishes`, {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content || '' },
                            body: JSON.stringify({ name: this.name, message: this.message })
                        });
                        if (wishRes.ok) {
                            const data = await wishRes.json();
                            this.wishes.unshift(data.wish);
                            this.stats.total_wishes++;
                            if (this.status === 'confirmed') this.stats.total_confirmed += parseInt(this.pax);
                            this.message = '';
                            this.success = true;
                            setTimeout(() => this.success = false, 5000);
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
                async loadStats() {
                    try {
                        const res = await fetch(`/api/invitations/${this.invitationId}/stats`);
                        this.stats = await res.json();
                    } catch (e) {}
                },
                init() { this.loadWishes(); this.loadStats(); }
            }">
            <div class="section-title">
                <h2>RSVP & Wishes</h2>
                <div class="title-leaf"></div>
            </div>

            <div class="max-w-md mx-auto">
                {{-- RSVP Form --}}
                <div class="rsvp-box">
                    {{-- Success --}}
                    <div x-show="success" x-transition style="margin-bottom: 15px; padding: 12px; background: rgba(40,167,69,0.15); border: 1px solid #28a745; border-radius: 10px; text-align: center; color: #155724; font-size: 0.9rem;">
                        ✓ Terima kasih! Ucapan dan konfirmasi Anda telah tersimpan.
                    </div>
                    {{-- Error --}}
                    <div x-show="error" x-transition style="margin-bottom: 15px; padding: 12px; background: rgba(220,53,69,0.15); border: 1px solid #dc3545; border-radius: 10px; text-align: center; color: #721c24; font-size: 0.9rem;" x-text="error"></div>

                    <form @submit.prevent="submitForm">
                        <div style="margin-bottom: 15px;">
                            <label style="display: block; margin-bottom: 5px; font-weight: bold; color: var(--sage-dark);">Nama Lengkap</label>
                            <input type="text" x-model="name" placeholder="Nama Lengkap" class="input-field">
                        </div>

                        <div style="margin-bottom: 15px;">
                            <label style="display: block; margin-bottom: 5px; font-weight: bold; color: var(--sage-dark);">Ucapan & Doa</label>
                            <textarea x-model="message" rows="3" placeholder="Tuliskan ucapan & doa..." class="input-field"></textarea>
                        </div>
                        
                        <div style="margin-bottom: 15px;">
                            <label style="display: block; margin-bottom: 8px; font-weight: bold; color: var(--sage-dark);">Konfirmasi Kehadiran</label>
                            <div class="flex gap-2">
                                <button type="button" @click="status = 'confirmed'"
                                    :class="status === 'confirmed' ? 'shadow-md' : 'shadow-sm'"
                                    :style="status === 'confirmed' ? 'background: var(--sage-medium); color: white; border-color: var(--sage-medium);' : 'background: white; color: var(--sage-dark); border-color: #ddd;'"
                                    class="flex-1 p-2 sm:p-3 border-2 rounded-xl cursor-pointer flex items-center justify-center gap-1 sm:gap-2 transition-all">
                                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="flex-shrink-0"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                    <span class="font-semibold text-xs sm:text-sm whitespace-nowrap">Akan Hadir</span>
                                </button>
                                <button type="button" @click="status = 'declined'"
                                    :class="status === 'declined' ? 'shadow-md' : 'shadow-sm'"
                                    :style="status === 'declined' ? 'background: #dc3545; color: white; border-color: #dc3545;' : 'background: white; color: var(--sage-dark); border-color: #ddd;'"
                                    class="flex-1 p-2 sm:p-3 border-2 rounded-xl cursor-pointer flex items-center justify-center gap-1 sm:gap-2 transition-all">
                                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="flex-shrink-0"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                    <span class="font-semibold text-xs sm:text-sm whitespace-nowrap">Tidak Hadir</span>
                                </button>
                            </div>
                        </div>

                        <div style="margin-bottom: 15px;" x-show="status === 'confirmed'" x-transition>
                            <label style="display: block; margin-bottom: 5px; font-weight: bold; color: var(--sage-dark);">Jumlah Tamu</label>
                            <select x-model="pax" class="input-field">
                                <option value="1">1 Orang</option>
                                <option value="2">2 Orang</option>
                                <option value="3">3 Orang</option>
                                <option value="4">4 Orang</option>
                                <option value="5">5 Orang</option>
                            </select>
                        </div>

                        <button type="submit" :disabled="loading" class="btn" style="width: 100%;">
                            <span x-show="!loading">Kirim Konfirmasi</span>
                            <span x-show="loading">Mengirim...</span>
                        </button>
                    </form>
                </div>

                {{-- Wishes List --}}
                <div style="margin-top: 30px;">
                    <div style="text-align: center; margin-bottom: 20px;">
                        <h3 style="font-family: 'Dancing Script', cursive; font-size: 1.8rem; color: var(--sage-medium);">Doa Restu</h3>
                    </div>
                
                    <div style="max-height: 400px; overflow-y: auto; padding-right: 5px;">
                        <template x-for="wish in wishes" :key="wish.id">
                            <div style="background: white; border-radius: 10px; padding: 15px; margin-bottom: 10px; border-left: 3px solid var(--sage-medium); box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                                    <div style="font-weight: 700; color: var(--sage-dark);" x-text="wish.name"></div>
                                    <span style="font-size: 0.7rem; color: #aaa;" x-text="wish.time"></span>
                                </div>
                                <p style="font-size: 0.9rem; color: #666; font-style: italic; margin-bottom: 8px;" x-text="`- ` + wish.message + ` -`"></p>
                                <template x-if="wish.attendance_status">
                                    <div class="mt-2 flex flex-wrap gap-2">
                                        <template x-if="wish.attendance_status === 'confirmed'">
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold" style="background-color: #DEF7EC; color: #03543F;">
                                                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="flex-shrink-0"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                <span class="whitespace-nowrap">Akan Hadir</span>
                                            </span>
                                        </template>
                                        <template x-if="wish.attendance_status === 'declined'">
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold" style="background-color: #FDE8E8; color: #9B1C1C;">
                                                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="flex-shrink-0"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                <span class="whitespace-nowrap">Tidak Hadir</span>
                                            </span>
                                        </template>
                                    </div>
                                </template>
                            </div>
                        </template>

                        <div x-show="wishes.length === 0" style="text-align: center; padding: 30px;">
                            <p style="color: #aaa; font-size: 0.9rem;">Belum ada ucapan</p>
                            <p style="color: #ccc; font-size: 0.8rem; margin-top: 5px;">Jadilah yang pertama memberikan ucapan!</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        @endif

        {{-- FOOTER --}}
        <footer style="text-align: center; padding: 50px 20px 100px; background: var(--sage-light); color: var(--sage-dark);">
            <h2 style="font-family: 'Dancing Script', cursive; font-size: 2rem;">{{ $invitation->groom_nickname }} & {{ $invitation->bride_nickname }}</h2>
            <p style="font-size: 0.8rem; margin-top: 10px;">Terima kasih atas doa restunya</p>
        </footer>

    </main>

    {{-- BOTTOM NAV --}}
    <nav x-show="opened" class="glass-nav">
        <a @click.prevent="scrollTo('home')" class="nav-icon" :class="{'active': activeSection === 'home'}">
            <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16"><path d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L2 8.207V13.5A1.5 1.5 0 0 0 3.5 15h9a1.5 1.5 0 0 0 1.5-1.5V8.207l.646.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.707 1.5Z"/></svg>
        </a>
        <a @click.prevent="scrollTo('couple')" class="nav-icon" :class="{'active': activeSection === 'couple'}">
            <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314z"/></svg>
        </a>
        <a @click.prevent="scrollTo('events')" class="nav-icon" :class="{'active': activeSection === 'events'}">
            <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16"><path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/></svg>
        </a>
        <a @click.prevent="scrollTo('rsvp')" class="nav-icon" :class="{'active': activeSection === 'rsvp'}">
            <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16"><path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4Zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1H2Zm13 2.383-4.708 2.825L15 11.105V5.383Zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741ZM1 11.105l4.708-2.897L1 5.383v5.722Z"/></svg>
        </a>
    </nav>
</div>
