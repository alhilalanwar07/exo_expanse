<?php

namespace App\Livewire\Admin;

use App\Models\BackgroundMusic;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

#[Layout('components.layouts.admin')]
class MusicManager extends Component
{
    use WithFileUploads, WithPagination;

    public $title;
    public $artist;
    public $musicFile; // Uploaded mp3/wav
    public $isActive = true;

    public $editingMusicId = null;
    public $search = '';

    public function mount()
    {
        // Custom initialization if needed
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function save()
    {
        $rules = [
            'title' => 'required|string|max:255',
            'artist' => 'nullable|string|max:255',
        ];

        $audioRule = 'file|extensions:mp3,wav,ogg,m4a|mimetypes:audio/mpeg,audio/mp3,audio/wav,audio/x-wav,audio/wave,audio/ogg,audio/mp4,audio/x-m4a,audio/aac,application/octet-stream|max:10240';

        if ($this->editingMusicId) {
            $rules['musicFile'] = 'nullable|'.$audioRule;
        } else {
            $rules['musicFile'] = 'required|'.$audioRule;
        }

        $this->validate($rules, [
            'musicFile.required' => 'File musik wajib diunggah!',
            'musicFile.extensions' => 'Format file harus berupa MP3, WAV, OGG, atau M4A.',
            'musicFile.mimetypes' => 'Format file harus berupa audio (MP3, WAV, OGG, atau M4A).',
            'musicFile.max' => 'Ukuran file maksimal 10MB.',
            'title.required' => 'Judul lagu wajib diisi.',
        ]);

        $path = null;
        if ($this->musicFile) {
            $path = $this->musicFile->store('music', 'public');
        }

        if ($this->editingMusicId) {
            $music = BackgroundMusic::findOrFail($this->editingMusicId);
            $data = [
                'title' => $this->title,
                'artist' => $this->artist,
                'is_active' => $this->isActive,
            ];

            if ($path) {
                // Hapus file lama jika di-upload ulang file baru
                $oldPath = $music->storage_path;
                if ($oldPath && Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
                $data['file_path'] = $path;
            }
            
            $music->update($data);
            session()->flash('message', 'Lagu berhasil diperbarui!');
        } else {
            BackgroundMusic::create([
                'title' => $this->title,
                'artist' => $this->artist,
                'file_path' => $path,
                'is_active' => $this->isActive,
            ]);
            session()->flash('message', 'Lagu berhasil ditambahkan ke pustaka!');
        }

        $this->resetInput();
    }

    public function edit($id)
    {
        $this->resetValidation();
        $music = BackgroundMusic::findOrFail($id);
        
        $this->editingMusicId = $music->id;
        $this->title = $music->title;
        $this->artist = $music->artist;
        $this->isActive = $music->is_active;
        $this->musicFile = null;
    }

    public function toggleActive($id)
    {
        $music = BackgroundMusic::findOrFail($id);
        $music->update(['is_active' => !$music->is_active]);
        $status = $music->is_active ? 'diaktifkan' : 'dinonaktifkan';
        session()->flash('message', "Lagu berhasil {$status}.");
    }

    public function delete($id)
    {
        $music = BackgroundMusic::findOrFail($id);

        $filePath = $music->storage_path;
        if ($filePath && Storage::disk('public')->exists($filePath)) {
            Storage::disk('public')->delete($filePath);
        }

        $music->delete();
        session()->flash('message', 'Lagu dan file berhasil dihapus selamanya.');
    }

    public function resetInput()
    {
        $this->reset(['title', 'artist', 'musicFile', 'isActive', 'editingMusicId']);
        $this->resetValidation();
    }

    public function render()
    {
        $musics = BackgroundMusic::where('title', 'like', "%{$this->search}%")
            ->orWhere('artist', 'like', "%{$this->search}%")
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('livewire.admin.music-manager', [
            'musics' => $musics
        ]);
    }
}
