<?php

namespace App\Filament\Clusters\Product\Resources\BrandResource\Pages;

use App\Filament\Clusters\Product\FilamentResource\ResourcePageHelper;
use App\Filament\Clusters\Product\Resources\BrandResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBrand extends EditRecord
{
    protected static string $resource = BrandResource::class;
    use ResourcePageHelper;

    protected function getHeaderActions() : array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }


}
