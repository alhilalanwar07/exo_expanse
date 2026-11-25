@php
    $assetBase = 'themes/wedding-walasuji/assets/';

    // Centralized swappable asset slot map (see public/themes/wedding-walasuji/ASSET_SLOTS.md)
    $slots = [
        'cover_hero_bg'         => asset($assetBase . 'cover-hero-bg.svg'),
        'walasuji_gate_pillar'  => asset($assetBase . 'walasuji-gate-pillar.svg'),
        'canopy_arch_top'       => asset($assetBase . 'canopy-arch-top.svg'),
        'floral_crest_bottom'   => asset($assetBase . 'floral-crest-bottom.svg'),
        'hero_border_frame'     => asset($assetBase . 'hero-border-frame.svg'),
        'bosara_emblem'         => asset($assetBase . 'bosara-emblem.svg'),
        'phinisi_hero_ship'     => asset($assetBase . 'phinisi-hero-ship.svg'),
        'phinisi_horizon'       => asset($assetBase . 'phinisi-horizon-silhouette.svg'),
        'bg_lippa_sabbe'        => asset($assetBase . 'bg-lippa-sabbe-tile.svg'),
        'card_songket_grid'     => asset($assetBase . 'card-songket-grid.svg'),
        'card_corner_drape'     => asset($assetBase . 'card-corner-drape.svg'),
        'card_diamond_jewel'    => asset($assetBase . 'card-diamond-jewel.svg'),
        'divider_ribbon'        => asset($assetBase . 'divider-lippa-sabbe-ribbon.svg'),
        'mempelai_backdrop'     => asset($assetBase . 'mempelai-sea-backdrop.svg'),
        'section_mandala_cap'   => asset($assetBase . 'section-mandala-cap.svg'),
        'walasuji_diamond_frame'=> asset($assetBase . 'walasuji-diamond-frame.svg'),
        'default_groom'         => asset($assetBase . 'default-couple-groom.svg'),
        'default_bride'         => asset($assetBase . 'default-couple-bride.svg'),
        'default_gallery_1'     => asset($assetBase . 'default-gallery-1.svg'),
        'default_gallery_2'     => asset($assetBase . 'default-gallery-2.svg'),
        'default_gallery_3'     => asset($assetBase . 'default-gallery-3.svg'),
    ];

    $styles = $invitation->custom_styles ?? [];
    $colors = $invitation->custom_colors ?? [];
    $eventType = $invitation->event_type ?? ($styles['event_type'] ?? 'both');
    $nameOrder = $styles['name_order'] ?? 'groom_first';

    // Couple data & ordering
    $groomNick = $invitation->groom_nickname ?: 'Arya';
    $groomFull = $invitation->groom_name ?: 'Arya Kusuma Wijaya';
    $brideNick = $invitation->bride_nickname ?: 'Sari';
    $brideFull = $invitation->bride_name ?: 'Sari Permata Dewi';

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

    $groomParents = $formatParents($invitation->groom_father, $invitation->groom_mother, 'Hendra Wijaya', 'Ratna Sari');
    $brideParents = $formatParents($invitation->bride_father, $invitation->bride_mother, 'Darmawan', 'Kartini');

    $groomIgRaw = ltrim(trim((string) ($invitation->instagram_groom ?? '')), '@');
    $brideIgRaw = ltrim(trim((string) ($invitation->instagram_bride ?? '')), '@');

    $groomPhotoUrl = $invitation->groom_photo ? img_url($invitation->groom_photo) : $slots['default_groom'];
    $bridePhotoUrl = $invitation->bride_photo ? img_url($invitation->bride_photo) : $slots['default_bride'];

    $groomChildLabel = trim((string) ($styles['groom_child_order'] ?? '')) !== ''
        ? trim((string) $styles['groom_child_order'])
        : 'Putra Pertama Dari';
    $brideChildLabel = trim((string) ($styles['bride_child_order'] ?? '')) !== ''
        ? trim((string) $styles['bride_child_order'])
        : 'Putri Pertama Dari';

    if ($nameOrder === 'bride_first') {
        $firstPerson = [
            'role' => 'MEMPELAI WANITA',
            'nick' => $brideNick,
            'full' => $brideFull,
            'photo' => $bridePhotoUrl,
            'childLabel' => $brideChildLabel,
            'parents' => $brideParents,
            'ig' => $brideIgRaw,
        ];
        $secondPerson = [
            'role' => 'MEMPELAI PRIA',
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
            'role' => 'MEMPELAI PRIA',
            'nick' => $groomNick,
            'full' => $groomFull,
            'photo' => $groomPhotoUrl,
            'childLabel' => $groomChildLabel,
            'parents' => $groomParents,
            'ig' => $groomIgRaw,
        ];
        $secondPerson = [
            'role' => 'MEMPELAI WANITA',
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

    $coverSubtitle = !empty($styles['cover_subtitle']) ? $styles['cover_subtitle'] : 'UNDANGAN PERNIKAHAN';
    $culturalTag = !empty($styles['cultural_tag']) ? $styles['cultural_tag'] : 'Mappabotting';
    $coupleTitle = !empty($styles['cover_title']) ? $styles['cover_title'] : ($firstNick . ' & ' . $secondNick);
    $coverPhotoUrl = $invitation->cover_image ? img_url($invitation->cover_image) : null;
    $resolvedGuest = trim((string) ($guestName ?? 'Tamu Undangan')) ?: 'Tamu Undangan';

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
    $displayDateFull = $mainDateObj
        ? $mainDateObj->copy()->locale('id')->translatedFormat('d F Y')
        : '12 Desember 2026';

    $countdownDateObj = $invitation->primaryEventDateTime();
    $targetTimestamp = $invitation->countdownTimestamp();

    $formatEventMeta = function ($dateVal, ?string $timeVal, string $defaultTime, string $titlePrefix, string $venue, string $address, ?string $mapsLink) use ($invitation, $coupleTitle, $groomFull, $brideFull): array {
        $d = null;
        if ($dateVal) {
            try {
                $d = \Carbon\Carbon::parse($dateVal)->locale('id');
            } catch (\Throwable $e) {
            }
        }
        $dayName = $d ? strtoupper($d->translatedFormat('l')) : 'SABTU';
        $dayNum = $d ? $d->format('d') : '12';
        $monthYear = $d ? $d->translatedFormat('M Y') : 'Des 2026';

        $t = trim((string) $timeVal);
        $timeDisplay = $invitation->formatCustomTime($t, $defaultTime, true, '.');

        $ymd = $d ? $d->format('Y-m-d') : \Carbon\Carbon::now($invitation->ianaTimezone())->addMonths(2)->format('Y-m-d');
        $calObj = \Carbon\Carbon::createFromFormat('Y-m-d', $ymd, $invitation->ianaTimezone());
        if (preg_match('/^(\d{1,2})[:.](\d{2})/', $t !== '' ? $t : $defaultTime, $tm)) {
            $calObj->setTime((int) $tm[1], (int) $tm[2], 0);
        } else {
            $calObj->setTime(8, 0, 0);
        }

        $locationStr = collect([$venue, $address])->filter()->implode(', ');
        $calendarUrl = 'https://www.google.com/calendar/render?' . http_build_query([
            'action' => 'TEMPLATE',
            'text' => $titlePrefix . ' - ' . $coupleTitle,
            'dates' => $calObj->copy()->utc()->format('Ymd\THis\Z') . '/' . $calObj->copy()->utc()->addHours(3)->format('Ymd\THis\Z'),
            'details' => 'Undangan Pernikahan ' . $groomFull . ' & ' . $brideFull,
            'location' => $locationStr,
        ]);

        $resolvedMap = trim((string) $mapsLink);
        if ($resolvedMap === '' && $locationStr !== '') {
            $resolvedMap = 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode($locationStr);
        }

        return [
            'day' => $dayName,
            'dayNum' => $dayNum,
            'monthYear' => $monthYear,
            'time' => $timeDisplay,
            'venue' => $venue,
            'address' => $address,
            'mapsUrl' => $resolvedMap,
            'calendarUrl' => $calendarUrl,
        ];
    };

    $akadMeta = $formatEventMeta(
        $invitation->akad_date,
        $invitation->resolveTimeHm($invitation->akad_time, $invitation->akad_date, '08:00'),
        '08.00',
        'Akad Nikah',
        (string) ($invitation->akad_venue ?? ''),
        (string) ($invitation->akad_address ?? ''),
        $invitation->akad_maps_link
    );

    $resepsiMeta = $formatEventMeta(
        $invitation->resepsi_date,
        $invitation->resolveTimeHm($invitation->resepsi_time, $invitation->resepsi_date, '11:00'),
        '11.00',
        'Resepsi Pernikahan',
        (string) ($invitation->resepsi_venue ?? ''),
        (string) ($invitation->resepsi_address ?? ''),
        $invitation->resepsi_maps_link
    );

    $mainAddress = ($eventType === 'resepsi_only')
        ? ($resepsiMeta['address'] ?: $resepsiMeta['venue'])
        : ($akadMeta['address'] ?: $akadMeta['venue'] ?: $resepsiMeta['address']);

    // Music
    $musicRaw = $invitation->background_music;
    $musicSrc = '';
    if (!empty($musicRaw)) {
        $musicSrc = str_starts_with($musicRaw, 'http') ? $musicRaw : img_url($musicRaw);
    }
    $musicExt = $musicSrc !== '' ? strtolower(pathinfo((string) parse_url($musicSrc, PHP_URL_PATH), PATHINFO_EXTENSION)) : '';
    $musicMime = match ($musicExt) {
        'wav' => 'audio/wav',
        'ogg' => 'audio/ogg',
        'm4a' => 'audio/mp4',
        'aac' => 'audio/aac',
        'webm' => 'audio/webm',
        default => 'audio/mpeg',
    };

    // Feature & Section Visibility
    $showAkad = in_array($eventType, ['akad_only', 'both'], true) && ($invitation->akad_date || $invitation->akad_venue || $invitation->akad_maps_link || !isset($styles['event_type']));
    $showResepsi = in_array($eventType, ['resepsi_only', 'both'], true) && ($invitation->resepsi_date || $invitation->resepsi_venue || $invitation->resepsi_maps_link || !isset($styles['event_type']));
    $unduhDate = $styles['unduh_mantu_date'] ?? $invitation->unduh_mantu_date ?? null;
    $unduhVenue = $styles['unduh_mantu_venue'] ?? $invitation->unduh_mantu_venue ?? null;
    $showUnduhMantu = !empty($unduhDate) || !empty($unduhVenue);
    $unduhMeta = $showUnduhMantu
        ? $formatEventMeta($unduhDate, $styles['unduh_mantu_time'] ?? null, '11.00', 'Ngunduh Mantu', (string) ($unduhVenue ?? ''), (string) ($styles['unduh_mantu_address'] ?? ''), $styles['unduh_mantu_maps'] ?? null)
        : null;

    $streamingLink = $styles['streaming_link'] ?? $invitation->streaming_link ?? null;
    $showStreaming = !empty($streamingLink);
    $showCountdown = (bool) ($styles['countdown_enabled'] ?? true);

    // Gallery Photos (uploaded first; demo SVG placeholders only when !$invitation->exists)
    $uploadedPhotos = $invitation->relationLoaded('photos') ? $invitation->photos : $invitation->photos()->get();
    $galleryPhotos = $uploadedPhotos->count() > 0
        ? $uploadedPhotos->map(fn ($p) => img_url($p->path))->values()->all()
        : (!$invitation->exists ? [$slots['default_gallery_1'], $slots['default_gallery_2'], $slots['default_gallery_3']] : []);
    $showGallery = (bool) ($invitation->enable_gallery ?? true) && count($galleryPhotos) > 0;

    // Text blocks
    $welcomeMessage = !empty($styles['welcome_message'])
        ? $styles['welcome_message']
        : 'Dengan memohon rahmat dan ridho Allah Subhanahu Wa Ta\'ala, kami bermaksud mengundang Bapak/Ibu/Saudara/i untuk menghadiri pernikahan putra-putri kami:';
    $quoteTitle = trim((string) ($styles['quote_title'] ?? '')) !== '' ? $styles['quote_title'] : 'Q.S Ar-Rum : 21';
    $quranVerse = trim((string) ($styles['quran_verse'] ?? '')) !== ''
        ? $styles['quran_verse']
        : '“Dan di antara tanda-tanda kekuasaan-Nya ialah Dia menciptakan untukmu pasangan-pasangan dari jenismu sendiri, agar kamu cenderung dan merasa tenteram kepadanya, dan Dia menjadikan di antaramu rasa kasih dan sayang.”';
    $quranRef = array_key_exists('quran_ref', $styles) ? trim((string) ($styles['quran_ref'] ?? '')) : $quoteTitle;
    $quotePhotoUrl = !empty($styles['quote_image']) ? img_url($styles['quote_image']) : null;

    // Love Story (supports both 'description' and DemoPage's 'story' key)
    $defaultStories = [
        ['date' => 'MARET 2022', 'title' => 'Pertama Bertemu', 'description' => 'Berawal dari perkenalan sederhana yang hangat, kami mulai mengenal satu sama lain dan menemukan kecocokan yang menenangkan.'],
        ['date' => 'AGUSTUS 2024', 'title' => 'Menjalin Komitmen', 'description' => 'Melewati waktu, jarak, dan cerita bersama membuat kami semakin yakin untuk melangkah menuju ikatan suci pernikahan.'],
        ['date' => 'LAMARAN', 'title' => 'Mappettuada / Lamaran', 'description' => 'Dengan restu kedua orang tua dan keluarga besar, kami mengikat janji dalam adat Bugis Makassar yang penuh makna.'],
    ];
    $filteredStories = (is_array($invitation->love_story) && !empty($invitation->love_story))
        ? array_values(array_filter($invitation->love_story, function ($story) {
            if (!is_array($story)) {
                return false;
            }
            return trim((string) ($story['title'] ?? '')) !== ''
                || trim((string) ($story['description'] ?? $story['story'] ?? '')) !== ''
                || trim((string) ($story['date'] ?? '')) !== '';
        }))
        : [];
    $rawStories = !empty($filteredStories) ? $filteredStories : (!$invitation->exists ? $defaultStories : []);
    $loveStories = collect($rawStories)->values()->map(function ($story, $idx) use ($galleryPhotos) {
        $fallbackImg = count($galleryPhotos) > 0 ? $galleryPhotos[$idx % count($galleryPhotos)] : null;
        return [
            'date' => $story['date'] ?? date('Y'),
            'title' => $story['title'] ?? 'Momen Spesial',
            'description' => $story['description'] ?? $story['story'] ?? '',
            'image' => !empty($story['image']) ? $story['image'] : $fallbackImg,
        ];
    })->all();
    $showLoveStory = count($loveStories) > 0;

    // Wedding Gift Accounts
    $giftAddress = $styles['gift_address'] ?? $invitation->gift_address ?? '';
    $defaultBanks = [
        ['bank' => 'BCA', 'account_number' => '1234567890', 'account_name' => $groomFull],
        ['bank' => 'MANDIRI', 'account_number' => '0987654321', 'account_name' => $brideFull],
    ];
    $bankAccounts = (!empty($invitation->bank_accounts) && is_array($invitation->bank_accounts))
        ? array_values(array_filter($invitation->bank_accounts, fn ($acc) => !empty($acc['account_number']) || !empty($acc['bank'])))
        : (!$invitation->exists ? $defaultBanks : []);
    $showGift = (bool) ($invitation->enable_gift ?? true) && (count($bankAccounts) > 0 || trim((string) $giftAddress) !== '');

    $showRsvp = (bool) (($invitation->enable_rsvp ?? true) || ($invitation->enable_wishes ?? true));
    $turutMengundang = trim((string) ($styles['turut_mengundang'] ?? ''));
    $eventNote = trim((string) ($styles['dress_code'] ?? $styles['event_note'] ?? ''));

    $wishesList = $wishes ?? collect([]);
    $wishesCount = $totalWishesCount ?? $wishesList->count();

    // Custom color overrides with deep crimson & gold defaults
    $cPrimary = $colors['primary'] ?? '#5B0B14';
    $cSecondary = $colors['secondary'] ?? '#8F1523';
    $cAccent = $colors['accent'] ?? '#D4AF37';
    $cBg = $colors['background'] ?? '#FBF6EE';
    $cText = $colors['text'] ?? '#2E1A1D';
@endphp

@once
    @push('fonts')
        <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=Cormorant+Garamond:ital,wght@0,500;0,600;0,700;1,400;1,600&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    @endpush

    @push('styles')
        <style>
            html, body {
                margin: 0;
                padding: 0;
                background-color: #3A060C;
                overflow-x: hidden;
            }
            body.wl-locked {
                height: 100vh !important;
                height: 100dvh !important;
                overflow: hidden !important;
            }

            .wl-root {
                --wl-crimson-deep: {{ $cPrimary }};
                --wl-crimson: {{ $cSecondary }};
                --wl-crimson-warm: #B9232E;
                --wl-crimson-soft: #F9EAEC;
                --wl-gold: {{ $cAccent }};
                --wl-gold-light: #F1D68A;
                --wl-ivory: {{ $cBg }};
                --wl-card: #FFFFFF;
                --wl-ink: {{ $cText }};
                --wl-muted: #6E5357;
                --wl-font-heading: 'Cormorant Garamond', Georgia, serif;
                --wl-font-body: 'Plus Jakarta Sans', system-ui, sans-serif;
                --wl-font-arabic: 'Amiri', Georgia, serif;
                font-family: var(--wl-font-body);
                color: var(--wl-ink);
                background-color: #3A060C;
                min-height: 100dvh;
                position: relative;
            }
            .wl-root *, .wl-root *::before, .wl-root *::after {
                box-sizing: border-box;
            }
            .wl-font-heading { font-family: var(--wl-font-heading); }
            .wl-font-body { font-family: var(--wl-font-body); }
            .wl-font-arabic { font-family: var(--wl-font-arabic); }

            /* Split-Screen Layout on Desktop (>=1024px) */
            .wl-desktop-left {
                display: none;
            }
            .wl-mobile-col {
                width: 100%;
                min-height: 100dvh;
                position: relative;
                background-color: var(--wl-ivory);
            }
            @media (min-width: 1024px) {
                .wl-desktop-left {
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    justify-content: center;
                    position: fixed;
                    top: 0;
                    left: 0;
                    bottom: 0;
                    width: calc(100% - 450px);
                    z-index: 50;
                    padding: 48px;
                    text-align: center;
                    color: #FFFFFF;
                    background: radial-gradient(circle at 50% 35%, rgba(143, 21, 35, 0.72), rgba(41, 3, 8, 0.96)),
                                linear-gradient(160deg, #5B0B14 0%, #3A060C 55%, #240206 100%);
                    overflow: hidden;
                }
                .wl-desktop-left-pattern {
                    position: absolute;
                    inset: 0;
                    opacity: 0.14;
                    background-image: radial-gradient(circle at 1px 1px, #D4AF37 1.2px, transparent 0);
                    background-size: 28px 28px;
                    pointer-events: none;
                }
                .wl-desktop-rule {
                    width: 68px;
                    height: 2px;
                    background: linear-gradient(90deg, transparent, var(--wl-gold), transparent);
                    margin: 18px auto 14px;
                }
                .wl-mobile-col {
                    width: 450px;
                    margin-left: auto;
                    box-shadow: -14px 0 42px rgba(0, 0, 0, 0.55);
                }
            }

            /* Cover Lock Gate */
            .wl-cover-gate {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                z-index: 900;
                background-color: #3A060C;
                overflow-y: auto;
                overflow-x: hidden;
                display: flex;
                flex-direction: column;
                align-items: center;
                transition: opacity 0.85s ease 0.22s, visibility 0.85s ease 0.22s;
            }
            @media (min-width: 1024px) {
                .wl-cover-gate {
                    left: auto;
                    width: 450px;
                }
            }
            .wl-cover-gate.is-opened {
                opacity: 0;
                visibility: hidden;
                pointer-events: none;
            }
            .wl-cover-bg {
                position: absolute;
                inset: 0;
                width: 100%;
                height: 100%;
                object-fit: cover;
                z-index: 0;
            }
            .wl-cover-photo-tint {
                position: absolute;
                inset: 0;
                width: 100%;
                height: 100%;
                object-fit: cover;
                opacity: 0.26;
                mix-blend-mode: luminosity;
                z-index: 1;
                pointer-events: none;
            }
            .wl-cover-scrim {
                position: absolute;
                inset: 0;
                background: linear-gradient(180deg, rgba(41, 3, 8, 0.55) 0%, rgba(91, 11, 20, 0.35) 50%, rgba(41, 3, 8, 0.82) 100%);
                z-index: 2;
                pointer-events: none;
            }

            /* Woven Walasuji Side Pillars */
            .wl-pillar-left,
            .wl-pillar-right {
                position: absolute;
                top: 0;
                bottom: 0;
                width: clamp(36px, 10vw, 54px);
                height: 100%;
                object-fit: fill;
                z-index: 6;
                pointer-events: none;
                transition: transform 0.95s cubic-bezier(0.77, 0, 0.175, 1), opacity 0.75s ease;
            }
            .wl-pillar-left { left: 0; }
            .wl-pillar-right { right: 0; transform: scaleX(-1); }
            .wl-cover-gate.is-opened .wl-pillar-left {
                transform: translateX(-120%);
                opacity: 0;
            }
            .wl-cover-gate.is-opened .wl-pillar-right {
                transform: translateX(120%) scaleX(-1);
                opacity: 0;
            }

            .wl-canopy-top {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                max-height: 22%;
                object-fit: cover;
                object-position: top center;
                z-index: 5;
                pointer-events: none;
            }
            .wl-crest-bottom {
                position: absolute;
                bottom: 0;
                left: 0;
                width: 100%;
                max-height: 24%;
                object-fit: cover;
                object-position: bottom center;
                z-index: 4;
                pointer-events: none;
            }

            /* Horizon gliding Phinisi & floating sparkles */
            @keyframes wlSailGlide {
                0% { transform: translateX(-18%); opacity: 0.75; }
                50% { transform: translateX(18%); opacity: 0.95; }
                100% { transform: translateX(-18%); opacity: 0.75; }
            }
            @keyframes wlSparkleFloat {
                0%, 100% { transform: translateY(0) rotate(45deg) scale(1); opacity: 0.35; }
                50% { transform: translateY(-14px) rotate(45deg) scale(1.25); opacity: 0.9; }
            }
            @keyframes wlFloatSlow {
                0%, 100% { transform: translateY(0); }
                50% { transform: translateY(-5px); }
            }
            .wl-horizon-ship {
                position: absolute;
                bottom: 24%;
                left: 50%;
                margin-left: -45px;
                width: 90px;
                height: auto;
                z-index: 3;
                pointer-events: none;
                animation: wlSailGlide 24s ease-in-out infinite;
            }
            .wl-sparkle-layer {
                position: absolute;
                inset: 0;
                overflow: hidden;
                pointer-events: none;
                z-index: 3;
            }
            .wl-sparkle {
                position: absolute;
                width: 7px;
                height: 7px;
                background: linear-gradient(135deg, #F9E7B2, #D4AF37);
                transform: rotate(45deg);
                animation: wlSparkleFloat 4.6s ease-in-out infinite;
            }
            .wl-float-slow {
                animation: wlFloatSlow 4.5s ease-in-out infinite;
            }

            .wl-cover-stack {
                position: relative;
                z-index: 8;
                width: 100%;
                margin: auto 0;
                padding: clamp(54px, 8dvh, 78px) clamp(44px, 12vw, 62px) clamp(50px, 7dvh, 72px);
                display: flex;
                flex-direction: column;
                align-items: center;
                text-align: center;
                color: #FFFFFF;
            }
            .wl-eyebrow-light {
                font-size: 11px;
                font-weight: 700;
                letter-spacing: 0.24em;
                text-transform: uppercase;
                color: var(--wl-gold-light);
            }
            .wl-cover-names {
                font-family: var(--wl-font-heading);
                font-size: clamp(32px, 5dvh, 42px);
                font-weight: 700;
                line-height: 1.05;
                color: #FFFFFF;
                text-shadow: 0 2px 14px rgba(0, 0, 0, 0.55);
                margin: 4px 0 10px;
            }
            .wl-cover-amp {
                display: block;
                font-style: italic;
                font-weight: 600;
                font-size: 0.68em;
                color: var(--wl-gold-light);
                margin: -2px 0;
            }

            /* Chamfered Octagon Geometry */
            .wl-chamfer-pill {
                display: inline-block;
                background: rgba(91, 11, 20, 0.85);
                border: 1px solid rgba(212, 175, 55, 0.65);
                color: var(--wl-gold-light);
                font-size: 12px;
                font-weight: 600;
                letter-spacing: 0.08em;
                padding: 5px 20px;
                clip-path: polygon(8px 0, calc(100% - 8px) 0, 100% 50%, calc(100% - 8px) 100%, 8px 100%, 0 50%);
            }
            .wl-cover-guest-box {
                width: 100%;
                max-width: 280px;
                margin: 12px auto;
                padding: 12px 18px;
                background: rgba(255, 255, 255, 0.94);
                color: var(--wl-ink);
                border-top: 2px solid var(--wl-crimson);
                border-bottom: 2px solid var(--wl-gold);
                clip-path: polygon(12px 0, calc(100% - 12px) 0, 100% 12px, 100% calc(100% - 12px), calc(100% - 12px) 100%, 12px 100%, 0 calc(100% - 12px), 0 12px);
                box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
            }

            /* 45-Deg Rotated Diamond Countdown Tiles */
            .wl-diamond-grid {
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 14px;
                margin: 10px 0 16px;
                flex-wrap: nowrap;
            }
            .wl-diamond-tile {
                width: 48px;
                height: 48px;
                transform: rotate(45deg);
                border: 1.5px solid var(--wl-gold);
                display: flex;
                align-items: center;
                justify-content: center;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.25);
                flex-shrink: 0;
            }
            .wl-diamond-tile.is-deep { background-color: var(--wl-crimson-deep); color: #FFFFFF; }
            .wl-diamond-tile.is-warm { background-color: var(--wl-crimson-warm); color: #FFFFFF; }
            .wl-diamond-inner {
                transform: rotate(-45deg);
                text-align: center;
                line-height: 1.05;
            }
            .wl-diamond-val {
                display: block;
                font-size: 14px;
                font-weight: 700;
            }
            .wl-diamond-lbl {
                display: block;
                font-size: 8px;
                letter-spacing: 0.06em;
                text-transform: uppercase;
                color: var(--wl-gold-light);
            }

            /* Chamfered Action Buttons */
            .wl-btn {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 7px;
                font-family: var(--wl-font-body);
                font-size: 12px;
                font-weight: 700;
                letter-spacing: 0.09em;
                text-transform: uppercase;
                padding: 11px 22px;
                border: none;
                cursor: pointer;
                text-decoration: none;
                clip-path: polygon(10px 0, calc(100% - 10px) 0, 100% 10px, 100% calc(100% - 10px), calc(100% - 10px) 100%, 10px 100%, 0 calc(100% - 10px), 0 10px);
                transition: transform 0.2s ease, opacity 0.2s ease;
            }
            .wl-btn:hover { opacity: 0.92; transform: translateY(-1px); }
            .wl-btn-warm {
                background: linear-gradient(135deg, #B9232E, #8F1523);
                color: #FFFFFF;
                box-shadow: 0 6px 18px rgba(91, 11, 20, 0.45);
            }
            .wl-btn-primary {
                background-color: var(--wl-crimson-deep);
                color: #FFFFFF;
            }
            .wl-btn-soft {
                background-color: var(--wl-crimson-soft);
                color: var(--wl-crimson-deep);
            }
            .wl-btn-gold {
                background: linear-gradient(135deg, #D4AF37, #A67C23);
                color: #290308;
            }

            /* Hero Banner */
            .wl-hero {
                position: relative;
                min-height: 100dvh;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                padding: 72px 28px 92px;
                text-align: center;
                color: #FFFFFF;
                overflow: hidden;
                background-color: #3A060C;
            }
            .wl-hero-frame {
                position: absolute;
                inset: 0;
                width: 100%;
                height: 100%;
                object-fit: fill;
                z-index: 5;
                pointer-events: none;
            }
            .wl-hero-mist {
                position: absolute;
                bottom: 0;
                left: 0;
                right: 0;
                height: 76px;
                background: linear-gradient(180deg, rgba(251, 246, 238, 0) 0%, var(--wl-ivory) 100%);
                z-index: 6;
                pointer-events: none;
            }

            /* Ivory Lippa Sabbe Light Canvas */
            .wl-ivory-canvas {
                background-color: var(--wl-ivory);
                background-repeat: repeat;
                background-size: 160px 160px;
                position: relative;
            }
            .wl-section {
                padding: 44px 20px;
                position: relative;
                z-index: 2;
            }
            .wl-section-header {
                text-align: center;
                margin-bottom: 24px;
            }
            .wl-eyebrow-red {
                font-size: 11px;
                font-weight: 700;
                letter-spacing: 0.24em;
                text-transform: uppercase;
                color: var(--wl-crimson-warm);
                margin-bottom: 4px;
            }
            .wl-heading-dark {
                font-family: var(--wl-font-heading);
                font-size: 32px;
                font-weight: 700;
                line-height: 1.15;
                color: var(--wl-crimson-deep);
                margin: 0;
            }
            .wl-divider-ribbon {
                display: block;
                width: 240px;
                max-width: 78%;
                height: auto;
                margin: 10px auto 0;
            }

            /* Signature Chamfered Songket Card */
            .wl-card-outer {
                filter: drop-shadow(0 8px 20px rgba(91, 11, 20, 0.1));
                margin-bottom: 20px;
            }
            .wl-card-shell {
                background: linear-gradient(135deg, #D4AF37 0%, #8F1523 50%, #D4AF37 100%);
                padding: 1.5px;
                clip-path: polygon(16px 0, calc(100% - 16px) 0, 100% 16px, 100% calc(100% - 16px), calc(100% - 16px) 100%, 16px 100%, 0 calc(100% - 16px), 0 16px);
            }
            .wl-card-inner {
                position: relative;
                background-color: var(--wl-card);
                background-repeat: repeat;
                background-size: 48px 48px;
                padding: 34px 22px 26px;
                clip-path: polygon(15px 0, calc(100% - 15px) 0, 100% 15px, 100% calc(100% - 15px), calc(100% - 15px) 100%, 15px 100%, 0 calc(100% - 15px), 0 15px);
                overflow: hidden;
            }
            .wl-card-inner::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                height: 3.5px;
                background-color: var(--wl-crimson-deep);
            }
            .wl-card-inner::after {
                content: '';
                position: absolute;
                bottom: 0;
                left: 0;
                right: 0;
                height: 3.5px;
                background: linear-gradient(90deg, var(--wl-gold), var(--wl-gold-light), var(--wl-gold));
            }
            .wl-card-drape-tl {
                position: absolute;
                top: 0;
                left: 0;
                width: 62px;
                height: 62px;
                pointer-events: none;
                z-index: 1;
            }
            .wl-card-drape-br {
                position: absolute;
                bottom: 0;
                right: 0;
                width: 62px;
                height: 62px;
                transform: rotate(180deg);
                pointer-events: none;
                z-index: 1;
            }
            .wl-card-jewel {
                display: block;
                width: 38px;
                height: 38px;
                margin: -10px auto 12px;
                position: relative;
                z-index: 2;
            }
            .wl-card-body {
                position: relative;
                z-index: 2;
            }

            /* Mempelai (Dark Full-Bleed Heritage Section) */
            .wl-mempelai {
                position: relative;
                background-color: #3A060C;
                color: #FFFFFF;
                padding: 86px 22px 96px;
                overflow: hidden;
                text-align: center;
            }
            .wl-mempelai-bg {
                position: absolute;
                inset: 0;
                width: 100%;
                height: 100%;
                object-fit: cover;
                z-index: 0;
                pointer-events: none;
            }
            .wl-mandala-top {
                position: absolute;
                top: -1px;
                left: 0;
                width: 100%;
                height: auto;
                max-height: 92px;
                object-fit: cover;
                object-position: top center;
                z-index: 2;
                pointer-events: none;
            }
            .wl-mandala-bottom {
                position: absolute;
                bottom: -1px;
                left: 0;
                width: 100%;
                height: auto;
                max-height: 92px;
                object-fit: cover;
                object-position: top center;
                transform: scaleY(-1);
                z-index: 2;
                pointer-events: none;
            }
            .wl-portrait-stage {
                position: relative;
                width: 224px;
                height: 224px;
                margin: 0 auto 14px;
            }
            .wl-couple-diamond {
                position: absolute;
                inset: 13.5%;
                clip-path: polygon(50% 0%, 100% 50%, 50% 100%, 0% 50%);
                background-color: var(--wl-crimson-deep);
                overflow: hidden;
                z-index: 1;
            }
            .wl-couple-diamond img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                object-position: center 20%;
                display: block;
            }
            .wl-walasuji-frame-overlay {
                position: absolute;
                inset: 0;
                width: 100%;
                height: 100%;
                object-fit: contain;
                z-index: 2;
                pointer-events: none;
            }

            /* Love Story Timeline Rail */
            .wl-timeline {
                position: relative;
                padding-left: 36px;
            }
            .wl-timeline::before {
                content: '';
                position: absolute;
                top: 14px;
                bottom: 24px;
                left: 13px;
                width: 2px;
                background: linear-gradient(180deg, var(--wl-gold), var(--wl-crimson), var(--wl-gold));
            }
            .wl-timeline-item {
                position: relative;
                margin-bottom: 20px;
            }
            .wl-timeline-node {
                position: absolute;
                left: -36px;
                top: 22px;
                width: 28px;
                height: 28px;
                z-index: 3;
            }

            /* Coverflow Gallery Carousel */
            .wl-gallery-stage {
                position: relative;
                width: 100%;
                height: 290px;
                display: flex;
                align-items: center;
                justify-content: center;
                overflow: hidden;
                touch-action: pan-y;
                margin-bottom: 16px;
            }
            .wl-gal-slide {
                position: absolute;
                width: 72%;
                height: 248px;
                padding: 2.5px;
                background: linear-gradient(135deg, var(--wl-gold-light), var(--wl-crimson), var(--wl-gold));
                clip-path: polygon(18px 0, calc(100% - 18px) 0, 100% 18px, 100% calc(100% - 18px), calc(100% - 18px) 100%, 18px 100%, 0 calc(100% - 18px), 0 18px);
                transition: transform 0.45s cubic-bezier(0.22, 1, 0.36, 1), opacity 0.45s ease;
                cursor: pointer;
                box-shadow: 0 10px 26px rgba(91, 11, 20, 0.2);
            }
            .wl-gal-slide-inner {
                width: 100%;
                height: 100%;
                overflow: hidden;
                background-color: #290308;
                clip-path: polygon(16px 0, calc(100% - 16px) 0, 100% 16px, 100% calc(100% - 16px), calc(100% - 16px) 100%, 16px 100%, 0 calc(100% - 16px), 0 16px);
            }
            .wl-gal-slide-inner img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                display: block;
            }
            .wl-gal-controls {
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 20px;
            }
            .wl-gal-arrow {
                width: 38px;
                height: 38px;
                border-radius: 50%;
                background-color: var(--wl-crimson-deep);
                color: var(--wl-gold-light);
                border: 1.5px solid var(--wl-gold);
                display: inline-flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                transition: transform 0.2s ease;
            }
            .wl-gal-arrow:hover { transform: scale(1.06); }

            /* RSVP & Wishes Form Inputs */
            .wl-label {
                display: block;
                font-size: 10px;
                font-weight: 700;
                letter-spacing: 0.14em;
                text-transform: uppercase;
                color: var(--wl-crimson);
                margin-bottom: 5px;
            }
            .wl-input, .wl-select, .wl-textarea {
                width: 100%;
                background-color: #FFFFFF;
                border: 1.5px solid rgba(143, 21, 35, 0.24);
                border-radius: 8px;
                padding: 10px 12px;
                font-family: var(--wl-font-body);
                font-size: 13px;
                color: var(--wl-ink);
                outline: none;
                transition: border-color 0.2s ease, box-shadow 0.2s ease;
            }
            .wl-input:focus, .wl-select:focus, .wl-textarea:focus {
                border-color: var(--wl-crimson);
                box-shadow: 0 0 0 3px rgba(143, 21, 35, 0.12);
            }

            /* Floating Bottom Nav Dock & Quick Controls */
            .wl-dock-wrap {
                position: fixed;
                bottom: 14px;
                left: 0;
                right: 0;
                z-index: 800;
                display: flex;
                justify-content: center;
                padding: 0 14px;
                pointer-events: none;
            }
            @media (min-width: 1024px) {
                .wl-dock-wrap {
                    left: auto;
                    width: 450px;
                }
            }
            .wl-dock {
                pointer-events: auto;
                position: relative;
                display: flex;
                align-items: center;
                justify-content: space-around;
                gap: 4px;
                width: 100%;
                max-width: 390px;
                padding: 7px 14px 9px;
                background: rgba(58, 6, 12, 0.95);
                backdrop-filter: blur(10px);
                -webkit-backdrop-filter: blur(10px);
                border-bottom: 2.5px solid var(--wl-gold);
                box-shadow: 0 10px 28px rgba(0, 0, 0, 0.45);
                clip-path: polygon(14px 0, calc(100% - 14px) 0, 100% 14px, 100% calc(100% - 14px), calc(100% - 14px) 100%, 14px 100%, 0 calc(100% - 14px), 0 14px);
            }
            .wl-dock-item {
                position: relative;
                width: 40px;
                height: 36px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                color: rgba(255, 255, 255, 0.78);
                text-decoration: none;
                transition: color 0.25s ease;
            }
            .wl-dock-item.is-active {
                color: var(--wl-crimson-deep);
            }
            .wl-dock-item.is-active::before {
                content: '';
                position: absolute;
                width: 28px;
                height: 28px;
                background: linear-gradient(135deg, var(--wl-gold-light), var(--wl-gold));
                transform: rotate(45deg);
                z-index: 0;
                box-shadow: 0 2px 10px rgba(212, 175, 55, 0.55);
            }
            .wl-dock-item svg {
                position: relative;
                z-index: 1;
                width: 17px;
                height: 17px;
            }

            .wl-fab-stack {
                position: fixed;
                bottom: 76px;
                right: 16px;
                z-index: 800;
                display: flex;
                flex-direction: column;
                gap: 8px;
            }
            .wl-fab-btn {
                width: 38px;
                height: 38px;
                border-radius: 50%;
                background: rgba(58, 6, 12, 0.92);
                color: var(--wl-gold-light);
                border: 1.5px solid var(--wl-gold);
                display: inline-flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                box-shadow: 0 4px 14px rgba(0, 0, 0, 0.35);
            }

            /* Scroll Reveal */
            .wl-reveal {
                opacity: 0;
                transform: translateY(20px);
                transition: opacity 0.7s ease, transform 0.7s ease;
            }
            .wl-revealed {
                opacity: 1;
                transform: translateY(0);
            }
            @media (prefers-reduced-motion: reduce) {
                .wl-horizon-ship, .wl-sparkle, .wl-float-slow { animation: none !important; }
                .wl-reveal { opacity: 1 !important; transform: none !important; }
            }
        </style>
    @endpush
@endonce

<div class="wl-root"
     x-data="walasujiTheme({
        targetTimestamp: {{ $targetTimestamp }},
        galleryCount: {{ count($galleryPhotos) }},
        hasMusic: {{ $musicSrc !== '' ? 'true' : 'false' }}
     })">

    <!-- Hidden Audio Element -->
    @if($musicSrc !== '')
        <div wire:ignore style="position: absolute; width: 0; height: 0; overflow: hidden; opacity: 0; pointer-events: none;" aria-hidden="true">
            <audio id="wl-song" loop preload="none" playsinline webkit-playsinline src="{{ $musicSrc }}">
                <source src="{{ $musicSrc }}" type="{{ $musicMime }}">
            </audio>
        </div>
    @endif

    <!-- Desktop Left Fixed Split Panel -->
    <aside class="wl-desktop-left" aria-hidden="true">
        <div class="wl-desktop-left-pattern"></div>
        <div style="position: relative; z-index: 2; max-width: 560px;">
            <p class="wl-eyebrow-light" style="margin-bottom: 14px;">{{ $coverSubtitle }}</p>
            <h2 class="wl-font-heading" style="font-size: clamp(46px, 5vw, 68px); font-weight: 700; line-height: 1.08; margin: 0; color: #FFFFFF;">
                {{ $coupleTitle }}
            </h2>
            <div class="wl-desktop-rule"></div>
            <p style="font-size: 15px; letter-spacing: 0.14em; color: var(--wl-gold-light); margin: 0;">
                {{ $displayDateFull }}
            </p>
        </div>
    </aside>

    <!-- Opening Cover Gate -->
    <div id="wl-cover" class="wl-cover-gate" :class="{ 'is-opened': isOpened }" :aria-hidden="isOpened ? 'true' : 'false'">
        <img src="{{ $slots['cover_hero_bg'] }}" alt="" class="wl-cover-bg" fetchpriority="high">
        @if($coverPhotoUrl)
            <img src="{{ $coverPhotoUrl }}" alt="{{ $coupleTitle }}" class="wl-cover-photo-tint">
        @endif
        <div class="wl-cover-scrim"></div>

        <!-- Left & Right Walasuji Bamboo Gate Pillars -->
        <img src="{{ $slots['walasuji_gate_pillar'] }}" alt="" class="wl-pillar-left">
        <img src="{{ $slots['walasuji_gate_pillar'] }}" alt="" class="wl-pillar-right">

        <img src="{{ $slots['canopy_arch_top'] }}" alt="" class="wl-canopy-top">
        <img src="{{ $slots['floral_crest_bottom'] }}" alt="" class="wl-crest-bottom">
        <img src="{{ $slots['phinisi_horizon'] }}" alt="" class="wl-horizon-ship">

        <div class="wl-sparkle-layer">
            <span class="wl-sparkle" style="top: 18%; left: 18%; animation-delay: 0s;"></span>
            <span class="wl-sparkle" style="top: 26%; right: 20%; animation-delay: 1.1s;"></span>
            <span class="wl-sparkle" style="top: 48%; left: 15%; animation-delay: 2.2s;"></span>
            <span class="wl-sparkle" style="top: 56%; right: 17%; animation-delay: 0.7s;"></span>
        </div>

        <div class="wl-cover-stack">
            <img src="{{ $slots['bosara_emblem'] }}" alt="Bosara" class="wl-float-slow" style="width: 68px; height: 68px; margin-bottom: 6px;">
            <p class="wl-font-heading" style="font-style: italic; font-size: 16px; color: var(--wl-gold-light); margin: 0 0 2px;">{{ $culturalTag }}</p>
            <p class="wl-eyebrow-light" style="font-size: 10px; margin: 0 0 6px;">{{ $coverSubtitle }}</p>

            <h1 class="wl-cover-names">
                {{ $firstNick }}
                <span class="wl-cover-amp">&amp;</span>
                {{ $secondNick }}
            </h1>

            <div class="wl-chamfer-pill">{{ $displayDateFull }}</div>

            <div class="wl-cover-guest-box">
                <p style="font-size: 11px; color: var(--wl-muted); margin: 0 0 3px;">Kepada Yth. Bapak/Ibu/Saudara/i</p>
                <p style="font-size: 15px; font-weight: 700; color: var(--wl-crimson-deep); margin: 0; word-break: break-word;">{{ $resolvedGuest }}</p>
            </div>

            @if($showCountdown)
                <div class="wl-diamond-grid" aria-label="Hitung mundur acara">
                    <div class="wl-diamond-tile is-deep">
                        <div class="wl-diamond-inner"><span class="wl-diamond-val" x-text="days">00</span><span class="wl-diamond-lbl">Hari</span></div>
                    </div>
                    <div class="wl-diamond-tile is-warm">
                        <div class="wl-diamond-inner"><span class="wl-diamond-val" x-text="hours">00</span><span class="wl-diamond-lbl">Jam</span></div>
                    </div>
                    <div class="wl-diamond-tile is-deep">
                        <div class="wl-diamond-inner"><span class="wl-diamond-val" x-text="minutes">00</span><span class="wl-diamond-lbl">Menit</span></div>
                    </div>
                    <div class="wl-diamond-tile is-warm">
                        <div class="wl-diamond-inner"><span class="wl-diamond-val" x-text="seconds">00</span><span class="wl-diamond-lbl">Detik</span></div>
                    </div>
                </div>
            @endif

            <button type="button" @click="openInvitation()" class="wl-btn wl-btn-warm">
                <svg style="width: 15px; height: 15px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                <span>Buka Undangan</span>
            </button>
        </div>
    </div>

    <!-- Main Right / Mobile Invitation Column -->
    <main class="wl-mobile-col">

        <!-- 1. Hero Section -->
        <section id="hero" class="wl-hero">
            <img src="{{ $slots['cover_hero_bg'] }}" alt="" class="wl-cover-bg">
            <div class="wl-cover-scrim"></div>
            <img src="{{ $slots['hero_border_frame'] }}" alt="" class="wl-hero-frame">
            <img src="{{ $slots['canopy_arch_top'] }}" alt="" class="wl-canopy-top">
            <img src="{{ $slots['floral_crest_bottom'] }}" alt="" class="wl-crest-bottom">
            <img src="{{ $slots['phinisi_horizon'] }}" alt="" class="wl-horizon-ship" style="bottom: 18%;">
            <div class="wl-hero-mist"></div>

            <div style="position: relative; z-index: 8; width: 100%; max-width: 340px;">
                <img src="{{ $slots['phinisi_hero_ship'] }}" alt="Kapal Phinisi" class="wl-float-slow" style="width: 185px; max-width: 72%; height: auto; margin: 0 auto 10px; display: block;">
                <p class="wl-eyebrow-light" style="margin-bottom: 6px;">THE WEDDING OF</p>
                <h2 class="wl-cover-names" style="margin-bottom: 10px;">
                    {{ $firstNick }}
                    <span class="wl-cover-amp">&amp;</span>
                    {{ $secondNick }}
                </h2>
                <div class="wl-chamfer-pill" style="margin-bottom: 16px;">{{ $displayDateFull }}</div>
                <p style="font-size: 11px; color: rgba(255,255,255,0.8); margin: 0 0 3px;">Kepada Yth.</p>
                <p class="wl-font-heading" style="font-size: 21px; font-weight: 700; color: var(--wl-gold-light); margin: 0; word-break: break-word;">
                    {{ $resolvedGuest }}
                </p>
            </div>
        </section>

        <!-- Light Ivory Woven Canvas Wrapper -->
        <div class="wl-ivory-canvas" style="background-image: url('{{ $slots['bg_lippa_sabbe'] }}');">

            <!-- 2. Quote / Ayat Section -->
            <section id="quote" class="wl-section wl-reveal">
                <div class="wl-card-outer">
                    <div class="wl-card-shell">
                        <div class="wl-card-inner" style="background-image: url('{{ $slots['card_songket_grid'] }}');">
                            <img src="{{ $slots['card_corner_drape'] }}" alt="" class="wl-card-drape-tl">
                            <img src="{{ $slots['card_corner_drape'] }}" alt="" class="wl-card-drape-br">
                            <img src="{{ $slots['card_diamond_jewel'] }}" alt="" class="wl-card-jewel">

                            <div class="wl-card-body" style="text-align: center;">
                                @if($quotePhotoUrl)
                                    <div style="width: 150px; height: 150px; margin: 0 auto 16px; border: 2.5px solid var(--wl-gold); padding: 4px; background: #FFF; clip-path: polygon(14px 0, calc(100% - 14px) 0, 100% 14px, 100% calc(100% - 14px), calc(100% - 14px) 100%, 14px 100%, 0 calc(100% - 14px), 0 14px);">
                                        <img src="{{ $quotePhotoUrl }}" alt="{{ $coupleTitle }}" style="width: 100%; height: 100%; object-fit: cover; display: block;">
                                    </div>
                                @endif

                                <p class="wl-font-arabic" dir="rtl" style="font-size: 20px; line-height: 1.9; color: var(--wl-crimson-deep); margin: 0 0 8px;">
                                    بِسْمِ اللَّهِ الرَّحْمَنِ الرَّحِيمِ
                                </p>
                                <p class="wl-font-arabic" dir="rtl" style="font-size: 18px; line-height: 2; color: var(--wl-crimson); margin: 0 0 14px;">
                                    وَمِنْ آيَاتِهِ أَنْ خَلَقَ لَكُم مِّنْ أَنفُسِكُمْ أَزْوَاجًا لِّتَسْكُنُوا إِلَيْهَا وَجَعَلَ بَيْنَكُم مَّوَدَّةً وَرَحْمَةً
                                </p>
                                <p style="font-size: 13px; line-height: 1.75; color: var(--wl-ink); margin: 0 0 10px;">
                                    {{ $quranVerse }}
                                </p>
                                @if($quranRef !== '')
                                    <p style="font-size: 11px; font-weight: 700; letter-spacing: 0.14em; text-transform: uppercase; color: var(--wl-crimson-warm); margin: 0;">
                                        — {{ $quranRef }} —
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <!-- 3. Kedua Mempelai (Dark Atmospheric Crimson Section) -->
        <section id="mempelai" class="wl-mempelai wl-section-spy">
            <img src="{{ $slots['mempelai_backdrop'] }}" alt="" class="wl-mempelai-bg">
            <img src="{{ $slots['section_mandala_cap'] }}" alt="" class="wl-mandala-top">
            <img src="{{ $slots['section_mandala_cap'] }}" alt="" class="wl-mandala-bottom">

            <div class="wl-reveal" style="position: relative; z-index: 3;">
                <p class="wl-eyebrow-light" style="margin-bottom: 4px;">MEMPELAI</p>
                <h2 class="wl-font-heading" style="font-size: 34px; font-weight: 700; color: #FFFFFF; margin: 0;">Kedua Mempelai</h2>
                <img src="{{ $slots['divider_ribbon'] }}" alt="" class="wl-divider-ribbon">

                <p style="font-size: 13px; line-height: 1.7; color: rgba(255, 255, 255, 0.86); max-width: 340px; margin: 16px auto 30px;">
                    {{ $welcomeMessage }}
                </p>

                <!-- First Person -->
                <div style="margin-bottom: 22px;">
                    <div class="wl-portrait-stage">
                        <div class="wl-couple-diamond">
                            <img src="{{ $firstPerson['photo'] }}" alt="{{ $firstPerson['full'] }}" loading="lazy">
                        </div>
                        <img src="{{ $slots['walasuji_diamond_frame'] }}" alt="" class="wl-walasuji-frame-overlay">
                    </div>

                    <p class="wl-eyebrow-light" style="font-size: 10px; margin: 6px 0 4px;">{{ $firstPerson['role'] }}</p>
                    <h3 class="wl-font-heading" style="font-size: 34px; font-weight: 700; color: #FFFFFF; margin: 0 0 2px;">{{ $firstPerson['nick'] }}</h3>
                    <p style="font-size: 15px; font-weight: 700; color: var(--wl-gold-light); margin: 0 0 6px;">{{ $firstPerson['full'] }}</p>
                    <p style="font-size: 12px; color: rgba(255,255,255,0.82); line-height: 1.6; margin: 0 auto 12px; max-width: 300px;">
                        {{ $firstPerson['childLabel'] }}<br>{{ $firstPerson['parents'] }}
                    </p>
                    @if($firstPerson['ig'] !== '')
                        <a href="https://www.instagram.com/{{ $firstPerson['ig'] }}" target="_blank" rel="noopener" class="wl-btn wl-btn-soft" style="background: rgba(255,255,255,0.12); color: var(--wl-gold-light); border: 1px solid rgba(212,175,55,0.45); padding: 7px 16px; font-size: 11px;">
                            <span>@<span>{{ $firstPerson['ig'] }}</span></span>
                        </a>
                    @endif
                </div>

                <!-- Ampersand Divider -->
                <div class="wl-font-heading" style="font-style: italic; font-size: 40px; font-weight: 600; color: var(--wl-gold-light); margin: 8px 0 18px;">&amp;</div>

                <!-- Second Person -->
                <div>
                    <div class="wl-portrait-stage">
                        <div class="wl-couple-diamond">
                            <img src="{{ $secondPerson['photo'] }}" alt="{{ $secondPerson['full'] }}" loading="lazy">
                        </div>
                        <img src="{{ $slots['walasuji_diamond_frame'] }}" alt="" class="wl-walasuji-frame-overlay">
                    </div>

                    <p class="wl-eyebrow-light" style="font-size: 10px; margin: 6px 0 4px;">{{ $secondPerson['role'] }}</p>
                    <h3 class="wl-font-heading" style="font-size: 34px; font-weight: 700; color: #FFFFFF; margin: 0 0 2px;">{{ $secondPerson['nick'] }}</h3>
                    <p style="font-size: 15px; font-weight: 700; color: var(--wl-gold-light); margin: 0 0 6px;">{{ $secondPerson['full'] }}</p>
                    <p style="font-size: 12px; color: rgba(255,255,255,0.82); line-height: 1.6; margin: 0 auto 12px; max-width: 300px;">
                        {{ $secondPerson['childLabel'] }}<br>{{ $secondPerson['parents'] }}
                    </p>
                    @if($secondPerson['ig'] !== '')
                        <a href="https://www.instagram.com/{{ $secondPerson['ig'] }}" target="_blank" rel="noopener" class="wl-btn wl-btn-soft" style="background: rgba(255,255,255,0.12); color: var(--wl-gold-light); border: 1px solid rgba(212,175,55,0.45); padding: 7px 16px; font-size: 11px;">
                            <span>@<span>{{ $secondPerson['ig'] }}</span></span>
                        </a>
                    @endif
                </div>
            </div>
        </section>

        <!-- Light Ivory Woven Canvas Body (Events -> Footer) -->
        <div class="wl-ivory-canvas" style="background-image: url('{{ $slots['bg_lippa_sabbe'] }}');">

            <!-- 4. Rangkaian Acara (Events & Countdown) -->
            <section id="acara" class="wl-section wl-section-spy wl-reveal">
                <div class="wl-section-header">
                    <p class="wl-eyebrow-red">WAKTU &amp; TEMPAT</p>
                    <h2 class="wl-heading-dark">Rangkaian Acara</h2>
                    <img src="{{ $slots['divider_ribbon'] }}" alt="" class="wl-divider-ribbon">
                </div>

                @if($showAkad)
                    <div class="wl-card-outer">
                        <div class="wl-card-shell">
                            <div class="wl-card-inner" style="background-image: url('{{ $slots['card_songket_grid'] }}');">
                                <img src="{{ $slots['card_corner_drape'] }}" alt="" class="wl-card-drape-tl">
                                <img src="{{ $slots['card_corner_drape'] }}" alt="" class="wl-card-drape-br">
                                <img src="{{ $slots['card_diamond_jewel'] }}" alt="" class="wl-card-jewel">

                                <div class="wl-card-body" style="text-align: center;">
                                    <h3 class="wl-font-heading" style="font-size: 26px; font-weight: 700; color: var(--wl-crimson-deep); margin: 0 0 10px;">Akad Nikah</h3>
                                    <p style="font-size: 11px; font-weight: 700; letter-spacing: 0.22em; color: var(--wl-muted); margin: 0;">{{ $akadMeta['day'] }}</p>
                                    <div class="wl-font-heading" style="font-size: 56px; font-weight: 700; line-height: 1; color: var(--wl-crimson-warm); margin: 2px 0;">{{ $akadMeta['dayNum'] }}</div>
                                    <p style="font-size: 13px; font-weight: 600; color: var(--wl-ink); margin: 0 0 12px;">{{ $akadMeta['monthYear'] }}</p>

                                    <div style="display: inline-block; padding: 6px 16px; border-radius: 999px; background-color: var(--wl-crimson-soft); color: var(--wl-crimson-deep); font-size: 12px; font-weight: 600; margin-bottom: 12px;">
                                        Pukul {{ $akadMeta['time'] }}
                                    </div>

                                    @if($akadMeta['venue'] !== '' || $akadMeta['address'] !== '')
                                        <p style="font-size: 13px; font-weight: 700; color: var(--wl-ink); margin: 0 0 3px;">{{ $akadMeta['venue'] }}</p>
                                        <p style="font-size: 12px; color: var(--wl-muted); line-height: 1.55; margin: 0 0 16px;">{{ $akadMeta['address'] }}</p>
                                    @endif

                                    <div style="display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 8px;">
                                        @if($akadMeta['mapsUrl'] !== '')
                                            <a href="{{ $akadMeta['mapsUrl'] }}" target="_blank" rel="noopener" class="wl-btn wl-btn-primary" style="padding: 9px 10px; font-size: 11px;">Lihat Lokasi</a>
                                        @endif
                                        <a href="{{ $akadMeta['calendarUrl'] }}" target="_blank" rel="noopener" class="wl-btn wl-btn-soft" style="padding: 9px 10px; font-size: 11px; {{ $akadMeta['mapsUrl'] === '' ? 'grid-column: span 2;' : '' }}">Simpan Kalender</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if($showResepsi)
                    <div class="wl-card-outer">
                        <div class="wl-card-shell">
                            <div class="wl-card-inner" style="background-image: url('{{ $slots['card_songket_grid'] }}');">
                                <img src="{{ $slots['card_corner_drape'] }}" alt="" class="wl-card-drape-tl">
                                <img src="{{ $slots['card_corner_drape'] }}" alt="" class="wl-card-drape-br">
                                <img src="{{ $slots['card_diamond_jewel'] }}" alt="" class="wl-card-jewel">

                                <div class="wl-card-body" style="text-align: center;">
                                    <h3 class="wl-font-heading" style="font-size: 26px; font-weight: 700; color: var(--wl-crimson-deep); margin: 0 0 10px;">Resepsi</h3>
                                    <p style="font-size: 11px; font-weight: 700; letter-spacing: 0.22em; color: var(--wl-muted); margin: 0;">{{ $resepsiMeta['day'] }}</p>
                                    <div class="wl-font-heading" style="font-size: 56px; font-weight: 700; line-height: 1; color: var(--wl-crimson-warm); margin: 2px 0;">{{ $resepsiMeta['dayNum'] }}</div>
                                    <p style="font-size: 13px; font-weight: 600; color: var(--wl-ink); margin: 0 0 12px;">{{ $resepsiMeta['monthYear'] }}</p>

                                    <div style="display: inline-block; padding: 6px 16px; border-radius: 999px; background-color: var(--wl-crimson-soft); color: var(--wl-crimson-deep); font-size: 12px; font-weight: 600; margin-bottom: 12px;">
                                        Pukul {{ $resepsiMeta['time'] }}
                                    </div>

                                    @if($resepsiMeta['venue'] !== '' || $resepsiMeta['address'] !== '')
                                        <p style="font-size: 13px; font-weight: 700; color: var(--wl-ink); margin: 0 0 3px;">{{ $resepsiMeta['venue'] }}</p>
                                        <p style="font-size: 12px; color: var(--wl-muted); line-height: 1.55; margin: 0 0 16px;">{{ $resepsiMeta['address'] }}</p>
                                    @endif

                                    <div style="display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 8px;">
                                        @if($resepsiMeta['mapsUrl'] !== '')
                                            <a href="{{ $resepsiMeta['mapsUrl'] }}" target="_blank" rel="noopener" class="wl-btn wl-btn-primary" style="padding: 9px 10px; font-size: 11px;">Lihat Lokasi</a>
                                        @endif
                                        <a href="{{ $resepsiMeta['calendarUrl'] }}" target="_blank" rel="noopener" class="wl-btn wl-btn-soft" style="padding: 9px 10px; font-size: 11px; {{ $resepsiMeta['mapsUrl'] === '' ? 'grid-column: span 2;' : '' }}">Simpan Kalender</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if($showUnduhMantu && $unduhMeta)
                    <div class="wl-card-outer">
                        <div class="wl-card-shell">
                            <div class="wl-card-inner" style="background-image: url('{{ $slots['card_songket_grid'] }}');">
                                <img src="{{ $slots['card_corner_drape'] }}" alt="" class="wl-card-drape-tl">
                                <img src="{{ $slots['card_corner_drape'] }}" alt="" class="wl-card-drape-br">
                                <img src="{{ $slots['card_diamond_jewel'] }}" alt="" class="wl-card-jewel">

                                <div class="wl-card-body" style="text-align: center;">
                                    <h3 class="wl-font-heading" style="font-size: 24px; font-weight: 700; color: var(--wl-crimson-deep); margin: 0 0 8px;">Ngunduh Mantu</h3>
                                    <p style="font-size: 11px; font-weight: 700; letter-spacing: 0.22em; color: var(--wl-muted); margin: 0;">{{ $unduhMeta['day'] }} • {{ $unduhMeta['dayNum'] }} {{ $unduhMeta['monthYear'] }}</p>
                                    <div style="display: inline-block; padding: 5px 14px; border-radius: 999px; background-color: var(--wl-crimson-soft); color: var(--wl-crimson-deep); font-size: 12px; font-weight: 600; margin: 10px 0;">
                                        Pukul {{ $unduhMeta['time'] }}
                                    </div>
                                    <p style="font-size: 13px; font-weight: 700; color: var(--wl-ink); margin: 0 0 12px;">{{ $unduhMeta['venue'] }}</p>
                                    @if($unduhMeta['mapsUrl'] !== '')
                                        <a href="{{ $unduhMeta['mapsUrl'] }}" target="_blank" rel="noopener" class="wl-btn wl-btn-primary">Lihat Lokasi</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if($showStreaming)
                    <div class="wl-card-outer">
                        <div class="wl-card-shell">
                            <div class="wl-card-inner" style="background-image: url('{{ $slots['card_songket_grid'] }}'); text-align: center;">
                                <div class="wl-card-body">
                                    <p class="wl-eyebrow-red" style="margin-bottom: 4px;">LIVE STREAMING</p>
                                    <p style="font-size: 13px; color: var(--wl-muted); margin: 0 0 12px;">Saksikan momen sakral kami secara virtual dari mana saja.</p>
                                    <a href="{{ $streamingLink }}" target="_blank" rel="noopener" class="wl-btn wl-btn-warm">Tonton Live</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if($showCountdown)
                    <div id="countdown" style="text-align: center; padding: 14px 0 8px;">
                        <p class="wl-eyebrow-red" style="margin-bottom: 14px;">HITUNG MUNDUR MENUJU HARI BAHAGIA</p>
                        <div class="wl-diamond-grid">
                            <div class="wl-diamond-tile is-deep" style="width: 56px; height: 56px;">
                                <div class="wl-diamond-inner"><span class="wl-diamond-val" style="font-size: 16px;" x-text="days">00</span><span class="wl-diamond-lbl">Hari</span></div>
                            </div>
                            <div class="wl-diamond-tile is-warm" style="width: 56px; height: 56px;">
                                <div class="wl-diamond-inner"><span class="wl-diamond-val" style="font-size: 16px;" x-text="hours">00</span><span class="wl-diamond-lbl">Jam</span></div>
                            </div>
                            <div class="wl-diamond-tile is-deep" style="width: 56px; height: 56px;">
                                <div class="wl-diamond-inner"><span class="wl-diamond-val" style="font-size: 16px;" x-text="minutes">00</span><span class="wl-diamond-lbl">Menit</span></div>
                            </div>
                            <div class="wl-diamond-tile is-warm" style="width: 56px; height: 56px;">
                                <div class="wl-diamond-inner"><span class="wl-diamond-val" style="font-size: 16px;" x-text="seconds">00</span><span class="wl-diamond-lbl">Detik</span></div>
                            </div>
                        </div>
                    </div>
                @endif

                @if($eventNote !== '' || $turutMengundang !== '')
                    <div class="wl-card-outer" style="margin-top: 12px;">
                        <div class="wl-card-shell">
                            <div class="wl-card-inner" style="background-image: url('{{ $slots['card_songket_grid'] }}'); text-align: center; padding: 22px 18px;">
                                <div class="wl-card-body">
                                    @if($eventNote !== '')
                                        <p class="wl-eyebrow-red" style="margin-bottom: 6px;">CATATAN ACARA</p>
                                        <p style="font-size: 12.5px; color: var(--wl-ink); line-height: 1.6; margin: 0 0 {{ $turutMengundang !== '' ? '12px' : '0' }};">{{ $eventNote }}</p>
                                    @endif
                                    @if($turutMengundang !== '')
                                        <p class="wl-eyebrow-red" style="margin-bottom: 6px;">TURUT MENGUNDANG</p>
                                        <p style="font-size: 12.5px; color: var(--wl-ink); line-height: 1.65; margin: 0;">{!! nl2br(e($turutMengundang)) !!}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </section>

            <!-- 5. Kisah Cinta (Love Story Timeline) -->
            @if($showLoveStory)
                <section id="cerita" class="wl-section wl-section-spy wl-reveal">
                    <div class="wl-section-header">
                        <p class="wl-eyebrow-red">PERJALANAN KAMI</p>
                        <h2 class="wl-heading-dark">Kisah Cinta</h2>
                        <img src="{{ $slots['divider_ribbon'] }}" alt="" class="wl-divider-ribbon">
                    </div>

                    <div class="wl-timeline">
                        @foreach($loveStories as $story)
                            <div class="wl-timeline-item">
                                <img src="{{ $slots['card_diamond_jewel'] }}" alt="" class="wl-timeline-node">
                                <div class="wl-card-outer" style="margin-bottom: 0;">
                                    <div class="wl-card-shell">
                                        <div class="wl-card-inner" style="background-image: url('{{ $slots['card_songket_grid'] }}'); padding: 20px 18px;">
                                            <img src="{{ $slots['card_corner_drape'] }}" alt="" class="wl-card-drape-tl" style="width: 48px; height: 48px;">
                                            <div class="wl-card-body">
                                                @if(!empty($story['image']))
                                                    <div style="margin-bottom: 12px; padding: 3px; border: 1.5px solid var(--wl-gold); background: #FFF; border-radius: 6px;">
                                                        <img src="{{ $story['image'] }}" alt="{{ $story['title'] }}" loading="lazy" style="width: 100%; aspect-ratio: 4/3; object-fit: cover; display: block; border-radius: 4px;">
                                                    </div>
                                                @endif
                                                <span style="display: inline-block; background-color: var(--wl-crimson-deep); color: var(--wl-gold-light); font-size: 10px; font-weight: 700; letter-spacing: 0.14em; padding: 4px 12px; text-transform: uppercase; clip-path: polygon(6px 0, calc(100% - 6px) 0, 100% 50%, calc(100% - 6px) 100%, 6px 100%, 0 50%); margin-bottom: 8px;">
                                                    {{ $story['date'] }}
                                                </span>
                                                <h3 class="wl-font-heading" style="font-size: 22px; font-weight: 700; color: var(--wl-crimson-deep); margin: 0 0 6px;">{{ $story['title'] }}</h3>
                                                <p style="font-size: 12.5px; color: var(--wl-muted); line-height: 1.65; margin: 0;">{{ $story['description'] }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif

            <!-- 6. Galeri (Coverflow Carousel + Lightbox) -->
            @if($showGallery)
                <section id="galeri" class="wl-section wl-section-spy wl-reveal">
                    <div class="wl-section-header">
                        <p class="wl-eyebrow-red">MOMEN BAHAGIA</p>
                        <h2 class="wl-heading-dark">Galeri</h2>
                        <img src="{{ $slots['divider_ribbon'] }}" alt="" class="wl-divider-ribbon">
                    </div>

                    <div class="wl-gallery-stage" @touchstart="onGalleryTouchStart($event)" @touchend="onGalleryTouchEnd($event)">
                        @foreach($galleryPhotos as $idx => $photo)
                            <div class="wl-gal-slide"
                                 :style="getSlideStyle({{ $idx }})"
                                 @click="onSlideClick({{ $idx }})">
                                <div class="wl-gal-slide-inner">
                                    <img src="{{ $photo }}" alt="Foto Galeri {{ $idx + 1 }}" loading="lazy">
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="wl-gal-controls">
                        <button type="button" class="wl-gal-arrow" @click="prevSlide()" aria-label="Foto sebelumnya">
                            <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                        </button>
                        <div style="text-align: center; min-width: 72px;">
                            <span style="font-size: 13px; font-weight: 700; color: var(--wl-crimson-deep);" x-text="formattedSlideCounter()">01 / 01</span>
                            <div style="height: 2px; background: linear-gradient(90deg, transparent, var(--wl-gold), transparent); margin-top: 4px;"></div>
                        </div>
                        <button type="button" class="wl-gal-arrow" @click="nextSlide()" aria-label="Foto berikutnya">
                            <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                        </button>
                    </div>
                </section>
            @endif

            <!-- 7. Amplop Digital -->
            @if($showGift)
                <section id="hadiah" class="wl-section wl-section-spy wl-reveal">
                    <div class="wl-section-header">
                        <p class="wl-eyebrow-red">TANDA KASIH</p>
                        <h2 class="wl-heading-dark">Amplop Digital</h2>
                        <img src="{{ $slots['divider_ribbon'] }}" alt="" class="wl-divider-ribbon">
                        <p style="font-size: 12.5px; color: var(--wl-muted); max-width: 320px; margin: 12px auto 0; line-height: 1.65;">
                            Doa restu Anda merupakan karunia terindah bagi kami. Namun jika memberi adalah ungkapan tanda kasih, Anda dapat menyalurkannya melalui rekening berikut:
                        </p>
                    </div>

                    @foreach($bankAccounts as $account)
                        @php
                            $accNum = (string) ($account['account_number'] ?? '');
                            $accName = (string) ($account['account_name'] ?? '');
                            $bankName = strtoupper((string) ($account['bank'] ?? 'BANK'));
                        @endphp
                        <div class="wl-card-outer">
                            <div class="wl-card-shell">
                                <div class="wl-card-inner" style="background-image: url('{{ $slots['card_songket_grid'] }}'); text-align: center;">
                                    <img src="{{ $slots['card_corner_drape'] }}" alt="" class="wl-card-drape-tl">
                                    <img src="{{ $slots['card_corner_drape'] }}" alt="" class="wl-card-drape-br">
                                    <img src="{{ $slots['card_diamond_jewel'] }}" alt="" class="wl-card-jewel">

                                    <div class="wl-card-body">
                                        <p class="wl-eyebrow-red" style="font-size: 10px; margin: 0 0 2px;">REKENING BANK</p>
                                        <h3 class="wl-font-heading" style="font-size: 24px; font-weight: 700; color: var(--wl-crimson-deep); margin: 0 0 12px;">{{ $bankName }}</h3>

                                        @if($accNum !== '')
                                            <div style="border-top: 1px solid rgba(212,175,55,0.4); border-bottom: 1px solid rgba(212,175,55,0.4); padding: 10px 8px; margin: 0 auto 8px; max-width: 260px;">
                                                <span style="font-size: 20px; font-weight: 700; letter-spacing: 0.12em; color: var(--wl-ink); font-variant-numeric: tabular-nums;">{{ $accNum }}</span>
                                            </div>
                                        @endif

                                        @if($accName !== '')
                                            <p style="font-size: 12.5px; color: var(--wl-muted); margin: 0 0 14px;">a.n. <strong style="color: var(--wl-ink);">{{ $accName }}</strong></p>
                                        @endif

                                        @if($accNum !== '')
                                            <button type="button" @click="copyText(@js($accNum), 'Nomor rekening {{ $bankName }}')" class="wl-btn wl-btn-primary">
                                                <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"/></svg>
                                                <span>Salin Nomor</span>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    @if(trim((string) $giftAddress) !== '')
                        <div class="wl-card-outer">
                            <div class="wl-card-shell">
                                <div class="wl-card-inner" style="background-image: url('{{ $slots['card_songket_grid'] }}'); text-align: center; padding: 24px 20px;">
                                    <div class="wl-card-body">
                                        <p class="wl-eyebrow-red" style="margin-bottom: 4px;">KIRIM KADO FISIK</p>
                                        <p style="font-size: 12.5px; color: var(--wl-ink); line-height: 1.6; margin: 0 0 14px;">{{ $giftAddress }}</p>
                                        <button type="button" @click="copyText(@js($giftAddress), 'Alamat pengiriman kado')" class="wl-btn wl-btn-soft">Salin Alamat</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </section>
            @endif

            <!-- 8. Konfirmasi Kehadiran (RSVP & Ucapan Tamu) -->
            @if($showRsvp)
                <section id="rsvp" class="wl-section wl-section-spy wl-reveal" :class="{ 'wl-revealed': isOpened }">
                    <div class="wl-section-header">
                        <p class="wl-eyebrow-red">R.S.V.P.</p>
                        <h2 class="wl-heading-dark">Konfirmasi Kehadiran</h2>
                        <img src="{{ $slots['divider_ribbon'] }}" alt="" class="wl-divider-ribbon">
                    </div>

                    <div class="wl-card-outer">
                        <div class="wl-card-shell">
                            <div class="wl-card-inner" style="background-image: url('{{ $slots['card_songket_grid'] }}');">
                                <img src="{{ $slots['card_corner_drape'] }}" alt="" class="wl-card-drape-tl">
                                <img src="{{ $slots['card_corner_drape'] }}" alt="" class="wl-card-drape-br">
                                <img src="{{ $slots['card_diamond_jewel'] }}" alt="" class="wl-card-jewel">

                                <div class="wl-card-body">
                                    <p style="text-align: center; font-size: 13px; color: var(--wl-muted); margin: 0 0 16px;">
                                        Kepada Yth. <strong style="color: var(--wl-crimson-deep);">{{ $resolvedGuest }}</strong>
                                    </p>

                                    @if (session()->has('rsvp_success'))
                                        <div style="background: #ECFDF5; border: 1px solid #10B981; color: #065F46; padding: 10px 14px; border-radius: 8px; margin-bottom: 14px; font-size: 12.5px; text-align: center;">
                                            {{ session('rsvp_success') }}
                                        </div>
                                    @endif

                                    <form wire:submit.prevent="submitRSVP">
                                        <div style="margin-bottom: 12px;">
                                            <label class="wl-label" for="wl-rsvp-name">Nama</label>
                                            <input id="wl-rsvp-name" type="text" wire:model="rsvpName" class="wl-input" placeholder="Tulis nama Anda" required>
                                            @error('rsvpName') <span style="display: block; color: #B91C1C; font-size: 11px; margin-top: 3px;">{{ $message }}</span> @enderror
                                        </div>

                                        <div style="margin-bottom: 12px;">
                                            <label class="wl-label" for="wl-rsvp-status">Konfirmasi Kehadiran</label>
                                            <select id="wl-rsvp-status" wire:model.live="rsvpStatus" class="wl-select" required>
                                                <option value="confirmed">Hadir</option>
                                                <option value="declined">Tidak Hadir</option>
                                                <option value="tentative">Masih Ragu</option>
                                            </select>
                                            @error('rsvpStatus') <span style="display: block; color: #B91C1C; font-size: 11px; margin-top: 3px;">{{ $message }}</span> @enderror
                                        </div>

                                        @if($rsvpStatus === 'confirmed')
                                            <div style="margin-bottom: 12px;">
                                                <label class="wl-label" for="wl-rsvp-guests">Jumlah Tamu</label>
                                                <input id="wl-rsvp-guests" type="number" min="1" max="20" wire:model="rsvpGuests" class="wl-input">
                                                @error('rsvpGuests') <span style="display: block; color: #B91C1C; font-size: 11px; margin-top: 3px;">{{ $message }}</span> @enderror
                                            </div>
                                        @endif

                                        <div style="margin-bottom: 16px;">
                                            <label class="wl-label" for="wl-rsvp-message">Ucapan &amp; Doa</label>
                                            <textarea id="wl-rsvp-message" rows="3" wire:model="rsvpMessage" class="wl-textarea" placeholder="Tulis doa restu dan ucapan terbaik Anda..." required></textarea>
                                            @error('rsvpMessage') <span style="display: block; color: #B91C1C; font-size: 11px; margin-top: 3px;">{{ $message }}</span> @enderror
                                        </div>

                                        <button type="submit" class="wl-btn wl-btn-warm" style="width: 100%;">
                                            <span wire:loading.remove wire:target="submitRSVP">Kirim RSVP &amp; Ucapan</span>
                                            <span wire:loading wire:target="submitRSVP">Mengirim...</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 9. Ucapan Tamu Feed -->
                    <div style="margin-top: 30px;">
                        <div class="wl-section-header" style="margin-bottom: 18px;">
                            <p class="wl-eyebrow-red">DOA &amp; UCAPAN ({{ $wishesCount }})</p>
                            <h3 class="wl-heading-dark" style="font-size: 28px;">Ucapan Tamu</h3>
                            <img src="{{ $slots['divider_ribbon'] }}" alt="" class="wl-divider-ribbon">
                        </div>

                        @forelse($wishesList as $wish)
                            @php
                                $cleanName = trim((string) ($wish->name ?? 'Tamu'));
                                $parts = preg_split('/\s+/', $cleanName) ?: [];
                                $initials = count($parts) >= 2
                                    ? strtoupper(mb_substr($parts[0], 0, 1) . mb_substr($parts[1], 0, 1))
                                    : strtoupper(mb_substr($cleanName, 0, 2));
                            @endphp
                            <div class="wl-card-outer" style="margin-bottom: 12px;" wire:key="wl-wish-{{ $wish->id }}">
                                <div class="wl-card-shell">
                                    <div class="wl-card-inner" style="background-image: url('{{ $slots['card_songket_grid'] }}'); padding: 16px 16px;">
                                        <div class="wl-card-body" style="display: flex; align-items: flex-start; gap: 14px;">
                                            <div style="width: 38px; height: 38px; flex-shrink: 0; margin-top: 4px; background: linear-gradient(135deg, var(--wl-crimson-deep), var(--wl-crimson-warm)); border: 1.5px solid var(--wl-gold); transform: rotate(45deg); display: flex; align-items: center; justify-content: center;">
                                                <span style="transform: rotate(-45deg); color: var(--wl-gold-light); font-size: 11px; font-weight: 700;">{{ $initials }}</span>
                                            </div>
                                            <div style="flex: 1; min-width: 0;">
                                                <div style="display: flex; align-items: center; justify-content: space-between; gap: 6px; flex-wrap: wrap; margin-bottom: 4px;">
                                                    <strong style="font-size: 13.5px; color: var(--wl-crimson-deep);">{{ $wish->name }}</strong>
                                                    @if($wish->attendance_status === 'confirmed' || $wish->attendance_status === 'present')
                                                        <span style="font-size: 10px; font-weight: 700; padding: 2px 8px; border-radius: 999px; background: #ECFDF5; color: #065F46; border: 1px solid #A7F3D0;">Hadir ({{ $wish->pax ?? 1 }})</span>
                                                    @elseif($wish->attendance_status === 'declined' || $wish->attendance_status === 'notpresent')
                                                        <span style="font-size: 10px; font-weight: 700; padding: 2px 8px; border-radius: 999px; background: #FEF2F2; color: #991B1B; border: 1px solid #FECACA;">Tidak Hadir</span>
                                                    @else
                                                        <span style="font-size: 10px; font-weight: 700; padding: 2px 8px; border-radius: 999px; background: #FFFBEB; color: #92400E; border: 1px solid #FDE68A;">Masih Ragu</span>
                                                    @endif
                                                </div>
                                                <p style="font-size: 12.5px; color: var(--wl-ink); line-height: 1.55; margin: 0 0 4px; word-break: break-word;">{{ $wish->message }}</p>
                                                <p style="font-size: 10px; color: var(--wl-muted); margin: 0;">
                                                    {{ $wish->created_at ? $wish->created_at->diffForHumans() : 'Baru saja' }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="wl-card-outer">
                                <div class="wl-card-shell">
                                    <div class="wl-card-inner" style="text-align: center; padding: 24px 18px;">
                                        <p style="font-size: 13px; color: var(--wl-muted); margin: 0;">Belum ada ucapan. Jadilah yang pertama memberikan doa restu!</p>
                                    </div>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </section>
            @endif

            <!-- 10. Closing & Footer -->
            <footer class="wl-section wl-reveal" style="text-align: center; padding-top: 26px; padding-bottom: 120px;">
                <img src="{{ $slots['bosara_emblem'] }}" alt="Bosara" class="wl-float-slow" style="width: 82px; height: 82px; margin: 0 auto 14px; display: block;">
                <p style="font-size: 12.5px; line-height: 1.7; color: var(--wl-muted); max-width: 330px; margin: 0 auto 14px;">
                    Merupakan suatu kehormatan dan kebahagiaan bagi kami apabila Bapak/Ibu/Saudara/i berkenan hadir dan memberikan doa restu.
                </p>
                <p class="wl-font-heading" style="font-style: italic; font-size: 19px; font-weight: 600; color: var(--wl-crimson-warm); margin: 0 0 6px;">
                    Terima kasih &middot; Tarima kasi
                </p>
                <h2 class="wl-font-heading" style="font-size: 32px; font-weight: 700; color: var(--wl-crimson-deep); margin: 0 0 26px;">
                    {{ $coupleTitle }}
                </h2>

                <div style="border-top: 1px solid rgba(212, 175, 55, 0.35); padding-top: 18px;">
                    <a href="{{ url('/') }}" style="display: inline-flex; flex-direction: column; align-items: center; text-decoration: none; gap: 6px;">
                        <span style="font-size: 9px; letter-spacing: 2.2px; text-transform: uppercase; color: var(--wl-crimson-warm); opacity: 0.72;">Digital Invitation By</span>
                        <img src="{{ asset('assets/svg/logo-dark.svg') }}" alt="ExoInvite" loading="lazy" style="width: 138px; height: auto; opacity: 0.92;">
                    </a>
                </div>
            </footer>
        </div>
    </main>

    <!-- Floating Quick Action Buttons (Audio + Auto-Scroll) -->
    <div class="wl-fab-stack" x-show="isOpened" x-transition.opacity style="display: none;">
        @if($musicSrc !== '')
            <button type="button" class="wl-fab-btn" @click="toggleMusic()" :aria-label="isPlaying ? 'Jeda Musik' : 'Putar Musik'" :title="isPlaying ? 'Jeda Musik' : 'Putar Musik'">
                <svg x-show="isPlaying" style="width: 17px; height: 17px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 19V6l12-3v13M9 19c0 1.1-.9 2-2 2s-2-.9-2-2 .9-2 2-2 2 .9 2 2zm12-3c0 1.1-.9 2-2 2s-2-.9-2-2 .9-2 2-2 2 .9 2 2z"/>
                </svg>
                <svg x-show="!isPlaying" style="width: 17px; height: 17px; opacity: 0.65;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 19V6l12-3v13M3 3l18 18"/>
                </svg>
            </button>
        @endif

        <button type="button" class="wl-fab-btn" @click="toggleAutoScroll()" :aria-label="autoScrolling ? 'Hentikan Auto Scroll' : 'Mulai Auto Scroll'" :title="autoScrolling ? 'Hentikan Auto Scroll' : 'Auto Scroll'">
            <svg x-show="!autoScrolling" style="width: 16px; height: 16px;" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14m-6-6l6 6 6-6"/>
            </svg>
            <svg x-show="autoScrolling" style="width: 16px; height: 16px;" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 5v14M14 5v14"/>
            </svg>
        </button>
    </div>

    <!-- Chamfered Floating Bottom Navigation Dock -->
    <nav class="wl-dock-wrap" x-show="isOpened" x-transition.opacity aria-label="Navigasi Seksi" style="display: none;">
        <div class="wl-dock">
            <a href="#mempelai" @click.prevent="scrollTo('#mempelai')" class="wl-dock-item" :class="{ 'is-active': activeSection === 'mempelai' }" title="Mempelai" aria-label="Mempelai">
                <svg fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
            </a>
            <a href="#acara" @click.prevent="scrollTo('#acara')" class="wl-dock-item" :class="{ 'is-active': activeSection === 'acara' }" title="Acara" aria-label="Acara">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
            </a>
            @if($showLoveStory)
                <a href="#cerita" @click.prevent="scrollTo('#cerita')" class="wl-dock-item" :class="{ 'is-active': activeSection === 'cerita' }" title="Kisah Cinta" aria-label="Kisah Cinta">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                </a>
            @endif
            @if($showGallery)
                <a href="#galeri" @click.prevent="scrollTo('#galeri')" class="wl-dock-item" :class="{ 'is-active': activeSection === 'galeri' }" title="Galeri" aria-label="Galeri">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="14" rx="2"/><circle cx="8.5" cy="10.5" r="1.5"/><path d="M21 15l-5-5L5 19"/></svg>
                </a>
            @endif
            @if($showGift)
                <a href="#hadiah" @click.prevent="scrollTo('#hadiah')" class="wl-dock-item" :class="{ 'is-active': activeSection === 'hadiah' }" title="Amplop Digital" aria-label="Amplop Digital">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="20 12 20 22 4 22 4 12"/><rect x="2" y="7" width="20" height="5"/><line x1="12" y1="22" x2="12" y2="7"/><path d="M12 7H7.5a2.5 2.5 0 010-5C11 2 12 7 12 7zM12 7h4.5a2.5 2.5 0 000-5C13 2 12 7 12 7z"/></svg>
                </a>
            @endif
            @if($showRsvp)
                <a href="#rsvp" @click.prevent="scrollTo('#rsvp')" class="wl-dock-item" :class="{ 'is-active': activeSection === 'rsvp' }" title="RSVP &amp; Ucapan" aria-label="RSVP dan Ucapan">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 11.5a8.38 8.38 0 01-.9 3.8 8.5 8.5 0 01-7.6 4.7 8.38 8.38 0 01-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 01-.9-3.8 8.5 8.5 0 014.7-7.6 8.38 8.38 0 013.8-.9h.5a8.48 8.48 0 018 8v.5z"/></svg>
                </a>
            @endif
        </div>
    </nav>

    <!-- Fullscreen Interactive Gallery Lightbox -->
    @if($showGallery)
        <div x-show="lightboxOpen"
             x-transition.opacity
             @keydown.window.escape="closeLightbox()"
             @keydown.window.arrow-left="if (lightboxOpen) lightboxPrev()"
             @keydown.window.arrow-right="if (lightboxOpen) lightboxNext()"
             @click.self="closeLightbox()"
             style="display: none; position: fixed; inset: 0; z-index: 950; background: rgba(20, 2, 5, 0.92); backdrop-filter: blur(6px); align-items: center; justify-content: center; padding: 24px;"
             :style="{ display: lightboxOpen ? 'flex' : 'none' }">
            <button type="button" @click="closeLightbox()" aria-label="Tutup" style="position: absolute; top: 18px; right: 18px; width: 40px; height: 40px; border-radius: 50%; background: rgba(255,255,255,0.14); border: 1px solid rgba(212,175,55,0.5); color: #FFF; font-size: 20px; cursor: pointer;">&times;</button>
            <button type="button" @click="lightboxPrev()" aria-label="Sebelumnya" style="position: absolute; left: 14px; width: 40px; height: 40px; border-radius: 50%; background: rgba(91,11,20,0.75); border: 1px solid var(--wl-gold); color: var(--wl-gold-light); cursor: pointer;">&#8249;</button>
            <img :src="galleryPhotos[lightboxIndex] || ''" alt="Preview Galeri" style="max-width: 90vw; max-height: 84vh; border-radius: 10px; border: 2px solid var(--wl-gold); object-fit: contain; box-shadow: 0 20px 50px rgba(0,0,0,0.75);">
            <button type="button" @click="lightboxNext()" aria-label="Berikutnya" style="position: absolute; right: 14px; width: 40px; height: 40px; border-radius: 50%; background: rgba(91,11,20,0.75); border: 1px solid var(--wl-gold); color: var(--wl-gold-light); cursor: pointer;">&#8250;</button>
            <div style="display: none;" id="wl-gallery-data" data-photos="{{ json_encode($galleryPhotos, JSON_UNESCAPED_SLASHES) }}"></div>
        </div>
    @else
        <div style="display: none;" id="wl-gallery-data" data-photos="[]"></div>
    @endif

    <!-- Chamfered Toast Notification -->
    <div x-show="toastShow"
         x-transition.opacity
         style="display: none; position: fixed; top: 20px; right: 18px; z-index: 960; background: #3A060C; color: var(--wl-gold-light); border-left: 3px solid var(--wl-gold); padding: 10px 16px; font-size: 12.5px; font-weight: 600; box-shadow: 0 10px 28px rgba(0,0,0,0.45); clip-path: polygon(8px 0, calc(100% - 8px) 0, 100% 8px, 100% calc(100% - 8px), calc(100% - 8px) 100%, 8px 100%, 0 calc(100% - 8px), 0 8px);">
        <span x-text="toastMessage">Tersalin!</span>
    </div>
</div>

@once
    @push('alpine-components')
        <script>
            window.walasujiTheme = function (config) {
                return {
                    isOpened: false,
                    targetTimestamp: Number(config.targetTimestamp || 0),
                    days: '00',
                    hours: '00',
                    minutes: '00',
                    seconds: '00',
                    countdownTimer: null,

                    isPlaying: false,
                    userPausedMusic: false,
                    pendingMusicUnlock: false,

                    autoScrolling: false,
                    autoScrollFrame: null,
                    wakeLockSentinel: null,

                    activeSection: 'mempelai',

                    galleryPhotos: [],
                    galleryIndex: 0,
                    galleryCount: Number(config.galleryCount || 0),
                    galleryAutoTimer: null,
                    galleryTouchStartX: 0,
                    lightboxOpen: false,
                    lightboxIndex: 0,

                    toastShow: false,
                    toastMessage: '',
                    toastTimer: null,

                    init() {
                        document.body.classList.add('wl-locked');

                        const galEl = document.getElementById('wl-gallery-data');
                        if (galEl && galEl.dataset.photos) {
                            try {
                                this.galleryPhotos = JSON.parse(galEl.dataset.photos);
                                this.galleryCount = this.galleryPhotos.length;
                            } catch (e) {}
                        }

                        this.updateCountdown();
                        this.countdownTimer = setInterval(() => this.updateCountdown(), 1000);
                        this.initScrollListeners();
                        this.startGalleryAuto();
                    },

                    openInvitation() {
                        if (this.isOpened) return;
                        this.isOpened = true;
                        document.body.classList.remove('wl-locked');

                        if (config.hasMusic) {
                            this.playMusic();
                        }

                        setTimeout(() => {
                            this.initObservers();
                        }, 180);
                    },

                    updateCountdown() {
                        const diff = (this.targetTimestamp * 1000) - Date.now();
                        if (diff <= 0) {
                            this.days = '00';
                            this.hours = '00';
                            this.minutes = '00';
                            this.seconds = '00';
                            return;
                        }
                        const d = Math.floor(diff / 86400000);
                        const h = Math.floor((diff % 86400000) / 3600000);
                        const m = Math.floor((diff % 3600000) / 60000);
                        const s = Math.floor((diff % 60000) / 1000);
                        this.days = String(d).padStart(2, '0');
                        this.hours = String(h).padStart(2, '0');
                        this.minutes = String(m).padStart(2, '0');
                        this.seconds = String(s).padStart(2, '0');
                    },

                    initObservers() {
                        if (!('IntersectionObserver' in window)) {
                            document.querySelectorAll('.wl-reveal').forEach((el) => el.classList.add('wl-revealed'));
                            return;
                        }

                        const revealObserver = new IntersectionObserver((entries) => {
                            entries.forEach((entry) => {
                                if (entry.isIntersecting) {
                                    entry.target.classList.add('wl-revealed');
                                    revealObserver.unobserve(entry.target);
                                }
                            });
                        }, { threshold: 0.12 });
                        document.querySelectorAll('.wl-reveal').forEach((el) => revealObserver.observe(el));

                        const spyObserver = new IntersectionObserver((entries) => {
                            entries.forEach((entry) => {
                                if (entry.isIntersecting && entry.target.id) {
                                    this.activeSection = entry.target.id;
                                }
                            });
                        }, { rootMargin: '-35% 0px -45% 0px', threshold: 0.01 });
                        document.querySelectorAll('.wl-section-spy').forEach((el) => spyObserver.observe(el));
                    },

                    initScrollListeners() {
                        const cancelAuto = (e) => {
                            if (!this.autoScrolling) return;
                            if (e.target && e.target.closest && (e.target.closest('.wl-fab-stack') || e.target.closest('.wl-dock-wrap'))) {
                                return;
                            }
                            this.autoScrolling = false;
                            this.stopScroll();
                        };
                        window.addEventListener('wheel', cancelAuto, { passive: true });
                        window.addEventListener('touchstart', cancelAuto, { passive: true });
                        window.addEventListener('keydown', cancelAuto, { passive: true });
                        document.addEventListener('visibilitychange', () => {
                            if (document.visibilityState === 'visible' && this.autoScrolling) {
                                this.requestWakeLock();
                            }
                        });
                        window.addEventListener('pagehide', () => this.releaseWakeLock());
                    },

                    scrollTo(selector) {
                        const el = document.querySelector(selector);
                        if (el) {
                            el.scrollIntoView({ behavior: 'smooth', block: 'start' });
                        }
                    },

                    playMusic() {
                        const audio = document.getElementById('wl-song');
                        if (!audio) return;
                        this.userPausedMusic = false;
                        if (audio.preload !== 'auto') audio.preload = 'auto';
                        if (audio.readyState === 0 && !audio.currentSrc && audio.src) audio.load();

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
                            const audio = document.getElementById('wl-song');
                            if (!audio) {
                                this.pendingMusicUnlock = false;
                                cleanup();
                                return;
                            }
                            if (audio.readyState === 0 && !audio.currentSrc && audio.src) audio.load();
                            try {
                                const p = audio.play();
                                if (p && typeof p.then === 'function') {
                                    p.then(() => {
                                        this.isPlaying = true;
                                        this.pendingMusicUnlock = false;
                                        cleanup();
                                    }).catch(() => {});
                                }
                            } catch (e) {}
                        };
                        window.addEventListener('touchend', tryUnlock, { capture: true, passive: true });
                        window.addEventListener('click', tryUnlock, { capture: true, passive: true });
                    },

                    pauseMusic() {
                        this.userPausedMusic = true;
                        this.pendingMusicUnlock = false;
                        const audio = document.getElementById('wl-song');
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
                                sentinel.release().catch(() => {});
                                return;
                            }
                            this.wakeLockSentinel = sentinel;
                            sentinel.addEventListener('release', () => {
                                if (this.wakeLockSentinel === sentinel) this.wakeLockSentinel = null;
                            });
                        } catch (e) {}
                    },

                    releaseWakeLock() {
                        const s = this.wakeLockSentinel;
                        this.wakeLockSentinel = null;
                        if (s && !s.released) s.release().catch(() => {});
                    },

                    startScroll() {
                        if (this.autoScrollFrame) cancelAnimationFrame(this.autoScrollFrame);
                        this.requestWakeLock();
                        const step = () => {
                            if (!this.autoScrolling) return;
                            window.scrollBy({ top: 1.25, behavior: 'instant' });
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

                    startGalleryAuto() {
                        if (this.galleryCount <= 1) return;
                        clearInterval(this.galleryAutoTimer);
                        this.galleryAutoTimer = setInterval(() => {
                            if (this.isOpened && !this.lightboxOpen) {
                                this.galleryIndex = (this.galleryIndex + 1) % this.galleryCount;
                            }
                        }, 5000);
                    },

                    nextSlide() {
                        if (this.galleryCount <= 0) return;
                        this.galleryIndex = (this.galleryIndex + 1) % this.galleryCount;
                        this.startGalleryAuto();
                    },

                    prevSlide() {
                        if (this.galleryCount <= 0) return;
                        this.galleryIndex = (this.galleryIndex - 1 + this.galleryCount) % this.galleryCount;
                        this.startGalleryAuto();
                    },

                    onGalleryTouchStart(e) {
                        if (e.changedTouches && e.changedTouches[0]) {
                            this.galleryTouchStartX = e.changedTouches[0].clientX;
                        }
                    },

                    onGalleryTouchEnd(e) {
                        if (!e.changedTouches || !e.changedTouches[0]) return;
                        const dx = e.changedTouches[0].clientX - this.galleryTouchStartX;
                        if (Math.abs(dx) > 36) {
                            if (dx < 0) this.nextSlide();
                            else this.prevSlide();
                        }
                    },

                    getSlideStyle(idx) {
                        const n = this.galleryCount;
                        if (n <= 1) {
                            return 'transform: translateX(0) scale(1); opacity: 1; z-index: 3;';
                        }
                        let diff = idx - this.galleryIndex;
                        if (diff > n / 2) diff -= n;
                        if (diff < -n / 2) diff += n;

                        if (diff === 0) {
                            return 'transform: translateX(0) scale(1); opacity: 1; z-index: 3;';
                        }
                        if (diff === -1) {
                            return 'transform: translateX(-62%) scale(0.82); opacity: 0.65; z-index: 2;';
                        }
                        if (diff === 1) {
                            return 'transform: translateX(62%) scale(0.82); opacity: 0.65; z-index: 2;';
                        }
                        return 'transform: translateX(' + (diff < 0 ? '-110%' : '110%') + ') scale(0.7); opacity: 0; z-index: 1; pointer-events: none;';
                    },

                    onSlideClick(idx) {
                        if (idx === this.galleryIndex) {
                            this.lightboxIndex = idx;
                            this.lightboxOpen = true;
                        } else {
                            this.galleryIndex = idx;
                            this.startGalleryAuto();
                        }
                    },

                    formattedSlideCounter() {
                        const cur = String(this.galleryIndex + 1).padStart(2, '0');
                        const tot = String(Math.max(this.galleryCount, 1)).padStart(2, '0');
                        return cur + ' / ' + tot;
                    },

                    closeLightbox() {
                        this.lightboxOpen = false;
                    },

                    lightboxNext() {
                        if (this.galleryCount <= 0) return;
                        this.lightboxIndex = (this.lightboxIndex + 1) % this.galleryCount;
                    },

                    lightboxPrev() {
                        if (this.galleryCount <= 0) return;
                        this.lightboxIndex = (this.lightboxIndex - 1 + this.galleryCount) % this.galleryCount;
                    },

                    copyText(text, label) {
                        if (!text) return;
                        const msg = (label ? label + ' disalin: ' : 'Berhasil disalin: ') + text;
                        if (navigator.clipboard && window.isSecureContext) {
                            navigator.clipboard.writeText(text).then(() => {
                                this.showToast(msg);
                            }).catch(() => this.fallbackCopy(text, msg));
                        } else {
                            this.fallbackCopy(text, msg);
                        }
                    },

                    fallbackCopy(text, msg) {
                        try {
                            const ta = document.createElement('textarea');
                            ta.value = text;
                            ta.setAttribute('readonly', '');
                            ta.style.position = 'fixed';
                            ta.style.left = '-9999px';
                            document.body.appendChild(ta);
                            ta.focus();
                            ta.select();
                            ta.setSelectionRange(0, ta.value.length);
                            document.execCommand('copy');
                            ta.remove();
                            this.showToast(msg);
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
                        }, 2600);
                    }
                };
            };
        </script>
    @endpush
@endonce
