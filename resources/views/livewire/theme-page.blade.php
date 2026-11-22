<div class="theme-page-router">
    @push('fonts')
        @if(!empty($this->googleFontsUrl))
            <link rel="stylesheet" href="{{ $this->googleFontsUrl }}">
        @endif
    @endpush

    @push('styles')
        <style>
            {!! $this->themeCssVariables !!}
        </style>
    @endpush

    @livewire($themeComponent, ['invitation' => $invitation, 'metadata' => $metadata])
</div>

