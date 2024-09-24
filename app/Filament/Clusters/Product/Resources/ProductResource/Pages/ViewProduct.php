<?php

namespace App\Filament\Clusters\Product\Resources\ProductResource\Pages;

use App\Filament\Clusters\Product\Resources\ProductResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Database\Eloquent\Model;
use RedJasmine\Product\Application\Product\Services\ProductQueryService;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;

class ViewProduct extends ViewRecord
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
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

}