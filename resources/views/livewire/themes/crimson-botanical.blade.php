@once
    @push('fonts')
        <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@500;600;700&family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,400;1,600&family=DM+Sans:wght@400;500;600;700&family=Pinyon+Script&display=swap" rel="stylesheet">
    @endpush

    @push('styles')
        <link rel="preload" as="image" href="{{ asset('themes/crimson-botanical/assets/parchment-bg.jpg') }}" fetchpriority="high">
        <style>
            :root {
                --cb-primary: #8B1E2B; --cb-primary-dark: #5C111B; --cb-primary-deep: #3D0910; --cb-primary-soft: #A83242;
                --cb-gold: #C59B5F; --cb-gold-light: #E8C98F; --cb-gold-dark: #966F38;
                --cb-bg: #F6EFE6; --cb-surface: rgba(255, 252, 247, 0.88); --cb-surface-strong: rgba(255, 253, 249, 0.95);
                --cb-text: #3A1D20; --cb-muted: #765659;
                --cb-border: rgba(139, 30, 43, 0.16); --cb-border-gold: rgba(197, 155, 95, 0.45);
                --cb-font-display: 'Cinzel', Georgia, serif;
                --cb-font-heading: 'Cormorant Garamond', Georgia, serif;
                --cb-font-script: 'Pinyon Script', cursive;
                --cb-font-body: 'DM Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            }
            *, *::before, *::after { box-sizing: border-box; }
            html { scroll-behavior: smooth; }
            body { margin: 0; padding: 0; background-color: #2B070C; color: var(--cb-text); font-family: var(--cb-font-body); overflow-x: hidden; -webkit-font-smoothing: antialiased; }
            body.cb-locked { overflow: hidden !important; height: 100dvh; }
            .cb-root { position: relative; min-height: 100dvh; width: 100%; display: flex; justify-content: center; background: radial-gradient(circle at 50% 18%, #58101A 0%, #240509 100%); }
            /* Unified Parchment Canvas (480px max on desktop, identical on Cover & Main Stage) */
            .cb-stage {
                position: relative; width: 100%; max-width: 480px; min-height: 100dvh;
                background-color: var(--cb-bg);
                background-image: url('{{ asset('themes/crimson-botanical/assets/parchment-bg.jpg') }}');
                background-size: 100% auto; background-position: top center; background-repeat: repeat-y;
                box-shadow: 0 0 65px rgba(0, 0, 0, 0.65); overflow: hidden; z-index: 1;
            }
            .cb-stage::before {
                content: ''; position: fixed; top: var(--cb-top-offset, 0px); bottom: 0; left: 50%; transform: translateX(-50%);
                width: 100%; max-width: 480px;
                background: radial-gradient(circle at 50% 35%, rgba(255, 252, 247, 0.28) 0%, rgba(246, 239, 230, 0.56) 100%);
                pointer-events: none; z-index: 1;
            }
            /* Fixed 4-Corner Botanicals Framing Both Cover & Main Stage Consistently (z-index: 160) */
            .cb-viewport-deco {
                position: fixed; top: var(--cb-top-offset, 0px); bottom: 0; left: 50%; transform: translateX(-50%);
                width: 100%; max-width: 480px; pointer-events: none; z-index: 160; overflow: hidden;
            }
            .cb-viewport-deco img { position: absolute; pointer-events: none; user-select: none; filter: drop-shadow(0 6px 14px rgba(58, 29, 32, 0.14)); will-change: transform; }
            .cb-deco-tl { top: -6px; left: -6px; width: 132px; transform-origin: top left; animation: cbWindSwayTL 7s ease-in-out infinite; }
            .cb-deco-tr { top: -6px; right: -6px; width: 112px; transform-origin: top right; animation: cbWindSwayTR 8s ease-in-out infinite 0.7s; }
            .cb-deco-bl { bottom: -6px; left: -6px; width: 108px; transform-origin: bottom left; animation: cbWindSwayBL 8.5s ease-in-out infinite 1.2s; }
            .cb-deco-br { bottom: -6px; right: -6px; width: 136px; transform-origin: bottom right; animation: cbWindSwayBR 7.5s ease-in-out infinite 0.4s; }
            /* Ambient Petals, Gold Sparkles & Butterflies Active from Cover Through Footer (z-index: 158) */
            .cb-ambient-layer {
                position: fixed; top: var(--cb-top-offset, 0px); bottom: 0; left: 50%; transform: translateX(-50%);
                width: 100%; max-width: 480px; pointer-events: none; z-index: 158; overflow: hidden;
            }
            .cb-petal { position: absolute; top: -32px; width: 14px; height: 14px; opacity: 0; pointer-events: none; will-change: transform, opacity; }
            .cb-petal svg { width: 100%; height: 100%; display: block; }
            .cb-petal.p1 { left: 14%; animation: cbPetalFall1 14s linear infinite 0.5s; }
            .cb-petal.p2 { left: 34%; width: 11px; height: 11px; animation: cbPetalFall2 17s linear infinite 3.5s; }
            .cb-petal.p3 { left: 66%; width: 13px; height: 13px; animation: cbPetalFall1 15.5s linear infinite 2s; }
            .cb-petal.p4 { left: 84%; width: 10px; height: 10px; animation: cbPetalFall2 18s linear infinite 6s; }
            .cb-sparkle {
                position: absolute; width: 5px; height: 5px; border-radius: 50%;
                background: radial-gradient(circle, #FFF8E7 0%, #C59B5F 75%, transparent 100%);
                box-shadow: 0 0 8px rgba(197, 155, 95, 0.8); opacity: 0; animation: cbTwinkle 4.8s ease-in-out infinite;
            }
            .cb-sparkle.s1 { top: 20%; left: 18%; animation-delay: 0.4s; }
            .cb-sparkle.s2 { top: 34%; right: 18%; animation-delay: 1.7s; }
            .cb-sparkle.s3 { top: 64%; left: 15%; animation-delay: 2.8s; }
            .cb-sparkle.s4 { top: 76%; right: 20%; animation-delay: 1.1s; }
            .cb-butterfly { position: absolute; width: 22px; height: 22px; pointer-events: none; opacity: 0; z-index: 159; filter: drop-shadow(0 3px 6px rgba(139, 30, 43, 0.14)); }
            .cb-butterfly svg { width: 100%; height: 100%; overflow: visible; }
            .cb-butterfly .cb-wing-l { transform-origin: 12px 12px; animation: cbWingFlapL 0.42s ease-in-out infinite; }
            .cb-butterfly .cb-wing-r { transform-origin: 12px 12px; animation: cbWingFlapR 0.42s ease-in-out infinite; }
            .cb-bf-1 { animation: cbButterflyFlight1 18s ease-in-out infinite 1.5s; }
            .cb-bf-2 { width: 18px; height: 18px; animation: cbButterflyFlight2 22s ease-in-out infinite 6s; }
            @keyframes cbWindSwayTL { 0%, 100% { transform: rotate(0deg) scale(1); } 50% { transform: rotate(2.2deg) scale(1.03); } }
            @keyframes cbWindSwayTR { 0%, 100% { transform: rotate(0deg) scale(1); } 50% { transform: rotate(-2.2deg) scale(1.03); } }
            @keyframes cbWindSwayBL { 0%, 100% { transform: rotate(0deg) scale(1); } 50% { transform: rotate(-2deg) scale(1.03); } }
            @keyframes cbWindSwayBR { 0%, 100% { transform: rotate(0deg) scale(1); } 50% { transform: rotate(2deg) scale(1.03); } }
            @keyframes cbPetalFall1 {
                0% { transform: translate3d(0, -30px, 0) rotate(0deg); opacity: 0; }
                12% { opacity: 0.55; }
                50% { transform: translate3d(22px, 46vh, 0) rotate(160deg); opacity: 0.45; }
                88% { opacity: 0.35; }
                100% { transform: translate3d(-14px, 96vh, 0) rotate(320deg); opacity: 0; }
            }
            @keyframes cbPetalFall2 {
                0% { transform: translate3d(0, -30px, 0) rotate(25deg); opacity: 0; }
                14% { opacity: 0.48; }
                50% { transform: translate3d(-24px, 48vh, 0) rotate(195deg); opacity: 0.4; }
                88% { opacity: 0.28; }
                100% { transform: translate3d(16px, 96vh, 0) rotate(350deg); opacity: 0; }
            }
            @keyframes cbTwinkle { 0%, 100% { opacity: 0.1; transform: scale(0.75); } 50% { opacity: 0.78; transform: scale(1.25); } }
            @keyframes cbWingFlapL { 0%, 100% { transform: scaleX(1) rotate(0deg); } 50% { transform: scaleX(0.55) rotate(6deg); } }
            @keyframes cbWingFlapR { 0%, 100% { transform: scaleX(1) rotate(0deg); } 50% { transform: scaleX(0.55) rotate(-6deg); } }
            @keyframes cbButterflyFlight1 {
                0% { top: 76%; left: -8%; opacity: 0; transform: rotate(18deg) scale(0.8); }
                12% { opacity: 0.65; }
                38% { top: 52%; left: 24%; transform: rotate(8deg) scale(0.95); }
                68% { top: 28%; left: 68%; transform: rotate(14deg) scale(0.9); }
                90% { opacity: 0.5; }
                100% { top: 14%; left: 108%; opacity: 0; transform: rotate(5deg) scale(0.75); }
            }
            @keyframes cbButterflyFlight2 {
                0% { top: 22%; right: -8%; opacity: 0; transform: rotate(-16deg) scale(0.75); }
                14% { opacity: 0.55; }
                45% { top: 44%; right: 28%; transform: rotate(-8deg) scale(0.9); }
                75% { top: 66%; right: 68%; transform: rotate(-14deg) scale(0.85); }
                90% { opacity: 0.42; }
                100% { top: 78%; right: 108%; opacity: 0; transform: rotate(-6deg) scale(0.7); }
            }
            @keyframes cbFloatArch { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-5px); } }
            @keyframes cbGarlandBreathe { 0%, 100% { transform: translateX(-50%) scale(1); } 50% { transform: translateX(-50%) scale(1.025); } }
            @keyframes cbPulseHalo {
                0%, 100% { box-shadow: 0 10px 26px rgba(139, 30, 43, 0.34), 0 0 0 0 rgba(139, 30, 43, 0.28); }
                50% { box-shadow: 0 14px 32px rgba(139, 30, 43, 0.46), 0 0 0 9px rgba(139, 30, 43, 0); }
            }
            @keyframes cbShimmerSweep { 0% { transform: translateX(-140%) skewX(-22deg); } 45%, 100% { transform: translateX(220%) skewX(-22deg); } }
            @keyframes cbWishIn { from { opacity: 0; transform: translateY(12px); } to { opacity: 1; transform: translateY(0); } }
            /* Cover Gate Overlay (Shares Exact Same Parchment & Botanical Frame as Main Stage) */
            #cb-cover-gate {
                position: fixed; top: var(--cb-top-offset, 0px); bottom: 0; left: 0; right: 0; z-index: 150;
                display: flex; justify-content: center; align-items: stretch;
                background: rgba(34, 5, 10, 0.72); backdrop-filter: blur(8px);
                transition: opacity 0.85s cubic-bezier(0.22, 1, 0.36, 1), transform 0.85s cubic-bezier(0.22, 1, 0.36, 1), visibility 0.85s;
            }
            #cb-cover-gate.cb-gate-opened { opacity: 0; transform: translateY(-32px) scale(1.02); visibility: hidden; pointer-events: none; }
            .cb-cover-card {
                position: relative; width: 100%; max-width: 480px; height: 100%;
                background-color: var(--cb-bg);
                background-image: url('{{ asset('themes/crimson-botanical/assets/parchment-bg.jpg') }}');
                background-size: 100% auto; background-position: top center; background-repeat: repeat-y;
                display: flex; flex-direction: column; justify-content: center; align-items: center;
                padding: 28px 22px 24px; text-align: center; overflow-y: auto; overflow-x: hidden;
            }
            .cb-cover-card::before {
                content: ''; position: absolute; inset: 0;
                background: radial-gradient(circle at 50% 35%, rgba(255, 252, 247, 0.28) 0%, rgba(246, 239, 230, 0.56) 100%);
                pointer-events: none;
            }
            .cb-cover-inner { position: relative; z-index: 10; width: 100%; max-width: 340px; display: flex; flex-direction: column; align-items: center; margin: auto 0; }
            /* Unified Luxury Double-Gold Arch Frame + Vector Rose Garland */
            .cb-arch-wrap { position: relative; width: 186px; height: 242px; margin: 0 auto 20px; animation: cbFloatArch 6s ease-in-out infinite; flex-shrink: 0; }
            .cb-arch-wrap::before { content: ''; position: absolute; inset: -7px; border-radius: 130px 130px 18px 18px; border: 1.2px dashed rgba(197, 155, 95, 0.65); pointer-events: none; }
            .cb-arch-crest { position: absolute; top: -15px; left: 50%; transform: translateX(-50%); width: 68px; height: 20px; z-index: 6; pointer-events: none; }
            .cb-arch-frame {
                position: relative; width: 100%; height: 100%; border-radius: 120px 120px 14px 14px; overflow: hidden;
                border: 4px solid #FFFDF9; outline: 1.5px solid rgba(197, 155, 95, 0.75);
                box-shadow: 0 14px 34px rgba(92, 17, 27, 0.22); background: linear-gradient(180deg, #7D1825 0%, #3E0A11 100%);
            }
            .cb-arch-frame::after { content: ''; position: absolute; inset: 7px; border-radius: 112px 112px 9px 9px; border: 1px solid rgba(243, 223, 182, 0.62); pointer-events: none; z-index: 2; }
            .cb-arch-frame img { width: 100%; height: 100%; object-fit: cover; display: block; transition: transform 1.2s ease; }
            .cb-arch-wrap:hover .cb-arch-frame img { transform: scale(1.04); }
            .cb-arch-garland {
                position: absolute; bottom: -16px; left: 50%; transform: translateX(-50%); width: 216px; height: 54px;
                z-index: 5; pointer-events: none; filter: drop-shadow(0 5px 10px rgba(58, 29, 32, 0.18)); animation: cbGarlandBreathe 5.5s ease-in-out infinite;
            }
            /* Monogram Crest Medallion (Shown When Real Saved Invitation Has No Cover Photo) */
            .cb-monogram-crest {
                width: 116px; height: 116px; border-radius: 50%; margin: 0 auto 18px;
                background: linear-gradient(145deg, var(--cb-primary) 0%, var(--cb-primary-dark) 100%);
                border: 3px solid #FFFDF9; outline: 1.5px solid var(--cb-gold);
                box-shadow: 0 12px 28px rgba(92, 17, 27, 0.22);
                display: flex; align-items: center; justify-content: center;
                font-family: var(--cb-font-script); font-size: 2.35rem; color: var(--cb-gold-light);
                position: relative; animation: cbFloatArch 6s ease-in-out infinite;
            }
            .cb-monogram-crest::after {
                content: ''; position: absolute; inset: 6px; border-radius: 50%;
                border: 1px dashed rgba(232, 201, 143, 0.55); pointer-events: none;
            }
            .cb-cover-guest-card {
                width: 100%; max-width: 295px; background: var(--cb-surface); backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px);
                border: 1px solid var(--cb-border-gold); border-radius: 20px; padding: 14px 18px 13px; margin: 10px auto 16px;
                box-shadow: 0 10px 26px rgba(92, 17, 27, 0.08), inset 0 0 0 3px rgba(255, 255, 255, 0.55); position: relative;
            }
            .cb-eyebrow { font-family: var(--cb-font-display); font-size: 10.5px; font-weight: 600; letter-spacing: 3.2px; text-transform: uppercase; color: var(--cb-primary); margin-bottom: 5px; }
            .cb-script-title { font-family: var(--cb-font-script); font-size: clamp(2.85rem, 11.5vw, 3.65rem); font-weight: 400; color: var(--cb-primary); line-height: 1.12; margin: 2px 0; text-shadow: 0 2px 12px rgba(255, 252, 247, 0.95); }
            .cb-serif-title { font-family: var(--cb-font-display); font-size: 1.28rem; font-weight: 600; letter-spacing: 0.6px; color: var(--cb-text); line-height: 1.32; }
            .cb-ornament-divider { display: flex; align-items: center; justify-content: center; gap: 10px; width: 165px; margin: 8px auto; }
            .cb-ornament-divider::before, .cb-ornament-divider::after { content: ''; flex: 1; height: 1px; background: linear-gradient(90deg, transparent, var(--cb-gold), transparent); }
            .cb-ornament-diamond { width: 7px; height: 7px; background: var(--cb-gold); transform: rotate(45deg); flex-shrink: 0; }
            .cb-btn-primary {
                position: relative; overflow: hidden; display: inline-flex; align-items: center; justify-content: center; gap: 9px;
                background: linear-gradient(135deg, var(--cb-primary) 0%, var(--cb-primary-dark) 100%); color: #FFFDF9;
                border: 1px solid rgba(232, 201, 143, 0.55); padding: 12px 30px; border-radius: 999px;
                font-family: var(--cb-font-body); font-size: 13px; font-weight: 600; letter-spacing: 0.5px; cursor: pointer;
                box-shadow: 0 10px 24px rgba(139, 30, 43, 0.3); transition: transform 0.25s ease, box-shadow 0.25s ease; text-decoration: none;
            }
            .cb-btn-primary::after {
                content: ''; position: absolute; top: 0; left: 0; width: 42%; height: 100%;
                background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.26), transparent);
                animation: cbShimmerSweep 3.8s ease-in-out infinite; pointer-events: none;
            }
            .cb-btn-primary.cb-btn-pulse { animation: cbPulseHalo 2.8s ease-in-out infinite; }
            .cb-btn-primary:hover { transform: translateY(-2px); box-shadow: 0 14px 28px rgba(139, 30, 43, 0.42); }
            .cb-btn-outline {
                display: inline-flex; align-items: center; justify-content: center; gap: 8px;
                background: rgba(255, 252, 247, 0.92); color: var(--cb-primary); border: 1.5px solid var(--cb-primary);
                padding: 10px 22px; border-radius: 999px; font-family: var(--cb-font-body); font-size: 12px; font-weight: 600;
                cursor: pointer; transition: all 0.25s ease; text-decoration: none;
            }
            .cb-btn-outline:hover { background: var(--cb-primary); color: #FFFDF9; box-shadow: 0 6px 16px rgba(139, 30, 43, 0.22); }
            .cb-section { position: relative; z-index: 10; padding: 48px 22px; width: 100%; }
            .cb-section-header { text-align: center; margin-bottom: 24px; }
            .cb-card {
                position: relative; background: var(--cb-surface); backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px);
                border: 1px solid var(--cb-border-gold); border-radius: 24px; padding: 26px 20px;
                box-shadow: 0 12px 32px rgba(92, 17, 27, 0.08), inset 0 0 0 4px rgba(255, 255, 255, 0.48);
            }
            .cb-reveal { opacity: 0; transform: translateY(26px); transition: opacity 0.8s cubic-bezier(0.22, 1, 0.36, 1), transform 0.8s cubic-bezier(0.22, 1, 0.36, 1); }
            .cb-reveal.cb-from-scale { transform: scale(0.93) translateY(16px); }
            .cb-reveal.cb-revealed { opacity: 1; transform: translateY(0) scale(1); }
            .cb-date-pill {
                display: inline-flex; align-items: center; gap: 8px; padding: 7px 20px; border-radius: 999px;
                background: rgba(255, 252, 247, 0.92); border: 1px solid var(--cb-border-gold);
                font-family: var(--cb-font-display); font-size: 13px; font-weight: 600; color: var(--cb-text);
                letter-spacing: 2.2px; box-shadow: 0 4px 14px rgba(92, 17, 27, 0.07); margin-top: 8px;
            }
            .cb-countdown-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; width: 100%; max-width: 340px; margin: 18px auto 20px; }
            .cb-cd-box {
                position: relative; background: linear-gradient(155deg, var(--cb-primary) 0%, var(--cb-primary-dark) 100%);
                color: #FFFDF9; border-radius: 14px; padding: 13px 6px 10px; text-align: center;
                border: 1px solid rgba(232, 201, 143, 0.6); box-shadow: 0 10px 22px rgba(139, 30, 43, 0.24); overflow: hidden;
            }
            .cb-cd-box::after { content: ''; position: absolute; bottom: 0; left: 20%; right: 20%; height: 2px; background: var(--cb-gold-light); opacity: 0.75; }
            .cb-cd-num { font-family: var(--cb-font-display); font-size: 1.45rem; font-weight: 700; line-height: 1.08; display: block; color: #FFFDF9; }
            .cb-cd-lbl { font-family: var(--cb-font-display); font-size: 8.5px; text-transform: uppercase; letter-spacing: 1.6px; color: var(--cb-gold-light); margin-top: 4px; display: block; font-weight: 600; }
            .cb-quote-card {
                position: relative; background: linear-gradient(180deg, rgba(255, 253, 249, 0.93) 0%, rgba(251, 243, 233, 0.93) 100%);
                border: 1px solid var(--cb-border-gold); border-radius: 24px; padding: 30px 22px 26px; text-align: center;
                box-shadow: 0 14px 34px rgba(92, 17, 27, 0.09), inset 0 0 0 5px rgba(197, 155, 95, 0.12);
            }
            .cb-quote-badge {
                display: inline-block; padding: 5px 16px; border-radius: 999px;
                background: rgba(139, 30, 43, 0.08); border: 1px solid rgba(139, 30, 43, 0.2);
                font-size: 11px; font-weight: 700; letter-spacing: 2px; text-transform: uppercase; color: var(--cb-primary);
            }
            .cb-couple-card {
                position: relative; background: var(--cb-surface); backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px);
                border: 1px solid var(--cb-border-gold); border-radius: 24px; padding: 26px 20px 24px; text-align: center;
                box-shadow: 0 12px 30px rgba(92, 17, 27, 0.08), inset 0 0 0 4px rgba(255, 255, 255, 0.5);
            }
            .cb-couple-role-pill {
                display: inline-block; padding: 4px 14px; border-radius: 999px;
                background: rgba(139, 30, 43, 0.09); border: 1px solid rgba(139, 30, 43, 0.18);
                font-size: 10px; font-weight: 700; letter-spacing: 2.2px; text-transform: uppercase; color: var(--cb-primary); margin-bottom: 14px;
            }
            .cb-couple-frame-wrap { position: relative; width: 156px; height: 204px; margin: 0 auto 18px; animation: cbFloatArch 6.5s ease-in-out infinite; }
            .cb-couple-frame-wrap::before { content: ''; position: absolute; inset: -6px; border-radius: 100px 100px 16px 16px; border: 1.2px dashed rgba(197, 155, 95, 0.65); pointer-events: none; }
            .cb-couple-arch {
                position: relative; width: 100%; height: 100%; border-radius: 95px 95px 14px 14px; overflow: hidden;
                border: 3.5px solid #FFFDF9; outline: 1.5px solid rgba(197, 155, 95, 0.72);
                box-shadow: 0 12px 26px rgba(139, 30, 43, 0.18); background: linear-gradient(180deg, #7D1825 0%, #38070D 100%);
            }
            .cb-couple-arch img { width: 100%; height: 100%; object-fit: cover; display: block; }
            .cb-couple-garland { position: absolute; bottom: -13px; left: 50%; transform: translateX(-50%); width: 176px; height: 44px; z-index: 4; pointer-events: none; filter: drop-shadow(0 4px 8px rgba(58, 29, 32, 0.15)); }
            .cb-ampersand-badge {
                width: 52px; height: 52px; border-radius: 50%; margin: 18px auto;
                background: linear-gradient(135deg, var(--cb-primary) 0%, var(--cb-primary-dark) 100%);
                border: 2px solid var(--cb-gold-light); color: #FFFDF9; font-family: var(--cb-font-script); font-size: 1.85rem;
                display: flex; align-items: center; justify-content: center; box-shadow: 0 8px 20px rgba(139, 30, 43, 0.25);
            }
            .cb-event-card {
                position: relative; background: var(--cb-surface-strong); border: 1.5px solid var(--cb-border-gold);
                border-radius: 130px 130px 24px 24px; padding: 36px 20px 24px; text-align: center;
                box-shadow: 0 14px 32px rgba(92, 17, 27, 0.1); margin-bottom: 22px; overflow: hidden;
            }
            .cb-event-card::before { content: ''; position: absolute; inset: 7px; border-radius: 124px 124px 18px 18px; border: 1px dashed rgba(197, 155, 95, 0.45); pointer-events: none; }
            .cb-event-icon-circle {
                width: 48px; height: 48px; border-radius: 50%; margin: 0 auto 10px;
                background: linear-gradient(135deg, rgba(139, 30, 43, 0.12), rgba(197, 155, 95, 0.18));
                border: 1px solid var(--cb-border-gold); color: var(--cb-primary); display: flex; align-items: center; justify-content: center;
            }
            .cb-event-meta-grid {
                display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin: 14px 0; padding: 12px 10px;
                background: rgba(139, 30, 43, 0.04); border: 1px solid rgba(197, 155, 95, 0.28); border-radius: 14px;
            }
            .cb-timeline { position: relative; padding-left: 24px; border-left: 2px solid rgba(139, 30, 43, 0.26); margin-left: 10px; }
            .cb-timeline-item { position: relative; margin-bottom: 18px; }
            .cb-timeline-item:last-child { margin-bottom: 0; }
            .cb-timeline-dot { position: absolute; left: -32px; top: 18px; width: 14px; height: 14px; border-radius: 50%; background: var(--cb-primary); border: 3px solid #FFFDF9; box-shadow: 0 0 0 2px var(--cb-gold); }
            .cb-story-date-pill {
                display: inline-block; padding: 3px 11px; border-radius: 999px; background: rgba(139, 30, 43, 0.1);
                color: var(--cb-primary); font-size: 10.5px; font-weight: 700; letter-spacing: 1.5px; text-transform: uppercase; margin-bottom: 6px;
            }
            .cb-gallery-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px; }
            .cb-gallery-item {
                position: relative; border-radius: 16px; overflow: hidden; aspect-ratio: 3 / 4; cursor: pointer;
                border: 2.5px solid #FFFDF9; outline: 1px solid rgba(197, 155, 95, 0.55); box-shadow: 0 8px 22px rgba(92, 17, 27, 0.14); background: #480C14;
            }
            .cb-gallery-item.cb-featured { grid-column: span 2; aspect-ratio: 16 / 10; }
            .cb-gallery-item img { width: 100%; height: 100%; object-fit: cover; display: block; transition: transform 0.6s ease; }
            .cb-gallery-item:hover img { transform: scale(1.06); }
            .cb-atm-card {
                position: relative; background: linear-gradient(135deg, #7E1724 0%, #4E0C15 58%, #34060C 100%);
                color: #FFFDF9; border-radius: 20px; padding: 22px 20px; border: 1px solid rgba(232, 201, 143, 0.55);
                box-shadow: 0 14px 30px rgba(74, 12, 20, 0.3); overflow: hidden; margin-bottom: 16px;
            }
            .cb-atm-card::before {
                content: ''; position: absolute; top: -45%; right: -20%; width: 210px; height: 210px; border-radius: 50%;
                background: radial-gradient(circle, rgba(232, 201, 143, 0.22) 0%, transparent 70%); pointer-events: none;
            }
            .cb-atm-card::after { content: ''; position: absolute; inset: 6px; border-radius: 15px; border: 1px solid rgba(232, 201, 143, 0.22); pointer-events: none; }
            .cb-atm-number { font-family: 'Courier New', Courier, monospace; font-size: 1.22rem; font-weight: 700; letter-spacing: 2.2px; color: var(--cb-gold-light); margin: 12px 0 6px; }
            .cb-input, .cb-select, .cb-textarea {
                width: 100%; padding: 12px 14px; border-radius: 12px; border: 1px solid rgba(139, 30, 43, 0.24);
                background: rgba(255, 255, 255, 0.94); color: var(--cb-text); font-family: var(--cb-font-body); font-size: 13.5px;
                outline: none; transition: border-color 0.2s ease, box-shadow 0.2s ease;
            }
            .cb-input:focus, .cb-select:focus, .cb-textarea:focus { border-color: var(--cb-primary); box-shadow: 0 0 0 3px rgba(139, 30, 43, 0.12); }
            .cb-wish-card {
                background: rgba(255, 253, 249, 0.94); border: 1px solid rgba(197, 155, 95, 0.35); border-radius: 16px;
                padding: 14px 16px; margin-bottom: 10px; box-shadow: 0 4px 14px rgba(92, 17, 27, 0.05);
                animation: cbWishIn 0.45s cubic-bezier(0.22, 1, 0.36, 1) both;
            }
            /* Right-Edge Luxury Control Pill (Adopted from 3d-adat-bugis-02 pill.css, docked to 480px canvas edge) */
            .zipill-panel.zipill-luxury {
                position: fixed !important; top: 50% !important;
                right: max(0px, calc(50vw - 240px)) !important;
                bottom: auto !important; left: auto !important;
                transform: translateY(-50%) translateZ(0) !important; z-index: 9000 !important;
                display: flex; flex-direction: column; align-items: stretch; gap: 0;
                width: 46px; min-width: 44px; padding: 5px 4px; margin: 0 !important;
                border: 1px solid rgba(232, 201, 143, 0.28); border-right: 0;
                border-radius: 27px 0 0 27px;
                background: linear-gradient(180deg, rgba(92, 17, 27, 0.94), rgba(46, 6, 12, 0.92)) !important;
                color: #FFF8EF !important;
                box-shadow: -5px 7px 22px rgba(35, 5, 5, 0.26), inset 0 1px 0 rgba(255, 255, 255, 0.08);
                -webkit-backdrop-filter: blur(10px) saturate(115%); backdrop-filter: blur(10px) saturate(115%);
                overflow: visible !important;
            }
            .zipill-panel .zipill-button {
                appearance: none; -webkit-appearance: none; position: relative;
                display: flex; align-items: center; justify-content: center; flex: 0 0 auto;
                width: auto; height: 46px; min-height: 44px; margin: 2px 0; padding: 0;
                border: 0; border-radius: 999px; background: transparent !important;
                color: #FFF8EF !important; cursor: pointer; touch-action: manipulation;
                transition: background-color 0.22s ease, color 0.22s ease, box-shadow 0.22s ease, transform 0.18s ease;
            }
            .zipill-panel .zipill-button:hover,
            .zipill-panel .zipill-button:focus-visible,
            .zipill-panel .zipill-button[aria-pressed="true"] {
                background: rgba(232, 201, 143, 0.2) !important;
                color: #E8C98F !important;
                box-shadow: inset 0 0 0 1px rgba(232, 201, 143, 0.24), 0 3px 10px rgba(0, 0, 0, 0.12);
            }
            .zipill-panel .zipill-button:hover { transform: translateX(-1px); }
            .zipill-panel .zipill-button + .zipill-button::before {
                content: ""; position: absolute; top: -2px; left: 30%; right: 30%; height: 1px;
                background: currentColor; opacity: 0.14; pointer-events: none;
            }
            html body .zipill-panel .zipill-icon {
                display: block !important; width: 20px !important; height: 20px !important;
                opacity: 1 !important; visibility: visible !important; pointer-events: none;
            }
            html body .zipill-panel .zipill-icon,
            html body .zipill-panel .zipill-icon path,
            html body .zipill-panel .zipill-icon circle,
            html body .zipill-panel .zipill-icon ellipse,
            html body .zipill-panel .zipill-icon polygon {
                color: inherit !important; fill: none !important; stroke: currentColor !important;
                stroke-width: 1.65 !important; stroke-linecap: round; stroke-linejoin: round;
            }
            .zipill-panel .zipill-progress {
                width: 36px; height: 36px; position: absolute; top: 50%; left: 50%;
                transform: translate(-50%, -50%) rotate(-90deg); fill: none !important;
                stroke: currentColor !important; stroke-width: 1.2 !important; pointer-events: none;
            }
            .zipill-progress-track { fill: none !important; stroke: currentColor !important; opacity: 0.15; }
            .zipill-progress-value {
                fill: none !important; stroke: #E8C98F !important; opacity: 0.92;
                stroke-dasharray: 100; stroke-dashoffset: calc(100 - var(--zipill-progress, 0));
            }
            .zipill-status-dot {
                position: absolute; right: 7px; bottom: 7px; width: 5px; height: 5px;
                border-radius: 50%; background: #E8C98F; opacity: 0; pointer-events: none;
            }
            .zipill-button[aria-pressed="true"] .zipill-status-dot { opacity: 0.95; }
            .zipill-button[data-action="music"][aria-pressed="true"] .zipill-status-dot {
                animation: cbZipillPulse 2.2s ease-out infinite;
            }
            @keyframes cbZipillPulse {
                0%, 100% { box-shadow: 0 0 0 0 rgba(232, 201, 143, 0.38); opacity: 0.9; }
                55% { box-shadow: 0 0 0 5px rgba(232, 201, 143, 0); opacity: 1; }
            }
            .cb-speed-chip {
                position: absolute; right: calc(100% + 8px); top: 50%; transform: translateY(-50%);
                background: rgba(58, 10, 17, 0.96); color: #E8C98F;
                border: 1px solid rgba(232, 201, 143, 0.35); border-radius: 999px;
                padding: 5px 10px; font-size: 11px; font-weight: 700; cursor: pointer;
                box-shadow: 0 4px 14px rgba(0, 0, 0, 0.34); white-space: nowrap;
            }
            /* Collapsible Bottom Navigation Dock (Adopted from 3d-adat-bugis-02 .bugis-bottom-dock) */
            .cb-bottom-dock {
                position: fixed; bottom: 18px; left: 50%; transform: translateX(-50%);
                z-index: 9500; display: flex; align-items: center; gap: 4px; padding: 6px 12px;
                background: rgba(58, 10, 17, 0.95); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px);
                border: 1px solid rgba(232, 201, 143, 0.4); border-radius: 50px;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.55);
                max-width: calc(100vw - 74px); overflow-x: auto; scrollbar-width: none;
            }
            .cb-bottom-dock::-webkit-scrollbar { display: none; }
            .cb-bottom-dock .dock-btn {
                display: flex; flex-direction: column; align-items: center; justify-content: center;
                min-width: 46px; height: 44px; padding: 0 6px;
                color: #FFF8EF; background: transparent; border: none; border-radius: 50%;
                text-decoration: none; cursor: pointer; flex-shrink: 0;
                transition: background-color 0.2s ease, color 0.2s ease;
            }
            .cb-bottom-dock .dock-btn:hover,
            .cb-bottom-dock .dock-btn.active { background: rgba(232, 201, 143, 0.22); color: #E8C98F; }
            .cb-bottom-dock .dock-btn svg { width: 15px; height: 15px; margin-bottom: 2px; flex-shrink: 0; }
            .cb-bottom-dock .dock-btn span { font-size: 9px; font-family: var(--cb-font-body); font-weight: 600; letter-spacing: 0.01em; line-height: 1.1; }
            .cb-dock-close-btn {
                display: flex; align-items: center; justify-content: center;
                width: 32px; height: 32px; background: rgba(255, 255, 255, 0.12);
                color: #E8C98F; border: 1px solid rgba(232, 201, 143, 0.38);
                border-radius: 50%; margin-left: 4px; cursor: pointer; flex-shrink: 0;
                transition: background-color 0.2s ease, transform 0.2s ease;
            }
            .cb-dock-close-btn:hover { background: rgba(232, 201, 143, 0.24); transform: scale(1.05); }
            .cb-lightbox { position: fixed; inset: 0; z-index: 300; background: rgba(20, 3, 6, 0.92); backdrop-filter: blur(8px); display: flex; align-items: center; justify-content: center; padding: 20px; }
            .cb-lightbox img { max-width: 94vw; max-height: 84dvh; border-radius: 16px; border: 2px solid var(--cb-gold-light); box-shadow: 0 20px 50px rgba(0, 0, 0, 0.7); }
            .cb-toast {
                position: fixed; bottom: 80px; left: 50%; transform: translateX(-50%); z-index: 260;
                background: rgba(74, 12, 20, 0.96); color: #FFFDF9; border: 1px solid var(--cb-gold-light);
                padding: 10px 20px; border-radius: 999px; font-size: 12.5px; font-weight: 600; box-shadow: 0 10px 25px rgba(0, 0, 0, 0.45); white-space: nowrap;
            }
            /* Redesigned Luxury Crimson-Burgundy & Gold Footer Card */
            .cb-footer-wrap { position: relative; z-index: 10; padding: 8px 22px 106px; width: 100%; }
            .cb-footer-card {
                position: relative; overflow: hidden; text-align: center;
                background: linear-gradient(155deg, #741522 0%, #4A0B14 58%, #2C050A 100%);
                border: 1px solid rgba(232, 201, 143, 0.5); border-radius: 26px; padding: 28px 20px 24px;
                color: #FFFDF9; box-shadow: 0 18px 38px rgba(58, 10, 17, 0.28);
            }
            .cb-footer-card::before {
                content: ''; position: absolute; inset: 7px; border-radius: 20px;
                border: 1px dashed rgba(232, 201, 143, 0.28); pointer-events: none;
            }
            .cb-footer-card::after {
                content: ''; position: absolute; top: -50%; left: 50%; transform: translateX(-50%);
                width: 240px; height: 180px; border-radius: 50%;
                background: radial-gradient(circle, rgba(232, 201, 143, 0.16) 0%, transparent 72%); pointer-events: none;
            }
            .cb-footer-cta {
                display: inline-flex; align-items: center; justify-content: center; gap: 7px;
                margin-top: 14px; padding: 9px 20px; border-radius: 999px;
                background: rgba(232, 201, 143, 0.14); border: 1px solid rgba(232, 201, 143, 0.52);
                color: #F8E5BE; font-family: var(--cb-font-body); font-size: 11.5px; font-weight: 600;
                letter-spacing: 0.4px; text-decoration: none; transition: all 0.25s ease;
            }
            .cb-footer-cta:hover { background: var(--cb-gold); color: #2C050A; transform: translateY(-1px); }
        </style>
    @endpush
@endonce

@php
    $assetBase = 'themes/crimson-botanical/assets/';
    $styles = $invitation->custom_styles ?? [];
    $eventType = $invitation->event_type ?? ($styles['event_type'] ?? 'both');
    $nameOrder = $styles['name_order'] ?? 'groom_first';

    // 1. Couple Data & Ordering (100% parity with 3d-adat-bugis-02.blade.php)
    $groomNick = $invitation->groom_nickname ?: 'Arya';
    $groomFull = $invitation->groom_name ?: 'Arya Kusuma Wijaya';
    $brideNick = $invitation->bride_nickname ?: 'Sari';
    $brideFull = $invitation->bride_name ?: 'Sari Permata Dewi';

    $formatParents = function (?string $father, ?string $mother, string $defaultFather = 'Nama Ayah', string $defaultMother = 'Nama Ibu'): string {
        $f = trim((string) $father);
        $m = trim((string) $mother);
        if ($f === '' && $m === '') {
            return 'Bpk. '.$defaultFather.' & Ibu '.$defaultMother;
        }
        $fFormatted = $f !== '' ? (preg_match('/^(bpk|bapak|h\.|hj\.|prof\.|dr\.)/i', $f) ? $f : 'Bpk. '.$f) : '';
        $mFormatted = $m !== '' ? (preg_match('/^(ibu|ny\.|hj\.|prof\.|dr\.)/i', $m) ? $m : 'Ibu '.$m) : '';

        return collect([$fFormatted, $mFormatted])->filter()->implode(' & ');
    };

    $groomParents = $formatParents($invitation->groom_father, $invitation->groom_mother, 'Hendra Wijaya', 'Ratna Sari');
    $brideParents = $formatParents($invitation->bride_father, $invitation->bride_mother, 'Darmawan Dewi', 'Kartini Dewi');

    $groomIgRaw = ltrim(trim((string) ($invitation->instagram_groom ?? '')), '@');
    $brideIgRaw = ltrim(trim((string) ($invitation->instagram_bride ?? '')), '@');

    // On real saved invitations, only show uploaded photos (hide frame if null); in Demo mode, show luxury SVG artwork
    $groomPhotoUrl = $invitation->groom_photo
        ? img_url($invitation->groom_photo)
        : (! $invitation->exists ? asset($assetBase.'default-groom.svg') : null);
    $bridePhotoUrl = $invitation->bride_photo
        ? img_url($invitation->bride_photo)
        : (! $invitation->exists ? asset($assetBase.'default-bride.svg') : null);

    $groomChildLabel = trim((string) ($styles['groom_child_order'] ?? '')) !== ''
        ? trim((string) $styles['groom_child_order'])
        : 'Putra Pertama Dari';
    $brideChildLabel = trim((string) ($styles['bride_child_order'] ?? '')) !== ''
        ? trim((string) $styles['bride_child_order'])
        : 'Putri Pertama Dari';

    if ($nameOrder === 'bride_first') {
        $firstPerson = [
            'role' => 'Mempelai Wanita',
            'nick' => $brideNick,
            'full' => $brideFull,
            'photo' => $bridePhotoUrl,
            'childLabel' => $brideChildLabel,
            'parents' => $brideParents,
            'ig' => $brideIgRaw,
        ];
        $secondPerson = [
            'role' => 'Mempelai Pria',
            'nick' => $groomNick,
            'full' => $groomFull,
            'photo' => $groomPhotoUrl,
            'childLabel' => $groomChildLabel,
            'parents' => $groomParents,
            'ig' => $groomIgRaw,
        ];
        $firstNick = $brideNick;
        $secondNick = $groomNick;
    } else {
        $firstPerson = [
            'role' => 'Mempelai Pria',
            'nick' => $groomNick,
            'full' => $groomFull,
            'photo' => $groomPhotoUrl,
            'childLabel' => $groomChildLabel,
            'parents' => $groomParents,
            'ig' => $groomIgRaw,
        ];
        $secondPerson = [
            'role' => 'Mempelai Wanita',
            'nick' => $brideNick,
            'full' => $brideFull,
            'photo' => $bridePhotoUrl,
            'childLabel' => $brideChildLabel,
            'parents' => $brideParents,
            'ig' => $brideIgRaw,
        ];
        $firstNick = $groomNick;
        $secondNick = $brideNick;
    }

    $coverSubtitle = ! empty($styles['cover_subtitle']) ? $styles['cover_subtitle'] : 'The Wedding Of';
    $coupleTitle = ! empty($styles['cover_title']) ? $styles['cover_title'] : ($firstNick.' & '.$secondNick);
    $coverPhotoUrl = $invitation->cover_image
        ? img_url($invitation->cover_image)
        : (! $invitation->exists ? asset($assetBase.'default-cover.svg') : null);
    $hashtag = $styles['hashtag'] ?? $invitation->hashtag ?? ('#'.preg_replace('/\s+/', '', $firstNick.$secondNick));

    // 2. Dates, Countdown & Calendar (100% parity with 3d-adat-bugis-02.blade.php & Builder.php)
    $mainDateObj = $invitation->event_date;
    if (! $mainDateObj && ! empty($styles['event_date'])) {
        try {
            $mainDateObj = \Carbon\Carbon::parse($styles['event_date']);
        } catch (\Throwable $e) {
        }
    }
    if (! $mainDateObj) {
        if ($eventType === 'resepsi_only') {
            $mainDateObj = $invitation->resepsi_date ? \Carbon\Carbon::parse($invitation->resepsi_date) : null;
        } else {
            $mainDateObj = $invitation->akad_date
                ? \Carbon\Carbon::parse($invitation->akad_date)
                : ($invitation->resepsi_date ? \Carbon\Carbon::parse($invitation->resepsi_date) : null);
        }
    }
    $displayDate = $mainDateObj ? $mainDateObj->format('d . m . Y') : '03 . 02 . 2027';

    $mainTimeRaw = $eventType === 'resepsi_only'
        ? ($invitation->resepsi_time ?: '10:00:00')
        : ($invitation->akad_time ?: $invitation->resepsi_time ?: '08:00:00');
    $countdownDateObj = $mainDateObj ? $mainDateObj->copy() : \Carbon\Carbon::parse('2027-02-03');
    if (preg_match('/^(\d{1,2})[:.](\d{2})/', (string) $mainTimeRaw, $tm)) {
        $countdownDateObj->setTime((int) $tm[1], (int) $tm[2], 0);
    } else {
        $countdownDateObj->setTime(8, 0, 0);
    }
    $targetTimestamp = $countdownDateObj->timestamp;

    $mainAddress = ($eventType === 'resepsi_only')
        ? ($invitation->resepsi_address ?? $invitation->resepsi_venue ?? '')
        : ($invitation->akad_address ?? $invitation->akad_venue ?? $invitation->resepsi_address ?? '');

    $googleCalendarUrl = 'https://www.google.com/calendar/render?'.http_build_query([
        'action' => 'TEMPLATE',
        'text' => 'Pernikahan '.$coupleTitle,
        'dates' => $countdownDateObj->copy()->utc()->format('Ymd\THis\Z').'/'.$countdownDateObj->copy()->utc()->addHours(4)->format('Ymd\THis\Z'),
        'details' => 'Undangan Pernikahan '.$groomFull.' & '.$brideFull,
        'location' => $mainAddress,
    ]);

    $formatEventTime = function (?string $time, string $fallback = '08:00'): string {
        $t = trim((string) $time);
        if ($t === '') {
            return $fallback.' WIB - Selesai';
        }
        if (preg_match('/^(\d{1,2}:\d{2})$/', $t, $m)) {
            return $m[1].' WIB - Selesai';
        }

        return str_contains(strtolower($t), 'selesai') ? $t : ($t.' - Selesai');
    };

    // 3. Background Music (resolves music_id via Invitation accessor + uploaded path + bg-music.mp3 fallback so music icon ALWAYS appears)
    $musicRaw = $invitation->background_music ?? $invitation->music_url ?? null;
    if (! empty($musicRaw)) {
        $musicSrc = str_starts_with((string) $musicRaw, 'http')
            ? $musicRaw
            : img_url($musicRaw);
    } else {
        $musicSrc = asset($assetBase.'bg-music.mp3');
    }
    $musicExt = strtolower(pathinfo((string) parse_url((string) $musicSrc, PHP_URL_PATH), PATHINFO_EXTENSION));
    $musicMime = match ($musicExt) {
        'wav' => 'audio/wav',
        'ogg' => 'audio/ogg',
        'm4a', 'mp4' => 'audio/mp4',
        'aac' => 'audio/aac',
        'webm' => 'audio/webm',
        default => 'audio/mpeg',
    };

    // 4. Event & Feature Visibility (supports 'both', 'akad_only', 'resepsi_only', plus 'akad'/'resepsi' aliases)
    $showAkad = in_array($eventType, ['akad_only', 'akad', 'both'], true)
        && ($invitation->akad_date || $invitation->akad_venue || $invitation->akad_maps_link || ! isset($styles['event_type']));
    $showResepsi = in_array($eventType, ['resepsi_only', 'resepsi', 'both'], true)
        && ($invitation->resepsi_date || $invitation->resepsi_venue || $invitation->resepsi_maps_link || ! isset($styles['event_type']));
    $unduhDate = $styles['unduh_mantu_date'] ?? $invitation->unduh_mantu_date ?? null;
    $unduhVenue = $styles['unduh_mantu_venue'] ?? $invitation->unduh_mantu_venue ?? null;
    $showUnduhMantu = ! empty($unduhDate) || ! empty($unduhVenue);
    $streamingLink = trim((string) ($styles['streaming_link'] ?? $styles['livestream_url'] ?? $invitation->streaming_link ?? ''));
    $showStreaming = $streamingLink !== '';
    $mapsEmbed = trim((string) ($styles['maps_embed'] ?? ''));

    $showCountdown = (bool) ($styles['countdown_enabled'] ?? true);
    $showGallery = (bool) ($invitation->enable_gallery ?? true);
    $showGift = (bool) ($invitation->enable_gift ?? ! empty($invitation->bank_accounts));
    $showRsvp = (bool) (($invitation->enable_rsvp ?? true) || ($invitation->enable_wishes ?? true));

    // 5. Text Blocks & Quote (100% parity with Builder.php & 3d-adat-bugis-02.blade.php)
    $welcomeMessage = ! empty($styles['welcome_message'])
        ? $styles['welcome_message']
        : 'Dengan memohon rahmat dan ridho Allah Swt. kami bermaksud mengundang Bapak/Ibu/Saudara/i untuk menghadiri acara pernikahan putra-putri kami:';
    $quoteTitle = trim((string) ($styles['quote_title'] ?? '')) !== ''
        ? $styles['quote_title']
        : 'WE FOUND LOVE';
    $quranVerse = trim((string) ($styles['quran_verse'] ?? $styles['quote_text'] ?? '')) !== ''
        ? ($styles['quran_verse'] ?? $styles['quote_text'])
        : '“Dan di antara tanda-tanda (kebesaran)-Nya ialah Dia menciptakan pasangan-pasangan untukmu dari jenismu sendiri, agar kamu cenderung dan merasa tenteram kepadanya, dan Dia menjadikan di antaramu rasa kasih dan sayang.”';
    $quranRef = array_key_exists('quran_ref', $styles)
        ? trim((string) ($styles['quran_ref'] ?? ''))
        : (trim((string) ($styles['quote_source'] ?? '')) !== '' ? trim((string) $styles['quote_source']) : 'Q.S Ar-Rum : 21');
    $quoteImageRaw = $styles['quote_image'] ?? null;
    $quotePhotoUrl = ! empty($quoteImageRaw) ? img_url($quoteImageRaw) : null;

    // 6. Gallery Photos & Optional YouTube Embed
    $uploadedPhotos = $invitation->relationLoaded('photos') ? $invitation->photos : $invitation->photos()->get();
    $galleryPhotos = [];
    if ($uploadedPhotos->count() > 0) {
        $galleryPhotos = $uploadedPhotos->map(fn ($p) => img_url($p->path))->values()->all();
    } elseif (is_array($invitation->gallery_images) && count($invitation->gallery_images) > 0) {
        $galleryPhotos = collect($invitation->gallery_images)->filter()->map(fn ($img) => img_url($img))->values()->all();
    } elseif (! $invitation->exists) {
        $galleryPhotos = [
            asset($assetBase.'default-gallery-1.svg'),
            asset($assetBase.'default-gallery-2.svg'),
            asset($assetBase.'default-gallery-3.svg'),
        ];
    }
    $youtubeUrl = trim((string) ($styles['youtube_url'] ?? ''));
    $showGallery = $showGallery && (count($galleryPhotos) > 0 || $youtubeUrl !== '');

    // 7. Love Story (supports both 'description' from Builder.php and 'story' from DemoPage.php + optional photo)
    $defaultStories = [
        ['date' => '2023', 'title' => 'Awal yang Gak Disangka', 'description' => 'Semua berawal dari hal sederhana—entah itu chat random, ketemu tanpa sengaja, atau cuma “iseng kenal”. Tapi dari situ, perlahan tumbuh rasa yang gak pernah direncanakan.'],
        ['date' => '2024', 'title' => 'Makin Dekat, Makin Nyaman', 'description' => 'Hari-hari mulai diisi dengan cerita, tawa, dan perhatian kecil. Dari yang awalnya biasa aja, jadi tempat pulang paling nyaman.'],
        ['date' => '2025', 'title' => 'Melewati Segalanya Bareng', 'description' => 'Gak selalu mulus, ada beda pendapat, ada jarak, ada ujian. Tapi justru di situ, cinta makin kuat karena selalu pilih untuk bertahan.'],
        ['date' => '2026', 'title' => 'Memilih Selamanya', 'description' => 'Sampai akhirnya sadar, “ini orangnya.” Bukan cuma untuk sekarang, tapi untuk semua hari ke depan. Dari dua cerita, jadi satu perjalanan selamanya.'],
    ];
    $filteredStories = (is_array($invitation->love_story) && ! empty($invitation->love_story))
        ? array_values(array_filter($invitation->love_story, function ($story) {
            if (! is_array($story)) {
                return false;
            }

            return trim((string) ($story['title'] ?? '')) !== ''
                || trim((string) ($story['description'] ?? $story['story'] ?? '')) !== ''
                || trim((string) ($story['date'] ?? '')) !== '';
        }))
        : [];
    $rawStories = ! empty($filteredStories)
        ? $filteredStories
        : (! $invitation->exists ? $defaultStories : []);
    $loveStories = collect($rawStories)->values()->map(function ($story, $idx) use ($uploadedPhotos) {
        $fallbackImg = $uploadedPhotos->count() > 0 ? img_url($uploadedPhotos[$idx % $uploadedPhotos->count()]->path) : null;
        $desc = trim((string) ($story['description'] ?? $story['story'] ?? $story['text'] ?? ''));

        return [
            'date' => trim((string) ($story['date'] ?? '')),
            'title' => trim((string) ($story['title'] ?? '')) !== '' ? trim((string) $story['title']) : 'Momen Spesial',
            'description' => $desc,
            'image' => ! empty($story['image']) ? $story['image'] : $fallbackImg,
        ];
    })->all();
    $showLoveStory = count($loveStories) > 0;

    // 8. Wedding Gift Accounts & Physical Gift Address
    $giftAddress = trim((string) ($styles['gift_address'] ?? $invitation->gift_address ?? $mainAddress));
    $defaultBanks = [
        ['bank' => 'BCA', 'account_number' => '1234567890', 'account_name' => $groomFull],
        ['bank' => 'BRI', 'account_number' => '0987654321', 'account_name' => $brideFull],
    ];
    $bankAccounts = (! empty($invitation->bank_accounts) && is_array($invitation->bank_accounts))
        ? array_values(array_filter($invitation->bank_accounts, fn ($acc) => is_array($acc) && (! empty($acc['account_number']) || ! empty($acc['bank']))))
        : ($showGift && ! $invitation->exists ? $defaultBanks : []);
    $showGift = $showGift && (count($bankAccounts) > 0 || $giftAddress !== '');

    $resolveBankLogo = function (?string $bankName) use ($assetBase): ?string {
        $b = strtolower(trim((string) $bankName));
        if (str_contains($b, 'bca')) {
            return asset($assetBase.'bank-bca.png');
        }
        if (str_contains($b, 'bri')) {
            return asset($assetBase.'bank-bri.png');
        }

        return null;
    };

    $wishesList = $wishes ?? collect([]);
    $wishesCount = $totalWishesCount ?? $wishesList->count();
@endphp

<div class="cb-root" style="--cb-top-offset: {{ ! $invitation->exists ? '44px' : '0px' }};" x-data="crimsonBotanicalTheme({{ $targetTimestamp }})">
    {{-- Background Audio (Always Present with User Music or Default Fallback so Music Icon Always Works) --}}
    <div wire:ignore style="position:fixed;width:0;height:0;overflow:hidden;opacity:0;pointer-events:none;" aria-hidden="true">
        <audio id="cbBgAudio" loop preload="none" playsinline webkit-playsinline src="{{ $musicSrc }}">
            <source src="{{ $musicSrc }}" type="{{ $musicMime }}">
        </audio>
    </div>

    {{-- Fixed 4-Corner Botanical Frame Around Viewport (Frames Both Cover & Main Stage Consistently) --}}
    <div class="cb-viewport-deco" aria-hidden="true">
        <img class="cb-deco-tl" src="{{ asset($assetBase.'floral-tl.png') }}" alt="" decoding="async">
        <img class="cb-deco-tr" src="{{ asset($assetBase.'floral-tr.png') }}" alt="" decoding="async">
        <img class="cb-deco-bl" src="{{ asset($assetBase.'floral-bl.png') }}" alt="" decoding="async">
        <img class="cb-deco-br" src="{{ asset($assetBase.'floral-br.png') }}" alt="" decoding="async">
    </div>

    {{-- Delicate Ambient Rose Petals, Sparkle Dust & Line-Art Butterflies --}}
    <div class="cb-ambient-layer" aria-hidden="true">
        <div class="cb-petal p1"><svg viewBox="0 0 24 24"><path d="M12 2C7 5 4 11 6 17c2 4 8 5 11 1 4-5 1-12-5-16z" fill="#C96A76" fill-opacity="0.45"/></svg></div>
        <div class="cb-petal p2"><svg viewBox="0 0 24 24"><path d="M12 2C7 5 4 11 6 17c2 4 8 5 11 1 4-5 1-12-5-16z" fill="#DFB895" fill-opacity="0.5"/></svg></div>
        <div class="cb-petal p3"><svg viewBox="0 0 24 24"><path d="M12 2C7 5 4 11 6 17c2 4 8 5 11 1 4-5 1-12-5-16z" fill="#C96A76" fill-opacity="0.4"/></svg></div>
        <div class="cb-petal p4"><svg viewBox="0 0 24 24"><path d="M12 2C7 5 4 11 6 17c2 4 8 5 11 1 4-5 1-12-5-16z" fill="#DFB895" fill-opacity="0.45"/></svg></div>

        <span class="cb-sparkle s1"></span>
        <span class="cb-sparkle s2"></span>
        <span class="cb-sparkle s3"></span>
        <span class="cb-sparkle s4"></span>

        <div class="cb-butterfly cb-bf-1">
            <svg viewBox="0 0 24 24">
                <path class="cb-wing-l" d="M12 12C8.5 3.5 1.5 3 2 8.5c.4 4.2 5.2 6.8 10 3.5Z" fill="#C59B5F" fill-opacity="0.45" stroke="#8B1E2B" stroke-opacity="0.55" stroke-width="0.8"/>
                <path class="cb-wing-r" d="M12 12c3.5-8.5 10.5-9 10-3.5-.4 4.2-5.2 6.8-10 3.5Z" fill="#C59B5F" fill-opacity="0.45" stroke="#8B1E2B" stroke-opacity="0.55" stroke-width="0.8"/>
                <line x1="12" y1="8" x2="12" y2="16" stroke="#8B1E2B" stroke-width="1" stroke-linecap="round"/>
            </svg>
        </div>
        <div class="cb-butterfly cb-bf-2">
            <svg viewBox="0 0 24 24">
                <path class="cb-wing-l" d="M12 12C8.5 3.5 1.5 3 2 8.5c.4 4.2 5.2 6.8 10 3.5Z" fill="#D8A7B1" fill-opacity="0.5" stroke="#8B1E2B" stroke-opacity="0.5" stroke-width="0.8"/>
                <path class="cb-wing-r" d="M12 12c3.5-8.5 10.5-9 10-3.5-.4 4.2-5.2 6.8-10 3.5Z" fill="#D8A7B1" fill-opacity="0.5" stroke="#8B1E2B" stroke-opacity="0.5" stroke-width="0.8"/>
                <line x1="12" y1="8" x2="12" y2="16" stroke="#8B1E2B" stroke-width="1" stroke-linecap="round"/>
            </svg>
        </div>
    </div>

    {{-- 1. COVER GATE OVERLAY --}}
    <div id="cb-cover-gate" :class="{ 'cb-gate-opened': isOpened }">
        <div class="cb-cover-card">
            <div class="cb-cover-inner">
                @if($coverPhotoUrl)
                    <div class="cb-arch-wrap">
                        <svg class="cb-arch-crest" viewBox="0 0 120 36" fill="none">
                            <path d="M12 28 Q60 6 108 28" stroke="#C59B5F" stroke-width="1.5" stroke-opacity="0.75"/>
                            <polygon points="60,4 66,14 60,24 54,14" fill="#C59B5F"/>
                            <circle cx="42" cy="17" r="2.5" fill="#8B1E2B"/>
                            <circle cx="78" cy="17" r="2.5" fill="#8B1E2B"/>
                        </svg>
                        <div class="cb-arch-frame">
                            <img src="{{ $coverPhotoUrl }}" alt="{{ $coupleTitle }}" fetchpriority="high" decoding="async">
                        </div>
                        <svg class="cb-arch-garland" viewBox="0 0 240 60" fill="none">
                            <path d="M18 32 C62 46 178 46 222 32" stroke="#C59B5F" stroke-width="1.6"/>
                            <path d="M72 36 C48 26 28 30 14 42 C34 44 54 42 72 36 Z" fill="#4D6E5D" fill-opacity="0.9"/>
                            <path d="M168 36 C192 26 212 30 226 42 C206 44 186 42 168 36 Z" fill="#4D6E5D" fill-opacity="0.9"/>
                            <path d="M84 32 C62 18 42 18 26 28 C44 32 64 34 84 32 Z" fill="#C59B5F" fill-opacity="0.78"/>
                            <path d="M156 32 C178 18 198 18 214 28 C196 32 176 34 156 32 Z" fill="#C59B5F" fill-opacity="0.78"/>
                            <circle cx="88" cy="34" r="11" fill="#F5E1D8" stroke="#C59B5F" stroke-width="1.2"/>
                            <circle cx="152" cy="34" r="11" fill="#F5E1D8" stroke="#C59B5F" stroke-width="1.2"/>
                            <circle cx="120" cy="32" r="15" fill="#8B1E2B" stroke="#E8C98F" stroke-width="1.5"/>
                            <path d="M113 29 C117 22 126 23 128 30 C129 36 121 40 116 37 C112 34 114 28 119 28" stroke="#FAF0E1" stroke-width="1.4"/>
                            <circle cx="120" cy="32" r="2" fill="#E8C98F"/>
                        </svg>
                    </div>
                @else
                    <div class="cb-monogram-crest" aria-hidden="true">
                        {{ mb_substr($firstNick, 0, 1) }}&amp;{{ mb_substr($secondNick, 0, 1) }}
                    </div>
                @endif

                <div class="cb-eyebrow">{{ $coverSubtitle }}</div>
                <h1 class="cb-script-title">{{ $coupleTitle }}</h1>

                <div class="cb-ornament-divider" aria-hidden="true">
                    <span class="cb-ornament-diamond"></span>
                </div>

                {{-- Structured Translucent Guest Card Box --}}
                <div class="cb-cover-guest-card">
                    <p style="font-size:11.5px;color:var(--cb-muted);margin:0 0 3px;letter-spacing:0.3px;">Kepada Yth: Bapak/Ibu/Saudara/i</p>
                    <div style="font-family:var(--cb-font-heading);font-size:1.38rem;font-weight:700;color:var(--cb-primary);line-height:1.25;margin-bottom:4px;word-break:break-word;">
                        {{ $guestName ?? 'Tamu Undangan' }}
                    </div>
                    <p style="font-size:10px;color:var(--cb-muted);font-style:italic;margin:0;opacity:0.85;">
                        *Mohon maaf apabila terdapat kesalahan penulisan nama/gelar.
                    </p>
                </div>

                <button type="button" @click="openInvitation()" class="cb-btn-primary cb-btn-pulse">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    <span>Buka Undangan</span>
                </button>
            </div>
        </div>
    </div>

    {{-- MAIN SCROLLABLE INVITATION STAGE --}}
    <main class="cb-stage">
        {{-- SECTION 1: HERO (Consistent with Cover & 3d-adat-bugis-02 Hero) --}}
        <section id="cb-hero" class="cb-section" style="min-height:100dvh;display:flex;flex-direction:column;justify-content:center;align-items:center;text-align:center;padding-top:64px;padding-bottom:68px;">
            <div class="cb-reveal cb-from-scale" data-reveal="hero-arch" :class="{ 'cb-revealed': isOpened || revealed['hero-arch'] }">
                @if($coverPhotoUrl)
                    <div class="cb-arch-wrap" style="width:196px;height:256px;margin-bottom:22px;">
                        <svg class="cb-arch-crest" viewBox="0 0 120 36" fill="none">
                            <path d="M12 28 Q60 6 108 28" stroke="#C59B5F" stroke-width="1.5" stroke-opacity="0.75"/>
                            <polygon points="60,4 66,14 60,24 54,14" fill="#C59B5F"/>
                            <circle cx="42" cy="17" r="2.5" fill="#8B1E2B"/>
                            <circle cx="78" cy="17" r="2.5" fill="#8B1E2B"/>
                        </svg>
                        <div class="cb-arch-frame">
                            <img src="{{ $coverPhotoUrl }}" alt="{{ $coupleTitle }}" decoding="async">
                        </div>
                        <svg class="cb-arch-garland" viewBox="0 0 240 60" fill="none">
                            <path d="M18 32 C62 46 178 46 222 32" stroke="#C59B5F" stroke-width="1.6"/>
                            <path d="M72 36 C48 26 28 30 14 42 C34 44 54 42 72 36 Z" fill="#4D6E5D" fill-opacity="0.9"/>
                            <path d="M168 36 C192 26 212 30 226 42 C206 44 186 42 168 36 Z" fill="#4D6E5D" fill-opacity="0.9"/>
                            <path d="M84 32 C62 18 42 18 26 28 C44 32 64 34 84 32 Z" fill="#C59B5F" fill-opacity="0.78"/>
                            <path d="M156 32 C178 18 198 18 214 28 C196 32 176 34 156 32 Z" fill="#C59B5F" fill-opacity="0.78"/>
                            <circle cx="88" cy="34" r="11" fill="#F5E1D8" stroke="#C59B5F" stroke-width="1.2"/>
                            <circle cx="152" cy="34" r="11" fill="#F5E1D8" stroke="#C59B5F" stroke-width="1.2"/>
                            <circle cx="120" cy="32" r="15" fill="#8B1E2B" stroke="#E8C98F" stroke-width="1.5"/>
                            <path d="M113 29 C117 22 126 23 128 30 C129 36 121 40 116 37 C112 34 114 28 119 28" stroke="#FAF0E1" stroke-width="1.4"/>
                        </svg>
                    </div>
                @else
                    <div class="cb-monogram-crest" aria-hidden="true">
                        {{ mb_substr($firstNick, 0, 1) }}&amp;{{ mb_substr($secondNick, 0, 1) }}
                    </div>
                @endif
            </div>

            <div class="cb-reveal" data-reveal="hero-title" :class="{ 'cb-revealed': isOpened || revealed['hero-title'] }">
                <div class="cb-eyebrow">{{ $coverSubtitle }}</div>
                <h2 class="cb-script-title" style="font-size:clamp(2.65rem, 11vw, 3.45rem);">{{ $firstNick }} &amp; {{ $secondNick }}</h2>
                <div class="cb-ornament-divider" aria-hidden="true">
                    <span class="cb-ornament-diamond"></span>
                </div>
                <div class="cb-date-pill">
                    <svg width="14" height="14" fill="none" stroke="var(--cb-primary)" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span>{{ $displayDate }}</span>
                </div>
                @if($hashtag !== '')
                    <div style="font-size:12px;font-weight:600;color:var(--cb-muted);letter-spacing:1px;margin-top:10px;">
                        {{ $hashtag }}
                    </div>
                @endif
            </div>

        </section>

        {{-- SECTION 2: QUOTE (Matches 3d-adat-bugis-02 quote_image, quote_title, quran_verse, quran_ref) --}}
        <section id="cb-quote" class="cb-section" style="padding-top:16px;padding-bottom:36px;">
            <div class="cb-quote-card cb-reveal" data-reveal="quote" :class="{ 'cb-revealed': revealed['quote'] }">
                @if($quotePhotoUrl)
                    <div class="cb-arch-wrap" style="width:168px;height:218px;margin-bottom:20px;">
                        <div class="cb-arch-frame">
                            <img src="{{ $quotePhotoUrl }}" alt="{{ $coupleTitle }}" loading="lazy" decoding="async">
                        </div>
                    </div>
                @endif
                <div style="width:42px;height:42px;border-radius:50%;margin:0 auto 10px;background:rgba(139,30,43,0.08);border:1px solid rgba(197,155,95,0.45);display:flex;align-items:center;justify-content:center;color:var(--cb-primary);">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/>
                    </svg>
                </div>
                <div class="cb-eyebrow" style="margin-bottom:10px;">{{ $quoteTitle }}</div>
                <div style="font-family:var(--cb-font-heading);font-size:1.12rem;font-style:italic;line-height:1.82;color:var(--cb-text);margin:0 0 16px;">
                    {!! nl2br(e($quranVerse)) !!}
                </div>
                @if($quranRef !== '')
                    <div class="cb-ornament-divider" aria-hidden="true" style="margin-bottom:12px;">
                        <span class="cb-ornament-diamond"></span>
                    </div>
                    <span class="cb-quote-badge">{{ $quranRef }}</span>
                @endif
            </div>
        </section>

        {{-- SECTION 3: MEMPELAI (Matches 3d-adat-bugis-02 welcome_message, name_order, child_order, parents, ig) --}}
        <section id="cb-couple" class="cb-section">
            <div class="cb-section-header cb-reveal" data-reveal="couple-hdr" :class="{ 'cb-revealed': revealed['couple-hdr'] }">
                <div class="cb-eyebrow">Assalamualaikum Warahmatullaahi Wabarakaatuh</div>
                <h2 class="cb-script-title" style="font-size:2.6rem;">Mempelai</h2>
                <div class="cb-ornament-divider" aria-hidden="true">
                    <span class="cb-ornament-diamond"></span>
                </div>
                <p style="font-size:13px;color:var(--cb-muted);line-height:1.75;max-width:360px;margin:8px auto 0;">
                    {{ $welcomeMessage }}
                </p>
            </div>

            {{-- First Person Card --}}
            <div class="cb-couple-card cb-reveal" data-reveal="couple-p1" :class="{ 'cb-revealed': revealed['couple-p1'] }">
                <span class="cb-couple-role-pill">{{ $firstPerson['role'] }}</span>
                @if(! empty($firstPerson['photo']))
                    <div class="cb-couple-frame-wrap">
                        <div class="cb-couple-arch">
                            <img src="{{ $firstPerson['photo'] }}" alt="{{ $firstPerson['full'] }}" loading="lazy" decoding="async">
                        </div>
                        <svg class="cb-couple-garland" viewBox="0 0 200 50" fill="none">
                            <path d="M24 28 C60 40 140 40 176 28" stroke="#C59B5F" stroke-width="1.5"/>
                            <path d="M68 30 C48 22 32 24 20 34 C36 36 52 34 68 30 Z" fill="#4D6E5D"/>
                            <path d="M132 30 C152 22 168 24 180 34 C164 36 148 34 132 30 Z" fill="#4D6E5D"/>
                            <circle cx="76" cy="29" r="9" fill="#F5E1D8" stroke="#C59B5F" stroke-width="1.1"/>
                            <circle cx="124" cy="29" r="9" fill="#F5E1D8" stroke="#C59B5F" stroke-width="1.1"/>
                            <circle cx="100" cy="27" r="12" fill="#8B1E2B" stroke="#E8C98F" stroke-width="1.3"/>
                            <circle cx="100" cy="27" r="2" fill="#E8C98F"/>
                        </svg>
                    </div>
                @endif
                <div class="cb-script-title" style="font-size:2.25rem;margin-top:2px;">{{ $firstPerson['nick'] }}</div>
                <h3 class="cb-serif-title" style="margin:2px 0 6px;color:var(--cb-primary);">{{ $firstPerson['full'] }}</h3>
                <p style="font-size:12px;color:var(--cb-muted);margin:0 0 3px;">{{ $firstPerson['childLabel'] }}</p>
                <p style="font-size:13.5px;font-weight:600;color:var(--cb-text);margin:0 0 12px;">{{ $firstPerson['parents'] }}</p>
                @if(! empty($firstPerson['ig']))
                    <a href="https://www.instagram.com/{{ $firstPerson['ig'] }}" target="_blank" rel="noopener noreferrer" class="cb-btn-outline" style="padding:7px 16px;font-size:11.5px;">
                        <span>@ {{ $firstPerson['ig'] }}</span>
                    </a>
                @endif
            </div>

            <div class="cb-ampersand-badge cb-reveal" data-reveal="couple-amp" :class="{ 'cb-revealed': revealed['couple-amp'] }">&amp;</div>

            {{-- Second Person Card --}}
            <div class="cb-couple-card cb-reveal" data-reveal="couple-p2" :class="{ 'cb-revealed': revealed['couple-p2'] }">
                <span class="cb-couple-role-pill">{{ $secondPerson['role'] }}</span>
                @if(! empty($secondPerson['photo']))
                    <div class="cb-couple-frame-wrap">
                        <div class="cb-couple-arch">
                            <img src="{{ $secondPerson['photo'] }}" alt="{{ $secondPerson['full'] }}" loading="lazy" decoding="async">
                        </div>
                        <svg class="cb-couple-garland" viewBox="0 0 200 50" fill="none">
                            <path d="M24 28 C60 40 140 40 176 28" stroke="#C59B5F" stroke-width="1.5"/>
                            <path d="M68 30 C48 22 32 24 20 34 C36 36 52 34 68 30 Z" fill="#4D6E5D"/>
                            <path d="M132 30 C152 22 168 24 180 34 C164 36 148 34 132 30 Z" fill="#4D6E5D"/>
                            <circle cx="76" cy="29" r="9" fill="#F5E1D8" stroke="#C59B5F" stroke-width="1.1"/>
                            <circle cx="124" cy="29" r="9" fill="#F5E1D8" stroke="#C59B5F" stroke-width="1.1"/>
                            <circle cx="100" cy="27" r="12" fill="#8B1E2B" stroke="#E8C98F" stroke-width="1.3"/>
                            <circle cx="100" cy="27" r="2" fill="#E8C98F"/>
                        </svg>
                    </div>
                @endif
                <div class="cb-script-title" style="font-size:2.25rem;margin-top:2px;">{{ $secondPerson['nick'] }}</div>
                <h3 class="cb-serif-title" style="margin:2px 0 6px;color:var(--cb-primary);">{{ $secondPerson['full'] }}</h3>
                <p style="font-size:12px;color:var(--cb-muted);margin:0 0 3px;">{{ $secondPerson['childLabel'] }}</p>
                <p style="font-size:13.5px;font-weight:600;color:var(--cb-text);margin:0 0 12px;">{{ $secondPerson['parents'] }}</p>
                @if(! empty($secondPerson['ig']))
                    <a href="https://www.instagram.com/{{ $secondPerson['ig'] }}" target="_blank" rel="noopener noreferrer" class="cb-btn-outline" style="padding:7px 16px;font-size:11.5px;">
                        <span>@ {{ $secondPerson['ig'] }}</span>
                    </a>
                @endif
            </div>
        </section>

        {{-- SECTION 4: RANGKAIAN ACARA (Matches 3d-adat-bugis-02 Akad, Resepsi, Unduh Mantu, Live Streaming, Maps Embed) --}}
        <section id="cb-event" class="cb-section">
            <div class="cb-section-header cb-reveal" data-reveal="event-hdr" :class="{ 'cb-revealed': revealed['event-hdr'] }">
                <div class="cb-eyebrow">Save The Date</div>
                <h2 class="cb-script-title" style="font-size:2.6rem;">Rangkaian Acara</h2>
                <div class="cb-ornament-divider" aria-hidden="true">
                    <span class="cb-ornament-diamond"></span>
                </div>
            </div>

            @if($showAkad)
                <div class="cb-event-card cb-reveal" data-reveal="event-akad" :class="{ 'cb-revealed': revealed['event-akad'] }">
                    <div class="cb-event-icon-circle">
                        <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                    </div>
                    <div class="cb-eyebrow" style="font-size:9.5px;letter-spacing:2.5px;">Sacred Vows</div>
                    <div class="cb-script-title" style="font-size:2.2rem;">Akad Nikah</div>

                    <div class="cb-event-meta-grid">
                        <div style="border-right:1px solid rgba(197,155,95,0.3);padding-right:6px;">
                            <div style="font-size:10px;text-transform:uppercase;letter-spacing:1.2px;color:var(--cb-muted);margin-bottom:3px;">
                                {{ $invitation->akad_date ? \Carbon\Carbon::parse($invitation->akad_date)->locale('id')->isoFormat('dddd') : 'Senin' }}
                            </div>
                            <div style="font-family:var(--cb-font-heading);font-size:15px;font-weight:700;color:var(--cb-primary);">
                                {{ $invitation->akad_date ? \Carbon\Carbon::parse($invitation->akad_date)->locale('id')->isoFormat('D MMMM Y') : '10 Agustus 2026' }}
                            </div>
                        </div>
                        <div style="padding-left:6px;">
                            <div style="font-size:10px;text-transform:uppercase;letter-spacing:1.2px;color:var(--cb-muted);margin-bottom:3px;">Waktu</div>
                            <div style="font-family:var(--cb-font-heading);font-size:14.5px;font-weight:700;color:var(--cb-primary);">
                                {{ $formatEventTime($invitation->akad_time, '08:00') }}
                            </div>
                        </div>
                    </div>

                    <div style="font-size:14.5px;font-weight:700;color:var(--cb-text);margin-bottom:4px;">
                        {{ $invitation->akad_venue ?: 'Kediaman Mempelai Wanita' }}
                    </div>
                    <div style="font-size:12.5px;color:var(--cb-muted);line-height:1.6;margin-bottom:16px;">
                        {{ $invitation->akad_address ?: 'Lokasi akan diinformasikan lebih lanjut.' }}
                    </div>
                    @if(! empty($invitation->akad_maps_link))
                        <a href="{{ $invitation->akad_maps_link }}" target="_blank" rel="noopener noreferrer" class="cb-btn-outline">
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span>Open Maps</span>
                        </a>
                    @endif
                </div>
            @endif

            @if($showResepsi)
                <div class="cb-event-card cb-reveal" data-reveal="event-resepsi" :class="{ 'cb-revealed': revealed['event-resepsi'] }">
                    <div class="cb-event-icon-circle">
                        <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                        </svg>
                    </div>
                    <div class="cb-eyebrow" style="font-size:9.5px;letter-spacing:2.5px;">Wedding Reception</div>
                    <div class="cb-script-title" style="font-size:2.2rem;">Resepsi</div>

                    <div class="cb-event-meta-grid">
                        <div style="border-right:1px solid rgba(197,155,95,0.3);padding-right:6px;">
                            <div style="font-size:10px;text-transform:uppercase;letter-spacing:1.2px;color:var(--cb-muted);margin-bottom:3px;">
                                {{ $invitation->resepsi_date ? \Carbon\Carbon::parse($invitation->resepsi_date)->locale('id')->isoFormat('dddd') : 'Rabu' }}
                            </div>
                            <div style="font-family:var(--cb-font-heading);font-size:15px;font-weight:700;color:var(--cb-primary);">
                                {{ $invitation->resepsi_date ? \Carbon\Carbon::parse($invitation->resepsi_date)->locale('id')->isoFormat('D MMMM Y') : '26 Agustus 2026' }}
                            </div>
                        </div>
                        <div style="padding-left:6px;">
                            <div style="font-size:10px;text-transform:uppercase;letter-spacing:1.2px;color:var(--cb-muted);margin-bottom:3px;">Waktu</div>
                            <div style="font-family:var(--cb-font-heading);font-size:14.5px;font-weight:700;color:var(--cb-primary);">
                                {{ $formatEventTime($invitation->resepsi_time, '10:00') }}
                            </div>
                        </div>
                    </div>

                    <div style="font-size:14.5px;font-weight:700;color:var(--cb-text);margin-bottom:4px;">
                        {{ $invitation->resepsi_venue ?: 'Kediaman Mempelai Wanita' }}
                    </div>
                    <div style="font-size:12.5px;color:var(--cb-muted);line-height:1.6;margin-bottom:16px;">
                        {{ $invitation->resepsi_address ?: 'Lokasi akan diinformasikan lebih lanjut.' }}
                    </div>
                    @if(! empty($invitation->resepsi_maps_link))
                        <a href="{{ $invitation->resepsi_maps_link }}" target="_blank" rel="noopener noreferrer" class="cb-btn-outline">
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span>Open Maps</span>
                        </a>
                    @endif
                </div>
            @endif

            @if($showUnduhMantu)
                <div class="cb-event-card cb-reveal" data-reveal="event-unduh" :class="{ 'cb-revealed': revealed['event-unduh'] }">
                    <div class="cb-event-icon-circle">
                        <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3"/>
                        </svg>
                    </div>
                    <div class="cb-eyebrow" style="font-size:9.5px;letter-spacing:2.5px;">Family Celebration</div>
                    <div class="cb-script-title" style="font-size:2.2rem;">Unduh Mantu</div>

                    <div class="cb-event-meta-grid">
                        <div style="border-right:1px solid rgba(197,155,95,0.3);padding-right:6px;">
                            <div style="font-size:10px;text-transform:uppercase;letter-spacing:1.2px;color:var(--cb-muted);margin-bottom:3px;">
                                {{ $unduhDate ? \Carbon\Carbon::parse($unduhDate)->locale('id')->isoFormat('dddd') : 'Hari' }}
                            </div>
                            <div style="font-family:var(--cb-font-heading);font-size:15px;font-weight:700;color:var(--cb-primary);">
                                {{ $unduhDate ? \Carbon\Carbon::parse($unduhDate)->locale('id')->isoFormat('D MMMM Y') : '-' }}
                            </div>
                        </div>
                        <div style="padding-left:6px;">
                            <div style="font-size:10px;text-transform:uppercase;letter-spacing:1.2px;color:var(--cb-muted);margin-bottom:3px;">Waktu</div>
                            <div style="font-family:var(--cb-font-heading);font-size:14.5px;font-weight:700;color:var(--cb-primary);">
                                {{ $formatEventTime($styles['unduh_mantu_time'] ?? null, '11:00') }}
                            </div>
                        </div>
                    </div>

                    <div style="font-size:14.5px;font-weight:700;color:var(--cb-text);margin-bottom:4px;">
                        {{ $unduhVenue ?: 'Kediaman Mempelai Pria' }}
                    </div>
                    <div style="font-size:12.5px;color:var(--cb-muted);line-height:1.6;margin-bottom:16px;">
                        {{ $styles['unduh_mantu_address'] ?? $invitation->resepsi_address ?? '' }}
                    </div>
                    @if(! empty($styles['unduh_mantu_maps_link']))
                        <a href="{{ $styles['unduh_mantu_maps_link'] }}" target="_blank" rel="noopener noreferrer" class="cb-btn-outline">
                            <span>Open Maps</span>
                        </a>
                    @endif
                </div>
            @endif

            @if($showStreaming)
                <div class="cb-card cb-reveal" data-reveal="event-live" :class="{ 'cb-revealed': revealed['event-live'] }" style="text-align:center;margin-bottom:18px;">
                    <div class="cb-eyebrow">Virtual Attendance</div>
                    <h3 class="cb-script-title" style="font-size:2.1rem;margin:4px 0 8px;">Live Streaming</h3>
                    <p style="font-size:12.5px;color:var(--cb-muted);line-height:1.7;margin:0 0 16px;">
                        Kami mengundang Bapak/Ibu/Saudara/i untuk menyaksikan Pernikahan kami secara Virtual yang disiarkan langsung melalui Sosial Media di bawah ini:
                    </p>
                    <a href="{{ $streamingLink }}" target="_blank" rel="noopener noreferrer" class="cb-btn-primary">
                        <span>Klik Disini</span>
                    </a>
                </div>
            @endif

            @if($mapsEmbed !== '')
                <div class="cb-card cb-reveal" data-reveal="event-map-embed" :class="{ 'cb-revealed': revealed['event-map-embed'] }" style="padding:12px;overflow:hidden;">
                    <div style="border-radius:16px;overflow:hidden;border:1px solid var(--cb-border-gold);">
                        @if(str_contains($mapsEmbed, '<iframe'))
                            {!! $mapsEmbed !!}
                        @else
                            <iframe src="{{ $mapsEmbed }}" width="100%" height="240" style="border:0;display:block;" allowfullscreen loading="lazy"></iframe>
                        @endif
                    </div>
                </div>
            @endif
        </section>

        {{-- SECTION 5: COUNTDOWN (Matches 3d-adat-bugis-02 #countdown & countdown_enabled toggle) --}}
        @if($showCountdown)
            <section id="cb-countdown" class="cb-section" style="padding-top:16px;">
                <div class="cb-card cb-reveal" data-reveal="countdown-card" :class="{ 'cb-revealed': revealed['countdown-card'] }" style="text-align:center;">
                    <div class="cb-eyebrow">Menuju Hari Bahagia</div>
                    <h2 class="cb-script-title" style="font-size:2.4rem;">Counting The Days</h2>
                    <div class="cb-ornament-divider" aria-hidden="true">
                        <span class="cb-ornament-diamond"></span>
                    </div>

                    <div class="cb-countdown-grid">
                        <div class="cb-cd-box">
                            <span class="cb-cd-num" x-text="days">0</span>
                            <span class="cb-cd-lbl">Hari</span>
                        </div>
                        <div class="cb-cd-box">
                            <span class="cb-cd-num" x-text="hours">0</span>
                            <span class="cb-cd-lbl">Jam</span>
                        </div>
                        <div class="cb-cd-box">
                            <span class="cb-cd-num" x-text="minutes">0</span>
                            <span class="cb-cd-lbl">Menit</span>
                        </div>
                        <div class="cb-cd-box">
                            <span class="cb-cd-num" x-text="seconds">0</span>
                            <span class="cb-cd-lbl">Detik</span>
                        </div>
                    </div>

                    <a href="{{ $googleCalendarUrl }}" target="_blank" rel="nofollow noopener noreferrer" class="cb-btn-primary">
                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span>Save on the Calendar</span>
                    </a>
                </div>
            </section>
        @endif

        {{-- SECTION 6: GALLERY (Matches 3d-adat-bugis-02 youtube_url + uploaded photos) --}}
        @if($showGallery)
            <section id="cb-gallery" class="cb-section">
                <div class="cb-section-header cb-reveal" data-reveal="gallery-hdr" :class="{ 'cb-revealed': revealed['gallery-hdr'] }">
                    <div class="cb-eyebrow">Constantly, Consistently, Continually, You</div>
                    <h2 class="cb-script-title" style="font-size:2.6rem;">Our Gallery</h2>
                    <div class="cb-ornament-divider" aria-hidden="true">
                        <span class="cb-ornament-diamond"></span>
                    </div>
                </div>

                @if($youtubeUrl !== '')
                    <div class="cb-card cb-reveal" data-reveal="gallery-yt" :class="{ 'cb-revealed': revealed['gallery-yt'] }" style="padding:12px;margin-bottom:16px;">
                        <iframe src="{{ $youtubeUrl }}" allowfullscreen loading="lazy"
                                style="width:100%;aspect-ratio:16/9;border:0;border-radius:14px;display:block;"></iframe>
                    </div>
                @endif

                @if(count($galleryPhotos) > 0)
                    <div class="cb-gallery-grid cb-reveal" data-reveal="gallery-grid" :class="{ 'cb-revealed': revealed['gallery-grid'] }">
                        @foreach($galleryPhotos as $gIdx => $photoUrl)
                            <div class="cb-gallery-item {{ $gIdx === 0 && count($galleryPhotos) % 2 === 1 ? 'cb-featured' : '' }}"
                                 wire:key="cb-gal-{{ $gIdx }}"
                                 @click="openLightbox('{{ $photoUrl }}')">
                                <img src="{{ $photoUrl }}" alt="Galeri {{ $gIdx + 1 }}" loading="lazy" decoding="async">
                            </div>
                        @endforeach
                    </div>
                @endif
            </section>
        @endif

        {{-- SECTION 7: LOVE STORY (Matches 3d-adat-bugis-02 love_story with optional photo) --}}
        @if($showLoveStory)
            <section id="cb-story" class="cb-section">
                <div class="cb-section-header cb-reveal" data-reveal="story-hdr" :class="{ 'cb-revealed': revealed['story-hdr'] }">
                    <div class="cb-eyebrow">Every Love Story Is Beautiful, But Ours Is My Favorite</div>
                    <h2 class="cb-script-title" style="font-size:2.6rem;">Love Story</h2>
                    <div class="cb-ornament-divider" aria-hidden="true">
                        <span class="cb-ornament-diamond"></span>
                    </div>
                </div>

                <div class="cb-timeline">
                    @foreach($loveStories as $sIdx => $story)
                        <div class="cb-timeline-item cb-reveal" data-reveal="story-{{ $sIdx }}" :class="{ 'cb-revealed': revealed['story-{{ $sIdx }}'] }" wire:key="cb-story-{{ $sIdx }}">
                            <div class="cb-timeline-dot"></div>
                            <div class="cb-card" style="padding:18px;">
                                @if(! empty($story['image']))
                                    <div style="border-radius:14px;overflow:hidden;margin-bottom:12px;border:1px solid var(--cb-border-gold);aspect-ratio:16/10;">
                                        <img src="{{ $story['image'] }}" alt="{{ $story['title'] }}" style="width:100%;height:100%;object-fit:cover;display:block;" loading="lazy" decoding="async">
                                    </div>
                                @endif
                                @if($story['date'] !== '')
                                    <span class="cb-story-date-pill">{{ $story['date'] }}</span>
                                @endif
                                <h3 class="cb-serif-title" style="font-size:1.25rem;margin:0 0 6px;color:var(--cb-primary);">{{ $story['title'] }}</h3>
                                <p style="font-size:13px;color:var(--cb-muted);line-height:1.72;margin:0;">{{ $story['description'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        {{-- SECTION 8: WEDDING GIFT (Matches 3d-adat-bugis-02 physical gift address + bank accounts) --}}
        @if($showGift)
            <section id="cb-gift" class="cb-section">
                <div class="cb-section-header cb-reveal" data-reveal="gift-hdr" :class="{ 'cb-revealed': revealed['gift-hdr'] }">
                    <div class="cb-eyebrow">Tanda Kasih</div>
                    <h2 class="cb-script-title" style="font-size:2.6rem;">Wedding Gift</h2>
                    <div class="cb-ornament-divider" aria-hidden="true">
                        <span class="cb-ornament-diamond"></span>
                    </div>
                    <p style="font-size:13px;color:var(--cb-muted);line-height:1.75;max-width:350px;margin:8px auto 0;">
                        Doa restu Anda merupakan karunia yang sangat berarti bagi kami. Dan jika memberi adalah ungkapan tanda kasih Anda, Anda dapat memberi kado secara cashless.
                    </p>
                </div>

                @foreach($bankAccounts as $bIdx => $account)
                    @php
                        $bankName = (string) ($account['bank'] ?? 'BANK');
                        $bankLogo = $resolveBankLogo($bankName);
                        $accNumber = (string) ($account['account_number'] ?? '');
                        $accName = (string) ($account['account_name'] ?? $coupleTitle);
                    @endphp
                    <div class="cb-atm-card cb-reveal" data-reveal="gift-bank-{{ $bIdx }}" :class="{ 'cb-revealed': revealed['gift-bank-{{ $bIdx }}'] }" wire:key="cb-bank-{{ $bIdx }}">
                        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:14px;">
                            <img src="{{ asset($assetBase.'atm-chip.png') }}" alt="Chip" style="width:38px;height:auto;" loading="lazy">
                            @if($bankLogo)
                                <img src="{{ $bankLogo }}" alt="{{ $bankName }}" style="height:24px;width:auto;background:#FFFDF9;padding:4px 10px;border-radius:6px;" loading="lazy">
                            @else
                                <span style="font-family:var(--cb-font-heading);font-size:1.1rem;font-weight:700;letter-spacing:1.5px;color:var(--cb-gold-light);background:rgba(255,253,249,0.12);padding:3px 12px;border-radius:999px;border:1px solid rgba(232,201,143,0.4);">{{ strtoupper($bankName) }}</span>
                            @endif
                        </div>
                        <div style="font-size:10px;text-transform:uppercase;letter-spacing:1.5px;color:rgba(255,253,249,0.65);">No Rekening</div>
                        <div class="cb-atm-number" style="margin-top:2px;">{{ $accNumber }}</div>
                        <div style="font-size:10px;text-transform:uppercase;letter-spacing:1.5px;color:rgba(255,253,249,0.65);margin-top:6px;">Atas Nama</div>
                        <div style="font-size:13.5px;font-weight:600;color:#FFFDF9;margin-bottom:14px;">{{ $accName }}</div>
                        @if($accNumber !== '')
                            <button type="button" @click="copyText(@js($accNumber), 'Nomor rekening berhasil disalin!')" class="cb-btn-outline" style="background:rgba(255,253,249,0.12);color:#FFFDF9;border-color:var(--cb-gold-light);padding:8px 18px;font-size:11.5px;">
                                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                </svg>
                                <span>Salin Rekening</span>
                            </button>
                        @endif
                    </div>
                @endforeach

                @if($giftAddress !== '')
                    <div class="cb-card cb-reveal" data-reveal="gift-addr" :class="{ 'cb-revealed': revealed['gift-addr'] }" style="text-align:center;">
                        <div class="cb-event-icon-circle" style="width:42px;height:42px;margin-bottom:8px;">
                            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/>
                            </svg>
                        </div>
                        <div class="cb-eyebrow">Kirim Kado Fisik</div>
                        <div style="font-size:11px;color:var(--cb-muted);margin-top:6px;">Nama Penerima</div>
                        <div style="font-family:var(--cb-font-heading);font-size:1.2rem;font-weight:700;color:var(--cb-primary);margin-bottom:6px;">{{ $coupleTitle }}</div>
                        <div style="font-size:11px;color:var(--cb-muted);">Alamat Penerima</div>
                        <p style="font-size:13px;color:var(--cb-text);line-height:1.7;margin:4px 0 14px;">{{ $giftAddress }}</p>
                        <button type="button" @click="copyText(@js($giftAddress), 'Alamat pengiriman kado disalin!')" class="cb-btn-outline">
                            <span>Salin Alamat</span>
                        </button>
                    </div>
                @endif
            </section>
        @endif

        {{-- SECTION 9: TURUT MENGUNDANG (Matches 3d-adat-bugis-02 Section 12) --}}
        <section id="cb-turut" class="cb-section" style="padding-top:16px;">
            <div class="cb-card cb-reveal" data-reveal="turut-card" :class="{ 'cb-revealed': revealed['turut-card'] }" style="text-align:center;">
                <div class="cb-eyebrow">Keluarga Besar</div>
                <h2 class="cb-script-title" style="font-size:2.35rem;">Turut Mengundang</h2>
                <div class="cb-ornament-divider" aria-hidden="true">
                    <span class="cb-ornament-diamond"></span>
                </div>
                <p style="font-size:12.5px;color:var(--cb-muted);line-height:1.75;margin:8px auto 14px;">
                    Dengan segala hormat, kami sekeluarga turut mengundang Bapak/Ibu/Saudara(i) untuk berkenan hadir dan memberikan doa restu pada acara tersebut:
                </p>
                <div style="font-family:var(--cb-font-heading);font-size:15px;line-height:1.8;color:var(--cb-text);">
                    @if(! empty($styles['turut_mengundang']))
                        {!! nl2br(e($styles['turut_mengundang'])) !!}
                    @else
                        <strong style="color:var(--cb-primary);">Keluarga Besar Mempelai Wanita</strong><br>
                        {{ $brideParents }}<br><br>
                        <strong style="color:var(--cb-primary);">Keluarga Besar Mempelai Pria</strong><br>
                        {{ $groomParents }}
                    @endif
                </div>
            </div>
        </section>

        {{-- SECTION 10: BEST WISHES & RSVP (Matches 3d-adat-bugis-02 Section 13 + Reactive Livewire Morph Safety) --}}
        @if($showRsvp)
            <section id="cb-rsvp" class="cb-section">
                <div class="cb-section-header cb-reveal" data-reveal="rsvp-hdr" :class="{ 'cb-revealed': isOpened || revealed['rsvp-hdr'] }">
                    <div class="cb-eyebrow">Sampaikan Doa dan Ucapan Terbaik Anda</div>
                    <h2 class="cb-script-title" style="font-size:2.6rem;">Best Wishes</h2>
                    <div class="cb-ornament-divider" aria-hidden="true">
                        <span class="cb-ornament-diamond"></span>
                    </div>
                </div>

                <div class="cb-card cb-reveal" data-reveal="ucapan" :class="{ 'cb-revealed': isOpened || revealed['ucapan'] }">
                    @if(session()->has('rsvp_success'))
                        <div style="background:rgba(22,101,52,0.1);border:1px solid rgba(22,101,52,0.3);color:#166534;padding:12px 14px;border-radius:12px;font-size:12.5px;font-weight:600;margin-bottom:16px;text-align:center;">
                            {{ session('rsvp_success') }}
                        </div>
                    @endif

                    <form wire:submit.prevent="submitRSVP">
                        <div style="margin-bottom:12px;">
                            <label style="display:block;font-size:11.5px;font-weight:600;color:var(--cb-muted);margin-bottom:5px;">Nama Anda</label>
                            <input type="text" wire:model="rsvpName" class="cb-input" placeholder="Nama Anda" required>
                            @error('rsvpName')
                                <span style="font-size:11px;color:#B91C1C;display:block;margin-top:4px;">{{ $message }}</span>
                            @enderror
                        </div>

                        @if($invitation->enable_rsvp ?? true)
                            <div style="margin-bottom:12px;">
                                <label style="display:block;font-size:11.5px;font-weight:600;color:var(--cb-muted);margin-bottom:5px;">Konfirmasi Kehadiran</label>
                                <select wire:model.live="rsvpStatus" class="cb-select" required>
                                    <option value="confirmed">Hadir</option>
                                    <option value="declined">Tidak Hadir</option>
                                    <option value="tentative">Masih Ragu</option>
                                </select>
                                @error('rsvpStatus')
                                    <span style="font-size:11px;color:#B91C1C;display:block;margin-top:4px;">{{ $message }}</span>
                                @enderror
                            </div>

                            @if($rsvpStatus === 'confirmed')
                                <div style="margin-bottom:12px;">
                                    <label style="display:block;font-size:11.5px;font-weight:600;color:var(--cb-muted);margin-bottom:5px;">Jumlah Tamu</label>
                                    <select wire:model="rsvpGuests" class="cb-select">
                                        @for($g = 1; $g <= 5; $g++)
                                            <option value="{{ $g }}">{{ $g }} Orang</option>
                                        @endfor
                                    </select>
                                    @error('rsvpGuests')
                                        <span style="font-size:11px;color:#B91C1C;display:block;margin-top:4px;">{{ $message }}</span>
                                    @enderror
                                </div>
                            @endif
                        @endif

                        <div style="margin-bottom:14px;">
                            <label style="display:block;font-size:11.5px;font-weight:600;color:var(--cb-muted);margin-bottom:5px;">Ucapan &amp; Doa Restu</label>
                            <textarea wire:model="rsvpMessage" rows="3" class="cb-textarea" placeholder="Tulis doa restu &amp; ucapan terbaik Anda..." required></textarea>
                            @error('rsvpMessage')
                                <span style="font-size:11px;color:#B91C1C;display:block;margin-top:4px;">{{ $message }}</span>
                            @enderror
                        </div>

                        <button type="submit" class="cb-btn-primary" style="width:100%;" wire:loading.attr="disabled">
                            <span wire:loading.remove wire:target="submitRSVP">Kirim Ucapan</span>
                            <span wire:loading wire:target="submitRSVP">Mengirim...</span>
                        </button>
                    </form>

                    {{-- Wishes Feed --}}
                    <div style="margin-top:24px;padding-top:18px;border-top:1px solid rgba(197,155,95,0.3);">
                        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:14px;">
                            <span style="font-family:var(--cb-font-heading);font-size:1.18rem;font-weight:700;color:var(--cb-primary);">Doa &amp; Ucapan Tamu</span>
                            <span style="font-size:11px;font-weight:700;background:rgba(139,30,43,0.1);color:var(--cb-primary);padding:4px 12px;border-radius:999px;border:1px solid rgba(139,30,43,0.18);">{{ $wishesCount }} Ucapan</span>
                        </div>

                        <div style="max-height:380px;overflow-y:auto;padding-right:4px;">
                            @forelse($wishesList as $wish)
                                <div class="cb-wish-card" wire:key="cb-wish-{{ $wish->id ?? $loop->index }}">
                                    <div style="display:flex;justify-content:space-between;align-items:center;gap:8px;margin-bottom:6px;">
                                        <div style="display:flex;align-items:center;gap:8px;min-width:0;">
                                            <div style="width:32px;height:32px;border-radius:50%;background:linear-gradient(135deg,var(--cb-primary),var(--cb-primary-dark));color:#FFFDF9;font-family:var(--cb-font-heading);font-size:13.5px;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0;border:1px solid var(--cb-gold-light);">
                                                {{ strtoupper(mb_substr((string) ($wish->name ?? 'T'), 0, 1)) ?: 'T' }}
                                            </div>
                                            <strong style="font-size:13px;color:var(--cb-text);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $wish->name }}</strong>
                                        </div>
                                        @php
                                            $status = $wish->attendance_status ?? 'confirmed';
                                            if ($status === 'confirmed' || $status === 'present') {
                                                $badgeText = 'Hadir ('.($wish->pax ?? 1).')';
                                                $badgeBg = 'rgba(22,101,52,0.12)';
                                                $badgeColor = '#166534';
                                            } elseif ($status === 'declined' || $status === 'notpresent') {
                                                $badgeText = 'Tidak Hadir';
                                                $badgeBg = 'rgba(185,28,28,0.12)';
                                                $badgeColor = '#991B1B';
                                            } else {
                                                $badgeText = 'Masih Ragu';
                                                $badgeBg = 'rgba(180,83,9,0.12)';
                                                $badgeColor = '#92400E';
                                            }
                                        @endphp
                                        <span style="font-size:10px;font-weight:700;padding:3px 9px;border-radius:999px;background:{{ $badgeBg }};color:{{ $badgeColor }};flex-shrink:0;">
                                            {{ $badgeText }}
                                        </span>
                                    </div>
                                    <p style="font-size:12.5px;color:var(--cb-muted);line-height:1.65;margin:0 0 6px;">{{ $wish->message }}</p>
                                    <div style="font-size:10px;color:rgba(122,90,93,0.75);text-align:right;">
                                        {{ ! empty($wish->created_at) ? \Carbon\Carbon::parse($wish->created_at)->diffForHumans() : 'Baru saja' }}
                                    </div>
                                </div>
                            @empty
                                <p style="text-align:center;font-size:12.5px;color:var(--cb-muted);padding:18px 0;margin:0;">
                                    Belum ada ucapan. Jadilah yang pertama memberikan doa restu!
                                </p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </section>
        @endif

        {{-- SECTION 11: CLOSING --}}
        <section id="cb-closing" class="cb-section" style="text-align:center;padding-bottom:26px;">
            <div class="cb-card cb-reveal" data-reveal="closing" :class="{ 'cb-revealed': revealed['closing'] }" style="padding:30px 22px 26px;">
                @if($coverPhotoUrl)
                    <div class="cb-arch-wrap" style="width:154px;height:202px;margin-bottom:22px;">
                        <div class="cb-arch-frame">
                            <img src="{{ $coverPhotoUrl }}" alt="{{ $coupleTitle }}" loading="lazy" decoding="async">
                        </div>
                        <svg class="cb-arch-garland" style="width:180px;height:45px;bottom:-13px;" viewBox="0 0 240 60" fill="none">
                            <path d="M18 32 C62 46 178 46 222 32" stroke="#C59B5F" stroke-width="1.6"/>
                            <path d="M72 36 C48 26 28 30 14 42 C34 44 54 42 72 36 Z" fill="#4D6E5D"/>
                            <path d="M168 36 C192 26 212 30 226 42 C206 44 186 42 168 36 Z" fill="#4D6E5D"/>
                            <circle cx="88" cy="34" r="10" fill="#F5E1D8" stroke="#C59B5F" stroke-width="1.2"/>
                            <circle cx="152" cy="34" r="10" fill="#F5E1D8" stroke="#C59B5F" stroke-width="1.2"/>
                            <circle cx="120" cy="32" r="14" fill="#8B1E2B" stroke="#E8C98F" stroke-width="1.5"/>
                        </svg>
                    </div>
                @else
                    <div class="cb-monogram-crest" aria-hidden="true">
                        {{ mb_substr($firstNick, 0, 1) }}&amp;{{ mb_substr($secondNick, 0, 1) }}
                    </div>
                @endif

                <p style="font-family:var(--cb-font-heading);font-size:1.05rem;font-style:italic;color:var(--cb-text);line-height:1.78;max-width:340px;margin:0 auto 14px;">
                    Menjadi sebuah kebahagiaan bagi kami apabila Bapak/Ibu/Saudara/i berkenan hadir dalam hari bahagia ini. Terima kasih atas segala doa, ucapan, dan perhatian yang diberikan.
                </p>
                <div class="cb-eyebrow" style="margin-top:10px;">Kami yang Berbahagia</div>
                <div class="cb-script-title" style="font-size:2.9rem;">{{ $coupleTitle }}</div>
                <div class="cb-ornament-divider" aria-hidden="true">
                    <span class="cb-ornament-diamond"></span>
                </div>
                <div style="font-family:var(--cb-font-display);font-size:11px;letter-spacing:2.4px;color:var(--cb-muted);margin-top:4px;">
                    {{ $displayDate }}
                </div>
            </div>
        </section>

        {{-- DEDICATED LUXURY EXOINVITE FOOTER --}}
        <footer class="cb-footer-wrap">
            <div class="cb-footer-card cb-reveal" data-reveal="footer" :class="{ 'cb-revealed': revealed['footer'] }">
                <div style="position:relative;z-index:2;display:flex;flex-direction:column;align-items:center;">
                    <span style="font-family:var(--cb-font-display);font-size:9.5px;font-weight:600;letter-spacing:3.2px;text-transform:uppercase;color:#E8C98F;opacity:0.9;margin-bottom:10px;">
                        Digital Invitation By
                    </span>
                    <a href="{{ url('/') }}" style="display:inline-flex;align-items:center;justify-content:center;text-decoration:none;">
                        <img src="{{ asset('assets/svg/logo-light.svg') }}" alt="ExoInvite Logo" loading="lazy" style="width:162px;max-width:72%;height:auto;display:block;filter:drop-shadow(0 4px 12px rgba(0,0,0,0.28));">
                    </a>
                    <p style="font-family:var(--cb-font-heading);font-size:0.96rem;font-style:italic;color:rgba(255,253,249,0.78);margin:10px auto 0;max-width:270px;line-height:1.5;">
                        Wujudkan undangan digital elegan &amp; berkesan untuk setiap momen istimewa Anda.
                    </p>
                    <a href="{{ url('/') }}" class="cb-footer-cta">
                        <span>Buat Undangan Seperti Ini</span>
                        <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </a>
                    <div style="width:110px;height:1px;background:linear-gradient(90deg,transparent,rgba(232,201,143,0.45),transparent);margin:18px auto 12px;"></div>
                    <div style="font-size:10.5px;color:rgba(248,229,190,0.68);letter-spacing:0.6px;">
                        &copy; {{ date('Y') }} ExoInvite &bull; All Rights Reserved
                    </div>
                </div>
            </div>
        </footer>
    </main>

    {{-- RIGHT-EDGE LUXURY CONTROL PILL (Adopted from 3d-adat-bugis-02 — Menu, Auto-Scroll + Progress + Speed Chip, & Music Icon Always Visible) --}}
    <div class="zipill-panel zipill-luxury zipill-live" data-side="right" role="group" aria-label="Kontrol undangan"
         x-show="isOpened" x-transition.opacity style="display:none;">
        {{-- 1. Toggle Menu Dock --}}
        <button type="button" class="zipill-button" @click="toggleMenu()" :aria-pressed="!menuCollapsed"
                :title="menuCollapsed ? 'Buka Menu Navigasi' : 'Tutup Menu Navigasi'" aria-label="Menu Navigasi">
            <svg class="zipill-icon" viewBox="0 0 24 24" aria-hidden="true">
                <circle cx="12" cy="12" r="9"></circle>
                <polygon points="15.5 8.5 13.5 13.5 8.5 15.5 10.5 10.5 15.5 8.5"></polygon>
            </svg>
            <span class="zipill-status-dot" aria-hidden="true"></span>
        </button>

        {{-- 2. Auto Scroll Toggle + Live Circular Progress + Speed Chip --}}
        <button type="button" class="zipill-button" data-action="scroll" @click="toggleAutoScroll()"
                :aria-pressed="autoScrolling" :title="autoScrolling ? 'Hentikan Auto Scroll' : 'Mulai Auto Scroll'"
                :style="'--zipill-progress: ' + scrollProgress" aria-label="Gulir otomatis">
            <svg class="zipill-progress" viewBox="0 0 40 40" aria-hidden="true">
                <circle class="zipill-progress-track" cx="20" cy="20" r="17"></circle>
                <circle class="zipill-progress-value" cx="20" cy="20" r="17" pathLength="100"></circle>
            </svg>
            <span data-icon-idle x-show="!autoScrolling">
                <svg class="zipill-icon" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M12 4v15m-5-5 5 5 5-5M8 4h8"></path>
                </svg>
            </span>
            <span data-icon-active x-show="autoScrolling" style="display:none;">
                <svg class="zipill-icon" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M9 5v14M15 5v14"></path>
                </svg>
            </span>
            <span x-show="autoScrolling" @click.stop="cycleScrollSpeed()" class="cb-speed-chip"
                  x-text="scrollSpeed + 'x'" title="Ubah kecepatan scroll" style="display:none;">1x</span>
        </button>

        {{-- 3. Music Toggle (Always Visible & Connected to #cbBgAudio) --}}
        <button type="button" class="zipill-button" data-action="music" @click="toggleAudio()" :aria-pressed="isPlaying"
                :title="isPlaying ? 'Jeda Musik' : 'Putar Musik'" aria-label="Putar musik">
            <svg class="zipill-icon" viewBox="0 0 24 24" aria-hidden="true">
                <path d="M10 17V6l10-2v11M10 9l10-2"></path>
                <ellipse cx="7" cy="17" rx="3" ry="2.5"></ellipse>
                <ellipse cx="17" cy="15" rx="3" ry="2.5"></ellipse>
            </svg>
            <span class="zipill-status-dot" aria-hidden="true"></span>
        </button>
    </div>

    {{-- COLLAPSIBLE BOTTOM NAVIGATION DOCK (Collapsed by Default, Adopted from 3d-adat-bugis-02) --}}
    <div class="cb-bottom-dock" x-show="isOpened && !menuCollapsed"
         x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" style="display:none;">
        <a href="#cb-hero" @click.prevent="scrollToSection('cb-hero')" class="dock-btn" :class="{ 'active': activeSection === 'cb-hero' }" title="Awal">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            <span>Awal</span>
        </a>
        <a href="#cb-couple" @click.prevent="scrollToSection('cb-couple')" class="dock-btn" :class="{ 'active': activeSection === 'cb-couple' }" title="Pasangan">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
            <span>Pasangan</span>
        </a>
        <a href="#cb-event" @click.prevent="scrollToSection('cb-event')" class="dock-btn" :class="{ 'active': activeSection === 'cb-event' }" title="Acara">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            <span>Acara</span>
        </a>
        @if($showCountdown)
            <a href="#cb-countdown" @click.prevent="scrollToSection('cb-countdown')" class="dock-btn" :class="{ 'active': activeSection === 'cb-countdown' }" title="Waktu">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>Waktu</span>
            </a>
        @endif
        @if($showGallery)
            <a href="#cb-gallery" @click.prevent="scrollToSection('cb-gallery')" class="dock-btn" :class="{ 'active': activeSection === 'cb-gallery' }" title="Galeri">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <span>Galeri</span>
            </a>
        @endif
        @if($showLoveStory)
            <a href="#cb-story" @click.prevent="scrollToSection('cb-story')" class="dock-btn" :class="{ 'active': activeSection === 'cb-story' }" title="Cerita">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                <span>Cerita</span>
            </a>
        @endif
        @if($showGift)
            <a href="#cb-gift" @click.prevent="scrollToSection('cb-gift')" class="dock-btn" :class="{ 'active': activeSection === 'cb-gift' }" title="Kado">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/></svg>
                <span>Kado</span>
            </a>
        @endif
        @if($showRsvp)
            <a href="#cb-rsvp" @click.prevent="scrollToSection('cb-rsvp')" class="dock-btn" :class="{ 'active': activeSection === 'cb-rsvp' }" title="Ucapan">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                <span>Ucapan</span>
            </a>
        @endif
        <button type="button" class="cb-dock-close-btn" @click="menuCollapsed = true" title="Sembunyikan Menu" aria-label="Sembunyikan Menu">
            <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.4" d="M9 5l7 7-7 7"/>
            </svg>
        </button>
    </div>

    {{-- LIGHTBOX MODAL --}}
    <div class="cb-lightbox" x-show="lightboxOpen" x-transition.opacity @click="closeLightbox()" style="display:none;">
        <img :src="lightboxImg" alt="Preview Foto" @click.stop>
        <button type="button" @click="closeLightbox()" aria-label="Tutup galeri"
                style="position:absolute;top:20px;right:20px;background:rgba(255,255,255,0.2);border:1px solid rgba(232,201,143,0.45);color:#fff;width:40px;height:40px;border-radius:50%;font-size:18px;cursor:pointer;display:flex;align-items:center;justify-content:center;">
            &times;
        </button>
    </div>

    {{-- TOAST NOTIFICATION --}}
    <div class="cb-toast" x-show="toastShow" x-transition.opacity x-text="toastMessage" style="display:none;"></div>
</div>

@once
    @push('alpine-components')
        <script>
            window.crimsonBotanicalTheme = function (targetTimestamp) {
                return {
                    isOpened: false,
                    isPlaying: false,
                    userPausedMusic: false,
                    pendingMusicUnlock: false,
                    menuCollapsed: true,
                    autoScrolling: false,
                    scrollSpeed: 1,
                    scrollProgress: 0,
                    autoScrollRaf: null,
                    wakeLockSentinel: null,
                    activeSection: 'cb-hero',
                    lightboxOpen: false,
                    lightboxImg: '',
                    toastShow: false,
                    toastMessage: '',
                    toastTimer: null,
                    days: '0',
                    hours: '0',
                    minutes: '0',
                    seconds: '0',
                    targetMs: Number(targetTimestamp || 0) * 1000,
                    revealed: {
                        'hero-arch': true,
                        'hero-title': true,
                        'hero-cta': true,
                    },
                    revealIO: null,

                    init() {
                        document.body.classList.add('cb-locked');
                        this.updateCountdown();
                        setInterval(() => this.updateCountdown(), 1000);
                        this.initObservers();
                        this.initScrollListeners();
                        this.initLivewireMorphHook();
                        this.initAudioSync();
                    },

                    initAudioSync() {
                        const audio = document.getElementById('cbBgAudio');
                        if (audio && !audio.dataset.iosSynced) {
                            audio.dataset.iosSynced = '1';
                            audio.addEventListener('play', () => {
                                this.isPlaying = true;
                                this.pendingMusicUnlock = false;
                            });
                            audio.addEventListener('pause', () => {
                                this.isPlaying = false;
                            });
                        }
                    },

                    toggleMenu() {
                        this.menuCollapsed = !this.menuCollapsed;
                    },

                    cycleScrollSpeed() {
                        this.scrollSpeed = this.scrollSpeed >= 3 ? 1 : this.scrollSpeed + 1;
                    },

                    initScrollListeners() {
                        const updateProgress = () => {
                            const docHeight = document.documentElement.scrollHeight - window.innerHeight;
                            if (docHeight <= 0) {
                                this.scrollProgress = 0;
                                return;
                            }
                            this.scrollProgress = Math.min(100, Math.max(0, Math.round((window.scrollY / docHeight) * 100)));
                        };
                        window.addEventListener('scroll', updateProgress, { passive: true });
                        updateProgress();

                        const cancelAutoOnInteract = (e) => {
                            if (!this.autoScrolling) return;
                            if (e.target && e.target.closest && (e.target.closest('.zipill-panel') || e.target.closest('.cb-bottom-dock'))) {
                                return;
                            }
                            this.stopAutoScroll();
                        };
                        window.addEventListener('wheel', cancelAutoOnInteract, { passive: true });
                        window.addEventListener('touchstart', cancelAutoOnInteract, { passive: true });
                        window.addEventListener('keydown', cancelAutoOnInteract, { passive: true });

                        document.addEventListener('visibilitychange', () => {
                            if (document.visibilityState === 'visible' && this.autoScrolling) {
                                this.requestWakeLock();
                            }
                        });
                        window.addEventListener('pagehide', () => this.releaseWakeLock());
                    },

                    openInvitation() {
                        if (this.isOpened) return;
                        this.isOpened = true;
                        document.body.classList.remove('cb-locked');
                        this.playAudio();
                        setTimeout(() => this.scanVisibleReveals(), 150);
                    },

                    updateCountdown() {
                        const diff = this.targetMs - Date.now();
                        if (diff <= 0) {
                            this.days = '0';
                            this.hours = '0';
                            this.minutes = '0';
                            this.seconds = '0';
                            return;
                        }
                        this.days = String(Math.floor(diff / 86400000));
                        this.hours = String(Math.floor((diff % 86400000) / 3600000));
                        this.minutes = String(Math.floor((diff % 3600000) / 60000));
                        this.seconds = String(Math.floor((diff % 60000) / 1000));
                    },

                    initObservers() {
                        this.revealIO = new IntersectionObserver((entries) => {
                            entries.forEach((e) => {
                                if (e.isIntersecting) {
                                    const key = e.target.getAttribute('data-reveal');
                                    if (key) {
                                        this.revealed[key] = true;
                                    }
                                }
                            });
                        }, { threshold: 0.12 });

                        document.querySelectorAll('.cb-reveal[data-reveal]').forEach((el) => this.revealIO.observe(el));

                        const sectionIO = new IntersectionObserver((entries) => {
                            entries.forEach((e) => {
                                if (e.isIntersecting && e.target.id) {
                                    this.activeSection = e.target.id;
                                }
                            });
                        }, { threshold: 0.35 });

                        document.querySelectorAll('.cb-section[id]').forEach((sec) => sectionIO.observe(sec));
                    },

                    scanVisibleReveals() {
                        const vh = window.innerHeight || document.documentElement.clientHeight;
                        document.querySelectorAll('.cb-reveal[data-reveal]').forEach((el) => {
                            const key = el.getAttribute('data-reveal');
                            if (!key) return;
                            if (this.revealIO) {
                                this.revealIO.observe(el);
                            }
                            const rect = el.getBoundingClientRect();
                            if (rect.top < vh * 0.96 && rect.bottom > 0) {
                                this.revealed[key] = true;
                            }
                        });
                    },

                    initLivewireMorphHook() {
                        const rescan = () => {
                            if (this.isOpened) {
                                this.revealed['rsvp-hdr'] = true;
                                this.revealed['ucapan'] = true;
                            }
                            this.$nextTick(() => this.scanVisibleReveals());
                        };
                        if (window.Livewire && typeof window.Livewire.hook === 'function') {
                            window.Livewire.hook('morph.updated', () => rescan());
                        }
                        document.addEventListener('livewire:init', () => {
                            if (window.Livewire && typeof window.Livewire.hook === 'function') {
                                window.Livewire.hook('morph.updated', () => rescan());
                            }
                        });
                    },

                    playAudio() {
                        const audio = document.getElementById('cbBgAudio');
                        if (!audio) return;
                        this.userPausedMusic = false;
                        if (audio.preload !== 'auto') {
                            audio.preload = 'auto';
                        }
                        if (audio.readyState === 0 && !audio.currentSrc && audio.src) {
                            audio.load();
                        }
                        try {
                            const p = audio.play();
                            if (p && typeof p.then === 'function') {
                                p.then(() => {
                                    this.isPlaying = true;
                                    this.pendingMusicUnlock = false;
                                }).catch(() => {
                                    this.isPlaying = false;
                                    this.armIosAudioUnlock();
                                });
                            } else {
                                this.isPlaying = !audio.paused;
                            }
                        } catch (_) {
                            this.isPlaying = false;
                            this.armIosAudioUnlock();
                        }
                    },

                    pauseAudio() {
                        this.userPausedMusic = true;
                        this.pendingMusicUnlock = false;
                        const audio = document.getElementById('cbBgAudio');
                        if (audio) {
                            audio.pause();
                            this.isPlaying = false;
                        }
                    },

                    toggleAudio() {
                        if (this.isPlaying) {
                            this.pauseAudio();
                        } else {
                            this.playAudio();
                        }
                    },

                    armIosAudioUnlock() {
                        if (this.pendingMusicUnlock || this.userPausedMusic) return;
                        this.pendingMusicUnlock = true;
                        const cleanup = () => {
                            window.removeEventListener('touchend', tryUnlock, true);
                            window.removeEventListener('click', tryUnlock, true);
                        };
                        const tryUnlock = () => {
                            if (this.userPausedMusic) {
                                this.pendingMusicUnlock = false;
                                cleanup();
                                return;
                            }
                            const audio = document.getElementById('cbBgAudio');
                            if (!audio) {
                                this.pendingMusicUnlock = false;
                                cleanup();
                                return;
                            }
                            if (audio.preload !== 'auto') {
                                audio.preload = 'auto';
                            }
                            if (audio.readyState === 0 && !audio.currentSrc && audio.src) {
                                audio.load();
                            }
                            try {
                                const p = audio.play();
                                if (p && typeof p.then === 'function') {
                                    p.then(() => {
                                        this.isPlaying = true;
                                        this.pendingMusicUnlock = false;
                                        cleanup();
                                    }).catch(() => {});
                                }
                            } catch (_) {}
                        };
                        window.addEventListener('touchend', tryUnlock, { capture: true, passive: true });
                        window.addEventListener('click', tryUnlock, { capture: true, passive: true });
                    },

                    toggleAutoScroll() {
                        if (this.autoScrolling) {
                            this.stopAutoScroll();
                        } else {
                            this.startAutoScroll();
                        }
                    },

                    startAutoScroll() {
                        if (this.autoScrollRaf) {
                            cancelAnimationFrame(this.autoScrollRaf);
                            this.autoScrollRaf = null;
                        }
                        this.autoScrolling = true;
                        this.requestWakeLock();
                        const step = () => {
                            if (!this.autoScrolling) return;
                            window.scrollBy({ top: 1.2 * this.scrollSpeed, behavior: 'instant' });
                            if ((window.innerHeight + window.scrollY) >= (document.documentElement.scrollHeight - 8)) {
                                this.stopAutoScroll();
                                return;
                            }
                            this.autoScrollRaf = requestAnimationFrame(step);
                        };
                        this.autoScrollRaf = requestAnimationFrame(step);
                    },

                    stopAutoScroll() {
                        this.autoScrolling = false;
                        if (this.autoScrollRaf) {
                            cancelAnimationFrame(this.autoScrollRaf);
                            this.autoScrollRaf = null;
                        }
                        this.releaseWakeLock();
                    },

                    async requestWakeLock() {
                        if (!this.autoScrolling || !('wakeLock' in navigator) || !navigator.wakeLock) return;
                        if (this.wakeLockSentinel && !this.wakeLockSentinel.released) return;
                        try {
                            const sentinel = await navigator.wakeLock.request('screen');
                            if (!this.autoScrolling) {
                                sentinel.release().catch(() => {});
                                return;
                            }
                            this.wakeLockSentinel = sentinel;
                            sentinel.addEventListener('release', () => {
                                if (this.wakeLockSentinel === sentinel) {
                                    this.wakeLockSentinel = null;
                                }
                            });
                        } catch (_) {}
                    },

                    releaseWakeLock() {
                        const sentinel = this.wakeLockSentinel;
                        this.wakeLockSentinel = null;
                        if (sentinel && !sentinel.released) {
                            sentinel.release().catch(() => {});
                        }
                    },

                    scrollToSection(id) {
                        this.stopAutoScroll();
                        this.activeSection = id;
                        const el = document.getElementById(id);
                        if (el) {
                            el.scrollIntoView({ behavior: 'smooth', block: 'start' });
                        }
                    },

                    openLightbox(url) {
                        this.lightboxImg = url;
                        this.lightboxOpen = true;
                    },

                    closeLightbox() {
                        this.lightboxOpen = false;
                        this.lightboxImg = '';
                    },

                    copyText(text, msg) {
                        if (!text) return;
                        if (navigator.clipboard && navigator.clipboard.writeText) {
                            navigator.clipboard.writeText(text).then(() => this.showToast(msg)).catch(() => this.fallbackCopy(text, msg));
                        } else {
                            this.fallbackCopy(text, msg);
                        }
                    },

                    fallbackCopy(text, msg) {
                        const ta = document.createElement('textarea');
                        ta.value = text;
                        ta.style.position = 'fixed';
                        ta.style.opacity = '0';
                        document.body.appendChild(ta);
                        ta.select();
                        try { document.execCommand('copy'); } catch (_) {}
                        document.body.removeChild(ta);
                        this.showToast(msg);
                    },

                    showToast(msg) {
                        this.toastMessage = msg;
                        this.toastShow = true;
                        if (this.toastTimer) clearTimeout(this.toastTimer);
                        this.toastTimer = setTimeout(() => { this.toastShow = false; }, 2500);
                    }
                };
            };
        </script>
    @endpush
@endonce
