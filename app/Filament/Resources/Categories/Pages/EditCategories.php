<?php

namespace App\Filament\Resources\Categories\Pages;

use App\Filament\Resources\Categories\CategoriesResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;

class EditCategories extends EditRecord
{
    protected static string $resource = CategoriesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }


    protected function getFormActions(): array
    {
        return [
            parent::getSaveFormAction(),
            Action::make('cancel')
                ->label('Cancel')
                ->url(CategoriesResource::getUrl('index'))
                ->color('gray'),
        ];
    }
}
