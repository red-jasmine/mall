<?php

namespace App\Filament\Clusters\Product\Resources\ProductSellerCategoryResource\Pages;

use App\Filament\Clusters\Product\Resources\ProductSellerCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProductSellerCategories extends ListRecords
{
    protected static string $resource = ProductSellerCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

}
