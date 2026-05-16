<?php

namespace App\Filament\Resources\Courses\Schemas;

use App\Enums\CourseLevel;
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
                TextInput::make('thumbnail'),
                Select::make('level')
                    ->options(CourseLevel::class)
                    ->required(),
            ]);
    }
}
