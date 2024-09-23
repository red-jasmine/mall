<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use RedJasmine\Product\Application\Product\Services\ProductCommandService;
use RedJasmine\Product\Application\Product\UserCases\Commands\ProductCreateCommand;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;


    protected function handleRecordCreation(array $data) : Model
    {


        $this->commandService = app(ProductCommandService::class);
        $command              = ProductCreateCommand::from($data);

       return $this->commandService->create($command);


    }



}
