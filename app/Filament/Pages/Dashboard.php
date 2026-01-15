<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\RecentActivityWidget;
use App\Filament\Widgets\StatsOverviewWidget;
use App\Filament\Widgets\SystemStatusWidget;
use App\Filament\Widgets\WelcomeWidget;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = null;

    protected static string $view = 'filament.pages.dashboard';

    public function getWidgets(): array
    {
        return [
            WelcomeWidget::class,
            StatsOverviewWidget::class,
            SystemStatusWidget::class,
            RecentActivityWidget::class,
        ];
    }

    public function getColumns(): int|string|array
    {
        return 2;
    }
}
