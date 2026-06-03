{{-- Section: Opening Slide --}}
@php
    $openingBg = $invitation->cover_image ? img_url($invitation->cover_image) : 'https://images.unsplash.com/photo-1519741497674-611481863552?w=600';
    $overlayGradient = $sc['overlay_gradient'] ?? 'linear-gradient(to bottom, rgba(93,7,31,0.6), rgba(93,7,31,0.9))';
@endphp

<div id="slide-{{ $slideIndex }}" data-index="{{ $slideIndex }}" class="satumomen_slide">
    <div class="container-mobile" style="background-image: url('{{ $openingBg }}');">
        <div class="frame">
            @if(!empty($frame['left']))<img class="frame-lc animate__animated animate__fadeInLeft animate__slower" src="{{ asset($frame['left']) }}" alt="frame">@endif
            @if(!empty($frame['right']))<img class="frame-rc animate__animated animate__fadeInRight animate__slower" src="{{ asset($frame['right']) }}" alt="frame">@endif
            @if(!empty($frame['tl']))<img class="frame-tl animate__animated animate__fadeInTopLeft animate__slow" src="{{ asset($frame['tl']) }}" alt="frame">@endif
            @if(!empty($frame['tr']))<img class="frame-tr animate__animated animate__fadeInTopRight animate__slow" src="{{ asset($frame['tr']) }}" alt="frame">@endif
            @if(!empty($frame['bl']))<img class="frame-bl animate__animated animate__fadeInBottomLeft animate__slow" src="{{ asset($frame['bl']) }}" alt="frame">@endif
            @if(!empty($frame['br']))<img class="frame-br animate__animated animate__fadeInBottomRight animate__slow" src="{{ asset($frame['br']) }}" alt="frame">@endif
        </div>
        <div style="position: absolute; inset:0; background: {{ $overlayGradient }}; z-index:1;"></div>

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
