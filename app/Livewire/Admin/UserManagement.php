<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.admin')]
class UserManagement extends Component
{
    use WithPagination;

    #[Url(history: true)]
    public $search = '';

    public bool $isUserModalOpen = false;
    public bool $isDeleteModalOpen = false;
    
    public ?int $editingUserId = null;
    public ?int $deletingUserId = null;

    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $role = 'user';

    protected function rules()
    {
        return [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users,email,' . $this->editingUserId,
            'password' => $this->editingUserId ? 'nullable|min:8' : 'required|min:8',
            'role' => 'required|in:admin,user',
        ];
    }

    public function updatingSearch()
    {
        $this->resetPage(); // Reset offset paginasi jika lagi ngetik pencarian
    }

    public function createUser()
    {
        $this->resetFields();
        $this->isUserModalOpen = true;
    }

    public function editUser($id)
    {
        $this->resetFields();
        $user = User::findOrFail($id);
        
        $this->editingUserId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->role;
        
        $this->isUserModalOpen = true;
    }

    public function saveUser()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
        ];

        if ($this->password) {
            $data['password'] = \Illuminate\Support\Facades\Hash::make($this->password);
        }

        User::updateOrCreate(['id' => $this->editingUserId], $data);

        $this->isUserModalOpen = false;
        $this->resetFields();
        
        // Optional: you can dispatch a toast notification here
        $this->dispatch('close-modal');
    }

    public function confirmDelete($id)
    {
        $this->deletingUserId = $id;
        $this->isDeleteModalOpen = true;
    }

    public function deleteUser()
    {
        if ($this->deletingUserId) {
            // Prevent deleting yourself
            if (auth()->id() === $this->deletingUserId) {
                $this->isDeleteModalOpen = false;
                return;
            }
            User::findOrFail($this->deletingUserId)->delete();
        }
        $this->isDeleteModalOpen = false;
        $this->deletingUserId = null;
    }

    public function resetFields()
    {
        $this->reset(['name', 'email', 'password', 'role', 'editingUserId']);
        $this->resetValidation();
    }

    public function render()
    {
        $users = User::where('name', 'like', '%' . $this->search . '%')
            ->orWhere('email', 'like', '%' . $this->search . '%')
            ->latest()
            ->paginate(10);

        return view('livewire.admin.user-management', [
            'users' => $users
        ]);
    }
}
