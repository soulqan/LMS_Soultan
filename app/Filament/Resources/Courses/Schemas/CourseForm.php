<?php

namespace App\Filament\Resources\Courses\Schemas;

use App\Enums\CourseLevel;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TagsInput;
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
                TextInput::make('subtitle'),
                Select::make('level')
                    ->options(CourseLevel::class)
                    ->required(),
                TextInput::make('price')
                    ->numeric()
                    ->required()
                    ->default(79.99),
                Textarea::make('description')
                    ->required()
                    ->columnSpanFull(),
                Textarea::make('about')
                    ->columnSpanFull(),
                TagsInput::make('what_you_will_learn')
                    ->label("What You'll Learn")
                    ->placeholder('Add a point...')
                    ->columnSpanFull(),
                TextInput::make('instructor_name')
                    ->required()
                    ->default('Ava Reynolds'),
                Textarea::make('instructor_bio')
                    ->columnSpanFull(),
                FileUpload::make('thumbnail')
                    ->label('Thumbnail Photo')
                    ->image()
                    ->disk('public')
                    ->directory('courses')
                    ->imagePreviewHeight('220')
                    ->columnSpanFull(),
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
                        Select::make('type')
                            ->options([
                                'video' => 'Video',
                                'module' => 'Module',
                                'quiz' => 'Quiz',
                            ])
                            ->required()
                            ->default('video')
                            ->live(),
                        TextInput::make('video_url')
                            ->label('Video URL')
                            ->url()
                            ->visible(fn ($get) => $get('type') === 'video'),
                        TextInput::make('duration_minutes')
                            ->integer()
                            ->required()
                            ->default(15)
                            ->label('Duration (minutes)'),
                        Textarea::make('content')
                            ->columnSpanFull(),
                        Repeater::make('module_content')
                            ->label('Module Structure')
                            ->schema([
                                TextInput::make('title')
                                    ->label('Section Title')
                                    ->required(),
                                Repeater::make('items')
                                    ->label('Sub-sections')
                                    ->schema([
                                        TextInput::make('subtitle')
                                            ->label('Subtitle'),
                                        Textarea::make('content')
                                            ->label('Content')
                                            ->rows(3),
                                        TextInput::make('photo')
                                            ->label('Photo Image URL')
                                            ->url(),
                                    ])
                                    ->grid(2)
                                    ->default([]),
                            ])
                            ->visible(fn ($get) => $get('type') === 'module')
                            ->columnSpanFull(),
                        TextInput::make('quiz_question')
                            ->label('Quiz Question')
                            ->visible(fn ($get) => $get('type') === 'quiz')
                            ->columnSpanFull(),
                        TagsInput::make('quiz_options')
                            ->label('Quiz Choices (Exactly 4)')
                            ->placeholder('Add a choice...')
                            ->visible(fn ($get) => $get('type') === 'quiz')
                            ->columnSpanFull(),
                        Select::make('quiz_correct_option')
                            ->label('Correct Answer')
                            ->options([
                                0 => 'Option 1',
                                1 => 'Option 2',
                                2 => 'Option 3',
                                3 => 'Option 4',
                            ])
                            ->visible(fn ($get) => $get('type') === 'quiz'),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
