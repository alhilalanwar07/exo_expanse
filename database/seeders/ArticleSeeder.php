<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\User;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('role', 'admin')->first() ?? User::first();

        if (! $user) {
            $user = User::factory()->create(['role' => 'admin']);
        }

        Article::create([
            'user_id' => $user->id,
            'title' => 'Selamat Datang di Blog ExoInvite',
            'slug' => Article::generateUniqueSlug('Selamat Datang di Blog ExoInvite'),
            'excerpt' => 'Blog ini berisi artikel menarik yang dibuat otomatis melalui Telegram Bot dan AI.',
            'content' => "## Pendahuluan\n\nSelamat datang di blog ExoInvite! Blog ini adalah fitur terbaru yang memungkinkan pembuatan artikel secara otomatis melalui Telegram Bot.\n\n## Cara Kerja\n\nCukup kirim gambar beserta judul ke Telegram Bot kami, dan AI akan membuatkan artikel lengkap untuk Anda. Artikel akan otomatis dipublikasikan di website ini.\n\n## Teknologi\n\nKami menggunakan **Meta LLaMA 3.3 70B** untuk menghasilkan konten berkualitas tinggi dalam Bahasa Indonesia.\n\n## Kesimpulan\n\nNikmati pengalaman blogging yang lebih mudah dan efisien dengan ExoInvite Blog Bot!",
            'status' => 'published',
            'published_at' => now(),
        ]);

        $this->command->info('Test article created successfully!');
    }
}
