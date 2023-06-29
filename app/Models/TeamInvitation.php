<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\URL;

class TeamInvitation extends Model
{
    use HasFactory, HasUlids, Notifiable;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array<string>
     */
    protected $guarded = ['id'];

    /**
     * The attributes to append to the model's array form.
     *
     * @var array<string>
     */
    protected $appends = [
        'accept_url',
    ];

    /**
     * Get the team that the invitation belongs to.
     */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * Search for an invitation where the email is like the given value.
     */
    public function scopeWhereEmailLike(Builder $query, ?string $email): void
    {
        $query->when($email, fn (Builder $query, string $email) => $query->where('email', 'like', "%{$email}%"));
    }

    /**
     * Get the URL to accept the invitation.
     */
    protected function acceptUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => URL::signedRoute('accepted-invitations.show', [
                'invitation' => $this->id,
            ])
        );
    }
}
