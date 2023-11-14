<?php

namespace Domain\Link\Models;

use Database\Factories\LinkFactory;
use Domain\Link\Builders\LinkBuilder;
use Domain\Redirect\Models\Visit;
use Domain\Team\Models\Team;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Hash;
use Support\Concerns\Blockable;
use Support\Eloquent\Attributes\WithBuilder;
use Support\Eloquent\Attributes\WithFactory;
use Support\Eloquent\Concerns\HasBuilder;
use Support\Eloquent\Concerns\HasFactory;

/**
 * @method static LinkBuilder query()
 *
 * @property string $destination_url
 * @property string $short_url
 * @property bool $has_password
 */
#[WithFactory(LinkFactory::class)]
#[WithBuilder(LinkBuilder::class)]
class Link extends Model
{
    use Blockable, HasBuilder, HasFactory, HasUlids, SoftDeletes;

    /**
     * The attributes that aren't mass assignable.
     */
    protected $guarded = [
        'id',
        'blocked',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'password' => 'hashed',
        'visit_limit' => 'integer',
        'total_visits' => 'integer',
        'expires_at' => 'datetime',
        'blocked' => 'boolean',
    ];

    /**
     * The accessors to append to the model's array form.
     */
    protected $appends = [
        'destination_url',
        'short_url',
        'has_password',
    ];

    /**
     * The relations to eager load on every query.
     */
    protected $with = [
        'domain',
    ];

    /**
     * Retrieve the model for a bound value.
     */
    public function resolveRouteBinding(mixed $value, $field = null): self
    {
        return $this
            ->when(
                $field,
                function () use ($value, $field) {
                    return $this->where($field, $value);
                },
                function () use ($value) {
                    return $this->where('id', $value);
                }
            )
            ->unblocked()
            ->whereHas('domain', function (Builder $query) {
                $query->where('blocked', false);
            })
            ->firstOrFail();
    }

    /**
     * The domain that the link belongs to.
     */
    public function domain(): BelongsTo
    {
        return $this->belongsTo(Domain::class);
    }

    /**
     * The team that the link belongs to.
     */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * The visits that belong to the link.
     */
    public function visits(): HasMany
    {
        return $this->hasMany(Visit::class);
    }

    /**
     * Increment the total visits for the link.
     */
    public function incrementTotalVisits(): void
    {
        $this->increment('total_visits');
    }

    /**
     * Determine if the password matches the given value.
     */
    public function passwordMatches(string $password): bool
    {
        return Hash::check($password, $this->password);
    }

    /**
     * Determine if the link has expired.
     */
    public function hasExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    /**
     * Determine if the link has reached its visit limit.
     */
    public function hasReachedVisitLimit(): bool
    {
        return $this->visit_limit && $this->total_visits >= $this->visit_limit;
    }

    /**
     * Get the link's destination URL.
     */
    protected function destinationUrl(): Attribute
    {
        return Attribute::make(
            get: function () {
                return "https://{$this->domain->host}{$this->destination_path}";
            }
        );
    }

    /**
     * Get the link's short URL.
     */
    protected function shortUrl(): Attribute
    {
        return Attribute::make(
            get: function () {
                return config('app.url')."/{$this->alias}";
            }
        );
    }

    /**
     * Determine if the link has a password.
     */
    protected function hasPassword(): Attribute
    {
        return Attribute::make(
            get: function () {
                return (bool) $this->password;
            }
        );
    }
}
