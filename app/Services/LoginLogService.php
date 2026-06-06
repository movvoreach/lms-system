<?php

namespace App\Services;

use App\Models\LoginLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class LoginLogService
{
    public function __construct(private readonly TelegramLoginAlertService $telegram)
    {
    }

    public function record(Request $request, ?User $user, string $status): LoginLog
    {
        $ipAddress = $request->ip();
        $userAgent = (string) $request->userAgent();
        $device = $this->device($userAgent);

        $loginLog = LoginLog::create([
            'user_id' => $user?->getKey(),
            'ip_address' => $ipAddress,
            'browser' => $this->browser($userAgent),
            'os' => $this->operatingSystem($userAgent),
            'device' => $device,
            'location' => $this->location($ipAddress),
            'login_at' => now(),
            'status' => $status,
        ]);

        $this->telegram->send($loginLog);

        return $loginLog;
    }

    private function browser(string $userAgent): string
    {
        return match (true) {
            str_contains($userAgent, 'Edg/') => 'Microsoft Edge',
            str_contains($userAgent, 'OPR/'), str_contains($userAgent, 'Opera') => 'Opera',
            str_contains($userAgent, 'Chrome/') => 'Google Chrome',
            str_contains($userAgent, 'Safari/') && str_contains($userAgent, 'Version/') => 'Safari',
            str_contains($userAgent, 'Firefox/') => 'Mozilla Firefox',
            str_contains($userAgent, 'MSIE'), str_contains($userAgent, 'Trident/') => 'Internet Explorer',
            default => 'Unknown',
        };
    }

    private function operatingSystem(string $userAgent): string
    {
        return match (true) {
            str_contains($userAgent, 'Windows NT 10.0') => 'Windows 10/11',
            str_contains($userAgent, 'Windows') => 'Windows',
            str_contains($userAgent, 'Android') => 'Android',
            str_contains($userAgent, 'iPhone'), str_contains($userAgent, 'iPad') => 'iOS',
            str_contains($userAgent, 'Mac OS X') => 'macOS',
            str_contains($userAgent, 'Linux') => 'Linux',
            default => 'Unknown',
        };
    }

    private function device(string $userAgent): string
    {
        return match (true) {
            str_contains($userAgent, 'iPad'), str_contains($userAgent, 'Tablet') => 'Tablet',
            str_contains($userAgent, 'Mobile'), str_contains($userAgent, 'Android'), str_contains($userAgent, 'iPhone') => 'Mobile',
            default => 'Desktop',
        };
    }

    private function location(?string $ipAddress): ?string
    {
        if (blank($ipAddress)) {
            return null;
        }

        if ($this->isPrivateIp($ipAddress)) {
            return 'Local/Private network';
        }

        if (! config('services.telegram.login_location_lookup')) {
            return null;
        }

        try {
            $response = Http::timeout(3)->get("http://ip-api.com/json/{$ipAddress}", [
                'fields' => 'status,country,regionName,city,query,message',
            ]);

            if ($response->failed() || $response->json('status') !== 'success') {
                return null;
            }

            return collect([
                $response->json('city'),
                $response->json('regionName'),
                $response->json('country'),
            ])->filter()->implode(', ');
        } catch (\Throwable $exception) {
            Log::debug('Login location lookup failed.', [
                'ip_address' => $ipAddress,
                'message' => $exception->getMessage(),
            ]);

            return null;
        }
    }

    private function isPrivateIp(string $ipAddress): bool
    {
        return filter_var(
            $ipAddress,
            FILTER_VALIDATE_IP,
            FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE
        ) === false;
    }
}
