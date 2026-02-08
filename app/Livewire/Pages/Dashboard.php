<?php

namespace App\Livewire\Pages;

use App\Models\Invitation;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Dashboard extends Component
{
    // Delete modal
    public bool $confirmingInvitationDeletion = false;

    public ?int $invitationIdToDelete = null;

    public string $invitationTitleToDelete = '';

    // Share modal
    public bool $showingShareModal = false;

    public string $shareInvitationTitle = '';

    public string $shareInvitationSlug = '';

    public string $shareRecipientName = '';

    public function confirmDeletion(int $id, string $title): void
    {
        $this->invitationIdToDelete = $id;
        $this->invitationTitleToDelete = $title;
        $this->confirmingInvitationDeletion = true;
    }

    public function cancelDeletion(): void
    {
        $this->confirmingInvitationDeletion = false;
        $this->invitationIdToDelete = null;
    }

    public function deleteInvitation(): void
    {
        if ($this->invitationIdToDelete) {
            $invitation = Invitation::where('user_id', Auth::id())->findOrFail($this->invitationIdToDelete);
            $invitation->delete();

            session()->flash('message', 'Undangan berhasil dihapus.');
        }

        $this->confirmingInvitationDeletion = false;
        $this->invitationIdToDelete = null;
    }

    public function openShareModal(string $title, string $slug): void
    {
        $this->shareInvitationTitle = $title;
        $this->shareInvitationSlug = $slug;
        $this->shareRecipientName = '';
        $this->showingShareModal = true;
    }

    public function closeShareModal(): void
    {
        $this->showingShareModal = false;
        $this->shareRecipientName = '';
    }

    public function getShareUrl(): string
    {
        $baseUrl = route('invitation.show', $this->shareInvitationSlug);

        if (! empty($this->shareRecipientName)) {
            return $baseUrl.'?kpd='.urlencode($this->shareRecipientName);
        }

        return $baseUrl;
    }

    public function getWhatsAppUrl(): string
    {
        $invitationUrl = $this->getShareUrl();
        $message = "Assalamualaikum Wr. Wb.\n\nDengan penuh sukacita, kami mengundang Bapak/Ibu/Saudara/i untuk hadir di acara pernikahan kami.\n\nKlik link berikut untuk membuka undangan:\n{$invitationUrl}\n\nTerima kasih 🙏";

        return 'https://wa.me/?text='.urlencode($message);
    }

    public function render()
    {
        return view('livewire.pages.dashboard', [
            'invitations' => Invitation::where('user_id', Auth::id())
                ->with('theme')
                ->withCount(['wishes'])
                ->withCount([
                    'guests as guests_count',
                    'guests as guests_confirmed_count' => function ($query) {
                        $query->where('status', 'confirmed');
                    },
                    'guests as guests_declined_count' => function ($query) {
                        $query->where('status', 'declined');
                    },
                ])
                ->latest()
                ->get(),
        ]);
    }
}
