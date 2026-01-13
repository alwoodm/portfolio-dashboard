<?php

namespace App\Http\Middleware;

use App\Support\ViewerMode;
use App\Support\ViewerUser;
use Filament\Facades\Filament;
use Filament\Http\Middleware\Authenticate as FilamentAuthenticateMiddleware;

class FilamentAuthenticate extends FilamentAuthenticateMiddleware
{
    /**
     * @param  array<string>  $guards
     */
    protected function authenticate($request, array $guards): void
    {
        if (ViewerMode::enabled() && ViewerMode::active()) {
            $guard = Filament::auth();
            $guard->setUser(new ViewerUser);
            $this->auth->shouldUse(Filament::getAuthGuard());

            return;
        }

        parent::authenticate($request, $guards);
    }
}
