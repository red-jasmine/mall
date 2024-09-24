<?php

namespace App\Filament\Resources\ProductCategoryResource\Pages;

use App\Filament\Resources\ProductCategoryResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use RedJasmine\Product\Application\Category\Services\ProductCategoryCommandService;
use RedJasmine\Product\Application\Category\UserCases\Commands\ProductCategoryCreateCommand;

class CreateProductCategory extends CreateRecord
{
    protected static string $resource = ProductCategoryResource::class;

    protected static string $commandService = ProductCategoryCommandService::class;
    protected static string $command        = ProductCategoryCreateCommand::class;

    protected function handleRecordCreation(array $data) : Model
    {
        $commandService = app(static::$commandService);

        return $commandService->create((static::$command)::from($data));

    }
}
