<?php

namespace App\Domain\Team\Models;

use App\Domain\Link\Models\Link;
use App\Domain\Redirect\Models\Visit;
use App\Domain\User\Models\User;
use Database\Factories\TeamFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

class Team extends Model
{
    use HasFactory, HasUlids, SoftDeletes;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array<string>
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'personal_team' => 'boolean',
    ];

    /**
     * The model's default values for attributes.
     */
    protected $attributes = [
        'personal_team' => false,
    ];

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): Factory
    {
        return TeamFactory::new();
    }

    /**
     * Get the owner of the team.
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the team members.
     */
    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    /**
     * Get all the team members including the owner.
     */
    public function membersAndOwner(): Collection
    {
        return $this->members->merge([$this->owner]);
    }

    /**
     * Get all the pending invitations for the team.
     */
    public function invitations(): HasMany
    {
        return $this->hasMany(TeamInvitation::class)->latest();
    }

    /**
     * Get all the links that belong to the team.
     */
    public function links(): HasMany
    {
        return $this->hasMany(Link::class);
    }

    /**
     * Get all the visits for the teams links.
     */
    public function linkVisits(): HasManyThrough
    {
        return $this->hasManyThrough(Visit::class, Link::class);
    }

    /**
     * Scope a query to only include personal teams.
     */
    public function scopePersonal(Builder $query): void
    {
        $query->where('personal_team', true);
    }

    /**
     * Scope a query to not include personal teams.
     */
    public function scopeNotPersonal(Builder $query): void
    {
        $query->where('personal_team', false);
    }
}
