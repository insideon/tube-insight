<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChannelMetric extends Model
{
    use HasFactory;

    protected $fillable = [
        'channel_id',
        'metric_date',
        'view_count',
        'subscriber_count',
        'video_count',
        'total_views',
        'performance_grade',
    ];

    protected function casts(): array
    {
        return [
            'metric_date' => 'date',
            'view_count' => 'integer',
            'subscriber_count' => 'integer',
            'video_count' => 'integer',
            'total_views' => 'integer',
        ];
    }

    public function channel(): BelongsTo
    {
        return $this->belongsTo(Channel::class);
    }

    public static function calculatePerformanceGrade(int $monthlyViews): string
    {
        return match (true) {
            $monthlyViews >= 1000000 => '고성과',
            $monthlyViews >= 500000 => '중성과',
            default => '저성과',
        };
    }
}
