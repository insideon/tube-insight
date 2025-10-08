<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'team_id',
        'role',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function managedTeams(): HasMany
    {
        return $this->hasMany(Team::class, 'manager_id');
    }

    public function managedChannels(): HasMany
    {
        return $this->hasMany(Channel::class, 'manager_id');
    }

    public function contentPlans(): HasMany
    {
        return $this->hasMany(ContentPlan::class, 'creator_id');
    }

    public function qcReviews(): HasMany
    {
        return $this->hasMany(ContentPlan::class, 'qc_reviewer_id');
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isTeamManager(): bool
    {
        return $this->role === 'team_manager';
    }

    public function isCreator(): bool
    {
        return $this->role === 'creator';
    }

    public function isQcTeam(): bool
    {
        return $this->role === 'qc_team';
    }

    public function getRoleLabelAttribute(): string
    {
        return match ($this->role) {
            'admin' => '관리자',
            'team_manager' => '팀장',
            'creator' => '기획자',
            'qc_team' => 'QC팀',
            default => $this->role,
        };
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->is_active && in_array($this->role, ['admin', 'team_manager', 'creator', 'qc_team']);
    }
}
