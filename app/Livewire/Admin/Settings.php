<?php

namespace App\Livewire\Admin;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.admin')]
class Settings extends Component
{
    public string $appName = 'ExoInvite';

    public string $supportEmail = 'support@exoinvite.com';

    public bool $enableRegistration = true;

    public bool $maintenanceMode = false;

    public function saveSettings()
    {
        $this->validate([
            'appName' => 'required|min:3',
            'supportEmail' => 'required|email',
        ]);

        // Karena ini demo, di dunia nyata Anda bisa simpan ini ke database Settings
        // atau update .env file / cache

        $this->dispatch('toast', message: 'Pengaturan berhasil diperbarui!', type: 'success');
    }

    public function render()
    {
        return view('livewire.admin.settings');
    }
}
