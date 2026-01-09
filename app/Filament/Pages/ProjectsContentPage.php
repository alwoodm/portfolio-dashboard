<?php

namespace App\Filament\Pages;

use App\Filament\Pages\Content\BaseContentPage;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;

class ProjectsContentPage extends BaseContentPage
{
    protected static ?string $navigationIcon = 'heroicon-o-folder-open';

    protected static ?string $navigationLabel = 'Projects';

    protected static ?string $navigationGroup = 'Content';

    protected static ?string $slug = 'content/projects';

    protected static ?int $navigationSort = 50;

    protected static string $contentFile = 'projects';

    protected function contentFormSchema(): array
    {
        return [
            Section::make('Projects')
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
                    Repeater::make('items')
                        ->schema([
                            TextInput::make('title')
                                ->required()
                                ->maxLength(255),
                            Toggle::make('isLive')
                                ->label('Live'),
                            TextInput::make('link')
                                ->url()
                                ->maxLength(255),
                            MarkdownEditor::make('description')
                                ->required()
                                ->maxLength(2000)
                                ->columnSpanFull(),
                            Repeater::make('tags')
                                ->simple(
                                    TextInput::make('tag')
                                        ->required()
                                        ->maxLength(255)
                                )
                                ->defaultItems(0)
                                ->columnSpanFull(),
                        ])
                        ->columns(2)
                        ->defaultItems(0)
                        ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                        ->collapsible()
                        ->collapsed()
                        ->columnSpanFull(),
                ])
                ->columns(2),
        ];
    }
}
