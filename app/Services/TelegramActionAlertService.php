<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class TelegramActionAlertService
{
    public function send(string $title, array $context = []): void
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
                    'text' => $this->message($title, $context),
                    'parse_mode' => 'HTML',
                    'disable_web_page_preview' => true,
                ]);

            if ($response->failed()) {
                Log::warning('Telegram action alert failed.', [
                    'title' => $title,
                    'status' => $response->status(),
                    'response' => $response->json(),
                ]);
            }
        } catch (\Throwable $exception) {
            Log::warning('Telegram action alert exception.', [
                'title' => $title,
                'message' => $exception->getMessage(),
            ]);
        }
    }

    public function sendForRequest(Request $request, int $statusCode, ?User $actor = null): void
    {
        $routeName = $request->route()?->getName() ?? 'unnamed';

        $this->send($this->titleForRoute($routeName), [
            'Actor' => $actor ? "{$actor->username} ({$actor->email})" : 'Guest',
            'Route' => $routeName,
            'Method' => $request->method(),
            'Path' => '/'.ltrim($request->path(), '/'),
            'IP' => $request->ip() ?? 'N/A',
            'HTTP Status' => (string) $statusCode,
            'Data' => $this->summarizeInput($request),
            'Time' => now()->format('Y-m-d H:i:s'),
        ]);
    }

    private function message(string $title, array $context): string
    {
        $lines = ['<b>'.$this->escape($title).'</b>'];

        foreach ($context as $key => $value) {
            if (blank($value)) {
                continue;
            }

            $lines[] = $this->escape((string) $key).': '.$this->escape($this->stringValue($value));
        }

        return implode("\n", $lines);
    }

    private function titleForRoute(string $routeName): string
    {
        return match (true) {
            $routeName === 'logout' => 'LMS Logout Alert',
            $routeName === 'two-factor.resend' => 'LMS OTP Requested',
            $routeName === 'profile.two-factor.update' => 'LMS Two-Factor Setting Changed',
            Str::contains($routeName, '.users.') => 'LMS User Management Action',
            Str::contains($routeName, '.students.courses.') => 'LMS Student Course Registration',
            Str::contains($routeName, '.students.') => 'LMS Student Management Action',
            Str::contains($routeName, '.teachers.courses.') => 'LMS Teacher Course Assignment',
            Str::contains($routeName, '.teachers.') => 'LMS Teacher Management Action',
            Str::contains($routeName, '.courses.') => 'LMS Course Management Action',
            Str::contains($routeName, '.course-categories.') => 'LMS Course Category Action',
            Str::contains($routeName, '.departments.') => 'LMS Department Management Action',
            Str::contains($routeName, '.faculty.') => 'LMS Faculty Management Action',
            Str::contains($routeName, '.semesters.') => 'LMS Semester Management Action',
            Str::contains($routeName, '.academic-years.') => 'LMS Academic Year Action',
            Str::contains($routeName, '.academic-progression.') => 'LMS Academic Progression Action',
            default => 'LMS System Action Alert',
        };
    }

    private function summarizeInput(Request $request): string
    {
        $data = collect($request->except([
            '_token',
            '_method',
            'password',
            'password_confirmation',
            'current_password',
            'otp',
            'two_factor_code',
        ]))->map(function ($value) {
            if (is_array($value)) {
                return Str::limit(json_encode($value), 180);
            }

            return Str::limit((string) $value, 180);
        });

        if ($data->isEmpty()) {
            return 'N/A';
        }

        return Str::limit($data->map(fn ($value, $key) => "{$key}={$value}")->implode(', '), 900);
    }

    private function stringValue(mixed $value): string
    {
        if (is_array($value)) {
            return json_encode($value);
        }

        return (string) $value;
    }

    private function escape(string $value): string
    {
        return e($value);
    }
}
