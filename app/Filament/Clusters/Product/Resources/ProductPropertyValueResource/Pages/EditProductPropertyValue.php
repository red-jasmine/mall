<?php

namespace App\Filament\Clusters\Product\Resources\ProductPropertyValueResource\Pages;

use App\Filament\Clusters\Product\Resources\ProductPropertyValueResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProductPropertyValue extends EditRecord
{
    protected static string $resource = ProductPropertyValueResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
