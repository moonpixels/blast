<?php

namespace Domain\Link\Models;

use Database\Factories\DomainFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Support\Concerns\Blockable;
use Support\Eloquent\Attributes\WithFactory;
use Support\Eloquent\Concerns\HasFactory;

#[WithFactory(DomainFactory::class)]
class Domain extends Model
{
    use Blockable, HasFactory, HasUlids;

    /**
     * The attributes that aren't mass assignable.
     */
    protected $guarded = [
        'id',
        'blocked',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'blocked' => 'boolean',
    ];

    /**
     * The links that belong to the domain.
     */
    public function links(): HasMany
    {
        return $this->hasMany(Link::class);
    }
}
