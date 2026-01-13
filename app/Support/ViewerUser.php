<?php

namespace App\Support;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Foundation\Auth\User as Authenticatable;

class ViewerUser extends Authenticatable implements FilamentUser
{
    public $timestamps = false;

    protected $fillable = [
        'name',
        'email',
    ];

    public function __construct()
    {
        parent::__construct([
            'name' => 'Viewer',
            'email' => null,
        ]);
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }

    public function isAdmin(): bool
    {
        return false;
    }

    public function canEditContent(): bool
    {
        return false;
    }

    public function hasEnabledTwoFactor(): bool
    {
        return false;
    }

    public function hasConfirmedTwoFactor(): bool
    {
        return false;
    }

    public function hasValidTwoFactorSession(): bool
    {
        return false;
    }

    public function setTwoFactorSession(?int $lifetime = null): void
    {
        // No-op for viewer sessions.
    }

    public function hasVerifiedEmail(): bool
    {
        return false;
    }
}
