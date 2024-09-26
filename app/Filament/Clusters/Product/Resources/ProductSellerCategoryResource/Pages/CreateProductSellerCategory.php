<?php

namespace App\Filament\Clusters\Product\Resources\ProductSellerCategoryResource\Pages;

use App\Filament\Clusters\Product\FilamentResource\ResourcePageHelper;
use App\Filament\Clusters\Product\Resources\ProductSellerCategoryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProductSellerCategory extends CreateRecord
{
    protected static string $resource = ProductSellerCategoryResource::class;

    use ResourcePageHelper;
}
