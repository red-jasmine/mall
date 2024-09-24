<?php

namespace App\Filament\Resources\BrandResource\Pages;

use App\Filament\Resources\BrandResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use RedJasmine\Product\Application\Brand\Services\BrandCommandService;
use RedJasmine\Product\Application\Brand\UserCases\Commands\BrandCreateCommand;
use RedJasmine\Product\Application\Category\Services\ProductCategoryCommandService;
use RedJasmine\Product\Application\Category\UserCases\Commands\ProductCategoryCreateCommand;

class CreateBrand extends CreateRecord
{
    protected static string $resource = BrandResource::class;



    protected static string $commandService = BrandCommandService::class;
    protected static string $command        = BrandCreateCommand::class;

    protected function handleRecordCreation(array $data) : Model
    {
        $commandService = app(static::$commandService);

        return $commandService->create((static::$command)::from($data));

    }
}
