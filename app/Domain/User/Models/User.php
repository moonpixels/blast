<?php

namespace Domain\User\Models;

use BaconQrCode\Renderer\Color\Rgb;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\Fill;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Database\Factories\UserFactory;
use Domain\Team\Models\Team;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Sanctum\HasApiTokens;
use LemonSqueezy\Laravel\Billable;
use Support\Concerns\Blockable;
use Support\Eloquent\Attributes\WithFactory;
use Support\Eloquent\Concerns\HasFactory;

/**
 * @property bool $two_factor_enabled
 * @property string $initials
 */
#[WithFactory(UserFactory::class)]
class User extends Authenticatable implements MustVerifyEmail
{
    use Billable, Blockable, HasApiTokens, HasFactory, HasUlids, Notifiable, TwoFactorAuthenticatable;

    /**
     * The attributes that aren't mass assignable.
     */
    protected $guarded = [
        'id',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'two_factor_confirmed_at',
        'remember_token',
        'is_admin',
        'blocked',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'two_factor_secret',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'is_admin' => 'boolean',
        'blocked' => 'boolean',
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     */
    protected $appends = [
        'two_factor_enabled',
        'initials',
    ];

    /**
     * The model's default values for attributes.
     */
    protected $attributes = [
        'is_admin' => false,
    ];

    /**
     * The user's current team.
     */
    public function currentTeam(): BelongsTo
    {
        if (! $this->current_team_id) {
            $this->switchTeam($this->personalTeam());
        }

        return $this->belongsTo(Team::class, 'current_team_id');
    }

    /**
     * Switch the user's current team.
     */
    public function switchTeam(Team $team): bool
    {
        if ($this->belongsToTeam($team)) {
            $this->update([
                'current_team_id' => $team->id,
            ]);

            $this->setRelation('currentTeam', $team);

            return true;
        }

        return false;
    }

    /**
     * Determine if the user belongs to the given team.
     */
    public function belongsToTeam(Team $team): bool
    {
        return $this->ownsTeam($team)
            || $this->teams()->where('team_id', $team->id)->exists();
    }

    /**
     * Get the user's personal team.
     */
    public function personalTeam(): Team
    {
        /** @phpstan-ignore-next-line */
        return $this->ownedTeams()->personal()->first();
    }

    /**
     * The teams that the user has ownership of.
     */
    public function ownedTeams(): HasMany
    {
        return $this->hasMany(Team::class, 'owner_id');
    }

    /**
     * Determine if the user owns the given team.
     */
    public function ownsTeam(Team $team): bool
    {
        return $team->owner_id === $this->id;
    }

    /**
     * Get all teams that the user owns or belongs to.
     */
    public function allTeams(): Collection
    {
        return $this->teams
            ->merge($this->ownedTeams);
    }

    /**
     * The teams the user is a member of.
     */
    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class)->withTimestamps();
    }

    /**
     * Get the two factor authentication QR code SVG.
     */
    public function twoFactorQrCodeSvg(): string
    {
        $svg = (new Writer(
            new ImageRenderer(
                new RendererStyle(128, 1, null, null,
                    Fill::uniformColor(new Rgb(255, 255, 255), new Rgb(24, 24, 27))),
                new SvgImageBackEnd
            )
        ))->writeString($this->twoFactorQrCodeUrl());

        return trim(substr($svg, strpos($svg, "\n") + 1));
    }

    /**
     * Determine if two factor authentication is enabled.
     */
    protected function twoFactorEnabled(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->hasEnabledTwoFactorAuthentication()
        );
    }

    /**
     * Get the user's initials.
     */
    protected function initials(): Attribute
    {
        return Attribute::make(
            get: function () {
                $letters = collect(explode(' ', $this->name))
                    ->map(fn ($word) => Str::substr($word, 0, 1))
                    ->map(fn ($letter) => Str::upper($letter));

                return $letters->count() > 1
                    ? $letters->first().$letters->last()
                    : $letters->first();
            }
        );
    }
}
