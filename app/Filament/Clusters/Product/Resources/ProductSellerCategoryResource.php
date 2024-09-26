<?php

namespace App\Filament\Clusters\Product\Resources;

use App\Filament\Clusters\Product;
use App\Filament\Clusters\Product\FilamentResource\ResourcePageHelper;
use App\Filament\Clusters\Product\Resources\ProductSellerCategoryResource\Pages;
use App\Filament\Clusters\Product\Resources\ProductSellerCategoryResource\RelationManagers;
use CodeWithDennis\FilamentSelectTree\SelectTree;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use LibDNS\Records\Record;
use RedJasmine\Product\Application\Category\Services\ProductCategoryCommandService;
use RedJasmine\Product\Application\Category\Services\ProductCategoryQueryService;
use RedJasmine\Product\Application\Category\Services\ProductSellerCategoryCommandService;
use RedJasmine\Product\Application\Category\Services\ProductSellerCategoryQueryService;
use RedJasmine\Product\Application\Category\UserCases\Commands\ProductCategoryCreateCommand;
use RedJasmine\Product\Application\Category\UserCases\Commands\ProductCategoryDeleteCommand;
use RedJasmine\Product\Application\Category\UserCases\Commands\ProductCategoryUpdateCommand;
use RedJasmine\Product\Application\Category\UserCases\Commands\ProductSellerCategoryCreateCommand;
use RedJasmine\Product\Application\Category\UserCases\Commands\ProductSellerCategoryDeleteCommand;
use RedJasmine\Product\Application\Category\UserCases\Commands\ProductSellerCategoryUpdateCommand;
use RedJasmine\Product\Domain\Category\Models\Enums\CategoryStatusEnum;
use RedJasmine\Product\Domain\Category\Models\ProductSellerCategory;

class ProductSellerCategoryResource extends Resource
{

    protected static ?string $cluster = Product::class;
    protected static ?string $model = ProductSellerCategory::class;
    protected static ?string $navigationIcon = 'heroicon-o-squares-plus';
    protected static ?string $commandService = ProductSellerCategoryCommandService::class;

    use ResourcePageHelper;
    protected static ?string $queryService   = ProductSellerCategoryQueryService::class;
    protected static ?string $createCommand  = ProductSellerCategoryCreateCommand::class;
    protected static ?string $updateCommand  = ProductSellerCategoryUpdateCommand::class;
    protected static ?string $deleteCommand  = ProductSellerCategoryDeleteCommand::class;

    public function __construct()
    {
    }

    public static function getModelLabel() : string
    {
        return __('red-jasmine.product::product-seller-category.labels.product-seller-category');
    }

    public static function form(Form $form) : Form
    {
        return $form
            ->schema([
                         Forms\Components\TextInput::make('owner_type')
                                                   ->required()
                                                   ->maxLength(255)->live()->readOnlyOn('edit'),
                         Forms\Components\TextInput::make('owner_id')
                                                   ->required()
                                                   ->numeric()
                                                   ->live()
                                                   ->readOnlyOn('edit'),

                         SelectTree::make('parent_id')
                                   ->relationship(relationship: 'parent',
                                       titleAttribute:          'name',
                                       parentAttribute:         'parent_id',
                                       modifyQueryUsing: fn($query, Forms\Get $get, ?Model $record) => $query->where('owner_type', $get('owner_type'))
                                                                                                             ->where('owner_id', $get('owner_id'))
                                                                                                             ->when($record?->getKey(), fn($query, $value) => $query->where('id', '<>', $value)),
                                       modifyChildQueryUsing: fn($query, Forms\Get $get, ?Model $record) => $query->where('owner_type', $get('owner_type'))
                                                                                                                  ->where('owner_id', $get('owner_id'))
                                                                                                                  ->when($record?->getKey(), fn($query, $value) => $query->where('id', '<>', $value)),
                                   )
                             // ->required()
                                   ->searchable()
                                   ->default(0)
                                   ->enableBranchNode()
                                   ->parentNullValue(0),
                         Forms\Components\TextInput::make('name')
                                                   ->required()
                                                   ->maxLength(255),
                         Forms\Components\TextInput::make('description')->maxLength(255),
                         Forms\Components\TextInput::make('group_name')
                                                   ->maxLength(255),
                         Forms\Components\FileUpload::make('image')
                                                    ->image(),
                         Forms\Components\TextInput::make('sort')
                                                   ->required()
                                                   ->numeric()
                                                   ->default(0),
                         Forms\Components\Radio::make('is_leaf')->default(false)->boolean()->inline()->inlineLabel(false)->required(),
                         Forms\Components\Radio::make('is_show')->default(true)->boolean()->inline()->inlineLabel(false)->required(),

                         Forms\Components\Radio::make('status')
                                               ->required()
                                               ->default(CategoryStatusEnum::ENABLE)
                                               ->options(CategoryStatusEnum::options())
                                               ->inline()->inlineLabel(false)->required(),
                     ]);
    }

    public static function table(Table $table) : Table
    {
        return $table
            ->columns([
                          Tables\Columns\TextColumn::make('id')
                                                   ->label('ID')
                                                   ->sortable(),
                          Tables\Columns\TextColumn::make('owner_type')
                                                   ->searchable(),
                          Tables\Columns\TextColumn::make('owner_id')
                                                   ->numeric()
                                                   ->sortable(),
                          Tables\Columns\TextColumn::make('parent.name')
                                                   ->sortable(),
                          Tables\Columns\TextColumn::make('name')
                                                   ->searchable(),

                          Tables\Columns\TextColumn::make('group_name')
                                                   ->searchable(),
                          Tables\Columns\ImageColumn::make('image'),
                          Tables\Columns\TextColumn::make('sort')->sortable(),
                          Tables\Columns\IconColumn::make('is_leaf')->boolean(),
                          Tables\Columns\IconColumn::make('is_allow_alias')->boolean(),
                          Tables\Columns\TextColumn::make('status')->badge()->formatStateUsing(fn($state) => $state->label())->color(fn($state) => $state->color()),

                      ])
            ->filters([
                          //
                      ])
            ->actions([
                          Tables\Actions\EditAction::make(),
                      ])
            ->bulkActions([
                              Tables\Actions\BulkActionGroup::make([
                                                                       Tables\Actions\DeleteBulkAction::make(),
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
            'index'  => Pages\ListProductSellerCategories::route('/'),
            'create' => Pages\CreateProductSellerCategory::route('/create'),
            'edit'   => Pages\EditProductSellerCategory::route('/{record}/edit'),
        ];
    }
}
