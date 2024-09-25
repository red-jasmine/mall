<?php

namespace App\Filament\Clusters\Product\Resources\ProductStockResource\Pages;

use App\Filament\Clusters\Product\Resources\ProductStockResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProductStocks extends ListRecords
{
    protected static string $resource = ProductStockResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
