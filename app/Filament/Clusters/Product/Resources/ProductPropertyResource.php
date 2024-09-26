<?php

namespace App\Filament\Clusters\Product\Resources;

use App\Filament\Clusters\Product;
use App\Filament\Clusters\Product\FilamentResource\ResourcePageHelper;
use App\Filament\Clusters\Product\Resources\ProductPropertyResource\Pages;
use App\Filament\Clusters\Product\Resources\ProductPropertyResource\RelationManagers;
use RedJasmine\Product\Application\Product\Services\ProductCommandService;
use RedJasmine\Product\Application\Product\Services\ProductQueryService;
use RedJasmine\Product\Application\Property\Services\ProductPropertyCommandService;
use RedJasmine\Product\Application\Property\Services\ProductPropertyQueryService;
use RedJasmine\Product\Application\Property\UserCases\Commands\ProductPropertyCreateCommand;
use RedJasmine\Product\Application\Property\UserCases\Commands\ProductPropertyDeleteCommand;
use RedJasmine\Product\Application\Property\UserCases\Commands\ProductPropertyUpdateCommand;
use RedJasmine\Product\Domain\Property\Models\Enums\PropertyStatusEnum;
use RedJasmine\Product\Domain\Property\Models\Enums\PropertyTypeEnum;
use RedJasmine\Product\Domain\Property\Models\ProductProperty;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductPropertyResource extends Resource
{
    protected static ?string $model = ProductProperty::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube-transparent';

    use ResourcePageHelper;
    protected static ?string $commandService = ProductPropertyCommandService::class;
    protected static ?string $queryService   = ProductPropertyQueryService::class;
    protected static ?string $createCommand  = ProductPropertyCreateCommand::class;
    protected static ?string $updateCommand  = ProductPropertyUpdateCommand::class;
    protected static ?string $deleteCommand  = ProductPropertyDeleteCommand::class;


    public static function getModelLabel() : string
    {
        return __('red-jasmine.product::product-property.labels.product-property');
    }

    protected static ?string $cluster = Product::class;


    public static function getNavigationGroup() : ?string
    {
        return __('red-jasmine.product::product-property.labels.product-property');
    }

    public static function form(Form $form) : Form
    {
        return $form
            ->schema([

                         Forms\Components\Radio::make('type')
                                               ->required()
                                               ->inline()
                                               ->inlineLabel(false)
                                               ->default(PropertyTypeEnum::SELECT)
                                               ->options(PropertyTypeEnum::options()),
                         Forms\Components\TextInput::make('name')
                                                   ->required()
                                                   ->maxLength(255),
                         Forms\Components\TextInput::make('description')->maxLength(255),

                         Forms\Components\TextInput::make('unit')->maxLength(10),
                         Forms\Components\Select::make('group_id')
                                                ->relationship('group', 'name')
                                                ->searchable([ 'name' ])
                                                ->preload()
                                                ->nullable(),
                         Forms\Components\Radio::make('is_allow_multiple')->default(false)->boolean()->inline()->inlineLabel(false)->required(),
                         Forms\Components\Radio::make('is_allow_alias')->default(false)->boolean()->inline()->inlineLabel(false)->required(),

                         Forms\Components\TextInput::make('sort')->required()->integer()->default(0),
                         Forms\Components\Radio::make('status')
                                               ->required()
                                               ->default(PropertyStatusEnum::ENABLE)->options(PropertyStatusEnum::options())
                                               ->inline()->inlineLabel(false)->required(),
                         Forms\Components\TextInput::make('creator_type')->readOnly()->visibleOn('view'),
                         Forms\Components\TextInput::make('creator_id')->readOnly()->visibleOn('view'),
                         Forms\Components\TextInput::make('updater_type')->readOnly()->visibleOn('view'),
                         Forms\Components\TextInput::make('updater_id')->readOnly()->visibleOn('view'),
                     ]);
    }

    public static function table(Table $table) : Table
    {
        return $table
            ->columns([
                          Tables\Columns\TextColumn::make('id')->label('ID')->sortable(),
                          Tables\Columns\TextColumn::make('group.name')->numeric(),
                          Tables\Columns\TextColumn::make('type')->badge()->formatStateUsing(fn($state
                          ) => PropertyTypeEnum::options()[$state->value])->color(fn($state
                          ) => PropertyTypeEnum::colors()[$state->value]),
                          Tables\Columns\TextColumn::make('name')->searchable(),
                          Tables\Columns\TextColumn::make('unit')
                          ,
                          Tables\Columns\IconColumn::make('is_allow_multiple')->boolean(),
                          Tables\Columns\IconColumn::make('is_allow_alias')->boolean(),
                          Tables\Columns\TextColumn::make('sort')->sortable(),
                          Tables\Columns\TextColumn::make('status')->badge()->formatStateUsing(fn($state) => $state->label())->color(fn($state) => $state->color()),
                          Tables\Columns\TextColumn::make('creator_type')->searchable()->toggleable(isToggledHiddenByDefault: true),
                          Tables\Columns\TextColumn::make('creator_id')->sortable()->toggleable(isToggledHiddenByDefault: true),
                          Tables\Columns\TextColumn::make('updater_type')->searchable()->toggleable(isToggledHiddenByDefault: true),
                          Tables\Columns\TextColumn::make('updater_id')->sortable()->toggleable(isToggledHiddenByDefault: true),
                          Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
                          Tables\Columns\TextColumn::make('updated_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
                          Tables\Columns\TextColumn::make('deleted_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
                      ])
            ->filters([
                          Tables\Filters\TrashedFilter::make(),
                      ])
            ->recordUrl(null)
            ->actions([
                          Tables\Actions\ViewAction::make(),
                          Tables\Actions\EditAction::make(),
                      ])
            ->bulkActions([
                              Tables\Actions\BulkActionGroup::make([
                                                                       Tables\Actions\DeleteBulkAction::make(),
                                                                       Tables\Actions\ForceDeleteBulkAction::make(),
                                                                       Tables\Actions\RestoreBulkAction::make(),
                                                                   ]),
                          ]);
    }

    public static function getRelations() : array
    {
        return [
            //
        ];
    }

    public static function getPages() : array
    {
        return [
            'index'  => Pages\ListProductProperties::route('/'),
            'create' => Pages\CreateProductProperty::route('/create'),
            'view'   => Pages\ViewProductProperty::route('/{record}'),
            'edit'   => Pages\EditProductProperty::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery() : Builder
    {
        return parent::getEloquentQuery()
                     ->withoutGlobalScopes([
                                               SoftDeletingScope::class,
                                           ]);
    }
}
