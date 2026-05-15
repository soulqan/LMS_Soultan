<?php

namespace App\Filament\Resources\Categories;

use App\Filament\Resources\Categories\Pages\CreateCategories;
use App\Filament\Resources\Categories\Pages\EditCategories;
use App\Filament\Resources\Categories\Pages\ListCategories;
use App\Filament\Resources\Categories\Pages\ViewCategories;
use App\Filament\Resources\Categories\Schemas\CategoriesForm;
// use App\Filament\Resources\Categories\Schemas\CategoriesInfolist;
use App\Filament\Resources\Categories\Tables\CategoriesTable;
use App\Models\Category;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CategoriesResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'categoriesResources';

    public static function form(Schema $schema): Schema
    {
        return CategoriesForm::configure($schema);
    }

    // public static function infolist(Schema $schema): Schema
    // {
    //     return CategoriesInfolist::configure($schema);
    // }

    public static function table(Table $table): Table
    {
        return CategoriesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCategories::route('/'),
            'create' => CreateCategories::route('/create'),
            'view' => ViewCategories::route('/{record}'),
            'edit' => EditCategories::route('/{record}/edit'),
        ];
    }
}
