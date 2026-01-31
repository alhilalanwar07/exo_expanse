<?php

use App\Models\Invitation;
use App\Models\MessageTemplate;
use App\Services\GuestService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Layout('layouts::app')]
#[Title('Sebar Undangan')] 
class extends Component
{
    public Invitation $invitation;
    
    // Recipient management
    public array $recipients = [];
    public string $newRecipient = '';
    public bool $linksGenerated = false;
    
    // Message template
    public ?int $selectedTemplateId = null;

    public function mount(int $id): void
    {
        $this->invitation = Invitation::where('user_id', Auth::id())
            ->with('theme')
            ->findOrFail($id);
            
        // Default to first template
        $firstTemplate = MessageTemplate::active()->ordered()->first();
        if ($firstTemplate) {
            $this->selectedTemplateId = $firstTemplate->id;
        }
    }

    #[Computed]
    public function templates()
    {
        return MessageTemplate::active()->ordered()->get();
    }

    #[Computed]
    public function selectedTemplate()
    {
        return MessageTemplate::find($this->selectedTemplateId);
    }

    #[Computed]
    public function baseUrl(): string
    {
        return route('invitation.show', $this->invitation->slug);
    }

    public function addRecipient(): void
    {
        $name = trim($this->newRecipient);
        
        if (!empty($name) && !in_array($name, $this->recipients)) {
            $this->recipients[] = $name;
            $this->linksGenerated = false;
        }
        
        $this->newRecipient = '';
    }

    public function removeRecipient(int $index): void
    {
        if (isset($this->recipients[$index])) {
            unset($this->recipients[$index]);
            $this->recipients = array_values($this->recipients);
        }
    }

    public function generateLinks(): void
    {
        if (empty($this->recipients)) {
            session()->flash('error', 'Tambahkan minimal 1 penerima terlebih dahulu');
            return;
        }
        
        $this->linksGenerated = true;
    }

    public function getPersonalUrl(string $name): string
    {
        return $this->baseUrl . '?kpd=' . urlencode($name);
    }

    /**
     * Format event details for WhatsApp message.
     */
    private function formatEventDetails(): string
    {
        $inv = $this->invitation;
        $details = [];

        // Akad
        if ($inv->akad_date) {
            $akadDate = Carbon::parse($inv->akad_date);
            $akadTime = $inv->akad_time ? Carbon::parse($inv->akad_time)->format('H:i') : '';
            
            $details[] = "Pada: Akad Pernikahan";
            $details[] = "📆 Tanggal: " . $akadDate->translatedFormat('d-m-Y');
            if ($akadTime) {
                $details[] = "⏰ Pukul: {$akadTime} - Selesai";
            }
            if ($inv->akad_location) {
                $details[] = "📍 Lokasi: {$inv->akad_location}";
            }
            $details[] = "";
        }

        // Resepsi
        if ($inv->event_date) {
            $receptionDate = Carbon::parse($inv->event_date);
            $receptionTime = $inv->reception_time ? Carbon::parse($inv->reception_time)->format('H:i') : '';
            
            $details[] = "Pada: Resepsi Pernikahan";
            $details[] = "📆 Tanggal: " . $receptionDate->translatedFormat('d-m-Y');
            if ($receptionTime) {
                $details[] = "⏰ Pukul: {$receptionTime} - Selesai";
            }
            if ($inv->reception_location) {
                $details[] = "📍 Lokasi: {$inv->reception_location}";
            }
        }

        return implode("\n", $details);
    }

    /**
     * Get the invitation title.
     */
    private function getInvitationTitle(): string
    {
        $inv = $this->invitation;
        
        if ($inv->groom_name && $inv->bride_name) {
            $first = $inv->name_order === 'bride_first' ? $inv->bride_name : $inv->groom_name;
            $second = $inv->name_order === 'bride_first' ? $inv->groom_name : $inv->bride_name;
            return "The Wedding of\n{$first} & {$second}";
        }
        
        return $inv->title;
    }

    public function getWhatsAppUrl(string $name): string
    {
        $template = $this->selectedTemplate;
        if (!$template) {
            return '#';
        }
        
        $personalUrl = $this->getPersonalUrl($name);
        $eventDetails = $this->formatEventDetails();
        $invitationTitle = $this->getInvitationTitle();
        
        // Replace placeholders
        $message = str_replace(
            ['{nama}', '{judul}', '{detail_acara}', '{link}'],
            [$name, $invitationTitle, $eventDetails, $personalUrl],
            $template->content
        );

        return 'https://wa.me/?text=' . urlencode($message);
    }

    /**
     * Get preview of message for display.
     */
    public function getMessagePreview(): string
    {
        $template = $this->selectedTemplate;
        if (!$template) {
            return '';
        }
        
        $recipientName = trim($this->newRecipient) ?: '[Nama Penerima]';
        
        return str_replace(
            ['{nama}', '{judul}', '{detail_acara}', '{link}'],
            [$recipientName, '[Judul Undangan]', '[Detail Akad & Resepsi]', '[Link Undangan]'],
            $template->content
        );
    }

    public function saveToGuestList(): void
    {
        $guestService = app(GuestService::class);
        
        $count = 0;
        foreach ($this->recipients as $name) {
            $exists = $this->invitation->guests()->where('name', $name)->exists();
            if (!$exists) {
                $guestService->addGuest($this->invitation, ['name' => $name]);
                $count++;
            }
        }

        if ($count > 0) {
            session()->flash('success', "{$count} penerima berhasil disimpan ke daftar tamu!");
        } else {
            session()->flash('info', "Semua penerima sudah ada di daftar tamu.");
        }
    }
}; ?>

<div class="min-h-screen bg-gradient-to-br from-rose-50 via-white to-amber-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900">
    <!-- Header -->
    <header class="sticky top-0 z-40 bg-white/80 dark:bg-slate-800/80 backdrop-blur-lg border-b border-slate-200 dark:border-slate-700">
        <div class="max-w-4xl mx-auto px-4 h-16 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('dashboard') }}" class="text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                </a>
                <div>
                    <h1 class="font-bold text-slate-800 dark:text-white flex items-center gap-2">
                        📤 Sebar Undangan
                    </h1>
                    <p class="text-xs text-slate-500 dark:text-slate-400">{{ $invitation->title }}</p>
                </div>
            </div>
            @if($invitation->theme)
                <span class="px-3 py-1 bg-rose-100 dark:bg-rose-900/30 text-rose-600 dark:text-rose-300 text-sm rounded-full">
                    {{ $invitation->theme->name }}
                </span>
            @endif
        </div>
    </header>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="max-w-4xl mx-auto px-4 mt-4">
            <div class="bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 rounded-xl p-4 flex items-center gap-3">
                <span class="text-2xl">✅</span>
                <p class="text-emerald-700 dark:text-emerald-300">{{ session('success') }}</p>
            </div>
        </div>
    @endif
    @if(session('error'))
        <div class="max-w-4xl mx-auto px-4 mt-4">
            <div class="bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 rounded-xl p-4 flex items-center gap-3">
                <span class="text-2xl">❌</span>
                <p class="text-red-700 dark:text-red-300">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <!-- Main Content -->
    <div class="max-w-4xl mx-auto px-4 py-8">
        
        <!-- Invitation Preview Card -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-lg border border-slate-200 dark:border-slate-700 p-6 mb-8">
            <div class="flex items-center gap-5">
                @if($invitation->cover_photo)
                    <img src="{{ asset('storage/' . $invitation->cover_photo) }}" class="w-20 h-20 rounded-full object-cover border-4 border-rose-200 dark:border-rose-800">
                @else
                    <div class="w-20 h-20 rounded-full bg-gradient-to-br from-rose-100 to-amber-100 dark:from-rose-900/30 dark:to-amber-900/30 flex items-center justify-center">
                        <span class="text-3xl">💍</span>
                    </div>
                @endif
                <div>
                    <h2 class="text-xl font-bold text-slate-800 dark:text-white">{{ $invitation->title }}</h2>
                    <p class="text-slate-500 dark:text-slate-400 text-sm">{{ $invitation->event_date?->translatedFormat('l, d F Y') ?? 'Tanggal belum diset' }}</p>
                    <a href="{{ $this->baseUrl }}" target="_blank" class="text-rose-500 hover:text-rose-600 text-sm mt-1 inline-flex items-center gap-1">
                        🔗 {{ $this->baseUrl }}
                    </a>
                </div>
            </div>
        </div>

        <!-- Step 1: Add Recipients -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-lg border border-slate-200 dark:border-slate-700 p-6 mb-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-rose-100 dark:bg-rose-900/30 rounded-full flex items-center justify-center text-lg font-bold text-rose-500">1</div>
                <div>
                    <h3 class="font-bold text-slate-800 dark:text-white">Tambah Penerima</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400">Masukkan nama penerima undangan</p>
                </div>
            </div>

            <!-- Recipient Tags -->
            <div class="flex flex-wrap gap-2 mb-4">
                @foreach($recipients as $index => $recipient)
                    <span class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-rose-500 to-amber-500 text-white rounded-full text-sm font-medium shadow-md">
                        {{ $recipient }}
                        <button wire:click="removeRecipient({{ $index }})" class="w-5 h-5 bg-white/20 rounded-full flex items-center justify-center hover:bg-white/30 transition-colors">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </span>
                @endforeach
                @if(empty($recipients))
                    <span class="text-slate-400 dark:text-slate-500 text-sm italic">Belum ada penerima...</span>
                @endif
            </div>

            <!-- Add Input -->
            <div class="flex gap-3">
                <input 
                    type="text" 
                    wire:model.live="newRecipient"
                    wire:keydown.enter="addRecipient"
                    class="flex-1 px-4 py-3 border-2 border-slate-200 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 text-slate-800 dark:text-white placeholder-slate-400 focus:ring-2 focus:ring-rose-500 focus:border-transparent"
                    placeholder="Ketik nama penerima, tekan Enter atau klik Tambah"
                >
                <button 
                    wire:click="addRecipient"
                    class="px-6 py-3 bg-rose-500 text-white font-semibold rounded-xl hover:bg-rose-600 transition-all shadow-lg shadow-rose-500/30"
                >
                    + Tambah
                </button>
            </div>
        </div>

        <!-- Step 2: Message Template -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-lg border border-slate-200 dark:border-slate-700 p-6 mb-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-amber-100 dark:bg-amber-900/30 rounded-full flex items-center justify-center text-lg font-bold text-amber-500">2</div>
                <div>
                    <h3 class="font-bold text-slate-800 dark:text-white">Pilih Template Pesan</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400">Template akan digunakan untuk pesan WhatsApp</p>
                </div>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-3">
                @foreach($this->templates as $template)
                    <label class="cursor-pointer" wire:key="template-{{ $template->id }}">
                        <input 
                            type="radio" 
                            name="selectedTemplate"
                            wire:model.live="selectedTemplateId" 
                            value="{{ $template->id }}" 
                            class="sr-only peer"
                        >
                        <div class="p-4 rounded-xl border-2 transition-all text-center peer-checked:border-rose-500 peer-checked:bg-rose-50 dark:peer-checked:bg-rose-900/20 border-slate-200 dark:border-slate-600 hover:border-rose-300">
                            <span class="text-2xl block mb-2">{{ $template->icon }}</span>
                            <span class="text-xs font-medium text-slate-700 dark:text-slate-300">{{ $template->name }}</span>
                        </div>
                    </label>
                @endforeach
            </div>

            <!-- Preview Template -->
            @if($this->selectedTemplate)
            <div class="mt-4 p-4 bg-slate-50 dark:bg-slate-700/50 rounded-xl">
                <p class="text-xs text-slate-500 dark:text-slate-400 mb-2">Preview pesan:</p>
                <pre class="text-sm text-slate-600 dark:text-slate-300 whitespace-pre-wrap font-sans leading-relaxed">{{ $this->getMessagePreview() }}</pre>
            </div>
            @endif
        </div>

        <!-- Step 3: Generate Links -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-lg border border-slate-200 dark:border-slate-700 p-6 mb-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-emerald-100 dark:bg-emerald-900/30 rounded-full flex items-center justify-center text-lg font-bold text-emerald-500">3</div>
                <div>
                    <h3 class="font-bold text-slate-800 dark:text-white">Generate & Sebar</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400">Klik tombol untuk generate link personal</p>
                </div>
            </div>

            <div class="flex gap-3">
                <button 
                    wire:click="generateLinks"
                    class="flex-1 py-4 bg-gradient-to-r from-emerald-500 to-teal-500 text-white font-bold rounded-xl hover:shadow-lg hover:shadow-emerald-500/30 transition-all flex items-center justify-center gap-2"
                >
                    🚀 GENERATE LINK
                </button>
                @if(count($recipients) > 0)
                    <button 
                        wire:click="saveToGuestList"
                        class="px-6 py-4 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-200 font-semibold rounded-xl hover:bg-slate-200 dark:hover:bg-slate-600 transition-all"
                        title="Simpan ke daftar tamu"
                    >
                        💾 Simpan
                    </button>
                @endif
            </div>
        </div>

        <!-- Generated Links -->
        @if($linksGenerated && count($recipients) > 0)
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-lg border border-slate-200 dark:border-slate-700 overflow-hidden">
                <div class="bg-gradient-to-r from-rose-500 to-amber-500 px-6 py-4">
                    <h3 class="text-white font-bold text-lg">📨 Link Personal Siap Dikirim</h3>
                    <p class="text-rose-100 text-sm">{{ count($recipients) }} penerima</p>
                </div>
                
                <div class="divide-y divide-slate-100 dark:divide-slate-700">
                    @foreach($recipients as $recipient)
                        <div class="p-5 hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                                <div>
                                    <p class="text-slate-500 dark:text-slate-400 text-sm">Kepada:</p>
                                    <p class="text-lg font-bold text-slate-800 dark:text-white">{{ $recipient }}</p>
                                </div>
                                <div class="flex gap-2">
                                    <!-- Copy Button -->
                                    <button 
                                        onclick="navigator.clipboard.writeText('{{ $this->getPersonalUrl($recipient) }}'); this.querySelector('span').textContent='Copied!'; setTimeout(() => this.querySelector('span').textContent='📋 Copy', 1500);"
                                        class="px-4 py-2.5 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-200 font-medium rounded-lg hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors flex items-center gap-2"
                                    >
                                        <span>📋 Copy</span>
                                    </button>
                                    
                                    <!-- WhatsApp Button -->
                                    <a 
                                        href="{{ $this->getWhatsAppUrl($recipient) }}"
                                        target="_blank"
                                        class="px-4 py-2.5 bg-green-500 text-white font-medium rounded-lg hover:bg-green-600 transition-colors flex items-center gap-2 shadow-lg shadow-green-500/30"
                                    >
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                        </svg>
                                        Kirim WA
                                    </a>
                                    
                                    <!-- Share Button -->
                                    <button 
                                        onclick="navigator.share ? navigator.share({title: '{{ $invitation->title }}', url: '{{ $this->getPersonalUrl($recipient) }}'}) : alert('Share tidak didukung browser ini')"
                                        class="px-4 py-2.5 bg-blue-500 text-white font-medium rounded-lg hover:bg-blue-600 transition-colors flex items-center gap-2 shadow-lg shadow-blue-500/30"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
                                        </svg>
                                        Share
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Tips -->
            <div class="mt-6 p-4 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-xl">
                <p class="text-amber-700 dark:text-amber-300 text-sm">
                    💡 <strong>Tips:</strong> Data penerima di halaman ini bersifat temporary. Klik "Simpan" untuk menyimpan ke daftar tamu permanen.
                </p>
            </div>
        @endif

    </div>
</div>
