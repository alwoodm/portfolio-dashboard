<?php

namespace App\Filament\Widgets;

use App\Models\ActivityLog;
use App\Support\ViewerMode;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentActivityWidget extends BaseWidget
{
    protected static ?int $sort = 4;

    protected static ?string $pollingInterval = '15s';

    protected static ?string $heading = 'Recent Activity';

    protected int|string|array $columnSpan = 1;

    public static function canView(): bool
    {
        return ! ViewerMode::isViewer();
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                ActivityLog::query()
                    ->with('user')
                    ->latest()
            )
            ->columns([
                TextColumn::make('page_name')
                    ->label('Page')
                    ->icon(fn (ActivityLog $record): string => $this->getPageIcon($record->content_page))
                    ->iconColor('primary')
                    ->weight('medium'),

                TextColumn::make('user.name')
                    ->label('User')
                    ->default('System')
                    ->color('gray'),

                TextColumn::make('created_at')
                    ->label('Time')
                    ->since()
                    ->color('gray'),
            ])
            ->paginated([5])
            ->defaultPaginationPageOption(5)
            ->emptyStateHeading('No recent activity')
            ->emptyStateDescription('Content updates will appear here.')
            ->emptyStateIcon('heroicon-o-document-text');
    }

    protected function getPageIcon(string $page): string
    {
        return match ($page) {
            'home' => 'heroicon-o-home',
            'about' => 'heroicon-o-user',
            'skills' => 'heroicon-o-star',
            'career' => 'heroicon-o-briefcase',
            'projects' => 'heroicon-o-rectangle-stack',
            'contact' => 'heroicon-o-envelope',
            'seo' => 'heroicon-o-magnifying-glass',
            default => 'heroicon-o-document',
        };
    }
}
