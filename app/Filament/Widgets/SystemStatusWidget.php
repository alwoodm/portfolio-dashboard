<?php

namespace App\Filament\Widgets;

use App\Services\PortfolioApiService;
use App\Support\ViewerMode;
use Filament\Widgets\Widget;

class SystemStatusWidget extends Widget
{
    protected static string $view = 'filament.widgets.system-status-widget';

    protected static ?int $sort = 3;

    public ?string $status = null;

    public ?int $responseTime = null;

    public ?string $error = null;

    protected int|string|array $columnSpan = 1;

    public static function canView(): bool
    {
        return ! ViewerMode::isViewer();
    }

    public function mount(): void
    {
        $this->checkHealth();
    }

    public function checkHealth(): void
    {
        $service = new PortfolioApiService;
        $health = $service->checkHealth();

        $this->status = $health['status'];
        $this->responseTime = $health['response_time'];
        $this->error = $health['error'];
    }

    public function getStatusLabel(): string
    {
        return match ($this->status ?? 'error') {
            'connected' => 'Connected',
            'not_configured' => 'Not Configured',
            default => 'Disconnected',
        };
    }

    public function getStatusIcon(): string
    {
        return match ($this->status ?? 'error') {
            'connected' => 'heroicon-o-check-circle',
            'not_configured' => 'heroicon-o-exclamation-triangle',
            default => 'heroicon-o-x-circle',
        };
    }
}
