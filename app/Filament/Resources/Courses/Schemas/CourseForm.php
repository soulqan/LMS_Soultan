<?php

namespace App\Filament\Resources\Courses\Schemas;

use App\Enums\CourseLevel;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class CourseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('category_id')
                    ->relationship('category', 'name')
                    ->required(),
                TextInput::make('title')
                    ->required(),
                Textarea::make('description')
                    ->required()
                    ->columnSpanFull(),
                FileUpload::make('thumbnail')
                    ->label('Thumbnail Photo')
                    ->image()
                    ->disk('public')
                    ->directory('courses')
                    ->imagePreviewHeight('220')
                    ->columnSpanFull(),
                Select::make('level')
                    ->options(CourseLevel::class)
                    ->required(),
                Repeater::make('lessons')
                    ->relationship()
                    ->orderColumn('order')
                    ->addActionLabel('Add lesson')
                    ->itemLabel(fn (array $state): ?string => $state['title'] ?? 'Untitled lesson')
                    ->itemNumbers()
                    ->reorderableWithButtons()
                    ->collapsible()
                    ->collapsed()
                    ->schema([
                        TextInput::make('title')
                            ->required(),
                        TextInput::make('video_url')
                            ->label('Video URL')
                            ->url(),
                        Textarea::make('content')
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
