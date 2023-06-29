<?php

namespace App\Models;

use BaconQrCode\Renderer\Color\Rgb;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\Fill;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property Pivot $team_membership
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, TwoFactorAuthenticatable, HasUlids;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array<string>
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The attributes to be appended to the model's array form.
     *
     * @var array<string>
     */
    protected $appends = [
        'two_factor_enabled',
        'initials',
    ];

    /**
     * Get the teams that the user owns.
     */
    public function ownedTeams(): HasMany
    {
        return $this->hasMany(Team::class, 'owner_id');
    }

    /**
     * Get user's current team.
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
        return $team->owner_id === $this->id || $team->users()->where('user_id', $this->id)->exists();
    }

    /**
     * Get the user's personal team.
     */
    public function personalTeam(): Team
    {
        return $this->ownedTeams->where('personal_team', true)->first();
    }

    /**
     * Determine if the user owns the given team.
     */
    public function ownsTeam(Team $team): bool
    {
        return $team->owner_id === $this->id;
    }

    /**
     * Get all the user's teams including owned teams.
     */
    public function allTeams(): Collection
    {
        return $this->teams->merge($this->ownedTeams);
    }

    /**
     * Get all the teams that the user is a member of.
     */
    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class)
            ->using(TeamMembership::class)
            ->withPivot(['id', 'team_id', 'user_id'])
            ->withTimestamps()
            ->as('team_membership');
    }

    /**
     * Get the QR code SVG of the user's two-factor authentication QR code URL.
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
     * Search for a user by where their email is like the given value.
     */
    public function scopeWhereEmailLike(Builder $query, ?string $email): void
    {
        $query->when($email, fn (Builder $query, string $email) => $query->where('email', 'like', "%{$email}%"));
    }

    /**
     * Search for a user by where their name is like the given value.
     */
    public function scopeWhereNameLike(Builder $query, ?string $name): void
    {
        $query->when($name, fn (Builder $query, string $name) => $query->where('name', 'like', "%{$name}%"));
    }

    /**
     * Get the user's two-factor authentication status.
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
