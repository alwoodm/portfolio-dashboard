<?php

namespace App\Filament\Widgets;

use App\Services\PortfolioApiService;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 2;

    protected static ?string $pollingInterval = '60s';

    protected int|string|array $columnSpan = 'full';

    protected function getStats(): array
    {
        $service = new PortfolioApiService;
        $stats = $service->getContentStats();

        $projectsStat = Stat::make('Projects', $stats['projects'])
            ->description('Portfolio projects')
            ->icon('heroicon-o-rectangle-stack')
            ->color('primary');

        $skillsStat = Stat::make('Skills', $stats['skills'])
            ->description('Technical skills')
            ->icon('heroicon-o-star')
            ->color('success');

        $careerStat = Stat::make('Career', $stats['career'])
            ->description('Timeline entries')
            ->icon('heroicon-o-briefcase')
            ->color('warning');

        // Add error indicator if API failed
        if ($stats['error']) {
            $projectsStat = $projectsStat->description($stats['error'])->color('danger');
        }

        return [
            $projectsStat,
            $skillsStat,
            $careerStat,
        ];
    }
}
