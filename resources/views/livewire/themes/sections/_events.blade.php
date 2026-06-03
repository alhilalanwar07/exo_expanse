{{-- Section: Events (Akad & Resepsi) --}}
<div id="slide-{{ $slideIndex }}" data-index="{{ $slideIndex }}" class="satumomen_slide">
    <div class="container-mobile">
        <div class="frame">
            @if(!empty($frame['left']))<img class="frame-lc animate__animated animate__fadeInLeft animate__slower" src="{{ asset($frame['left']) }}" alt="frame">@endif
            @if(!empty($frame['right']))<img class="frame-rc animate__animated animate__fadeInRight animate__slower" src="{{ asset($frame['right']) }}" alt="frame">@endif
            @if(!empty($frame['tl']))<img class="frame-tl animate__animated animate__fadeInTopLeft animate__slow" src="{{ asset($frame['tl']) }}" alt="frame">@endif
            @if(!empty($frame['tr']))<img class="frame-tr animate__animated animate__fadeInTopRight animate__slow" src="{{ asset($frame['tr']) }}" alt="frame">@endif
            @if(!empty($frame['bl']))<img class="frame-bl animate__animated animate__fadeInBottomLeft animate__slow" src="{{ asset($frame['bl']) }}" alt="frame">@endif
            @if(!empty($frame['br']))<img class="frame-br animate__animated animate__fadeInBottomRight animate__slow" src="{{ asset($frame['br']) }}" alt="frame">@endif
        </div>

        <div class="slide-content slide-center">
            <div class="text-center animate__animated animate__fadeInDown animate__slower w-full">
                <div class="color-accent font-latin text-3xl mb-2">Akad Nikah</div>
                <div class="text-sm mb-1">{{ $invitation->akad_date?->translatedFormat('l, d F Y') }}</div>
                <div class="text-sm mb-2">Pukul {{ $invitation->akad_date?->format('H:i') }} WITA - Selesai</div>
                <div class="text-xs opacity-80 mt-2">
                    <strong>{{ $invitation->akad_venue }}</strong><br>
                    {{ $invitation->akad_address }}
                </div>
            </div>

            <div class="color-accent text-center animate__animated animate__fadeIn animate__slower my-4">
                <svg xmlns="http://www.w3.org/2000/svg" width="100" height="auto" viewBox="0 0 308.5 43.6" style="opacity:0.5; margin: 0 auto;"><path d="M308.5 21.7c0-.9-.4-1.7-1.1-2.2-.7-.5-1.5-.8-2.3-.9-1.6-.2-3.2-.1-4.8.2-.8.1-1.6.3-2.4.5-.8.2-1.6.4-2.3.6-1.1.3-2.2.7-3.3 1.1 1.5-1 2.9-2.1 4-3.5.7-.9 1.2-1.9 1.4-3.1.2-1.1 0-2.4-.9-3.3-.9-.8-2-1.2-3.2-1.2-.6 0-1.1.1-1.7.3-.5.2-1.1.5-1.4 1-.3.5-.5 1.1-.6 1.6-.1.6-.1 1.2.1 1.7.3 1.1 1.3 1.9 2.2 2.4-.1.7-.5 1.2-1 1.7s-1.2.8-1.9 1.1c-1.3.5-2.7.9-4.1 1h-36.5c-1 0-2 .5-2.5 1.3s-.6 1.9-.3 2.9c.4 1 1.6 1.4 2.6 1.3h-250c-1.3.5-2.7.9-4.1 1h35z" fill="currentColor"/></svg>
            </div>

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
