<?php

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

if (! function_exists('activity_log')) {
    function activity_log(string $action, string $module, string $description): ?ActivityLog
    {
        try {
            return ActivityLog::query()->create([
                'user_id' => Auth::id(),
                'action' => str($action)->lower()->replace(' ', '_')->limit(40, '')->toString(),
                'module' => str($module)->limit(80, '')->toString(),
                'description' => $description,
                'ip_address' => request()?->ip(),
                'user_agent' => request()?->userAgent(),
            ]);
        } catch (Throwable) {
            return null;
        }
    }
}
