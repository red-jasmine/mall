<?php

namespace App\Filament\Clusters\Product\Resources\ProductSellerCategoryResource\Pages;

use App\Filament\Clusters\Product\FilamentResource\ResourcePageHelper;
use App\Filament\Clusters\Product\Resources\ProductSellerCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProductSellerCategory extends EditRecord
{
    protected static string $resource = ProductSellerCategoryResource::class;


    protected function getHeaderActions() : array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    use ResourcePageHelper;
}
