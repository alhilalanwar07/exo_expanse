{{-- Section: Love Story --}}
@if($invitation->love_story && count($invitation->love_story) > 0)
<div id="slide-{{ $slideIndex }}" data-index="{{ $slideIndex }}" class="satumomen_slide" style="height: auto; min-height: 100dvh;">
    <div class="container-mobile" style="height: auto; min-height: 100%;">
        <div class="frame">
            @if(!empty($frame['tl']))<img class="frame-tl animate__animated animate__fadeInTopLeft animate__slow" src="{{ asset($frame['tl']) }}" alt="frame">@endif
            @if(!empty($frame['tr']))<img class="frame-tr animate__animated animate__fadeInTopRight animate__slow" src="{{ asset($frame['tr']) }}" alt="frame">@endif
            @if(!empty($frame['bl']))<img class="frame-bl animate__animated animate__fadeInBottomLeft animate__slow" src="{{ asset($frame['bl']) }}" alt="frame">@endif
            @if(!empty($frame['br']))<img class="frame-br animate__animated animate__fadeInBottomRight animate__slow" src="{{ asset($frame['br']) }}" alt="frame">@endif
        </div>

        <div class="slide-content" style="padding-top: 80px; padding-bottom: 80px;">
            <div class="text-center mb-8 animate__animated animate__fadeInDown animate__slower">
                <div class="font-accent color-accent" style="font-size: 36px;">Love Story</div>
                <div class="mx-auto mt-2" style="width: 60px; height: 1px; background: var(--inv-accent); opacity: 0.5;"></div>
            </div>

            <div style="position: relative; padding-left: 24px; border-left: 1px solid rgba(212,176,81,0.3); margin-left: 16px;">
                @foreach($invitation->love_story as $index => $story)
                <div class="animate__animated animate__fadeInUp animate__slower mb-8" style="animation-delay: {{ $index * 0.15 }}s; position: relative;">
                    <div style="position: absolute; left: -30px; top: 6px; width: 12px; height: 12px; border-radius: 50%; background: var(--inv-accent); border: 3px solid var(--inv-bg);"></div>
                    <div style="background: rgba(255,255,255,0.06); border: 1px solid rgba(212,176,81,0.2); border-radius: 12px; padding: 20px;">
                        @if(!empty($story['date']))
                        <div class="color-accent font-accent" style="font-size: 14px; margin-bottom: 4px;">{{ $story['date'] ?? '' }}</div>
                        @endif
                        <div class="color-accent" style="font-size: 18px; font-weight: 600; margin-bottom: 8px; font-family: var(--font-base);">{{ $story['title'] ?? '' }}</div>
                        <div style="font-size: 13px; opacity: 0.8; line-height: 1.7;">{{ $story['description'] ?? '' }}</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endif
