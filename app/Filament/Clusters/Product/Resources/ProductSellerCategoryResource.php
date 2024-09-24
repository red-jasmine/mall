<?php

namespace App\Filament\Clusters\Product\Resources;

use App\Filament\Clusters\Product;
use App\Filament\Clusters\Product\Resources\ProductSellerCategoryResource\Pages;
use App\Filament\Clusters\Product\Resources\ProductSellerCategoryResource\RelationManagers;
use CodeWithDennis\FilamentSelectTree\SelectTree;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use RedJasmine\Product\Application\Category\Services\ProductSellerCategoryCommandService;
use RedJasmine\Product\Application\Category\Services\ProductSellerCategoryQueryService;
use RedJasmine\Product\Domain\Category\Models\Enums\CategoryStatusEnum;
use RedJasmine\Product\Domain\Category\Models\ProductSellerCategory;

class ProductSellerCategoryResource extends Resource
{

    protected static ?string $cluster = Product::class;
    public function __construct(
        public ProductSellerCategoryCommandService $commandService,
        public ProductSellerCategoryQueryService $queryService

    ) {
    }

    protected static ?string $model = ProductSellerCategory::class;

    protected static ?string $navigationIcon = 'heroicon-o-squares-plus';


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
                                          ->maxLength(255),
                Forms\Components\TextInput::make('owner_id')
                                          ->required()
                                          ->numeric(),

                SelectTree::make('parent_id')
                          ->relationship(relationship: 'parent', titleAttribute: 'name', parentAttribute: 'parent_id')
                    // ->required()
                          ->searchable()
                          ->default(0)
                          ->enableBranchNode()
                          ->parentNullValue(0),
                Forms\Components\TextInput::make('name')
                                          ->required()
                                          ->maxLength(255),

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
                Tables\Columns\TextColumn::make('name')
                                         ->searchable(),
                Tables\Columns\TextColumn::make('parent.name')
                                         ->numeric()
                                         ->sortable(),
                Tables\Columns\TextColumn::make('group_name')
                                         ->searchable(),
                Tables\Columns\ImageColumn::make('image'),
                Tables\Columns\TextColumn::make('sort')
                                         ->numeric()
                                         ->sortable(),
                Tables\Columns\TextColumn::make('is_leaf')
                                         ->numeric()
                                         ->sortable(),
                Tables\Columns\TextColumn::make('is_show')
                                         ->numeric()
                                         ->sortable(),
                Tables\Columns\TextColumn::make('status')
                                         ->badge()
                                         ->searchable(),
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
            'index'  => \App\Filament\Clusters\Product\Resources\ProductSellerCategoryResource\Pages\ListProductSellerCategories::route('/'),
            'create' => \App\Filament\Clusters\Product\Resources\ProductSellerCategoryResource\Pages\CreateProductSellerCategory::route('/create'),
            'edit'   => \App\Filament\Clusters\Product\Resources\ProductSellerCategoryResource\Pages\EditProductSellerCategory::route('/{record}/edit'),
        ];
    }
}
