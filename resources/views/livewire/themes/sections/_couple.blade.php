{{-- Section: Couple / Mempelai --}}
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

        <div class="slide-content slide-center text-center">
            @php $order = $invitation->custom_styles['name_order'] ?? 'groom_first'; @endphp

            @php
                $p1_role = $order === 'groom_first' ? 'Putra' : 'Putri';
                $p1_name = $order === 'groom_first' ? $invitation->groom_name : $invitation->bride_name;
                $p1_father = $order === 'groom_first' ? $invitation->groom_father : $invitation->bride_father;
                $p1_mother = $order === 'groom_first' ? $invitation->groom_mother : $invitation->bride_mother;
                $p1_photo = $order === 'groom_first' ? $invitation->groom_photo : $invitation->bride_photo;
                $p1_fallback = 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=200';
            @endphp
            <div class="mb-4">
                <div class="photo-frame mb-3 animate__animated animate__fadeInDown animate__slower">
                    <img src="{{ $p1_photo ? img_url($p1_photo) : $p1_fallback }}" alt="Photo 1">
                </div>
                <div class="animate__animated animate__fadeInLeft animate__slower">
                    <div class="color-accent font-accent text-2xl mb-1">{{ $p1_name }}</div>
                    <div class="text-sm opacity-80 leading-relaxed">{{ $p1_role }} dari<br>{{ $p1_father }} & {{ $p1_mother }}</div>
                </div>
            </div>

            <div class="color-accent animate__animated animate__fadeIn animate__slower my-2 flex justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 50 59.8" xml:space="preserve"><path d="M10.4 54.1c2.6 2.5 5.9 3.8 9.8 3.8 2.7 0 5.1-.6 7.4-1.8 2.3-1.2 3.8-2.6 4.6-4.3.8-1.7 1.2-3.6 1.2-5.7 0-4.2-1.3-7.8-4-10.8-.6-.8-2.1-1.5-4.4-2-1-.3-2-.5-3.2-.6 4-.4 6.5-.2 7.7.5 1.9 1.2 3.4 3 4.6 5.4s2.1 3.6 2.6 3.7l5.2.4c2.6.1 4.5.9 5.7 2.3 1.7 2.1 2.4 5.6 2.2 10.6-1-5.5-2.4-8.5-3.9-9.1-1.6-.6-3-.8-4.2-.8-3 0-5.1 1.7-6.3 5.2-3.3 5.9-8.1 8.8-14.4 8.8-5.3 0-10.2-2-14.5-5.9C2.2 49.9 0 45.3 0 40.2c0-3.2 1.6-6.7 4.9-10.5 3.6-4.2 7.8-6.6 12.6-7.2-3.8-.8-6.8-2.4-9.1-4.9C6.1 15.1 5 12.3 5 9.1c0-2.3 1.1-4.4 3.4-6.3C10.4.9 12.5 0 14.7 0c1 0 2.3.3 4.1 1 2.3.9 3.4 1.9 3.4 3 0 .9-.6 1.4-1.7 1.4-.1 0-.5-.4-1.4-1.3-.8-.8-1.8-1.3-2.9-1.4-1.2-.1-2.4.5-3.6 1.8-1.2 1.4-1.8 2.7-1.8 4.1 0 7.2 5.3 12.1 15.8 14.7 1.4.3 2.1.7 2.1 1.3-1.2-.2-2.5-.3-3.9-.3-5.1 0-9.3 1.1-12.6 3.4-4.2 2.9-6.3 7.2-6.3 13 0 5.9 1.5 10.4 4.5 13.4z" fill="currentColor"></path></svg>
            </div>

            @php
                $p2_role = $order === 'groom_first' ? 'Putri' : 'Putra';
                $p2_name = $order === 'groom_first' ? $invitation->bride_name : $invitation->groom_name;
                $p2_father = $order === 'groom_first' ? $invitation->bride_father : $invitation->groom_father;
                $p2_mother = $order === 'groom_first' ? $invitation->bride_mother : $invitation->groom_mother;
                $p2_photo = $order === 'groom_first' ? $invitation->bride_photo : $invitation->groom_photo;
                $p2_fallback = 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=200';
            @endphp
            <div class="mt-4">
                <div class="animate__animated animate__fadeInRight animate__slower mb-3">
                    <div class="color-accent font-accent text-2xl mb-1">{{ $p2_name }}</div>
                    <div class="text-sm opacity-80 leading-relaxed">{{ $p2_role }} dari<br>{{ $p2_father }} & {{ $p2_mother }}</div>
                </div>
                <div class="photo-frame animate__animated animate__fadeInUp animate__slower">
                    <img src="{{ $p2_photo ? img_url($p2_photo) : $p2_fallback }}" alt="Photo 2">
                </div>
            </div>
        </div>
    </div>
</div>
