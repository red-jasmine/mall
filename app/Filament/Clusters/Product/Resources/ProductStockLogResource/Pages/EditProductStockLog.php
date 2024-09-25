<?php

namespace App\Filament\Clusters\Product\Resources\ProductStockLogResource\Pages;

use App\Filament\Clusters\Product\Resources\ProductStockLogResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProductStockLog extends EditRecord
{
    protected static string $resource = ProductStockLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
