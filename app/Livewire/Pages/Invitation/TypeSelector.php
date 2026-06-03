<?php

namespace App\Livewire\Pages\Invitation;

use App\Enums\InvitationType;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class TypeSelector extends Component
{
    public function select(string $type)
    {
        $invitationType = InvitationType::tryFrom($type);

        if (! $invitationType || ! $invitationType->isAvailable()) {
            session()->flash('error', 'Mohon maaf, tipe undangan ini belum tersedia. Coming soon!');

            return;
        }

        return $this->redirect(route('invitations.create', ['type' => $type]), navigate: false);
    }

    public function render()
    {
        $grouped = InvitationType::grouped();

        return view('livewire.pages.invitation.type-selector', [
            'availableTypes' => $grouped['available'],
            'comingSoonTypes' => $grouped['coming_soon'],
        ]);
    }
}
