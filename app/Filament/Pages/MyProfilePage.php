<?php

namespace App\Filament\Pages;

use App\Support\ViewerMode;
use Jeffgreco13\FilamentBreezy\Pages\MyProfilePage as BreezyMyProfilePage;

class MyProfilePage extends BreezyMyProfilePage
{
    public static function canAccess(): bool
    {
        return auth()->check() && ! ViewerMode::isViewer();
    }
}
