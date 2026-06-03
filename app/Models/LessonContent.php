<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class LessonContent extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'course_id',
        'module_number',
        'module_title',
        'title',
        'slug',
        'content_type',
        'summary',
        'body',
        'external_url',
        'file_path',
        'video_url',
        'duration_minutes',
        'position',
        'available_from',
        'available_until',
        'completion_required',
        'visibility',
        'max_score',
        'passing_score',
        'allow_comments',
        'metadata',
        'is_published',
    ];

    protected $casts = [
        'module_number' => 'integer',
        'duration_minutes' => 'integer',
        'position' => 'integer',
        'available_from' => 'datetime',
        'available_until' => 'datetime',
        'completion_required' => 'boolean',
        'max_score' => 'decimal:2',
        'passing_score' => 'decimal:2',
        'allow_comments' => 'boolean',
        'metadata' => 'array',
        'is_published' => 'boolean',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function studentGrades(): HasMany
    {
        return $this->hasMany(StudentLessonGrade::class, 'lesson_content_id');
    }
}
