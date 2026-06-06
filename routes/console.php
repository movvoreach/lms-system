<?php

use Illuminate\Foundation\Inspiring;
use App\Services\TelegramActionAlertService;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('telegram:chat-id', function () {
    $token = config('services.telegram.bot_token');

    if (blank($token)) {
        $this->error('TELEGRAM_BOT_TOKEN is not set.');

        return 1;
    }

    try {
        $response = Http::timeout(10)->get("https://api.telegram.org/bot{$token}/getUpdates");
    } catch (\Throwable $exception) {
        $this->error('Could not connect to Telegram: '.$exception->getMessage());

        return 1;
    }

    if ($response->failed() || ! $response->json('ok')) {
        $this->error('Could not read Telegram updates.');
        $this->line($response->body());

        return 1;
    }

    $updates = collect($response->json('result', []));

    if ($updates->isEmpty()) {
        $this->warn('No Telegram messages found. Send /start to your bot, then run this command again.');

        return 0;
    }

    $updates
        ->map(fn (array $update) => $update['message']['chat'] ?? $update['channel_post']['chat'] ?? null)
        ->filter()
        ->unique('id')
        ->each(function (array $chat) {
            $this->line('Chat ID: '.$chat['id']);
            $this->line('Type: '.($chat['type'] ?? 'unknown'));
            $this->line('Name: '.collect([$chat['first_name'] ?? null, $chat['last_name'] ?? null, $chat['title'] ?? null])->filter()->implode(' '));
            $this->newLine();
        });

    return 0;
})->purpose('Show Telegram chat IDs from recent bot messages');

Artisan::command('telegram:test-alert', function () {
    app(TelegramActionAlertService::class)->send('LMS Telegram Test Alert', [
        'Message' => 'Telegram action alerts are working.',
        'Time' => now()->format('Y-m-d H:i:s'),
    ]);

    $this->info('Telegram test alert sent.');

    return 0;
})->purpose('Send a test Telegram alert');
