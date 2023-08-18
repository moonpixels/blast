<?php

namespace App\Domain\Link\Models;

use Database\Factories\DomainFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Domain extends Model
{
    use HasFactory, HasUlids;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array<string>
     */
    protected $guarded = ['id'];

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): Factory
    {
        return DomainFactory::new();
    }

    /**
     * Get the links for the domain.
     */
    public function links(): HasMany
    {
        return $this->hasMany(Link::class);
    }
}
