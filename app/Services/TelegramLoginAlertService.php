<?php

namespace App\Services;

use App\Models\LoginLog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramLoginAlertService
{
    public function __construct(private readonly LoginChartImageService $chartImageService)
    {
    }

    public function send(LoginLog $loginLog): void
    {
        $token = config('services.telegram.bot_token');
        $chatId = config('services.telegram.login_alert_chat_id');

        if (blank($token) || blank($chatId)) {
            return;
        }

        try {
            $response = Http::timeout(5)
                ->post("https://api.telegram.org/bot{$token}/sendMessage", [
                    'chat_id' => $chatId,
                    'text' => $this->message($loginLog),
                    'parse_mode' => 'HTML',
                    'disable_web_page_preview' => true,
                ]);

            if ($response->failed()) {
                Log::warning('Telegram login alert failed.', [
                    'login_log_id' => $loginLog->id,
                    'status' => $response->status(),
                    'response' => $response->json(),
                ]);
            }

            if ($loginLog->status === 'success') {
                $this->sendChart($loginLog, $token, $chatId);
            }
        } catch (\Throwable $exception) {
            Log::warning('Telegram login alert exception.', [
                'login_log_id' => $loginLog->id,
                'message' => $exception->getMessage(),
            ]);
        }
    }

    public function sendChartOnly(): void
    {
        $token = config('services.telegram.bot_token');
        $chatId = config('services.telegram.login_alert_chat_id');

        if (blank($token) || blank($chatId)) {
            return;
        }

        $this->sendChart(null, $token, $chatId);
    }

    private function message(LoginLog $loginLog): string
    {
        $loginLog->loadMissing('user.roles');

        $user = $loginLog->user;
        $name = $user?->username ?? 'Unknown user';
        $email = $user?->email ?? 'N/A';
        $roles = $user?->roles?->pluck('role_name')->implode(', ') ?: 'N/A';

        return implode("\n", [
            '<b>LMS Login Alert</b>',
            'Status: '.$this->escape($loginLog->status),
            'User: '.$this->escape($name),
            'Email: '.$this->escape($email),
            'Roles: '.$this->escape($roles),
            'IP: '.$this->escape($loginLog->ip_address ?? 'N/A'),
            'Location: '.$this->escape($loginLog->location ?? 'N/A'),
            'Browser: '.$this->escape($loginLog->browser ?? 'N/A'),
            'OS: '.$this->escape($loginLog->os ?? 'N/A'),
            'Device: '.$this->escape($loginLog->device ?? 'N/A'),
            'Time: '.$this->escape($loginLog->login_at->format('Y-m-d H:i:s')),
        ]);
    }

    private function escape(string $value): string
    {
        return e($value);
    }

    private function sendChart(?LoginLog $loginLog, string $token, string $chatId): void
    {
        $path = $this->chartImageService->generateDailyLoginChart();

        if (blank($path) || ! is_file($path)) {
            return;
        }

        $handle = fopen($path, 'r');

        if ($handle === false) {
            return;
        }

        try {
            $caption = $loginLog
                ? 'Daily login chart after login #'.$loginLog->id
                : 'Daily login chart test';

            $response = Http::timeout(10)
                ->attach('photo', $handle, basename($path))
                ->post("https://api.telegram.org/bot{$token}/sendPhoto", [
                    'chat_id' => $chatId,
                    'caption' => $caption,
                ]);

            if ($response->failed()) {
                Log::warning('Telegram login chart failed.', [
                    'login_log_id' => $loginLog?->id,
                    'status' => $response->status(),
                    'response' => $response->json(),
                ]);
            }
        } catch (\Throwable $exception) {
            Log::warning('Telegram login chart exception.', [
                'login_log_id' => $loginLog?->id,
                'message' => $exception->getMessage(),
            ]);
        } finally {
            fclose($handle);
        }
    }
}
