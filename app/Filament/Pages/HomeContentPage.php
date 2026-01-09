<?php

namespace App\Filament\Pages;

use App\Filament\Pages\Content\BaseContentPage;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;

class HomeContentPage extends BaseContentPage
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static ?string $navigationLabel = 'Home';

    protected static ?string $navigationGroup = 'Content';

    protected static ?string $slug = 'content/home';

    protected static ?int $navigationSort = 10;

    protected static string $contentFile = 'home';

    protected function contentFormSchema(): array
    {
        return [
            Section::make('Home')
                ->schema([
                    TextInput::make('intro')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('name')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('role')
                        ->required()
                        ->maxLength(255),
                    Repeater::make('roles')
                        ->simple(
                            TextInput::make('role')
                                ->required()
                                ->maxLength(255)
                        )
                        ->defaultItems(0)
                        ->columnSpanFull(),
                    Section::make('Social')
                        ->schema([
                            TextInput::make('social.linkedin')
                                ->required()
                                ->url()
                                ->maxLength(255),
                            TextInput::make('social.github')
                                ->required()
                                ->url()
                                ->maxLength(255),
                        ])
                        ->columns(2)
                        ->columnSpanFull(),
                ])
                ->columns(2),
        ];
    }
}
