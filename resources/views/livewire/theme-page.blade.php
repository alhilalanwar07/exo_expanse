<div class="theme-page-router">
    @push('fonts')
        <link rel="stylesheet" href="{{ $this->googleFontsUrl }}">
    @endpush

    @push('styles')
        <style>
            {!! $this->themeCssVariables !!}
        </style>
    @endpush

    @livewire($themeComponent, ['invitation' => $invitation, 'metadata' => $metadata])
</div>

