@php
    $assetBase = 'themes/crimson-botanical/assets/';

    $slots = [
        'parchment_bg'      => asset($assetBase . 'parchment-bg.jpg'),
        'floral_tl'         => asset($assetBase . 'floral-tl.png'),
        'floral_tr'         => asset($assetBase . 'floral-tr.png'),
        'floral_br'         => asset($assetBase . 'floral-br.png'),
        'atm_chip'          => asset($assetBase . 'atm-chip.png'),
        'bank_bca'          => asset($assetBase . 'bank-bca.png'),
        'bank_bri'          => asset($assetBase . 'bank-bri.png'),
        'default_cover'     => asset($assetBase . 'default-cover.svg'),
        'default_groom'     => asset($assetBase . 'default-groom.svg'),
        'default_bride'     => asset($assetBase . 'default-bride.svg'),
        'default_gallery_1' => asset($assetBase . 'default-gallery-1.svg'),
        'default_gallery_2' => asset($assetBase . 'default-gallery-2.svg'),
        'default_gallery_3' => asset($assetBase . 'default-gallery-3.svg'),
    ];

    $styles = $invitation->custom_styles ?? [];
    $colors = $invitation->custom_colors ?? [];
    $eventType = $invitation->event_type ?? ($styles['event_type'] ?? 'both');
    $nameOrder = $styles['name_order'] ?? 'groom_first';

    $groomNick = $invitation->groom_nickname ?: 'Putra';
    $groomFull = $invitation->groom_name ?: 'Putra Andika Pratama';
    $brideNick = $invitation->bride_nickname ?: 'Putri';
    $brideFull = $invitation->bride_name ?: 'Putri Cantika Sari';

    $formatParents = function (?string $father, ?string $mother, string $defaultFather, string $defaultMother): string {
        $f = trim((string) $father);
        $m = trim((string) $mother);
        if ($f === '' && $m === '') {
            return 'Bpk. ' . $defaultFather . ' & Ibu ' . $defaultMother;
        }
        $fFmt = $f !== '' ? (preg_match('/^(bpk|bapak|h\.|hj\.|prof\.|dr\.)/i', $f) ? $f : 'Bpk. ' . $f) : '';
        $mFmt = $m !== '' ? (preg_match('/^(ibu|ny\.|hj\.|prof\.|dr\.)/i', $m) ? $m : 'Ibu ' . $m) : '';
        return collect([$fFmt, $mFmt])->filter()->implode(' & ');
    };

    $groomParents = $formatParents($invitation->groom_father, $invitation->groom_mother, 'Deni Bastian', 'Aisha Dania');
    $brideParents = $formatParents($invitation->bride_father, $invitation->bride_mother, 'Abdul Rozak', 'Adelia Marni');

    $groomIg = ltrim(trim((string) ($invitation->instagram_groom ?? '')), '@');
    $brideIg = ltrim(trim((string) ($invitation->instagram_bride ?? '')), '@');

    $groomPhotoUrl = $invitation->groom_photo ? img_url($invitation->groom_photo) : $slots['default_groom'];
    $bridePhotoUrl = $invitation->bride_photo ? img_url($invitation->bride_photo) : $slots['default_bride'];

    $groomChildLabel = trim((string) ($styles['groom_child_order'] ?? '')) !== ''
        ? trim((string) $styles['groom_child_order'])
        : 'Putra Pertama dari';
    $brideChildLabel = trim((string) ($styles['bride_child_order'] ?? '')) !== ''
        ? trim((string) $styles['bride_child_order'])
        : 'Putri Pertama dari';

    if ($nameOrder === 'bride_first') {
        $firstPerson = [
            'nick' => $brideNick, 'full' => $brideFull, 'photo' => $bridePhotoUrl,
            'childLabel' => $brideChildLabel, 'parents' => $brideParents, 'ig' => $brideIg,
        ];
        $secondPerson = [
            'nick' => $groomNick, 'full' => $groomFull, 'photo' => $groomPhotoUrl,
            'childLabel' => $groomChildLabel, 'parents' => $groomParents, 'ig' => $groomIg,
        ];
        $firstNick = $brideNick;
        $secondNick = $groomNick;
    } else {
        $firstPerson = [
            'nick' => $groomNick, 'full' => $groomFull, 'photo' => $groomPhotoUrl,
            'childLabel' => $groomChildLabel, 'parents' => $groomParents, 'ig' => $groomIg,
        ];
        $secondPerson = [
            'nick' => $brideNick, 'full' => $brideFull, 'photo' => $bridePhotoUrl,
            'childLabel' => $brideChildLabel, 'parents' => $brideParents, 'ig' => $brideIg,
        ];
        $firstNick = $groomNick;
        $secondNick = $brideNick;
    }

    $coverSubtitle = !empty($styles['cover_subtitle']) ? $styles['cover_subtitle'] : 'THE WEDDING OF';
    $coupleTitle = !empty($styles['cover_title']) ? $styles['cover_title'] : ($firstNick . ' & ' . $secondNick);
    $coverPhotoUrl = $invitation->cover_image ? img_url($invitation->cover_image) : $slots['default_cover'];
    $heroPhotoUrl = $invitation->cover_image ? img_url($invitation->cover_image) : $slots['default_cover'];
    $resolvedGuest = trim((string) ($guestName ?? 'Tamu Undangan')) ?: 'Tamu Undangan';

    // Dates, countdown & calendar
    $mainDateObj = $invitation->event_date;
    if (!$mainDateObj && !empty($styles['event_date'])) {
        try { $mainDateObj = \Carbon\Carbon::parse($styles['event_date']); } catch (\Throwable $e) {}
    }
    if (!$mainDateObj) {
        $mainDateObj = $eventType === 'resepsi_only'
            ? ($invitation->resepsi_date ? \Carbon\Carbon::parse($invitation->resepsi_date) : null)
            : ($invitation->akad_date ? \Carbon\Carbon::parse($invitation->akad_date) : ($invitation->resepsi_date ? \Carbon\Carbon::parse($invitation->resepsi_date) : null));
    }
    $heroDisplayDate = $mainDateObj
        ? $mainDateObj->copy()->locale('id')->translatedFormat('l, d F Y')
        : 'Minggu, 28 Desember 2027';

    $mainTimeRaw = $eventType === 'resepsi_only'
        ? ($invitation->resepsi_time ?: '11:00:00')
        : ($invitation->akad_time ?: $invitation->resepsi_time ?: '08:00:00');
    $countdownDateObj = $mainDateObj ? $mainDateObj->copy() : \Carbon\Carbon::now()->addMonths(2);
    if (preg_match('/^(\d{1,2}):(\d{2})/', (string) $mainTimeRaw, $tm)) {
        $countdownDateObj->setTime((int) $tm[1], (int) $tm[2], 0);
    } else {
        $countdownDateObj->setTime(8, 0, 0);
    }
    $targetTimestamp = $countdownDateObj->timestamp;

    $formatEvent = function ($dateVal, ?string $timeVal, string $defaultTime, string $titlePrefix, string $venue, string $address, ?string $mapsLink) use ($coupleTitle, $groomFull, $brideFull): array {
        $d = null;
        if ($dateVal) {
            try { $d = \Carbon\Carbon::parse($dateVal)->locale('id'); } catch (\Throwable $e) {}
        }
        $dayName = $d ? $d->translatedFormat('l') : 'Minggu';
        $dayNum = $d ? $d->format('d') : '28';
        $monthName = $d ? $d->translatedFormat('F') : 'Desember';
        $yearStr = $d ? $d->format('Y') : '2027';

        $t = trim((string) $timeVal);
        if ($t === '') {
            $timeDisplay = $defaultTime . ' WIB - Selesai';
        } elseif (preg_match('/^(\d{1,2}:\d{2})/', $t, $m)) {
            $timeDisplay = $m[1] . ' WIB - Selesai';
        } else {
            $timeDisplay = str_contains(strtolower($t), 'selesai') ? $t : ($t . ' - Selesai');
        }

        $calObj = $d ? $d->copy() : \Carbon\Carbon::now()->addMonths(2);
        if (preg_match('/^(\d{1,2}):(\d{2})/', $t !== '' ? $t : $defaultTime, $tm)) {
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

        $mapUrl = trim((string) $mapsLink);
        if ($mapUrl === '' && $locationStr !== '') {
            $mapUrl = 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode($locationStr);
        }

        return [
            'day' => $dayName,
            'dayNum' => $dayNum,
            'monthName' => $monthName,
            'year' => $yearStr,
            'time' => $timeDisplay,
            'venue' => $venue,
            'address' => $address,
            'mapsUrl' => $mapUrl,
            'calendarUrl' => $calendarUrl,
        ];
    };

    $akadMeta = $formatEvent($invitation->akad_date, $invitation->akad_time, '08:00', 'Akad Nikah', (string) ($invitation->akad_venue ?? ''), (string) ($invitation->akad_address ?? ''), $invitation->akad_maps_link);
    $resepsiMeta = $formatEvent($invitation->resepsi_date, $invitation->resepsi_time, '11:00', 'Resepsi Pernikahan', (string) ($invitation->resepsi_venue ?? ''), (string) ($invitation->resepsi_address ?? ''), $invitation->resepsi_maps_link);
    $heroCalendarUrl = ($eventType === 'resepsi_only') ? $resepsiMeta['calendarUrl'] : $akadMeta['calendarUrl'];

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

    // Section visibility
    $showAkad = in_array($eventType, ['akad_only', 'both'], true) && ($invitation->akad_date || $invitation->akad_venue || $invitation->akad_maps_link || !isset($styles['event_type']));
    $showResepsi = in_array($eventType, ['resepsi_only', 'both'], true) && ($invitation->resepsi_date || $invitation->resepsi_venue || $invitation->resepsi_maps_link || !isset($styles['event_type']));
    $unduhDate = $styles['unduh_mantu_date'] ?? $invitation->unduh_mantu_date ?? null;
    $unduhVenue = $styles['unduh_mantu_venue'] ?? $invitation->unduh_mantu_venue ?? null;
    $showUnduhMantu = !empty($unduhDate) || !empty($unduhVenue);
    $unduhMeta = $showUnduhMantu
        ? $formatEvent($unduhDate, $styles['unduh_mantu_time'] ?? null, '11:00', 'Ngunduh Mantu', (string) ($unduhVenue ?? ''), (string) ($styles['unduh_mantu_address'] ?? ''), $styles['unduh_mantu_maps'] ?? null)
        : null;

    $streamingLink = $styles['streaming_link'] ?? $invitation->streaming_link ?? null;
    $showStreaming = !empty($streamingLink);
    $showCountdown = (bool) ($styles['countdown_enabled'] ?? true);

    // Gallery (real uploads first; demo SVG placeholders only when !$invitation->exists)
    $uploadedPhotos = $invitation->relationLoaded('photos') ? $invitation->photos : $invitation->photos()->get();
    $galleryPhotos = $uploadedPhotos->count() > 0
        ? $uploadedPhotos->map(fn ($p) => img_url($p->path))->values()->all()
        : (!$invitation->exists ? [$slots['default_gallery_1'], $slots['default_gallery_2'], $slots['default_gallery_3']] : []);
    $showGallery = (bool) ($invitation->enable_gallery ?? true) && count($galleryPhotos) > 0;

    // Text & Quote
    $openingPrayer = !empty($styles['welcome_message'])
        ? $styles['welcome_message']
        : "Maha Suci Allah yang telah menciptakan makhluk-Nya berpasang-pasangan. Ya Allah semoga ridho-Mu tercurah mengiringi pernikahan kami.";
    $quranVerse = trim((string) ($styles['quran_verse'] ?? '')) !== ''
        ? $styles['quran_verse']
        : '“Dan di antara tanda-tanda (kebesaran)-Nya ialah Dia menciptakan pasangan-pasangan untukmu dari jenismu sendiri, agar kamu cenderung dan merasa tenteram kepadanya, dan Dia menjadikan di antaramu rasa kasih dan sayang.”';
    $quranRef = array_key_exists('quran_ref', $styles)
        ? trim((string) ($styles['quran_ref'] ?? ''))
        : (trim((string) ($styles['quote_title'] ?? '')) !== '' ? trim((string) $styles['quote_title']) : 'Q.S Ar-Rum : 21');
    $quotePhotoUrl = !empty($styles['quote_image']) ? img_url($styles['quote_image']) : null;

    // Love Story (supports both 'description' and 'story')
    $defaultStories = [
        ['date' => 'Pertama Bertemu', 'title' => 'Awal Cerita', 'description' => 'Di tengah kesibukan masing-masing, takdir mempertemukan kami lewat percakapan sederhana yang terasa hangat.'],
        ['date' => 'Lamaran', 'title' => 'Menuju Halal', 'description' => 'Dengan doa dan restu kedua orang tua, kami mantap mengikat janji untuk melangkah bersama menuju masa depan.'],
    ];
    $filteredStories = (is_array($invitation->love_story) && !empty($invitation->love_story))
        ? array_values(array_filter($invitation->love_story, function ($story) {
            if (!is_array($story)) return false;
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

    // Gift & Bank Accounts
    $giftAddress = $styles['gift_address'] ?? $invitation->gift_address ?? '';
    $giftRecipient = $styles['gift_recipient'] ?? $coupleTitle;
    $defaultBanks = [
        ['bank' => 'BCA', 'account_number' => '1234567890', 'account_name' => $brideFull],
        ['bank' => 'BRI', 'account_number' => '0987654321', 'account_name' => $groomFull],
    ];
    $bankAccounts = (!empty($invitation->bank_accounts) && is_array($invitation->bank_accounts))
        ? array_values(array_filter($invitation->bank_accounts, fn ($acc) => !empty($acc['account_number']) || !empty($acc['bank'])))
        : (!$invitation->exists ? $defaultBanks : []);
    $showGift = (bool) ($invitation->enable_gift ?? true) && (count($bankAccounts) > 0 || trim((string) $giftAddress) !== '');

    $resolveBankLogo = function (?string $bankName) use ($slots): ?string {
        $b = strtolower(trim((string) $bankName));
        if (str_contains($b, 'bca') && !str_contains($b, 'syariah')) return $slots['bank_bca'];
        if (str_contains($b, 'bri')) return $slots['bank_bri'];
        return null;
    };

    $showRsvp = (bool) (($invitation->enable_rsvp ?? true) || ($invitation->enable_wishes ?? true));
    $dressCode = trim((string) ($styles['dress_code'] ?? $styles['event_note'] ?? ''));
    $turutMengundang = trim((string) ($styles['turut_mengundang'] ?? ''));

    $wishesList = $wishes ?? collect([]);
    $wishesCount = $totalWishesCount ?? $wishesList->count();

    $cPrimary = $colors['primary'] ?? '#8B1E2B';
    $cPrimaryDark = $colors['secondary'] ?? '#5C111B';
    $cGold = $colors['accent'] ?? '#C59B5F';
    $cBg = $colors['background'] ?? '#F7F1E8';
    $cText = $colors['text'] ?? '#332527';
@endphp

@once
    @push('fonts')
        <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=Cormorant+Garamond:ital,wght@0,500;0,600;0,700;1,400;1,600&family=Galada&family=Nunito+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    @endpush

    @push('styles')
        <style>
            html, body {
                margin: 0;
                padding: 0;
                background-color: #3E0B12;
                overflow-x: hidden;
            }
            body.cb-locked {
                height: 100vh !important;
                height: 100dvh !important;
                overflow: hidden !important;
            }

            .cb-root {
                --cb-primary: {{ $cPrimary }};
                --cb-primary-dark: {{ $cPrimaryDark }};
                --cb-gold: {{ $cGold }};
                --cb-bg: {{ $cBg }};
                --cb-soft: #F6E9EB;
                --cb-text: {{ $cText }};
                --cb-muted: #6E585B;
                --cb-border: rgba(139, 30, 43, 0.24);
                --cb-font-heading: 'Cormorant Garamond', Georgia, serif;
                --cb-font-script: 'Galada', cursive;
                --cb-font-body: 'Nunito Sans', system-ui, sans-serif;
                --cb-font-arabic: 'Amiri', Georgia, serif;
                font-family: var(--cb-font-body);
                color: var(--cb-text);
                background-color: #3E0B12;
                min-height: 100dvh;
                position: relative;
            }
            .cb-root *, .cb-root *::before, .cb-root *::after {
                box-sizing: border-box;
            }
            .cb-font-heading { font-family: var(--cb-font-heading); }
            .cb-font-script { font-family: var(--cb-font-script); }
            .cb-font-arabic { font-family: var(--cb-font-arabic); }

            /* Split-Screen Desktop (>=1024px) */
            .cb-desktop-left { display: none; }
            .cb-mobile-col {
                width: 100%;
                min-height: 100dvh;
                position: relative;
                background-color: var(--cb-bg);
                background-position: center top;
                background-size: 100% auto;
                background-repeat: repeat-y;
                overflow-x: hidden;
            }
            @media (min-width: 1024px) {
                .cb-desktop-left {
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    justify-content: center;
                    position: fixed;
                    inset: 0 auto 0 0;
                    width: calc(100% - 440px);
                    z-index: 50;
                    padding: 48px;
                    text-align: center;
                    color: #FFFFFF;
                    background: linear-gradient(155deg, rgba(139, 30, 43, 0.88), rgba(62, 11, 18, 0.96));
                    overflow: hidden;
                }
                .cb-desktop-left-bg {
                    position: absolute;
                    inset: 0;
                    width: 100%;
                    height: 100%;
                    object-fit: cover;
                    opacity: 0.22;
                    mix-blend-mode: luminosity;
                    pointer-events: none;
                    animation: cbSlowKenBurns 22s ease-in-out infinite alternate;
                }
                .cb-mobile-col {
                    width: 440px;
                    margin-left: auto;
                    box-shadow: -12px 0 36px rgba(0, 0, 0, 0.45);
                }
            }

            @keyframes cbSlowKenBurns {
                0% { transform: scale(1) translateY(0); }
                100% { transform: scale(1.08) translateY(-10px); }
            }

            /* Organic Wind-Swaying Botanical Corner Ornaments */
            .cb-deco-layer {
                position: absolute;
                inset: 0;
                pointer-events: none;
                overflow: hidden;
                z-index: 2;
            }
            @keyframes cbWindSwayTL {
                0%, 100% { transform: translate(0, 0) rotate(0deg) scale(1); }
                33% { transform: translate(3px, 3px) rotate(2.2deg) scale(1.04); }
                66% { transform: translate(-1px, 4px) rotate(-1.4deg) scale(1.06); }
            }
            @keyframes cbWindSwayTR {
                0%, 100% { transform: translate(0, 0) rotate(0deg) scale(1); }
                35% { transform: translate(-3px, 4px) rotate(-2deg) scale(1.05); }
                70% { transform: translate(2px, 2px) rotate(1.5deg) scale(1.02); }
            }
            @keyframes cbWindSwayBL {
                0%, 100% { transform: scaleX(-1) translate(0, 0) rotate(0deg) scale(1); }
                40% { transform: scaleX(-1) translate(3px, -3px) rotate(2deg) scale(1.05); }
                75% { transform: scaleX(-1) translate(-2px, -4px) rotate(-1.4deg) scale(1.03); }
            }
            @keyframes cbWindSwayBR {
                0%, 100% { transform: translate(0, 0) rotate(0deg) scale(1); }
                40% { transform: translate(-3px, -4px) rotate(-2.2deg) scale(1.05); }
                75% { transform: translate(2px, -2px) rotate(1.4deg) scale(1.03); }
            }
            .cb-deco-tl {
                position: absolute; top: -8px; left: -8px; width: 145px; opacity: 0.86;
                transform-origin: top left;
                animation: cbWindSwayTL 6.2s ease-in-out infinite;
            }
            .cb-deco-tr {
                position: absolute; top: -8px; right: -10px; width: 120px; opacity: 0.86;
                transform-origin: top right;
                animation: cbWindSwayTR 7s ease-in-out infinite 0.7s;
            }
            .cb-deco-bl {
                position: absolute; bottom: -8px; left: -8px; width: 135px; opacity: 0.86;
                transform-origin: bottom left;
                animation: cbWindSwayBL 6.6s ease-in-out infinite 1.1s;
            }
            .cb-deco-br {
                position: absolute; bottom: -8px; right: -8px; width: 135px; opacity: 0.86;
                transform-origin: bottom right;
                animation: cbWindSwayBR 6.8s ease-in-out infinite 1.7s;
            }

            /* Ambient Falling Rose Petals & Twinkling Sparkles */
            .cb-ambient-layer {
                position: fixed;
                inset: 0;
                z-index: 4;
                pointer-events: none;
                overflow: hidden;
            }
            @media (min-width: 1024px) {
                .cb-ambient-layer {
                    left: auto;
                    width: 440px;
                }
            }
            @keyframes cbPetalFall {
                0% { transform: translate3d(0, -10vh, 0) rotate(0deg) scale(0.85); opacity: 0; }
                12% { opacity: 0.62; }
                50% { transform: translate3d(26px, 48vh, 0) rotate(170deg) scale(1); opacity: 0.55; }
                88% { opacity: 0.45; }
                100% { transform: translate3d(-18px, 106vh, 0) rotate(330deg) scale(0.8); opacity: 0; }
            }
            @keyframes cbTwinkle {
                0%, 100% { transform: scale(0.6) rotate(45deg); opacity: 0.2; }
                50% { transform: scale(1.25) rotate(45deg); opacity: 0.85; }
            }
            .cb-petal {
                position: absolute;
                top: -24px;
                width: 12px;
                height: 16px;
                border-radius: 75% 15% 75% 15%;
                background: linear-gradient(135deg, rgba(168, 42, 57, 0.72), rgba(241, 214, 138, 0.45));
                box-shadow: 0 2px 6px rgba(92, 17, 27, 0.14);
                animation: cbPetalFall 13s linear infinite;
            }
            .cb-sparkle {
                position: absolute;
                width: 6px;
                height: 6px;
                background: linear-gradient(135deg, #F5DFA0, #C59B5F);
                animation: cbTwinkle 3.8s ease-in-out infinite;
            }

            /* Fluttering Crimson-Gold Butterflies */
            .cb-butterfly {
                position: absolute;
                z-index: 6;
                pointer-events: none;
                width: 26px;
                height: 26px;
                opacity: 0;
            }
            .cb-butterfly svg { width: 100%; height: 100%; overflow: visible; }
            .cb-wing-l { transform-origin: 12px 12px; animation: cbWingL 0.28s ease-in-out infinite; }
            .cb-wing-r { transform-origin: 12px 12px; animation: cbWingR 0.28s ease-in-out infinite; }
            @keyframes cbWingL { 0%, 100% { transform: rotateY(0deg); } 50% { transform: rotateY(58deg); } }
            @keyframes cbWingR { 0%, 100% { transform: rotateY(0deg); } 50% { transform: rotateY(-58deg); } }
            @keyframes cbFlyA {
                0% { top: 72%; left: -8%; opacity: 0; transform: rotate(-18deg) scale(0.8); }
                12% { opacity: 0.78; }
                42% { top: 34%; left: 36%; transform: rotate(12deg) scale(1); }
                75% { top: 24%; left: 74%; transform: rotate(-10deg) scale(0.92); }
                90% { opacity: 0.65; }
                100% { top: 8%; left: 108%; opacity: 0; transform: rotate(-6deg) scale(0.8); }
            }
            @keyframes cbFlyB {
                0% { top: 20%; right: -8%; left: auto; opacity: 0; transform: rotate(14deg) scale(0.72); }
                15% { opacity: 0.62; }
                50% { top: 52%; right: 44%; transform: rotate(-14deg) scale(0.95); }
                85% { opacity: 0.52; }
                100% { top: 76%; right: 108%; opacity: 0; transform: rotate(8deg) scale(0.75); }
            }
            .cb-bf-a { animation: cbFlyA 14s ease-in-out infinite 0.8s; }
            .cb-bf-b { animation: cbFlyB 17s ease-in-out infinite 3.5s; width: 21px; height: 21px; }

            /* Cover Lock Screen & Choreographed Entrance */
            .cb-cover-gate {
                position: fixed;
                inset: 0;
                z-index: 900;
                background-color: var(--cb-bg);
                background-size: cover;
                background-position: center;
                overflow-y: auto;
                overflow-x: hidden;
                display: flex;
                flex-direction: column;
                align-items: center;
                transition: transform 1.05s cubic-bezier(0.77, 0, 0.175, 1), opacity 0.85s ease;
            }
            @media (min-width: 1024px) {
                .cb-cover-gate { left: auto; width: 440px; }
            }
            .cb-cover-gate.is-opened {
                transform: translateY(-100%);
                opacity: 0;
                pointer-events: none;
            }
            .cb-cover-stack {
                position: relative;
                z-index: 5;
                width: 100%;
                margin: auto 0;
                padding: 58px 26px 46px;
                display: flex;
                flex-direction: column;
                align-items: center;
                text-align: center;
            }
            @keyframes cbCoverRise {
                from { opacity: 0; transform: translateY(18px) scale(0.97); }
                to { opacity: 1; transform: translateY(0) scale(1); }
            }
            .cb-cover-anim-1 { animation: cbCoverRise 0.85s cubic-bezier(0.22, 1, 0.36, 1) 0.08s both; }
            .cb-cover-anim-2 { animation: cbCoverRise 0.8s cubic-bezier(0.22, 1, 0.36, 1) 0.24s both; }
            .cb-cover-anim-3 { animation: cbCoverRise 0.8s cubic-bezier(0.22, 1, 0.36, 1) 0.4s both; }
            .cb-cover-anim-4 { animation: cbCoverRise 0.8s cubic-bezier(0.22, 1, 0.36, 1) 0.56s both; }

            /* Safari-Safe Explicit Arch Photo Wrappers */
            @keyframes cbArchFloat {
                0%, 100% { transform: translateY(0); }
                50% { transform: translateY(-5px); }
            }
            @keyframes cbFlowerSwayL {
                0%, 100% { transform: scaleX(-1) rotate(0deg); }
                50% { transform: scaleX(-1) rotate(5deg); }
            }
            @keyframes cbFlowerSwayR {
                0%, 100% { transform: rotate(0deg); }
                50% { transform: rotate(-5deg); }
            }
            .cb-arch-wrap {
                position: relative;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                margin-bottom: 18px;
                flex-shrink: 0;
            }
            .cb-arch-float { animation: cbArchFloat 5.5s ease-in-out infinite; }
            .cb-arch-cover {
                width: clamp(160px, 48vw, 200px);
                height: clamp(210px, 62vw, 260px);
                border-radius: 500px 500px 10px 10px;
                border: 5px solid #FFFFFF;
                outline: 1.5px solid var(--cb-gold);
                overflow: hidden;
                background: #EEDEE0;
                box-shadow: 0 12px 28px rgba(92, 17, 27, 0.18);
                flex-shrink: 0;
            }
            .cb-arch-couple {
                width: 176px;
                height: 224px;
                border-radius: 500px 500px 10px 10px;
                border: 5px solid #FFFFFF;
                outline: 1.5px solid var(--cb-gold);
                overflow: hidden;
                background: #EEDEE0;
                box-shadow: 0 10px 24px rgba(92, 17, 27, 0.16);
                flex-shrink: 0;
            }
            .cb-arch-cover img, .cb-arch-couple img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                object-position: center 20%;
                display: block;
            }
            .cb-arch-flower-bl {
                position: absolute; bottom: -10px; left: -24px; width: 76px;
                transform-origin: bottom right; pointer-events: none; z-index: 3;
                animation: cbFlowerSwayL 5.2s ease-in-out infinite;
            }
            .cb-arch-flower-br {
                position: absolute; bottom: -10px; right: -24px; width: 76px;
                transform-origin: bottom left; pointer-events: none; z-index: 3;
                animation: cbFlowerSwayR 5.2s ease-in-out infinite 0.4s;
            }

            /* Typography & Buttons */
            .cb-eyebrow {
                font-size: 11px;
                font-weight: 700;
                letter-spacing: 0.24em;
                text-transform: uppercase;
                color: var(--cb-primary);
            }
            .cb-btn {
                position: relative;
                overflow: hidden;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 8px;
                padding: 11px 26px;
                border-radius: 999px;
                font-family: var(--cb-font-body);
                font-size: 13px;
                font-weight: 700;
                letter-spacing: 0.04em;
                border: none;
                cursor: pointer;
                text-decoration: none;
                transition: transform 0.25s ease, background-color 0.25s ease, box-shadow 0.25s ease;
            }
            .cb-btn-primary {
                background-color: var(--cb-primary);
                color: #FFFFFF;
                box-shadow: 0 6px 18px rgba(139, 30, 43, 0.28);
            }
            .cb-btn-primary:hover {
                background-color: var(--cb-primary-dark);
                transform: translateY(-2px);
            }
            .cb-btn-outline {
                background-color: rgba(255, 255, 255, 0.85);
                color: var(--cb-primary);
                border: 1.5px solid var(--cb-primary);
            }
            .cb-btn-outline:hover {
                background-color: var(--cb-primary);
                color: #FFFFFF;
            }
            @keyframes cbShimmerSweep {
                0% { transform: translateX(-140%) skewX(-20deg); }
                45%, 100% { transform: translateX(220%) skewX(-20deg); }
            }
            @keyframes cbPulseRing {
                0% { box-shadow: 0 0 0 0 rgba(139, 30, 43, 0.45), 0 6px 18px rgba(139, 30, 43, 0.28); }
                70% { box-shadow: 0 0 0 11px rgba(139, 30, 43, 0), 0 6px 18px rgba(139, 30, 43, 0.28); }
                100% { box-shadow: 0 0 0 0 rgba(139, 30, 43, 0), 0 6px 18px rgba(139, 30, 43, 0.28); }
            }
            .cb-btn-shimmer {
                animation: cbPulseRing 2.6s ease-out infinite;
            }
            .cb-btn-shimmer::after {
                content: '';
                position: absolute;
                top: 0;
                bottom: 0;
                left: 0;
                width: 45%;
                background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.36), transparent);
                animation: cbShimmerSweep 3.4s ease-in-out infinite;
                pointer-events: none;
            }

            /* Hero Choreographed Entrance & Bouncing Scroll Hint */
            .cb-hero-step {
                opacity: 0;
                transform: translateY(18px);
                transition: opacity 0.75s cubic-bezier(0.22, 1, 0.36, 1), transform 0.75s cubic-bezier(0.22, 1, 0.36, 1);
            }
            .cb-hero-active .cb-hero-step { opacity: 1; transform: translateY(0); }
            .cb-hero-active .cb-hs-1 { transition-delay: 0.15s; }
            .cb-hero-active .cb-hs-2 { transition-delay: 0.3s; }
            .cb-hero-active .cb-hs-3 { transition-delay: 0.45s; }
            .cb-hero-active .cb-hs-4 { transition-delay: 0.6s; }
            @keyframes cbBounceDown {
                0%, 100% { transform: translateY(0); opacity: 0.7; }
                50% { transform: translateY(6px); opacity: 1; }
            }
            .cb-scroll-cue {
                animation: cbBounceDown 2s ease-in-out infinite;
            }

            /* Countdown Crimson Boxes with Pop Stagger */
            .cb-countdown-grid {
                display: grid;
                grid-template-columns: repeat(4, minmax(0, 1fr));
                gap: 8px;
                width: 100%;
                max-width: 300px;
                margin: 16px auto 18px;
            }
            .cb-cd-box {
                background: linear-gradient(160deg, var(--cb-primary), var(--cb-primary-dark));
                color: #FFFFFF;
                border-radius: 10px;
                padding: 10px 4px;
                border: 1px solid rgba(197, 155, 95, 0.45);
                box-shadow: 0 6px 14px rgba(92, 17, 27, 0.2);
                text-align: center;
                opacity: 0;
                transform: scale(0.84) translateY(10px);
                transition: opacity 0.55s cubic-bezier(0.22, 1, 0.36, 1), transform 0.55s cubic-bezier(0.22, 1, 0.36, 1);
            }
            .cb-hero-active .cb-cd-box { opacity: 1; transform: scale(1) translateY(0); }
            .cb-hero-active .cb-cd-box:nth-child(1) { transition-delay: 0.45s; }
            .cb-hero-active .cb-cd-box:nth-child(2) { transition-delay: 0.54s; }
            .cb-hero-active .cb-cd-box:nth-child(3) { transition-delay: 0.63s; }
            .cb-hero-active .cb-cd-box:nth-child(4) { transition-delay: 0.72s; }
            .cb-cd-val {
                display: block;
                font-family: var(--cb-font-heading);
                font-size: 24px;
                font-weight: 700;
                line-height: 1.1;
            }
            .cb-cd-lbl {
                display: block;
                font-size: 10px;
                letter-spacing: 0.06em;
                opacity: 0.88;
                margin-top: 2px;
            }

            /* Sections, Cards & Hover Lift */
            .cb-section {
                position: relative;
                padding: 52px 24px;
                z-index: 3;
            }
            .cb-card {
                background: rgba(255, 253, 249, 0.92);
                backdrop-filter: blur(8px);
                -webkit-backdrop-filter: blur(8px);
                border: 1px solid var(--cb-border);
                border-radius: 18px;
                overflow: hidden;
                box-shadow: 0 10px 26px rgba(92, 17, 27, 0.08);
                transition: transform 0.35s ease, box-shadow 0.35s ease;
            }
            .cb-card:hover {
                transform: translateY(-2px);
                box-shadow: 0 14px 30px rgba(92, 17, 27, 0.13);
            }
            .cb-card-header {
                background: linear-gradient(135deg, var(--cb-primary), var(--cb-primary-dark));
                color: #FFFFFF;
                padding: 12px 18px;
                text-align: center;
            }
            @keyframes cbHeartPulse {
                0%, 100% { transform: scale(1); }
                50% { transform: scale(1.12); }
            }
            .cb-amp-pulse {
                display: inline-block;
                animation: cbHeartPulse 2.8s ease-in-out infinite;
            }

            /* ATM Bank Card Style + Periodic Metallic Sheen */
            @keyframes cbAtmSheen {
                0% { transform: translateX(-160%) rotate(22deg); }
                30%, 100% { transform: translateX(260%) rotate(22deg); }
            }
            .cb-atm-card {
                position: relative;
                background: linear-gradient(135deg, #FFFDF9 0%, #F7EBEC 58%, #EFD8DB 100%);
                border: 1.5px solid rgba(139, 30, 43, 0.26);
                border-radius: 16px;
                padding: 20px;
                box-shadow: 0 10px 24px rgba(92, 17, 27, 0.1);
                overflow: hidden;
                margin-bottom: 16px;
            }
            .cb-atm-card::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                height: 4px;
                background: linear-gradient(90deg, var(--cb-primary-dark), var(--cb-primary), var(--cb-gold));
            }
            .cb-atm-card::after {
                content: '';
                position: absolute;
                top: -40%;
                bottom: -40%;
                left: 0;
                width: 38%;
                background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.55), transparent);
                animation: cbAtmSheen 5.5s ease-in-out infinite;
                pointer-events: none;
            }

            /* Form Controls & Wish Card Entrance */
            .cb-input, .cb-select, .cb-textarea {
                width: 100%;
                background-color: #FFFFFF;
                border: 1.5px solid var(--cb-border);
                border-radius: 10px;
                padding: 11px 14px;
                font-family: var(--cb-font-body);
                font-size: 13.5px;
                color: var(--cb-text);
                outline: none;
                margin-bottom: 12px;
                transition: border-color 0.2s ease, box-shadow 0.2s ease;
            }
            .cb-input:focus, .cb-select:focus, .cb-textarea:focus {
                border-color: var(--cb-primary);
                box-shadow: 0 0 0 3px rgba(139, 30, 43, 0.12);
            }
            @keyframes cbWishIn {
                from { opacity: 0; transform: translateY(10px); }
                to { opacity: 1; transform: translateY(0); }
            }
            .cb-wish-card {
                animation: cbWishIn 0.45s cubic-bezier(0.22, 1, 0.36, 1) both;
            }

            /* Gallery Grid */
            .cb-gal-grid {
                display: grid;
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 10px;
            }
            .cb-gal-item {
                border-radius: 12px;
                overflow: hidden;
                border: 1.5px solid rgba(139, 30, 43, 0.2);
                cursor: pointer;
                aspect-ratio: 4/3;
                background: #EEDEE0;
                transition: border-color 0.3s ease, box-shadow 0.3s ease, opacity 0.7s ease, transform 0.7s ease;
            }
            .cb-gal-item:hover {
                border-color: var(--cb-gold);
                box-shadow: 0 8px 22px rgba(92, 17, 27, 0.18);
            }
            .cb-gal-item img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                display: block;
                transition: transform 0.45s ease;
            }
            .cb-gal-item:hover img { transform: scale(1.06); }

            /* Floating Dock & Animated FABs */
            .cb-dock-wrap {
                position: fixed;
                bottom: 16px;
                left: 0;
                right: 0;
                z-index: 800;
                display: flex;
                justify-content: center;
                padding: 0 14px;
                pointer-events: none;
            }
            @media (min-width: 1024px) {
                .cb-dock-wrap { left: auto; width: 440px; }
            }
            .cb-dock {
                pointer-events: auto;
                display: flex;
                align-items: center;
                justify-content: space-around;
                width: 100%;
                max-width: 360px;
                padding: 7px 10px;
                background: rgba(139, 30, 43, 0.96);
                backdrop-filter: blur(10px);
                -webkit-backdrop-filter: blur(10px);
                border-radius: 16px;
                border: 1px solid rgba(197, 155, 95, 0.45);
                box-shadow: 0 10px 26px rgba(62, 11, 18, 0.42);
            }
            .cb-dock-item {
                width: 40px;
                height: 36px;
                border-radius: 10px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                color: rgba(255, 255, 255, 0.78);
                text-decoration: none;
                transition: background-color 0.25s ease, color 0.25s ease, transform 0.25s ease;
            }
            .cb-dock-item.is-active {
                background-color: var(--cb-primary-dark);
                color: #F1D68A;
                transform: translateY(-2px);
                box-shadow: inset 0 0 0 1px rgba(197, 155, 95, 0.55);
            }
            .cb-dock-item svg { width: 17px; height: 17px; }

            .cb-fab-stack {
                position: fixed;
                bottom: 78px;
                right: 16px;
                z-index: 800;
                display: flex;
                flex-direction: column;
                gap: 8px;
            }
            .cb-fab-btn {
                position: relative;
                width: 38px;
                height: 38px;
                border-radius: 50%;
                background: var(--cb-primary);
                color: #FFFFFF;
                border: 1.5px solid var(--cb-gold);
                display: inline-flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                box-shadow: 0 4px 14px rgba(62, 11, 18, 0.35);
            }
            @keyframes cbMusicSpin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }
            @keyframes cbGoldHalo {
                0% { box-shadow: 0 0 0 0 rgba(197, 155, 95, 0.55), 0 4px 14px rgba(62, 11, 18, 0.35); }
                75% { box-shadow: 0 0 0 10px rgba(197, 155, 95, 0), 0 4px 14px rgba(62, 11, 18, 0.35); }
                100% { box-shadow: 0 0 0 0 rgba(197, 155, 95, 0), 0 4px 14px rgba(62, 11, 18, 0.35); }
            }
            .cb-fab-btn.is-playing {
                animation: cbGoldHalo 2.1s ease-out infinite;
            }
            .cb-fab-btn.is-playing svg {
                animation: cbMusicSpin 7s linear infinite;
            }
            .cb-fab-btn.is-active {
                background: var(--cb-primary-dark);
                color: #F1D68A;
                animation: cbGoldHalo 1.8s ease-out infinite;
            }

            /* Directional Scroll Reveals (Reactive via Alpine revealed[key]) */
            .cb-reveal {
                opacity: 0;
                transform: translateY(22px);
                transition: opacity 0.72s cubic-bezier(0.22, 1, 0.36, 1), transform 0.72s cubic-bezier(0.22, 1, 0.36, 1);
            }
            .cb-reveal.cb-from-left { transform: translateX(-26px); }
            .cb-reveal.cb-from-right { transform: translateX(26px); }
            .cb-reveal.cb-from-scale { transform: scale(0.92); }
            .cb-reveal.cb-revealed {
                opacity: 1;
                transform: translate3d(0, 0, 0) scale(1);
            }
            .cb-d1 { transition-delay: 0.12s; }
            .cb-d2 { transition-delay: 0.24s; }
            .cb-d3 { transition-delay: 0.36s; }

            @media (prefers-reduced-motion: reduce) {
                .cb-deco-tl, .cb-deco-tr, .cb-deco-bl, .cb-deco-br,
                .cb-arch-float, .cb-arch-flower-bl, .cb-arch-flower-br,
                .cb-petal, .cb-sparkle, .cb-butterfly, .cb-wing-l, .cb-wing-r,
                .cb-btn-shimmer, .cb-btn-shimmer::after, .cb-scroll-cue,
                .cb-amp-pulse, .cb-atm-card::after, .cb-desktop-left-bg,
                .cb-fab-btn.is-playing, .cb-fab-btn.is-playing svg, .cb-fab-btn.is-active,
                .cb-cover-anim-1, .cb-cover-anim-2, .cb-cover-anim-3, .cb-cover-anim-4, .cb-wish-card {
                    animation: none !important;
                }
                .cb-reveal, .cb-hero-step, .cb-cd-box {
                    opacity: 1 !important;
                    transform: none !important;
                    transition: none !important;
                }
            }
        </style>
    @endpush
@endonce

<div class="cb-root"
     x-data="crimsonBotanicalTheme({
        targetTimestamp: {{ $targetTimestamp }},
        galleryCount: {{ count($galleryPhotos) }},
        hasMusic: {{ $musicSrc !== '' ? 'true' : 'false' }}
     })">

    @if($musicSrc !== '')
        <div wire:ignore style="position: absolute; width: 0; height: 0; overflow: hidden; opacity: 0; pointer-events: none;" aria-hidden="true">
            <audio id="cb-song" loop preload="none" playsinline webkit-playsinline src="{{ $musicSrc }}">
                <source src="{{ $musicSrc }}" type="{{ $musicMime }}">
            </audio>
        </div>
    @endif

    <!-- Desktop Fixed Left Cover Column -->
    <aside class="cb-desktop-left" aria-hidden="true">
        <img src="{{ $heroPhotoUrl }}" alt="" class="cb-desktop-left-bg">
        <div style="position: relative; z-index: 2; max-width: 540px;">
            <p class="cb-eyebrow" style="color: #F1D68A; margin: 0 0 12px;">{{ $coverSubtitle }}</p>
            <h2 class="cb-font-heading" style="font-size: clamp(44px, 4.8vw, 64px); font-weight: 700; line-height: 1.08; margin: 0; color: #FFFFFF;">
                {{ $coupleTitle }}
            </h2>
            <div style="width: 68px; height: 2px; background: var(--cb-gold); margin: 18px auto 14px;"></div>
            <p style="font-size: 14px; letter-spacing: 0.14em; color: rgba(255, 255, 255, 0.88); margin: 0;">
                {{ $heroDisplayDate }}
            </p>
        </div>
    </aside>

    <!-- Opening Cover Screen -->
    <div id="cover" class="cb-cover-gate" :class="{ 'is-opened': isOpened }" :aria-hidden="isOpened ? 'true' : 'false'"
         style="background-image: url('{{ $slots['parchment_bg'] }}');">
        <div class="cb-deco-layer" wire:ignore>
            <img src="{{ $slots['floral_tl'] }}" alt="" class="cb-deco-tl" fetchpriority="high">
            <img src="{{ $slots['floral_tr'] }}" alt="" class="cb-deco-tr" fetchpriority="high">
            <img src="{{ $slots['floral_br'] }}" alt="" class="cb-deco-bl">
            <img src="{{ $slots['floral_br'] }}" alt="" class="cb-deco-br">
        </div>

        <!-- Cover Petals & Butterfly -->
        <div class="cb-deco-layer" wire:ignore style="z-index: 3;">
            <span class="cb-petal" style="left: 16%; animation-duration: 11s; animation-delay: 0s;"></span>
            <span class="cb-petal" style="left: 52%; animation-duration: 13.5s; animation-delay: 2.5s; width: 10px; height: 14px;"></span>
            <span class="cb-petal" style="left: 82%; animation-duration: 12s; animation-delay: 1.2s;"></span>
            <span class="cb-sparkle" style="top: 22%; left: 18%; animation-delay: 0.4s;"></span>
            <span class="cb-sparkle" style="top: 30%; right: 20%; animation-delay: 1.6s;"></span>
            <div class="cb-butterfly cb-bf-a" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="#8B1E2B">
                    <path class="cb-wing-l" d="M12 12C9 6 2 5 2 10c0 2.8 3.5 4 6.5 3.2C6 15 4 17.5 6 19.5c2 2 5-1.5 6-5z" fill-opacity="0.72"/>
                    <path class="cb-wing-r" d="M12 12c3-6 10-7 10-2 0 2.8-3.5 4-6.5 3.2 2.5 1.8 4.5 4.3 2.5 6.3-2 2-5-1.5-6-5z" fill-opacity="0.72"/>
                    <ellipse cx="12" cy="13" rx="1" ry="4" fill="#C59B5F"/>
                </svg>
            </div>
        </div>

        <div class="cb-cover-stack">
            <div class="cb-arch-wrap cb-arch-float cb-cover-anim-1">
                <div class="cb-arch-cover">
                    <img src="{{ $coverPhotoUrl }}" alt="{{ $coupleTitle }}" fetchpriority="high">
                </div>
                <img src="{{ $slots['floral_br'] }}" alt="" class="cb-arch-flower-bl">
                <img src="{{ $slots['floral_br'] }}" alt="" class="cb-arch-flower-br">
            </div>

            <div class="cb-cover-anim-2">
                <p class="cb-eyebrow" style="margin: 0 0 4px;">{{ $coverSubtitle }}</p>
                <h1 class="cb-font-script" style="font-size: clamp(32px, 5.2dvh, 42px); font-weight: 400; color: var(--cb-primary); line-height: 1.2; margin: 0 0 10px;">
                    {{ $coupleTitle }}
                </h1>
            </div>

            <div class="cb-cover-anim-3">
                <p style="font-size: 12px; color: var(--cb-muted); margin: 0 0 3px;">Kepada Yth. Bapak/Ibu/Saudara/i</p>
                <p class="cb-font-heading" style="font-size: 21px; font-weight: 700; color: var(--cb-primary-dark); margin: 0 0 6px; word-break: break-word;">
                    {{ $resolvedGuest }}
                </p>
                <p style="font-size: 10px; font-style: italic; color: var(--cb-muted); margin: 0 0 18px;">
                    *Mohon maaf jika ada kesalahan dalam penulisan nama / gelar.
                </p>
            </div>

            <button type="button" @click="openInvitation()" class="cb-btn cb-btn-primary cb-btn-shimmer cb-cover-anim-4">
                <svg style="width: 15px; height: 15px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                <span>Buka Undangan</span>
            </button>
        </div>
    </div>

    <!-- Fixed Ambient Falling Petals & Sparkles After Opening -->
    <div class="cb-ambient-layer" wire:ignore x-show="isOpened" x-transition.opacity style="display: none;" aria-hidden="true">
        <span class="cb-petal" style="left: 12%; animation-duration: 12s; animation-delay: 0s;"></span>
        <span class="cb-petal" style="left: 34%; animation-duration: 15s; animation-delay: 3.1s; width: 10px; height: 13px;"></span>
        <span class="cb-petal" style="left: 58%; animation-duration: 13.5s; animation-delay: 1.4s;"></span>
        <span class="cb-petal" style="left: 78%; animation-duration: 16s; animation-delay: 4.5s; width: 11px; height: 14px;"></span>
        <span class="cb-petal" style="left: 90%; animation-duration: 14s; animation-delay: 2.2s;"></span>
        <span class="cb-sparkle" style="top: 18%; left: 16%; animation-delay: 0.2s;"></span>
        <span class="cb-sparkle" style="top: 44%; right: 14%; animation-delay: 1.4s;"></span>
        <span class="cb-sparkle" style="top: 72%; left: 22%; animation-delay: 2.3s;"></span>
    </div>

    <!-- Scrollable Single Mobile Column -->
    <main class="cb-mobile-col" style="background-image: url('{{ $slots['parchment_bg'] }}');">

        <!-- 1. Hero & Countdown -->
        <section id="hero" class="cb-section cb-section-spy" :class="{ 'cb-hero-active': isOpened }"
                 style="min-height: 100dvh; display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; padding-top: 64px;">
            <div class="cb-deco-layer" wire:ignore>
                <img src="{{ $slots['floral_tl'] }}" alt="" class="cb-deco-tl">
                <img src="{{ $slots['floral_tr'] }}" alt="" class="cb-deco-tr">
                <img src="{{ $slots['floral_br'] }}" alt="" class="cb-deco-bl">
                <img src="{{ $slots['floral_br'] }}" alt="" class="cb-deco-br">
                <div class="cb-butterfly cb-bf-b" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="#8B1E2B">
                        <path class="cb-wing-l" d="M12 12C9 6 2 5 2 10c0 2.8 3.5 4 6.5 3.2C6 15 4 17.5 6 19.5c2 2 5-1.5 6-5z" fill-opacity="0.65"/>
                        <path class="cb-wing-r" d="M12 12c3-6 10-7 10-2 0 2.8-3.5 4-6.5 3.2 2.5 1.8 4.5 4.3 2.5 6.3-2 2-5-1.5-6-5z" fill-opacity="0.65"/>
                        <ellipse cx="12" cy="13" rx="1" ry="4" fill="#C59B5F"/>
                    </svg>
                </div>
            </div>

            <div style="position: relative; z-index: 4; width: 100%;">
                <div class="cb-arch-wrap cb-arch-float cb-hero-step cb-hs-1">
                    <div class="cb-arch-cover">
                        <img src="{{ $heroPhotoUrl }}" alt="{{ $coupleTitle }}" decoding="async">
                    </div>
                    <img src="{{ $slots['floral_br'] }}" alt="" class="cb-arch-flower-bl">
                    <img src="{{ $slots['floral_br'] }}" alt="" class="cb-arch-flower-br">
                </div>

                <div class="cb-hero-step cb-hs-2">
                    <p class="cb-eyebrow" style="margin: 0 0 4px;">THE WEDDING OF</p>
                    <h2 class="cb-font-script" style="font-size: 38px; font-weight: 400; color: var(--cb-primary); line-height: 1.2; margin: 0 0 4px;">
                        {{ $coupleTitle }}
                    </h2>
                    <p style="font-size: 13px; font-weight: 600; color: var(--cb-muted); margin: 0;">
                        {{ $heroDisplayDate }}
                    </p>
                </div>

                @if($showCountdown)
                    <div class="cb-countdown-grid" aria-label="Hitung mundur">
                        <div class="cb-cd-box"><span class="cb-cd-val" x-text="days">00</span><span class="cb-cd-lbl">Days</span></div>
                        <div class="cb-cd-box"><span class="cb-cd-val" x-text="hours">00</span><span class="cb-cd-lbl">Hours</span></div>
                        <div class="cb-cd-box"><span class="cb-cd-val" x-text="minutes">00</span><span class="cb-cd-lbl">Minutes</span></div>
                        <div class="cb-cd-box"><span class="cb-cd-val" x-text="seconds">00</span><span class="cb-cd-lbl">Seconds</span></div>
                    </div>
                @endif

                <div class="cb-hero-step cb-hs-3">
                    <a href="{{ $heroCalendarUrl }}" target="_blank" rel="noopener" class="cb-btn cb-btn-primary cb-btn-shimmer" style="padding: 9px 22px; font-size: 12px;">
                        Simpan Tanggal
                    </a>
                </div>

                <div class="cb-hero-step cb-hs-4">
                    <p class="cb-scroll-cue" style="font-size: 10px; letter-spacing: 0.16em; text-transform: uppercase; color: var(--cb-primary); margin: 22px 0 0;">
                        &darr; Scroll Down
                    </p>
                </div>
            </div>
        </section>

        <!-- 2. Quote / Ayat Section -->
        <section id="quote" class="cb-section cb-reveal cb-from-scale" data-reveal="quote" :class="{ 'cb-revealed': revealed['quote'] }" style="padding-top: 20px; padding-bottom: 24px;">
            <div style="background: linear-gradient(145deg, var(--cb-primary), var(--cb-primary-dark)); color: #FFFFFF; border-radius: 18px; padding: 28px 22px; text-align: center; box-shadow: 0 12px 28px rgba(92, 17, 27, 0.24); border: 1px solid rgba(197, 155, 95, 0.45);">
                @if($quotePhotoUrl)
                    <div style="width: 140px; height: 140px; margin: 0 auto 16px; border-radius: 500px 500px 10px 10px; overflow: hidden; border: 3px solid rgba(255,255,255,0.85);">
                        <img src="{{ $quotePhotoUrl }}" alt="{{ $coupleTitle }}" loading="lazy" style="width: 100%; height: 100%; object-fit: cover; display: block;">
                    </div>
                @endif
                <p class="cb-font-heading" style="font-style: italic; font-size: 16.5px; line-height: 1.75; margin: 0 0 12px; color: #FFFFFF;">
                    {{ $quranVerse }}
                </p>
                @if($quranRef !== '')
                    <p style="font-size: 11px; font-weight: 700; letter-spacing: 0.18em; text-transform: uppercase; color: #F1D68A; margin: 0;">
                        {{ $quranRef }}
                    </p>
                @endif
            </div>
        </section>

        <!-- 3. Mempelai Section -->
        <section id="mempelai" class="cb-section cb-section-spy" style="text-align: center;">
            <div class="cb-deco-layer" wire:ignore>
                <img src="{{ $slots['floral_tl'] }}" alt="" class="cb-deco-tl" style="width: 115px; opacity: 0.65;">
                <img src="{{ $slots['floral_br'] }}" alt="" class="cb-deco-br" style="width: 115px; opacity: 0.65;">
            </div>

            <div style="position: relative; z-index: 4;">
                <div class="cb-reveal" data-reveal="mempelai" :class="{ 'cb-revealed': revealed['mempelai'] }">
                    <p class="cb-font-arabic" dir="rtl" style="font-size: 22px; color: var(--cb-primary); margin: 0 0 8px;">
                        بِسْمِ اللَّهِ الرَّحْمَنِ الرَّحِيمِ
                    </p>
                    <p style="font-size: 12.5px; font-weight: 700; color: var(--cb-primary-dark); margin: 0 0 4px;">
                        Assalamu’alaikum Warahmatullahi Wabarakatuh
                    </p>
                    <p style="font-size: 12.5px; color: var(--cb-muted); line-height: 1.7; max-width: 340px; margin: 0 auto 30px;">
                        {{ $openingPrayer }}
                    </p>
                </div>

                <!-- First Person -->
                <div class="cb-reveal cb-from-left cb-d1" data-reveal="mempelai-1" :class="{ 'cb-revealed': revealed['mempelai-1'] }" style="margin-bottom: 18px;">
                    <div class="cb-arch-wrap cb-arch-float">
                        <div class="cb-arch-couple">
                            <img src="{{ $firstPerson['photo'] }}" alt="{{ $firstPerson['full'] }}" loading="lazy">
                        </div>
                        <img src="{{ $slots['floral_br'] }}" alt="" class="cb-arch-flower-bl">
                        <img src="{{ $slots['floral_br'] }}" alt="" class="cb-arch-flower-br">
                    </div>

                    <h3 class="cb-font-heading" style="font-size: 27px; font-weight: 700; color: var(--cb-primary); margin: 4px 0 4px;">
                        {{ $firstPerson['full'] }}
                    </h3>
                    <p style="font-size: 12px; font-weight: 600; color: var(--cb-primary-dark); margin: 0 0 2px;">{{ $firstPerson['childLabel'] }}</p>
                    <p style="font-size: 12.5px; color: var(--cb-muted); line-height: 1.6; margin: 0 0 10px;">{{ $firstPerson['parents'] }}</p>

                    @if($firstPerson['ig'] !== '')
                        <a href="https://www.instagram.com/{{ $firstPerson['ig'] }}" target="_blank" rel="noopener" class="cb-btn cb-btn-outline" style="padding: 6px 14px; font-size: 11px;">
                            @<span>{{ $firstPerson['ig'] }}</span>
                        </a>
                    @endif
                </div>

                <div class="cb-font-heading cb-amp-pulse" style="font-style: italic; font-size: 38px; font-weight: 600; color: var(--cb-primary); margin: 12px 0 18px;">&amp;</div>

                <!-- Second Person -->
                <div class="cb-reveal cb-from-right cb-d2" data-reveal="mempelai-2" :class="{ 'cb-revealed': revealed['mempelai-2'] }">
                    <div class="cb-arch-wrap cb-arch-float">
                        <div class="cb-arch-couple">
                            <img src="{{ $secondPerson['photo'] }}" alt="{{ $secondPerson['full'] }}" loading="lazy">
                        </div>
                        <img src="{{ $slots['floral_br'] }}" alt="" class="cb-arch-flower-bl">
                        <img src="{{ $slots['floral_br'] }}" alt="" class="cb-arch-flower-br">
                    </div>

                    <h3 class="cb-font-heading" style="font-size: 27px; font-weight: 700; color: var(--cb-primary); margin: 4px 0 4px;">
                        {{ $secondPerson['full'] }}
                    </h3>
                    <p style="font-size: 12px; font-weight: 600; color: var(--cb-primary-dark); margin: 0 0 2px;">{{ $secondPerson['childLabel'] }}</p>
                    <p style="font-size: 12.5px; color: var(--cb-muted); line-height: 1.6; margin: 0 0 10px;">{{ $secondPerson['parents'] }}</p>

                    @if($secondPerson['ig'] !== '')
                        <a href="https://www.instagram.com/{{ $secondPerson['ig'] }}" target="_blank" rel="noopener" class="cb-btn cb-btn-outline" style="padding: 6px 14px; font-size: 11px;">
                            @<span>{{ $secondPerson['ig'] }}</span>
                        </a>
                    @endif
                </div>
            </div>
        </section>

        <!-- 4. Acara Section -->
        <section id="acara" class="cb-section cb-section-spy" style="text-align: center;">
            <div class="cb-reveal" data-reveal="acara" :class="{ 'cb-revealed': revealed['acara'] }">
                <p class="cb-eyebrow" style="margin: 0 0 4px;">SAVE THE DATE</p>
                <h2 class="cb-font-heading" style="font-size: 32px; font-weight: 700; color: var(--cb-primary-dark); margin: 0 0 22px;">Rangkaian Acara</h2>
            </div>

            @if($showAkad)
                <div class="cb-card cb-reveal cb-from-scale cb-d1" data-reveal="acara-akad" :class="{ 'cb-revealed': revealed['acara-akad'] }" style="margin-bottom: 18px;">
                    <div class="cb-card-header">
                        <h3 class="cb-font-heading" style="font-size: 24px; font-weight: 700; margin: 0;">Akad Nikah</h3>
                    </div>
                    <div style="padding: 22px 18px;">
                        <p style="font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.14em; color: var(--cb-muted); margin: 0;">{{ $akadMeta['day'] }}</p>
                        <div class="cb-font-heading" style="font-size: 54px; font-weight: 700; line-height: 1; color: var(--cb-primary); margin: 4px 0;">{{ $akadMeta['dayNum'] }}</div>
                        <p style="font-size: 14px; font-weight: 700; color: var(--cb-text); margin: 0 0 12px;">{{ $akadMeta['monthName'] }} {{ $akadMeta['year'] }}</p>

                        <span style="display: inline-block; background: var(--cb-soft); color: var(--cb-primary); font-size: 12px; font-weight: 700; padding: 5px 14px; border-radius: 999px; margin-bottom: 14px;">
                            {{ $akadMeta['time'] }}
                        </span>

                        @if($akadMeta['venue'] !== '' || $akadMeta['address'] !== '')
                            <p style="font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.12em; color: var(--cb-primary); margin: 0 0 4px;">Lokasi Acara</p>
                            @if($akadMeta['venue'] !== '')
                                <p style="font-size: 13.5px; font-weight: 700; color: var(--cb-text); margin: 0 0 2px;">{{ $akadMeta['venue'] }}</p>
                            @endif
                            <p style="font-size: 12.5px; color: var(--cb-muted); line-height: 1.55; margin: 0 0 16px;">{{ $akadMeta['address'] }}</p>
                        @endif

                        <div style="display: flex; gap: 8px; justify-content: center; flex-wrap: wrap;">
                            @if($akadMeta['mapsUrl'] !== '')
                                <a href="{{ $akadMeta['mapsUrl'] }}" target="_blank" rel="noopener" class="cb-btn cb-btn-primary" style="padding: 9px 18px; font-size: 12px;">Google Maps</a>
                            @endif
                            <a href="{{ $akadMeta['calendarUrl'] }}" target="_blank" rel="noopener" class="cb-btn cb-btn-outline" style="padding: 9px 18px; font-size: 12px;">Simpan Tanggal</a>
                        </div>
                    </div>
                </div>
            @endif

            @if($showResepsi)
                <div class="cb-card cb-reveal cb-from-scale cb-d2" data-reveal="acara-resepsi" :class="{ 'cb-revealed': revealed['acara-resepsi'] }" style="margin-bottom: 18px;">
                    <div class="cb-card-header">
                        <h3 class="cb-font-heading" style="font-size: 24px; font-weight: 700; margin: 0;">Resepsi</h3>
                    </div>
                    <div style="padding: 22px 18px;">
                        <p style="font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.14em; color: var(--cb-muted); margin: 0;">{{ $resepsiMeta['day'] }}</p>
                        <div class="cb-font-heading" style="font-size: 54px; font-weight: 700; line-height: 1; color: var(--cb-primary); margin: 4px 0;">{{ $resepsiMeta['dayNum'] }}</div>
                        <p style="font-size: 14px; font-weight: 700; color: var(--cb-text); margin: 0 0 12px;">{{ $resepsiMeta['monthName'] }} {{ $resepsiMeta['year'] }}</p>

                        <span style="display: inline-block; background: var(--cb-soft); color: var(--cb-primary); font-size: 12px; font-weight: 700; padding: 5px 14px; border-radius: 999px; margin-bottom: 14px;">
                            {{ $resepsiMeta['time'] }}
                        </span>

                        @if($resepsiMeta['venue'] !== '' || $resepsiMeta['address'] !== '')
                            <p style="font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.12em; color: var(--cb-primary); margin: 0 0 4px;">Lokasi Acara</p>
                            @if($resepsiMeta['venue'] !== '')
                                <p style="font-size: 13.5px; font-weight: 700; color: var(--cb-text); margin: 0 0 2px;">{{ $resepsiMeta['venue'] }}</p>
                            @endif
                            <p style="font-size: 12.5px; color: var(--cb-muted); line-height: 1.55; margin: 0 0 16px;">{{ $resepsiMeta['address'] }}</p>
                        @endif

                        <div style="display: flex; gap: 8px; justify-content: center; flex-wrap: wrap;">
                            @if($resepsiMeta['mapsUrl'] !== '')
                                <a href="{{ $resepsiMeta['mapsUrl'] }}" target="_blank" rel="noopener" class="cb-btn cb-btn-primary" style="padding: 9px 18px; font-size: 12px;">Google Maps</a>
                            @endif
                            <a href="{{ $resepsiMeta['calendarUrl'] }}" target="_blank" rel="noopener" class="cb-btn cb-btn-outline" style="padding: 9px 18px; font-size: 12px;">Simpan Tanggal</a>
                        </div>
                    </div>
                </div>
            @endif

            @if($showUnduhMantu && $unduhMeta)
                <div class="cb-card cb-reveal cb-d2" data-reveal="acara-unduh" :class="{ 'cb-revealed': revealed['acara-unduh'] }" style="margin-bottom: 18px;">
                    <div class="cb-card-header"><h3 class="cb-font-heading" style="font-size: 22px; font-weight: 700; margin: 0;">Ngunduh Mantu</h3></div>
                    <div style="padding: 20px 18px;">
                        <p style="font-size: 13px; font-weight: 700; color: var(--cb-primary); margin: 0 0 6px;">{{ $unduhMeta['day'] }}, {{ $unduhMeta['dayNum'] }} {{ $unduhMeta['monthName'] }} {{ $unduhMeta['year'] }}</p>
                        <p style="font-size: 12px; color: var(--cb-muted); margin: 0 0 12px;">{{ $unduhMeta['time'] }} &bull; {{ $unduhMeta['venue'] }}</p>
                        @if($unduhMeta['mapsUrl'] !== '')
                            <a href="{{ $unduhMeta['mapsUrl'] }}" target="_blank" rel="noopener" class="cb-btn cb-btn-primary" style="padding: 8px 18px; font-size: 12px;">Google Maps</a>
                        @endif
                    </div>
                </div>
            @endif

            @if($showStreaming)
                <div class="cb-card cb-reveal cb-d3" data-reveal="acara-stream" :class="{ 'cb-revealed': revealed['acara-stream'] }" style="padding: 22px 18px; margin-bottom: 18px;">
                    <h3 class="cb-font-heading" style="font-size: 24px; font-weight: 700; color: var(--cb-primary); margin: 0 0 6px;">Live Streaming</h3>
                    <p style="font-size: 12.5px; color: var(--cb-muted); line-height: 1.6; margin: 0 0 14px;">Saksikan momen pernikahan kami secara virtual melalui tautan di bawah ini.</p>
                    <a href="{{ $streamingLink }}" target="_blank" rel="noopener" class="cb-btn cb-btn-primary" style="padding: 9px 20px; font-size: 12px;">Lihat Live Streaming</a>
                </div>
            @endif

            @if($dressCode !== '' || $turutMengundang !== '')
                <div class="cb-card cb-reveal cb-d3" data-reveal="acara-note" :class="{ 'cb-revealed': revealed['acara-note'] }" style="padding: 20px 18px;">
                    @if($dressCode !== '')
                        <h3 class="cb-font-heading" style="font-size: 22px; font-weight: 700; color: var(--cb-primary); margin: 0 0 6px;">Dresscode</h3>
                        <p style="font-size: 12.5px; color: var(--cb-muted); line-height: 1.6; margin: 0 0 {{ $turutMengundang !== '' ? '14px' : '0' }};">{{ $dressCode }}</p>
                    @endif
                    @if($turutMengundang !== '')
                        <h3 class="cb-font-heading" style="font-size: 22px; font-weight: 700; color: var(--cb-primary); margin: 0 0 6px;">Turut Mengundang</h3>
                        <p style="font-size: 12.5px; color: var(--cb-muted); line-height: 1.65; margin: 0;">{!! nl2br(e($turutMengundang)) !!}</p>
                    @endif
                </div>
            @endif
        </section>

        <!-- 5. Love Story -->
        @if($showLoveStory)
            <section id="story" class="cb-section">
                <div class="cb-reveal" data-reveal="story" :class="{ 'cb-revealed': revealed['story'] }" style="text-align: center; margin-bottom: 22px;">
                    <p class="cb-eyebrow" style="margin: 0 0 4px;">PERJALANAN CINTA</p>
                    <h2 class="cb-font-heading" style="font-size: 32px; font-weight: 700; color: var(--cb-primary-dark); margin: 0;">Love Story</h2>
                </div>

                @foreach($loveStories as $sIdx => $story)
                    <div class="cb-card cb-reveal {{ $sIdx % 2 === 0 ? 'cb-from-left' : 'cb-from-right' }}"
                         data-reveal="story-{{ $sIdx }}"
                         :class="{ 'cb-revealed': revealed['story-{{ $sIdx }}'] }"
                         style="padding: 18px; margin-bottom: 14px;">
                        @if(!empty($story['image']))
                            <img src="{{ $story['image'] }}" alt="{{ $story['title'] }}" loading="lazy" style="width: 100%; aspect-ratio: 4/3; object-fit: cover; border-radius: 10px; margin-bottom: 12px; display: block;">
                        @endif
                        <span style="display: inline-block; background: var(--cb-primary); color: #FFFFFF; font-size: 10.5px; font-weight: 700; letter-spacing: 0.08em; padding: 3px 12px; border-radius: 999px; margin-bottom: 6px;">
                            {{ $story['date'] }}
                        </span>
                        <h3 class="cb-font-heading" style="font-size: 21px; font-weight: 700; color: var(--cb-primary-dark); margin: 0 0 4px;">{{ $story['title'] }}</h3>
                        <p style="font-size: 12.5px; color: var(--cb-muted); line-height: 1.65; margin: 0;">{{ $story['description'] }}</p>
                    </div>
                @endforeach
            </section>
        @endif

        <!-- 6. Our Moments / Galeri -->
        @if($showGallery)
            <section id="galeri" class="cb-section cb-section-spy">
                <div class="cb-reveal" data-reveal="galeri" :class="{ 'cb-revealed': revealed['galeri'] }" style="text-align: center; margin-bottom: 22px;">
                    <p class="cb-eyebrow" style="margin: 0 0 4px;">POTRET BAHAGIA</p>
                    <h2 class="cb-font-heading" style="font-size: 32px; font-weight: 700; color: var(--cb-primary-dark); margin: 0;">Our Moments</h2>
                </div>

                <div class="cb-gal-grid">
                    @foreach($galleryPhotos as $idx => $photo)
                        @php $delayCls = ['cb-d1', 'cb-d2', 'cb-d3'][$idx % 3]; @endphp
                        <div class="cb-gal-item cb-reveal cb-from-scale {{ $delayCls }}"
                             data-reveal="gal-{{ $idx }}"
                             :class="{ 'cb-revealed': revealed['gal-{{ $idx }}'] }"
                             style="{{ ($idx === 0 && count($galleryPhotos) % 2 === 1) ? 'grid-column: span 2; aspect-ratio: 16/10;' : '' }}"
                             @click="openLightbox({{ $idx }})">
                            <img src="{{ $photo }}" alt="Galeri {{ $idx + 1 }}" loading="lazy" decoding="async">
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        <!-- 7. Wedding Gift & Kado -->
        @if($showGift)
            <section id="kado" class="cb-section cb-section-spy">
                <div class="cb-reveal" data-reveal="kado" :class="{ 'cb-revealed': revealed['kado'] }" style="text-align: center; margin-bottom: 22px;">
                    <p class="cb-eyebrow" style="margin: 0 0 4px;">TANDA KASIH</p>
                    <h2 class="cb-font-heading" style="font-size: 32px; font-weight: 700; color: var(--cb-primary-dark); margin: 0 0 8px;">Wedding Gift</h2>
                    <p style="font-size: 12.5px; color: var(--cb-muted); line-height: 1.65; margin: 0 auto; max-width: 340px;">
                        Doa restu Anda merupakan karunia yang sangat berarti bagi kami. Dan jika memberi adalah ungkapan tanda kasih, Anda dapat memberi melalui rekening di bawah ini.
                    </p>
                </div>

                @foreach($bankAccounts as $bIdx => $account)
                    @php
                        $accNum = (string) ($account['account_number'] ?? '');
                        $accName = (string) ($account['account_name'] ?? '');
                        $bankName = strtoupper((string) ($account['bank'] ?? 'BANK'));
                        $bankLogo = $resolveBankLogo($bankName);
                    @endphp
                    <div class="cb-atm-card cb-reveal cb-from-scale cb-d1" data-reveal="kado-{{ $bIdx }}" :class="{ 'cb-revealed': revealed['kado-{{ $bIdx }}'] }">
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px;">
                            <img src="{{ $slots['atm_chip'] }}" alt="Chip" loading="lazy" style="width: 36px; height: auto;">
                            @if($bankLogo)
                                <img src="{{ $bankLogo }}" alt="{{ $bankName }}" loading="lazy" style="height: 24px; width: auto;">
                            @else
                                <span style="font-size: 13px; font-weight: 700; color: var(--cb-primary); border: 1px solid var(--cb-primary); padding: 2px 10px; border-radius: 6px;">{{ $bankName }}</span>
                            @endif
                        </div>

                        <p style="font-size: 11px; color: var(--cb-muted); margin: 0 0 2px;">No Rekening</p>
                        <p style="font-size: 20px; font-weight: 700; letter-spacing: 0.08em; color: var(--cb-primary-dark); margin: 0 0 10px; font-variant-numeric: tabular-nums;">{{ $accNum }}</p>

                        <div style="display: flex; align-items: center; justify-content: space-between; gap: 10px; flex-wrap: wrap;">
                            <div>
                                <p style="font-size: 10.5px; color: var(--cb-muted); margin: 0;">Atas Nama</p>
                                <p style="font-size: 13.5px; font-weight: 700; color: var(--cb-text); margin: 0;">{{ $accName }}</p>
                            </div>
                            @if($accNum !== '')
                                <button type="button" @click="copyText(@js($accNum), 'No. Rekening {{ $bankName }}')" class="cb-btn cb-btn-primary" style="padding: 8px 16px; font-size: 11.5px;">
                                    Salin
                                </button>
                            @endif
                        </div>
                    </div>
                @endforeach

                @if(trim((string) $giftAddress) !== '')
                    <div class="cb-card cb-reveal cb-d2" data-reveal="kado-addr" :class="{ 'cb-revealed': revealed['kado-addr'] }" style="padding: 20px 18px; text-align: center;">
                        <h3 class="cb-font-heading" style="font-size: 22px; font-weight: 700; color: var(--cb-primary); margin: 0 0 8px;">Kirim Kado</h3>
                        <p style="font-size: 12.5px; font-weight: 700; color: var(--cb-text); margin: 0 0 4px;">{{ $giftRecipient }}</p>
                        <p style="font-size: 12.5px; color: var(--cb-muted); line-height: 1.6; margin: 0 0 14px;">{{ $giftAddress }}</p>
                        <button type="button" @click="copyText(@js($giftAddress), 'Alamat kado')" class="cb-btn cb-btn-outline" style="padding: 8px 18px; font-size: 12px;">
                            Salin Alamat
                        </button>
                    </div>
                @endif
            </section>
        @endif

        <!-- 8. Ucapan & RSVP -->
        @if($showRsvp)
            <section id="ucapan" class="cb-section cb-section-spy cb-reveal" data-reveal="ucapan" :class="{ 'cb-revealed': isOpened || revealed['ucapan'] }">
                <div style="text-align: center; margin-bottom: 22px;">
                    <p class="cb-eyebrow" style="margin: 0 0 4px;">KEHADIRAN &amp; DOA ({{ $wishesCount }})</p>
                    <h2 class="cb-font-heading" style="font-size: 32px; font-weight: 700; color: var(--cb-primary-dark); margin: 0 0 4px;">Ucapan &amp; RSVP</h2>
                    <p style="font-size: 12.5px; color: var(--cb-muted); margin: 0;">Berikan doa dan ucapan terbaik untuk kami.</p>
                </div>

                <div class="cb-card" style="padding: 22px 18px; margin-bottom: 18px;">
                    @if (session()->has('rsvp_success'))
                        <div style="background: #ECFDF5; border: 1px solid #10B981; color: #065F46; padding: 10px 14px; border-radius: 10px; margin-bottom: 14px; font-size: 12.5px; text-align: center;">
                            {{ session('rsvp_success') }}
                        </div>
                    @endif

                    <form wire:submit.prevent="submitRSVP">
                        <input type="text" wire:model="rsvpName" class="cb-input" placeholder="Nama Anda" required>
                        @error('rsvpName') <span style="display: block; color: #B91C1C; font-size: 11px; margin: -8px 0 8px;">{{ $message }}</span> @enderror

                        <select wire:model.live="rsvpStatus" class="cb-select" required>
                            <option value="confirmed">Hadir</option>
                            <option value="declined">Tidak Hadir</option>
                            <option value="tentative">Masih Ragu</option>
                        </select>
                        @error('rsvpStatus') <span style="display: block; color: #B91C1C; font-size: 11px; margin: -8px 0 8px;">{{ $message }}</span> @enderror

                        @if($rsvpStatus === 'confirmed')
                            <select wire:model="rsvpGuests" class="cb-select">
                                @for($g = 1; $g <= 5; $g++)
                                    <option value="{{ $g }}">{{ $g }} Orang</option>
                                @endfor
                            </select>
                            @error('rsvpGuests') <span style="display: block; color: #B91C1C; font-size: 11px; margin: -8px 0 8px;">{{ $message }}</span> @enderror
                        @endif

                        <textarea rows="3" wire:model="rsvpMessage" class="cb-textarea" placeholder="Tulis doa dan ucapan..." required></textarea>
                        @error('rsvpMessage') <span style="display: block; color: #B91C1C; font-size: 11px; margin: -8px 0 8px;">{{ $message }}</span> @enderror

                        <button type="submit" class="cb-btn cb-btn-primary" style="width: 100%;">
                            <span wire:loading.remove wire:target="submitRSVP">Kirim Ucapan</span>
                            <span wire:loading wire:target="submitRSVP">Mengirim...</span>
                        </button>
                    </form>
                </div>

                @forelse($wishesList as $wish)
                    <div class="cb-card cb-wish-card" style="padding: 14px 16px; margin-bottom: 10px;" wire:key="cb-wish-{{ $wish->id }}">
                        <div style="display: flex; align-items: flex-start; gap: 12px;">
                            <div style="width: 34px; height: 34px; border-radius: 50%; background: var(--cb-primary); color: #FFFFFF; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 13px; flex-shrink: 0;">
                                {{ strtoupper(mb_substr(trim((string) ($wish->name ?? 'T')), 0, 1)) }}
                            </div>
                            <div style="flex: 1; min-width: 0;">
                                <div style="display: flex; align-items: center; justify-content: space-between; gap: 6px; flex-wrap: wrap; margin-bottom: 4px;">
                                    <strong style="font-size: 13.5px; color: var(--cb-primary-dark);">{{ $wish->name }}</strong>
                                    @if($wish->attendance_status === 'confirmed' || $wish->attendance_status === 'present')
                                        <span style="font-size: 10px; font-weight: 700; padding: 2px 8px; border-radius: 999px; background: #ECFDF5; color: #065F46;">Hadir ({{ $wish->pax ?? 1 }})</span>
                                    @elseif($wish->attendance_status === 'declined' || $wish->attendance_status === 'notpresent')
                                        <span style="font-size: 10px; font-weight: 700; padding: 2px 8px; border-radius: 999px; background: #FEF2F2; color: #991B1B;">Tidak Hadir</span>
                                    @else
                                        <span style="font-size: 10px; font-weight: 700; padding: 2px 8px; border-radius: 999px; background: #FFFBEB; color: #92400E;">Masih Ragu</span>
                                    @endif
                                </div>
                                <p style="font-size: 12.5px; color: var(--cb-text); line-height: 1.55; margin: 0 0 4px; word-break: break-word;">{{ $wish->message }}</p>
                                <p style="font-size: 10px; color: var(--cb-muted); margin: 0;">{{ $wish->created_at ? $wish->created_at->diffForHumans() : 'Baru saja' }}</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="cb-card cb-wish-card" style="padding: 20px; text-align: center;">
                        <p style="font-size: 12.5px; color: var(--cb-muted); margin: 0;">Belum ada ucapan. Jadilah yang pertama memberikan doa restu!</p>
                    </div>
                @endforelse
            </section>
        @endif

        <!-- 9. Closing & ExoInvite Footer -->
        <footer class="cb-section cb-reveal cb-from-scale" data-reveal="closing" :class="{ 'cb-revealed': revealed['closing'] }" style="text-align: center; padding-top: 24px; padding-bottom: 112px;">
            <p class="cb-eyebrow" style="margin: 0 0 8px;">TERIMA KASIH</p>
            <p style="font-size: 12.5px; color: var(--cb-muted); line-height: 1.7; max-width: 330px; margin: 0 auto 14px;">
                Merupakan suatu kebahagiaan dan kehormatan bagi kami apabila Bapak/Ibu/Saudara/i berkenan hadir serta memberikan doa restu.
            </p>
            <h2 class="cb-font-script" style="font-size: 36px; font-weight: 400; color: var(--cb-primary); margin: 0 0 24px;">
                {{ $coupleTitle }}
            </h2>

            <div style="border-top: 1px solid var(--cb-border); padding-top: 18px;">
                <a href="{{ url('/') }}" style="display: inline-flex; align-items: center; text-decoration: none;">
                    <img src="{{ asset('assets/svg/logo.svg') }}" alt="ExoInvite" loading="lazy" style="width: 120px; height: auto; opacity: 0.78;">
                </a>
            </div>
        </footer>
    </main>

    <!-- Floating Music & Auto-Scroll Buttons -->
    <div class="cb-fab-stack" x-show="isOpened" x-transition.opacity style="display: none;">
        @if($musicSrc !== '')
            <button type="button" class="cb-fab-btn" :class="{ 'is-playing': isPlaying }" @click="toggleMusic()" :aria-label="isPlaying ? 'Jeda Musik' : 'Putar Musik'" :title="isPlaying ? 'Jeda Musik' : 'Putar Musik'">
                <svg x-show="isPlaying" style="width: 17px; height: 17px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 19V6l12-3v13M9 19c0 1.1-.9 2-2 2s-2-.9-2-2 .9-2 2-2 2 .9 2 2zm12-3c0 1.1-.9 2-2 2s-2-.9-2-2 .9-2 2-2 2 .9 2 2z"/>
                </svg>
                <svg x-show="!isPlaying" style="width: 17px; height: 17px; opacity: 0.65;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 19V6l12-3v13M3 3l18 18"/>
                </svg>
            </button>
        @endif

        <button type="button" class="cb-fab-btn" :class="{ 'is-active': autoScrolling }" @click="toggleAutoScroll()" :aria-label="autoScrolling ? 'Hentikan Auto Scroll' : 'Mulai Auto Scroll'" :title="autoScrolling ? 'Hentikan Auto Scroll' : 'Auto Scroll'">
            <svg x-show="!autoScrolling" style="width: 16px; height: 16px;" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14m-6-6l6 6 6-6"/>
            </svg>
            <svg x-show="autoScrolling" style="width: 16px; height: 16px;" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 5v14M14 5v14"/>
            </svg>
        </button>
    </div>

    <!-- Bottom Anchor Dock -->
    <nav class="cb-dock-wrap" x-show="isOpened" x-transition.opacity aria-label="Navigasi Undangan" style="display: none;">
        <div class="cb-dock">
            <a href="#hero" @click.prevent="scrollTo('#hero')" class="cb-dock-item" :class="{ 'is-active': activeSection === 'hero' }" title="Home">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 12l9-9 9 9M5 10v10h14V10"/></svg>
            </a>
            <a href="#mempelai" @click.prevent="scrollTo('#mempelai')" class="cb-dock-item" :class="{ 'is-active': activeSection === 'mempelai' }" title="Mempelai">
                <svg fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
            </a>
            <a href="#acara" @click.prevent="scrollTo('#acara')" class="cb-dock-item" :class="{ 'is-active': activeSection === 'acara' }" title="Acara">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
            </a>
            @if($showGallery)
                <a href="#galeri" @click.prevent="scrollTo('#galeri')" class="cb-dock-item" :class="{ 'is-active': activeSection === 'galeri' }" title="Galeri">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="14" rx="2"/><circle cx="8.5" cy="10.5" r="1.5"/><path d="M21 15l-5-5L5 19"/></svg>
                </a>
            @endif
            @if($showGift)
                <a href="#kado" @click.prevent="scrollTo('#kado')" class="cb-dock-item" :class="{ 'is-active': activeSection === 'kado' }" title="Kado">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="20 12 20 22 4 22 4 12"/><rect x="2" y="7" width="20" height="5"/><line x1="12" y1="22" x2="12" y2="7"/></svg>
                </a>
            @endif
            @if($showRsvp)
                <a href="#ucapan" @click.prevent="scrollTo('#ucapan')" class="cb-dock-item" :class="{ 'is-active': activeSection === 'ucapan' }" title="Ucapan">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 11.5a8.38 8.38 0 01-.9 3.8 8.5 8.5 0 01-7.6 4.7 8.38 8.38 0 01-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 01-.9-3.8 8.5 8.5 0 014.7-7.6 8.38 8.38 0 013.8-.9h.5a8.48 8.48 0 018 8v.5z"/></svg>
                </a>
            @endif
        </div>
    </nav>

    <!-- Fullscreen Gallery Lightbox -->
    @if($showGallery)
        <div x-show="lightboxOpen"
             x-transition.opacity
             @keydown.window.escape="closeLightbox()"
             @keydown.window.arrow-left="if (lightboxOpen) lightboxPrev()"
             @keydown.window.arrow-right="if (lightboxOpen) lightboxNext()"
             @click.self="closeLightbox()"
             style="display: none; position: fixed; inset: 0; z-index: 950; background: rgba(30, 5, 9, 0.92); backdrop-filter: blur(6px); align-items: center; justify-content: center; padding: 24px;"
             :style="{ display: lightboxOpen ? 'flex' : 'none' }">
            <button type="button" @click="closeLightbox()" aria-label="Tutup" style="position: absolute; top: 18px; right: 18px; width: 40px; height: 40px; border-radius: 50%; background: rgba(255,255,255,0.16); border: 1px solid rgba(197,155,95,0.6); color: #FFF; font-size: 20px; cursor: pointer;">&times;</button>
            <button type="button" @click="lightboxPrev()" aria-label="Sebelumnya" style="position: absolute; left: 14px; width: 40px; height: 40px; border-radius: 50%; background: var(--cb-primary); border: 1px solid var(--cb-gold); color: #FFF; cursor: pointer;">&#8249;</button>
            <img :src="galleryPhotos[lightboxIndex] || ''" alt="Preview Galeri" style="max-width: 90vw; max-height: 84vh; border-radius: 10px; border: 2px solid var(--cb-gold); object-fit: contain; box-shadow: 0 20px 50px rgba(0,0,0,0.75);">
            <button type="button" @click="lightboxNext()" aria-label="Berikutnya" style="position: absolute; right: 14px; width: 40px; height: 40px; border-radius: 50%; background: var(--cb-primary); border: 1px solid var(--cb-gold); color: #FFF; cursor: pointer;">&#8250;</button>
        </div>
    @endif
    <div style="display: none;" id="cb-gallery-data" data-photos="{{ json_encode($galleryPhotos, JSON_UNESCAPED_SLASHES) }}"></div>

    <!-- Toast Notification -->
    <div x-show="toastShow" x-transition.opacity
         style="display: none; position: fixed; top: 20px; right: 18px; z-index: 960; background: #5C111B; color: #F1D68A; border-left: 3px solid var(--cb-gold); border-radius: 10px; padding: 10px 16px; font-size: 12.5px; font-weight: 600; box-shadow: 0 10px 28px rgba(0,0,0,0.4);">
        <span x-text="toastMessage">Tersalin!</span>
    </div>
</div>

@once
    @push('alpine-components')
        <script>
            window.crimsonBotanicalTheme = function (config) {
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

                    activeSection: 'hero',
                    revealed: {},
                    revealObserver: null,
                    spyObserver: null,

                    galleryPhotos: [],
                    lightboxOpen: false,
                    lightboxIndex: 0,

                    toastShow: false,
                    toastMessage: '',
                    toastTimer: null,

                    init() {
                        document.body.classList.add('cb-locked');

                        const galEl = document.getElementById('cb-gallery-data');
                        if (galEl && galEl.dataset.photos) {
                            try { this.galleryPhotos = JSON.parse(galEl.dataset.photos); } catch (e) {}
                        }

                        this.updateCountdown();
                        this.countdownTimer = setInterval(() => this.updateCountdown(), 1000);
                        this.initScrollListeners();

                        if (window.Livewire && typeof window.Livewire.hook === 'function') {
                            window.Livewire.hook('morph.updated', () => {
                                if (this.isOpened) {
                                    document.body.classList.remove('cb-locked');
                                    this.markRevealed('ucapan');
                                    this.observeReveals();
                                }
                            });
                        }
                    },

                    markRevealed(key) {
                        if (key && !this.revealed[key]) {
                            this.revealed[key] = true;
                        }
                    },

                    openInvitation() {
                        if (this.isOpened) return;
                        this.isOpened = true;
                        document.body.classList.remove('cb-locked');
                        this.markRevealed('ucapan');

                        if (config.hasMusic) {
                            this.playMusic();
                        }

                        setTimeout(() => this.initObservers(), 180);
                    },

                    updateCountdown() {
                        const diff = (this.targetTimestamp * 1000) - Date.now();
                        if (diff <= 0) {
                            this.days = '00'; this.hours = '00'; this.minutes = '00'; this.seconds = '00';
                            return;
                        }
                        this.days = String(Math.floor(diff / 86400000)).padStart(2, '0');
                        this.hours = String(Math.floor((diff % 86400000) / 3600000)).padStart(2, '0');
                        this.minutes = String(Math.floor((diff % 3600000) / 60000)).padStart(2, '0');
                        this.seconds = String(Math.floor((diff % 60000) / 1000)).padStart(2, '0');
                    },

                    observeReveals() {
                        const els = document.querySelectorAll('[data-reveal]');
                        if (!('IntersectionObserver' in window)) {
                            els.forEach((el) => this.markRevealed(el.dataset.reveal));
                            return;
                        }
                        if (!this.revealObserver) {
                            this.revealObserver = new IntersectionObserver((entries) => {
                                entries.forEach((entry) => {
                                    if (entry.isIntersecting) {
                                        this.markRevealed(entry.target.dataset.reveal);
                                        this.revealObserver.unobserve(entry.target);
                                    }
                                });
                            }, { threshold: 0.1 });
                        }
                        els.forEach((el) => {
                            const key = el.dataset.reveal;
                            if (key && !this.revealed[key]) {
                                this.revealObserver.observe(el);
                            }
                        });
                    },

                    initObservers() {
                        this.observeReveals();

                        if ('IntersectionObserver' in window) {
                            if (!this.spyObserver) {
                                this.spyObserver = new IntersectionObserver((entries) => {
                                    entries.forEach((entry) => {
                                        if (entry.isIntersecting && entry.target.id) {
                                            this.activeSection = entry.target.id;
                                        }
                                    });
                                }, { rootMargin: '-35% 0px -45% 0px', threshold: 0.01 });
                            }
                            document.querySelectorAll('.cb-section-spy').forEach((el) => this.spyObserver.observe(el));
                        }
                    },

                    initScrollListeners() {
                        const cancelAuto = (e) => {
                            if (!this.autoScrolling) return;
                            if (e.target && e.target.closest && (e.target.closest('.cb-fab-stack') || e.target.closest('.cb-dock-wrap'))) {
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
                        if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    },

                    playMusic() {
                        const audio = document.getElementById('cb-song');
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
                            const audio = document.getElementById('cb-song');
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
                        const audio = document.getElementById('cb-song');
                        if (audio) {
                            audio.pause();
                            this.isPlaying = false;
                        }
                    },

                    toggleMusic() {
                        if (this.isPlaying) this.pauseMusic();
                        else this.playMusic();
                    },

                    toggleAutoScroll() {
                        this.autoScrolling = !this.autoScrolling;
                        if (this.autoScrolling) this.startScroll();
                        else this.stopScroll();
                    },

                    async requestWakeLock() {
                        if (!this.autoScrolling || !('wakeLock' in navigator) || !navigator.wakeLock) return;
                        if (this.wakeLockSentinel && !this.wakeLockSentinel.released) return;
                        try {
                            const sentinel = await navigator.wakeLock.request('screen');
                            if (!this.autoScrolling) { sentinel.release().catch(() => {}); return; }
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

                    openLightbox(idx) {
                        this.lightboxIndex = idx;
                        this.lightboxOpen = true;
                    },

                    closeLightbox() {
                        this.lightboxOpen = false;
                    },

                    lightboxNext() {
                        if (this.galleryPhotos.length <= 0) return;
                        this.lightboxIndex = (this.lightboxIndex + 1) % this.galleryPhotos.length;
                    },

                    lightboxPrev() {
                        if (this.galleryPhotos.length <= 0) return;
                        this.lightboxIndex = (this.lightboxIndex - 1 + this.galleryPhotos.length) % this.galleryPhotos.length;
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
                        this.toastTimer = setTimeout(() => { this.toastShow = false; }, 2600);
                    }
                };
            };
        </script>
    @endpush
@endonce
