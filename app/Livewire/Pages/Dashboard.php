<?php

namespace App\Livewire\Pages;

use App\Models\Invitation;
use App\Models\Theme;
use App\Services\GuestImportService;
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

    // Theme modal
    public bool $showingThemeModal = false;

    public ?int $invitationIdToChangeTheme = null;

    public string $invitationTitleToChangeTheme = '';

    public ?int $selectedThemeId = null;

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

            $this->dispatch('toast', message: 'Undangan berhasil dihapus.', type: 'success');
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
        $invitation = Invitation::where('slug', $this->shareInvitationSlug)->first();
        if (! $invitation) {
            return '#';
        }

        $invitationUrl = route('invitation.show', $this->shareInvitationSlug);
        $importService = app(GuestImportService::class);
        $recipientName = empty($this->shareRecipientName) ? '[Nama Tamu]' : $this->shareRecipientName;

        return $importService->generateWhatsAppUrl($invitation, $invitationUrl, $recipientName);
    }

    public function openThemeModal(int $id, string $title, ?int $currentThemeId): void
    {
        $this->invitationIdToChangeTheme = $id;
        $this->invitationTitleToChangeTheme = $title;
        $this->selectedThemeId = $currentThemeId;
        $this->showingThemeModal = true;
    }

    public function closeThemeModal(): void
    {
        $this->showingThemeModal = false;
        $this->invitationIdToChangeTheme = null;
    }

    public function updateTheme(): void
    {
        if ($this->invitationIdToChangeTheme && $this->selectedThemeId) {
            $invitation = Invitation::where('user_id', Auth::id())->findOrFail($this->invitationIdToChangeTheme);
            $invitation->update(['theme_id' => $this->selectedThemeId]);

            $this->dispatch('toast', message: 'Tema berhasil diubah.', type: 'success');
            $this->closeThemeModal();
        }
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
            'themes' => Theme::where('is_active', true)->get(),
        ]);
    }
}
