<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\GenerateArticleFromTelegram;
use App\Services\TelegramBotService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TelegramWebhookController extends Controller
{
    public function handle(Request $request, TelegramBotService $telegram): JsonResponse
    {
        // Verify webhook secret token
        $secretToken = config('services.telegram.webhook_secret');
        if ($secretToken && $request->header('X-Telegram-Bot-Api-Secret-Token') !== $secretToken) {
            return response()->json(['ok' => false], 403);
        }

        $message = $request->input('message');

        if (! $message) {
            return response()->json(['ok' => true]);
        }

        $chatId = $message['chat']['id'] ?? null;
        $caption = $message['caption'] ?? null;
        $photos = $message['photo'] ?? null;

        // Only process messages with photos + captions
        if (! $photos || ! $caption) {
            if ($chatId) {
                $text = $message['text'] ?? '';

                if (str_starts_with($text, '/start')) {
                    $telegram->sendMessage($chatId, "👋 <b>Selamat datang di ExoInvite Blog Bot!</b>\n\nKirim gambar dengan caption/judul untuk membuat artikel blog otomatis.\n\n<b>Cara pakai:</b>\n📸 Kirim foto + tulis judul di caption\n⏳ Bot akan memproses & generate artikel\n✅ Setelah selesai, link artikel akan dikirim ke sini");
                } else {
                    $telegram->sendMessage($chatId, '📸 Kirim gambar beserta judul di caption untuk membuat artikel.');
                }
            }

            return response()->json(['ok' => true]);
        }

        // Validate chat ID is authorized
        $allowedChatIds = config('services.telegram.allowed_chat_ids', []);
        if (! empty($allowedChatIds) && ! in_array((string) $chatId, $allowedChatIds)) {
            $telegram->sendMessage($chatId, '⛔ Maaf, Anda tidak memiliki akses untuk membuat artikel.');

            return response()->json(['ok' => true]);
        }

        // Send processing notification to user
        $telegram->sendMessage($chatId, "⏳ <b>Memproses artikel...</b>\n\nJudul: <i>{$caption}</i>\n\nSedang mengunduh gambar dan generate artikel. Mohon tunggu...");

        // Dispatch job for async processing
        GenerateArticleFromTelegram::dispatch((string) $chatId, $caption, $photos, (string) ($message['message_id'] ?? ''));

        return response()->json(['ok' => true]);
    }
}
