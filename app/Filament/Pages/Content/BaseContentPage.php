<?php

namespace App\Filament\Pages\Content;

use App\Models\ActivityLog;
use App\Support\ViewerUser;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Http;

abstract class BaseContentPage extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $view = 'filament.pages.content-editor';

    protected static string $contentFile = '';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill($this->prepareFormData($this->fetchContent()));
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema($this->contentFormSchema())
            ->disabled($this->isReadOnly())
            ->statePath('data');
    }

    public function save(): void
    {
        if ($this->isReadOnly()) {
            Notification::make()
                ->warning()
                ->title('Read-only access')
                ->body('You do not have permission to edit content.')
                ->send();

            return;
        }

        $payload = $this->transformPayload($this->form->getState());
        $url = $this->getContentUrl(static::$contentFile);
        $token = $this->getAdminToken();

        if ($url === null || $token === null) {
            Notification::make()
                ->danger()
                ->title('Missing configuration')
                ->body('Set PORTFOLIO_URL and PORTFOLIO_ADMIN_TOKEN in the environment.')
                ->send();

            return;
        }

        $response = Http::timeout(10)
            ->acceptJson()
            ->withHeaders(['x-admin-token' => $token])
            ->post($url, $payload);

        if (! $response->successful()) {
            Notification::make()
                ->danger()
                ->title('Save failed')
                ->body($response->json('error') ?? 'Failed to update content.')
                ->send();

            return;
        }

        Notification::make()
            ->success()
            ->title('Saved')
            ->body('Content updated successfully.')
            ->send();

        $this->logActivity('updated');
    }

    public function canEdit(): bool
    {
        return auth()->user()?->canEditContent() ?? false;
    }

    protected function contentFormSchema(): array
    {
        return [];
    }

    protected function prepareFormData(array $data): array
    {
        return $data;
    }

    protected function transformPayload(array $payload): array
    {
        return $payload;
    }

    protected function fetchContent(): array
    {
        $url = $this->getContentUrl(static::$contentFile);

        if ($url === null) {
            return [];
        }

        $response = Http::timeout(10)->acceptJson()->get($url);

        if (! $response->successful()) {
            Notification::make()
                ->danger()
                ->title('Failed to load content')
                ->body($response->json('error') ?? 'Unable to fetch remote content.')
                ->send();

            return [];
        }

        $data = $response->json();

        return is_array($data) ? $data : [];
    }

    protected function getContentUrl(string $file): ?string
    {
        $baseUrl = config('portfolio.url');
        if (! $baseUrl) {
            return null;
        }

        $prefix = config('portfolio.content_prefix', '/api/content');
        $baseUrl = rtrim($baseUrl, '/');
        $prefix = '/' . trim($prefix, '/');

        return $baseUrl . $prefix . '/' . $file;
    }

    protected function getAdminToken(): ?string
    {
        $token = config('portfolio.admin_token');

        return $token !== '' ? $token : null;
    }

    protected function isReadOnly(): bool
    {
        return ! $this->canEdit();
    }

    /**
     * Log activity when content is saved.
     */
    protected function logActivity(string $action = 'updated'): void
    {
        $user = auth()->user();

        ActivityLog::create([
            'user_id' => $user?->id,
            'content_page' => static::$contentFile,
            'action' => $action,
            'is_viewer' => $user instanceof ViewerUser,
        ]);
    }
}
