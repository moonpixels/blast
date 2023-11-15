<?php

namespace Domain\Redirect\Models;

use Database\Factories\VisitFactory;
use Domain\Link\Models\Link;
use Domain\Redirect\Enums\DeviceType;
use Domain\Team\Models\Team;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Support\Eloquent\Attributes\WithFactory;
use Support\Eloquent\Concerns\HasFactory;

#[WithFactory(VisitFactory::class)]
class Visit extends Model
{
    use HasFactory, HasUlids;

    /**
     * The attributes that aren't mass assignable.
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'device_type' => DeviceType::class,
        'is_robot' => 'boolean',
    ];

    /**
     * The model's default values for attributes.
     */
    protected $attributes = [
        'is_robot' => false,
    ];

    /**
     * The link that the visit is for..
     */
    public function link(): BelongsTo
    {
        return $this->belongsTo(Link::class);
    }

    /**
     * The team that the visit is for.
     */
    public function team(): HasOneThrough
    {
        return $this->hasOneThrough(
            Team::class,
            Link::class,
            'id',
            'id',
            'link_id',
            'team_id'
        );
    }
}
