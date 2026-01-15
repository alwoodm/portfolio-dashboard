<?php

namespace App\Filament\Widgets;

use App\Support\ViewerMode;
use Filament\Widgets\Widget;

class WelcomeWidget extends Widget
{
    protected static string $view = 'filament.widgets.welcome-widget';

    protected static ?int $sort = 1;

    protected int|string|array $columnSpan = 'full';

    public function getUserName(): string
    {
        return auth()->user()?->name ?? 'Guest';
    }

    public function getCurrentDate(): string
    {
        return now()->format('l, F j, Y');
    }

    public function isViewerModeEnabled(): bool
    {
        return ViewerMode::enabled();
    }
}
