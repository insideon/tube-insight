<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Channel extends Model
{
    use HasFactory;

    protected $fillable = [
        'youtube_channel_id',
        'name',
        'description',
        'thumbnail_url',
        'team_id',
        'manager_id',
        'is_active',
        'last_synced_at',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'last_synced_at' => 'datetime',
        ];
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function metrics(): HasMany
    {
        return $this->hasMany(ChannelMetric::class);
    }

    public function contentPlans(): HasMany
    {
        return $this->hasMany(ContentPlan::class);
    }

    public function latestMetric(): HasMany
    {
        return $this->metrics()->latest('metric_date')->limit(1);
    }
}
