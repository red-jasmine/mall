<?php

namespace App\Filament\Clusters\Product\Resources\ProductPropertyResource\Pages;

use App\Filament\Clusters\Product\Resources\ProductPropertyResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateProductProperty extends CreateRecord
{
    protected static string $resource = ProductPropertyResource::class;
}
