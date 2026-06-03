<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\MobileAccessService;
use Illuminate\Console\Command;

class IssueMobileAccessCode extends Command
{
    protected $signature = 'mobile:access-code
        {userId : ID user owner}
        {--device= : Alias perangkat, contoh iPhone Budi}
        {--platform=ios : Platform perangkat (ios/android/web)}';

    protected $description = 'Generate one-time access code untuk koneksi mobile owner';

    public function handle(MobileAccessService $mobileAccessService): int
    {
        $user = User::query()->find($this->argument('userId'));

        if (! $user) {
            $this->error('User tidak ditemukan.');

            return self::FAILURE;
        }

        $payload = $mobileAccessService->issueAccessCode(
            $user,
            $this->option('device'),
            $this->option('platform')
        );

        $this->info('Access code berhasil dibuat.');
        $this->line('User: '.$user->name.' (ID '.$user->id.')');
        $this->line('Access code: '.$payload['access_code']);
        $this->line('Expires at: '.$payload['expires_at']);

        return self::SUCCESS;
    }
}
