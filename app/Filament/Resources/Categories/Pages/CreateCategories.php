<?php

namespace App\Filament\Resources\Categories\Pages;

use App\Filament\Resources\Categories\CategoriesResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;

class CreateCategories extends CreateRecord
{
    protected static string $resource = CategoriesResource::class;

    protected static bool $canCreateAnother = false;

    protected function getFormActions(): array
    {
        return [
            $this->getCreateFormAction(),
            $this->getBackFormAction(),
        ];
    }

    protected function getBackFormAction(): Action
    {
        return Action::make('back')
            ->label('Back')
            ->url(CategoriesResource::getUrl('index'))
            ->color('gray');
            
    }
}
