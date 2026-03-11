<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class TelegramBotService
{
    private string $botToken;
    private string $apiBase;

    public function __construct()
    {
        $this->botToken = config('services.telegram.bot_token');
        $this->apiBase = 'https://api.telegram.org/bot' . $this->botToken;
    }

    /**
     * Send a text message to a Telegram chat.
     */
    public function sendMessage(string $chatId, string $text, ?string $parseMode = 'HTML'): bool
    {
        try {
            $response = Http::timeout(10)->post($this->apiBase . '/sendMessage', [
                'chat_id' => $chatId,
                'text' => $text,
                'parse_mode' => $parseMode,
            ]);

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('Telegram sendMessage failed', ['error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Download a file from Telegram by file_id.
     */
    public function downloadFile(string $fileId): ?string
    {
        try {
            // Get file path from Telegram
            $response = Http::timeout(10)->get($this->apiBase . '/getFile', [
                'file_id' => $fileId,
            ]);

            if (! $response->successful()) {
                return null;
            }

            $filePath = $response->json('result.file_path');
            $downloadUrl = "https://api.telegram.org/file/bot{$this->botToken}/{$filePath}";

            // Download the file
            $fileContent = Http::timeout(30)->get($downloadUrl)->body();
            $extension = pathinfo($filePath, PATHINFO_EXTENSION) ?: 'jpg';
            $storagePath = 'articles/' . uniqid('img_', true) . '.' . $extension;

            Storage::disk('public')->put($storagePath, $fileContent);

            return $storagePath;
        } catch (\Exception $e) {
            Log::error('Telegram file download failed', ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Set the webhook URL for the bot.
     */
    public function setWebhook(string $url): bool
    {
        try {
            $params = [
                'url' => $url,
                'allowed_updates' => ['message'],
            ];

            $secret = config('services.telegram.webhook_secret');
            if ($secret) {
                $params['secret_token'] = $secret;
            }

            $response = Http::timeout(10)->post($this->apiBase . '/setWebhook', $params);

            return $response->successful() && $response->json('ok');
        } catch (\Exception $e) {
            Log::error('Telegram setWebhook failed', ['error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Remove the webhook.
     */
    public function removeWebhook(): bool
    {
        try {
            $response = Http::post($this->apiBase . '/deleteWebhook');
            return $response->successful();
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get the largest photo from a Telegram message.
     */
    public function getLargestPhotoFileId(array $photos): ?string
    {
        if (empty($photos)) {
            return null;
        }

        // Telegram sends multiple sizes, get the largest one
        $largest = collect($photos)->sortByDesc('file_size')->first();

        return $largest['file_id'] ?? null;
    }
}
