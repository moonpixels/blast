<?php

namespace App\Domain\Team\Models;

use Database\Factories\TeamInvitationFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\URL;
use Laravel\Scout\Searchable;

class TeamInvitation extends Model
{
    use HasFactory, HasUlids, Notifiable, Searchable;

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
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): Factory
    {
        return TeamInvitationFactory::new();
    }

    /**
     * Get the team that the invitation belongs to.
     */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
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
            'email' => $this->email,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    /**
     * Get the URL to accept the invitation.
     */
    protected function acceptUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => URL::signedRoute('teams.invitations.accept', [
                'team' => $this->team,
                'invitation' => $this,
            ])
        );
    }
}
