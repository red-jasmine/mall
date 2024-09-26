<?php

namespace App\Filament\Clusters\Product\Resources\ProductPropertyValueResource\Pages;

use App\Filament\Clusters\Product\FilamentResource\ResourcePageHelper;
use App\Filament\Clusters\Product\Resources\ProductPropertyValueResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProductPropertyValue extends CreateRecord
{
    protected static string $resource = ProductPropertyValueResource::class;
    use ResourcePageHelper;
}
