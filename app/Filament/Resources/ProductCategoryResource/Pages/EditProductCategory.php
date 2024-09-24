<?php

namespace App\Filament\Resources\ProductCategoryResource\Pages;

use App\Filament\Resources\ProductCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use RedJasmine\Product\Application\Category\Services\ProductCategoryCommandService;
use RedJasmine\Product\Application\Category\Services\ProductCategoryQueryService;
use RedJasmine\Product\Application\Category\Services\ProductSellerCategoryCommandService;
use RedJasmine\Product\Application\Category\Services\ProductSellerCategoryQueryService;
use RedJasmine\Product\Application\Category\UserCases\Commands\ProductCategoryUpdateCommand;
use RedJasmine\Product\Application\Category\UserCases\Commands\ProductSellerCategoryUpdateCommand;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;

class EditProductCategory extends EditRecord
{
    protected static string $resource = ProductCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }


    protected static string $queryService   = ProductCategoryQueryService::class;
    protected static string $commandService = ProductCategoryCommandService::class;
    protected static string $command        = ProductCategoryUpdateCommand::class;

    protected function resolveRecord(int|string $key) : Model
    {
        $queryService = app(static::$queryService);

        return $queryService->findById(FindQuery::make($key));
    }


    protected function handleRecordUpdate(Model $record, array $data) : Model
    {
        $commandService = app(static::$commandService);
        $data['id']     = $record->getKey();
        return $commandService->update((static::$command)::from($data));


    }
}
