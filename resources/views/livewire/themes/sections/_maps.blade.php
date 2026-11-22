{{-- Section: Maps --}}
<div id="slide-{{ $slideIndex }}" data-index="{{ $slideIndex }}" class="satumomen_slide">
    <div class="container-mobile">
        <div class="frame">
            @if(!empty($frame['left']))<img class="frame-lc animate__animated animate__fadeInLeft animate__slower" src="{{ asset($frame['left']) }}" alt="frame">@endif
            @if(!empty($frame['right']))<img class="frame-rc animate__animated animate__fadeInRight animate__slower" src="{{ asset($frame['right']) }}" alt="frame">@endif
        </div>

        <div class="slide-content slide-center">
            @php
                $eventType = $invitation->custom_styles['event_type'] ?? 'both';
                $mapsLink = ($eventType === 'resepsi_only')
                    ? ($invitation->resepsi_maps_link ?? $invitation->akad_maps_link)
                    : ($invitation->akad_maps_link ?? $invitation->resepsi_maps_link);
                $venue = ($eventType === 'resepsi_only')
                    ? ($invitation->resepsi_venue ?? $invitation->akad_venue)
                    : ($invitation->akad_venue ?? $invitation->resepsi_venue);
                $address = ($eventType === 'resepsi_only')
                    ? ($invitation->resepsi_address ?? $invitation->akad_address)
                    : ($invitation->akad_address ?? $invitation->resepsi_address);
                $mapsQuery = $address ?? $venue ?? '';
                $mapsEmbed = 'https://maps.google.com/maps?q=' . urlencode($mapsQuery) . '&z=15&output=embed';
            @endphp
            @if($mapsLink || $mapsQuery)
            <div style="width:85%;margin:auto;border-radius:12px;overflow:hidden;margin-bottom:20px;" class="animate__animated animate__fadeInDown animate__slow">
                <iframe src="{{ $mapsEmbed }}" width="100%" height="280" style="border:0; border-radius:12px;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
            @endif

            <div class="text-center animate__animated animate__fadeInUp animate__slow">
                <div class="mb-4 text-sm opacity-90 mx-auto" style="max-width: 250px;">
                    {{ $venue }}<br>
                    {{ $address }}
                </div>
                @if($mapsLink)
                <a href="{{ $mapsLink }}" class="btn-primary" target="_blank" rel="noreferrer noopener">Petunjuk Ke Lokasi</a>
                @endif
            </div>
        </div>
    </div>
</div>
