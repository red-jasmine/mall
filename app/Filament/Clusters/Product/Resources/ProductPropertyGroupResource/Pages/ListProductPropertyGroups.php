<?php

namespace App\Filament\Clusters\Product\Resources\ProductPropertyGroupResource\Pages;

use App\Filament\Clusters\Product\Resources\ProductPropertyGroupResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProductPropertyGroups extends ListRecords
{
    protected static string $resource = ProductPropertyGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
