<?php

namespace App\Livewire\Pages\Invitation;

use App\Models\Invitation;
use App\Models\MessageTemplate;
use App\Services\GuestImportService;
use App\Services\GuestService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Sebar Undangan')]
class Sebar extends Component
{
    #[Locked]
    public Invitation $invitation;

    // Recipient management
    public array $recipients = [];

    public string $newRecipient = '';

    public string $searchRecipient = '';

    public bool $linksGenerated = false;

    // Message template
    public ?int $selectedTemplateId = null;

    public function mount($id): void
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
        // Handle case where specific template ID is not set or not found
        if ($this->selectedTemplateId) {
            return MessageTemplate::find($this->selectedTemplateId);
        }

        return null;
    }

    #[Computed]
    public function baseUrl(): string
    {
        return route('invitation.show', $this->invitation->slug);
    }

    #[Computed]
    public function existingGuests(): array
    {
        return $this->invitation->guests()->pluck('name')->map(fn ($n) => mb_strtolower($n))->toArray();
    }

    public function isExistingGuest(string $name): bool
    {
        return in_array(mb_strtolower($name), $this->existingGuests);
    }

    #[Computed]
    public function groupedRecipients(): array
    {
        $search = mb_strtolower(trim($this->searchRecipient));
        $grouped = [];
        foreach ($this->recipients as $index => $recipient) {
            if ($search !== '' && ! str_contains(mb_strtolower($recipient['name']), $search)) {
                continue;
            }
            $date = $recipient['created_at'] ?? now()->format('Y-m-d');
            $grouped[$date][] = ['name' => $recipient['name'], 'index' => $index];
        }
        krsort($grouped);

        return $grouped;
    }

    public function getRecipientNames(): array
    {
        return array_column($this->recipients, 'name');
    }

    public function addRecipient(): void
    {
        $name = trim($this->newRecipient);
        $existingNames = $this->getRecipientNames();

        if (! empty($name) && ! in_array($name, $existingNames)) {
            $this->recipients[] = ['name' => $name, 'created_at' => now()->format('Y-m-d')];
            $this->linksGenerated = false;
        }

        $this->newRecipient = '';
    }

    public function loadExistingGuests(): void
    {
        $guests = $this->invitation->guests()->select('name', 'created_at')->get();
        $existingNames = $this->getRecipientNames();
        $added = 0;

        foreach ($guests as $guest) {
            if (! in_array($guest->name, $existingNames)) {
                $this->recipients[] = [
                    'name' => $guest->name,
                    'created_at' => $guest->created_at->format('Y-m-d'),
                ];
                $added++;
            }
        }

        $this->linksGenerated = false;

        if ($added > 0) {
            $this->dispatch('toast', message: "{$added} tamu berhasil dimuat ke daftar penerima.", type: 'success');
        } else {
            $this->dispatch('toast', message: 'Semua tamu sudah ada di daftar penerima.', type: 'info');
        }
    }

    public function removeRecipient(int $index): void
    {
        if (isset($this->recipients[$index])) {
            unset($this->recipients[$index]);
            $this->recipients = array_values($this->recipients);
        }
    }

    public function removeByDate(string $date): void
    {
        $this->recipients = array_values(array_filter(
            $this->recipients,
            fn ($r) => ($r['created_at'] ?? '') !== $date
        ));
        $this->linksGenerated = false;
        $this->dispatch('toast', message: 'Penerima tanggal '.Carbon::parse($date)->translatedFormat('d M Y').' telah dihapus.', type: 'success');
    }

    public function generateLinks(): void
    {
        if (empty($this->recipients)) {
            session()->flash('error', 'Tambahkan minimal 1 penerima terlebih dahulu');

            return;
        }

        $this->linksGenerated = true; // Fix typo logic
    }

    public function getPersonalUrl(string $name): string
    {
        return $this->baseUrl.'?kpd='.urlencode($name);
    }

    public function getWhatsAppUrl(string $name): string
    {
        $template = $this->selectedTemplate;
        if (! $template) {
            return '#';
        }

        $service = app(GuestImportService::class);

        return $service->generateWhatsAppUrl(
            $this->invitation,
            $this->baseUrl,
            $name,
            null,
            $template->content
        );
    }

    /**
     * Get preview of message for display.
     */
    public function getMessagePreview(): string
    {
        $template = $this->selectedTemplate;
        if (! $template) {
            return '';
        }

        $service = app(GuestImportService::class);
        $recipientName = trim($this->newRecipient) ?: '[Nama Penerima]';
        $invitationTitle = $service->getInvitationTitle($this->invitation);
        $eventDetails = $service->formatEventDetails($this->invitation);

        return str_replace(
            ['{nama}', '{judul}', '{detail_acara}', '{link}'],
            [$recipientName, $invitationTitle, $eventDetails, $this->baseUrl.'?kpd=...'],
            $template->content
        );
    }

    public function saveToGuestList(): void
    {
        $guestService = app(GuestService::class);

        $count = 0;
        foreach ($this->recipients as $recipient) {
            $name = $recipient['name'];
            $exists = $this->invitation->guests()->where('name', $name)->exists();
            if (! $exists) {
                $guestService->addGuest($this->invitation, ['name' => $name]);
                $count++;
            }
        }

        if ($count > 0) {
            $this->dispatch('toast', message: "{$count} penerima berhasil disimpan ke daftar tamu!", type: 'success');
        } else {
            $this->dispatch('toast', message: 'Semua penerima sudah ada di daftar tamu.', type: 'info');
        }
    }

    public function render()
    {
        return view('livewire.pages.invitation.sebar');
    }
}
