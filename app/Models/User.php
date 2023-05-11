<?php

namespace App\Models;

use BaconQrCode\Renderer\Color\Rgb;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\Fill;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, TwoFactorAuthenticatable;

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
            get: fn () => collect(explode(' ', $this->name))
                ->map(fn ($word) => Str::upper(mb_substr($word, 0, 1)))
                ->implode('')
        );
    }
}
