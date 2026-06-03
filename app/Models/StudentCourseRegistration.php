<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StudentCourseRegistration extends Model
{
    use HasFactory;

    protected $primaryKey = 'registration_id';

    protected $fillable = [
        'student_id',
        'course_id',
        'academic_year_id',
        'student_academic_year_record_id',
        'semester_id',
        'study_year',
        'term_number',
        'status',
        'registered_at',
        'completed_at',
        'notes',
    ];

    protected $casts = [
        'study_year' => 'integer',
        'term_number' => 'integer',
        'registered_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id', 'student_id');
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year_id', 'academic_year_id');
    }

    public function academicRecord(): BelongsTo
    {
        return $this->belongsTo(StudentAcademicYearRecord::class, 'student_academic_year_record_id', 'record_id');
    }

    public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class, 'semester_id', 'semester_id');
    }

    public function lessonGrades(): HasMany
    {
        return $this->hasMany(StudentLessonGrade::class, 'student_id', 'student_id')
            ->whereColumn('course_id', 'student_course_registrations.course_id');
    }
}
