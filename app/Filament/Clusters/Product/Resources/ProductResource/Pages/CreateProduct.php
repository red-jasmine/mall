<?php

namespace App\Filament\Clusters\Product\Resources\ProductResource\Pages;

use App\Filament\Clusters\Product\FilamentResource\ResourcePageHelper;
use App\Filament\Clusters\Product\Resources\ProductResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;


    use ResourcePageHelper;
}
