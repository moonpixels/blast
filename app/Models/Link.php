<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Link extends Model
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
        'total_visits' => 'integer',
    ];

    /**
     * The attributes to be appended to the model's array form.
     *
     * @var array<string>
     */
    protected $appends = [
        'destination_url',
        'short_url',
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
}