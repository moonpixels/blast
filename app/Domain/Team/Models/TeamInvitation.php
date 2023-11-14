<?php

namespace Domain\Team\Models;

use Database\Factories\TeamInvitationFactory;
use Domain\Team\Builders\TeamInvitationBuilder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\URL;
use Support\Eloquent\Attributes\WithBuilder;
use Support\Eloquent\Attributes\WithFactory;
use Support\Eloquent\Concerns\HasBuilder;
use Support\Eloquent\Concerns\HasFactory;

/**
 * @method static TeamInvitationBuilder query()
 *
 * @property string $accept_url
 */
#[WithBuilder(TeamInvitationBuilder::class)]
#[WithFactory(TeamInvitationFactory::class)]
class TeamInvitation extends Model
{
    use HasBuilder, HasFactory, HasUlids;

    /**
     * The attributes that aren't mass assignable.
     */
    protected $guarded = ['id'];

    /**
     * The accessors to append to the model's array form.
     */
    protected $appends = [
        'accept_url',
    ];

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * Get the URL where the user can accept the invitation.
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
