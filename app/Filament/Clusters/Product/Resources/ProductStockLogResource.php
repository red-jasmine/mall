<?php

namespace App\Filament\Clusters\Product\Resources;

use App\Filament\Clusters\Product;
use App\Filament\Clusters\Product\Resources\ProductStockLogResource\Pages;
use App\Filament\Clusters\Product\Resources\ProductStockLogResource\RelationManagers;
use RedJasmine\Product\Domain\Stock\Models\ProductStockLog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductStockLogResource extends Resource
{
    protected static ?string $model = ProductStockLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-queue-list';

    protected static ?string $cluster = Product::class;

    protected static ?int $navigationSort = 2;

    public static function getNavigationGroup() : ?string
    {
        return __('red-jasmine.product::product-stock.labels.product-stock');
    }

    public static function getModelLabel() : string
    {
        return __('red-jasmine.product::product-stock-log.labels.product-stock-log');
    }


    public static function form(Form $form): Form
    {
        return $form;
        return $form
            ->schema([
                Forms\Components\TextInput::make('owner_type')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('owner_id')
                    ->required()
                    ->numeric(),
                Forms\Components\Select::make('product_id')
                    ->relationship('product', 'title')
                    ->required(),
                Forms\Components\Select::make('sku_id')
                    ->relationship('sku', 'id')
                    ->required(),
                Forms\Components\TextInput::make('type')
                    ->required()
                    ->maxLength(32),
                Forms\Components\TextInput::make('change_type')
                    ->required()
                    ->maxLength(32),
                Forms\Components\TextInput::make('change_detail')
                    ->maxLength(255),
                Forms\Components\TextInput::make('stock')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('lock_stock')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('channel_type')
                    ->maxLength(255),
                Forms\Components\TextInput::make('channel_id')
                    ->numeric(),
                Forms\Components\TextInput::make('creator_type')
                    ->maxLength(255),
                Forms\Components\TextInput::make('creator_id')
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('ID')->sortable(),
                Tables\Columns\TextColumn::make('owner_type'),
                Tables\Columns\TextColumn::make('owner_id')->sortable(),
                Tables\Columns\TextColumn::make('product.title'),
                Tables\Columns\TextColumn::make('sku.properties_name'),
                Tables\Columns\TextColumn::make('action_type')->formatStateUsing(fn($state)=>$state->getLabel()),
                Tables\Columns\TextColumn::make('change_type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('change_detail')
                    ->searchable(),
                Tables\Columns\TextColumn::make('action_stock')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('lock_stock')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('channel_type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('channel_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('creator_type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('creator_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordUrl(null)
            ->actions([

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([

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
            'index' => Pages\ListProductStockLogs::route('/')
        ];
    }
}
