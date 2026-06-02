{{-- Section: Closing / Penutup --}}
<div id="slide-{{ $slideIndex }}" data-index="{{ $slideIndex }}" class="satumomen_slide">
    <div class="container-mobile">
        <div class="frame">
            @if(!empty($frame['tl']))<img class="frame-tl animate__animated animate__fadeInTopLeft animate__slow" src="{{ asset($frame['tl']) }}" alt="frame">@endif
            @if(!empty($frame['tr']))<img class="frame-tr animate__animated animate__fadeInTopRight animate__slow" src="{{ asset($frame['tr']) }}" alt="frame">@endif
            @if(!empty($frame['bl']))<img class="frame-bl animate__animated animate__fadeInBottomLeft animate__slow" src="{{ asset($frame['bl']) }}" alt="frame">@endif
            @if(!empty($frame['br']))<img class="frame-br animate__animated animate__fadeInBottomRight animate__slow" src="{{ asset($frame['br']) }}" alt="frame">@endif
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
