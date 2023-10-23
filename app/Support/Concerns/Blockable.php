<?php

namespace App\Support\Concerns;

use Illuminate\Database\Eloquent\Builder;

trait Blockable
{
    /**
     * Determine if the model is blocked.
     */
    public function isBlocked(): bool
    {
        return (bool) $this->blocked;
    }

    /**
     * Block the model.
     */
    public function block(): void
    {
        $this->forceFill(['blocked' => true])->save();
    }

    /**
     * Unblock the model.
     */
    public function unblock(): void
    {
        $this->forceFill(['blocked' => false])->save();
    }

    /**
     * Scope a query to only include blocked models.
     */
    public function scopeBlocked(Builder $query): void
    {
        $query->where('blocked', true);
    }

    /**
     * Scope a query to only include unblocked models.
     */
    public function scopeUnblocked(Builder $query): void
    {
        $query->where('blocked', false);
    }
}
