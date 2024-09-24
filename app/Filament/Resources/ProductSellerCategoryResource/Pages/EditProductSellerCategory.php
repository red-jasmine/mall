<?php

namespace App\Filament\Resources\ProductSellerCategoryResource\Pages;

use App\Filament\Resources\ProductSellerCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use RedJasmine\Product\Application\Category\Services\ProductSellerCategoryCommandService;
use RedJasmine\Product\Application\Category\Services\ProductSellerCategoryQueryService;
use RedJasmine\Product\Application\Category\UserCases\Commands\ProductSellerCategoryCreateCommand;
use RedJasmine\Product\Application\Category\UserCases\Commands\ProductSellerCategoryUpdateCommand;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;

class EditProductSellerCategory extends EditRecord
{
    protected static string $resource = ProductSellerCategoryResource::class;


    protected function getHeaderActions() : array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected static string $queryService   = ProductSellerCategoryQueryService::class;
    protected static string $commandService = ProductSellerCategoryCommandService::class;
    protected static string $command        = ProductSellerCategoryUpdateCommand::class;

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
