{{-- Section: Quote / Ayat --}}
@php
    $quoteTitle = $sc['quote_title'] ?? 'QS. Ar-Rum 21';
    $quoteText = $sc['quote_text'] ?? '"Dan di antara tanda-tanda (kebesaran)-Nya ialah Dia menciptakan pasangan-pasangan untukmu dari jenismu sendiri, agar kamu cenderung dan merasa tenteram kepadanya, dan Dia menjadikan di antaramu rasa kasih dan sayang. Sungguh, pada yang demikian itu benar-benar terdapat tanda-tanda (kebesaran Allah) bagi kaum yang berpikir."';
@endphp

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
            <div class="color-accent text-center animate__animated animate__fadeInDown animate__slower font-accent mb-4" style="font-size:36px;">{{ $quoteTitle }}</div>
            <div class="text-center animate__animated animate__fadeInUp animate__slower text-sm italic opacity-90 leading-loose" style="padding: 0 50px 0 16px;">
                {{ $quoteText }}
            </div>
        </div>
    </div>
</div>
