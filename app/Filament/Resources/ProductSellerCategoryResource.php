<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductSellerCategoryResource\Pages;
use App\Filament\Resources\ProductSellerCategoryResource\RelationManagers;
use RedJasmine\Product\Domain\Category\Models\ProductSellerCategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductSellerCategoryResource extends Resource
{
    protected static ?string $model = ProductSellerCategory::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';


    public static function getModelLabel() : string
    {
        return  __('red-jasmine.product::product-seller-category.labels.product-seller-category');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('owner_type')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('owner_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('parent_id')
                    ->relationship('parent', 'name')
                    ->required()
                    ->default(0),
                Forms\Components\TextInput::make('group_name')
                    ->maxLength(255),
                Forms\Components\FileUpload::make('image')
                    ->image(),
                Forms\Components\TextInput::make('sort')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('is_leaf')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('is_show')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('status')
                    ->required()
                    ->maxLength(32),
                Forms\Components\TextInput::make('creator_type')
                    ->maxLength(255),
                Forms\Components\TextInput::make('creator_id')
                    ->numeric(),
                Forms\Components\TextInput::make('updater_type')
                    ->maxLength(255),
                Forms\Components\TextInput::make('updater_id')
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->numeric()
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProductSellerCategories::route('/'),
            'create' => Pages\CreateProductSellerCategory::route('/create'),
            'edit' => Pages\EditProductSellerCategory::route('/{record}/edit'),
        ];
    }
}
