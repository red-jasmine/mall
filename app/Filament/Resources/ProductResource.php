<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use CodeWithDennis\FilamentSelectTree\SelectTree;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Log;
use RedJasmine\Ecommerce\Domain\Models\Enums\ProductTypeEnum;
use RedJasmine\Ecommerce\Domain\Models\Enums\ShippingTypeEnum;
use RedJasmine\Product\Domain\Product\Models\Enums\FreightPayerEnum;
use RedJasmine\Product\Domain\Product\Models\Enums\ProductStatusEnum;
use RedJasmine\Product\Domain\Product\Models\Product;
use RedJasmine\Product\Domain\Property\Models\Enums\PropertyTypeEnum;
use RedJasmine\Product\Domain\Property\Models\ProductProperty;
use RedJasmine\Product\Domain\Property\Models\ProductPropertyValue;

class ProductResource extends Resource
{


    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form) : Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('基本信息')->inlineLabel()
                                        ->schema([
                                            Forms\Components\TextInput::make('title')->required()->maxLength(60),
                                            Forms\Components\Radio::make('product_type')->required()->default(ProductTypeEnum::GOODS->value)->inline()->options(ProductTypeEnum::options()),
                                            Forms\Components\Radio::make('shipping_type')->required()->inline()->options(ShippingTypeEnum::options()),
                                            Forms\Components\TextInput::make('slogan')->maxLength(255),


                                            Forms\Components\Radio::make('is_customized')
                                                                  ->required()
                                                                  ->boolean()->inline()
                                                                  ->default(0),
                                            SelectTree::make('brand_id')
                                                      ->relationship('brand', 'name', 'parent_id')
                                                      ->enableBranchNode()
                                                      ->parentNullValue(0)
                                                      ->default(0),
                                            SelectTree::make('category_id')
                                                      ->relationship('category', 'name', 'parent_id')
                                                      ->enableBranchNode()
                                                      ->parentNullValue(0)
                                                      ->default(0), // 设置可选

                                            SelectTree::make('seller_category_id')
                                                      ->relationship('sellerCategory', 'name', 'parent_id')
                                                      ->enableBranchNode()
                                                      ->parentNullValue(0)
                                                      ->default(0), // 设置可选


                                            Forms\Components\Radio::make('status')->required()->inline()->options(ProductStatusEnum::options()),

                                            static::basicProps()->columnSpan('full'),

                                        ]),

                Forms\Components\Section::make('销售信息')->inlineLabel()
                                        ->schema([
                                            Forms\Components\Radio::make('is_multiple_spec')->required()->boolean()->live()->inline()->default(0),


                                            static::saleProps()->visible(fn(Forms\Get $get
                                            ) => $get('is_multiple_spec'))->live()
                                                ->afterStateUpdated(function ( $state, $old,Forms\Get $get, Forms\Set $set) {

                                                    Log::info('saleProps更新SKU',['state'=>$state]);
                                                    // TODO 更新SKU 值
                                                }),

                                            static::skus()->visible()->visible(fn(Forms\Get $get
                                            ) => $get('is_multiple_spec'))->live()
                                                ->afterStateUpdated(function ($state, $old) {
                                                    Log::info('更新SKU');
                                                }),
                                            Forms\Components\TextInput::make('stock')
                                                                      ->required()
                                                                      ->numeric()
                                                                      ->default(0)
                                                                      ->hidden(fn(Forms\Get $get
                                                                      ) => $get('is_multiple_spec')),
                                            Forms\Components\TextInput::make('price')
                                                                      ->required()
                                                                      ->numeric()
                                                                      ->default(0.00)
                                                                      ->formatStateUsing(fn($state
                                                                      ) => is_object($state) ? $state->value() : $state)
                                                                      ->hidden(fn(Forms\Get $get
                                                                      ) => $get('is_multiple_spec')),
                                            Forms\Components\TextInput::make('market_price')
                                                                      ->required()
                                                                      ->numeric()
                                                                      ->formatStateUsing(fn($state
                                                                      ) => is_object($state) ? $state->value() : $state)
                                                                      ->default(0.00)->hidden(fn(Forms\Get $get
                                                ) => $get('is_multiple_spec')),
                                            Forms\Components\TextInput::make('cost_price')
                                                                      ->required()
                                                                      ->numeric()
                                                                      ->formatStateUsing(fn($state
                                                                      ) => is_object($state) ? $state->value() : $state)
                                                                      ->default(0.00)->hidden(fn(Forms\Get $get
                                                ) => $get('is_multiple_spec')),
                                            Forms\Components\TextInput::make('unit')
                                                                      ->required()
                                                                      ->numeric()
                                                                      ->default(1),
                                            Forms\Components\TextInput::make('unit_name')
                                                                      ->maxLength(32),
                                            Forms\Components\TextInput::make('barcode')->maxLength(32),
                                            Forms\Components\TextInput::make('outer_id')->maxLength(255),

                                            Forms\Components\TextInput::make('safety_stock')
                                                                      ->numeric()
                                                                      ->default(0),

                                            Forms\Components\TextInput::make('min_limit')
                                                                      ->required()
                                                                      ->numeric()
                                                                      ->default(0),
                                            Forms\Components\TextInput::make('max_limit')
                                                                      ->required()
                                                                      ->numeric()
                                                                      ->default(0),
                                            Forms\Components\TextInput::make('step_limit')
                                                                      ->required()
                                                                      ->numeric()
                                                                      ->default(1),
                                            Forms\Components\TextInput::make('vip')
                                                                      ->required()
                                                                      ->numeric()
                                                                      ->default(0),

                                        ]),

                Forms\Components\Section::make('商品描述')->inlineLabel()
                                        ->schema([
                                            Forms\Components\FileUpload::make('image')->image(),
                                            Forms\Components\FileUpload::make('images')->image()->multiple(),
                                            Forms\Components\FileUpload::make('videos')->image()->multiple(),
                                            Forms\Components\RichEditor::make('detail'),
                                        ]),
                Forms\Components\Section::make('运营')->inlineLabel()
                                        ->schema([
                                            Forms\Components\TextInput::make('tips')->maxLength(255),
                                            Forms\Components\TextInput::make('points')
                                                                      ->required()
                                                                      ->numeric()
                                                                      ->default(0),
                                            Forms\Components\Radio::make('is_hot')->required()->inline()->boolean()->default(0),
                                            Forms\Components\Radio::make('is_new')->required()->inline()->boolean()->default(0),
                                            Forms\Components\Radio::make('is_best')->required()->inline()->boolean()->default(0),
                                            Forms\Components\Radio::make('is_benefit')->required()->inline()->boolean()->default(0),
                                            Forms\Components\TextInput::make('sort')
                                                                      ->required()
                                                                      ->numeric()
                                                                      ->minValue(0)
                                                                      ->default(0),
                                        ]),

                Forms\Components\Section::make('SEO')->inlineLabel()
                                        ->schema([
                                            Forms\Components\TextInput::make('keywords')->maxLength(255),
                                            Forms\Components\TextInput::make('description')->maxLength(255),
                                        ]),
                Forms\Components\Section::make('发货服务')->inlineLabel()->schema([

                    Forms\Components\Radio::make('freight_payer')->required()->default(FreightPayerEnum::SELLER->value)->inline()->options(FreightPayerEnum::options()),
                    Forms\Components\TextInput::make('postage_id')->numeric(),
                    Forms\Components\TextInput::make('delivery_time')
                                              ->required()
                                              ->numeric()
                                              ->default(0),

                ]),
                Forms\Components\Section::make('供应商')->inlineLabel()->schema([
                    Forms\Components\TextInput::make('supplier_type')
                                              ->maxLength(255),
                    Forms\Components\TextInput::make('supplier_id')
                                              ->numeric(),
                    Forms\Components\TextInput::make('supplier_product_id')
                                              ->numeric(),
                ]),

                Forms\Components\Section::make('其他')->inlineLabel()->schema([
                    Forms\Components\TextInput::make('remarks')->maxLength(255),
                ]),
                Forms\Components\TextInput::make('owner_type')
                                          ->required()
                                          ->maxLength(255),
                Forms\Components\TextInput::make('owner_id')
                                          ->required()
                                          ->numeric(),


            ])
            ->columns(1);
    }

    protected static function basicProps()
    {

        return Forms\Components\Repeater::make('basic_props')->schema([
            Forms\Components\Select::make('pid')
                                   ->live()
                                   ->columns(1)
                                   ->inlineLabel()
                                   ->searchable()
                                   ->getSearchResultsUsing(fn(string $search) : array => ProductProperty::where('name',
                                       'like', "%{$search}%")->limit(50)->pluck('name', 'id')->toArray())
                                   ->getOptionLabelUsing(fn($value, Forms\Get $get) : ?string => $get('name')),

            Forms\Components\Repeater::make('values')
                                     ->hiddenLabel()
                                     ->schema([
                                         Forms\Components\Select::make('vid')
                                                                ->searchable()
                                                                ->inlineLabel()
                                                                ->getSearchResultsUsing(fn(string $search
                                                                ) : array => ProductPropertyValue::where('name', 'like',
                                                                    "%{$search}%")->limit(50)->pluck('name',
                                                                    'id')->toArray())
                                                                ->getOptionLabelUsing(fn(
                                                                    $value,
                                                                    Forms\Get $get
                                                                ) : ?string => $get('name'))
                                                                ->hidden(fn(Forms\Get $get
                                                                ) => ProductProperty::find($get('../../pid'))?->type === PropertyTypeEnum::TEXT),


                                         Forms\Components\TextInput::make('name')
                                                                   ->maxLength(30)
                                                                   ->inlineLabel()
                                                                   ->hidden(fn(Forms\Get $get
                                                                   ) => ProductProperty::find($get('../../pid'))?->type !== PropertyTypeEnum::TEXT),


                                         Forms\Components\TextInput::make('alias')->maxLength(30)
                                                                   ->inlineLabel()
                                                                   ->hidden(fn(Forms\Get $get
                                                                   ) => ProductProperty::find($get('../../pid'))?->type === PropertyTypeEnum::TEXT),


                                     ])
                                     ->columns(2)
                                     ->minItems(1)
                                     ->reorderable(false)
                // 是否多选
                                     ->hidden(fn(Forms\Get $get) => !$get('pid')),


        ])
                                        ->columns(1)
                                        ->default([]);
    }


    protected static function saleProps()
    {
        return Forms\Components\Repeater::make('sale_props')->schema([
            Forms\Components\Select::make('pid')
                                   ->live()
                                   ->columns(1)
                                   ->inlineLabel()
                                   ->searchable()
                                   ->getSearchResultsUsing(fn(string $search) : array => ProductProperty::where('name',
                                       'like', "%{$search}%")->limit(50)->pluck('name', 'id')->toArray())
                                   ->getOptionLabelUsing(fn($value, Forms\Get $get) : ?string => $get('name')),

            Forms\Components\Repeater::make('values')
                                     ->hiddenLabel()
                                     ->schema([
                                         Forms\Components\Select::make('vid')
                                                                ->searchable()
                                                                ->inlineLabel()
                                                                ->getSearchResultsUsing(fn(string $search
                                                                ) : array => ProductPropertyValue::where('name', 'like',
                                                                    "%{$search}%")->limit(50)->pluck('name',
                                                                    'id')->toArray())
                                                                ->getOptionLabelUsing(fn(
                                                                    $value,
                                                                    Forms\Get $get
                                                                ) : ?string => $get('name'))
                                                                ->hidden(fn(Forms\Get $get
                                                                ) => ProductProperty::find($get('../../pid'))?->type === PropertyTypeEnum::TEXT),


                                         Forms\Components\TextInput::make('name')
                                                                   ->maxLength(30)
                                                                   ->inlineLabel()
                                                                   ->hidden(fn(Forms\Get $get
                                                                   ) => ProductProperty::find($get('../../pid'))?->type !== PropertyTypeEnum::TEXT),


                                         Forms\Components\TextInput::make('alias')->maxLength(30)
                                                                   ->inlineLabel()
                                                                   ->hidden(fn(Forms\Get $get
                                                                   ) => ProductProperty::find($get('../../pid'))?->type === PropertyTypeEnum::TEXT),


                                     ])
                                     ->columns(2)
                                     ->minItems(1)
                                     ->reorderable(false)
                // 是否多选
                                     ->hidden(fn(Forms\Get $get) => !$get('pid')),


        ])
                                        ->columns(1)
                                        ->default([]);
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
                Tables\Columns\TextColumn::make('title')
                                         ->searchable(),
                Tables\Columns\TextColumn::make('product_type')
                                         ->searchable(),
                Tables\Columns\TextColumn::make('shipping_type')
                                         ->searchable(),
                Tables\Columns\TextColumn::make('status')
                                         ->badge()
                                         ->formatStateUsing(fn($state) => ProductStatusEnum::labels()[$state->value])
                                         ->color(fn($state) => ProductStatusEnum::colors()[$state->value])
                ,
                Tables\Columns\ImageColumn::make('image'),
                Tables\Columns\TextColumn::make('barcode')->searchable(),
                Tables\Columns\TextColumn::make('outer_id')->searchable(),

                Tables\Columns\TextColumn::make('is_multiple_spec')
                                         ->numeric()
                                         ->sortable(),

                Tables\Columns\TextColumn::make('brand.name')
                                         ->numeric()
                                         ->sortable(),
                Tables\Columns\TextColumn::make('category.name')
                                         ->numeric()
                                         ->sortable(),
                Tables\Columns\TextColumn::make('sellerCategory.name')
                                         ->numeric()
                                         ->sortable(),

                Tables\Columns\TextColumn::make('price')
                                         ->money()
                                         ->sortable(),
                Tables\Columns\TextColumn::make('market_price')
                                         ->numeric()
                                         ->sortable(),
                Tables\Columns\TextColumn::make('cost_price')
                                         ->numeric()
                                         ->sortable(),

                Tables\Columns\TextColumn::make('stock')
                                         ->numeric()
                                         ->sortable(),
                Tables\Columns\TextColumn::make('safety_stock')
                                         ->numeric()
                                         ->sortable(),

                Tables\Columns\TextColumn::make('sort')
                                         ->numeric()
                                         ->sortable(),

                Tables\Columns\TextColumn::make('sales')
                                         ->numeric()
                                         ->sortable(),
                Tables\Columns\TextColumn::make('views')
                                         ->numeric()
                                         ->sortable(),
                Tables\Columns\TextColumn::make('modified_time')
                                         ->dateTime()
                                         ->sortable(),
                Tables\Columns\TextColumn::make('version')
                                         ->numeric()
                                         ->sortable(),

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
            'index'  => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit'   => Pages\EditProduct::route('/{record}/edit'),
        ];
    }

    protected static function skus()
    {
        return Forms\Components\Repeater::make('skus')->schema([
            Forms\Components\TextInput::make('properties_name')->maxLength(32),
            Forms\Components\TextInput::make('properties')->maxLength(32),
            Forms\Components\TextInput::make('price')->maxLength(32),
            Forms\Components\TextInput::make('market_price')->maxLength(32),
            Forms\Components\TextInput::make('cost_price')->maxLength(32),
            Forms\Components\TextInput::make('stock')->maxLength(32),
            Forms\Components\TextInput::make('safety_stock')->maxLength(32),
            Forms\Components\TextInput::make('barcode')->maxLength(32),
            Forms\Components\TextInput::make('outer_id')->maxLength(32),
            Forms\Components\TextInput::make('supplier_sku_id')->maxLength(32),
            Forms\Components\TextInput::make('status')->maxLength(32),
        ]);
    }
}
