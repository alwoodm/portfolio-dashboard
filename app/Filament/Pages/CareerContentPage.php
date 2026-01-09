<?php

namespace App\Filament\Pages;

use App\Filament\Pages\Content\BaseContentPage;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;

class CareerContentPage extends BaseContentPage
{
    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    protected static ?string $navigationLabel = 'Career';

    protected static ?string $navigationGroup = 'Content';

    protected static ?string $slug = 'content/career';

    protected static ?int $navigationSort = 40;

    protected static string $contentFile = 'career';

    protected function contentFormSchema(): array
    {
        return [
            Section::make('Career')
                ->schema([
                    TextInput::make('badge')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('title')
                        ->required()
                        ->maxLength(255),
                    MarkdownEditor::make('description')
                        ->required()
                        ->maxLength(2000)
                        ->columnSpanFull(),
                    Repeater::make('timeline')
                        ->schema([
                            TextInput::make('period')
                                ->required()
                                ->maxLength(255),
                            Toggle::make('isActive')
                                ->label('Active role'),
                            TextInput::make('role')
                                ->required()
                                ->maxLength(255),
                            TextInput::make('company')
                                ->required()
                                ->maxLength(255),
                            TextInput::make('companyUrl')
                                ->label('Company URL')
                                ->url()
                                ->maxLength(255),
                            MarkdownEditor::make('description')
                                ->required()
                                ->maxLength(2000)
                                ->columnSpanFull(),
                        ])
                        ->columns(2)
                        ->defaultItems(0)
                        ->itemLabel(fn (array $state): ?string => $state['role'] ?? null)
                        ->collapsible()
                        ->collapsed()
                        ->columnSpanFull(),
                ])
                ->columns(2),
        ];
    }
}
