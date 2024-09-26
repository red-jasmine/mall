<?php

namespace App\Filament\Clusters\Product\Resources\ProductPropertyResource\Pages;

use App\Filament\Clusters\Product\FilamentResource\ResourcePageHelper;
use App\Filament\Clusters\Product\Resources\ProductPropertyResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProductProperty extends CreateRecord
{
    protected static string $resource = ProductPropertyResource::class;
    use ResourcePageHelper;
}
