<?php

namespace App\Filament\Pages;

use App\Filament\Pages\Content\BaseContentPage;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;

class AboutContentPage extends BaseContentPage
{
    protected static ?string $navigationIcon = 'heroicon-o-identification';

    protected static ?string $navigationLabel = 'About';

    protected static ?string $navigationGroup = 'Content';

    protected static ?string $slug = 'content/about';

    protected static string $contentFile = 'about';

    protected function contentFormSchema(): array
    {
        return [
            Section::make('About')
                ->schema([
                    TextInput::make('badge')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('title')
                        ->required()
                        ->maxLength(255),
                    MarkdownEditor::make('description')
                        ->required()
                        ->maxLength(4000)
                        ->columnSpanFull(),
                ])
                ->columns(2),
            Section::make('Hobbies')
                ->schema([
                    TextInput::make('hobbies.title')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('hobbies.subtitle')
                        ->required()
                        ->maxLength(255),
                    Repeater::make('hobbies.items')
                        ->schema([
                            TextInput::make('label')
                                ->required()
                                ->maxLength(255),
                            TextInput::make('iconId')
                                ->required()
                                ->maxLength(255),
                        ])
                        ->columns(2)
                        ->defaultItems(0)
                        ->columnSpanFull(),
                ])
                ->columns(2),
            Section::make('Languages')
                ->schema([
                    TextInput::make('languages.label')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('languages.iconId')
                        ->required()
                        ->maxLength(255),
                    Repeater::make('languages.items')
                        ->schema([
                            TextInput::make('label')
                                ->required()
                                ->maxLength(255),
                            TextInput::make('iconId')
                                ->required()
                                ->maxLength(255),
                            TextInput::make('level')
                                ->maxLength(255),
                        ])
                        ->columns(3)
                        ->defaultItems(0)
                        ->columnSpanFull(),
                ])
                ->columns(2),
            Section::make('Gender')
                ->schema([
                    TextInput::make('gender.label')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('gender.iconId')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('gender.value')
                        ->required()
                        ->maxLength(255)
                        ->columnSpanFull(),
                ])
                ->columns(2),
            Section::make('Nationality')
                ->schema([
                    TextInput::make('nationality.label')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('nationality.iconId')
                        ->required()
                        ->maxLength(255),
                    Repeater::make('nationality.items')
                        ->schema([
                            TextInput::make('label')
                                ->required()
                                ->maxLength(255),
                            TextInput::make('iconId')
                                ->required()
                                ->maxLength(255),
                        ])
                        ->columns(2)
                        ->defaultItems(0)
                        ->columnSpanFull(),
                ])
                ->columns(2),
        ];
    }
}
