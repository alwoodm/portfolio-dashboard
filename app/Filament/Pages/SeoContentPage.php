<?php

namespace App\Filament\Pages;

use App\Filament\Pages\Content\BaseContentPage;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

class SeoContentPage extends BaseContentPage
{
    protected static ?string $navigationIcon = 'heroicon-o-globe-alt';

    protected static ?string $navigationLabel = 'SEO';

    protected static ?string $navigationGroup = 'Content';

    protected static ?string $slug = 'content/seo';

    protected static ?int $navigationSort = 70;

    protected static string $contentFile = 'seo';

    protected function contentFormSchema(): array
    {
        return [
            Section::make('SEO')
                ->schema([
                    TextInput::make('siteName')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('titleTemplate')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('defaultTitle')
                        ->required()
                        ->maxLength(255),
                    Textarea::make('defaultDescription')
                        ->required()
                        ->maxLength(500)
                        ->rows(4)
                        ->columnSpanFull(),
                ])
                ->columns(2),
            Section::make('Open Graph')
                ->schema([
                    Select::make('openGraph.type')
                        ->required()
                        ->options([
                            'website' => 'website',
                        ])
                        ->default('website'),
                    TextInput::make('openGraph.locale')
                        ->required()
                        ->maxLength(50),
                ])
                ->columns(2),
            Section::make('Twitter')
                ->schema([
                    Select::make('twitter.card')
                        ->required()
                        ->options([
                            'summary' => 'summary',
                            'summary_large_image' => 'summary_large_image',
                            'app' => 'app',
                            'player' => 'player',
                        ]),
                ])
                ->columns(2),
        ];
    }
}
