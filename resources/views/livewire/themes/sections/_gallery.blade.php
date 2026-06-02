{{-- Section: Gallery --}}
@if($invitation->enable_gallery)
<div id="slide-{{ $slideIndex }}" data-index="{{ $slideIndex }}" class="satumomen_slide">
    <div class="container-mobile">
        <div class="frame">
            @if(!empty($frame['tl']))<img class="frame-tl animate__animated animate__fadeInTopLeft animate__slow" src="{{ asset($frame['tl']) }}" alt="frame">@endif
            @if(!empty($frame['tr']))<img class="frame-tr animate__animated animate__fadeInTopRight animate__slow" src="{{ asset($frame['tr']) }}" alt="frame">@endif
            @if(!empty($frame['bl']))<img class="frame-bl animate__animated animate__fadeInBottomLeft animate__slow" src="{{ asset($frame['bl']) }}" alt="frame">@endif
            @if(!empty($frame['br']))<img class="frame-br animate__animated animate__fadeInBottomRight animate__slow" src="{{ asset($frame['br']) }}" alt="frame">@endif
        </div>

        <div class="slide-content pt-16">
            <div class="text-center mb-6 animate__animated animate__fadeInDown animate__slower">
                <div class="font-accent color-accent text-3xl">Galeri</div>
            </div>

            <div class="grid grid-cols-2 gap-3 pb-20 justify-center">
                @foreach($invitation->photos as $index => $photo)
                <div class="animate__animated animate__zoomIn animate__slower mb-2" style="animation-delay: {{ $index * 0.1 }}s; border-radius: 12px; overflow: hidden; height: {{ $index % 2 == 0 ? '160px' : '120px' }};">
                    <img src="{{ $photo->url }}" alt="Gallery image" style="width: 100%; height: 100%; object-fit: cover;">
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endif
