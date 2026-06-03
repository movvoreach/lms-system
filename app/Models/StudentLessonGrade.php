<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentLessonGrade extends Model
{
    use HasFactory;

    protected $primaryKey = 'grade_id';

    protected $fillable = [
        'student_id',
        'course_id',
        'lesson_content_id',
        'teacher_id',
        'score',
        'max_score',
        'passed',
        'feedback',
        'graded_at',
    ];

    protected $casts = [
        'score' => 'decimal:2',
        'max_score' => 'decimal:2',
        'passed' => 'boolean',
        'graded_at' => 'datetime',
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

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class, 'teacher_id', 'teacher_id');
    }
}
