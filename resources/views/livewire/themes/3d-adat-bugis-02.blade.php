@once
    @push('fonts')
        <!-- Google Fonts matching 3d-adat-bugis-02/index.html -->
        <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link
            href="https://fonts.googleapis.com/css2?family=Aboreto&family=Acme&family=Caudex:ital,wght@0,400;0,700;1,400&family=Cinzel:wght@400;500;600;700;800&family=Epilogue:wght@400;500;600;700&family=Gilda+Display&family=Great+Vibes&family=Libre+Caslon+Display&family=Lusitana:wght@400;700&family=Montserrat:wght@400;500;600;700&family=Pinyon+Script&family=Poppins:wght@300;400;500;600;700;800&family=Roboto:wght@300;400;500;700&family=Roboto+Slab:wght@400;600;700&family=Vidaloka&family=ZCOOL+XiaoWei&display=swap"
            rel="stylesheet">
    @endpush

    @push('styles')
        <!-- Preload Cover Background for Fast First Paint -->
        <link rel="preload" as="image" href="{{ asset('themes/3d-adat-bugis-02/assets/COVER-BUKA-UNDANGAN.jpg') }}"
            fetchpriority="high" />

        <!-- FontAwesome & Icons (all.min.css bundles solid, regular & brands) -->
        <link rel="stylesheet" href="{{ asset('themes/3d-adat-bugis-02/assets/all.min.css') }}?v=2" />
        <link rel="stylesheet" href="{{ asset('themes/3d-adat-bugis-02/assets/v4-shims.min.css') }}" />

        <!-- Elementor & Theme CSS from 3d-adat-bugis-02/index.html -->
        <link rel="stylesheet" href="{{ asset('themes/3d-adat-bugis-02/assets/uaf.css') }}?v=2" />
        <link rel="stylesheet" href="{{ asset('themes/3d-adat-bugis-02/assets/wds-elementor.css') }}" />
        <link rel="stylesheet" href="{{ asset('themes/3d-adat-bugis-02/assets/saic_style.css') }}" />
        <link rel="stylesheet" href="{{ asset('themes/3d-adat-bugis-02/assets/eae.min.css') }}" />
        <link rel="stylesheet" href="{{ asset('themes/3d-adat-bugis-02/assets/wdp.css') }}" />
        <link rel="stylesheet" href="{{ asset('themes/3d-adat-bugis-02/assets/elementor-icons.min.css') }}?v=2" />
        <link rel="stylesheet" href="{{ asset('themes/3d-adat-bugis-02/assets/elementor-frontend.min.css') }}" />
        <link rel="stylesheet" href="{{ asset('themes/3d-adat-bugis-02/assets/widget-heading.min.css') }}" />
        <link rel="stylesheet" href="{{ asset('themes/3d-adat-bugis-02/assets/widget-image.min.css') }}" />
        <link rel="stylesheet" href="{{ asset('themes/3d-adat-bugis-02/assets/widget-lottie.min.css') }}" />
        <link rel="stylesheet" href="{{ asset('themes/3d-adat-bugis-02/assets/widget-countdown.min.css') }}" />
        <link rel="stylesheet" href="{{ asset('themes/3d-adat-bugis-02/assets/widget-divider.min.css') }}" />
        <link rel="stylesheet" href="{{ asset('themes/3d-adat-bugis-02/assets/widget-spacer.min.css') }}" />
        <link rel="stylesheet" href="{{ asset('themes/3d-adat-bugis-02/assets/widget-video.min.css') }}" />
        <link rel="stylesheet" href="{{ asset('themes/3d-adat-bugis-02/assets/widget-gallery.min.css') }}" />
        <link rel="stylesheet" href="{{ asset('themes/3d-adat-bugis-02/assets/e-gallery.min.css') }}" />
        <link rel="stylesheet" href="{{ asset('themes/3d-adat-bugis-02/assets/widget-icon-box.min.css') }}" />
        <link rel="stylesheet" href="{{ asset('themes/3d-adat-bugis-02/assets/transitions.min.css') }}" />
        <link rel="stylesheet" href="{{ asset('themes/3d-adat-bugis-02/assets/zoomIn.min.css') }}" />
        <link rel="stylesheet" href="{{ asset('themes/3d-adat-bugis-02/assets/fadeInDown.min.css') }}" />
        <link rel="stylesheet" href="{{ asset('themes/3d-adat-bugis-02/assets/fadeInUp.min.css') }}" />
        <link rel="stylesheet" href="{{ asset('themes/3d-adat-bugis-02/assets/fadeInLeft.min.css') }}" />
        <link rel="stylesheet" href="{{ asset('themes/3d-adat-bugis-02/assets/fadeInRight.min.css') }}" />
        <link rel="stylesheet" href="{{ asset('themes/3d-adat-bugis-02/assets/rotateInDownLeft.min.css') }}" />
        <link rel="stylesheet" href="{{ asset('themes/3d-adat-bugis-02/assets/jet-elements.css') }}" />
        <link rel="stylesheet" href="{{ asset('themes/3d-adat-bugis-02/assets/jet-timeline.css') }}" />
        <link rel="stylesheet" href="{{ asset('themes/3d-adat-bugis-02/assets/jet-elements-jet-timeline.css') }}" />
        <link rel="stylesheet" href="{{ asset('themes/3d-adat-bugis-02/assets/pill.css') }}" />
        <link rel="stylesheet" href="{{ asset('themes/3d-adat-bugis-02/assets/post-7.css') }}" />
        <link rel="stylesheet" href="{{ asset('themes/3d-adat-bugis-02/assets/post-346.css') }}" />

        <style>
            /* Local Font Fallback for "Gethuk Jawa" (all other UAF fonts come from uaf.css?v=2 with font-display: swap) */
            @font-face {
                font-family: "Gethuk Jawa";
                font-style: normal;
                font-weight: 400;
                font-display: swap;
                src: url('{{ asset('themes/3d-adat-bugis-02/assets/2559Gethuk-Jawa.woff2') }}') format('woff2'),
                     url('{{ asset('themes/3d-adat-bugis-02/assets/Gethuk-Jawa.otf') }}') format('opentype');
            }

            /* Core Widget Inline Helpers */
            .elementor-button .elementor-button-icon {
                display: inline-flex;
            }

            /* Tailwind Preflight Compatibility Fixes for Elementor */
            html,
            body {
                margin: 0;
                padding: 0;
                background-color: #460505;
                overflow-x: hidden;
            }

            .bugis-theme-root {
                width: 100%;
                min-height: 100vh;
                position: relative;
            }

            .elementor-346 .elementor-widget-image img {
                display: inline-block;
            }

            .elementor-346 p,
            .elementor-346 h1,
            .elementor-346 h2,
            .elementor-346 h5 {
                margin: 0;
            }

            .elementor-346 a {
                text-decoration: none;
            }

            /* Ensure Elementor ::before overlays sit behind container content */
            .elementor-346 .e-con::before {
                z-index: 0;
                pointer-events: none;
            }

            .elementor-346 .e-con>.elementor-widget:not(.elementor-absolute),
            .elementor-346 .e-con>.e-con:not(.elementor-absolute) {
                position: relative;
                z-index: 1;
            }

            .elementor-346 .elementor-widget-lottie.elementor-absolute {
                z-index: 5;
                pointer-events: none;
            }

            /* Scroll Lock & Cover Slide Transition */
            .stop-scrolling {
                height: 100vh !important;
                height: 100dvh !important;
                overflow: hidden !important;
            }

            .cover-hidden {
                transform: translateY(-100%) !important;
                opacity: 0 !important;
                pointer-events: none !important;
                transition: transform 1.2s cubic-bezier(0.77, 0, 0.175, 1), opacity 1.1s ease !important;
            }

            .tombolbuka {
                opacity: 0 !important;
                transform: translateY(10px);
            }

            .tombolbuka.btnVisibleAfterLoad {
                transition: all 1.5s ease-in-out !important;
                opacity: 1 !important;
                transform: translateY(0px) !important;
            }

            /* Cover Safe-Zone Framing & Typography (keeps text/button cleanly inside upper cream arch above Bugis house roof) */
            .elementor-346 .elementor-element.elementor-element-f9feda1.cover {
                display: flex !important;
                align-items: stretch !important;
                justify-content: center !important;
                background-position: center center !important;
                background-size: cover !important;
            }

            .elementor-346 .elementor-element.elementor-element-132bad9a {
                margin-top: 0 !important;
                --margin-top: 0px !important;
                min-height: 100vh !important;
                min-height: 100dvh !important;
                height: 100% !important;
                width: 100% !important;
                display: flex !important;
                flex-direction: column !important;
                align-items: center !important;
                justify-content: center !important;
                gap: clamp(3px, 0.7dvh, 7px) !important;
                --gap: clamp(3px, 0.7dvh, 7px) !important;
                --widgets-spacing: 0px;
                --widgets-spacing-row: 0px;
                padding: clamp(46px, 9.5dvh, 84px) 28px clamp(150px, 28dvh, 250px) 28px !important;
                box-sizing: border-box !important;
            }

            .elementor-346 .elementor-element.elementor-element-2f225040 {
                margin: 0 !important;
                flex-shrink: 0;
            }

            .elementor-346 .elementor-element.elementor-element-2f225040 .elementor-heading-title {
                font-size: clamp(13px, 2dvh, 16px) !important;
                line-height: 1.3 !important;
                letter-spacing: 0.08em;
                text-transform: uppercase;
            }

            .elementor-346 .elementor-element.elementor-element-423e4dc1 {
                margin: 2px 0 clamp(6px, 1.1dvh, 12px) 0 !important;
                flex-shrink: 0;
            }

            .elementor-346 .elementor-element.elementor-element-423e4dc1 .elementor-heading-title {
                font-size: clamp(28px, 4.4dvh, 36px) !important;
                line-height: 1.2 !important;
            }

            .elementor-346 .elementor-element.elementor-element-2ec0975a {
                margin: 2px 0 clamp(6px, 1.1dvh, 10px) 0 !important;
                width: 100%;
                display: flex;
                justify-content: center;
                flex-shrink: 0;
            }

            .elementor-346 .elementor-element.elementor-element-325a7260 {
                margin: 2px 0 0 0 !important;
                flex-shrink: 0;
            }

            .elementor-346 .elementor-element.elementor-element-325a7260 .elementor-heading-title {
                font-size: clamp(12px, 1.8dvh, 14px) !important;
                line-height: 1.35 !important;
            }

            .elementor-346 .elementor-element.elementor-element-30ed7aeb {
                padding: 0 12px clamp(4px, 0.8dvh, 8px) !important;
                margin: 0 !important;
                flex-shrink: 0;
                max-width: 100%;
            }

            .elementor-346 .elementor-element.elementor-element-30ed7aeb .elementor-heading-title {
                font-size: clamp(17px, 2.5dvh, 21px) !important;
                line-height: 1.25 !important;
                font-weight: 600 !important;
                text-shadow: 0 1px 6px rgba(255, 245, 238, 0.88);
                word-break: break-word;
            }

            .elementor-346 .elementor-element.elementor-element-46720f30 {
                margin-top: 2px !important;
                flex-shrink: 0;
            }

            .elementor-346 .elementor-element.elementor-element-46720f30 .elementor-button {
                padding: 10px 20px !important;
                line-height: 1.2 !important;
            }

            .elementor-346 .elementor-element.elementor-element-46720f30 .elementor-button-content-wrapper {
                gap: 7px;
                align-items: center;
                justify-content: center;
            }

            /* Safari-Safe Explicit Portrait Frame Wrappers (prevents WebKit flex intrinsic distortion on 1:1 uploaded photos) */
            .bugis-cover-arch-frame {
                width: clamp(114px, min(40vw, 20dvh), 160px);
                height: clamp(148px, min(52vw, 26dvh), 208px);
                border: 4px solid #BF996D;
                border-radius: 500px 500px 0 0;
                overflow: hidden;
                flex-shrink: 0;
                box-sizing: border-box;
                background: rgba(255, 248, 240, 0.45);
            }

            .bugis-couple-frame {
                width: min(64%, 240px);
                aspect-ratio: 2 / 3;
                border: 4px solid #BF996D;
                border-radius: 500px;
                overflow: hidden;
                margin: 0 auto;
                box-sizing: border-box;
                flex-shrink: 0;
            }

            .bugis-quote-frame {
                width: min(92%, 320px);
                aspect-ratio: 2 / 3;
                border-radius: 153px;
                overflow: hidden;
                margin: 0 auto;
                box-shadow: 0 0 10px 17px rgba(0, 0, 0, 0.13);
                flex-shrink: 0;
            }

            .bugis-cover-arch-frame img,
            .bugis-couple-frame img,
            .bugis-quote-frame img {
                width: 100% !important;
                max-width: 100% !important;
                height: 100% !important;
                object-fit: cover !important;
                object-position: center top !important;
                display: block !important;
                border: none !important;
                border-radius: 0 !important;
                box-shadow: none !important;
            }

            /* Sidebar & Couple Heading Line-Height Safety */
            .elementor-346 .elementor-element.elementor-element-41dfae8f .elementor-heading-title {
                line-height: 1.18 !important;
            }

            .elementor-346 .elementor-element.elementor-element-1ed755bd .elementor-heading-title,
            .elementor-346 .elementor-element.elementor-element-74ac10ba .elementor-heading-title {
                line-height: 1.15 !important;
            }

            .elementor-346 .elementor-element.elementor-element-5a868f7f .elementor-heading-title,
            .elementor-346 .elementor-element.elementor-element-63ed8e8e .elementor-heading-title {
                line-height: 1.3 !important;
            }

            /* Desktop 70% / 30% Split Layout Matching 3d-adat-bugis-02/index.html */
            @media (min-width: 768px) {
                .elementor-346 .elementor-element.elementor-element-7a2531fd {
                    position: fixed !important;
                    top: 0;
                    left: 0;
                    width: 70% !important;
                    max-width: 70% !important;
                    height: 100vh !important;
                    z-index: 10;
                    padding: 0 28px !important;
                    gap: 14px !important;
                    box-sizing: border-box;
                    background-image: url('{{ asset('themes/3d-adat-bugis-02/assets/BACKGROUND-FIXED.jpg') }}') !important;
                    background-size: cover !important;
                    background-position: bottom center !important;
                    background-repeat: no-repeat !important;
                }

                .elementor-346 .elementor-element.elementor-element-41dfae8f {
                    margin: 18px 0 !important;
                }

                .elementor-346 .elementor-element.elementor-element-7a2531fd::before {
                    content: '';
                    position: absolute;
                    inset: 0;
                    background-color: #000000;
                    opacity: 0.5;
                    z-index: 1;
                    pointer-events: none;
                }

                .elementor-346 .elementor-element.elementor-element-7a2531fd>.elementor-widget {
                    position: relative;
                    z-index: 2;
                }

                .elementor-346 .elementor-element.elementor-element-35ac074c {
                    margin-left: 70% !important;
                    width: 30% !important;
                    max-width: 30% !important;
                    flex: 0 0 30% !important;
                    min-height: 100vh;
                }

                .elementor-346 .elementor-element.elementor-element-35ac074c::before {
                    left: 70% !important;
                    width: 30% !important;
                    background-size: cover !important;
                    background-position: center center !important;
                }

                .elementor-346 .elementor-element.elementor-element-f9feda1.cover {
                    position: fixed !important;
                    top: 0;
                    right: 0;
                    left: auto !important;
                    width: 30% !important;
                    max-width: 30% !important;
                    height: 100vh !important;
                    height: 100dvh !important;
                    z-index: 9999 !important;
                    overflow: hidden;
                    margin: 0;
                    padding: 0;
                }

                .bugis-cover-arch-frame {
                    width: clamp(118px, min(14vw, 20vh), 170px);
                    height: clamp(154px, min(18.5vw, 26vh), 224px);
                }

                .elementor-346 .elementor-element.elementor-element-53c49100 .elementor-heading-title,
                .elementor-346 .elementor-element.elementor-element-7689559c .elementor-heading-title {
                    font-size: clamp(44px, 4.2vw, 68px) !important;
                    line-height: 1.15 !important;
                }
            }

            /* Mobile Full-Width Layout */
            @media (max-width: 767px) {
                .elementor-346 .elementor-element.elementor-element-7a2531fd {
                    display: none !important;
                }

                .elementor-346 .elementor-element.elementor-element-35ac074c {
                    margin-left: 0 !important;
                    width: 100% !important;
                    max-width: 100% !important;
                    flex: 0 0 100% !important;
                }

                .elementor-346 .elementor-element.elementor-element-35ac074c::before {
                    left: 0 !important;
                    width: 100% !important;
                    background-size: cover !important;
                    background-position: center center !important;
                }

                .elementor-346 .elementor-element.elementor-element-f9feda1.cover {
                    position: fixed !important;
                    top: 0;
                    left: 0 !important;
                    width: 100% !important;
                    max-width: 100% !important;
                    height: 100vh !important;
                    height: 100dvh !important;
                    z-index: 9999 !important;
                    overflow: hidden;
                    margin: 0;
                    padding: 0;
                }
            }

            /* Hero Video & Smooth Text Reveal */
            .motionSection {
                position: relative;
                min-height: 100vh;
                min-height: 100dvh;
                overflow: hidden;
            }

            .motionSection .elementor-background-video-container {
                position: absolute;
                inset: 0;
                z-index: 0;
                overflow: hidden;
                pointer-events: none;
            }

            .motionSection video {
                -webkit-object-fit: cover !important;
                object-fit: cover !important;
                width: 100%;
                height: 100%;
            }

            .motionSection .motionText {
                position: relative;
                z-index: 2;
                width: 100%;
                min-height: 100vh;
                min-height: 100dvh;
                margin-top: 0 !important;
                --margin-top: 0px !important;
                padding: 28px 20px !important;
                gap: 4px !important;
                box-sizing: border-box;
                opacity: 0;
                transform: translateY(12px);
                transition: opacity 1s ease, transform 1s ease;
            }

            .motionSection .motionText.motionText--visible {
                opacity: 1;
                transform: translateY(0);
            }

            .elementor-346 .elementor-element.elementor-element-53c49100 .elementor-heading-title,
            .elementor-346 .elementor-element.elementor-element-7689559c .elementor-heading-title {
                font-size: clamp(44px, 11.5vw, 66px) !important;
                line-height: 1.15 !important;
            }

            .elementor-346 .elementor-element.elementor-element-2117e8c7 .elementor-heading-title {
                font-size: clamp(22px, 5.5vw, 32px) !important;
                line-height: 1.1 !important;
            }

            .elementor-346 .elementor-element.elementor-element-2bc135a1 {
                margin: 6px 0 2px 0 !important;
            }

            .elementor-346 .elementor-element.elementor-element-2bc135a1 .elementor-heading-title {
                line-height: 1.35 !important;
            }

            .elementor-346 .elementor-element.elementor-element-477aca49 {
                margin: 0 0 14px 0 !important;
            }

            .elementor-346 .elementor-element.elementor-element-477aca49 .elementor-heading-title {
                line-height: 1.35 !important;
            }

            /* Lightbox Overlay */
            .bugis-lightbox {
                position: fixed;
                inset: 0;
                background: rgba(0, 0, 0, 0.92);
                z-index: 99999;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 20px;
            }

            /* Gallery 2-Column Masonry Grid Styling */
            .elementor-346 .elementor-element.elementor-element-21562324 .elementor-gallery__container {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                gap: 10px;
                width: 100%;
            }

            .elementor-346 .elementor-element.elementor-element-21562324 .e-gallery-item {
                position: relative;
                display: block;
                overflow: hidden;
                border-radius: 5px;
                aspect-ratio: 2 / 3;
                cursor: pointer;
                background: rgba(0, 0, 0, 0.25);
            }

            .elementor-346 .elementor-element.elementor-element-21562324 .e-gallery-item img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                display: block;
                transition: transform 0.6s ease;
            }

            .elementor-346 .elementor-element.elementor-element-21562324 .e-gallery-item:hover img {
                transform: scale(1.06);
            }

            .elementor-346 .elementor-element.elementor-element-21562324 .elementor-gallery-item__overlay {
                position: absolute;
                inset: 0;
                background-color: rgba(0, 0, 0, 0);
                transition: background-color 0.4s ease;
                pointer-events: none;
            }

            .elementor-346 .elementor-element.elementor-element-21562324 .e-gallery-item:hover .elementor-gallery-item__overlay {
                background-color: rgba(0, 0, 0, 0.35);
            }

            /* Dual-Slide Ken Burns Crossfade */
            .bugis-kenburns-wrapper {
                position: relative;
                overflow: hidden;
                width: 100%;
            }

            .bugis-kb-slide {
                position: absolute;
                inset: 0;
                background-size: cover;
                background-position: center center;
                will-change: transform, opacity;
            }

            .bugis-kb-slide-1 {
                animation: bugisKenBurnsSlide1 16s ease-in-out infinite;
            }

            .bugis-kb-slide-2 {
                animation: bugisKenBurnsSlide2 16s ease-in-out infinite;
            }

            @keyframes bugisKenBurnsSlide1 {
                0% {
                    opacity: 1;
                    transform: scale(1);
                }

                45% {
                    opacity: 1;
                    transform: scale(1.12);
                }

                55% {
                    opacity: 0;
                    transform: scale(1.15);
                }

                95% {
                    opacity: 0;
                    transform: scale(1);
                }

                100% {
                    opacity: 1;
                    transform: scale(1);
                }
            }

            @keyframes bugisKenBurnsSlide2 {
                0% {
                    opacity: 0;
                    transform: scale(1);
                }

                45% {
                    opacity: 0;
                    transform: scale(1);
                }

                55% {
                    opacity: 1;
                    transform: scale(1.02);
                }

                95% {
                    opacity: 1;
                    transform: scale(1.14);
                }

                100% {
                    opacity: 0;
                    transform: scale(1.15);
                }
            }

            /* Jet Timeline Visibility & Contrast Guards */
            .elementor-346 .elementor-element.elementor-element-50f723ba .jet-timeline__line-progress {
                transition: height 0.45s ease;
            }

            .elementor-346 .elementor-element.elementor-element-50f723ba .jet-timeline-item.is--show .timeline-item__card {
                opacity: 1 !important;
                visibility: visible !important;
                transform: none;
            }

            .elementor-346 .elementor-element.elementor-element-50f723ba .jet-timeline-item:not(.is--active) .timeline-item__card-title,
            .elementor-346 .elementor-element.elementor-element-50f723ba .jet-timeline-item:not(.is--active) .timeline-item__card-desc {
                color: #4a1c1c;
            }

            .elementor-346 .elementor-element.elementor-element-50f723ba .timeline-item__card-content {
                padding: 18px 20px;
            }

            .elementor-346 .elementor-element.elementor-element-50f723ba .timeline-item__card-title {
                font-family: "Caudex", serif;
                font-size: 18px;
                font-weight: 700;
                margin: 6px 0;
            }

            .elementor-346 .elementor-element.elementor-element-50f723ba .timeline-item__card-desc {
                font-family: "Poppins", sans-serif;
                font-size: 13px;
                line-height: 1.55;
            }

            .elementor-346 .elementor-element.elementor-element-50f723ba .timeline-item__card-img img {
                width: 100%;
                aspect-ratio: 4 / 3;
                object-fit: cover;
                display: block;
            }

            /* Gift Bank Card & Copy Button Helpers */
            .bugis-gift-bank-badge {
                display: inline-block;
                padding: 4px 10px;
                background: #682B2B;
                color: #E6DAAC;
                border: 1px solid #BF996D;
                border-radius: 6px;
                font-family: "Cinzel", serif;
                font-size: 12px;
                font-weight: 700;
                letter-spacing: 0.04em;
                text-transform: uppercase;
            }

            .elementor-346 .elementor-element.elementor-element-62ae0d81,
            .elementor-346 .elementor-element.elementor-element-2b1d7932 {
                padding: 20px 18px;
            }

            .elementor-346 .elementor-element.elementor-element-518e93ea,
            .elementor-346 .elementor-element.elementor-element-3fab5e6b {
                margin-top: 8px;
            }

            /* Best Wishes / RSVP SAIC Styling Harmonized with post-346.css */
            .elementor-346 .elementor-element.elementor-element-b25636f .saic-wrapper {
                background-color: rgba(255, 255, 255, 0.04);
                border: 1px solid rgba(255, 255, 255, 0.25);
                border-radius: 8px;
                color: #FFFFFF;
            }

            .elementor-346 .elementor-element.elementor-element-b25636f .saic-wrap-link {
                padding: 14px 15px 8px;
            }

            .elementor-346 .elementor-element.elementor-element-b25636f .saic-wrap-form {
                padding: 15px 18px;
                border-top: 1px solid rgba(255, 255, 255, 0.16);
            }

            .elementor-346 .elementor-element.elementor-element-b25636f input.saic-input,
            .elementor-346 .elementor-element.elementor-element-b25636f select.saic-select,
            .elementor-346 .elementor-element.elementor-element-b25636f textarea.saic-textarea {
                width: 100%;
                padding: 9px 14px !important;
                background: #FFFFFF !important;
                color: #44525F !important;
                border: 1px solid #d5deea !important;
                border-radius: 5px !important;
                font-size: 14px !important;
                font-family: "Poppins", sans-serif;
                box-sizing: border-box;
            }

            .elementor-346 .elementor-element.elementor-element-b25636f .saic-wrap-submit {
                margin-top: 14px !important;
                text-align: right;
            }

            .elementor-346 .elementor-element.elementor-element-b25636f .saic-submit-btn {
                background-color: #FFFFFF;
                color: #682B2B;
                font-weight: 700;
                font-size: 14px;
                padding: 9px 22px;
                border: none;
                border-radius: 4px;
                cursor: pointer;
                transition: opacity 0.2s ease, transform 0.2s ease;
            }

            .elementor-346 .elementor-element.elementor-element-b25636f .saic-submit-btn:hover {
                opacity: 0.92;
                transform: translateY(-1px);
            }

            .elementor-346 .elementor-element.elementor-element-b25636f ul.saic-container-comments {
                display: block !important;
                list-style: none;
                padding: 0;
                margin: 0;
                max-height: 440px;
                overflow-y: auto;
                border-top: 1px solid rgba(255, 255, 255, 0.18);
            }

            .elementor-346 .elementor-element.elementor-element-b25636f ul.saic-container-comments li.saic-item-comment {
                padding: 12px 16px !important;
                border-bottom: 1px solid rgba(255, 255, 255, 0.12);
            }

            /* Floating Control Pill & Speed Mini-Controls */
            .bugis-speed-chip {
                position: absolute;
                right: calc(100% + 8px);
                top: 50%;
                transform: translateY(-50%);
                background: rgba(46, 6, 6, 0.94);
                color: #E7D39F;
                border: 1px solid rgba(231, 211, 159, 0.28);
                border-radius: 999px;
                padding: 5px 10px;
                font-size: 11px;
                font-weight: 700;
                cursor: pointer;
                box-shadow: 0 4px 14px rgba(0, 0, 0, 0.3);
                white-space: nowrap;
            }

            /* Responsive Bottom Navigation Dock */
            .bugis-bottom-dock {
                position: fixed;
                bottom: 18px;
                left: 50%;
                transform: translateX(-50%);
                z-index: 9500;
                display: flex;
                align-items: center;
                gap: 4px;
                padding: 6px 12px;
                background: rgba(46, 6, 6, 0.94);
                backdrop-filter: blur(12px);
                -webkit-backdrop-filter: blur(12px);
                border: 1px solid rgba(231, 211, 159, 0.35);
                border-radius: 50px;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.55);
                max-width: calc(100vw - 74px);
                overflow-x: auto;
                scrollbar-width: none;
            }

            .bugis-bottom-dock::-webkit-scrollbar {
                display: none;
            }

            .bugis-bottom-dock .dock-btn {
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                min-width: 44px;
                height: 44px;
                padding: 0 4px;
                color: #FFF8EF;
                border-radius: 50%;
                text-decoration: none;
                transition: background-color 0.2s ease, color 0.2s ease;
                flex-shrink: 0;
            }

            .bugis-bottom-dock .dock-btn:hover {
                background: rgba(231, 211, 159, 0.18);
                color: #E7D39F;
            }

            .bugis-bottom-dock .dock-btn i {
                font-size: 15px;
                margin-bottom: 2px;
            }

            .bugis-bottom-dock .dock-btn span {
                font-size: 9px;
                font-family: "Poppins", sans-serif;
                letter-spacing: 0.01em;
            }

            @media (min-width: 768px) {
                .bugis-bottom-dock {
                    left: 85%;
                    max-width: calc(30vw - 64px);
                }
            }

            /* Toast Notification */
            .bugis-toast {
                position: fixed;
                bottom: 82px;
                left: 50%;
                transform: translateX(-50%);
                background: rgba(46, 6, 6, 0.96);
                color: #E7D39F;
                border: 1px solid #BF996D;
                padding: 10px 20px;
                border-radius: 30px;
                font-size: 13px;
                font-weight: 500;
                font-family: "Poppins", sans-serif;
                z-index: 100000;
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.5);
                white-space: nowrap;
            }
        </style>
    @endpush

    @push('alpine-components')
        <!-- Lottie runtime & Alpine controller loaded in body before Livewire initializes Alpine (keeps <head> under 64 KB for WhatsApp OG parser) -->
        <script src="{{ asset('themes/3d-adat-bugis-02/assets/lottie.min.js') }}" defer></script>
        <script>
            window.bugisTheme = function (targetTimestamp, config = {}) {
                return {
                    isOpened: false,
                    coverBtnVisible: false,
                    heroTextVisible: false,
                    isPlaying: false,
                    userPausedMusic: false,
                    pendingMusicUnlock: false,
                    menuCollapsed: true,
                    autoScrolling: false,
                    scrollSpeed: 1,
                    scrollProgress: 0,
                    autoScrollFrame: null,
                    wakeLockSentinel: null,
                    wakeLockActive: false,
                    hasAutoScrolledOnce: false,
                    heroFallbackTimer: null,
                    autoScrollFallbackTimer: null,
                    lightboxOpen: false,
                    lightboxImg: '',
                    toastShow: false,
                    toastMessage: '',
                    toastTimer: null,
                    days: 0,
                    hours: 0,
                    minutes: 0,
                    seconds: 0,
                    targetTime: Number(targetTimestamp || 0) * 1000,
                    lottieRetries: 0,

                    init() {
                        if (!this.isOpened) {
                            document.body.classList.add('stop-scrolling');
                        }

                        this.updateCountdown();
                        setInterval(() => this.updateCountdown(), 1000);

                        setTimeout(() => {
                            this.coverBtnVisible = true;
                            this.revealElementsIn(document.getElementById('cover'));
                        }, 200);

                        this.initObservers();
                        this.initLotties();
                        this.initVideoReplay();
                        this.initScrollListeners();

                        const song = document.getElementById('song');
                        if (song && !song.dataset.iosSynced) {
                            song.dataset.iosSynced = '1';
                            song.addEventListener('play', () => {
                                this.isPlaying = true;
                                this.pendingMusicUnlock = false;
                            });
                            song.addEventListener('pause', () => {
                                this.isPlaying = false;
                            });
                        }

                        const warmHeroMedia = () => {
                            const heroVideo = document.getElementById('heroVideo');
                            if (heroVideo && heroVideo.preload === 'none') {
                                heroVideo.preload = 'metadata';
                            }
                            const songEl = document.getElementById('song');
                            if (songEl && songEl.preload === 'none') {
                                songEl.preload = 'metadata';
                            }
                        };
                        if (document.readyState === 'complete') {
                            setTimeout(warmHeroMedia, 300);
                        } else {
                            window.addEventListener('load', warmHeroMedia, { once: true });
                        }
                    },

                    openInvitation() {
                        if (this.isOpened) return;
                        this.isOpened = true;
                        document.body.classList.remove('stop-scrolling');

                        this.playMusic();

                        const heroVideo = document.getElementById('heroVideo');
                        if (heroVideo) {
                            heroVideo.muted = true;
                            heroVideo.defaultMuted = true;
                            heroVideo.playsInline = true;
                            heroVideo.preload = 'auto';
                            heroVideo.loop = false;
                            if (heroVideo.readyState >= 1) {
                                heroVideo.currentTime = 0;
                            }
                            heroVideo.play().then(() => {
                                const songEl = document.getElementById('song');
                                if (songEl && songEl.paused && !this.userPausedMusic) {
                                    this.playMusic();
                                }
                            }).catch(() => { });
                        }

                        // Reveal hero text synchronized with 3D motion reveal (~9.5s) or fallback
                        clearTimeout(this.heroFallbackTimer);
                        this.heroFallbackTimer = setTimeout(() => {
                            this.revealHeroText();
                        }, 9500);

                        // Safety fallback to start auto-scroll after video duration (~20.5s)
                        clearTimeout(this.autoScrollFallbackTimer);
                        this.autoScrollFallbackTimer = setTimeout(() => {
                            this.triggerInitialAutoScroll();
                        }, 20500);

                        this.scrollTo('.motionSection', 'instant');
                    },

                    revealHeroText() {
                        if (this.heroTextVisible) return;
                        this.heroTextVisible = true;
                        this.$nextTick(() => {
                            this.revealElementsIn(document.querySelector('.motionText'));
                        });
                    },

                    triggerInitialAutoScroll() {
                        if (!this.isOpened || this.hasAutoScrolledOnce) return;
                        this.hasAutoScrolledOnce = true;
                        if (!this.autoScrolling) {
                            this.autoScrolling = true;
                            this.startScroll();
                            this.showToast('Auto scroll aktif');
                        }
                    },

                    revealElementsIn(container) {
                        if (!container) return;
                        container.querySelectorAll('.elementor-invisible').forEach((el) => {
                            this.animateElement(el);
                        });
                    },

                    animateElement(el) {
                        if (!el || !el.classList.contains('elementor-invisible')) return;
                        let anim = 'fadeInUp';
                        let delay = 0;
                        try {
                            const raw = el.getAttribute('data-settings');
                            if (raw) {
                                const cfg = JSON.parse(raw);
                                anim = cfg._animation || cfg._animation_mobile || 'fadeInUp';
                                delay = parseInt(cfg._animation_delay || 0, 10) || 0;
                            }
                        } catch (e) { }

                        setTimeout(() => {
                            el.classList.remove('elementor-invisible');
                            el.classList.add('animated', anim);
                        }, delay);
                    },

                    initObservers() {
                        // Elementor entrance animations (defer #cover to initial timer and .motionText to revealHeroText)
                        const animTargets = Array.from(document.querySelectorAll('.elementor-invisible')).filter(
                            (el) => !el.closest('.motionText') && !el.closest('#cover')
                        );
                        if ('IntersectionObserver' in window) {
                            const animObserver = new IntersectionObserver((entries) => {
                                entries.forEach((entry) => {
                                    if (entry.isIntersecting) {
                                        this.animateElement(entry.target);
                                        animObserver.unobserve(entry.target);
                                    }
                                });
                            }, { threshold: 0.08, rootMargin: '0px 0px -20px 0px' });

                            animTargets.forEach((el) => animObserver.observe(el));
                        } else {
                            animTargets.forEach((el) => this.animateElement(el));
                        }

                        // Jet Timeline items & progress line
                        const timelineItems = Array.from(document.querySelectorAll('#story .jet-timeline-item'));
                        const progressEl = document.querySelector('#story .jet-timeline__line-progress');
                        if (timelineItems.length > 0 && 'IntersectionObserver' in window) {
                            const tlObserver = new IntersectionObserver((entries) => {
                                entries.forEach((entry) => {
                                    if (entry.isIntersecting) {
                                        entry.target.classList.add('is--show', 'is--active');
                                        if (progressEl) {
                                            const activeCount = timelineItems.filter((item) => item.classList.contains('is--active')).length;
                                            const pct = Math.min(100, Math.round((activeCount / timelineItems.length) * 100));
                                            progressEl.style.height = pct + '%';
                                        }
                                    }
                                });
                            }, { threshold: 0.18 });

                            timelineItems.forEach((item) => tlObserver.observe(item));
                        } else {
                            timelineItems.forEach((item) => item.classList.add('is--show', 'is--active'));
                            if (progressEl) progressEl.style.height = '100%';
                        }
                    },

                    initLotties() {
                        if (typeof window.lottie === 'undefined') {
                            if (this.lottieRetries < 40) {
                                this.lottieRetries++;
                                setTimeout(() => this.initLotties(), 150);
                            }
                            return;
                        }

                        const rootEl = this.$root.closest('.bugis-theme-root') || document.querySelector('.bugis-theme-root');
                        const fallbackJsonUrl = (rootEl && rootEl.dataset.lottieUrl) ? rootEl.dataset.lottieUrl : (config.lottieUrl || '');

                        document.querySelectorAll('.elementor-widget-lottie .e-lottie__animation').forEach((target) => {
                            if (target.dataset.lottieLoaded === '1') return;
                            const widget = target.closest('.elementor-widget-lottie');
                            let jsonUrl = widget?.dataset?.lottieUrl || fallbackJsonUrl;
                            if (!jsonUrl) return;

                            target.dataset.lottieLoaded = '1';
                            target.innerHTML = '';
                            try {
                                window.lottie.loadAnimation({
                                    container: target,
                                    renderer: 'svg',
                                    loop: true,
                                    autoplay: true,
                                    path: jsonUrl,
                                });
                            } catch (e) { }
                        });
                    },

                    initVideoReplay() {
                        const heroVideo = document.getElementById('heroVideo');
                        const heroSection = document.querySelector('.motionSection');
                        if (!heroVideo || !heroSection) return;

                        heroVideo.loop = false;

                        heroVideo.addEventListener('timeupdate', () => {
                            if (heroVideo.currentTime >= 9.2 && !this.heroTextVisible) {
                                this.revealHeroText();
                            }
                            if (heroVideo.duration && heroVideo.currentTime >= (heroVideo.duration - 0.35)) {
                                this.triggerInitialAutoScroll();
                            }
                        });

                        heroVideo.addEventListener('ended', () => {
                            this.revealHeroText();
                            this.triggerInitialAutoScroll();
                        });

                        let hasLeftHero = false;
                        if ('IntersectionObserver' in window) {
                            const videoObserver = new IntersectionObserver((entries) => {
                                entries.forEach((entry) => {
                                    if (!entry.isIntersecting) {
                                        hasLeftHero = true;
                                        heroVideo.pause();
                                    } else if (hasLeftHero && this.isOpened) {
                                        hasLeftHero = false;
                                        heroVideo.currentTime = 0;
                                        heroVideo.play().catch(() => { });
                                    }
                                });
                            }, { threshold: 0.25 });

                            videoObserver.observe(heroSection);
                        }
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
                            if (e.target && e.target.closest && (e.target.closest('.zipill-panel') || e.target.closest('.bugis-bottom-dock'))) {
                                return;
                            }
                            this.autoScrolling = false;
                            this.stopScroll();
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

                    playMusic() {
                        const audio = document.getElementById('song');
                        if (!audio) return;

                        this.userPausedMusic = false;
                        if (audio.preload !== 'auto') {
                            audio.preload = 'auto';
                        }
                        if (audio.readyState === 0 && !audio.currentSrc && audio.src) {
                            audio.load();
                        }

                        try {
                            const playPromise = audio.play();
                            if (playPromise && typeof playPromise.then === 'function') {
                                playPromise.then(() => {
                                    this.isPlaying = true;
                                    this.pendingMusicUnlock = false;
                                }).catch(() => {
                                    this.isPlaying = false;
                                    this.armIosAudioUnlock();
                                });
                            } else {
                                this.isPlaying = !audio.paused;
                            }
                        } catch (e) {
                            this.isPlaying = false;
                            this.armIosAudioUnlock();
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
                            const audio = document.getElementById('song');
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
                                    }).catch(() => { });
                                }
                            } catch (e) { }
                        };

                        window.addEventListener('touchend', tryUnlock, { capture: true, passive: true });
                        window.addEventListener('click', tryUnlock, { capture: true, passive: true });
                    },

                    pauseMusic() {
                        this.userPausedMusic = true;
                        this.pendingMusicUnlock = false;
                        const audio = document.getElementById('song');
                        if (audio) {
                            audio.pause();
                            this.isPlaying = false;
                        }
                    },

                    toggleMusic() {
                        if (this.isPlaying) {
                            this.pauseMusic();
                        } else {
                            this.playMusic();
                        }
                    },

                    toggleMenu() {
                        this.menuCollapsed = !this.menuCollapsed;
                    },

                    scrollTo(selector, behavior = 'smooth') {
                        const el = document.querySelector(selector);
                        if (el) {
                            el.scrollIntoView({ behavior, block: 'start' });
                        }
                    },

                    toggleAutoScroll() {
                        this.autoScrolling = !this.autoScrolling;
                        if (this.autoScrolling) {
                            this.startScroll();
                        } else {
                            this.stopScroll();
                        }
                    },

                    async requestWakeLock() {
                        if (!this.autoScrolling || !('wakeLock' in navigator) || !navigator.wakeLock) return;
                        if (this.wakeLockSentinel && !this.wakeLockSentinel.released) return;

                        try {
                            const sentinel = await navigator.wakeLock.request('screen');
                            if (!this.autoScrolling) {
                                sentinel.release().catch(() => { });
                                return;
                            }
                            this.wakeLockSentinel = sentinel;
                            this.wakeLockActive = true;
                            sentinel.addEventListener('release', () => {
                                if (this.wakeLockSentinel === sentinel) {
                                    this.wakeLockSentinel = null;
                                    this.wakeLockActive = false;
                                }
                            });
                        } catch (e) { }
                    },

                    releaseWakeLock() {
                        const sentinel = this.wakeLockSentinel;
                        this.wakeLockSentinel = null;
                        this.wakeLockActive = false;
                        if (sentinel && !sentinel.released) {
                            sentinel.release().catch(() => { });
                        }
                    },

                    startScroll() {
                        if (this.autoScrollFrame) {
                            cancelAnimationFrame(this.autoScrollFrame);
                            this.autoScrollFrame = null;
                        }
                        this.requestWakeLock();
                        const step = () => {
                            if (!this.autoScrolling) return;
                            const distance = 1.2 * this.scrollSpeed;
                            window.scrollBy({ top: distance, behavior: 'instant' });
                            if ((window.innerHeight + window.scrollY) >= document.documentElement.scrollHeight - 8) {
                                this.autoScrolling = false;
                                this.stopScroll();
                                return;
                            }
                            this.autoScrollFrame = requestAnimationFrame(step);
                        };
                        this.autoScrollFrame = requestAnimationFrame(step);
                    },

                    stopScroll() {
                        if (this.autoScrollFrame) {
                            cancelAnimationFrame(this.autoScrollFrame);
                            this.autoScrollFrame = null;
                        }
                        this.releaseWakeLock();
                    },

                    cycleScrollSpeed() {
                        this.scrollSpeed = this.scrollSpeed >= 3 ? 1 : this.scrollSpeed + 1;
                    },

                    openLightbox(url) {
                        this.lightboxImg = url;
                        this.lightboxOpen = true;
                    },

                    copyToClipboard(text) {
                        if (!text) return;
                        if (navigator.clipboard && navigator.clipboard.writeText) {
                            navigator.clipboard.writeText(text).then(() => {
                                this.showToast('Berhasil disalin: ' + text);
                            }).catch(() => this.fallbackCopy(text));
                        } else {
                            this.fallbackCopy(text);
                        }
                    },

                    fallbackCopy(text) {
                        try {
                            const ta = document.createElement('textarea');
                            ta.value = text;
                            ta.style.position = 'fixed';
                            ta.style.opacity = '0';
                            document.body.appendChild(ta);
                            ta.select();
                            document.execCommand('copy');
                            ta.remove();
                            this.showToast('Berhasil disalin: ' + text);
                        } catch (e) {
                            this.showToast('Gagal menyalin');
                        }
                    },

                    showToast(msg) {
                        this.toastMessage = msg;
                        this.toastShow = true;
                        clearTimeout(this.toastTimer);
                        this.toastTimer = setTimeout(() => {
                            this.toastShow = false;
                        }, 2500);
                    },

                    updateCountdown() {
                        const now = Date.now();
                        const distance = this.targetTime - now;
                        if (distance <= 0) {
                            this.days = 0;
                            this.hours = 0;
                            this.minutes = 0;
                            this.seconds = 0;
                            return;
                        }
                        this.days = Math.floor(distance / (1000 * 60 * 60 * 24));
                        this.hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                        this.minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                        this.seconds = Math.floor((distance % (1000 * 60)) / 1000);
                    }
                };
            };
        </script>
    @endpush
@endonce

@php
    $assetBase = 'themes/3d-adat-bugis-02/assets/';
    $styles = $invitation->custom_styles ?? [];
    $eventType = $invitation->event_type ?? ($styles['event_type'] ?? 'both');
    $nameOrder = $styles['name_order'] ?? 'groom_first';

    // Couple data & ordering
    $groomNick = $invitation->groom_nickname ?: 'Diky';
    $groomFull = $invitation->groom_name ?: 'M. Diky Alhafizh';
    $brideNick = $invitation->bride_nickname ?: 'Yuni';
    $brideFull = $invitation->bride_name ?: 'Tri Wahyuni';

    $formatParents = function (?string $father, ?string $mother, string $defaultFather = 'Nama Ayah', string $defaultMother = 'Nama Ibu'): string {
        $f = trim((string) $father);
        $m = trim((string) $mother);
        if ($f === '' && $m === '') {
            return 'Bpk. ' . $defaultFather . ' & Ibu ' . $defaultMother;
        }
        $fFormatted = $f !== '' ? (preg_match('/^(bpk|bapak|h\.|hj\.|prof\.|dr\.)/i', $f) ? $f : 'Bpk. ' . $f) : '';
        $mFormatted = $m !== '' ? (preg_match('/^(ibu|ny\.|hj\.|prof\.|dr\.)/i', $m) ? $m : 'Ibu ' . $m) : '';
        return collect([$fFormatted, $mFormatted])->filter()->implode(' & ');
    };

    $groomParents = $formatParents($invitation->groom_father, $invitation->groom_mother, 'Lorem', 'Lorema');
    $brideParents = $formatParents($invitation->bride_father, $invitation->bride_mother, 'Lorem', 'Lorema');

    $groomIgRaw = ltrim(trim((string) ($invitation->instagram_groom ?? '')), '@');
    $brideIgRaw = ltrim(trim((string) ($invitation->instagram_bride ?? '')), '@');

    $groomPhotoUrl = $invitation->groom_photo ? img_url($invitation->groom_photo) : null;
    $bridePhotoUrl = $invitation->bride_photo ? img_url($invitation->bride_photo) : null;

    $groomChildLabel = trim((string) ($styles['groom_child_order'] ?? '')) !== ''
        ? trim((string) $styles['groom_child_order'])
        : 'Putra Pertama Dari';
    $brideChildLabel = trim((string) ($styles['bride_child_order'] ?? '')) !== ''
        ? trim((string) $styles['bride_child_order'])
        : 'Putri Pertama Dari';

    if ($nameOrder === 'bride_first') {
        $firstPerson = [
            'nick' => $brideNick,
            'full' => $brideFull,
            'photo' => $bridePhotoUrl,
            'childLabel' => $brideChildLabel,
            'parents' => $brideParents,
            'ig' => $brideIgRaw,
            'spanClass' => 'wds-jfb-typography-meta-_nama_lengkap_wanita',
            'imgClass' => 'attachment-full size-full wp-image-866',
        ];
        $secondPerson = [
            'nick' => $groomNick,
            'full' => $groomFull,
            'photo' => $groomPhotoUrl,
            'childLabel' => $groomChildLabel,
            'parents' => $groomParents,
            'ig' => $groomIgRaw,
            'spanClass' => 'wds-jfb-typography-meta-_nama_lengkap_pria',
            'imgClass' => 'attachment-full size-full wp-image-867',
        ];
        $firstNick = $brideNick;
        $secondNick = $groomNick;
    } else {
        $firstPerson = [
            'nick' => $groomNick,
            'full' => $groomFull,
            'photo' => $groomPhotoUrl,
            'childLabel' => $groomChildLabel,
            'parents' => $groomParents,
            'ig' => $groomIgRaw,
            'spanClass' => 'wds-jfb-typography-meta-_nama_lengkap_pria',
            'imgClass' => 'attachment-full size-full wp-image-867',
        ];
        $secondPerson = [
            'nick' => $brideNick,
            'full' => $brideFull,
            'photo' => $bridePhotoUrl,
            'childLabel' => $brideChildLabel,
            'parents' => $brideParents,
            'ig' => $brideIgRaw,
            'spanClass' => 'wds-jfb-typography-meta-_nama_lengkap_wanita',
            'imgClass' => 'attachment-full size-full wp-image-866',
        ];
        $firstNick = $groomNick;
        $secondNick = $brideNick;
    }

    $coverSubtitle = !empty($styles['cover_subtitle']) ? $styles['cover_subtitle'] : 'The Wedding Of';
    $coupleTitle = !empty($styles['cover_title']) ? $styles['cover_title'] : ($firstNick . ' & ' . $secondNick);
    $coverPhotoUrl = $invitation->cover_image ? img_url($invitation->cover_image) : null;
    $hashtag = $styles['hashtag'] ?? $invitation->hashtag ?? ('#' . preg_replace('/\s+/', '', $firstNick . $secondNick));

    // Dates, countdown & calendar
    $mainDateObj = $invitation->event_date;
    if (!$mainDateObj && !empty($styles['event_date'])) {
        try {
            $mainDateObj = \Carbon\Carbon::parse($styles['event_date']);
        } catch (\Throwable $e) {
        }
    }
    if (!$mainDateObj) {
        if ($eventType === 'resepsi_only') {
            $mainDateObj = $invitation->resepsi_date ? \Carbon\Carbon::parse($invitation->resepsi_date) : null;
        } else {
            $mainDateObj = $invitation->akad_date
                ? \Carbon\Carbon::parse($invitation->akad_date)
                : ($invitation->resepsi_date ? \Carbon\Carbon::parse($invitation->resepsi_date) : null);
        }
    }
    $displayDate = $mainDateObj ? $mainDateObj->format('d . m . Y') : '03 . 02 . 2027';

    // Use event time if available for countdown precision
    $mainTimeRaw = $eventType === 'resepsi_only'
        ? ($invitation->resepsi_time ?: '10:00:00')
        : ($invitation->akad_time ?: $invitation->resepsi_time ?: '08:00:00');
    $countdownDateObj = $mainDateObj ? $mainDateObj->copy() : \Carbon\Carbon::parse('2027-02-03');
    if (preg_match('/^(\d{1,2}):(\d{2})/', (string) $mainTimeRaw, $tm)) {
        $countdownDateObj->setTime((int) $tm[1], (int) $tm[2], 0);
    } else {
        $countdownDateObj->setTime(8, 0, 0);
    }
    $targetTimestamp = $countdownDateObj->timestamp;

    $mainAddress = ($eventType === 'resepsi_only')
        ? ($invitation->resepsi_address ?? $invitation->resepsi_venue ?? '')
        : ($invitation->akad_address ?? $invitation->akad_venue ?? $invitation->resepsi_address ?? '');

    $googleCalendarUrl = 'https://www.google.com/calendar/render?' . http_build_query([
        'action' => 'TEMPLATE',
        'text' => 'Pernikahan ' . $coupleTitle,
        'dates' => $countdownDateObj->copy()->utc()->format('Ymd\THis\Z') . '/' . $countdownDateObj->copy()->utc()->addHours(4)->format('Ymd\THis\Z'),
        'details' => 'Undangan Pernikahan ' . $groomFull . ' & ' . $brideFull,
        'location' => $mainAddress,
    ]);

    $formatEventTime = function (?string $time, string $fallback = '08:00'): string {
        $t = trim((string) $time);
        if ($t === '') {
            return $fallback . ' WIB - Selesai';
        }
        if (preg_match('/^(\d{1,2}:\d{2})/', $t, $m)) {
            return $m[1] . ' WIB - Selesai';
        }
        return str_contains(strtolower($t), 'selesai') ? $t : ($t . ' - Selesai');
    };

    // Music
    $musicRaw = $invitation->background_music;
    if (!empty($musicRaw)) {
        $musicSrc = str_starts_with($musicRaw, 'http')
            ? $musicRaw
            : img_url($musicRaw);
    } else {
        $musicSrc = asset($assetBase . '759IcESZZqg.mp3');
    }
    $musicExt = strtolower(pathinfo((string) parse_url($musicSrc, PHP_URL_PATH), PATHINFO_EXTENSION));
    $musicMime = match ($musicExt) {
        'wav' => 'audio/wav',
        'ogg' => 'audio/ogg',
        'm4a' => 'audio/mp4',
        'aac' => 'audio/aac',
        default => 'audio/mpeg',
    };

    // Event & Feature Visibility
    $showAkad = in_array($eventType, ['akad_only', 'both']) && ($invitation->akad_date || $invitation->akad_venue || $invitation->akad_maps_link || !isset($styles['event_type']));
    $showResepsi = in_array($eventType, ['resepsi_only', 'both']) && ($invitation->resepsi_date || $invitation->resepsi_venue || $invitation->resepsi_maps_link || !isset($styles['event_type']));
    $unduhDate = $styles['unduh_mantu_date'] ?? $invitation->unduh_mantu_date ?? null;
    $unduhVenue = $styles['unduh_mantu_venue'] ?? $invitation->unduh_mantu_venue ?? null;
    $showUnduhMantu = !empty($unduhDate) || !empty($unduhVenue);
    $streamingLink = $styles['streaming_link'] ?? $invitation->streaming_link ?? null;
    $showStreaming = !empty($streamingLink);

    $showCountdown = (bool) ($styles['countdown_enabled'] ?? true);
    $showGallery = (bool) ($invitation->enable_gallery ?? true);
    $showGift = (bool) ($invitation->enable_gift ?? !empty($invitation->bank_accounts));
    $showRsvp = (bool) (($invitation->enable_rsvp ?? true) || ($invitation->enable_wishes ?? true));

    // Text blocks
    $welcomeMessage = !empty($styles['welcome_message'])
        ? $styles['welcome_message']
        : 'Dengan memohon rahmat dan ridho Allah Swt. kami bermaksud mengundang Bapak/Ibu/Saudara/i untuk menghadiri acara pernikahan putra-putri kami:';
    $quoteTitle = trim((string) ($styles['quote_title'] ?? '')) !== ''
        ? $styles['quote_title']
        : 'WE FOUND LOVE';
    $quranVerse = trim((string) ($styles['quran_verse'] ?? '')) !== ''
        ? $styles['quran_verse']
        : '“Dan di antara tanda-tanda (kebesaran)-Nya ialah Dia menciptakan pasangan-pasangan untukmu dari jenismu sendiri, agar kamu cenderung dan merasa tenteram kepadanya, dan Dia menjadikan di antaramu rasa kasih dan sayang.”';
    $quranRef = array_key_exists('quran_ref', $styles)
        ? trim((string) ($styles['quran_ref'] ?? ''))
        : 'Q.S Ar-Rum : 21';

    // Gallery & Ken Burns Images
    $uploadedPhotos = $invitation->relationLoaded('photos') ? $invitation->photos : $invitation->photos()->get();
    $galleryPhotos = $uploadedPhotos->count() > 0
        ? $uploadedPhotos->map(fn($p) => img_url($p->path))->values()->all()
        : [];
    $showGallery = $showGallery && count($galleryPhotos) > 0;

    $quoteImageRaw = $styles['quote_image'] ?? null;
    $quotePhotoUrl = !empty($quoteImageRaw)
        ? img_url($quoteImageRaw)
        : null;

    $kenBurns1Slides = $uploadedPhotos->count() >= 2
        ? [img_url($uploadedPhotos[0]->path), img_url($uploadedPhotos[1]->path)]
        : [];

    $kenBurns2Slides = $uploadedPhotos->count() >= 4
        ? [img_url($uploadedPhotos[2]->path), img_url($uploadedPhotos[3]->path)]
        : [];

    // Love Story
    $defaultStories = [
        ['date' => '2023', 'title' => 'Awal yang Gak Disangka', 'description' => 'Semua berawal dari hal sederhana—entah itu chat random, ketemu tanpa sengaja, atau cuma “iseng kenal”. Tapi dari situ, perlahan tumbuh rasa yang gak pernah direncanakan.'],
        ['date' => '2024', 'title' => 'Makin Dekat, Makin Nyaman', 'description' => 'Hari-hari mulai diisi dengan cerita, tawa, dan perhatian kecil. Dari yang awalnya biasa aja, jadi tempat pulang paling nyaman.'],
        ['date' => '2025', 'title' => 'Melewati Segalanya Bareng', 'description' => 'Gak selalu mulus, ada beda pendapat, ada jarak, ada ujian. Tapi justru di situ, cinta makin kuat karena selalu pilih untuk bertahan.'],
        ['date' => '2026', 'title' => 'Memilih Selamanya', 'description' => 'Sampai akhirnya sadar, “ini orangnya.” Bukan cuma untuk sekarang, tapi untuk semua hari ke depan. Dari dua cerita, jadi satu perjalanan selamanya.'],
    ];
    $filteredStories = (is_array($invitation->love_story) && !empty($invitation->love_story))
        ? array_values(array_filter($invitation->love_story, function ($story) {
            if (!is_array($story)) {
                return false;
            }
            return trim((string) ($story['title'] ?? '')) !== ''
                || trim((string) ($story['description'] ?? '')) !== ''
                || trim((string) ($story['date'] ?? '')) !== '';
        }))
        : [];
    $rawStories = !empty($filteredStories)
        ? $filteredStories
        : (!$invitation->exists ? $defaultStories : []);
    $loveStories = collect($rawStories)->values()->map(function ($story, $idx) use ($galleryPhotos) {
        $fallbackImg = count($galleryPhotos) > 0 ? $galleryPhotos[$idx % count($galleryPhotos)] : null;
        return [
            'date' => $story['date'] ?? date('Y'),
            'title' => $story['title'] ?? 'Momen Spesial',
            'description' => $story['description'] ?? '',
            'image' => !empty($story['image']) ? $story['image'] : $fallbackImg,
        ];
    })->all();
    $showLoveStory = count($loveStories) > 0;

    // Wedding Gift Accounts
    $giftAddress = $styles['gift_address'] ?? $invitation->gift_address ?? $mainAddress;
    $defaultBanks = [
        ['bank' => 'BCA', 'account_number' => '1234567890', 'account_name' => $groomFull],
        ['bank' => 'BNI', 'account_number' => '1234567890', 'account_name' => $brideFull],
    ];
    $bankAccounts = (!empty($invitation->bank_accounts) && is_array($invitation->bank_accounts))
        ? array_values(array_filter($invitation->bank_accounts, fn($acc) => !empty($acc['account_number']) || !empty($acc['bank'])))
        : ($showGift && !$invitation->exists ? $defaultBanks : []);

    $resolveBankLogo = function (?string $bankName) use ($assetBase): ?string {
        $b = strtolower(trim((string) $bankName));
        if (str_contains($b, 'bca syariah') || $b === 'bcas') {
            return asset($assetBase . 'logo-bcas-full-color-1.png');
        }
        if (str_contains($b, 'bca')) {
            return asset($assetBase . '1200px-Bank_Central_Asia.svg-1.png');
        }
        if (str_contains($b, 'bni')) {
            return asset($assetBase . 'bank_bni-1.png');
        }
        if (str_contains($b, 'aceh')) {
            return asset($assetBase . 'bank_bank-aceh-1.png');
        }
        return null;
    };

    $lottieJsonUrl = asset($assetBase . 'animation_lnbjd092-1-1-1.json');
    $wishesList = $wishes ?? collect([]);
    $wishesCount = $totalWishesCount ?? $wishesList->count();
@endphp

<div class="bugis-theme-root elementor-kit-7 elementor-page-346" data-lottie-url="{{ $lottieJsonUrl }}"
    x-data="bugisTheme({{ $targetTimestamp }})">

    <!-- Elementor Page Wrapper -->
    <div data-elementor-type="single-post" data-elementor-id="346"
        class="elementor elementor-346 elementor-location-single post-759 post type-post status-publish format-standard has-post-thumbnail hentry category-3d-motion 3d-motion-3d-adat-bugis-02">

        <!-- Audio Element -->
        <div wire:ignore style="position: absolute; width: 0; height: 0; overflow: hidden; opacity: 0; pointer-events: none;" aria-hidden="true">
            <audio id="song" loop preload="none" playsinline webkit-playsinline src="{{ $musicSrc }}">
                <source src="{{ $musicSrc }}" type="{{ $musicMime }}">
            </audio>
        </div>

        <!-- Outer Elementor Container: 2b26d455 -->
        <div class="has_eae_slider elementor-element elementor-element-2b26d455 e-con-full e-flex e-con e-parent">

            <!-- 1. Desktop Left Sticky Sidebar Column (7a2531fd) -->
            <div id="sidebar"
                class="has_eae_slider elementor-element elementor-element-7a2531fd elementor-hidden-mobile e-con-full e-flex e-con e-child"
                data-e-type="container" data-element_type="container" data-id="7a2531fd">
                <div class="elementor-element elementor-element-3e314f4a elementor-widget elementor-widget-heading"
                    data-e-type="widget" data-element_type="widget" data-id="3e314f4a"
                    data-widget_type="heading.default">
                    <p class="elementor-heading-title elementor-size-default">{{ $coverSubtitle }}</p>
                </div>
                <div class="elementor-element elementor-element-41dfae8f elementor-widget elementor-widget-heading"
                    data-e-type="widget" data-element_type="widget" data-id="41dfae8f"
                    data-widget_type="heading.default">
                    <h2 class="elementor-heading-title elementor-size-default">{{ $coupleTitle }}</h2>
                </div>
                <div class="elementor-element elementor-element-2b80dfdf elementor-widget elementor-widget-heading"
                    data-e-type="widget" data-element_type="widget" data-id="2b80dfdf"
                    data-widget_type="heading.default">
                    <p class="elementor-heading-title elementor-size-default">{{ $displayDate }}</p>
                </div>
            </div>

            <!-- Main Right / Mobile Scrollable Content Column (35ac074c) -->
            <div class="has_eae_slider elementor-element elementor-element-35ac074c e-con-full e-flex e-con e-child"
                data-e-type="container" data-element_type="container" data-id="35ac074c">

                <!-- 2. Opening Cover Overlay (f9feda1 / #cover) -->
                <div id="cover" :class="{ 'cover-hidden': isOpened }"
                    class="has_eae_slider elementor-element elementor-element-f9feda1 e-con-full cover e-flex e-con e-child"
                    data-e-type="container" data-element_type="container" data-id="f9feda1">
                    <div class="has_eae_slider elementor-element elementor-element-132bad9a e-con-full e-flex e-con e-child"
                        data-e-type="container" data-element_type="container" data-id="132bad9a">
                        <div class="elementor-element elementor-element-2f225040 animated-slow elementor-invisible elementor-widget elementor-widget-heading"
                            data-settings='{"_animation":"zoomIn"}' data-widget_type="heading.default">
                            <h2 class="elementor-heading-title elementor-size-default">{{ $coverSubtitle }}</h2>
                        </div>
                        <div class="elementor-element elementor-element-423e4dc1 animated-slow custom-font-cover elementor-invisible elementor-widget elementor-widget-heading"
                            data-settings='{"_animation":"zoomIn"}' data-widget_type="heading.default">
                            <h2 class="elementor-heading-title elementor-size-default">{{ $coupleTitle }}</h2>
                        </div>
                        @if($coverPhotoUrl)
                            <div class="elementor-element elementor-element-2ec0975a animated-slow elementor-invisible elementor-widget elementor-widget-image"
                                data-settings='{"_animation":"zoomIn","_animation_delay":200,"_animation_mobile":"zoomIn"}'
                                data-widget_type="image.default">
                                <div class="bugis-cover-arch-frame">
                                    <img alt="{{ $coupleTitle }}" class="attachment-full size-full wp-image-862"
                                        fetchpriority="high" decoding="async"
                                        src="{{ $coverPhotoUrl }}" />
                                </div>
                            </div>
                        @endif
                        <div class="elementor-element elementor-element-325a7260 animated-slow elementor-invisible elementor-widget elementor-widget-heading"
                            data-settings='{"_animation":"zoomIn"}' data-widget_type="heading.default">
                            <h2 class="elementor-heading-title elementor-size-default">Kepada Yth:
                                <br />Bapak/Ibu/Saudara/i</h2>
                        </div>
                        <div class="elementor-element elementor-element-30ed7aeb animated-slow namatamu elementor-invisible elementor-widget elementor-widget-heading"
                            data-settings='{"_animation":"zoomIn"}' data-widget_type="heading.default">
                            <h2 class="elementor-heading-title elementor-size-default">
                                {{ $guestName ?? 'Tamu Undangan' }}</h2>
                        </div>
                        <div id="tombolbuka" :class="{ 'btnVisibleAfterLoad': coverBtnVisible }"
                            class="elementor-element elementor-element-46720f30 elementor-align-center animated-slow tombolbuka elementor-invisible elementor-widget elementor-widget-button"
                            data-settings='{"_animation":"zoomIn"}' data-widget_type="button.default">
                            <a @click.prevent="openInvitation()"
                                class="elementor-button elementor-button-link elementor-size-sm" href="#sc">
                                <span class="elementor-button-content-wrapper">
                                    <span class="elementor-button-icon">
                                        <i aria-hidden="true" class="far fa-envelope-open"></i>
                                    </span>
                                    <span class="elementor-button-text">Buka Undangan</span>
                                </span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- 3. 3D Motion Hero Section (40db289a / .motionSection) -->
                <div id="hero"
                    class="has_eae_slider elementor-element elementor-element-40db289a e-con-full motionSection e-flex e-con e-child"
                    data-e-type="container" data-element_type="container" data-id="40db289a">
                    <div class="elementor-background-video-container" wire:ignore>
                        <video class="elementor-background-video-hosted" id="heroVideo" muted playsinline preload="none"
                            poster="{{ asset($assetBase . 'BUGISMAROONFALLBACK.jpg') }}" role="presentation">
                            <source src="{{ asset($assetBase . 'BUGIS-MAROON.mp4') }}" type="video/mp4" />
                        </video>
                    </div>
                    <div :class="{ 'motionText--visible': heroTextVisible }"
                        class="has_eae_slider elementor-element elementor-element-1aa90ea9 e-con-full motionText e-flex e-con e-child"
                        data-e-type="container" data-element_type="container" data-id="1aa90ea9">
                        <div class="elementor-element elementor-element-761cee93 elementor-absolute elementor-align-center elementor-widget elementor-widget-lottie"
                            data-lottie-url="{{ $lottieJsonUrl }}" data-widget_type="lottie.default" wire:ignore>
                            <div class="e-lottie__container">
                                <div class="e-lottie__animation"></div>
                            </div>
                        </div>
                        <div class="elementor-element elementor-element-729d7126 elementor-invisible elementor-widget elementor-widget-heading"
                            data-settings='{"_animation":"zoomIn","_animation_delay":200}'
                            data-widget_type="heading.default">
                            <h2 class="elementor-heading-title elementor-size-default">{{ $coverSubtitle }}</h2>
                        </div>
                        <div class="elementor-element elementor-element-53c49100 animated-slow wdsdv-enabled--yes custom-font-opening elementor-invisible elementor-widget elementor-widget-heading"
                            data-settings='{"_animation":"zoomIn","_animation_delay":250}'
                            data-widget_type="heading.default">
                            <h1 class="elementor-heading-title elementor-size-default">{{ $firstNick }}</h1>
                        </div>
                        <div class="elementor-element elementor-element-2117e8c7 elementor-invisible elementor-widget elementor-widget-heading"
                            data-settings='{"_animation":"zoomIn","_animation_delay":300}'
                            data-widget_type="heading.default">
                            <h1 class="elementor-heading-title elementor-size-default">&amp;</h1>
                        </div>
                        <div class="elementor-element elementor-element-7689559c animated-slow wdsdv-enabled--yes custom-font-opening elementor-invisible elementor-widget elementor-widget-heading"
                            data-settings='{"_animation":"zoomIn","_animation_delay":350}'
                            data-widget_type="heading.default">
                            <h1 class="elementor-heading-title elementor-size-default">{{ $secondNick }}</h1>
                        </div>
                        <div class="elementor-element elementor-element-2bc135a1 elementor-invisible elementor-widget elementor-widget-heading"
                            data-settings='{"_animation":"zoomIn","_animation_delay":400}'
                            data-widget_type="heading.default">
                            <h1 class="elementor-heading-title elementor-size-default">{{ $displayDate }}</h1>
                        </div>
                        <div class="elementor-element elementor-element-477aca49 elementor-invisible elementor-widget elementor-widget-heading"
                            data-settings='{"_animation":"zoomIn","_animation_delay":450}'
                            data-widget_type="heading.default">
                            <h1 class="elementor-heading-title elementor-size-default">{{ $hashtag }}</h1>
                        </div>
                        <div class="elementor-element elementor-element-8c314b6 elementor-align-center elementor-mobile-align-center elementor-invisible elementor-widget elementor-widget-button"
                            data-settings='{"_animation_mobile":"fadeInDown","_animation":"fadeInDown","_animation_delay":500}'
                            data-widget_type="button.default">
                            <a @click.prevent="scrollTo('#sc')"
                                class="elementor-button elementor-button-link elementor-size-sm" href="#sc"
                                aria-label="Scroll ke bawah">
                                <span class="elementor-button-content-wrapper">
                                    <span class="elementor-button-text">I</span>
                                </span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- 4. Quote Section (1b4036b1 / #sc) -->
                <div id="sc"
                    class="has_eae_slider elementor-element elementor-element-1b4036b1 e-con-full e-flex e-con e-child"
                    data-e-type="container" data-element_type="container" data-id="1b4036b1">
                    <div class="has_eae_slider elementor-element elementor-element-6517fc08 e-con-full e-flex e-con e-child"
                        data-e-type="container" data-element_type="container" data-id="6517fc08">
                        @if($quotePhotoUrl)
                            <div class="elementor-element elementor-element-4957ef64 elementor-invisible elementor-widget elementor-widget-image"
                                data-settings='{"_animation":"fadeInDown","_animation_delay":100,"_animation_mobile":"fadeInDown"}'
                                data-widget_type="image.default">
                                <div class="bugis-quote-frame">
                                    <img alt="{{ $coupleTitle }}" class="attachment-full size-full wp-image-863"
                                        loading="lazy" decoding="async"
                                        src="{{ $quotePhotoUrl }}" />
                                </div>
                            </div>
                        @endif
                        <div class="elementor-element elementor-element-29338a02 elementor-widget elementor-widget-heading"
                            data-widget_type="heading.default">
                            <p class="elementor-heading-title elementor-size-default">{{ $quoteTitle }}</p>
                        </div>
                        <div class="elementor-element elementor-element-5cc07038 elementor-invisible elementor-widget elementor-widget-text-editor"
                            data-settings='{"_animation":"fadeInUp","_animation_delay":250}'
                            data-widget_type="text-editor.default">
                            {!! nl2br(e($quranVerse)) !!}
                        </div>
                        @if($quranRef !== '')
                            <div class="elementor-element elementor-element-3c98760a elementor-invisible elementor-widget elementor-widget-text-editor"
                                data-settings='{"_animation":"fadeInUp","_animation_delay":300}'
                                data-widget_type="text-editor.default">
                                {{ $quranRef }}
                            </div>
                        @endif
                    </div>
                </div>

                <!-- 5. Couple Section (25e9fe6 / #couple) -->
                <div id="couple"
                    class="has_eae_slider elementor-element elementor-element-25e9fe6 e-con-full e-flex e-con e-child"
                    data-e-type="container" data-element_type="container" data-id="25e9fe6">
                    <div class="has_eae_slider elementor-element elementor-element-3ca70f12 e-con-full wdsdv-enabled--yes e-flex e-con e-child"
                        data-e-type="container" data-element_type="container" data-id="3ca70f12">
                        <div class="elementor-element elementor-element-1238a045 elementor-invisible elementor-widget elementor-widget-heading"
                            data-settings='{"_animation":"fadeInUp","_animation_delay":200,"_animation_mobile":"fadeInDown"}'
                            data-widget_type="heading.default">
                            <p class="elementor-heading-title elementor-size-default">Assalamualaikum Warahmatullaahi
                                Wabarakaatuh <br />{{ $welcomeMessage }}</p>
                        </div>

                        <!-- Mempelai Pertama -->
                        @if(!empty($firstPerson['photo']))
                            <div class="elementor-element elementor-element-4f01d582 animated-slow elementor-invisible elementor-widget elementor-widget-image"
                                data-settings='{"_animation":"fadeInLeft","_animation_delay":200,"_animation_mobile":"fadeInLeft"}'
                                data-widget_type="image.default">
                                <div class="bugis-couple-frame">
                                    <img alt="{{ $firstPerson['full'] }}" class="{{ $firstPerson['imgClass'] }}"
                                        loading="lazy" decoding="async"
                                        src="{{ $firstPerson['photo'] }}" />
                                </div>
                            </div>
                        @endif
                        <div class="elementor-element elementor-element-1ed755bd custom-font-nama-pendek elementor-widget elementor-widget-heading"
                            data-widget_type="heading.default">
                            <p class="elementor-heading-title elementor-size-default">{{ $firstPerson['nick'] }}</p>
                        </div>
                        <div class="elementor-element elementor-element-5a868f7f custom-font-nama-panjang elementor-widget elementor-widget-heading"
                            data-widget_type="heading.default">
                            <p class="elementor-heading-title elementor-size-default"><span
                                    class="{{ $firstPerson['spanClass'] }}">{{ $firstPerson['full'] }}</span></p>
                        </div>
                        <div class="elementor-element elementor-element-7d28a885 elementor-invisible elementor-widget elementor-widget-heading"
                            data-settings='{"_animation":"fadeInUp","_animation_delay":250,"_animation_mobile":"fadeInDown"}'
                            data-widget_type="heading.default">
                            <p class="elementor-heading-title elementor-size-default">{{ $firstPerson['childLabel'] }}
                            </p>
                        </div>
                        <div class="elementor-element elementor-element-4ebd532b elementor-invisible elementor-widget elementor-widget-heading"
                            data-settings='{"_animation":"fadeInUp","_animation_delay":300,"_animation_mobile":"fadeInDown"}'
                            data-widget_type="heading.default">
                            <p class="elementor-heading-title elementor-size-default">{{ $firstPerson['parents'] }}</p>
                        </div>
                        @if(!empty($firstPerson['ig']))
                            <div class="elementor-element elementor-element-7ceeffae elementor-align-center animated-slow wdsdv-enabled--yes elementor-invisible elementor-widget elementor-widget-button"
                                data-settings='{"_animation":"zoomIn"}' data-widget_type="button.default">
                                <a class="elementor-button elementor-button-link elementor-size-md"
                                    href="https://www.instagram.com/{{ $firstPerson['ig'] }}" target="_blank"
                                    rel="noopener noreferrer">
                                    <span class="elementor-button-content-wrapper">
                                        <span class="elementor-button-text">@ {{ $firstPerson['ig'] }}</span>
                                    </span>
                                </a>
                            </div>
                        @endif

                        <!-- Divider Ornament -->
                        <div class="elementor-element elementor-element-2194bbef elementor-invisible elementor-widget elementor-widget-image"
                            data-settings='{"_animation":"fadeInDown","_animation_delay":100,"_animation_mobile":"fadeInDown"}'
                            data-widget_type="image.default">
                            <img alt="" class="attachment-large size-large wp-image-302" height="870" width="774"
                                loading="lazy" decoding="async"
                                src="{{ asset($assetBase . 'dan-bugis-1.png') }}" />
                        </div>

                        <!-- Mempelai Kedua -->
                        @if(!empty($secondPerson['photo']))
                            <div class="elementor-element elementor-element-3ce2deed animated-slow elementor-invisible elementor-widget elementor-widget-image"
                                data-settings='{"_animation":"fadeInRight","_animation_delay":200,"_animation_mobile":"fadeInRight"}'
                                data-widget_type="image.default">
                                <div class="bugis-couple-frame">
                                    <img alt="{{ $secondPerson['full'] }}" class="{{ $secondPerson['imgClass'] }}"
                                        loading="lazy" decoding="async"
                                        src="{{ $secondPerson['photo'] }}" />
                                </div>
                            </div>
                        @endif
                        <div class="elementor-element elementor-element-74ac10ba custom-font-nama-pendek elementor-widget elementor-widget-heading"
                            data-widget_type="heading.default">
                            <p class="elementor-heading-title elementor-size-default">{{ $secondPerson['nick'] }}</p>
                        </div>
                        <div class="elementor-element elementor-element-63ed8e8e custom-font-nama-panjang elementor-widget elementor-widget-heading"
                            data-widget_type="heading.default">
                            <p class="elementor-heading-title elementor-size-default"><span
                                    class="{{ $secondPerson['spanClass'] }}">{{ $secondPerson['full'] }}</span></p>
                        </div>
                        <div class="elementor-element elementor-element-6450c259 elementor-invisible elementor-widget elementor-widget-heading"
                            data-settings='{"_animation":"fadeInUp","_animation_delay":250,"_animation_mobile":"fadeInDown"}'
                            data-widget_type="heading.default">
                            <p class="elementor-heading-title elementor-size-default">{{ $secondPerson['childLabel'] }}
                            </p>
                        </div>
                        <div class="elementor-element elementor-element-100da207 elementor-invisible elementor-widget elementor-widget-heading"
                            data-settings='{"_animation":"fadeInUp","_animation_delay":300,"_animation_mobile":"fadeInDown"}'
                            data-widget_type="heading.default">
                            <p class="elementor-heading-title elementor-size-default">{{ $secondPerson['parents'] }}</p>
                        </div>
                        @if(!empty($secondPerson['ig']))
                            <div class="elementor-element elementor-element-db60ba5 elementor-align-center animated-slow wdsdv-enabled--yes elementor-invisible elementor-widget elementor-widget-button"
                                data-settings='{"_animation":"zoomIn"}' data-widget_type="button.default">
                                <a class="elementor-button elementor-button-link elementor-size-md"
                                    href="https://www.instagram.com/{{ $secondPerson['ig'] }}" target="_blank"
                                    rel="noopener noreferrer">
                                    <span class="elementor-button-content-wrapper">
                                        <span class="elementor-button-text">@ {{ $secondPerson['ig'] }}</span>
                                    </span>
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- 6. Events Section (50d22c29 / #events) -->
                <div id="events"
                    class="has_eae_slider elementor-element elementor-element-50d22c29 e-con-full e-flex e-con e-child"
                    data-e-type="container" data-element_type="container" data-id="50d22c29">
                    @if($showAkad)
                        <div class="has_eae_slider elementor-element elementor-element-58f2f6ac e-con-full e-flex e-con e-child"
                            data-e-type="container" data-element_type="container" data-id="58f2f6ac">
                            <div class="has_eae_slider elementor-element elementor-element-28543923 e-con-full e-flex e-con e-child"
                                data-e-type="container" data-element_type="container" data-id="28543923">
                                <div class="elementor-element elementor-element-53867c96 elementor-invisible elementor-widget elementor-widget-image"
                                    data-settings='{"_animation":"fadeInDown","_animation_delay":100,"_animation_mobile":"fadeInDown"}'
                                    data-widget_type="image.default">
                                    <img alt="" class="attachment-large size-large wp-image-305" height="819" width="953"
                                        loading="lazy" decoding="async"
                                        src="{{ asset($assetBase . '01-2-1.png') }}" />
                                </div>
                                <div class="elementor-element elementor-element-7339f369 elementor-invisible elementor-widget elementor-widget-heading"
                                    data-settings='{"_animation":"zoomIn","_animation_delay":300}'
                                    data-widget_type="heading.default">
                                    <p class="elementor-heading-title elementor-size-default">Akad Nikah</p>
                                </div>
                                <div class="elementor-element elementor-element-2422d853 elementor-widget elementor-widget-heading"
                                    data-widget_type="heading.default">
                                    <p class="elementor-heading-title elementor-size-default">
                                        {{ $invitation->akad_date ? \Carbon\Carbon::parse($invitation->akad_date)->locale('id')->isoFormat('dddd') : 'Senin' }}
                                    </p>
                                </div>
                                <div class="elementor-element elementor-element-68cc7cbc elementor-invisible elementor-widget elementor-widget-heading"
                                    data-settings='{"_animation":"zoomIn","_animation_delay":200}'
                                    data-widget_type="heading.default">
                                    <p class="elementor-heading-title elementor-size-default">
                                        {{ $invitation->akad_date ? \Carbon\Carbon::parse($invitation->akad_date)->locale('id')->isoFormat('D MMMM Y') : '10 Agustus 2026' }}
                                    </p>
                                </div>
                                <div class="elementor-element elementor-element-5ba6ee95 elementor-invisible elementor-widget elementor-widget-heading"
                                    data-settings='{"_animation":"fadeInUp","_animation_delay":250}'
                                    data-widget_type="heading.default">
                                    <p class="elementor-heading-title elementor-size-default">
                                        {{ $formatEventTime($invitation->akad_time, '08:00') }}</p>
                                </div>
                                <div class="elementor-element elementor-element-6b92d7a7 elementor-view-default elementor-invisible elementor-widget elementor-widget-icon"
                                    data-settings='{"_animation":"fadeInDown","_animation_delay":250}'
                                    data-widget_type="icon.default">
                                    <div class="elementor-icon-wrapper">
                                        <div class="elementor-icon">
                                            <i aria-hidden="true" class="fas fa-map-marker-alt"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="elementor-element elementor-element-55b29470 elementor-invisible elementor-widget elementor-widget-heading"
                                    data-settings='{"_animation":"fadeInUp","_animation_delay":250}'
                                    data-widget_type="heading.default">
                                    <p class="elementor-heading-title elementor-size-default">
                                        {{ $invitation->akad_venue ?: 'Kediaman Mempelai Wanita' }}</p>
                                </div>
                                <div class="elementor-element elementor-element-6da697c4 elementor-invisible elementor-widget elementor-widget-heading"
                                    data-settings='{"_animation":"fadeInUp","_animation_delay":250}'
                                    data-widget_type="heading.default">
                                    <p class="elementor-heading-title elementor-size-default">
                                        {{ $invitation->akad_address ?: 'Lokasi akan diinformasikan lebih lanjut.' }}</p>
                                </div>
                                @if(!empty($invitation->akad_maps_link))
                                    <div class="elementor-element elementor-element-62f4a2b8 elementor-align-center wdsdv-enabled--yes elementor-invisible elementor-widget elementor-widget-button"
                                        data-settings='{"_animation":"fadeInUp","_animation_delay":300}'
                                        data-widget_type="button.default">
                                        <a class="elementor-button elementor-button-link elementor-size-xs"
                                            href="{{ $invitation->akad_maps_link }}" target="_blank" rel="noopener noreferrer">
                                            <span class="elementor-button-content-wrapper">
                                                <span class="elementor-button-icon">
                                                    <i aria-hidden="true" class="fas fa-map-marker-alt"></i>
                                                </span>
                                                <span class="elementor-button-text">Open Maps</span>
                                            </span>
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    @if($showResepsi)
                        <div class="has_eae_slider elementor-element elementor-element-16ca673e e-con-full wdsdv-enabled--yes e-flex e-con e-child"
                            data-e-type="container" data-element_type="container" data-id="16ca673e">
                            <div class="elementor-element elementor-element-515998fd elementor-invisible elementor-widget elementor-widget-image"
                                data-settings='{"_animation":"fadeInDown","_animation_delay":100,"_animation_mobile":"fadeInDown"}'
                                data-widget_type="image.default">
                                <img alt="" class="attachment-large size-large wp-image-306" height="512" width="512"
                                    loading="lazy" decoding="async"
                                    src="{{ asset($assetBase . 'icon-bunga-1.png') }}" />
                            </div>
                            <div class="elementor-element elementor-element-24e96e0e elementor-invisible elementor-widget elementor-widget-heading"
                                data-settings='{"_animation":"zoomIn","_animation_delay":300}'
                                data-widget_type="heading.default">
                                <p class="elementor-heading-title elementor-size-default">Resepsi</p>
                            </div>
                            <div class="elementor-element elementor-element-45e610ee elementor-widget elementor-widget-heading"
                                data-widget_type="heading.default">
                                <p class="elementor-heading-title elementor-size-default">
                                    {{ $invitation->resepsi_date ? \Carbon\Carbon::parse($invitation->resepsi_date)->locale('id')->isoFormat('dddd') : 'Rabu' }}
                                </p>
                            </div>
                            <div class="elementor-element elementor-element-6bb26c0c elementor-invisible elementor-widget elementor-widget-heading"
                                data-settings='{"_animation":"zoomIn","_animation_delay":200}'
                                data-widget_type="heading.default">
                                <p class="elementor-heading-title elementor-size-default">
                                    {{ $invitation->resepsi_date ? \Carbon\Carbon::parse($invitation->resepsi_date)->locale('id')->isoFormat('D MMMM Y') : '26 Agustus 2026' }}
                                </p>
                            </div>
                            <div class="elementor-element elementor-element-42183b5b elementor-invisible elementor-widget elementor-widget-heading"
                                data-settings='{"_animation":"fadeInUp","_animation_delay":250}'
                                data-widget_type="heading.default">
                                <p class="elementor-heading-title elementor-size-default">
                                    {{ $formatEventTime($invitation->resepsi_time, '10:00') }}</p>
                            </div>
                            <div class="elementor-element elementor-element-1c20eef2 elementor-view-default elementor-invisible elementor-widget elementor-widget-icon"
                                data-settings='{"_animation":"fadeInDown","_animation_delay":250}'
                                data-widget_type="icon.default">
                                <div class="elementor-icon-wrapper">
                                    <div class="elementor-icon">
                                        <i aria-hidden="true" class="fas fa-map-marker-alt"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="elementor-element elementor-element-5b0e4b13 elementor-invisible elementor-widget elementor-widget-heading"
                                data-settings='{"_animation":"fadeInUp","_animation_delay":250}'
                                data-widget_type="heading.default">
                                <p class="elementor-heading-title elementor-size-default">
                                    {{ $invitation->resepsi_venue ?: 'Kediaman Mempelai Wanita' }}</p>
                            </div>
                            <div class="elementor-element elementor-element-ced8f3c elementor-invisible elementor-widget elementor-widget-heading"
                                data-settings='{"_animation":"fadeInUp","_animation_delay":250}'
                                data-widget_type="heading.default">
                                <p class="elementor-heading-title elementor-size-default">
                                    {{ $invitation->resepsi_address ?: 'Lokasi akan diinformasikan lebih lanjut.' }}</p>
                            </div>
                            @if(!empty($invitation->resepsi_maps_link))
                                <div class="elementor-element elementor-element-53d80eda elementor-align-center wdsdv-enabled--yes elementor-invisible elementor-widget elementor-widget-button"
                                    data-settings='{"_animation":"fadeInUp","_animation_delay":300}'
                                    data-widget_type="button.default">
                                    <a class="elementor-button elementor-button-link elementor-size-xs"
                                        href="{{ $invitation->resepsi_maps_link }}" target="_blank" rel="noopener noreferrer">
                                        <span class="elementor-button-content-wrapper">
                                            <span class="elementor-button-icon">
                                                <i aria-hidden="true" class="fas fa-map-marker-alt"></i>
                                            </span>
                                            <span class="elementor-button-text">Open Maps</span>
                                        </span>
                                    </a>
                                </div>
                            @endif
                        </div>
                    @endif

                    @if($showUnduhMantu)
                        <div class="has_eae_slider elementor-element elementor-element-4a4571fe e-con-full wdsdv-enabled--yes e-flex e-con e-child"
                            data-e-type="container" data-element_type="container" data-id="4a4571fe">
                            <div class="elementor-element elementor-element-6c1bcce6 elementor-invisible elementor-widget elementor-widget-image"
                                data-settings='{"_animation":"fadeInDown","_animation_delay":100,"_animation_mobile":"fadeInDown"}'
                                data-widget_type="image.default">
                                <img alt="" class="attachment-large size-large wp-image-306" height="512" width="512"
                                    loading="lazy" decoding="async"
                                    src="{{ asset($assetBase . 'icon-bunga-1.png') }}" />
                            </div>
                            <div class="elementor-element elementor-element-720bfe6a elementor-invisible elementor-widget elementor-widget-heading"
                                data-settings='{"_animation":"zoomIn","_animation_delay":300}'
                                data-widget_type="heading.default">
                                <p class="elementor-heading-title elementor-size-default">Unduh Mantu</p>
                            </div>
                            @if($unduhDate)
                                <div class="elementor-element elementor-element-63b6144b elementor-widget elementor-widget-heading"
                                    data-widget_type="heading.default">
                                    <p class="elementor-heading-title elementor-size-default">
                                        {{ \Carbon\Carbon::parse($unduhDate)->locale('id')->isoFormat('dddd') }}</p>
                                </div>
                                <div class="elementor-element elementor-element-6d2a8985 elementor-invisible elementor-widget elementor-widget-heading"
                                    data-settings='{"_animation":"zoomIn","_animation_delay":200}'
                                    data-widget_type="heading.default">
                                    <p class="elementor-heading-title elementor-size-default">
                                        {{ \Carbon\Carbon::parse($unduhDate)->locale('id')->isoFormat('D MMMM Y') }}</p>
                                </div>
                            @endif
                            <div class="elementor-element elementor-element-5f309065 elementor-invisible elementor-widget elementor-widget-heading"
                                data-settings='{"_animation":"fadeInUp","_animation_delay":250}'
                                data-widget_type="heading.default">
                                <p class="elementor-heading-title elementor-size-default">
                                    {{ $formatEventTime($styles['unduh_mantu_time'] ?? null, '11:00') }}</p>
                            </div>
                            <div class="elementor-element elementor-element-3a1a56b elementor-view-default elementor-invisible elementor-widget elementor-widget-icon"
                                data-settings='{"_animation":"fadeInDown","_animation_delay":250}'
                                data-widget_type="icon.default">
                                <div class="elementor-icon-wrapper">
                                    <div class="elementor-icon">
                                        <i aria-hidden="true" class="fas fa-map-marker-alt"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="elementor-element elementor-element-3ed7805a elementor-invisible elementor-widget elementor-widget-heading"
                                data-settings='{"_animation":"fadeInUp","_animation_delay":250}'
                                data-widget_type="heading.default">
                                <p class="elementor-heading-title elementor-size-default">
                                    {{ $unduhVenue ?: 'Kediaman Mempelai Pria' }}</p>
                            </div>
                            <div class="elementor-element elementor-element-4320230 elementor-invisible elementor-widget elementor-widget-heading"
                                data-settings='{"_animation":"fadeInUp","_animation_delay":250}'
                                data-widget_type="heading.default">
                                <p class="elementor-heading-title elementor-size-default">
                                    {{ $styles['unduh_mantu_address'] ?? $invitation->resepsi_address ?? '' }}</p>
                            </div>
                            @if(!empty($styles['unduh_mantu_maps_link']))
                                <div class="elementor-element elementor-element-52301062 elementor-align-center wdsdv-enabled--yes elementor-invisible elementor-widget elementor-widget-button"
                                    data-settings='{"_animation":"fadeInUp","_animation_delay":300}'
                                    data-widget_type="button.default">
                                    <a class="elementor-button elementor-button-link elementor-size-xs"
                                        href="{{ $styles['unduh_mantu_maps_link'] }}" target="_blank" rel="noopener noreferrer">
                                        <span class="elementor-button-content-wrapper">
                                            <span class="elementor-button-icon">
                                                <i aria-hidden="true" class="fas fa-map-marker-alt"></i>
                                            </span>
                                            <span class="elementor-button-text">Open Maps</span>
                                        </span>
                                    </a>
                                </div>
                            @endif
                        </div>
                    @endif

                    @if($showStreaming)
                        <div class="has_eae_slider elementor-element elementor-element-431cd505 e-con-full wdsdv-enabled--yes e-flex e-con e-child"
                            data-e-type="container" data-element_type="container" data-id="431cd505">
                            <div class="elementor-element elementor-element-21d93487 elementor-invisible elementor-widget elementor-widget-heading"
                                data-settings='{"_animation":"zoomIn","_animation_delay":300}'
                                data-widget_type="heading.default">
                                <p class="elementor-heading-title elementor-size-default">Live Streaming</p>
                            </div>
                            <div class="elementor-element elementor-element-2ff52334 elementor-invisible elementor-widget elementor-widget-heading"
                                data-settings='{"_animation":"fadeInUp","_animation_delay":250}'
                                data-widget_type="heading.default">
                                <p class="elementor-heading-title elementor-size-default">Kami mengundang
                                    Bapak/Ibu/Saudara/i untuk menyaksikan Pernikahan kami secara Virtual yang disiarkan
                                    langsung melalui Sosial Media di bawah ini</p>
                            </div>
                            <div class="elementor-element elementor-element-6a037e56 elementor-align-center elementor-invisible elementor-widget elementor-widget-button"
                                data-settings='{"_animation":"fadeInUp","_animation_delay":300}'
                                data-widget_type="button.default">
                                <a class="elementor-button elementor-button-link elementor-size-xs"
                                    href="{{ $streamingLink }}" target="_blank" rel="noopener noreferrer">
                                    <span class="elementor-button-content-wrapper">
                                        <span class="elementor-button-icon">
                                            <i aria-hidden="true" class="fas fa-video"></i>
                                        </span>
                                        <span class="elementor-button-text">Klik Disini</span>
                                    </span>
                                </a>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- 7. Countdown Section (7741f858 / #countdown) -->
                @if($showCountdown)
                    <div id="countdown"
                        class="has_eae_slider elementor-element elementor-element-7741f858 e-con-full e-flex e-con e-child"
                        data-e-type="container" data-element_type="container" data-id="7741f858">
                        <div class="has_eae_slider elementor-element elementor-element-42ca87c3 e-con-full e-flex e-con e-child"
                            data-e-type="container" data-element_type="container" data-id="42ca87c3">
                            <div class="elementor-element elementor-element-5a9f45e9 elementor-invisible elementor-widget elementor-widget-heading"
                                data-settings='{"_animation":"zoomIn","_animation_delay":300}'
                                data-widget_type="heading.default">
                                <p class="elementor-heading-title elementor-size-default">Counting The Days</p>
                            </div>
                            <div class="elementor-element elementor-element-11a582ee elementor-absolute elementor-align-center elementor-widget elementor-widget-lottie"
                                data-lottie-url="{{ $lottieJsonUrl }}" data-widget_type="lottie.default" wire:ignore>
                                <div class="e-lottie__container">
                                    <div class="e-lottie__animation"></div>
                                </div>
                            </div>
                            <div class="elementor-element elementor-element-65709850 elementor-countdown--label-block elementor-widget elementor-widget-countdown"
                                data-widget_type="countdown.default">
                                <div class="elementor-countdown-wrapper">
                                    <div class="elementor-countdown-item"><span
                                            class="elementor-countdown-digits elementor-countdown-days"
                                            x-text="days">0</span> <span class="elementor-countdown-label">Hari</span></div>
                                    <div class="elementor-countdown-item"><span
                                            class="elementor-countdown-digits elementor-countdown-hours"
                                            x-text="hours">0</span> <span class="elementor-countdown-label">Jam</span></div>
                                    <div class="elementor-countdown-item"><span
                                            class="elementor-countdown-digits elementor-countdown-minutes"
                                            x-text="minutes">0</span> <span class="elementor-countdown-label">Menit</span>
                                    </div>
                                    <div class="elementor-countdown-item"><span
                                            class="elementor-countdown-digits elementor-countdown-seconds"
                                            x-text="seconds">0</span> <span class="elementor-countdown-label">Detik</span>
                                    </div>
                                </div>
                            </div>
                            <div class="elementor-element elementor-element-60649bf6 elementor-align-center elementor-invisible elementor-widget elementor-widget-wds_calendar"
                                data-settings='{"_animation":"fadeInUp","_animation_delay":300}'
                                data-widget_type="wds_calendar.default">
                                <div class="elementor-widget-container">
                                    <div class="elementor-button-wrapper">
                                        <a class="elementor-button-link elementor-button elementor-size-xs"
                                            href="{{ $googleCalendarUrl }}" rel="nofollow noopener noreferrer" role="button"
                                            target="_blank">
                                            <span class="elementor-button-content-wrapper wds-flexbox">
                                                <span class="elementor-button-icon elementor-align-icon-row">
                                                    <i aria-hidden="true" class="far fa-calendar-alt"></i>
                                                </span>
                                                <span class="elementor-button-text">Save on the calendar</span>
                                            </span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="elementor-element elementor-element-2559009f elementor-widget-divider--view-line_icon elementor-view-default elementor-widget-divider--element-align-center elementor-invisible elementor-widget elementor-widget-divider"
                                data-settings='{"_animation":"zoomIn","_animation_delay":200}'
                                data-widget_type="divider.default">
                                <div class="elementor-divider">
                                    <span class="elementor-divider-separator">
                                        <div class="elementor-icon elementor-divider__element">
                                            <i aria-hidden="true" class="far fa-gem"></i>
                                        </div>
                                    </span>
                                </div>
                            </div>
                            <div class="elementor-element elementor-element-7c203bca elementor-widget elementor-widget-spacer"
                                data-widget_type="spacer.default">
                                <div class="elementor-spacer">
                                    <div class="elementor-spacer-inner"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- 8. Gallery Section (5bc5469a / #gallery) -->
                @if($showGallery)
                    <div id="gallery"
                        class="has_eae_slider elementor-element elementor-element-5bc5469a e-con-full wdsdv-enabled--yes e-flex e-con e-child"
                        data-e-type="container" data-element_type="container" data-id="5bc5469a">
                        <div class="elementor-element elementor-element-511afdbf elementor-invisible elementor-widget elementor-widget-heading"
                            data-settings='{"_animation":"fadeInUp","_animation_delay":200}'
                            data-widget_type="heading.default">
                            <p class="elementor-heading-title elementor-size-default">Our Gallery</p>
                        </div>
                        <div class="elementor-element elementor-element-3e68f57a elementor-invisible elementor-widget elementor-widget-heading"
                            data-settings='{"_animation":"fadeInUp","_animation_delay":300}'
                            data-widget_type="heading.default">
                            <p class="elementor-heading-title elementor-size-default">Constantly, consistently, continually,
                                You.</p>
                        </div>
                        @if(!empty($styles['youtube_url']))
                            <div class="elementor-element elementor-element-cb59f60 elementor-widget elementor-widget-video"
                                data-widget_type="video.default">
                                <div class="elementor-wrapper elementor-open-inline">
                                    <iframe class="elementor-video" src="{{ $styles['youtube_url'] }}" allowfullscreen
                                        loading="lazy"
                                        style="width: 100%; aspect-ratio: 16 / 9; border: 0; border-radius: 6px;"></iframe>
                                </div>
                            </div>
                        @endif
                        <div class="elementor-element elementor-element-21562324 elementor-widget elementor-widget-gallery"
                            data-widget_type="gallery.default">
                            <div class="elementor-gallery__container">
                                @foreach($galleryPhotos as $gIdx => $photoUrl)
                                    <a href="{{ $photoUrl }}" wire:key="gallery-{{ $gIdx }}"
                                        @click.prevent="openLightbox('{{ $photoUrl }}')"
                                        class="e-gallery-item elementor-gallery-item elementor-animated-content">
                                        <img src="{{ $photoUrl }}" alt="Galeri {{ $gIdx + 1 }}" loading="lazy" />
                                        <div class="elementor-gallery-item__overlay"></div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <!-- 9. Ken Burns Slideshow 1 (749fd8c6) -->
                @if(count($kenBurns1Slides) >= 2)
                    <div class="has_eae_slider elementor-element elementor-element-749fd8c6 e-con-full e-flex e-con e-child bugis-kenburns-wrapper"
                        data-e-type="container" data-element_type="container" data-id="749fd8c6" style="min-height: 100vh;"
                        wire:ignore>
                        <div class="bugis-kb-slide bugis-kb-slide-1"
                            style="background-image: url('{{ $kenBurns1Slides[0] }}');"></div>
                        <div class="bugis-kb-slide bugis-kb-slide-2"
                            style="background-image: url('{{ $kenBurns1Slides[1] }}');"></div>
                    </div>
                @endif

                <!-- 10. Love Story Section (2838b78c / #story) -->
                @if($showLoveStory)
                    <div id="story"
                        class="has_eae_slider elementor-element elementor-element-2838b78c e-con-full wdsdv-enabled--yes e-flex e-con e-child"
                        data-e-type="container" data-element_type="container" data-id="2838b78c">
                        <div class="has_eae_slider elementor-element elementor-element-1d47a666 e-con-full e-flex e-con e-child"
                            data-e-type="container" data-element_type="container" data-id="1d47a666">
                            <div class="elementor-element elementor-element-3314bf86 elementor-widget elementor-widget-heading"
                                data-widget_type="heading.default">
                                <p class="elementor-heading-title elementor-size-default">Love Story</p>
                            </div>
                            <div class="elementor-element elementor-element-24abdce5 elementor-invisible elementor-widget elementor-widget-heading"
                                data-settings='{"_animation":"fadeInUp","_animation_mobile":"fadeInDown","_animation_delay":250}'
                                data-widget_type="heading.default">
                                <p class="elementor-heading-title elementor-size-default">Every love story is beautiful, but
                                    ours is my favorite.</p>
                            </div>
                            <div class="elementor-element elementor-element-50f723ba elementor-widget elementor-widget-jet-timeline"
                                data-widget_type="jet-timeline.default">
                                <div class="elementor-widget-container">
                                    <div class="elementor-jet-timeline jet-elements">
                                        <div class="jet-timeline jet-timeline--align-left jet-timeline--align-middle">
                                            <div class="jet-timeline__line">
                                                <div class="jet-timeline__line-progress"></div>
                                            </div>
                                            <div class="jet-timeline-list">
                                                @foreach($loveStories as $sIdx => $story)
                                                    <div class="jet-timeline-item jet-timeline-item--animated elementor-repeater-item-a1d724f-{{ $sIdx }} jet-timeline-item--image-inside is--show"
                                                        wire:key="story-{{ $sIdx }}">
                                                        <div class="timeline-item__card">
                                                            <div class="timeline-item__card-inner">
                                                                @if(!empty($story['image']))
                                                                    <div class="timeline-item__card-img">
                                                                        <img alt="{{ $story['title'] }}"
                                                                            class="attachment-full size-full" loading="lazy"
                                                                            decoding="async"
                                                                            height="1590" width="1060"
                                                                            src="{{ $story['image'] }}" />
                                                                    </div>
                                                                @endif
                                                                <div class="timeline-item__card-content">
                                                                    <div class="timeline-item__meta">
                                                                        <div class="timeline-item__meta-content">
                                                                            {{ $story['date'] }}</div>
                                                                    </div>
                                                                    <h5 class="timeline-item__card-title">{{ $story['title'] }}
                                                                    </h5>
                                                                    <div class="timeline-item__card-desc">
                                                                        {{ $story['description'] }}</div>
                                                                </div>
                                                            </div>
                                                            <div class="timeline-item__card-arrow"></div>
                                                        </div>
                                                        <div class="timeline-item__point">
                                                            <div
                                                                class="timeline-item__point-content timeline-item__point-content--icon">
                                                                <span class="jet-elements-icon"><i aria-hidden="true"
                                                                        class="fas fa-hand-holding-heart"></i></span>
                                                            </div>
                                                        </div>
                                                        <div class="timeline-item__meta">
                                                            <div class="timeline-item__meta-content">{{ $story['date'] }}</div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="elementor-element elementor-element-409643d3 elementor-absolute animated-slow elementor-invisible elementor-widget elementor-widget-image"
                                data-settings='{"_animation":"rotateInDownLeft","_animation_delay":300,"_animation_mobile":"fadeInLeft","_position":"absolute"}'
                                data-widget_type="image.default">
                                <img alt="" class="attachment-full size-full wp-image-316" height="978" width="585"
                                    loading="lazy" decoding="async"
                                    src="{{ asset($assetBase . 'bunga-bugis.png') }}" />
                            </div>
                        </div>
                    </div>
                @endif

                <!-- 11. Wedding Gift Section (3057ba0d / #gift) -->
                @if($showGift)
                    <div id="gift"
                        class="has_eae_slider elementor-element elementor-element-3057ba0d e-con-full wdsdv-enabled--yes e-flex e-con e-child"
                        data-e-type="container" data-element_type="container" data-id="3057ba0d">
                        <div class="has_eae_slider elementor-element elementor-element-273fc174 e-con-full e-flex e-con e-child"
                            data-e-type="container" data-element_type="container" data-id="273fc174">
                            <div class="elementor-element elementor-element-79776c1 elementor-widget elementor-widget-heading"
                                data-widget_type="heading.default">
                                <p class="elementor-heading-title elementor-size-default">Wedding Gift</p>
                            </div>
                            <div class="elementor-element elementor-element-2939947d elementor-invisible elementor-widget elementor-widget-heading"
                                data-settings='{"_animation":"fadeInUp","_animation_delay":250,"_animation_mobile":"fadeInDown"}'
                                data-widget_type="heading.default">
                                <p class="elementor-heading-title elementor-size-default">Doa restu Anda merupakan karunia
                                    yang sangat berarti bagi kami. Dan jika memberi adalah ungkapan tanda kasih Anda, Anda
                                    dapat memberi kado secara cashless.</p>
                            </div>

                            <div class="has_eae_slider elementor-element elementor-element-774c3988 e-con-full wdsdv-enabled--yes e-flex e-con e-child"
                                data-e-type="container" data-element_type="container" data-id="774c3988">
                                <!-- Gift Physical Address Card (62ae0d81) -->
                                @if(!empty($giftAddress))
                                    <div class="has_eae_slider elementor-element elementor-element-62ae0d81 e-con-full wdsdv-enabled--yes e-flex e-con e-child"
                                        data-e-type="container" data-element_type="container" data-id="62ae0d81">
                                        <div class="has_eae_slider elementor-element elementor-element-34d256a e-con-full e-flex e-con e-child"
                                            data-e-type="container" data-element_type="container" data-id="34d256a">
                                            <div class="elementor-element elementor-element-1ec52691 elementor-view-default elementor-widget elementor-widget-icon"
                                                data-widget_type="icon.default">
                                                <div class="elementor-icon-wrapper">
                                                    <div class="elementor-icon">
                                                        <i aria-hidden="true" class="fas fa-gift"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="elementor-element elementor-element-5d18c60e elementor-widget__width-initial elementor-widget elementor-widget-heading"
                                                data-widget_type="heading.default">
                                                <p class="elementor-heading-title elementor-size-default">Gift</p>
                                            </div>
                                        </div>
                                        <div class="elementor-element elementor-element-437a1b68 elementor-widget elementor-widget-heading"
                                            data-widget_type="heading.default">
                                            <p class="elementor-heading-title elementor-size-default">Nama Penerima</p>
                                        </div>
                                        <div class="elementor-element elementor-element-25567066 elementor-widget elementor-widget-heading"
                                            data-widget_type="heading.default">
                                            <p class="elementor-heading-title elementor-size-default">{{ $coupleTitle }}</p>
                                        </div>
                                        <div class="elementor-element elementor-element-59feb8a elementor-widget elementor-widget-heading"
                                            data-widget_type="heading.default">
                                            <p class="elementor-heading-title elementor-size-default">Alamat Penerima</p>
                                        </div>
                                        <div class="elementor-element elementor-element-d106704 elementor-widget elementor-widget-heading"
                                            data-widget_type="heading.default">
                                            <p class="elementor-heading-title elementor-size-default">{{ $giftAddress }}</p>
                                        </div>
                                        <div class="elementor-element elementor-element-3fab5e6b elementor-align-center elementor-widget elementor-widget-weddingpress-copy-text"
                                            data-widget_type="weddingpress-copy-text.default">
                                            <div class="elementor-widget-container">
                                                <div class="elementor-button-wrapper">
                                                    <button type="button" @click.prevent="copyToClipboard(@js($giftAddress))"
                                                        class="elementor-button" style="cursor: pointer; border: none;">
                                                        <span class="elementor-button-content-wrapper">
                                                            <span><i aria-hidden="true" class="far fa-copy"></i></span>
                                                            <span class="elementor-button-text">Salin Alamat</span>
                                                        </span>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <!-- Dynamic Bank Account Cards (2b1d7932) -->
                                @foreach($bankAccounts as $bIdx => $account)
                                    @php
                                        $bankLogo = $resolveBankLogo($account['bank'] ?? '');
                                        $accNumber = (string) ($account['account_number'] ?? '');
                                        $accName = (string) ($account['account_name'] ?? $coupleTitle);
                                    @endphp
                                    <div class="has_eae_slider elementor-element elementor-element-2b1d7932 e-con-full wdsdv-enabled--yes e-flex e-con e-child"
                                        data-e-type="container" data-element_type="container" data-id="2b1d7932"
                                        wire:key="bank-{{ $bIdx }}">
                                        <div class="has_eae_slider elementor-element elementor-element-73d2be4c e-con-full e-flex e-con e-child"
                                            data-e-type="container" data-element_type="container" data-id="73d2be4c">
                                            <div class="has_eae_slider elementor-element elementor-element-2fed61fe e-con-full e-flex e-con e-child"
                                                data-e-type="container" data-element_type="container" data-id="2fed61fe">
                                                <div class="elementor-element elementor-element-bac28ac elementor-widget elementor-widget-spacer"
                                                    data-widget_type="spacer.default">
                                                    <div class="elementor-spacer">
                                                        <div class="elementor-spacer-inner"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="has_eae_slider elementor-element elementor-element-4307ea14 e-con-full e-flex e-con e-child"
                                                data-e-type="container" data-element_type="container" data-id="4307ea14">
                                                <div class="elementor-element elementor-element-7e004ae5 elementor-widget elementor-widget-image"
                                                    data-widget_type="image.default">
                                                    @if($bankLogo)
                                                        <img alt="{{ $account['bank'] ?? 'Bank' }}" loading="lazy"
                                                            src="{{ $bankLogo }}" />
                                                    @else
                                                        <span class="bugis-gift-bank-badge">{{ $account['bank'] ?? 'BANK' }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="elementor-element elementor-element-7c806a63 elementor-widget elementor-widget-heading"
                                            data-widget_type="heading.default">
                                            <p class="elementor-heading-title elementor-size-default">No Rekening</p>
                                        </div>
                                        <div class="elementor-element elementor-element-a8dd9fb elementor-widget elementor-widget-heading"
                                            data-widget_type="heading.default">
                                            <p class="elementor-heading-title elementor-size-default">{{ $accNumber }}</p>
                                        </div>
                                        <div class="elementor-element elementor-element-39f40727 elementor-widget elementor-widget-heading"
                                            data-widget_type="heading.default">
                                            <p class="elementor-heading-title elementor-size-default">Atas Nama</p>
                                        </div>
                                        <div class="elementor-element elementor-element-76b22250 elementor-widget elementor-widget-heading"
                                            data-widget_type="heading.default">
                                            <p class="elementor-heading-title elementor-size-default">{{ $accName }}</p>
                                        </div>
                                        @if($accNumber !== '')
                                            <div class="elementor-element elementor-element-518e93ea elementor-align-right elementor-widget elementor-widget-weddingpress-copy-text"
                                                data-widget_type="weddingpress-copy-text.default">
                                                <div class="elementor-widget-container">
                                                    <div class="elementor-button-wrapper">
                                                        <button type="button" @click.prevent="copyToClipboard(@js($accNumber))"
                                                            class="elementor-button" style="cursor: pointer; border: none;">
                                                            <span class="elementor-button-content-wrapper">
                                                                <span><i aria-hidden="true" class="far fa-copy"></i></span>
                                                                <span class="elementor-button-text">Salin Rekening</span>
                                                            </span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <!-- 12. Turut Mengundang Section (7f6afcc1) -->
                <div class="has_eae_slider elementor-element elementor-element-7f6afcc1 e-con-full wdsdv-enabled--yes e-flex e-con e-child"
                    data-e-type="container" data-element_type="container" data-id="7f6afcc1">
                    <!-- Turut Mengundang Card (512fd482) -->
                    <div class="has_eae_slider elementor-element elementor-element-512fd482 e-con-full wdsdv-enabled--yes e-flex e-con e-child"
                        data-e-type="container" data-element_type="container" data-id="512fd482">
                        <div class="elementor-element elementor-element-7923ce75 elementor-widget elementor-widget-heading"
                            data-widget_type="heading.default">
                            <p class="elementor-heading-title elementor-size-default">Turut Mengundang</p>
                        </div>
                        <div class="elementor-element elementor-element-705f3fc3 elementor-invisible elementor-widget elementor-widget-heading"
                            data-settings='{"_animation":"fadeInUp","_animation_delay":250,"_animation_mobile":"fadeInDown"}'
                            data-widget_type="heading.default">
                            <p class="elementor-heading-title elementor-size-default">Dengan segala hormat, kami
                                sekeluarga turut mengundang Bapak/Ibu/Saudara(i) untuk berkenan hadir dan memberikan doa
                                restu pada acara tersebut:</p>
                        </div>
                        <div class="elementor-element elementor-element-397bb84f elementor-widget elementor-widget-text-editor"
                            data-widget_type="text-editor.default"
                            style="text-align: center; font-family: 'Caudex', serif; font-size: 14px; line-height: 1.8;">
                            @if(!empty($styles['turut_mengundang']))
                                {!! nl2br(e($styles['turut_mengundang'])) !!}
                            @else
                                <strong>Keluarga Besar Mempelai Wanita</strong><br />
                                {{ $brideParents }}<br /><br />
                                <strong>Keluarga Besar Mempelai Pria</strong><br />
                                {{ $groomParents }}
                            @endif
                        </div>
                    </div>
                </div>

                <!-- 13. Best Wishes / RSVP Section (2f42a54 / #rsvp) -->
                @if($showRsvp)
                    <div id="rsvp"
                        class="has_eae_slider elementor-element elementor-element-2f42a54 e-con-full e-flex e-con e-child"
                        data-e-type="container" data-element_type="container" data-id="2f42a54">
                        <div class="has_eae_slider elementor-element elementor-element-49f6526d e-con-full e-flex e-con e-child"
                            data-e-type="container" data-element_type="container" data-id="49f6526d">
                            <div class="elementor-element elementor-element-4d0fea9f elementor-widget elementor-widget-heading"
                                data-widget_type="heading.default">
                                <p class="elementor-heading-title elementor-size-default">Best Wishes</p>
                            </div>
                            <div class="elementor-element elementor-element-3187e611 elementor-invisible elementor-widget elementor-widget-heading"
                                data-settings='{"_animation":"fadeInUp","_animation_delay":250,"_animation_mobile":"fadeInDown"}'
                                data-widget_type="heading.default">
                                <p class="elementor-heading-title elementor-size-default">Sampaikan doa dan ucapan terbaik
                                    Anda</p>
                            </div>

                            <div class="elementor-element elementor-element-b25636f elementor-widget elementor-widget-wds_rsvp"
                                data-widget_type="wds_rsvp.default">
                                <div class="elementor-widget-container">
                                    <div class="saic-wrapper saic-default saic-border saic-full">
                                        <!-- Ucapan Counter -->
                                        <div class="saic-wrap-link">
                                            <a href="#rsvp" @click.prevent
                                                class="saic-link saic-icon-link saic-icon-link-true">
                                                <i class="far fa-comments" style="margin-right: 6px;"></i>
                                                <span>{{ $wishesCount }}</span> Ucapan
                                            </a>
                                        </div>

                                        <!-- Livewire RSVP & Wishes Form -->
                                        <div class="saic-wrap-form saic-clearfix">
                                            <form wire:submit.prevent="submitRSVP">
                                                @if (session()->has('rsvp_success'))
                                                    <div
                                                        style="background: rgba(34, 197, 94, 0.25); border: 1px solid #22c55e; color: #FFFFFF; padding: 10px 14px; border-radius: 6px; margin-bottom: 14px; font-size: 13px; text-align: center;">
                                                        <i class="fas fa-check-circle"
                                                            style="margin-right: 6px;"></i>{{ session('rsvp_success') }}
                                                    </div>
                                                @endif

                                                <p class="comment-form-author saic-field-1" style="margin-bottom: 12px;">
                                                    <input type="text" wire:model="rsvpName" class="saic-input"
                                                        placeholder="Nama Anda" required />
                                                    @error('rsvpName') <span
                                                        style="display:block; color:#fca5a5; font-size: 12px; margin-top: 4px;">{{ $message }}</span>
                                                    @enderror
                                                </p>

                                                <div class="saic-wrap-attendance" style="margin-bottom: 12px;">
                                                    <select wire:model.live="rsvpStatus" class="saic-select" required>
                                                        <option value="confirmed">Hadir</option>
                                                        <option value="declined">Tidak Hadir</option>
                                                        <option value="tentative">Masih Ragu</option>
                                                    </select>
                                                    @error('rsvpStatus') <span
                                                        style="display:block; color:#fca5a5; font-size: 12px; margin-top: 4px;">{{ $message }}</span>
                                                    @enderror
                                                </div>

                                                @if($rsvpStatus === 'confirmed')
                                                    <div class="saic-wrap-guest" style="margin-bottom: 12px;">
                                                        <select wire:model="rsvpGuests" class="saic-select">
                                                            <option value="1">1 Orang</option>
                                                            <option value="2">2 Orang</option>
                                                            <option value="3">3 Orang</option>
                                                            <option value="4">4 Orang</option>
                                                            <option value="5">5 Orang</option>
                                                        </select>
                                                        @error('rsvpGuests') <span
                                                            style="display:block; color:#fca5a5; font-size: 12px; margin-top: 4px;">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                @endif

                                                <div class="saic-wrap-textarea" style="margin-bottom: 12px;">
                                                    <textarea wire:model="rsvpMessage" class="saic-textarea"
                                                        placeholder="Tulis doa restu &amp; ucapan terbaik Anda..." rows="3"
                                                        required></textarea>
                                                    @error('rsvpMessage') <span
                                                        style="display:block; color:#fca5a5; font-size: 12px; margin-top: 4px;">{{ $message }}</span>
                                                    @enderror
                                                </div>

                                                <div class="saic-wrap-submit saic-clearfix">
                                                    <button type="submit" class="saic-submit-btn">
                                                        <span wire:loading.remove wire:target="submitRSVP"><i
                                                                class="fas fa-paper-plane"
                                                                style="margin-right: 6px;"></i>Kirim Ucapan</span>
                                                        <span wire:loading wire:target="submitRSVP"><i
                                                                class="fas fa-spinner fa-spin"
                                                                style="margin-right: 6px;"></i>Mengirim...</span>
                                                    </button>
                                                </div>
                                            </form>
                                        </div>

                                        <!-- Wishes Feed -->
                                        <ul class="saic-container-comments">
                                            @forelse ($wishesList as $wish)
                                                <li class="saic-item-comment" wire:key="wish-{{ $wish->id }}">
                                                    <div style="display: flex; align-items: flex-start; gap: 10px;">
                                                        <div
                                                            style="width: 34px; height: 34px; border-radius: 50%; background: rgba(255,255,255,0.16); color: #FFF; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 14px; flex-shrink: 0; border: 1px solid rgba(255,255,255,0.35);">
                                                            {{ strtoupper(substr($wish->name ?? 'T', 0, 1)) }}
                                                        </div>
                                                        <div class="saic-comment-content" style="margin-left: 0; flex: 1;">
                                                            <div class="saic-comment-info"
                                                                style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 6px; margin-bottom: 4px;">
                                                                <span class="saic-commenter-name">{{ $wish->name }}</span>
                                                                @if ($wish->attendance_status === 'confirmed' || $wish->attendance_status === 'present')
                                                                    <span
                                                                        style="background: rgba(34,197,94,0.22); color: #bbf7d0; border: 1px solid rgba(34,197,94,0.45); font-size: 11px; padding: 2px 8px; border-radius: 12px;"><i
                                                                            class="fas fa-check"
                                                                            style="margin-right: 4px;"></i>Hadir
                                                                        ({{ $wish->pax ?? 1 }})</span>
                                                                @elseif ($wish->attendance_status === 'declined' || $wish->attendance_status === 'notpresent')
                                                                    <span
                                                                        style="background: rgba(239,68,68,0.22); color: #fecaca; border: 1px solid rgba(239,68,68,0.45); font-size: 11px; padding: 2px 8px; border-radius: 12px;"><i
                                                                            class="fas fa-times"
                                                                            style="margin-right: 4px;"></i>Tidak Hadir</span>
                                                                @elseif ($wish->attendance_status === 'tentative' || $wish->attendance_status === 'notsure')
                                                                    <span
                                                                        style="background: rgba(234,179,8,0.22); color: #fef08a; border: 1px solid rgba(234,179,8,0.45); font-size: 11px; padding: 2px 8px; border-radius: 12px;"><i
                                                                            class="fas fa-question"
                                                                            style="margin-right: 4px;"></i>Masih Ragu</span>
                                                                @endif
                                                            </div>
                                                            <div class="saic-comment-text"
                                                                style="color: #FFFFFF; font-size: 13px; line-height: 1.5; margin-bottom: 4px;">
                                                                <p>{{ $wish->message }}</p>
                                                            </div>
                                                            <div class="saic-comment-time"
                                                                style="font-size: 10px; color: rgba(255,255,255,0.75); text-align: right;">
                                                                {{ $wish->created_at ? $wish->created_at->diffForHumans() : 'Baru saja' }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                            @empty
                                                <li
                                                    style="text-align: center; color: rgba(255,255,255,0.8); padding: 20px; font-size: 13px;">
                                                    Belum ada ucapan. Jadilah yang pertama memberikan doa restu!
                                                </li>
                                            @endforelse
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- 14. Ken Burns Slideshow 2 (2d3d35f6) -->
                @if(count($kenBurns2Slides) >= 2)
                    <div class="has_eae_slider elementor-element elementor-element-2d3d35f6 e-con-full e-flex e-con e-child bugis-kenburns-wrapper"
                        data-e-type="container" data-element_type="container" data-id="2d3d35f6" style="min-height: 550px;"
                        wire:ignore>
                        <div class="bugis-kb-slide bugis-kb-slide-1"
                            style="background-image: url('{{ $kenBurns2Slides[0] }}');"></div>
                        <div class="bugis-kb-slide bugis-kb-slide-2"
                            style="background-image: url('{{ $kenBurns2Slides[1] }}');"></div>
                        <div class="has_eae_slider elementor-element elementor-element-311600f9 e-con-full e-flex e-con e-child"
                            data-e-type="container" data-element_type="container" data-id="311600f9">
                            <div class="elementor-element elementor-element-571925d7 elementor-widget elementor-widget-spacer"
                                data-widget_type="spacer.default">
                                <div class="elementor-spacer">
                                    <div class="elementor-spacer-inner"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- 15. Closing Section (626ff90) -->
                <div class="has_eae_slider elementor-element elementor-element-626ff90 e-con-full e-flex e-con e-child"
                    data-e-type="container" data-element_type="container" data-id="626ff90">
                    <div class="has_eae_slider elementor-element elementor-element-226bfae8 e-con-full e-flex e-con e-child"
                        data-e-type="container" data-element_type="container" data-id="226bfae8">
                        <div class="elementor-element elementor-element-7f03092c elementor-absolute elementor-align-center elementor-widget elementor-widget-lottie"
                            data-lottie-url="{{ $lottieJsonUrl }}" data-widget_type="lottie.default" wire:ignore>
                            <div class="e-lottie__container">
                                <div class="e-lottie__animation"></div>
                            </div>
                        </div>
                        <div class="elementor-element elementor-element-78290e07 elementor-invisible elementor-widget elementor-widget-text-editor"
                            data-settings='{"_animation":"fadeInUp","_animation_delay":250}'
                            data-widget_type="text-editor.default">
                            Menjadi sebuah kebahagiaan bagi kami apabila Bapak/Ibu/Saudara/i berkenan hadir dalam hari
                            bahagia ini. Terima kasih atas segala doa, ucapan dan perhatian yang diberikan.
                        </div>
                        <div class="elementor-element elementor-element-259a9dd7 custom-font-closing elementor-invisible elementor-widget elementor-widget-heading"
                            data-settings='{"_animation":"zoomIn","_animation_delay":200}'
                            data-widget_type="heading.default">
                            <h2 class="elementor-heading-title elementor-size-default">{{ $coupleTitle }}</h2>
                        </div>
                        <div class="elementor-element elementor-element-52f0c192 elementor-widget-mobile__width-initial elementor-widget elementor-widget-spacer"
                            data-widget_type="spacer.default">
                            <div class="elementor-spacer">
                                <div class="elementor-spacer-inner"></div>
                            </div>
                        </div>
                        <div class="elementor-element elementor-element-5ddeaeaa elementor-widget elementor-widget-spacer"
                            data-widget_type="spacer.default">
                            <div class="elementor-spacer">
                                <div class="elementor-spacer-inner"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer (34aa0a29) -->
                <div class="has_eae_slider elementor-element elementor-element-34aa0a29 e-con-full e-flex e-con e-child"
                    data-e-type="container" data-element_type="container" data-id="34aa0a29">
                    <div class="elementor-element elementor-element-34a93772 elementor-widget elementor-widget-image"
                        data-widget_type="image.default">
                        <a href="{{ url('/') }}">
                            <img alt="ExoInvite Logo" loading="lazy" src="{{ asset('assets/svg/logo.svg') }}"
                                style="width: 170px; max-width: 65%; height: auto;" />
                        </a>
                    </div>
                    <!-- <div class="elementor-element elementor-element-292a0be7 elementor-widget elementor-widget-heading" data-widget_type="heading.default">
                        <h2 class="elementor-heading-title elementor-size-default"><a href="{{ url('/') }}">{{ config('app.name', 'ExoInvite') }}</a></h2>
                    </div> -->
                    <div class="has_eae_slider elementor-element elementor-element-5fcccf3f e-grid e-con-full e-con e-child"
                        data-e-type="container" data-element_type="container" data-id="5fcccf3f">
                        <div class="elementor-element elementor-element-30608850 elementor-widget__width-auto elementor-view-default elementor-widget elementor-widget-icon"
                            data-widget_type="icon.default">
                            <div class="elementor-icon-wrapper">
                                <div class="elementor-icon"><i aria-hidden="true" class="fab fa-instagram"></i></div>
                            </div>
                        </div>
                        <div class="elementor-element elementor-element-3f3c4899 elementor-widget__width-auto elementor-view-default elementor-widget elementor-widget-icon"
                            data-widget_type="icon.default">
                            <div class="elementor-icon-wrapper">
                                <div class="elementor-icon"><i aria-hidden="true" class="fab fa-tiktok"></i></div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Right-Edge Luxury Control Pill (matching pill.css .zipill-panel.zipill-luxury) -->
    <div class="zipill-panel zipill-luxury zipill-live" data-side="right" role="group" aria-label="Kontrol undangan"
        x-show="isOpened" x-transition.opacity style="display: none;">
        <!-- Toggle Menu Dock -->
        <button type="button" class="zipill-button" @click="toggleMenu()" :aria-pressed="!menuCollapsed"
            :title="menuCollapsed ? 'Buka Menu Navigasi' : 'Tutup Menu Navigasi'" aria-label="Menu Navigasi">
            <svg class="zipill-icon" viewBox="0 0 24 24" aria-hidden="true">
                <circle cx="12" cy="12" r="9"></circle>
                <polygon points="15.5 8.5 13.5 13.5 8.5 15.5 10.5 10.5 15.5 8.5"></polygon>
            </svg>
            <span class="zipill-status-dot" aria-hidden="true"></span>
        </button>

        <!-- Auto Scroll Toggle + Live Circular Progress -->
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
            <span data-icon-active x-show="autoScrolling" style="display: none;">
                <svg class="zipill-icon" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M9 5v14M15 5v14"></path>
                </svg>
            </span>
            <span x-show="autoScrolling" @click.stop="cycleScrollSpeed()" class="bugis-speed-chip"
                x-text="scrollSpeed + 'x'" title="Ubah kecepatan scroll" style="display: none;">1x</span>
        </button>

        <!-- Music Toggle -->
        <button type="button" class="zipill-button" data-action="music" @click="toggleMusic()" :aria-pressed="isPlaying"
            :title="isPlaying ? 'Jeda Musik' : 'Putar Musik'" aria-label="Putar musik">
            <svg class="zipill-icon" viewBox="0 0 24 24" aria-hidden="true">
                <path d="M10 17V6l10-2v11M10 9l10-2"></path>
                <ellipse cx="7" cy="17" rx="3" ry="2.5"></ellipse>
                <ellipse cx="17" cy="15" rx="3" ry="2.5"></ellipse>
            </svg>
            <span class="zipill-status-dot" aria-hidden="true"></span>
        </button>
    </div>

    <!-- Responsive Bottom Navigation Dock (Collapsed to Side by Default) -->
    <div class="bugis-bottom-dock" x-show="isOpened && !menuCollapsed"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" style="display: none;">
        <a href="#hero" @click.prevent="scrollTo('#hero')" class="dock-btn" title="Awal">
            <i class="fas fa-home"></i><span>Awal</span>
        </a>
        <a href="#couple" @click.prevent="scrollTo('#couple')" class="dock-btn" title="Mempelai">
            <i class="fas fa-heart"></i><span>Pasangan</span>
        </a>
        <a href="#events" @click.prevent="scrollTo('#events')" class="dock-btn" title="Acara">
            <i class="fas fa-calendar-alt"></i><span>Acara</span>
        </a>
        @if($showCountdown)
            <a href="#countdown" @click.prevent="scrollTo('#countdown')" class="dock-btn" title="Waktu">
                <i class="fas fa-clock"></i><span>Waktu</span>
            </a>
        @endif
        @if($showGallery)
            <a href="#gallery" @click.prevent="scrollTo('#gallery')" class="dock-btn" title="Galeri">
                <i class="fas fa-images"></i><span>Galeri</span>
            </a>
        @endif
        @if($showLoveStory)
            <a href="#story" @click.prevent="scrollTo('#story')" class="dock-btn" title="Kisah">
                <i class="fas fa-book-open"></i><span>Cerita</span>
            </a>
        @endif
        @if($showGift)
            <a href="#gift" @click.prevent="scrollTo('#gift')" class="dock-btn" title="Kado">
                <i class="fas fa-gift"></i><span>Kado</span>
            </a>
        @endif
        @if($showRsvp)
            <a href="#rsvp" @click.prevent="scrollTo('#rsvp')" class="dock-btn" title="Ucapan">
                <i class="fas fa-envelope-open-text"></i><span>Ucapan</span>
            </a>
        @endif
        <button type="button" @click="menuCollapsed = true" title="Sembunyikan Menu"
            style="display: flex; align-items: center; justify-content: center; width: 32px; height: 32px; background: rgba(255,255,255,0.12); color: #E7D39F; border: 1px solid rgba(231,211,159,0.35); border-radius: 50%; margin-left: 4px; cursor: pointer; flex-shrink: 0;">
            <i class="fas fa-chevron-right" style="font-size: 12px;"></i>
        </button>
    </div>

    <!-- Lightbox Modal for Gallery -->
    <div x-show="lightboxOpen" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" @click="lightboxOpen = false" class="bugis-lightbox" style="display: none;">
        <img :src="lightboxImg" alt="Preview Galeri"
            style="max-width: 95vw; max-height: 90vh; border-radius: 8px; box-shadow: 0 10px 40px rgba(0,0,0,0.8); object-fit: contain;" />
        <button type="button" @click="lightboxOpen = false" aria-label="Tutup galeri"
            style="position: absolute; top: 20px; right: 20px; background: rgba(255,255,255,0.2); border: none; color: #fff; width: 40px; height: 40px; border-radius: 50%; font-size: 18px; cursor: pointer;">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <!-- Toast Notification -->
    <div x-show="toastShow" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="bugis-toast"
        style="display: none;">
        <i class="fas fa-check-circle" style="margin-right: 6px;"></i>
        <span x-text="toastMessage">Tersalin!</span>
    </div>
</div>