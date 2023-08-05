<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Hash;
use Laravel\Scout\Searchable;

/**
 * @property string $destination_url
 * @property string $short_url
 */
class Link extends Model
{
    use HasFactory, HasUlids, Searchable;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array<string>
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be hidden.
     *
     * @var array<int, string>
     */
    protected $hidden = ['password'];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'total_visits' => 'integer',
        'expires_at' => 'datetime',
    ];

    /**
     * The attributes to be appended to the model's array form.
     *
     * @var array<string>
     */
    protected $appends = [
        'destination_url',
        'short_url',
        'has_password',
    ];

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = ['domain'];

    /**
     * Get the domain that the link belongs to.
     */
    public function domain(): BelongsTo
    {
        return $this->belongsTo(Domain::class);
    }

    /**
     * Get the team that the link belongs to.
     */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * Get the visits for the link.
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
     * Get the indexable data array for the model.
     *
     * @return array<string, mixed>
     */
    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'team_id' => $this->team_id,
            'alias' => $this->alias,
            'destination_path' => $this->destination_path,
            'destination_url' => $this->destination_url,
            'short_url' => $this->short_url,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    /**
     * Determine if the link's password matches the given password.
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
     * Get the link's long link.
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
     * Get the link's short link.
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
     * Determine if the link is password protected.
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
