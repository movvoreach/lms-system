<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StudentAcademicYearRecord extends Model
{
    use HasFactory;

    protected $primaryKey = 'record_id';

    protected $fillable = [
        'student_id',
        'academic_year_id',
        'course_id',
        'status',
        'promotion_type',
        'promoted_from_record_id',
        'promoted_at',
        'notes',
    ];

    protected $casts = [
        'promoted_at' => 'datetime',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id', 'student_id');
    }

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year_id', 'academic_year_id');
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function previousRecord(): BelongsTo
    {
        return $this->belongsTo(self::class, 'promoted_from_record_id', 'record_id');
    }

    public function promotedRecords(): HasMany
    {
        return $this->hasMany(self::class, 'promoted_from_record_id', 'record_id');
    }
}
