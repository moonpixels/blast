<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Collection;

class Team extends Model
{
    use HasFactory, HasUlids;

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
     * Get the owner of the team.
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Get the teams memberships.
     */
    public function memberships(): HasMany
    {
        return $this->hasMany(TeamMembership::class);
    }

    /**
     * Get the users that have memberships with the team.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'team_memberships');
    }

    /**
     * Get all the team's users including the owner.
     */
    public function allUsers(): Collection
    {
        return $this->users->merge([$this->owner]);
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
}
