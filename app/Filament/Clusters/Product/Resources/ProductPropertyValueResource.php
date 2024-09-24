<?php

namespace App\Filament\Clusters\Product\Resources;

use App\Filament\Clusters\Product;
use App\Filament\Clusters\Product\Resources\ProductPropertyValueResource\Pages;
use App\Filament\Clusters\Product\Resources\ProductPropertyValueResource\RelationManagers;
use RedJasmine\Product\Domain\Property\Models\Enums\PropertyStatusEnum;
use RedJasmine\Product\Domain\Property\Models\ProductPropertyValue;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductPropertyValueResource extends Resource
{
    protected static ?string $model = ProductPropertyValue::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = Product::class;

    public static function getNavigationGroup() : ?string
    {
        return '属性';
    }

    public static function form(Form $form) : Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('pid')
                                       ->required()
                                       ->relationship('property', 'name')
                                       ->searchable(['name'])
                                       ->preload()->optionsLimit(2)
                                       ->nullable(),

                Forms\Components\Select::make('group_id')
                                       ->relationship('group', 'name')
                                       ->searchable(['name'])
                                       ->preload()
                                       ->nullable(),
                Forms\Components\TextInput::make('name')
                                          ->required()
                                          ->maxLength(64),
                Forms\Components\TextInput::make('sort')
                                          ->required()
                                          ->numeric()
                                          ->default(0),
                Forms\Components\Radio::make('status')
                                      ->required()
                                      ->default(PropertyStatusEnum::ENABLE)->options(PropertyStatusEnum::options())
                                      ->inline()->inlineLabel(false)->required(),
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

    public static function table(Table $table) : Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                                         ->label('ID')
                                         ->copyable()
                                         ->sortable(),
                Tables\Columns\TextColumn::make('property.name'),

                Tables\Columns\TextColumn::make('name')->copyable(),
                Tables\Columns\TextColumn::make('group.name')->sortable(),
                Tables\Columns\TextColumn::make('sort')->sortable(),
                Tables\Columns\TextColumn::make('status'),
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
            'index'  => Pages\ListProductPropertyValues::route('/'),
            'create' => Pages\CreateProductPropertyValue::route('/create'),
            'view'   => Pages\ViewProductPropertyValue::route('/{record}'),
            'edit'   => Pages\EditProductPropertyValue::route('/{record}/edit'),
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
