<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LearningIssueReport extends Model
{
    use HasFactory;

    protected $primaryKey = 'issue_report_id';

    protected $fillable = [
        'student_id',
        'course_id',
        'lesson_content_id',
        'assigned_teacher_id',
        'title',
        'issue_type',
        'priority',
        'status',
        'progress_percent',
        'description',
        'deadline_at',
        'resolved_at',
    ];

    protected $casts = [
        'deadline_at' => 'datetime',
        'resolved_at' => 'datetime',
        'progress_percent' => 'integer',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id', 'student_id');
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function lessonContent(): BelongsTo
    {
        return $this->belongsTo(LessonContent::class, 'lesson_content_id');
    }

    public function assignedTeacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class, 'assigned_teacher_id', 'teacher_id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(LearningIssueReply::class, 'issue_report_id', 'issue_report_id');
    }
}
