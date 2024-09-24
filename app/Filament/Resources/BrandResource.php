<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BrandResource\Pages;
use App\Filament\Resources\BrandResource\RelationManagers;
use CodeWithDennis\FilamentSelectTree\SelectTree;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use RedJasmine\Product\Domain\Brand\Models\Brand;
use RedJasmine\Product\Domain\Brand\Models\Enums\BrandStatusEnum;
use RedJasmine\Product\Domain\Brand\Repositories\BrandReadRepositoryInterface;

class BrandResource extends Resource
{
    protected static ?string $model = Brand::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';



    public static function getModelLabel() : string
    {
        return __('red-jasmine.product::brand.labels.brand');
    }

    public static function form(Form $form) : Form
    {
        return $form
            ->schema([
                SelectTree::make('parent_id')
                          ->relationship(relationship: 'parent',titleAttribute:  'name',parentAttribute: 'parent_id')
                    // ->required()
                          ->searchable()
                          ->default(0)
                          ->enableBranchNode()
                          ->parentNullValue(0)
                ,
                Forms\Components\TextInput::make('name')
                                          ->label(__('red-jasmine.product::brand.fields.name'))
                                          ->required(),
                Forms\Components\TextInput::make('english_name')
                                          ->label(__('red-jasmine.product::brand.fields.english_name'))
                                          ->required(),
                Forms\Components\TextInput::make('initial')->label(__('red-jasmine.product::brand.fields.initial'))->required(),
                Forms\Components\TextInput::make('sort')
                                          ->label(__('red-jasmine.product::brand.fields.sort'))
                                          ->default(0)->required()->numeric()->minValue(0),
                Forms\Components\Radio::make('is_show')
                                      ->label(__('red-jasmine.product::brand.fields.is_show'))
                                      ->boolean()->inline()->options([true=>'是',false=>'否'])->default(true),
                Forms\Components\Radio::make('status')->label(__('red-jasmine.product::brand.fields.status'))
                                      ->options(BrandStatusEnum::options())
                                      ->inline()->default(BrandStatusEnum::ENABLE->value),
            ])->columns(1);
    }

    public static function table(Table $table) : Table
    {

        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id'),
                Tables\Columns\TextColumn::make('parent.name'),
                Tables\Columns\TextColumn::make('name')
                                         ->label(__('red-jasmine.product::brand.fields.name'))
                                         ->searchable(),
                Tables\Columns\TextColumn::make('initial')
                                         ->label(__('red-jasmine.product::brand.fields.initial')),
                Tables\Columns\TextColumn::make('english_name')
                                         ->label(__('red-jasmine.product::brand.fields.english_name')),
                Tables\Columns\ImageColumn::make('logo')->label(__('red-jasmine.product::brand.fields.logo')),

                Tables\Columns\IconColumn::make('is_show')->label(__('red-jasmine.product::brand.fields.is_show'))
                                         ->boolean(),
                Tables\Columns\TextColumn::make('sort')->label(__('red-jasmine.product::brand.fields.sort'))
                                         ->sortable(),
                Tables\Columns\TextColumn::make('status')->label(__('red-jasmine.product::brand.fields.status'))
                                         ->badge()
                                         ->formatStateUsing(fn($state
                                         ) : string => BrandStatusEnum::labels()[$state->value])
                                         ->color(fn($state) : string => BrandStatusEnum::colors()[$state->value]),

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
            'index'  => Pages\ListBrands::route('/'),
            'create' => Pages\CreateBrand::route('/create'),
            'edit'   => Pages\EditBrand::route('/{record}/edit'),
        ];
    }
}
