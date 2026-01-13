<?php

namespace App\Filament\Pages;

use App\Models\AdminSetting;
use App\Support\ViewerMode;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class AdminSettingsPage extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static string $view = 'filament.pages.admin-settings-page';

    protected static ?string $navigationGroup = 'Admin';

    protected static ?int $navigationSort = 20;

    protected static ?string $navigationLabel = 'Settings';

    protected static ?string $slug = 'settings';

    public ?array $data = [];

    public static function canAccess(): bool
    {
        return auth()->check() && ! ViewerMode::isViewer();
    }

    public function mount(): void
    {
        $settings = AdminSetting::current();
        $this->form->fill([
            'viewer_mode_enabled' => $settings->viewer_mode_enabled,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Viewer mode')
                    ->description('Allow a guest viewer session for read-only access.')
                    ->schema([
                        Toggle::make('viewer_mode_enabled')
                            ->label('Enable viewer mode'),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        if (ViewerMode::isViewer()) {
            Notification::make()
                ->warning()
                ->title('Read-only access')
                ->body('Viewer sessions cannot update settings.')
                ->send();

            return;
        }

        $settings = AdminSetting::current();
        $settings->update($this->form->getState());

        Notification::make()
            ->success()
            ->title('Saved')
            ->body('Settings updated successfully.')
            ->send();
    }

    public function isViewerModeEnabled(): bool
    {
        return AdminSetting::isViewerModeEnabled();
    }
}
