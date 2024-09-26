<?php

namespace App\Filament\Clusters\Product\Resources\ProductCategoryResource\Pages;

use App\Filament\Clusters\Product\FilamentResource\ResourcePageHelper;
use App\Filament\Clusters\Product\Resources\ProductCategoryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProductCategory extends CreateRecord
{
    protected static string $resource = ProductCategoryResource::class;

    use ResourcePageHelper;
}
