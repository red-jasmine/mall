<?php

namespace App\Filament\Clusters\Product\Resources\ProductPropertyResource\Pages;

use App\Filament\Clusters\Product\Resources\ProductPropertyResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProductProperties extends ListRecords
{
    protected static string $resource = ProductPropertyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
