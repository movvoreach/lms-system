<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Teacher extends Model
{
    use HasFactory;

    protected $primaryKey = 'teacher_id';

    protected $fillable = [
        'course_id',
        'user_id',
        'employee_number',
        'first_name',
        'last_name',
        'specialization',
        'academic_rank',
        'phone',
        'address',
        'status',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function courseAssignments(): HasMany
    {
        return $this->hasMany(TeacherCourseAssignment::class, 'teacher_id', 'teacher_id');
    }

    public function learningIssueReports(): HasMany
    {
        return $this->hasMany(LearningIssueReport::class, 'assigned_teacher_id', 'teacher_id');
    }
}
