<?php

namespace App\Domain\Redirect\Models;

use App\Domain\Link\Models\Link;
use App\Domain\Redirect\Enums\DeviceTypes;
use App\Domain\Team\Models\Team;
use Database\Factories\VisitFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\Factory;
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
     * @var array<string, string>
     */
    protected $casts = [
        'device_type' => DeviceTypes::class,
        'is_robot' => 'boolean',
    ];

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): Factory
    {
        return VisitFactory::new();
    }

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
