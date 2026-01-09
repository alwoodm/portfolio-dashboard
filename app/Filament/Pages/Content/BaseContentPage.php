<?php

namespace App\Filament\Pages\Content;

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
        $this->form->fill($this->fetchContent());
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema($this->contentFormSchema())
            ->statePath('data');
    }

    public function save(): void
    {
        $payload = $this->form->getState();
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
    }

    protected function contentFormSchema(): array
    {
        return [];
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
}
