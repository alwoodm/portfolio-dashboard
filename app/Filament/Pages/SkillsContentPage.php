<?php

namespace App\Filament\Pages;

use App\Filament\Pages\Content\BaseContentPage;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;

class SkillsContentPage extends BaseContentPage
{
    protected static ?string $navigationIcon = 'heroicon-o-cpu-chip';

    protected static ?string $navigationLabel = 'Skills';

    protected static ?string $navigationGroup = 'Content';

    protected static ?string $slug = 'content/skills';

    protected static ?int $navigationSort = 30;

    protected static string $contentFile = 'skills';

    protected function contentFormSchema(): array
    {
        return [
            Section::make('Skills')
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
                    Repeater::make('categories')
                        ->schema([
                            TextInput::make('title')
                                ->required()
                                ->maxLength(255),
                            Repeater::make('items')
                                ->schema([
                                    TextInput::make('name')
                                        ->required()
                                        ->maxLength(255),
                                    TextInput::make('iconId')
                                        ->required()
                                        ->maxLength(255),
                                ])
                                ->columns(2)
                                ->defaultItems(0),
                        ])
                        ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                        ->collapsible()
                        ->collapsed()
                        ->defaultItems(0)
                        ->columnSpanFull(),
                ])
                ->columns(2),
        ];
    }
}
