<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LearningIssueReply extends Model
{
    use HasFactory;

    protected $primaryKey = 'issue_reply_id';

    protected $fillable = [
        'issue_report_id',
        'user_id',
        'message',
        'is_teacher_feedback',
    ];

    protected $casts = [
        'is_teacher_feedback' => 'boolean',
    ];

    public function report(): BelongsTo
    {
        return $this->belongsTo(LearningIssueReport::class, 'issue_report_id', 'issue_report_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
