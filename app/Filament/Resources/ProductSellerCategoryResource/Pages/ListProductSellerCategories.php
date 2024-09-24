<?php

namespace App\Filament\Resources\ProductSellerCategoryResource\Pages;

use App\Filament\Resources\ProductSellerCategoryResource;
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
