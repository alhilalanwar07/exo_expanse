<?php

namespace App\Console\Commands;

use App\Services\TelegramBotService;
use Illuminate\Console\Command;

class TelegramSetWebhook extends Command
{
    protected $signature = 'telegram:set-webhook {--remove : Remove the webhook}';

    protected $description = 'Set or remove the Telegram bot webhook URL';

    public function handle(TelegramBotService $telegram): int
    {
        if ($this->option('remove')) {
            if ($telegram->removeWebhook()) {
                $this->info('Webhook removed successfully.');
            } else {
                $this->error('Failed to remove webhook.');
            }

            return self::SUCCESS;
        }

        $url = rtrim(config('app.url'), '/').'/api/telegram/webhook';
        $this->info("Setting webhook to: {$url}");

        if ($telegram->setWebhook($url)) {
            $this->info('Webhook set successfully!');
        } else {
            $this->error('Failed to set webhook. Make sure your APP_URL is publicly accessible (use ngrok for local development).');
        }

        return self::SUCCESS;
    }
}
