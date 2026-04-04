<?php

namespace App\Livewire\Admin;

use App\Models\Invitation;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.admin')]
class InvitationManagement extends Component
{
    use WithPagination;

    #[Url(history: true)]
    public $search = '';

    public bool $isDeleteModalOpen = false;

    public ?int $deletingInvitationId = null;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function confirmDelete($id)
    {
        $this->deletingInvitationId = $id;
        $this->isDeleteModalOpen = true;
    }

    public function deleteInvitation()
    {
        if ($this->deletingInvitationId) {
            $invitation = Invitation::findOrFail($this->deletingInvitationId);
            $invitation->delete();
            $this->dispatch('toast', message: 'Undangan berhasil dihapus.', type: 'success');
        }
        $this->isDeleteModalOpen = false;
        $this->deletingInvitationId = null;
    }

    public function render()
    {
        // Fitur pencarian mencari berdasar judul/slug atau nama user
        $invitations = Invitation::with(['user:id,name', 'theme:id,name'])
            ->when($this->search, function ($query) {
                $query->where('title', 'like', '%'.$this->search.'%')
                    ->orWhere('slug', 'like', '%'.$this->search.'%')
                    ->orWhereHas('user', function ($q) {
                        $q->where('name', 'like', '%'.$this->search.'%');
                    });
            })
            ->latest()
            ->paginate(10);

        return view('livewire.admin.invitation-management', [
            'invitations' => $invitations,
        ]);
    }
}
