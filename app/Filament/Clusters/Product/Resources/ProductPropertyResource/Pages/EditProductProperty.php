<?php

namespace App\Filament\Clusters\Product\Resources\ProductPropertyResource\Pages;

use App\Filament\Clusters\Product\FilamentResource\ResourcePageHelper;
use App\Filament\Clusters\Product\Resources\ProductPropertyResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProductProperty extends EditRecord

{
    protected static string $resource = ProductPropertyResource::class;

    protected function getHeaderActions() : array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }

    use ResourcePageHelper;
}
