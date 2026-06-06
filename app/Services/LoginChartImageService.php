<?php

namespace App\Services;

use App\Models\LoginLog;
use Carbon\CarbonPeriod;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class LoginChartImageService
{
    public function generateDailyLoginChart(): ?string
    {
        if (! extension_loaded('gd')) {
            Log::warning('Cannot generate login chart because PHP GD is not enabled.');

            return null;
        }

        $days = collect(CarbonPeriod::create(now()->subDays(6)->startOfDay(), now()->startOfDay()))
            ->map(fn ($date) => $date->format('Y-m-d'));

        $logs = LoginLog::query()
            ->where('status', 'success')
            ->where('login_at', '>=', now()->subDays(6)->startOfDay())
            ->get(['user_id', 'login_at']);

        $dailyLogins = $this->dailyCounts($days, $logs);
        $dailyActiveUsers = $this->dailyActiveUsers($days, $logs);

        $path = storage_path('app/private/telegram/daily-login-chart.png');
        $directory = dirname($path);

        if (! is_dir($directory)) {
            mkdir($directory, 0775, true);
        }

        $this->drawChart($path, $days, $dailyLogins, $dailyActiveUsers);

        return $path;
    }

    private function dailyCounts(Collection $days, Collection $logs): Collection
    {
        $grouped = $logs->groupBy(fn (LoginLog $log) => $log->login_at->format('Y-m-d'));

        return $days->mapWithKeys(fn (string $day) => [$day => $grouped->get($day, collect())->count()]);
    }

    private function dailyActiveUsers(Collection $days, Collection $logs): Collection
    {
        $grouped = $logs->groupBy(fn (LoginLog $log) => $log->login_at->format('Y-m-d'));

        return $days->mapWithKeys(function (string $day) use ($grouped) {
            return [$day => $grouped->get($day, collect())->pluck('user_id')->filter()->unique()->count()];
        });
    }

    private function drawChart(string $path, Collection $days, Collection $dailyLogins, Collection $dailyActiveUsers): void
    {
        $width = 980;
        $height = 560;
        $paddingLeft = 78;
        $paddingRight = 42;
        $paddingTop = 92;
        $paddingBottom = 98;
        $chartWidth = $width - $paddingLeft - $paddingRight;
        $chartHeight = $height - $paddingTop - $paddingBottom;

        $image = imagecreatetruecolor($width, $height);

        $white = imagecolorallocate($image, 255, 255, 255);
        $ink = imagecolorallocate($image, 31, 41, 55);
        $muted = imagecolorallocate($image, 107, 114, 128);
        $grid = imagecolorallocate($image, 229, 231, 235);
        $blue = imagecolorallocate($image, 37, 99, 235);
        $green = imagecolorallocate($image, 5, 150, 105);
        $orange = imagecolorallocate($image, 245, 158, 11);

        imagefill($image, 0, 0, $white);
        imagestring($image, 5, 34, 28, 'LMS Login Activity - Last 7 Days', $ink);
        imagestring($image, 3, 34, 56, 'Blue: total successful logins    Green: unique active users', $muted);

        $maxValue = max(1, $dailyLogins->max(), $dailyActiveUsers->max());
        $maxValue = max(5, (int) ceil($maxValue / 5) * 5);

        for ($i = 0; $i <= 5; $i++) {
            $value = (int) round($maxValue - (($maxValue / 5) * $i));
            $y = $paddingTop + (int) round(($chartHeight / 5) * $i);

            imageline($image, $paddingLeft, $y, $width - $paddingRight, $y, $grid);
            imagestring($image, 2, 24, $y - 7, (string) $value, $muted);
        }

        imageline($image, $paddingLeft, $paddingTop, $paddingLeft, $height - $paddingBottom, $ink);
        imageline($image, $paddingLeft, $height - $paddingBottom, $width - $paddingRight, $height - $paddingBottom, $ink);

        $barGroupWidth = $chartWidth / $days->count();
        $barWidth = max(18, (int) floor($barGroupWidth * 0.24));

        $days->values()->each(function (string $day, int $index) use (
            $image,
            $dailyLogins,
            $dailyActiveUsers,
            $paddingLeft,
            $paddingTop,
            $paddingBottom,
            $height,
            $chartHeight,
            $barGroupWidth,
            $barWidth,
            $maxValue,
            $blue,
            $green,
            $muted,
            $orange
        ) {
            $centerX = $paddingLeft + (int) round(($barGroupWidth * $index) + ($barGroupWidth / 2));
            $baseY = $height - $paddingBottom;
            $loginValue = (int) $dailyLogins->get($day, 0);
            $activeValue = (int) $dailyActiveUsers->get($day, 0);
            $loginHeight = (int) round(($loginValue / $maxValue) * $chartHeight);
            $activeHeight = (int) round(($activeValue / $maxValue) * $chartHeight);

            imagefilledrectangle($image, $centerX - $barWidth - 3, $baseY - $loginHeight, $centerX - 3, $baseY, $blue);
            imagefilledrectangle($image, $centerX + 3, $baseY - $activeHeight, $centerX + $barWidth + 3, $baseY, $green);

            imagestring($image, 2, $centerX - $barWidth - 3, max($paddingTop, $baseY - $loginHeight - 16), (string) $loginValue, $orange);
            imagestring($image, 2, $centerX + 6, max($paddingTop, $baseY - $activeHeight - 16), (string) $activeValue, $orange);
            imagestring($image, 2, $centerX - 25, $baseY + 16, Carbon::parse($day)->format('M d'), $muted);
        });

        imagestring($image, 3, 34, $height - 42, 'Generated: '.now()->format('Y-m-d H:i:s'), $muted);

        imagepng($image, $path);
        imagedestroy($image);
    }
}
