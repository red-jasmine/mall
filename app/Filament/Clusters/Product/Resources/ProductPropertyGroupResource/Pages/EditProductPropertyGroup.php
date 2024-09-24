<?php

namespace App\Filament\Clusters\Product\Resources\ProductPropertyGroupResource\Pages;

use App\Filament\Clusters\Product\Resources\ProductPropertyGroupResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProductPropertyGroup extends EditRecord
{
    protected static string $resource = ProductPropertyGroupResource::class;

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
