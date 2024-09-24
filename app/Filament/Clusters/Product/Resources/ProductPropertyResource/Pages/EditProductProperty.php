<?php

namespace App\Filament\Clusters\Product\Resources\ProductPropertyResource\Pages;

use App\Filament\Clusters\Product\Resources\ProductPropertyResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditProductProperty extends EditRecord

{
    protected static string $resource = ProductPropertyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data) : Model
    {

        return parent::handleRecordUpdate($record, $data); // TODO: Change the autogenerated stub
    }
}
