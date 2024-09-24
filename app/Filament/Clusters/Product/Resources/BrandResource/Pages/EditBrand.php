<?php

namespace App\Filament\Clusters\Product\Resources\BrandResource\Pages;

use App\Filament\Clusters\Product\Resources\BrandResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use RedJasmine\Product\Application\Brand\Services\BrandCommandService;
use RedJasmine\Product\Application\Brand\Services\BrandQueryService;
use RedJasmine\Product\Application\Brand\UserCases\Commands\BrandCreateCommand;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;

class EditBrand extends EditRecord
{
    protected static string $resource = BrandResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected static string $queryService   = BrandQueryService::class;
    protected static string $commandService = BrandCommandService::class;
    protected static string $command        = BrandCreateCommand::class;

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
