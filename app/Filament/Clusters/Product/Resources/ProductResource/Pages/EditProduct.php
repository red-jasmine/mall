<?php

namespace App\Filament\Clusters\Product\Resources\ProductResource\Pages;

use App\Filament\Clusters\Product\Resources\ProductResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use RedJasmine\Product\Application\Product\Services\ProductCommandService;
use RedJasmine\Product\Application\Product\Services\ProductQueryService;
use RedJasmine\Product\Application\Product\UserCases\Commands\ProductUpdateCommand;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions() : array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }


    protected function resolveRecord(int|string $key) : Model
    {
        $query          = FindQuery::make($key);
        $query->include = ['skus', 'info'];
        $model          = app(ProductQueryService::class)->findById($query);
        foreach ($model->info->getAttributes() as $key => $value) {
            $model->setAttribute($key, $model->info->{$key});
        }
        $model->setAttribute('skus', $model->skus->toArray());

        return $model;
    }


    protected function handleRecordUpdate(Model $record, array $data) : Model
    {

        $this->commandService = app(ProductCommandService::class);
        $data['id']           = $record->getKey();
        $command              = ProductUpdateCommand::from($data);


        return $this->commandService->update($command);
    }

}
