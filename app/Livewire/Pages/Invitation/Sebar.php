<?php

namespace App\Livewire\Pages\Invitation;

use App\Models\Invitation;
use App\Models\MessageTemplate;
use App\Services\GuestService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Sebar Undangan')]
class Sebar extends Component
{
    public Invitation $invitation;

    // Recipient management
    public array $recipients = [];

    public string $newRecipient = '';

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

    public function addRecipient(): void
    {
        $name = trim($this->newRecipient);

        if (! empty($name) && ! in_array($name, $this->recipients)) {
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

        $this->linksGenerated = true; // Fix typo logic
    }

    public function getPersonalUrl(string $name): string
    {
        return $this->baseUrl.'?kpd='.urlencode($name);
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

            $details[] = 'Pada: Akad Pernikahan';
            $details[] = '📆 Tanggal: '.$akadDate->translatedFormat('d-m-Y');
            if ($akadTime) {
                $details[] = "⏰ Pukul: {$akadTime} - Selesai";
            }
            if ($inv->akad_location) {
                $details[] = "📍 Lokasi: {$inv->akad_location}";
            }
            $details[] = '';
        }

        // Resepsi
        if ($inv->event_date) {
            $receptionDate = Carbon::parse($inv->event_date);
            $receptionTime = $inv->reception_time ? Carbon::parse($inv->reception_time)->format('H:i') : '';

            $details[] = 'Pada: Resepsi Pernikahan';
            $details[] = '📆 Tanggal: '.$receptionDate->translatedFormat('d-m-Y');
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
        if (! $template) {
            return '#';
        }

        // $personalUrl = $this->getPersonalUrl($name); // Inside string interpolation issues
        $personalUrl = $this->baseUrl.'?kpd='.urlencode($name);

        $eventDetails = $this->formatEventDetails();
        $invitationTitle = $this->getInvitationTitle();

        // Replace placeholders
        $message = str_replace(
            ['{nama}', '{judul}', '{detail_acara}', '{link}'],
            [$name, $invitationTitle, $eventDetails, $personalUrl],
            $template->content
        );

        return 'https://wa.me/?text='.urlencode($message);
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
            if (! $exists) {
                $guestService->addGuest($this->invitation, ['name' => $name]);
                $count++;
            }
        }

        if ($count > 0) {
            session()->flash('success', "{$count} penerima berhasil disimpan ke daftar tamu!");
        } else {
            session()->flash('info', 'Semua penerima sudah ada di daftar tamu.');
        }
    }

    public function render()
    {
        return view('livewire.pages.invitation.sebar');
    }
}
