{{-- Section: Gift / Amplop Digital --}}
@if($invitation->enable_gift)
<div id="slide-{{ $slideIndex }}" data-index="{{ $slideIndex }}" class="satumomen_slide">
    <div class="container-mobile">
        <div class="frame">
            @if(!empty($frame['left']))<img class="frame-lc animate__animated animate__fadeInLeft animate__slower" src="{{ asset($frame['left']) }}" alt="frame">@endif
            @if(!empty($frame['right']))<img class="frame-rc animate__animated animate__fadeInRight animate__slower" src="{{ asset($frame['right']) }}" alt="frame">@endif
        </div>

        <div class="slide-content slide-center text-center">
            <div class="color-accent text-3xl mb-2 animate__animated animate__fadeInDown animate__slower font-latin">Wedding Gift</div>
            <div class="text-sm opacity-80 mb-6 animate__animated animate__fadeInDown animate__slower max-w-xs mx-auto">
                Terima kasih telah menambah semangat kegembiraan pernikahan kami dengan kehadiran dan hadiah indah Anda.
            </div>

            <div class="flex flex-col gap-4 items-center w-full max-w-sm">
                @foreach($invitation->bank_accounts ?? [] as $acc)
                <div class="animate__animated animate__zoomIn animate__slower p-4" style="background:linear-gradient(113deg, #d9d9d9 0%, #ffffff 23%, #e5e5e5 31%, #fdfdfd 61%, #bababa 100%);border-radius:1rem;color:#333;width:100%;text-align:left;">
                    <div class="font-bold mb-2">{{ $acc['bank'] ?? 'Bank' }}</div>
                    <div class="text-xl font-bold font-mono tracking-widest mb-1">{{ $acc['account_number'] }}</div>
                    <div class="text-sm opacity-80 mb-3">a.n {{ $acc['account_name'] }}</div>
                    <button x-data="{copied: false}" @click="navigator.clipboard.writeText('{{ $acc['account_number'] }}'); copied=true; setTimeout(()=>copied=false, 2000)" class="btn-primary" style="padding: 6px 12px; font-size: 12px;">
                        <span x-text="copied ? 'Tersalin' : 'Salin Rekening'"></span>
                    </button>
                </div>
                @endforeach

                @if($invitation->bank_name && empty($invitation->bank_accounts))
                <div class="animate__animated animate__zoomIn animate__slower p-4" style="background:linear-gradient(113deg, #d9d9d9 0%, #ffffff 23%, #e5e5e5 31%, #fdfdfd 61%, #bababa 100%);border-radius:1rem;color:#333;width:100%;text-align:left;">
                    <div class="font-bold mb-2">{{ $invitation->bank_name }}</div>
                    <div class="text-xl font-bold font-mono tracking-widest mb-1">{{ $invitation->bank_account }}</div>
                    <div class="text-sm opacity-80 mb-3">a.n {{ $invitation->bank_holder }}</div>
                    <button x-data="{copied: false}" @click="navigator.clipboard.writeText('{{ $invitation->bank_account }}'); copied=true; setTimeout(()=>copied=false, 2000)" class="btn-primary" style="padding: 6px 12px; font-size: 12px;">
                        <span x-text="copied ? 'Tersalin' : 'Salin Rekening'"></span>
                    </button>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endif
