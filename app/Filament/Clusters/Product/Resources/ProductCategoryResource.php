<?php

namespace App\Filament\Clusters\Product\Resources;

use App\Filament\Clusters\Product;
use App\Filament\Clusters\Product\Resources\ProductCategoryResource\Pages;
use App\Filament\Clusters\Product\Resources\ProductCategoryResource\Pages\CreateProductCategory;
use App\Filament\Clusters\Product\Resources\ProductCategoryResource\Pages\EditProductCategory;
use App\Filament\Clusters\Product\Resources\ProductCategoryResource\Pages\ListProductCategories;
use App\Filament\Clusters\Product\Resources\ProductCategoryResource\RelationManagers;
use CodeWithDennis\FilamentSelectTree\SelectTree;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use RedJasmine\Product\Domain\Category\Models\Enums\CategoryStatusEnum;
use RedJasmine\Product\Domain\Category\Models\ProductCategory;

class ProductCategoryResource extends Resource
{
    protected static ?string $cluster = Product::class;
    protected static ?string $model   = ProductCategory::class;

    public static function getModelLabel() : string
    {
        return __('red-jasmine.product::product-category.labels.product-category');
    }

    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';




    public static function form(Form $form) : Form
    {
        return $form
            ->schema([

                SelectTree::make('parent_id')
                          ->relationship(relationship: 'parent', titleAttribute: 'name', parentAttribute: 'parent_id')
                    // ->required()
                          ->searchable()
                          ->default(0)
                          ->enableBranchNode()
                          ->parentNullValue(0)
                ,
                Forms\Components\TextInput::make('name')
                                          ->required()
                                          ->maxLength(255),
                Forms\Components\FileUpload::make('image')
                                           ->image(),
                Forms\Components\TextInput::make('group_name')
                                          ->maxLength(255),
                Forms\Components\TextInput::make('sort')
                                          ->required()
                                          ->numeric()
                                          ->default(0),
                Forms\Components\Toggle::make('is_leaf')
                                       ->required()
                                       ->default(0),
                Forms\Components\Toggle::make('is_show')
                                       ->required()
                                       ->default(0),
                Forms\Components\Radio::make('status')
                                      ->required()
                                      ->options(CategoryStatusEnum::options()),
            ]);
    }

    public static function table(Table $table) : Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                                         ->label('ID')
                                         ->sortable(),
                Tables\Columns\TextColumn::make('parent.name')
                                         ->sortable(),
                Tables\Columns\TextColumn::make('name')
                                         ->searchable(),
                Tables\Columns\ImageColumn::make('image'),
                Tables\Columns\TextColumn::make('group_name')
                                         ->searchable(),

                Tables\Columns\IconColumn::make('is_leaf')->boolean(),
                Tables\Columns\IconColumn::make('is_show')->boolean(),
                Tables\Columns\TextColumn::make('sort')->sortable(),
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
            'index'  => ListProductCategories::route('/'),
            'create' => CreateProductCategory::route('/create'),
            'edit'   => EditProductCategory::route('/{record}/edit'),
        ];
    }
}
