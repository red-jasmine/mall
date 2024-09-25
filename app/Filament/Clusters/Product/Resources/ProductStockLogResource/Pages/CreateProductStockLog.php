<?php

namespace App\Filament\Clusters\Product\Resources\ProductStockLogResource\Pages;

use App\Filament\Clusters\Product\Resources\ProductStockLogResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateProductStockLog extends CreateRecord
{
    protected static string $resource = ProductStockLogResource::class;
}
