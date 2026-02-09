@section('title', 'The Wedding of ' . $invitation->groom_nickname . ' & ' . $invitation->bride_nickname)

@push('fonts')
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,400&family=Dancing+Script:wght@400;500;600;700&family=Lora:wght@400;500;600&display=swap" rel="stylesheet">
@endpush

@push('styles')
<style>
:root {
    --gold: #6B8E6B;
    --gold-light: #A8C5A8;
    --gold-dark: #4A6741;
    --cream: #F5F7F2;
    --dark: #2D3B2D;
    --text: #4A5548;
    --accent: #C4A35A;
    --blush: #E8DDD4;
}

* { margin: 0; padding: 0; box-sizing: border-box; }

html {
    scroll-behavior: smooth;
    -webkit-tap-highlight-color: transparent;
}

body { 
    font-family: 'Lora', serif; 
    background: var(--cream); 
    color: var(--text); 
    overflow-x: hidden;
    -webkit-font-smoothing: antialiased;
}

.font-serif { font-family: 'Cormorant Garamond', serif; }
.font-script { font-family: 'Dancing Script', cursive; }

/* === ADVANCED ANIMATIONS === */
@keyframes fadeUp { 
    from { opacity: 0; transform: translateY(40px); } 
    to { opacity: 1; transform: translateY(0); } 
}
@keyframes fadeInScale {
    from { opacity: 0; transform: scale(0.9); }
    to { opacity: 1; transform: scale(1); }
}
@keyframes pulse { 
    0%, 100% { transform: scale(1); } 
    50% { transform: scale(1.05); } 
}
@keyframes float { 
    0%, 100% { transform: translateY(0) rotate(0deg); } 
    50% { transform: translateY(-15px) rotate(3deg); } 
}
@keyframes floatReverse { 
    0%, 100% { transform: translateY(0) rotate(0deg); } 
    50% { transform: translateY(-12px) rotate(-3deg); } 
}
@keyframes shimmer {
    0% { background-position: -200% 0; }
    100% { background-position: 200% 0; }
}
@keyframes leafFall {
    0% { transform: translateY(-10vh) rotate(0deg) translateX(0); opacity: 0.9; }
    20% { transform: translateY(18vh) rotate(72deg) translateX(25px); }
    40% { transform: translateY(36vh) rotate(144deg) translateX(-15px); }
    60% { transform: translateY(54vh) rotate(216deg) translateX(30px); }
    80% { transform: translateY(72vh) rotate(288deg) translateX(-20px); }
    100% { transform: translateY(110vh) rotate(360deg) translateX(10px); opacity: 0.2; }
}
@keyframes gentleSway {
    0%, 100% { transform: rotate(-8deg) scale(1); }
    50% { transform: rotate(8deg) scale(1.02); }
}
@keyframes breathe {
    0%, 100% { transform: scale(1); opacity: 0.6; }
    50% { transform: scale(1.1); opacity: 0.8; }
}
@keyframes slideInLeft {
    from { opacity: 0; transform: translateX(-60px); }
    to { opacity: 1; transform: translateX(0); }
}
@keyframes slideInRight {
    from { opacity: 0; transform: translateX(60px); }
    to { opacity: 1; transform: translateX(0); }
}
@keyframes ripple {
    0% { transform: scale(0.8); opacity: 1; }
    100% { transform: scale(2.5); opacity: 0; }
}
@keyframes sparkle {
    0%, 100% { opacity: 0; transform: scale(0); }
    50% { opacity: 1; transform: scale(1); }
}
@keyframes rotateGlow {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.animate-fade-up { animation: fadeUp 0.8s ease forwards; }
.animate-fade-scale { animation: fadeInScale 0.6s ease forwards; }
.animate-float { animation: float 4s ease-in-out infinite; }
.animate-float-reverse { animation: floatReverse 5s ease-in-out infinite; }
.animate-sway { animation: gentleSway 6s ease-in-out infinite; }
.animate-breathe { animation: breathe 4s ease-in-out infinite; }
.animate-slide-left { animation: slideInLeft 0.8s ease forwards; }
.animate-slide-right { animation: slideInRight 0.8s ease forwards; }

/* === FALLING LEAVES === */
.leaves-container {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    pointer-events: none;
    z-index: 99;
    overflow: hidden;
}
.leaf {
    position: absolute;
    opacity: 0.7;
    animation: leafFall linear infinite;
}
.leaf svg {
    width: 20px;
    height: 20px;
    fill: var(--gold);
}
.leaf:nth-child(odd) svg { fill: var(--gold-light); width: 16px; height: 16px; }
.leaf:nth-child(3n) svg { fill: var(--accent); width: 24px; height: 24px; }
.leaf:nth-child(1) { left: 8%; animation-duration: 14s; animation-delay: 0s; }
.leaf:nth-child(2) { left: 18%; animation-duration: 18s; animation-delay: 3s; }
.leaf:nth-child(3) { left: 28%; animation-duration: 12s; animation-delay: 5s; }
.leaf:nth-child(4) { left: 42%; animation-duration: 16s; animation-delay: 2s; }
.leaf:nth-child(5) { left: 55%; animation-duration: 15s; animation-delay: 4s; }
.leaf:nth-child(6) { left: 68%; animation-duration: 17s; animation-delay: 1s; }
.leaf:nth-child(7) { left: 78%; animation-duration: 13s; animation-delay: 6s; }
.leaf:nth-child(8) { left: 88%; animation-duration: 16s; animation-delay: 2.5s; }
.leaf:nth-child(9) { left: 35%; animation-duration: 14s; animation-delay: 7s; }
.leaf:nth-child(10) { left: 92%; animation-duration: 15s; animation-delay: 4.5s; }

/* === DECORATIVE BACKGROUNDS === */
.pattern-botanical {
    position: relative;
    overflow: hidden;
}
.pattern-botanical::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-image: 
        radial-gradient(circle at 20% 30%, rgba(107,142,107,0.08) 0%, transparent 50%),
        radial-gradient(circle at 80% 70%, rgba(107,142,107,0.06) 0%, transparent 40%),
        radial-gradient(circle at 50% 50%, rgba(196,163,90,0.04) 0%, transparent 60%);
    pointer-events: none;
}
.pattern-dots {
    background-image: radial-gradient(circle, rgba(107,142,107,0.15) 1px, transparent 1px);
    background-size: 24px 24px;
}
.pattern-leaves {
    background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M30 5c-5 8-3 18 5 23s20 3 25-5c-8 5-18 3-23-5s-3-20 5-25c-8 5-12 12-12 12z' fill='rgba(107,142,107,0.04)'/%3E%3C/svg%3E");
    background-size: 60px 60px;
}

/* === DECORATIVE DIVIDERS === */
.section-divider {
    position: relative;
    height: 80px;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}
.section-divider::before,
.section-divider::after {
    content: '';
    flex: 1;
    height: 1px;
    background: linear-gradient(90deg, transparent, var(--gold), transparent);
}
.section-divider-icon {
    width: 40px;
    height: 40px;
    margin: 0 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--gold);
    animation: gentleSway 4s ease-in-out infinite;
}
.section-divider-icon svg {
    width: 28px;
    height: 28px;
}

/* === FLOATING DECORATIONS === */
.floating-decor {
    position: absolute;
    pointer-events: none;
    opacity: 0.15;
}
.floating-decor.top-left {
    top: 20px;
    left: 20px;
    animation: float 6s ease-in-out infinite;
}
.floating-decor.top-right {
    top: 20px;
    right: 20px;
    animation: floatReverse 7s ease-in-out infinite;
}
.floating-decor.bottom-left {
    bottom: 20px;
    left: 20px;
    animation: floatReverse 5s ease-in-out infinite;
}
.floating-decor.bottom-right {
    bottom: 20px;
    right: 20px;
    animation: float 6.5s ease-in-out infinite;
}
.floating-decor svg {
    width: 60px;
    height: 60px;
    fill: var(--gold);
}

/* === GLOWING ACCENTS === */
.glow-accent {
    position: absolute;
    width: 200px;
    height: 200px;
    border-radius: 50%;
    filter: blur(80px);
    opacity: 0.3;
    animation: breathe 6s ease-in-out infinite;
}
.glow-accent.green { background: var(--gold); }
.glow-accent.gold { background: var(--accent); }

/* === COVER SCREEN === */
.cover { 
    position: fixed; 
    inset: 0; 
    z-index: 100; 
    display: flex; 
    align-items: center; 
    justify-content: center;
    padding: 24px;
}
.cover-bg { 
    position: absolute; 
    inset: 0; 
    background-size: cover; 
    background-position: center;
}
.cover-overlay { 
    position: absolute; 
    inset: 0; 
    background: linear-gradient(180deg, rgba(0,0,0,0.5) 0%, rgba(0,0,0,0.75) 100%);
}

/* === SECTIONS === */
.section { 
    padding: 56px 16px; 
    position: relative; 
}
.section-title { 
    text-align: center; 
    margin-bottom: 36px; 
}
.section-title h2 { 
    font-family: 'Cormorant Garamond', serif; 
    font-size: 1.875rem; 
    color: var(--dark); 
    font-weight: 600;
    line-height: 1.2;
}
.section-title .divider { 
    width: 60px; 
    height: 2px; 
    background: linear-gradient(90deg, transparent, var(--gold), transparent); 
    margin: 12px auto; 
}

/* === CARDS === */
.card { 
    background: white; 
    border-radius: 16px; 
    box-shadow: 0 4px 20px rgba(0,0,0,0.06); 
    overflow: hidden; 
}

/* === BUTTONS === */
.btn { 
    display: inline-flex; 
    align-items: center; 
    justify-content: center;
    gap: 8px; 
    padding: 14px 28px; 
    border-radius: 50px; 
    font-weight: 500; 
    font-size: 14px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); 
    cursor: pointer; 
    border: none; 
    text-decoration: none;
    min-height: 48px; /* Touch friendly */
}
.btn-gold { 
    background: linear-gradient(135deg, var(--gold), var(--gold-dark)); 
    color: white; 
    box-shadow: 0 4px 15px rgba(201,162,39,0.3);
}
.btn-gold:active { 
    transform: scale(0.98); 
}
.btn-outline { 
    background: transparent; 
    border: 2px solid var(--gold); 
    color: var(--gold);
    min-height: 44px;
    padding: 10px 20px;
}
.btn-outline:active { 
    background: var(--gold); 
    color: white; 
}

/* === COUNTDOWN === */
.countdown-grid { 
    display: grid; 
    grid-template-columns: repeat(4, 1fr); 
    gap: 8px; 
    max-width: 100%; 
    margin: 0 auto;
    padding: 0 8px;
}
.countdown-item { 
    background: white; 
    padding: 16px 8px; 
    border-radius: 12px; 
    text-align: center; 
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
}
.countdown-num { 
    font-family: 'Cormorant Garamond', serif; 
    font-size: 1.75rem; 
    font-weight: 700; 
    color: var(--gold);
    line-height: 1;
}
.countdown-label { 
    font-size: 0.625rem; 
    color: var(--text); 
    text-transform: uppercase; 
    letter-spacing: 0.5px; 
    margin-top: 4px;
}

/* === EVENT CARDS === */
.event-card { 
    background: white; 
    border-radius: 16px; 
    padding: 24px; 
    margin-bottom: 16px; 
    box-shadow: 0 4px 20px rgba(0,0,0,0.06); 
    border-left: 4px solid var(--gold);
}
.event-card.secondary {
    border-left-color: var(--gold-dark);
}
.event-card-inner {
    display: flex;
    align-items: flex-start;
    gap: 16px;
}
.event-icon {
    flex-shrink: 0;
    width: 52px;
    height: 52px;
    background: var(--gold);
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
}
.event-icon.secondary {
    background: var(--gold-dark);
}
.event-icon svg {
    width: 26px;
    height: 26px;
}
.event-content {
    flex: 1;
    min-width: 0;
}
.event-title {
    font-family: 'Cormorant Garamond', serif;
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--dark);
    margin-bottom: 4px;
}
.event-date {
    color: var(--gold);
    font-weight: 600;
    font-size: 14px;
    margin-bottom: 12px;
}
.event-date.secondary {
    color: var(--gold-dark);
}
.event-info {
    display: flex;
    flex-direction: column;
    gap: 8px;
}
.event-info-item {
    display: flex;
    align-items: flex-start;
    gap: 8px;
    color: #666;
    font-size: 13px;
    line-height: 1.5;
}
.event-info-item svg {
    width: 16px;
    height: 16px;
    margin-top: 2px;
    flex-shrink: 0;
    color: var(--gold);
}
.event-link {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    margin-top: 16px;
    padding: 10px 18px;
    border: 2px solid var(--gold);
    border-radius: 50px;
    color: var(--gold);
    font-size: 12px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
}
.event-link:active {
    background: var(--gold);
    color: white;
}
.event-link svg {
    width: 16px;
    height: 16px;
}

/* === COUPLE SECTION === */
.couple-card { 
    text-align: center; 
    padding: 24px 16px;
}
.couple-photo { 
    width: 140px; 
    height: 140px; 
    border-radius: 50%; 
    object-fit: cover; 
    border: 4px solid var(--gold-light); 
    margin: 0 auto 16px; 
    box-shadow: 0 8px 25px rgba(0,0,0,0.12);
}
.couple-name { 
    font-family: 'Cormorant Garamond', serif; 
    font-size: 1.5rem; 
    color: var(--dark); 
    margin-bottom: 4px;
    line-height: 1.2;
}

/* === GALLERY === */
.gallery-grid { 
    display: grid; 
    grid-template-columns: repeat(2, 1fr); 
    gap: 8px;
}
.gallery-item { 
    aspect-ratio: 1; 
    overflow: hidden; 
    border-radius: 12px; 
    cursor: pointer;
}
.gallery-item img { 
    width: 100%; 
    height: 100%; 
    object-fit: cover; 
    transition: transform 0.5s;
}
.gallery-item:active img { 
    transform: scale(1.05); 
}

/* === GIFT SECTION === */
.gift-card { 
    background: white; 
    border-radius: 16px; 
    padding: 16px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.06);
    border: 1px solid rgba(201,162,39,0.15);
}
.gift-card-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 12px;
}
.gift-card-icon {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, var(--gold), var(--gold-dark));
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    box-shadow: 0 4px 12px rgba(201,162,39,0.25);
}
.gift-card-icon svg {
    width: 20px;
    height: 20px;
    color: white;
}
.gift-card-info {
    flex: 1;
    min-width: 0;
}
.gift-card-bank {
    font-family: 'Cormorant Garamond', serif;
    font-weight: 700;
    color: var(--gold-dark);
    font-size: 0.9rem;
    margin-bottom: 2px;
}
.gift-card-number {
    font-family: 'Courier New', monospace;
    font-size: 1rem;
    color: var(--dark);
    font-weight: 600;
    letter-spacing: 1px;
}
.gift-card-name {
    font-size: 0.75rem;
    color: var(--text);
    margin-top: 2px;
}
.copy-btn { 
    width: 100%;
    background: linear-gradient(135deg, var(--gold), var(--gold-dark)); 
    border: none; 
    color: white; 
    padding: 10px 16px; 
    border-radius: 8px; 
    cursor: pointer; 
    font-size: 12px;
    font-weight: 600;
    min-height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    transition: all 0.25s ease;
    box-shadow: 0 4px 12px rgba(201,162,39,0.2);
}
.copy-btn:active { 
    transform: scale(0.98);
}
.copy-btn.copied {
    background: #10B981;
    box-shadow: 0 4px 12px rgba(16,185,129,0.25);
}
.copy-btn svg {
    width: 14px;
    height: 14px;
}

/* === BOTTOM NAVIGATION === */
:root {
    --bottom-nav-height: 70px;
}
.bottom-nav { 
    position: fixed; 
    bottom: 0; 
    left: 0; 
    right: 0; 
    background: white; 
    box-shadow: 0 -4px 20px rgba(0,0,0,0.08); 
    z-index: 90; 
    padding: 8px 0;
    padding-bottom: max(8px, env(safe-area-inset-bottom));
    height: var(--bottom-nav-height);
}
.theme-footer {
    padding-top: 48px;
    padding-bottom: calc(var(--bottom-nav-height) + 30px + env(safe-area-inset-bottom));
    background: linear-gradient(135deg, #1A1A1A, #2D2D2D);
    text-align: center;
    color: white;
}
.nav-items { 
    display: flex; 
    justify-content: space-around; 
    max-width: 100%; 
    margin: 0 auto;
}
.nav-item { 
    display: flex; 
    flex-direction: column; 
    align-items: center; 
    gap: 2px; 
    color: var(--text); 
    text-decoration: none; 
    font-size: 9px; 
    padding: 8px 12px; 
    transition: color 0.2s;
    min-width: 56px;
    -webkit-tap-highlight-color: transparent;
}
.nav-item.active, .nav-item:active { 
    color: var(--gold); 
}
.nav-item svg { 
    width: 20px; 
    height: 20px; 
}

/* === FLOATING MUSIC BUTTON === */
.music-btn { 
    position: fixed; 
    bottom: calc(70px + env(safe-area-inset-bottom)); 
    right: 16px; 
    width: 48px; 
    height: 48px; 
    border-radius: 50%; 
    background: var(--gold); 
    color: white; 
    display: flex; 
    align-items: center; 
    justify-content: center; 
    cursor: pointer; 
    z-index: 95; 
    box-shadow: 0 4px 15px rgba(201,162,39,0.4); 
    transition: all 0.3s; 
    border: none;
}
.music-btn:active { 
    transform: scale(0.95); 
}
.music-btn.playing { 
    animation: pulse 1.5s infinite; 
}

/* === FORM ELEMENTS === */
.form-input { 
    width: 100%; 
    padding: 14px 16px; 
    border: 2px solid #E5E5E5; 
    border-radius: 12px; 
    font-size: 16px; /* Prevents zoom on iOS */
    transition: all 0.3s; 
    background: white; 
    font-family: inherit;
    min-height: 48px;
}
.form-input:focus { 
    outline: none; 
    border-color: var(--gold); 
    box-shadow: 0 0 0 3px rgba(201,162,39,0.15); 
}
.form-select { 
    appearance: none; 
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%23999'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E"); 
    background-repeat: no-repeat; 
    background-position: right 12px center; 
    background-size: 20px;
    padding-right: 40px;
}

/* === WISH/RSVP SECTION === */
.wish-item { 
    background: #FAFAFA; 
    border-radius: 12px; 
    padding: 16px; 
    margin-bottom: 12px; 
    border: 1px solid #f0f0f0;
}
.wish-avatar { 
    width: 40px; 
    height: 40px; 
    border-radius: 50%; 
    background: linear-gradient(135deg, var(--gold), var(--gold-dark)); 
    color: white; 
    display: flex; 
    align-items: center; 
    justify-content: center; 
    font-weight: 600; 
    flex-shrink: 0;
    font-size: 14px;
}

/* === HERO SECTION === */
.hero-section {
    position: relative;
    min-height: 100vh;
    min-height: 100svh;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    color: white;
    padding: 40px 20px;
}
.hero-bg {
    position: absolute;
    inset: 0;
    background: linear-gradient(180deg, #1A1A1A 0%, #2D2D2D 100%);
    z-index: -1;
}
.hero-content {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 24px;
}
.hero-photo {
    width: 180px;
    height: 180px;
    border-radius: 50%;
    overflow: hidden;
    border: 4px solid white;
    box-shadow: 0 8px 30px rgba(0,0,0,0.3);
}
.hero-photo img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.hero-names-text {
    font-family: 'Great Vibes', cursive;
    font-size: clamp(2.5rem, 10vw, 4rem);
    color: var(--gold);
    margin: 16px 0;
    line-height: 1.2;
}
.hero-countdown {
    display: flex;
    gap: 8px;
    margin-top: 16px;
}
.hero-countdown-item {
    background: var(--gold);
    border-radius: 8px;
    padding: 12px 14px;
    min-width: 65px;
    text-align: center;
}
.hero-countdown-num {
    display: block;
    font-family: 'Cormorant Garamond', serif;
    font-size: 1.75rem;
    font-weight: 700;
    color: white;
    line-height: 1;
}
.hero-countdown-label {
    display: block;
    font-size: 8px;
    letter-spacing: 1px;
    color: rgba(255,255,255,0.85);
    margin-top: 4px;
}
.hero-date-display {
    margin-top: 16px;
    text-align: center;
}
.hero-day {
    font-family: 'Cormorant Garamond', serif;
    font-size: 1.25rem;
    color: var(--gold);
    margin-bottom: 4px;
}
.hero-full-date {
    font-family: 'Cormorant Garamond', serif;
    font-size: 1.75rem;
    font-weight: 600;
    color: white;
}
.hero-calendar-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    margin-top: 24px;
    padding: 12px 24px;
    background: transparent;
    border: 2px solid rgba(255,255,255,0.3);
    border-radius: 50px;
    color: white;
    font-size: 13px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
}
.hero-calendar-btn:hover,
.hero-calendar-btn:active {
    background: rgba(255,255,255,0.1);
    border-color: rgba(255,255,255,0.5);
}
.hero-calendar-btn svg {
    width: 18px;
    height: 18px;
}
.hero-scroll-indicator {
    position: absolute;
    bottom: 30px;
    left: 50%;
    transform: translateX(-50%);
    opacity: 0.5;
}
.hero-scroll-indicator svg {
    width: 24px;
    height: 24px;
    color: white;
}

/* === RSVP SECTION STYLES === */
.rsvp-section {
    padding: 56px 16px;
    background: linear-gradient(180deg, var(--cream) 0%, white 100%);
}
.rsvp-stats {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 12px;
    margin-bottom: 24px;
}
.rsvp-stat-card {
    background: white;
    border-radius: 16px;
    padding: 20px 16px;
    text-align: center;
    box-shadow: 0 4px 20px rgba(0,0,0,0.06);
    border: 1px solid rgba(201,162,39,0.1);
    position: relative;
    overflow: hidden;
}
.rsvp-stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, var(--gold), var(--gold-light));
}
.rsvp-stat-num {
    font-family: 'Cormorant Garamond', serif;
    font-size: 2.5rem;
    font-weight: 700;
    color: var(--gold);
    line-height: 1;
}
.rsvp-stat-label {
    font-size: 10px;
    text-transform: uppercase;
    letter-spacing: 2px;
    color: #999;
    margin-top: 4px;
}

.rsvp-form-card {
    background: white;
    border-radius: 20px;
    padding: 24px;
    box-shadow: 0 8px 40px rgba(0,0,0,0.1);
    margin-bottom: 24px;
}
.rsvp-form-group {
    margin-bottom: 20px;
}
.rsvp-label {
    display: block;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: #666;
    margin-bottom: 8px;
}
.rsvp-input {
    width: 100%;
    padding: 16px;
    background: #f8f8f8;
    border: 2px solid #eee;
    border-radius: 12px;
    font-size: 15px;
    font-family: inherit;
    transition: all 0.3s ease;
}
.rsvp-input:focus {
    outline: none;
    background: white;
    border-color: var(--gold);
    box-shadow: 0 0 0 4px rgba(201,162,39,0.1);
}
.rsvp-input::placeholder {
    color: #aaa;
}
.rsvp-textarea {
    resize: none;
    min-height: 100px;
}
.rsvp-select {
    appearance: none;
    cursor: pointer;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%23999'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 16px center;
    background-size: 18px;
    padding-right: 48px;
}

.rsvp-toggle-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 12px;
}
.rsvp-toggle-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 16px;
    border-radius: 12px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    border: 2px solid #eee;
    background: #f8f8f8;
    color: #666;
}
.rsvp-toggle-btn.active {
    background: linear-gradient(135deg, var(--gold), var(--gold-dark));
    border-color: transparent;
    color: white;
    box-shadow: 0 4px 15px rgba(201,162,39,0.3);
}
.rsvp-toggle-btn.active.declined {
    background: #555;
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}
.rsvp-toggle-btn svg {
    width: 18px;
    height: 18px;
}

.rsvp-submit-btn {
    width: 100%;
    padding: 18px;
    background: linear-gradient(135deg, var(--gold), var(--gold-dark));
    color: white;
    border: none;
    border-radius: 12px;
    font-size: 14px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(201,162,39,0.3);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}
.rsvp-submit-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(201,162,39,0.4);
}
.rsvp-submit-btn:active {
    transform: translateY(0);
}
.rsvp-submit-btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none;
}
.rsvp-submit-btn svg {
    width: 18px;
    height: 18px;
}

.rsvp-wishes-list {
    margin-top: 8px;
}
.rsvp-wishes-title {
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 2px;
    color: #888;
    margin-bottom: 16px;
}
.rsvp-wish-card {
    background: white;
    border-radius: 16px;
    padding: 16px;
    margin-bottom: 12px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.04);
    border: 1px solid #f0f0f0;
}
.rsvp-wish-card:hover {
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
}
.rsvp-wish-avatar {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--gold), var(--gold-dark));
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: 'Cormorant Garamond', serif;
    font-weight: 700;
    font-size: 18px;
    flex-shrink: 0;
}
.rsvp-wish-name {
    font-weight: 600;
    color: var(--dark);
    font-size: 14px;
}
.rsvp-wish-time {
    font-size: 10px;
    color: #aaa;
    letter-spacing: 0.5px;
}
.rsvp-wish-message {
    color: #666;
    font-size: 13px;
    line-height: 1.6;
    margin-top: 4px;
}
.rsvp-attendance-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 3px 10px;
    border-radius: 20px;
    font-size: 10px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-top: 8px;
}
.rsvp-attendance-badge.confirmed {
    background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(5, 150, 105, 0.15));
    color: #059669;
    border: 1px solid rgba(16, 185, 129, 0.3);
}
.rsvp-attendance-badge.confirmed svg {
    color: #10B981;
}
.rsvp-attendance-badge.declined {
    background: linear-gradient(135deg, rgba(107, 114, 128, 0.1), rgba(75, 85, 99, 0.15));
    color: #4B5563;
    border: 1px solid rgba(107, 114, 128, 0.3);
}
.rsvp-attendance-badge.declined svg {
    color: #6B7280;
}
.rsvp-attendance-badge svg {
    width: 12px;
    height: 12px;
}

.rsvp-empty {
    background: rgba(255,255,255,0.5);
    border: 2px dashed #ddd;
    border-radius: 16px;
    padding: 40px 20px;
    text-align: center;
}
.rsvp-empty-icon {
    width: 56px;
    height: 56px;
    background: #f5f5f5;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 16px;
}
.rsvp-empty-icon svg {
    width: 28px;
    height: 28px;
    color: #bbb;
}
.rsvp-empty-text {
    color: #888;
    font-size: 14px;
}
.rsvp-empty-subtext {
    color: #aaa;
    font-size: 12px;
    margin-top: 4px;
}

.rsvp-alert {
    padding: 16px;
    border-radius: 12px;
    margin-bottom: 20px;
    display: flex;
    align-items: flex-start;
    gap: 12px;
}
.rsvp-alert-success {
    background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
    border: 1px solid #86efac;
}
.rsvp-alert-error {
    background: #fef2f2;
    border: 1px solid #fecaca;
}
.rsvp-alert-icon {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.rsvp-alert-success .rsvp-alert-icon {
    background: #22c55e;
    color: white;
}
.rsvp-alert-error .rsvp-alert-icon {
    background: #ef4444;
    color: white;
}
.rsvp-alert-title {
    font-weight: 600;
    font-size: 14px;
}
.rsvp-alert-success .rsvp-alert-title {
    color: #166534;
}
.rsvp-alert-error .rsvp-alert-title {
    color: #991b1b;
}
.rsvp-alert-text {
    font-size: 12px;
    margin-top: 2px;
}
.rsvp-alert-success .rsvp-alert-text {
    color: #15803d;
}
.rsvp-alert-error .rsvp-alert-text {
    color: #b91c1c;
}
.text-gold { color: var(--gold); }
.bg-gold { background: var(--gold); }
.safe-bottom { padding-bottom: env(safe-area-inset-bottom); }

/* === LOADING SKELETON === */
.skeleton {
    background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
    background-size: 200% 100%;
    animation: shimmer 1.5s infinite;
}

/* === HERO MOBILE OPTIMIZATIONS === */
.hero-content {
    padding: 0 20px;
}
.hero-names {
    font-size: clamp(3rem, 12vw, 5rem);
    line-height: 1.1;
}
.hero-date {
    font-size: 1rem;
}

/* === TABLET+ === */
@media (min-width: 640px) {
    .section { 
        padding: 80px 24px; 
    }
    .section-title h2 { 
        font-size: 2.5rem; 
    }
    .countdown-grid {
        max-width: 450px;
        gap: 12px;
    }
    .countdown-num { 
        font-size: 2.5rem; 
    }
    .countdown-label {
        font-size: 0.75rem;
    }
    .couple-photo { 
        width: 180px; 
        height: 180px; 
    }
    .couple-name {
        font-size: 2rem;
    }
    .gallery-grid { 
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
    }
    .event-card {
        padding: 30px;
    }
    .event-icon {
        width: 64px;
        height: 64px;
    }
    .event-icon svg {
        width: 32px;
        height: 32px;
    }
    .hero-names {
        font-size: 5rem;
    }
    .nav-item {
        font-size: 10px;
    }
    .nav-item svg {
        width: 22px;
        height: 22px;
    }
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
        
        // Setup scroll spy after opening
        this.$watch('opened', (value) => {
            if (value) {
                this.$nextTick(() => this.setupScrollSpy());
            }
        });
    },
    setupScrollSpy() {
        const sections = ['home', 'couple', 'events', 'gift', 'rsvp'];
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    this.activeSection = entry.target.id;
                }
            });
        }, { threshold: 0.3, rootMargin: '-20% 0px -60% 0px' });
        
        sections.forEach(id => {
            const el = document.getElementById(id);
            if (el) observer.observe(el);
        });
    },
    open() {
        this.opened = true;
        document.body.style.overflow = 'auto';
        
        // Get audio element and play
        this.$nextTick(() => {
            this.audioEl = document.getElementById('bgMusic');
            if (this.audioEl) {
                this.audioEl.volume = 0.7;
                this.audioEl.play()
                    .then(() => { this.playing = true; })
                    .catch(e => { console.log('Play failed:', e); });
            }
        });
    },
    toggleAudio() {
        if (!this.audioEl) {
            this.audioEl = document.getElementById('bgMusic');
        }
        if (!this.audioEl) return;
        
        if (this.playing) { 
            this.audioEl.pause(); 
            this.playing = false;
        } else { 
            this.audioEl.play()
                .then(() => { this.playing = true; })
                .catch(() => { this.playing = false; });
        }
    },
    scrollTo(id) {
        this.activeSection = id;
        document.getElementById(id)?.scrollIntoView({ behavior: 'smooth', block: 'start' });
    },
    copyText(text) {
        navigator.clipboard.writeText(text);
        alert('Nomor rekening disalin!');
    }
}">
    @if($invitation->background_music)
    @php
        $musicSrc = str_starts_with($invitation->background_music, 'http') 
            ? $invitation->background_music 
            : asset('storage/' . $invitation->background_music);
    @endphp
    <audio id="bgMusic" loop preload="auto">
        <source src="{{ $musicSrc }}" type="audio/mpeg">
    </audio>
    @endif

    {{-- FALLING LEAVES ANIMATION --}}
    <div class="leaves-container" x-show="opened">
        <div class="leaf"><svg viewBox="0 0 24 24"><path d="M17,8C8,10 5.9,16.17 3.82,21.34L5.71,22L6.66,19.7C7.14,19.87 7.64,20 8,20C19,20 22,3 22,3C21,5 14,5.25 9,6.25C4,7.25 2,11.5 2,13.5C2,15.5 3.75,17.25 3.75,17.25C7,8 17,8 17,8Z"/></svg></div>
        <div class="leaf"><svg viewBox="0 0 24 24"><path d="M17,8C8,10 5.9,16.17 3.82,21.34L5.71,22L6.66,19.7C7.14,19.87 7.64,20 8,20C19,20 22,3 22,3C21,5 14,5.25 9,6.25C4,7.25 2,11.5 2,13.5C2,15.5 3.75,17.25 3.75,17.25C7,8 17,8 17,8Z"/></svg></div>
        <div class="leaf"><svg viewBox="0 0 24 24"><path d="M17,8C8,10 5.9,16.17 3.82,21.34L5.71,22L6.66,19.7C7.14,19.87 7.64,20 8,20C19,20 22,3 22,3C21,5 14,5.25 9,6.25C4,7.25 2,11.5 2,13.5C2,15.5 3.75,17.25 3.75,17.25C7,8 17,8 17,8Z"/></svg></div>
        <div class="leaf"><svg viewBox="0 0 24 24"><path d="M17,8C8,10 5.9,16.17 3.82,21.34L5.71,22L6.66,19.7C7.14,19.87 7.64,20 8,20C19,20 22,3 22,3C21,5 14,5.25 9,6.25C4,7.25 2,11.5 2,13.5C2,15.5 3.75,17.25 3.75,17.25C7,8 17,8 17,8Z"/></svg></div>
        <div class="leaf"><svg viewBox="0 0 24 24"><path d="M17,8C8,10 5.9,16.17 3.82,21.34L5.71,22L6.66,19.7C7.14,19.87 7.64,20 8,20C19,20 22,3 22,3C21,5 14,5.25 9,6.25C4,7.25 2,11.5 2,13.5C2,15.5 3.75,17.25 3.75,17.25C7,8 17,8 17,8Z"/></svg></div>
        <div class="leaf"><svg viewBox="0 0 24 24"><path d="M17,8C8,10 5.9,16.17 3.82,21.34L5.71,22L6.66,19.7C7.14,19.87 7.64,20 8,20C19,20 22,3 22,3C21,5 14,5.25 9,6.25C4,7.25 2,11.5 2,13.5C2,15.5 3.75,17.25 3.75,17.25C7,8 17,8 17,8Z"/></svg></div>
        <div class="leaf"><svg viewBox="0 0 24 24"><path d="M17,8C8,10 5.9,16.17 3.82,21.34L5.71,22L6.66,19.7C7.14,19.87 7.64,20 8,20C19,20 22,3 22,3C21,5 14,5.25 9,6.25C4,7.25 2,11.5 2,13.5C2,15.5 3.75,17.25 3.75,17.25C7,8 17,8 17,8Z"/></svg></div>
        <div class="leaf"><svg viewBox="0 0 24 24"><path d="M17,8C8,10 5.9,16.17 3.82,21.34L5.71,22L6.66,19.7C7.14,19.87 7.64,20 8,20C19,20 22,3 22,3C21,5 14,5.25 9,6.25C4,7.25 2,11.5 2,13.5C2,15.5 3.75,17.25 3.75,17.25C7,8 17,8 17,8Z"/></svg></div>
        <div class="leaf"><svg viewBox="0 0 24 24"><path d="M17,8C8,10 5.9,16.17 3.82,21.34L5.71,22L6.66,19.7C7.14,19.87 7.64,20 8,20C19,20 22,3 22,3C21,5 14,5.25 9,6.25C4,7.25 2,11.5 2,13.5C2,15.5 3.75,17.25 3.75,17.25C7,8 17,8 17,8Z"/></svg></div>
        <div class="leaf"><svg viewBox="0 0 24 24"><path d="M17,8C8,10 5.9,16.17 3.82,21.34L5.71,22L6.66,19.7C7.14,19.87 7.64,20 8,20C19,20 22,3 22,3C21,5 14,5.25 9,6.25C4,7.25 2,11.5 2,13.5C2,15.5 3.75,17.25 3.75,17.25C7,8 17,8 17,8Z"/></svg></div>
    </div>

    {{-- COVER --}}
    <div x-show="!opened" x-transition:leave="transition duration-700" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="cover">
        <div class="cover-bg" style="background-image: url('{{ $invitation->cover_image ? asset('storage/' . $invitation->cover_image) : 'https://images.unsplash.com/photo-1519741497674-611481863552?w=1200' }}');"></div>
        <div class="cover-overlay"></div>
        
        <div class="relative z-10 text-center text-white w-full max-w-sm px-6">
            {{-- Label --}}
            <p style="font-size: 11px; letter-spacing: 4px; text-transform: uppercase; opacity: 0.9; margin-bottom: 24px;">THE WEDDING OF</p>
            
            {{-- Names --}}
            @php $order = $invitation->custom_styles['name_order'] ?? 'groom_first'; @endphp
            @if($order === 'bride_first')
                <h1 class="font-script hero-names" style="margin-bottom: 8px;">{{ $invitation->bride_nickname }}</h1>
                <p class="font-script" style="font-size: 2rem; color: #E8D5A3; margin: 16px 0;">&</p>
                <h1 class="font-script hero-names" style="margin-bottom: 40px;">{{ $invitation->groom_nickname }}</h1>
            @else
                <h1 class="font-script hero-names" style="margin-bottom: 8px;">{{ $invitation->groom_nickname }}</h1>
                <p class="font-script" style="font-size: 2rem; color: #E8D5A3; margin: 16px 0;">&</p>
                <h1 class="font-script hero-names" style="margin-bottom: 40px;">{{ $invitation->bride_nickname }}</h1>
            @endif
            
            {{-- Divider --}}
            <div style="width: 80px; height: 1px; background: linear-gradient(90deg, transparent, #C9A227, transparent); margin: 0 auto 32px;"></div>
            
            {{-- Guest Section --}}
            <p style="font-size: 12px; letter-spacing: 1px; opacity: 0.8; margin-bottom: 8px;">Kepada Yth.</p>
            <p class="font-serif" style="font-size: 1.5rem; font-weight: 500; margin-bottom: 20px;">{{ $guestName }}</p>
            
            {{-- Invitation Text --}}
            <p style="font-size: 13px; line-height: 1.7; opacity: 0.85; margin-bottom: 32px; padding: 0 8px;">
                Tanpa Mengurangi Rasa Hormat, Kami Mengundang Bapak/Ibu/Saudara/i untuk Hadir di Acara Kami.
            </p>
            
            {{-- Button --}}
            <button @click="open()" class="btn btn-gold animate-fade-up" style="width: 100%; max-width: 240px; padding: 16px 32px;">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 19v-8.93a2 2 0 01.89-1.664l7-4.666a2 2 0 012.22 0l7 4.666A2 2 0 0121 10.07V19M3 19a2 2 0 002 2h14a2 2 0 002-2M3 19l6.75-4.5M21 19l-6.75-4.5M3 10l6.75 4.5M21 10l-6.75 4.5m0 0l-1.14.76a2 2 0 01-2.22 0l-1.14-.76"/></svg>
                Buka Undangan
            </button>
        </div>
    </div>

    {{-- MAIN --}}
    <main x-show="opened" x-transition>
        
        {{-- HERO --}}
        <section id="home" class="hero-section" x-data="{
            days: 0, hours: 0, minutes: 0, seconds: 0,
            isActive: true,
            target: new Date('{{ $invitation->akad_date?->format('Y-m-d H:i:s') }}'),
            init() { 
                this.update(); 
                setInterval(() => this.update(), 1000); 
            },
            update() {
                const diff = this.target - new Date();
                if (diff > 0) {
                    this.isActive = true;
                    this.days = Math.floor(diff / 86400000);
                    this.hours = Math.floor((diff % 86400000) / 3600000);
                    this.minutes = Math.floor((diff % 3600000) / 60000);
                    this.seconds = Math.floor((diff % 60000) / 1000);
                } else {
                    this.isActive = false;
                }
            },
            saveToCalendar() {
                const title = '{{ ($invitation->custom_styles['name_order'] ?? 'groom_first') === 'bride_first' ? $invitation->bride_nickname . ' & ' . $invitation->groom_nickname : $invitation->groom_nickname . ' & ' . $invitation->bride_nickname }} Wedding';
                const date = '{{ $invitation->akad_date?->format('Ymd\THis') }}';
                const location = '{{ $invitation->akad_venue }}';
                const url = `https://calendar.google.com/calendar/render?action=TEMPLATE&text=${encodeURIComponent(title)}&dates=${date}/${date}&location=${encodeURIComponent(location)}`;
                window.open(url, '_blank');
            }
        }">
            {{-- Background --}}
            <div class="hero-bg"></div>
            
            {{-- Content --}}
            <div class="hero-content">
                {{-- Couple Photo --}}
                <div class="hero-photo">
                    <img src="{{ $invitation->cover_image ? asset('storage/' . $invitation->cover_image) : 'https://images.unsplash.com/photo-1519741497674-611481863552?w=600' }}" alt="Couple">
                </div>
                
                {{-- Names --}}
                <h1 class="hero-names-text">
                    @php $order = $invitation->custom_styles['name_order'] ?? 'groom_first'; @endphp
                    {{ $order === 'bride_first' ? $invitation->bride_nickname . ' & ' . $invitation->groom_nickname : $invitation->groom_nickname . ' & ' . $invitation->bride_nickname }}
                </h1>
                
                {{-- Countdown (if active) --}}
                <div x-show="isActive" class="hero-countdown">
                    <div class="hero-countdown-item">
                        <span class="hero-countdown-num" x-text="days">0</span>
                        <span class="hero-countdown-label">DAYS</span>
                    </div>
                    <div class="hero-countdown-item">
                        <span class="hero-countdown-num" x-text="hours">0</span>
                        <span class="hero-countdown-label">HOURS</span>
                    </div>
                    <div class="hero-countdown-item">
                        <span class="hero-countdown-num" x-text="minutes">0</span>
                        <span class="hero-countdown-label">MINUTES</span>
                    </div>
                    <div class="hero-countdown-item">
                        <span class="hero-countdown-num" x-text="seconds">0</span>
                        <span class="hero-countdown-label">SECONDS</span>
                    </div>
                </div>
                
                {{-- Date (if countdown ended) --}}
                <div x-show="!isActive" class="hero-date-display">
                    <p class="hero-day">{{ $invitation->akad_date?->translatedFormat('l') }}</p>
                    <p class="hero-full-date">{{ $invitation->akad_date?->translatedFormat('d F Y') }}</p>
                </div>
                
                {{-- Save To Calendar Button --}}
                <button @click="saveToCalendar()" class="hero-calendar-btn">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Save To Calendar
                </button>
            </div>
            
            {{-- Scroll Indicator --}}
            <div class="hero-scroll-indicator animate-float">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
            </div>
        </section>

        {{-- INTRO --}}
        <section class="section pattern-botanical" style="background: linear-gradient(180deg, white 0%, var(--cream) 100%);">
            {{-- Floating Decorations --}}
            <div class="floating-decor top-left">
                <svg viewBox="0 0 24 24"><path d="M17,8C8,10 5.9,16.17 3.82,21.34L5.71,22L6.66,19.7C7.14,19.87 7.64,20 8,20C19,20 22,3 22,3C21,5 14,5.25 9,6.25C4,7.25 2,11.5 2,13.5C2,15.5 3.75,17.25 3.75,17.25C7,8 17,8 17,8Z"/></svg>
            </div>
            <div class="floating-decor top-right">
                <svg viewBox="0 0 24 24"><path d="M17,8C8,10 5.9,16.17 3.82,21.34L5.71,22L6.66,19.7C7.14,19.87 7.64,20 8,20C19,20 22,3 22,3C21,5 14,5.25 9,6.25C4,7.25 2,11.5 2,13.5C2,15.5 3.75,17.25 3.75,17.25C7,8 17,8 17,8Z"/></svg>
            </div>
            {{-- Glowing Accents --}}
            <div class="glow-accent green" style="top: -50px; left: -100px;"></div>
            <div class="glow-accent gold" style="bottom: -50px; right: -100px;"></div>
            
            <div class="max-w-lg mx-auto text-center px-4 relative z-10">
                <p class="font-serif text-lg leading-relaxed mb-3" style="color: var(--dark);">بِسْمِ اللَّهِ الرَّحْمَنِ الرَّحِيمِ</p>
                <p class="text-xs mb-6" style="color: var(--text);">Assalamu'alaikum Warahmatullahi Wabarakatuh</p>
                <div style="width: 48px; height: 2px; background: linear-gradient(90deg, transparent, var(--gold), transparent); margin: 0 auto 24px;"></div>
                <p class="leading-relaxed text-sm" style="color: var(--text);">Dengan memohon rahmat dan ridho Allah SWT, kami bermaksud menyelenggarakan acara pernikahan kami. Merupakan suatu kehormatan dan kebahagiaan bagi kami apabila Bapak/Ibu/Saudara/i berkenan hadir untuk memberikan doa restu.</p>
            </div>
        </section>

        {{-- SECTION DIVIDER --}}
        <div class="section-divider" style="background: var(--cream);">
            <div class="section-divider-icon">
                <svg fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
            </div>
        </div>

        {{-- COUPLE --}}
        <section id="couple" class="section pattern-dots" style="background: var(--cream);">
            {{-- Floating Decorations --}}
            <div class="floating-decor bottom-left" style="opacity: 0.1;">
                <svg viewBox="0 0 24 24"><path d="M17,8C8,10 5.9,16.17 3.82,21.34L5.71,22L6.66,19.7C7.14,19.87 7.64,20 8,20C19,20 22,3 22,3C21,5 14,5.25 9,6.25C4,7.25 2,11.5 2,13.5C2,15.5 3.75,17.25 3.75,17.25C7,8 17,8 17,8Z"/></svg>
            </div>
            <div class="floating-decor bottom-right" style="opacity: 0.1;">
                <svg viewBox="0 0 24 24"><path d="M17,8C8,10 5.9,16.17 3.82,21.34L5.71,22L6.66,19.7C7.14,19.87 7.64,20 8,20C19,20 22,3 22,3C21,5 14,5.25 9,6.25C4,7.25 2,11.5 2,13.5C2,15.5 3.75,17.25 3.75,17.25C7,8 17,8 17,8Z"/></svg>
            </div>
            <div class="section-title">
                <p class="text-[10px] tracking-widest uppercase mb-2" style="color: var(--gold);">Bride & Groom</p>
                <h2>Mempelai</h2>
                <div class="divider"></div>
            </div>
            
            <div class="max-w-lg mx-auto space-y-4">
            @php $order = $invitation->custom_styles['name_order'] ?? 'groom_first'; @endphp
            @if($order === 'bride_first')
                {{-- Bride --}}
                <div class="card couple-card me-2">
                    <img src="{{ $invitation->bride_photo ? asset('storage/' . $invitation->bride_photo) : 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=400' }}" class="couple-photo" alt="Bride" loading="lazy">
                    <h3 class="couple-name">{{ $invitation->bride_name }}</h3>
                    <p class="text-gray-500 text-sm mb-3">Putri dari Bapak {{ $invitation->bride_father }} & Ibu {{ $invitation->bride_mother }}</p>
                    @if($invitation->bride_instagram)
                    <a href="https://instagram.com/{{ $invitation->bride_instagram }}" target="_blank" class="inline-flex items-center gap-1.5 text-[#C9A227] text-sm">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                        @{{ $invitation->bride_instagram }}
                    </a>
                    @endif
                </div>

                {{-- Groom --}}
                <div class="card couple-card">
                    <img src="{{ $invitation->groom_photo ? asset('storage/' . $invitation->groom_photo) : 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=400' }}" class="couple-photo" alt="Groom" loading="lazy">
                    <h3 class="couple-name">{{ $invitation->groom_name }}</h3>
                    <p class="text-gray-500 text-sm mb-3">Putra dari Bapak {{ $invitation->groom_father }} & Ibu {{ $invitation->groom_mother }}</p>
                    @if($invitation->groom_instagram)
                    <a href="https://instagram.com/{{ $invitation->groom_instagram }}" target="_blank" class="inline-flex items-center gap-1.5 text-[#C9A227] text-sm">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                        @{{ $invitation->groom_instagram }}
                    </a>
                    @endif
                </div>
            @else
                {{-- Groom --}}
                <div class="card couple-card me-2">
                    <img src="{{ $invitation->groom_photo ? asset('storage/' . $invitation->groom_photo) : 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=400' }}" class="couple-photo" alt="Groom" loading="lazy">
                    <h3 class="couple-name">{{ $invitation->groom_name }}</h3>
                    <p class="text-gray-500 text-sm mb-3">Putra dari Bapak {{ $invitation->groom_father }} & Ibu {{ $invitation->groom_mother }}</p>
                    @if($invitation->groom_instagram)
                    <a href="https://instagram.com/{{ $invitation->groom_instagram }}" target="_blank" class="inline-flex items-center gap-1.5 text-[#C9A227] text-sm">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                        @{{ $invitation->groom_instagram }}
                    </a>
                    @endif
                </div>

                {{-- Bride --}}
                <div class="card couple-card">
                    <img src="{{ $invitation->bride_photo ? asset('storage/' . $invitation->bride_photo) : 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=400' }}" class="couple-photo" alt="Bride" loading="lazy">
                    <h3 class="couple-name">{{ $invitation->bride_name }}</h3>
                    <p class="text-gray-500 text-sm mb-3">Putri dari Bapak {{ $invitation->bride_father }} & Ibu {{ $invitation->bride_mother }}</p>
                    @if($invitation->bride_instagram)
                    <a href="https://instagram.com/{{ $invitation->bride_instagram }}" target="_blank" class="inline-flex items-center gap-1.5 text-[#C9A227] text-sm">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                        @{{ $invitation->bride_instagram }}
                    </a>
                    @endif
                </div>
            @endif
            </div>
        </section>

        {{-- COUNTDOWN --}}
        <section class="section" style="background: linear-gradient(135deg, #1A1A1A, #2D2D2D); padding: 60px 20px;">
            <div class="max-w-lg mx-auto text-center text-white">
                <p class="font-script text-[#C9A227]" style="font-size: 2rem; margin-bottom: 8px;">Save The Date</p>
                <p style="font-size: 10px; letter-spacing: 3px; text-transform: uppercase; opacity: 0.7; margin-bottom: 32px;">Hitung Mundur Menuju Hari Bahagia</p>
                
                <div x-data="{
                    days: 0, hours: 0, minutes: 0, seconds: 0,
                    target: new Date('{{ $invitation->akad_date?->format('Y-m-d H:i:s') }}'),
                    init() { this.update(); setInterval(() => this.update(), 1000); },
                    update() {
                        const diff = this.target - new Date();
                        if (diff > 0) {
                            this.days = Math.floor(diff / 86400000);
                            this.hours = Math.floor((diff % 86400000) / 3600000);
                            this.minutes = Math.floor((diff % 3600000) / 60000);
                            this.seconds = Math.floor((diff % 60000) / 1000);
                        }
                    }
                }" class="grid grid-cols-4 gap-3" style="max-width: 320px; margin: 0 auto;">
                    <div style="background: rgba(255,255,255,0.1); border-radius: 12px; padding: 16px 8px;">
                        <div class="font-serif" style="font-size: 1.75rem; font-weight: 600;" x-text="days">0</div>
                        <div style="font-size: 10px; opacity: 0.7; margin-top: 4px;">Hari</div>
                    </div>
                    <div style="background: rgba(255,255,255,0.1); border-radius: 12px; padding: 16px 8px;">
                        <div class="font-serif" style="font-size: 1.75rem; font-weight: 600;" x-text="hours">0</div>
                        <div style="font-size: 10px; opacity: 0.7; margin-top: 4px;">Jam</div>
                    </div>
                    <div style="background: rgba(255,255,255,0.1); border-radius: 12px; padding: 16px 8px;">
                        <div class="font-serif" style="font-size: 1.75rem; font-weight: 600;" x-text="minutes">0</div>
                        <div style="font-size: 10px; opacity: 0.7; margin-top: 4px;">Menit</div>
                    </div>
                    <div style="background: rgba(255,255,255,0.1); border-radius: 12px; padding: 16px 8px;">
                        <div class="font-serif" style="font-size: 1.75rem; font-weight: 600;" x-text="seconds">0</div>
                        <div style="font-size: 10px; opacity: 0.7; margin-top: 4px;">Detik</div>
                    </div>
                </div>
            </div>
        </section>

        {{-- EVENTS --}}
        <section id="events" class="section bg-white">
            <div class="section-title">
                <p class="text-gold" style="font-size: 10px; letter-spacing: 2px; text-transform: uppercase; margin-bottom: 8px;">Wedding Events</p>
                <h2>Waktu & Tempat</h2>
                <div class="divider"></div>
            </div>
            
            <div class="max-w-lg mx-auto">
                {{-- Akad --}}
                <div class="event-card">
                    <div class="event-card-inner">
                        <div class="event-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                        </div>
                        <div class="event-content">
                            <h3 class="event-title">Akad Nikah</h3>
                            <p class="event-date">{{ $invitation->akad_date?->translatedFormat('l, d F Y') }}</p>
                            <div class="event-info">
                                <div class="event-info-item">
                                    <svg fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10 10-4.5 10-10S17.5 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm.5-13H11v6l5.2 3.2.8-1.3-4.5-2.7V7z"/></svg>
                                    <span>{{ $invitation->akad_date?->format('H:i') }} WIB</span>
                                </div>
                                <div class="event-info-item">
                                    <svg fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                                    <span>{{ $invitation->akad_venue }}<br>{{ $invitation->akad_address }}</span>
                                </div>
                            </div>
                            <a href="{{ $invitation->akad_maps_link }}" target="_blank" class="event-link">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                Lihat Lokasi
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Resepsi --}}
                <div class="event-card secondary">
                    <div class="event-card-inner">
                        <div class="event-icon secondary">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 15.546c-.523 0-1.046.151-1.5.454a2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.701 2.701 0 01-1.5.454M9 6v2m3-2v2m3-2v2M9 3h.01M12 3h.01M15 3h.01M21 21v-7a2 2 0 00-2-2H5a2 2 0 00-2 2v7h18zm-3-9v-2a2 2 0 00-2-2H8a2 2 0 00-2 2v2h12z"/></svg>
                        </div>
                        <div class="event-content">
                            <h3 class="event-title">Resepsi</h3>
                            <p class="event-date secondary">{{ $invitation->resepsi_date?->translatedFormat('l, d F Y') }}</p>
                            <div class="event-info">
                                <div class="event-info-item">
                                    <svg fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10 10-4.5 10-10S17.5 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm.5-13H11v6l5.2 3.2.8-1.3-4.5-2.7V7z"/></svg>
                                    <span>{{ $invitation->resepsi_date?->format('H:i') }} WIB - Selesai</span>
                                </div>
                                <div class="event-info-item">
                                    <svg fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                                    <span>{{ $invitation->resepsi_venue }}<br>{{ $invitation->resepsi_address }}</span>
                                </div>
                            </div>
                            <a href="{{ $invitation->resepsi_maps_link }}" target="_blank" class="event-link">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                Lihat Lokasi
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- GALLERY --}}
        @if($invitation->photos->count() > 0)
        <section id="gallery" class="section bg-cream" x-data="{ 
            lightboxOpen: false, 
            lightboxImage: '', 
            lightboxCaption: '',
            openLightbox(url, caption) {
                this.lightboxImage = url;
                this.lightboxCaption = caption;
                this.lightboxOpen = true;
                document.body.style.overflow = 'hidden';
            },
            closeLightbox() {
                this.lightboxOpen = false;
                document.body.style.overflow = '';
            }
        }">
            <div class="section-title">
                <p class="text-gold" style="font-size: 10px; letter-spacing: 2px; text-transform: uppercase; margin-bottom: 8px;">Our Moments</p>
                <h2>Galeri</h2>
                <div class="divider"></div>
            </div>
            <div class="max-w-lg mx-auto gallery-grid">
                @foreach($invitation->photos as $photo)
                <div class="gallery-item" @click="openLightbox('{{ $photo->url }}', '{{ $photo->caption ?? '' }}')">
                    <img src="{{ $photo->url }}" alt="{{ $photo->caption ?? 'Gallery' }}" loading="lazy">
                </div>
                @endforeach
            </div>

            {{-- Lightbox Modal --}}
            <div x-show="lightboxOpen" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click.self="closeLightbox()"
                 @keydown.escape.window="closeLightbox()"
                 class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/90"
                 style="display: none;">
                
                {{-- Close Button --}}
                <button @click="closeLightbox()" class="absolute top-4 right-4 w-12 h-12 flex items-center justify-center text-white/80 hover:text-white transition-colors z-10">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>

                {{-- Image Container --}}
                <div class="relative max-w-4xl max-h-[90vh] w-full">
                    <img :src="lightboxImage" :alt="lightboxCaption || 'Gallery'" 
                         class="w-full h-full object-contain rounded-lg shadow-2xl"
                         @click.stop>
                    
                    {{-- Caption --}}
                    <p x-show="lightboxCaption" 
                       x-text="lightboxCaption" 
                       class="absolute bottom-0 left-0 right-0 p-4 text-center text-white bg-gradient-to-t from-black/70 to-transparent rounded-b-lg"></p>
                </div>
            </div>
        </section>
        @endif

        {{-- GIFT --}}
        @if($invitation->enable_gift)
        <section id="gift" class="section" style="background: linear-gradient(180deg, #1A1A1A 0%, #2D2D2D 50%, #1A1A1A 100%); position: relative; overflow: hidden;">
            {{-- Decorative Elements --}}
            <div style="position: absolute; top: 0; left: 0; right: 0; height: 1px; background: linear-gradient(90deg, transparent, var(--gold), transparent);"></div>
            <div style="position: absolute; bottom: 0; left: 0; right: 0; height: 1px; background: linear-gradient(90deg, transparent, var(--gold), transparent);"></div>
            <div style="position: absolute; top: 20%; left: -100px; width: 200px; height: 200px; background: radial-gradient(circle, rgba(201,162,39,0.08) 0%, transparent 70%); pointer-events: none;"></div>
            <div style="position: absolute; bottom: 20%; right: -100px; width: 200px; height: 200px; background: radial-gradient(circle, rgba(201,162,39,0.08) 0%, transparent 70%); pointer-events: none;"></div>
            
            <div class="section-title" style="position: relative; z-index: 1;">
                <div style="display: inline-flex; align-items: center; justify-content: center; width: 64px; height: 64px; background: linear-gradient(135deg, var(--gold), var(--gold-dark)); border-radius: 50%; margin-bottom: 20px; box-shadow: 0 8px 32px rgba(201,162,39,0.3);">
                    <svg style="width: 28px; height: 28px; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/></svg>
                </div>
                <p style="font-size: 10px; letter-spacing: 3px; text-transform: uppercase; margin-bottom: 8px; color: var(--gold);">Wedding Gift</p>
                <h2 style="color: white; font-size: 2rem;">Amplop Digital</h2>
                <div style="width: 60px; height: 2px; background: linear-gradient(90deg, transparent, var(--gold), transparent); margin: 16px auto;"></div>
            </div>
            <div class="max-w-md mx-auto text-center px-4" style="position: relative; z-index: 1;">
                <p style="color: rgba(255,255,255,0.7); margin-bottom: 32px; font-size: 14px; font-style: italic; font-family: 'Cormorant Garamond', serif; line-height: 1.8;">Doa restu Anda merupakan karunia yang sangat berarti bagi kami. Dan jika memberi adalah ungkapan tanda kasih, Anda dapat memberi kado secara cashless.</p>
                
                <div style="display: grid; gap: 16px;">
                @if($invitation->bank_accounts)
                    @foreach($invitation->bank_accounts as $index => $account)
                    <div class="gift-card animate-fade-up" x-data="{ copied: false }" style="animation-delay: {{ $index * 0.1 }}s;">
                        <div class="gift-card-header">
                            <div class="gift-card-icon">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                            </div>
                            <div class="gift-card-info">
                                <p class="gift-card-bank">{{ $account['bank'] }}</p>
                                <p class="gift-card-number">{{ $account['account_number'] }}</p>
                                <p class="gift-card-name">a.n {{ $account['account_name'] }}</p>
                            </div>
                        </div>
                        <button @click="navigator.clipboard.writeText('{{ $account['account_number'] }}'); copied = true; setTimeout(() => copied = false, 2000)" 
                                class="copy-btn" :class="{ 'copied': copied }">
                            <svg x-show="!copied" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                            <svg x-show="copied" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span x-text="copied ? 'Tersalin!' : 'Salin Rekening'"></span>
                        </button>
                    </div>
                    @endforeach
                @elseif($invitation->bank_name)
                    <div class="gift-card" x-data="{ copied: false }">
                        <div class="gift-card-header">
                            <div class="gift-card-icon">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                            </div>
                            <div class="gift-card-info">
                                <p class="gift-card-bank">{{ $invitation->bank_name }}</p>
                                <p class="gift-card-number">{{ $invitation->bank_account }}</p>
                                <p class="gift-card-name">a.n {{ $invitation->bank_holder }}</p>
                            </div>
                        </div>
                        <button @click="navigator.clipboard.writeText('{{ $invitation->bank_account }}'); copied = true; setTimeout(() => copied = false, 2000)" 
                                class="copy-btn" :class="{ 'copied': copied }">
                            <svg x-show="!copied" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                            <svg x-show="copied" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span x-text="copied ? 'Tersalin!' : 'Salin Rekening'"></span>
                        </button>
                    </div>
                @endif
                </div>
                
                {{-- Thank You Text --}}
                <p style="color: rgba(255,255,255,0.5); font-size: 12px; margin-top: 32px; display: flex; align-items: center; justify-content: center; gap: 8px;">
                    <svg style="width: 16px; height: 16px; color: var(--gold);" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                    Terima kasih atas kebaikan hati Anda
                </p>
            </div>
        </section>
        @endif
        {{-- RSVP & WISHES --}}
        @if($invitation->enable_rsvp || $invitation->enable_wishes)
        <section id="rsvp" class="rsvp-section"
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
            <div class="max-w-md mx-auto">
                {{-- Section Header --}}
                <div class="section-title">
                    <p class="text-gold" style="font-size: 10px; letter-spacing: 3px; text-transform: uppercase; margin-bottom: 8px;">RSVP & Wishes</p>
                    <h2>Ucapan & Kehadiran</h2>
                    <div class="divider"></div>
                </div>

                {{-- Stats Cards --}}
                <div class="rsvp-stats">
                    <div class="rsvp-stat-card">
                        <div class="rsvp-stat-num" x-text="stats.total_wishes">0</div>
                        <div class="rsvp-stat-label">Ucapan</div>
                    </div>
                    <div class="rsvp-stat-card">
                        <div class="rsvp-stat-num" x-text="stats.total_confirmed">0</div>
                        <div class="rsvp-stat-label">Tamu Hadir</div>
                    </div>
                </div>

                {{-- Form Card --}}
                <div class="rsvp-form-card">
                    {{-- Success Message --}}
                    <div x-show="success" x-transition class="rsvp-alert rsvp-alert-success">
                        <div class="rsvp-alert-icon">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        </div>
                        <div>
                            <p class="rsvp-alert-title">Terima kasih!</p>
                            <p class="rsvp-alert-text">Ucapan dan konfirmasi Anda telah tersimpan.</p>
                        </div>
                    </div>

                    {{-- Error Message --}}
                    <div x-show="error" x-transition class="rsvp-alert rsvp-alert-error">
                        <div class="rsvp-alert-icon">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                        </div>
                        <div>
                            <p class="rsvp-alert-title">Terjadi kesalahan</p>
                            <p class="rsvp-alert-text" x-text="error"></p>
                        </div>
                    </div>

                    <form @submit.prevent="submitForm">
                        {{-- Name Input --}}
                        <div class="rsvp-form-group">
                            <label class="rsvp-label">Nama Lengkap</label>
                            <input type="text" x-model="name" placeholder="Masukkan nama Anda" class="rsvp-input">
                        </div>

                        {{-- Message Input --}}
                        <div class="rsvp-form-group">
                            <label class="rsvp-label">Ucapan & Doa</label>
                            <textarea x-model="message" rows="4" placeholder="Tulis ucapan dan doa terbaik Anda..." class="rsvp-input rsvp-textarea"></textarea>
                        </div>

                        {{-- Attendance Toggle --}}
                        <div class="rsvp-form-group">
                            <label class="rsvp-label">Konfirmasi Kehadiran</label>
                            <div class="rsvp-toggle-grid">
                                <button type="button" @click="status = 'confirmed'"
                                    :class="{ 'active': status === 'confirmed' }"
                                    class="rsvp-toggle-btn">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    Hadir
                                </button>
                                <button type="button" @click="status = 'declined'"
                                    :class="{ 'active declined': status === 'declined' }"
                                    class="rsvp-toggle-btn">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    Tidak Hadir
                                </button>
                            </div>
                        </div>

                        {{-- Guest Count --}}
                        <div class="rsvp-form-group" x-show="status === 'confirmed'" x-transition>
                            <label class="rsvp-label">Jumlah Tamu</label>
                            <select x-model="pax" class="rsvp-input rsvp-select">
                                <option value="1">1 Orang</option>
                                <option value="2">2 Orang</option>
                                <option value="3">3 Orang</option>
                                <option value="4">4 Orang</option>
                                <option value="5">5 Orang</option>
                            </select>
                        </div>

                        {{-- Submit Button --}}
                        <button type="submit" :disabled="loading" class="rsvp-submit-btn">
                            <template x-if="!loading">
                                <span style="display: flex; align-items: center; gap: 8px;">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                                    Kirim Ucapan
                                </span>
                            </template>
                            <template x-if="loading">
                                <span style="display: flex; align-items: center; gap: 8px;">
                                    <svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                                    </svg>
                                    Mengirim...
                                </span>
                            </template>
                        </button>
                    </form>
                </div>

                {{-- Wishes List --}}
                <div class="rsvp-wishes-list">
                    <h3 class="rsvp-wishes-title">Ucapan Terbaru</h3>
                    
                    <template x-for="wish in wishes" :key="wish.id">
                        <div class="rsvp-wish-card">
                            <div style="display: flex; gap: 12px;">
                                <div class="rsvp-wish-avatar" x-text="wish.initial"></div>
                                <div style="flex: 1; min-width: 0;">
                                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 4px;">
                                        <h4 class="rsvp-wish-name" x-text="wish.name"></h4>
                                        <span class="rsvp-wish-time" x-text="wish.time"></span>
                                    </div>
                                    <p class="rsvp-wish-message" x-text="wish.message"></p>
                                    <template x-if="wish.attendance_status">
                                        <span class="rsvp-attendance-badge" :class="wish.attendance_status">
                                            <template x-if="wish.attendance_status === 'confirmed'">
                                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                            </template>
                                            <template x-if="wish.attendance_status === 'declined'">
                                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                            </template>
                                            <span x-text="wish.attendance_status === 'confirmed' ? 'Akan Hadir' : 'Tidak Hadir'"></span>
                                        </span>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </template>

                    {{-- Empty State --}}
                    <div x-show="wishes.length === 0" class="rsvp-empty">
                        <div class="rsvp-empty-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                        </div>
                        <p class="rsvp-empty-text">Belum ada ucapan</p>
                        <p class="rsvp-empty-subtext">Jadilah yang pertama memberikan ucapan!</p>
                    </div>
                </div>
            </div>
        </section>
        @endif

        {{-- FOOTER --}}
        <footer class="theme-footer">
            <p class="font-script text-4xl text-[#C9A227] mb-3">{{ $invitation->groom_nickname }} & {{ $invitation->bride_nickname }}</p>
            <p class="font-serif text-lg mb-4">{{ $invitation->akad_date?->translatedFormat('d F Y') }}</p>
            <div style="width: 64px; height: 1px; background: linear-gradient(90deg, transparent, #C9A227, transparent); margin: 0 auto 16px;"></div>
            <p class="text-xs opacity-60">Terima kasih atas kehadiran dan doa restu Anda</p>
        </footer>
    </main>

    {{-- MUSIC BUTTON --}}
    <button x-show="opened" @click="toggleAudio()" class="music-btn" :class="{ 'playing': playing }">
        <svg x-show="playing" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M3 9v6h4l5 5V4L7 9H3zm13.5 3c0-1.77-1.02-3.29-2.5-4.03v8.05c1.48-.73 2.5-2.25 2.5-4.02z"/></svg>
        <svg x-show="!playing" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M16.5 12c0-1.77-1.02-3.29-2.5-4.03v2.21l2.45 2.45c.03-.2.05-.41.05-.63zm2.5 0c0 .94-.2 1.82-.54 2.64l1.51 1.51C20.63 14.91 21 13.5 21 12c0-4.28-2.99-7.86-7-8.77v2.06c2.89.86 5 3.54 5 6.71zM4.27 3L3 4.27 7.73 9H3v6h4l5 5v-6.73l4.25 4.25c-.67.52-1.42.93-2.25 1.18v2.06c1.38-.31 2.63-.95 3.69-1.81L19.73 21 21 19.73l-9-9L4.27 3zM12 4L9.91 6.09 12 8.18V4z"/></svg>
    </button>

    {{-- BOTTOM NAV --}}
    <nav x-show="opened" class="bottom-nav">
        <div class="nav-items">
            <a @click.prevent="scrollTo('home')" class="nav-item" :class="{ 'active': activeSection === 'home' }">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                <span>Home</span>
            </a>
            <a @click.prevent="scrollTo('couple')" class="nav-item" :class="{ 'active': activeSection === 'couple' }">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                <span>Couple</span>
            </a>
            <a @click.prevent="scrollTo('events')" class="nav-item" :class="{ 'active': activeSection === 'events' }">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <span>Event</span>
            </a>
            @if($invitation->enable_gift)
            <a @click.prevent="scrollTo('gift')" class="nav-item" :class="{ 'active': activeSection === 'gift' }">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/></svg>
                <span>Gift</span>
            </a>
            @endif
            @if($invitation->enable_rsvp || $invitation->enable_wishes)
            <a @click.prevent="scrollTo('rsvp')" class="nav-item" :class="{ 'active': activeSection === 'rsvp' }">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                <span>RSVP</span>
            </a>
            @endif
        </div>
    </nav>
</div>