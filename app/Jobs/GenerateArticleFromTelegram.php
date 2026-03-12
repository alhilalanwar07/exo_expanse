<?php

namespace App\Jobs;

use App\Models\Article;
use App\Models\User;
use App\Services\ArticleGeneratorService;
use App\Services\TelegramBotService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class GenerateArticleFromTelegram implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 2;

    public int $timeout = 180;

    public function __construct(
        private string $chatId,
        private string $title,
        private array $photos,
        private ?string $messageId,
    ) {}

    public function handle(TelegramBotService $telegram, ArticleGeneratorService $generator): void
    {
        try {
            // 1. Download the image
            $fileId = $telegram->getLargestPhotoFileId($this->photos);
            $imagePath = $fileId ? $telegram->downloadFile($fileId) : null;

            // 2. Get or create the default admin user for articles
            $user = User::where('role', 'admin')->first();
            if (! $user) {
                $user = User::first();
            }

            if (! $user) {
                $telegram->sendMessage($this->chatId, '❌ Gagal: Tidak ada user di sistem.');

                return;
            }

            // 3. Create article record with 'generating' status
            $article = Article::create([
                'user_id' => $user->id,
                'title' => $this->title,
                'slug' => Article::generateUniqueSlug($this->title),
                'content' => '',
                'image' => $imagePath,
                'telegram_chat_id' => $this->chatId,
                'telegram_message_id' => $this->messageId,
                'status' => 'generating',
            ]);

            // 4. Generate article content using AI
            $result = $generator->generate($this->title);

            if (! $result) {
                $article->update(['status' => 'failed']);
                $telegram->sendMessage($this->chatId, "❌ <b>Gagal membuat artikel</b>\n\nJudul: <i>{$this->title}</i>\nAI tidak dapat menghasilkan konten. Silakan coba lagi.");

                return;
            }

            // 5. Update article with generated content
            $article->update([
                'content' => $result['content'],
                'excerpt' => $result['excerpt'],
                'meta_description' => $result['meta_description'] ?? null,
                'focus_keyword' => $result['focus_keyword'] ?? null,
                'meta_keywords' => $result['meta_keywords'] ?? null,
                'reading_time' => Article::calculateReadingTime($result['content']),
                'status' => 'published',
                'published_at' => now(),
            ]);

            // 6. Send success notification back to Telegram
            $articleUrl = $article->getUrl();
            $telegram->sendMessage(
                $this->chatId,
                "✅ <b>Artikel berhasil dibuat!</b>\n\n📝 <b>{$article->title}</b>\n🔗 <a href=\"{$articleUrl}\">{$articleUrl}</a>"
            );

            Log::info('Article generated from Telegram', ['article_id' => $article->id, 'title' => $article->title]);
        } catch (\Exception $e) {
            Log::error('GenerateArticleFromTelegram failed', [
                'error' => $e->getMessage(),
                'chat_id' => $this->chatId,
            ]);

            $telegram->sendMessage($this->chatId, "❌ <b>Terjadi kesalahan</b>\n\n{$e->getMessage()}");
        }
    }
}
