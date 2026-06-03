<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    public const UPDATED_AT = null;

    protected $fillable = [
        'user_id',
        'action',
        'module',
        'description',
        'ip_address',
        'user_agent',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public static function actionMeta(string $action): array
    {
        return match ($action) {
            'login' => ['badge' => 'success', 'icon' => 'fas fa-sign-in-alt'],
            'logout' => ['badge' => 'secondary', 'icon' => 'fas fa-sign-out-alt'],
            'create' => ['badge' => 'primary', 'icon' => 'fas fa-plus-circle'],
            'update' => ['badge' => 'info', 'icon' => 'fas fa-edit'],
            'delete' => ['badge' => 'danger', 'icon' => 'fas fa-trash'],
            'upload' => ['badge' => 'warning', 'icon' => 'fas fa-upload'],
            'download' => ['badge' => 'dark', 'icon' => 'fas fa-download'],
            'role_permission' => ['badge' => 'purple', 'icon' => 'fas fa-user-shield'],
            'password_change' => ['badge' => 'warning', 'icon' => 'fas fa-key'],
            default => ['badge' => 'light', 'icon' => 'fas fa-history'],
        };
    }
}
