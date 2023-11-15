<?php

namespace Domain\Team\Models;

use Database\Factories\TeamFactory;
use Domain\Link\Models\Link;
use Domain\Redirect\Models\Visit;
use Domain\Team\Builders\TeamBuilder;
use Domain\User\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Support\Eloquent\Attributes\WithBuilder;
use Support\Eloquent\Attributes\WithFactory;
use Support\Eloquent\Concerns\HasBuilder;
use Support\Eloquent\Concerns\HasFactory;

/**
 * @method static TeamBuilder query()
 */
#[WithBuilder(TeamBuilder::class)]
#[WithFactory(TeamFactory::class)]
class Team extends Model
{
    use HasBuilder, HasFactory, HasUlids, SoftDeletes;

    /**
     * The attributes that aren't mass assignable.
     */
    protected $guarded = ['id', 'personal_team'];

    /**
     * The attributes that should be cast.
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
     * The owner of the team.
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The members of the team.
     */
    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    /**
     * The members of the team including the owner.
     */
    public function membersAndOwner(): Collection
    {
        return $this->members->merge([$this->owner]);
    }

    /**
     * The pending invitations for the team.
     */
    public function invitations(): HasMany
    {
        return $this->hasMany(TeamInvitation::class)->latest();
    }

    /**
     * The links for the team.
     */
    public function links(): HasMany
    {
        return $this->hasMany(Link::class);
    }

    /**
     * The visits for the team.
     */
    public function linkVisits(): HasManyThrough
    {
        return $this->hasManyThrough(Visit::class, Link::class);
    }
}
