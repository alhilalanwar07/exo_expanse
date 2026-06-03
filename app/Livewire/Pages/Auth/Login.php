<?php

namespace App\Livewire\Pages\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.guest')]
class Login extends Component
{
    public string $email = '';

    public string $password = '';

    public bool $remember = false;

    protected function rules(): array
    {
        return [
            'email' => 'required|email',
            'password' => 'required|string',
        ];
    }

    protected function messages(): array
    {
        return [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ];
    }

    public function login()
    {
        $this->validate();

        $throttleKey = Str::lower($this->email).'|'.request()->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            $this->addError('email', "Terlalu banyak percobaan login. Silakan coba lagi dalam {$seconds} detik.");

            return;
        }

        if (! Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            RateLimiter::hit($throttleKey);
            $this->addError('email', 'Email atau password yang Anda masukkan salah.');

            return;
        }

        if (! Auth::user()?->hasVerifiedEmail()) {
            $unverifiedEmail = Auth::user()->email;
            Auth::logout();
            session()->invalidate();
            session()->regenerateToken();
            RateLimiter::clear($throttleKey);
            session(['verification.email' => $unverifiedEmail]);

            return $this->redirect(route('verification.notice'), navigate: true);
        }

        RateLimiter::clear($throttleKey);
        session()->regenerate();

        if (Auth::user()->role === 'admin') {
            return $this->redirectRoute('admin.dashboard', navigate: true);
        } else {
            return $this->redirectRoute('dashboard', navigate: true);
        }
    }

    public function render()
    {
        return view('livewire.pages.auth.login');
    }
}
