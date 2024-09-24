<?php

namespace App\Filament\Clusters\Product\Resources\ProductPropertyGroupResource\Pages;

use App\Filament\Clusters\Product\Resources\ProductPropertyGroupResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewProductPropertyGroup extends ViewRecord
{
    protected static string $resource = ProductPropertyGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
