<?php

namespace App\Filament\Clusters\Product\Resources\ProductPropertyValueResource\Pages;

use App\Filament\Clusters\Product\Resources\ProductPropertyValueResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewProductPropertyValue extends ViewRecord
{
    protected static string $resource = ProductPropertyValueResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
