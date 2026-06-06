<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CertificateRequest extends Model
{
    use HasFactory;

    protected $primaryKey = 'certificate_request_id';

    protected $fillable = [
        'student_id',
        'course_id',
        'registration_id',
        'requested_by_teacher_id',
        'reviewed_by_user_id',
        'status',
        'teacher_note',
        'admin_note',
        'requested_at',
        'reviewed_at',
    ];

    protected $casts = [
        'requested_at' => 'datetime',
        'reviewed_at' => 'datetime',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id', 'student_id');
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function registration(): BelongsTo
    {
        return $this->belongsTo(StudentCourseRegistration::class, 'registration_id', 'registration_id');
    }

    public function requestedByTeacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class, 'requested_by_teacher_id', 'teacher_id');
    }

    public function reviewedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by_user_id', 'user_id');
    }
}
