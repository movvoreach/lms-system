<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Announcement extends Model
{
    use HasFactory;

    protected $primaryKey = 'announcement_id';

    protected $fillable = [
        'created_by_user_id',
        'course_id',
        'title',
        'message',
        'attachment_path',
        'priority',
        'target_type',
        'status',
        'publish_at',
        'expire_at',
        'published_at',
        'archived_at',
    ];

    protected $casts = [
        'publish_at' => 'datetime',
        'expire_at' => 'datetime',
        'published_at' => 'datetime',
        'archived_at' => 'datetime',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id', 'user_id');
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function getAttachmentUrlAttribute(): ?string
    {
        return $this->attachment_path ? Storage::url($this->attachment_path) : null;
    }

    public function isPublishedNow(): bool
    {
        return $this->status === 'published'
            && (! $this->publish_at || $this->publish_at->lte(now()))
            && (! $this->expire_at || $this->expire_at->gte(now()))
            && ! $this->archived_at;
    }
}
