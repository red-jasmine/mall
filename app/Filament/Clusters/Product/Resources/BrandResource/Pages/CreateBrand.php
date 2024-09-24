<?php

namespace App\Filament\Clusters\Product\Resources\BrandResource\Pages;

use App\Filament\Clusters\Product\Resources\BrandResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use RedJasmine\Product\Application\Brand\Services\BrandCommandService;
use RedJasmine\Product\Application\Brand\UserCases\Commands\BrandCreateCommand;

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
