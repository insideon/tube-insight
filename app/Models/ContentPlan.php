<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContentPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'channel_id',
        'creator_id',
        'title',
        'topic',
        'concept',
        'description',
        'expected_competitiveness_score',
        'qc_score',
        'qc_feedback',
        'qc_reviewer_id',
        'qc_reviewed_at',
        'status',
        'youtube_video_id',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'expected_competitiveness_score' => 'integer',
            'qc_score' => 'integer',
            'qc_reviewed_at' => 'datetime',
            'published_at' => 'datetime',
        ];
    }

    public function channel(): BelongsTo
    {
        return $this->belongsTo(Channel::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function qcReviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'qc_reviewer_id');
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'draft' => '작성중',
            'submitted' => '제출됨',
            'qc_review' => 'QC 검토중',
            'approved' => '승인됨',
            'rejected' => '반려됨',
            'published' => '게시됨',
            default => $this->status,
        };
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeSubmitted($query)
    {
        return $query->where('status', 'submitted');
    }

    public function scopeQcReview($query)
    {
        return $query->where('status', 'qc_review');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }
}
