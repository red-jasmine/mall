<?php

namespace App\Filament\Clusters\Product\Resources\BrandResource\Pages;

use App\Filament\Clusters\Product\FilamentResource\ResourcePageHelper;
use App\Filament\Clusters\Product\Resources\BrandResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBrand extends CreateRecord
{

    use ResourcePageHelper;

    protected static string $resource = BrandResource::class;


}


