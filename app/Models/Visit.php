<?php

namespace App\Models;

use App\Enums\Visits\DeviceTypes;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Visit extends Model
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
     * @var array<string>
     */
    protected $casts = [
        'device_type' => DeviceTypes::class,
        'is_robot' => 'boolean',
    ];

    /**
     * Get the link that the visit belongs to.
     */
    public function link(): BelongsTo
    {
        return $this->belongsTo(Link::class);
    }

    /**
     * Get the team that the visit belongs to.
     */
    public function team(): HasOneThrough
    {
        return $this->hasOneThrough(Team::class, Link::class);
    }
}
