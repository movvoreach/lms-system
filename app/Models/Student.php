<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    use HasFactory;

    protected $primaryKey = 'student_id';

    protected $fillable = [
        'course_id',
        'department_id',
        'user_id',
        'student_number',
        'first_name',
        'last_name',
        'gender',
        'date_of_birth',
        'phone',
        'address',
        'status',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id', 'department_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function academicYearRecords(): HasMany
    {
        return $this->hasMany(StudentAcademicYearRecord::class, 'student_id', 'student_id');
    }

    public function courseRegistrations(): HasMany
    {
        return $this->hasMany(StudentCourseRegistration::class, 'student_id', 'student_id');
    }

    public function lessonGrades(): HasMany
    {
        return $this->hasMany(StudentLessonGrade::class, 'student_id', 'student_id');
    }

    public function learningIssueReports(): HasMany
    {
        return $this->hasMany(LearningIssueReport::class, 'student_id', 'student_id');
    }
}
