<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class TeamMembership extends Pivot
{
    use HasFactory, HasUlids;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'team_user';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array<string>
     */
    protected $guarded = ['id'];

    /**
     * Get the team that the membership belongs to.
     */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * Get the user that the membership belongs to.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
