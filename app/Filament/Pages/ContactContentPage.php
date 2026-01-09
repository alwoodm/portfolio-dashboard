<?php

namespace App\Filament\Pages;

use App\Filament\Pages\Content\BaseContentPage;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

class ContactContentPage extends BaseContentPage
{
    protected static ?string $navigationIcon = 'heroicon-o-envelope';

    protected static ?string $navigationLabel = 'Contact';

    protected static ?string $navigationGroup = 'Content';

    protected static ?string $slug = 'content/contact';

    protected static ?int $navigationSort = 60;

    protected static string $contentFile = 'contact';

    protected function contentFormSchema(): array
    {
        return [
            Section::make('Contact')
                ->schema([
                    TextInput::make('badge')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('title')
                        ->required()
                        ->maxLength(255),
                    Textarea::make('detailsIntro')
                        ->required()
                        ->maxLength(500)
                        ->rows(4)
                        ->columnSpanFull(),
                ])
                ->columns(2),
            Section::make('Email')
                ->schema([
                    TextInput::make('email.label')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('email.iconId')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('email.address')
                        ->label('Email address')
                        ->email()
                        ->required()
                        ->maxLength(255)
                        ->helperText('Stored as base64 in JSON.'),
                ])
                ->columns(2),
        ];
    }

    protected function prepareFormData(array $data): array
    {
        $email = $data['email'] ?? [];
        $base64 = is_array($email) ? ($email['base64'] ?? null) : null;
        $decoded = '';

        if (is_string($base64) && $base64 !== '') {
            $decoded = base64_decode($base64, true) ?: '';
        }

        if (! is_array($email)) {
            $email = [];
        }

        $email['address'] = $decoded;
        $data['email'] = $email;

        return $data;
    }

    protected function transformPayload(array $payload): array
    {
        $email = $payload['email'] ?? [];
        $address = is_array($email) ? ($email['address'] ?? '') : '';

        if (! is_array($email)) {
            $email = [];
        }

        $email['base64'] = base64_encode((string) $address);
        unset($email['address']);

        $payload['email'] = $email;

        return $payload;
    }
}
