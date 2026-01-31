<?php

use App\Livewire\Forms\InvitationForm;
use App\Models\Guest;
use App\Models\Invitation;
use App\Models\InvitationPhoto;
use App\Models\Theme;
use App\Services\GuestImportService;
use App\Services\GuestService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithFileUploads;

new #[Layout('layouts::app')]
#[Title('Buat Undangan')]
class extends Component
{
    use WithFileUploads;

    public InvitationForm $form;

    // Tab Navigation
    #[Url]
    public string $tab = 'cover';

    // Edit Mode
    public ?int $invitationId = null;

    // Photo Uploads
    public array $photos = [];
    public array $existingPhotos = [];

    // Cover Photo Upload
    public $coverPhotoUpload = null;

    // Music Upload
    public $musicUpload = null; // New property for music files

    // Guest Management
    public string $guestInput = '';
    public $guestFile = null;
    public array $guests = [];

    // Available Tabs
    public array $tabs = [
        'cover' => ['label' => 'Cover', 'icon' => '🎨'],
        'mempelai' => ['label' => 'Mempelai', 'icon' => '💑'],
        'acara' => ['label' => 'Acara', 'icon' => '📅'],
        'gallery' => ['label' => 'Gallery', 'icon' => '📸'],
        'tamu' => ['label' => 'Tamu', 'icon' => '👥'],
        'settings' => ['label' => 'Settings', 'icon' => '⚙️'],
    ];

    public function mount(?int $id = null): void
    {
        if ($id) {
            $invitation = Invitation::where('user_id', Auth::id())->findOrFail($id);
            $this->invitationId = $id;
            $this->form->setInvitation($invitation);
            $this->loadExistingPhotos($invitation);
            $this->loadGuests($invitation);
        }
    }

    #[Computed]
    public function themes(): \Illuminate\Database\Eloquent\Collection
    {
        return Theme::orderBy('name')->get();
    }

    #[Computed]
    public function invitation(): ?Invitation
    {
        if ($this->invitationId) {
            return Invitation::find($this->invitationId);
        }
        return null;
    }

    public function setTab(string $tab): void
    {
        if (array_key_exists($tab, $this->tabs)) {
            $this->tab = $tab;
        }
    }

    public function loadExistingPhotos(Invitation $invitation): void
    {
        $this->existingPhotos = $invitation->photos->map(fn($photo) => [
            'id' => $photo->id,
            'url' => asset('storage/' . $photo->path),
            'path' => $photo->path,
        ])->toArray();
    }


    // ==================
    // COVER PHOTO
    // ==================

    public function updatedMusicUpload(): void
    {
        $this->validate([
            'musicUpload' => 'required|file|mimes:mp3|max:10240', // 10MB
        ]);

        if ($this->musicUpload) {
            // Delete old music if exists
            if ($this->form->music_url) {
                Storage::disk('public')->delete($this->form->music_url);
            }

            $path = $this->musicUpload->store('invitations/music', 'public');
            $this->form->music_url = $path;
            $this->musicUpload = null;
        }
    }

    public function removeMusic(): void
    {
        if ($this->form->music_url) {
            Storage::disk('public')->delete($this->form->music_url);
            $this->form->music_url = null;
        }
    }

    public function updatedCoverPhotoUpload(): void
    {
        $this->validate([
            'coverPhotoUpload' => 'image|max:5120', // 5MB
        ]);

        if ($this->coverPhotoUpload) {
            // Delete old cover photo if exists
            if ($this->form->cover_photo) {
                Storage::disk('public')->delete($this->form->cover_photo);
            }

            $path = $this->coverPhotoUpload->store('invitations/covers', 'public');
            $this->form->cover_photo = $path;
            $this->coverPhotoUpload = null;
        }
    }

    public function removeCoverPhoto(): void
    {
        if ($this->form->cover_photo) {
            Storage::disk('public')->delete($this->form->cover_photo);
            $this->form->cover_photo = null;
        }
    }

    // ==================
    // GALLERY PHOTOS
    // ==================

    public function updatedPhotos(): void
    {
        $this->validate([
            'photos.*' => 'image|max:5120', // 5MB per photo
        ]);
    }

    public function removePhoto(int $index): void
    {
        if (isset($this->photos[$index])) {
            unset($this->photos[$index]);
            $this->photos = array_values($this->photos);
        }
    }

    public function removeExistingPhoto(int $photoId): void
    {
        $photo = InvitationPhoto::find($photoId);
        
        if ($photo && $photo->invitation_id === $this->invitationId) {
            Storage::disk('public')->delete($photo->path);
            $photo->delete();
            
            $this->existingPhotos = array_filter(
                $this->existingPhotos,
                fn($p) => $p['id'] !== $photoId
            );
            $this->existingPhotos = array_values($this->existingPhotos);
        }
    }

    // ==================
    // GIFT ACCOUNTS
    // ==================

    public function addGiftAccount(): void
    {
        $this->form->gift_accounts[] = [
            'bank' => '',
            'account_number' => '',
            'account_name' => '',
        ];
    }

    public function removeGiftAccount(int $index): void
    {
        if (isset($this->form->gift_accounts[$index])) {
            unset($this->form->gift_accounts[$index]);
            $this->form->gift_accounts = array_values($this->form->gift_accounts);
        }
    }

    // ==================
    // GUEST MANAGEMENT
    // ==================

    public function loadGuests(Invitation $invitation): void
    {
        $this->guests = $invitation->guests->map(fn($g) => [
            'id' => $g->id,
            'name' => $g->name,
            'slug' => $g->slug,
            'phone_number' => $g->phone_number,
            'status' => $g->status?->value ?? 'pending',
        ])->toArray();
    }

    public function addGuestsFromText(): void
    {
        if (!$this->invitationId) {
            session()->flash('error', 'Simpan undangan terlebih dahulu sebelum menambahkan tamu.');
            return;
        }

        $invitation = Invitation::find($this->invitationId);
        $importService = app(GuestImportService::class);
        $guestService = app(GuestService::class);

        $guestData = $importService->parseCommaSeparated($this->guestInput);
        $guestData = $importService->validateGuests($guestData);

        if (empty($guestData)) {
            session()->flash('error', 'Tidak ada tamu yang valid untuk ditambahkan.');
            return;
        }

        $count = $guestService->bulkImport($invitation, $guestData);
        
        $this->guestInput = '';
        $this->loadGuests($invitation);

        session()->flash('success', "{$count} tamu berhasil ditambahkan!");
    }

    public function updatedGuestFile(): void
    {
        $this->validate([
            'guestFile' => 'required|file|mimes:csv,txt,xlsx,xls|max:2048',
        ]);
    }

    public function importGuestsFromFile(): void
    {
        if (!$this->invitationId) {
            session()->flash('error', 'Simpan undangan terlebih dahulu sebelum import tamu.');
            return;
        }

        if (!$this->guestFile) {
            session()->flash('error', 'Pilih file terlebih dahulu.');
            return;
        }

        $invitation = Invitation::find($this->invitationId);
        $importService = app(GuestImportService::class);
        $guestService = app(GuestService::class);

        $guestData = $importService->parseCsvFile($this->guestFile);
        $guestData = $importService->validateGuests($guestData);

        if (empty($guestData)) {
            session()->flash('error', 'Tidak ada tamu yang valid dalam file.');
            return;
        }

        $count = $guestService->bulkImport($invitation, $guestData);
        
        $this->guestFile = null;
        $this->loadGuests($invitation);

        session()->flash('success', "{$count} tamu berhasil diimport dari file!");
    }

    public function deleteGuest(int $guestId): void
    {
        $guest = Guest::find($guestId);
        
        if ($guest && $guest->invitation_id === $this->invitationId) {
            $guest->delete();
            $this->guests = array_filter($this->guests, fn($g) => $g['id'] !== $guestId);
            $this->guests = array_values($this->guests);
        }
    }

    public function getGuestShareUrl(string $guestName): string
    {
        if (!$this->invitationId) {
            return '#';
        }

        $invitation = Invitation::find($this->invitationId);
        $baseUrl = route('invitation.show', $invitation->slug);
        
        return $baseUrl . '?kpd=' . urlencode($guestName);
    }

    public function getGuestWhatsAppUrl(string $guestName, ?string $phone = null): string
    {
        if (!$this->invitationId) {
            return '#';
        }

        $importService = app(GuestImportService::class);
        $invitation = Invitation::find($this->invitationId);
        $baseUrl = route('invitation.show', $invitation->slug);

        return $importService->generateWhatsAppUrl($baseUrl, $guestName, $phone);
    }

    // ==================
    // SAVE
    // ==================

    public function save(): void
    {
        $invitation = $this->form->save();
        
        // Save new photos
        $this->savePhotos($invitation);

        session()->flash('success', 'Undangan berhasil disimpan!');
        
        $this->redirect(route('invitations.edit', $invitation->id));
    }

    public function savePhotos(Invitation $invitation): void
    {
        foreach ($this->photos as $index => $photo) {
            if ($photo) {
                $path = $photo->store('invitations/' . $invitation->id, 'public');
                
                InvitationPhoto::create([
                    'invitation_id' => $invitation->id,
                    'path' => $path,
                    'order' => count($this->existingPhotos) + $index,
                ]);
            }
        }
        
        $this->photos = [];
        $this->loadExistingPhotos($invitation);
    }

    public function publish(): void
    {
        $invitation = $this->form->save();
        $invitation->publish();
        
        session()->flash('success', 'Undangan berhasil dipublikasikan!');
        
        $this->redirect(route('dashboard'));
    }
}; ?>

<div class="min-h-screen bg-gradient-to-br from-rose-50 via-white to-amber-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900">
    <!-- Header -->
    <header class="sticky top-0 z-40 bg-white/80 dark:bg-slate-800/80 backdrop-blur-lg border-b border-slate-200 dark:border-slate-700">
        <div class="max-w-7xl mx-auto px-4 h-16 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('dashboard') }}" class="text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                </a>
                <div>
                    <h1 class="font-bold text-slate-800 dark:text-white">{{ $invitationId ? 'Edit Undangan' : 'Buat Undangan Baru' }}</h1>
                    <p class="text-xs text-slate-500 dark:text-slate-400">{{ $form->title ?: 'Belum ada judul' }}</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <button wire:click="save" wire:loading.attr="disabled" class="px-4 py-2 text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg transition-all flex items-center gap-2">
                    <span wire:loading.remove wire:target="save">💾 Simpan Draft</span>
                    <span wire:loading wire:target="save" class="flex items-center gap-2">
                        <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/></svg>
                        Menyimpan...
                    </span>
                </button>
                <button wire:click="publish" wire:loading.attr="disabled" class="px-5 py-2 bg-gradient-to-r from-rose-500 to-amber-500 text-white font-semibold rounded-lg hover:shadow-lg hover:shadow-rose-500/30 transition-all flex items-center gap-2">
                    <span wire:loading.remove wire:target="publish">🚀 Publish</span>
                    <span wire:loading wire:target="publish">Publishing...</span>
                </button>
            </div>
        </div>
    </header>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 mt-4">
            <div class="bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 rounded-xl p-4 flex items-center gap-3">
                <span class="text-2xl">✅</span>
                <p class="text-emerald-700 dark:text-emerald-300">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 py-6">
        <div class="grid lg:grid-cols-5 gap-6">
            
            <!-- Left: Preview Panel -->
            <div class="lg:col-span-2 order-2 lg:order-1">
                <div class="sticky top-24">
                    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl overflow-hidden border border-slate-200 dark:border-slate-700">
                        <div class="bg-slate-100 dark:bg-slate-700 px-4 py-2 flex items-center gap-2">
                            <div class="flex gap-1.5">
                                <div class="w-3 h-3 rounded-full bg-red-400"></div>
                                <div class="w-3 h-3 rounded-full bg-yellow-400"></div>
                                <div class="w-3 h-3 rounded-full bg-green-400"></div>
                            </div>
                            <span class="text-xs text-slate-500 dark:text-slate-400 ml-2">Preview</span>
                        </div>
                        <div class="aspect-[9/16] bg-gradient-to-br from-rose-100 to-amber-100 dark:from-rose-900/30 dark:to-amber-900/30 flex items-center justify-center p-6">
                            <div class="text-center">
                                @if($form->cover_photo)
                                    <img src="{{ asset('storage/' . $form->cover_photo) }}" alt="Cover" class="w-32 h-32 rounded-full mx-auto mb-4 object-cover shadow-lg">
                                @else
                                    <div class="w-32 h-32 rounded-full mx-auto mb-4 bg-white/50 dark:bg-slate-700/50 flex items-center justify-center">
                                        <span class="text-5xl">💍</span>
                                    </div>
                                @endif
                                <p class="text-sm text-slate-500 dark:text-slate-400 mb-2">{{ $form->cover_subtitle ?: 'The Wedding of' }}</p>
                                <h2 class="text-2xl font-bold text-slate-800 dark:text-white">
                                    @if($form->groom_name && $form->bride_name)
                                        {{ $form->name_order === 'bride_first' ? $form->bride_name : $form->groom_name }}
                                        <span class="text-rose-500">&</span>
                                        {{ $form->name_order === 'bride_first' ? $form->groom_name : $form->bride_name }}
                                    @else
                                        {{ $form->title ?: 'Nama Mempelai' }}
                                    @endif
                                </h2>
                                @if($form->event_date)
                                    <p class="mt-4 text-slate-600 dark:text-slate-300">
                                        {{ \Carbon\Carbon::parse($form->event_date)->translatedFormat('l, d F Y') }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    @if($invitationId && $this->invitation?->is_published)
                        <a href="{{ route('invitation.show', $this->invitation->slug) }}" target="_blank" class="mt-4 block text-center py-3 bg-slate-100 dark:bg-slate-700 rounded-xl text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-600 transition-all">
                            🔗 Lihat Undangan Live
                        </a>
                    @endif
                </div>
            </div>

            <!-- Right: Form Panel -->
            <div class="lg:col-span-3 order-1 lg:order-2">
                <!-- Tab Navigation -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-lg border border-slate-200 dark:border-slate-700 mb-6 overflow-hidden">
                    <div class="flex overflow-x-auto scrollbar-hide">
                        @foreach($tabs as $key => $tabInfo)
                            <button 
                                wire:click="setTab('{{ $key }}')"
                                @class([
                                    'flex-1 min-w-[100px] px-4 py-4 text-center transition-all border-b-2 whitespace-nowrap',
                                    'border-rose-500 text-rose-600 dark:text-rose-400 bg-rose-50 dark:bg-rose-900/20' => $tab === $key,
                                    'border-transparent text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700/50' => $tab !== $key,
                                ])
                            >
                                <span class="text-xl block mb-1">{{ $tabInfo['icon'] }}</span>
                                <span class="text-sm font-medium">{{ $tabInfo['label'] }}</span>
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- Tab Content -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-lg border border-slate-200 dark:border-slate-700 p-6">
                    
                    {{-- TAB: COVER --}}
                    @if($tab === 'cover')
                        <div class="space-y-6">
                            <div class="border-b border-slate-200 dark:border-slate-700 pb-4 mb-6">
                                <h2 class="text-xl font-bold text-slate-800 dark:text-white flex items-center gap-2">
                                    🎨 Data Cover
                                </h2>
                                <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">Atur tampilan cover undangan</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Judul Undangan *</label>
                                <input wire:model.blur="form.title" type="text" class="w-full px-4 py-3 border-2 border-slate-200 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition-all" placeholder="Pernikahan Andi & Rina">
                                @error('form.title') <span class="text-rose-500 text-sm mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div class="grid md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Teks Kecil Atas</label>
                                    <input wire:model.blur="form.cover_subtitle" type="text" class="w-full px-4 py-3 border-2 border-slate-200 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition-all" placeholder="The Wedding of">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Teks Judul Cover</label>
                                    <input wire:model.blur="form.cover_title" type="text" class="w-full px-4 py-3 border-2 border-slate-200 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition-all" placeholder="Andi & Rina">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Foto Cover</label>
                                @if($form->cover_photo)
                                    <div class="relative inline-block">
                                        <img src="{{ asset('storage/' . $form->cover_photo) }}" alt="Cover" class="w-40 h-40 object-cover rounded-xl">
                                        <button wire:click="removeCoverPhoto" type="button" class="absolute -top-2 -right-2 w-8 h-8 bg-red-500 text-white rounded-full flex items-center justify-center shadow-lg hover:bg-red-600">
                                            ✕
                                        </button>
                                    </div>
                                @else
                                    <label class="block w-full border-2 border-dashed border-slate-300 dark:border-slate-600 rounded-xl p-8 text-center cursor-pointer hover:border-rose-400 transition-all">
                                        <input type="file" wire:model="coverPhotoUpload" accept="image/*" class="hidden">
                                        <div class="text-4xl mb-2">📷</div>
                                        <p class="text-slate-500 dark:text-slate-400">Klik untuk upload foto cover</p>
                                        <p class="text-xs text-slate-400 mt-1">Max 5MB (JPG, PNG)</p>
                                    </label>
                                @endif
                                <div wire:loading wire:target="coverPhotoUpload" class="mt-2 text-rose-500">Mengupload...</div>
                            </div>
                        </div>
                    @endif

                    {{-- TAB: MEMPELAI --}}
                    @if($tab === 'mempelai')
                        <div class="space-y-6">
                            <div class="border-b border-slate-200 dark:border-slate-700 pb-4 mb-6">
                                <h2 class="text-xl font-bold text-slate-800 dark:text-white flex items-center gap-2">
                                    💑 Data Mempelai
                                </h2>
                                <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">Informasi kedua mempelai</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Urutan Nama</label>
                                <select wire:model.live="form.name_order" class="w-full px-4 py-3 border-2 border-slate-200 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition-all">
                                    <option value="groom_first">🤵 Pria & 👰 Wanita</option>
                                    <option value="bride_first">👰 Wanita & 🤵 Pria</option>
                                </select>
                            </div>

                            <!-- Mempelai Pria -->
                            <div class="bg-blue-50 dark:bg-blue-900/20 rounded-xl p-5 space-y-4">
                                <h3 class="font-bold text-blue-800 dark:text-blue-200 flex items-center gap-2">
                                    <span class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center text-white">🤵</span>
                                    Mempelai Pria
                                </h3>
                                <div class="grid md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm text-slate-600 dark:text-slate-400 mb-1">Nama Panggilan *</label>
                                        <input wire:model.blur="form.groom_name" type="text" class="w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500" placeholder="Andi">
                                        @error('form.groom_name') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm text-slate-600 dark:text-slate-400 mb-1">Nama Lengkap</label>
                                        <input wire:model.blur="form.groom_fullname" type="text" class="w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700" placeholder="Andi Prasetyo, S.Kom">
                                    </div>
                                    <div>
                                        <label class="block text-sm text-slate-600 dark:text-slate-400 mb-1">Putra dari</label>
                                        <input wire:model.blur="form.groom_parents" type="text" class="w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700" placeholder="Bpk. Budiman & Ibu Siti">
                                    </div>
                                    <div>
                                        <label class="block text-sm text-slate-600 dark:text-slate-400 mb-1">Instagram</label>
                                        <div class="relative">
                                            <span class="absolute left-3 top-2.5 text-slate-400">@</span>
                                            <input wire:model.blur="form.groom_instagram" type="text" class="w-full pl-8 pr-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700" placeholder="username">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Mempelai Wanita -->
                            <div class="bg-pink-50 dark:bg-pink-900/20 rounded-xl p-5 space-y-4">
                                <h3 class="font-bold text-pink-800 dark:text-pink-200 flex items-center gap-2">
                                    <span class="w-8 h-8 bg-pink-500 rounded-lg flex items-center justify-center text-white">👰</span>
                                    Mempelai Wanita
                                </h3>
                                <div class="grid md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm text-slate-600 dark:text-slate-400 mb-1">Nama Panggilan *</label>
                                        <input wire:model.blur="form.bride_name" type="text" class="w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 focus:ring-2 focus:ring-pink-500/20 focus:border-pink-500" placeholder="Rina">
                                        @error('form.bride_name') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm text-slate-600 dark:text-slate-400 mb-1">Nama Lengkap</label>
                                        <input wire:model.blur="form.bride_fullname" type="text" class="w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700" placeholder="Rina Wulandari, S.Pd">
                                    </div>
                                    <div>
                                        <label class="block text-sm text-slate-600 dark:text-slate-400 mb-1">Putri dari</label>
                                        <input wire:model.blur="form.bride_parents" type="text" class="w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700" placeholder="Bpk. Ahmad & Ibu Dewi">
                                    </div>
                                    <div>
                                        <label class="block text-sm text-slate-600 dark:text-slate-400 mb-1">Instagram</label>
                                        <div class="relative">
                                            <span class="absolute left-3 top-2.5 text-slate-400">@</span>
                                            <input wire:model.blur="form.bride_instagram" type="text" class="w-full pl-8 pr-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700" placeholder="username">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- TAB: ACARA --}}
                    @if($tab === 'acara')
                        <div class="space-y-6">
                            <div class="border-b border-slate-200 dark:border-slate-700 pb-4 mb-6">
                                <h2 class="text-xl font-bold text-slate-800 dark:text-white flex items-center gap-2">
                                    📅 Data Acara
                                </h2>
                                <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">Detail waktu dan tempat acara</p>
                            </div>

                            <div class="grid md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Tanggal Utama *</label>
                                    <input wire:model.blur="form.event_date" type="date" class="w-full px-4 py-3 border-2 border-slate-200 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500">
                                    @error('form.event_date') <span class="text-rose-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Jenis Acara</label>
                                    <select wire:model.live="form.event_type" class="w-full px-4 py-3 border-2 border-slate-200 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500">
                                        <option value="both">💒 Akad & 🍽️ Resepsi</option>
                                        <option value="akad_only">💒 Akad Saja</option>
                                        <option value="resepsi_only">🍽️ Resepsi Saja</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Akad -->
                            @if(in_array($form->event_type, ['akad_only', 'both']))
                            <div class="bg-amber-50 dark:bg-amber-900/20 rounded-xl p-5 space-y-4">
                                <h3 class="font-bold text-amber-800 dark:text-amber-200 flex items-center gap-2">
                                    <span class="w-8 h-8 bg-amber-500 rounded-lg flex items-center justify-center text-white">💒</span>
                                    Akad Nikah
                                </h3>
                                <div class="grid md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm text-slate-600 dark:text-slate-400 mb-1">Tanggal</label>
                                        <input wire:model.blur="form.akad_date" type="date" class="w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700">
                                    </div>
                                    <div>
                                        <label class="block text-sm text-slate-600 dark:text-slate-400 mb-1">Waktu</label>
                                        <input wire:model.blur="form.akad_time" type="time" class="w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700">
                                    </div>
                                    <div>
                                        <label class="block text-sm text-slate-600 dark:text-slate-400 mb-1">Nama Tempat</label>
                                        <input wire:model.blur="form.akad_venue" type="text" class="w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700" placeholder="Masjid Al-Ikhlas">
                                    </div>
                                    <div>
                                        <label class="block text-sm text-slate-600 dark:text-slate-400 mb-1">Alamat</label>
                                        <input wire:model.blur="form.akad_address" type="text" class="w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700" placeholder="Jl. Merdeka No. 123">
                                    </div>
                                </div>
                            </div>
                            @endif

                            <!-- Resepsi -->
                            @if(in_array($form->event_type, ['resepsi_only', 'both']))
                            <div class="bg-emerald-50 dark:bg-emerald-900/20 rounded-xl p-5 space-y-4">
                                <h3 class="font-bold text-emerald-800 dark:text-emerald-200 flex items-center gap-2">
                                    <span class="w-8 h-8 bg-emerald-500 rounded-lg flex items-center justify-center text-white">🍽️</span>
                                    Resepsi
                                </h3>
                                <div class="grid md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm text-slate-600 dark:text-slate-400 mb-1">Tanggal</label>
                                        <input wire:model.blur="form.resepsi_date" type="date" class="w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700">
                                    </div>
                                    <div>
                                        <label class="block text-sm text-slate-600 dark:text-slate-400 mb-1">Waktu</label>
                                        <input wire:model.blur="form.resepsi_time" type="time" class="w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700">
                                    </div>
                                    <div>
                                        <label class="block text-sm text-slate-600 dark:text-slate-400 mb-1">Nama Tempat</label>
                                        <input wire:model.blur="form.resepsi_venue" type="text" class="w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700" placeholder="Gedung Serbaguna">
                                    </div>
                                    <div>
                                        <label class="block text-sm text-slate-600 dark:text-slate-400 mb-1">Alamat</label>
                                        <input wire:model.blur="form.resepsi_address" type="text" class="w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700" placeholder="Jl. Mawar No. 456">
                                    </div>
                                </div>
                            </div>
                            @endif

                            <!-- Maps -->
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Link Google Maps</label>
                                <input wire:model.blur="form.maps_url" type="url" class="w-full px-4 py-3 border-2 border-slate-200 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700" placeholder="https://maps.google.com/...">
                                <p class="text-xs text-slate-400 mt-1">Tamu bisa langsung buka lokasi di Maps</p>
                            </div>
                        </div>
                    @endif

                    {{-- TAB: GALLERY --}}
                    @if($tab === 'gallery')
                        <div class="space-y-6">
                            <div class="border-b border-slate-200 dark:border-slate-700 pb-4 mb-6">
                                <h2 class="text-xl font-bold text-slate-800 dark:text-white flex items-center gap-2">
                                    📸 Galeri Foto
                                </h2>
                                <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">Upload foto-foto pernikahan (opsional)</p>
                            </div>

                            <!-- Existing Photos -->
                            @if(count($existingPhotos) > 0)
                                <div>
                                    <h4 class="text-sm font-medium text-slate-600 dark:text-slate-400 mb-3">Foto Tersimpan</h4>
                                    <div class="grid grid-cols-3 md:grid-cols-4 gap-3">
                                        @foreach($existingPhotos as $photo)
                                            <div class="relative group aspect-square rounded-xl overflow-hidden bg-slate-100 dark:bg-slate-700">
                                                <img src="{{ $photo['url'] }}" alt="Photo" class="w-full h-full object-cover">
                                                <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                                    <button wire:click="removeExistingPhoto({{ $photo['id'] }})" type="button" class="w-10 h-10 bg-red-500 text-white rounded-full">✕</button>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Upload New -->
                            <div>
                                <label class="block w-full border-2 border-dashed border-slate-300 dark:border-slate-600 rounded-xl p-8 text-center cursor-pointer hover:border-rose-400 transition-all">
                                    <input type="file" wire:model="photos" accept="image/*" multiple class="hidden">
                                    <div class="text-5xl mb-3">📷</div>
                                    <p class="text-slate-600 dark:text-slate-300 font-medium">Klik untuk upload foto</p>
                                    <p class="text-xs text-slate-400 mt-1">Bisa pilih beberapa foto sekaligus (max 5MB per foto)</p>
                                </label>
                                <div wire:loading wire:target="photos" class="mt-3 text-center text-rose-500">
                                    <svg class="animate-spin w-6 h-6 mx-auto" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                                    <p class="mt-2">Mengupload...</p>
                                </div>
                            </div>

                            <!-- New Photo Previews -->
                            @if(count($photos) > 0)
                                <div>
                                    <h4 class="text-sm font-medium text-slate-600 dark:text-slate-400 mb-3">Foto Baru (belum disimpan)</h4>
                                    <div class="grid grid-cols-3 md:grid-cols-4 gap-3">
                                        @foreach($photos as $index => $photo)
                                            @if($photo)
                                                <div class="relative group aspect-square rounded-xl overflow-hidden bg-slate-100 dark:bg-slate-700 ring-2 ring-rose-500">
                                                    <img src="{{ $photo->temporaryUrl() }}" alt="Preview" class="w-full h-full object-cover">
                                                    <span class="absolute top-2 left-2 px-2 py-1 bg-rose-500 text-white text-xs rounded-full">Baru</span>
                                                    <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                                        <button wire:click="removePhoto({{ $index }})" type="button" class="w-10 h-10 bg-red-500 text-white rounded-full">✕</button>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif

                    {{-- TAB: TAMU --}}
                    @if($tab === 'tamu')
                        <div class="space-y-6">
                            <div class="border-b border-slate-200 dark:border-slate-700 pb-4 mb-6">
                                <h2 class="text-xl font-bold text-slate-800 dark:text-white flex items-center gap-2">
                                    👥 Daftar Tamu
                                </h2>
                                <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">Kelola tamu undangan dan kirim link personal via WhatsApp</p>
                            </div>

                            @if(!$invitationId)
                                <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-xl p-4 flex items-center gap-3">
                                    <span class="text-2xl">⚠️</span>
                                    <p class="text-amber-700 dark:text-amber-300">Simpan undangan terlebih dahulu untuk menambahkan tamu.</p>
                                </div>
                            @else
                                <!-- Quick Add via Text -->
                                <div class="bg-slate-50 dark:bg-slate-700/50 rounded-xl p-5">
                                    <h4 class="font-medium text-slate-700 dark:text-slate-200 mb-3 flex items-center gap-2">
                                        ✏️ Tambah Cepat
                                    </h4>
                                    <p class="text-sm text-slate-500 dark:text-slate-400 mb-3">Pisahkan nama tamu dengan koma atau enter</p>
                                    <textarea 
                                        wire:model="guestInput" 
                                        rows="3" 
                                        class="w-full px-4 py-3 border-2 border-slate-200 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 resize-none text-slate-800 dark:text-white"
                                        placeholder="Bapak Budi, Ibu Siti, Pak Ahmad, Bu Rina"
                                    ></textarea>
                                    <button 
                                        wire:click="addGuestsFromText"
                                        wire:loading.attr="disabled"
                                        class="mt-3 px-5 py-2.5 bg-rose-500 text-white font-medium rounded-lg hover:bg-rose-600 transition-all flex items-center gap-2"
                                    >
                                        <span wire:loading.remove wire:target="addGuestsFromText">+ Tambah Tamu</span>
                                        <span wire:loading wire:target="addGuestsFromText">Menambahkan...</span>
                                    </button>
                                </div>

                                <!-- Upload CSV/Excel -->
                                <div class="bg-slate-50 dark:bg-slate-700/50 rounded-xl p-5">
                                    <h4 class="font-medium text-slate-700 dark:text-slate-200 mb-3 flex items-center gap-2">
                                        📁 Import dari File
                                    </h4>
                                    <p class="text-sm text-slate-500 dark:text-slate-400 mb-3">Upload file CSV dengan kolom: nama, telepon (opsional)</p>
                                    
                                    <div class="flex gap-3 items-end">
                                        <div class="flex-1">
                                            <input 
                                                type="file" 
                                                wire:model="guestFile"
                                                accept=".csv,.txt,.xlsx,.xls"
                                                class="w-full px-4 py-2.5 border-2 border-slate-200 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-sm"
                                            >
                                        </div>
                                        <button 
                                            wire:click="importGuestsFromFile"
                                            wire:loading.attr="disabled"
                                            class="px-5 py-2.5 bg-emerald-500 text-white font-medium rounded-lg hover:bg-emerald-600 transition-all"
                                        >
                                            <span wire:loading.remove wire:target="importGuestsFromFile">📥 Import</span>
                                            <span wire:loading wire:target="importGuestsFromFile">Importing...</span>
                                        </button>
                                    </div>
                                    @error('guestFile') <span class="text-rose-500 text-sm mt-2 block">{{ $message }}</span> @enderror
                                </div>

                                <!-- Guest List -->
                                @if(count($guests) > 0)
                                    <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 overflow-hidden">
                                        <div class="px-5 py-4 border-b border-slate-200 dark:border-slate-700 flex items-center justify-between">
                                            <h4 class="font-medium text-slate-700 dark:text-slate-200">
                                                Daftar Tamu ({{ count($guests) }})
                                            </h4>
                                        </div>
                                        
                                        <div class="overflow-x-auto">
                                            <table class="w-full text-sm">
                                                <thead class="bg-slate-50 dark:bg-slate-700/50">
                                                    <tr>
                                                        <th class="px-4 py-3 text-left font-medium text-slate-600 dark:text-slate-300">#</th>
                                                        <th class="px-4 py-3 text-left font-medium text-slate-600 dark:text-slate-300">Nama</th>
                                                        <th class="px-4 py-3 text-left font-medium text-slate-600 dark:text-slate-300">Status</th>
                                                        <th class="px-4 py-3 text-center font-medium text-slate-600 dark:text-slate-300">Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                                                    @foreach($guests as $index => $guest)
                                                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30">
                                                        <td class="px-4 py-3 text-slate-500">{{ $index + 1 }}</td>
                                                        <td class="px-4 py-3 font-medium text-slate-800 dark:text-white">{{ $guest['name'] }}</td>
                                                        <td class="px-4 py-3">
                                                            @if($guest['status'] === 'confirmed')
                                                                <span class="px-2 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-medium">✓ Hadir</span>
                                                            @elseif($guest['status'] === 'declined')
                                                                <span class="px-2 py-1 bg-red-100 text-red-700 rounded-full text-xs font-medium">✗ Tidak Hadir</span>
                                                            @else
                                                                <span class="px-2 py-1 bg-slate-100 text-slate-600 rounded-full text-xs font-medium">Pending</span>
                                                            @endif
                                                        </td>
                                                        <td class="px-4 py-3">
                                                            <div class="flex items-center justify-center gap-2">
                                                                <!-- Copy Link -->
                                                                <button 
                                                                    onclick="navigator.clipboard.writeText('{{ $this->getGuestShareUrl($guest['name']) }}'); this.innerHTML='✓'; setTimeout(() => this.innerHTML='🔗', 1000);"
                                                                    class="w-8 h-8 bg-slate-100 dark:bg-slate-600 rounded-lg flex items-center justify-center hover:bg-slate-200 dark:hover:bg-slate-500 transition-colors"
                                                                    title="Salin Link"
                                                                >🔗</button>
                                                                
                                                                <!-- WhatsApp -->
                                                                <a 
                                                                    href="{{ $this->getGuestWhatsAppUrl($guest['name'], $guest['phone_number'] ?? null) }}"
                                                                    target="_blank"
                                                                    class="w-8 h-8 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center hover:bg-green-200 dark:hover:bg-green-800/50 transition-colors"
                                                                    title="Kirim WhatsApp"
                                                                >📱</a>
                                                                
                                                                <!-- Delete -->
                                                                <button 
                                                                    wire:click="deleteGuest({{ $guest['id'] }})"
                                                                    wire:confirm="Hapus tamu '{{ $guest['name'] }}'?"
                                                                    class="w-8 h-8 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center hover:bg-red-200 dark:hover:bg-red-800/50 transition-colors"
                                                                    title="Hapus"
                                                                >🗑️</button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                @else
                                    <div class="bg-slate-50 dark:bg-slate-700/50 rounded-xl p-8 text-center">
                                        <div class="w-16 h-16 bg-slate-200 dark:bg-slate-600 rounded-full flex items-center justify-center mx-auto mb-4">
                                            <span class="text-3xl">👥</span>
                                        </div>
                                        <p class="text-slate-500 dark:text-slate-400">Belum ada tamu ditambahkan</p>
                                        <p class="text-slate-400 dark:text-slate-500 text-sm mt-1">Tambahkan tamu menggunakan form di atas</p>
                                    </div>
                                @endif
                            @endif
                        </div>
                    @endif

                    {{-- TAB: SETTINGS --}}
                    @if($tab === 'settings')
                        <div class="space-y-6">
                            <div class="border-b border-slate-200 dark:border-slate-700 pb-4 mb-6">
                                <h2 class="text-xl font-bold text-slate-800 dark:text-white flex items-center gap-2">
                                    ⚙️ Pengaturan
                                </h2>
                                <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">Tema, musik, dan fitur lainnya</p>
                            </div>

                            <!-- Theme Selection -->
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-3">Pilih Tema *</label>
                                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                    @foreach($this->themes as $theme)
                                        <label class="relative cursor-pointer group">
                                            <input type="radio" wire:model.live="form.theme_id" value="{{ $theme->id }}" class="sr-only peer">
                                            <div class="rounded-xl overflow-hidden border-2 transition-all peer-checked:border-rose-500 peer-checked:ring-2 peer-checked:ring-rose-500/30 border-slate-200 dark:border-slate-700 hover:border-rose-300">
                                                <div class="aspect-video bg-gradient-to-br from-rose-100 to-amber-100 dark:from-rose-900/30 dark:to-amber-900/30 flex items-center justify-center">
                                                    <span class="text-4xl">💍</span>
                                                </div>
                                                <div class="p-3 bg-white dark:bg-slate-800">
                                                    <h4 class="font-medium text-slate-800 dark:text-white text-sm">{{ $theme->name }}</h4>
                                                </div>
                                            </div>
                                            <div class="absolute top-2 right-2 w-6 h-6 rounded-full bg-rose-500 text-white flex items-center justify-center opacity-0 peer-checked:opacity-100 transition-opacity">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                                @error('form.theme_id') <span class="text-rose-500 text-sm mt-2 block">{{ $message }}</span> @enderror
                            </div>

                            <!-- Feature Toggles -->
                            <div class="space-y-4">
                                <h4 class="font-medium text-slate-700 dark:text-slate-300">Fitur</h4>
                                
                                <label class="flex items-center justify-between p-4 bg-slate-50 dark:bg-slate-700/50 rounded-xl cursor-pointer">
                                    <div class="flex items-center gap-3">
                                        <span class="text-2xl">⏰</span>
                                        <div>
                                            <p class="font-medium text-slate-700 dark:text-slate-200">Countdown Timer</p>
                                            <p class="text-xs text-slate-500">Tampilkan hitung mundur ke hari H</p>
                                        </div>
                                    </div>
                                    <input type="checkbox" wire:model.live="form.countdown_enabled" class="w-5 h-5 rounded text-rose-500 focus:ring-rose-500">
                                </label>

                                <label class="flex items-center justify-between p-4 bg-slate-50 dark:bg-slate-700/50 rounded-xl cursor-pointer">
                                    <div class="flex items-center gap-3">
                                        <span class="text-2xl">📝</span>
                                        <div>
                                            <p class="font-medium text-slate-700 dark:text-slate-200">RSVP / Konfirmasi</p>
                                            <p class="text-xs text-slate-500">Tamu bisa konfirmasi kehadiran</p>
                                        </div>
                                    </div>
                                    <input type="checkbox" wire:model.live="form.rsvp_enabled" class="w-5 h-5 rounded text-rose-500 focus:ring-rose-500">
                                </label>

                                <label class="flex items-center justify-between p-4 bg-slate-50 dark:bg-slate-700/50 rounded-xl cursor-pointer">
                                    <div class="flex items-center gap-3">
                                        <span class="text-2xl">💬</span>
                                        <div>
                                            <p class="font-medium text-slate-700 dark:text-slate-200">Ucapan & Doa</p>
                                            <p class="text-xs text-slate-500">Tamu bisa kirim ucapan</p>
                                        </div>
                                    </div>
                                    <input type="checkbox" wire:model.live="form.wishes_enabled" class="w-5 h-5 rounded text-rose-500 focus:ring-rose-500">
                                </label>

                                <label class="flex items-center justify-between p-4 bg-slate-50 dark:bg-slate-700/50 rounded-xl cursor-pointer">
                                    <div class="flex items-center gap-3">
                                        <span class="text-2xl">🎵</span>
                                        <div>
                                            <p class="font-medium text-slate-700 dark:text-slate-200">Background Music</p>
                                            <p class="text-xs text-slate-500">Putar musik di undangan</p>
                                        </div>
                                    </div>
                                    <input type="checkbox" wire:model.live="form.music_enabled" class="w-5 h-5 rounded text-rose-500 focus:ring-rose-500">
                                </label>

                                @if($form->music_enabled)
                                    <div class="ml-12">
                                        <label class="block text-sm text-slate-600 dark:text-slate-400 mb-1">URL Musik (MP3)</label>
                                        <input wire:model.blur="form.music_url" type="url" class="w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700" placeholder="https://example.com/music.mp3">
                                    </div>
                                @endif

                                <label class="flex items-center justify-between p-4 bg-slate-50 dark:bg-slate-700/50 rounded-xl cursor-pointer">
                                    <div class="flex items-center gap-3">
                                        <span class="text-2xl">🎁</span>
                                        <div>
                                            <p class="font-medium text-slate-700 dark:text-slate-200">Gift / Angpao Digital</p>
                                            <p class="text-xs text-slate-500">Tampilkan rekening untuk transfer</p>
                                        </div>
                                    </div>
                                    <input type="checkbox" wire:model.live="form.gift_enabled" class="w-5 h-5 rounded text-rose-500 focus:ring-rose-500">
                                </label>

                                @if($form->gift_enabled)
                                    <div class="ml-12 space-y-3">
                                        @foreach($form->gift_accounts as $index => $account)
                                            <div class="flex gap-2 items-start">
                                                <div class="flex-1 grid grid-cols-3 gap-2">
                                                    <input wire:model.blur="form.gift_accounts.{{ $index }}.bank" type="text" class="px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-sm" placeholder="Bank">
                                                    <input wire:model.blur="form.gift_accounts.{{ $index }}.account_number" type="text" class="px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-sm" placeholder="No. Rekening">
                                                    <input wire:model.blur="form.gift_accounts.{{ $index }}.account_name" type="text" class="px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-sm" placeholder="Atas Nama">
                                                </div>
                                                <button wire:click="removeGiftAccount({{ $index }})" type="button" class="w-9 h-9 bg-red-100 text-red-500 rounded-lg hover:bg-red-200">✕</button>
                                            </div>
                                        @endforeach
                                        <button wire:click="addGiftAccount" type="button" class="text-rose-500 text-sm font-medium hover:text-rose-600">+ Tambah Rekening</button>
                                    </div>
                                @endif
                            </div>

                            <!-- Quote -->
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Ayat / Kutipan</label>
                                <textarea wire:model.blur="form.quran_verse" rows="3" class="w-full px-4 py-3 border-2 border-slate-200 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 resize-none" placeholder="QS. Ar-Rum: 21"></textarea>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
