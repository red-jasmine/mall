<?php

namespace App\Filament\Clusters\Product\Resources\ProductStockLogResource\Pages;

use App\Filament\Clusters\Product\Resources\ProductStockLogResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProductStockLogs extends ListRecords
{
    protected static string $resource = ProductStockLogResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
