<?php

namespace App\Filament\Clusters\Product\Resources\ProductSellerCategoryResource\Pages;

use App\Filament\Clusters\Product\Resources\ProductSellerCategoryResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use RedJasmine\Product\Application\Category\Services\ProductSellerCategoryCommandService;
use RedJasmine\Product\Application\Category\UserCases\Commands\ProductSellerCategoryCreateCommand;

class CreateProductSellerCategory extends CreateRecord
{
    protected static string $resource = ProductSellerCategoryResource::class;

    protected static string $commandService = ProductSellerCategoryCommandService::class;
    protected static string $command        = ProductSellerCategoryCreateCommand::class;

    protected function handleRecordCreation(array $data) : Model
    {
        $commandService = app(static::$commandService);

        return $commandService->create((static::$command)::from($data));

    }
}
