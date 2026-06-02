{{-- Section: Cover Overlay --}}
{{-- Variables: $invitation, $sc (section config), $frame (frame config) --}}
@php
    $coverBg = $invitation->cover_image ? img_url($invitation->cover_image) : asset('assets/themes/adat-bone/bg_bone.webp');
    $overlayGradient = $sc['overlay_gradient'] ?? 'linear-gradient(to bottom, rgba(93,7,31,0.75) 0%, rgba(93,7,31,0.45) 35%, rgba(93,7,31,0.35) 55%, rgba(93,7,31,0.92) 85%, rgba(93,7,31,0.98) 100%)';
    $titleText = $sc['title_text'] ?? 'The Wedding Of';
    $buttonText = $sc['button_text'] ?? 'Buka Undangan';
@endphp

<div x-show="!opened" x-transition.opacity.duration.1000ms id="cover-overlay" class="container-mobile" style="position: absolute; left: 50%; transform: translateX(-50%); text-shadow: 0 2px 8px rgba(0,0,0,0.9), 0 0 20px rgba(93,7,31,0.8), 0 0 4px rgba(0,0,0,1); background-image: url('{{ $coverBg }}');">
    <div class="frame">
        @if(!empty($frame['left']))<img class="frame-lc animate__animated animate__fadeInLeft animate__slower" src="{{ asset($frame['left']) }}" alt="frame" style="width: auto;">@endif
        @if(!empty($frame['right']))<img class="frame-rc animate__animated animate__fadeInRight animate__slower" src="{{ asset($frame['right']) }}" alt="frame" style="width: auto;">@endif
        @if(!empty($frame['tl']))<img class="frame-tl animate__animated animate__fadeInTopLeft animate__slow" src="{{ asset($frame['tl']) }}" alt="frame">@endif
        @if(!empty($frame['tr']))<img class="frame-tr animate__animated animate__fadeInTopRight animate__slow" src="{{ asset($frame['tr']) }}" alt="frame">@endif
        @if(!empty($frame['bl']))<img class="frame-bl animate__animated animate__fadeInBottomLeft animate__slow" src="{{ asset($frame['bl']) }}" alt="frame">@endif
        @if(!empty($frame['br']))<img class="frame-br animate__animated animate__fadeInBottomRight animate__slow" src="{{ asset($frame['br']) }}" alt="frame">@endif
    </div>

    <div style="position: absolute; inset:0; background: {{ $overlayGradient }}; z-index:1;"></div>

    <div class="slide-content" style="justify-content: space-between;">
        <div class="text-center w-full mt-10">
            <div class="mb-2 text-center animate__animated animate__fadeInDown animate__slower" style="letter-spacing:3px; text-transform: uppercase; font-size: 13px;">{{ $titleText }}</div>
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
            <button type="button" @click="open()" class="btn-primary animate__animated animate__fadeInUp animate__slow shadow-xl" style="max-width: 220px;">{{ $buttonText }}</button>
        </div>
    </div>
</div>
