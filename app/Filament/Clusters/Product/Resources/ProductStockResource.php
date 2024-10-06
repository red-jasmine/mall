<?php

namespace App\Filament\Clusters\Product\Resources;

use App\Filament\Clusters\Product;
use App\Filament\Clusters\Product\Resources\ProductStockResource\Pages;
use App\Filament\Clusters\Product\Resources\ProductStockResource\RelationManagers;
use Awcodes\TableRepeater\Components\TableRepeater;
use Awcodes\TableRepeater\Header;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use RedJasmine\Product\Application\Stock\Services\StockCommandService;
use RedJasmine\Product\Application\Stock\UserCases\BulkStockCommand;
use RedJasmine\Product\Domain\Stock\Models\Enums\ProductStockActionTypeEnum;
use RedJasmine\Product\Domain\Stock\Models\ProductStock;

class ProductStockResource extends Resource
{
    protected static ?string $model = \RedJasmine\Product\Domain\Stock\Models\Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box';

    protected static ?string $cluster        = Product::class;
    protected static ?int    $navigationSort = 1;

    public static function getModelLabel() : string
    {
        return __('red-jasmine.product::product-stock.labels.product-stock');
    }


    public static function getNavigationGroup() : ?string
    {
        return __('red-jasmine.product::product-stock.labels.product-stock');
    }

    public static function table(Table $table) : Table
    {
        return $table
            ->striped()
            ->columns([
                          Tables\Columns\TextColumn::make('id')->label('ID')->sortable(),
                          Tables\Columns\TextColumn::make('owner_type')
                                                   ->label(__('red-jasmine.product::product-stock.fields.owner_type'))
                          ,
                          Tables\Columns\TextColumn::make('owner_id')->label(__('red-jasmine.product::product-stock.fields.owner_id')),
                          Tables\Columns\TextColumn::make('title')->label(__('red-jasmine.product::product-stock.fields.title')),
                          Tables\Columns\TextColumn::make('outer_id')->label(__('red-jasmine.product::product-stock.fields.outer_id')),
                          Tables\Columns\ImageColumn::make('image')->label(__('red-jasmine.product::product-stock.fields.image'))->size(40),
                          Tables\Columns\TextColumn::make('status')->label(__('red-jasmine.product::product-stock.fields.status')),
                          Tables\Columns\TextColumn::make('stock')->label(__('red-jasmine.product::product-stock.fields.stock')),
                          Tables\Columns\TextColumn::make('lock_stock')->label(__('red-jasmine.product::product-stock.fields.lock_stock')),

                      ])
            ->filters([
                          //
                      ])
            ->actions([
                          static::editStockAction()
                      ])
            ->bulkActions([
                              Tables\Actions\BulkActionGroup::make([
                                                                       Tables\Actions\DeleteBulkAction::make(),
                                                                   ]),
                          ]);
    }

    protected static function editStockAction()
    {
        return Tables\Actions\Action::make('edit')
                                    ->label(__('red-jasmine.product::product-stock.labels.edit'))
                                    ->modalWidth('7xl')
                                    ->slideOver()
                                    ->modalAutofocus(false)
                                    ->stickyModalFooter()
                                    ->form([

                                               Forms\Components\TextInput::make('id')->readOnly(),
                                               Forms\Components\TextInput::make('title')->readOnly(),
                                               Forms\Components\TextInput::make('outer_id')->readOnly(),
                                               Forms\Components\TextInput::make('image')->readOnly(),
                                               TableRepeater::make('skus')
                                                            ->headers([
                                                                          Header::make('SKU ID'),
                                                                          Header::make('properties_name'),
                                                                          Header::make('barcode'),
                                                                          Header::make('outer_id'),
                                                                          Header::make('status'),
                                                                          Header::make('stock'),
                                                                          Header::make('lock_stock'),
                                                                          Header::make('action'),
                                                                          Header::make('action_stock'),

                                                                      ])
                                                            ->schema([
                                                                         Forms\Components\Hidden::make('properties'),
                                                                         Forms\Components\TextInput::make('id')->readOnly(),
                                                                         Forms\Components\TextInput::make('properties_name')->readOnly(),
                                                                         Forms\Components\TextInput::make('barcode')->maxLength(32),
                                                                         Forms\Components\TextInput::make('outer_id')->maxLength(32),

                                                                         Forms\Components\TextInput::make('status'),

                                                                         Forms\Components\TextInput::make('stock')->maxLength(32),
                                                                         Forms\Components\TextInput::make('safety_stock')
                                                                                                   ->numeric()
                                                                                                   ->default(0),
                                                                         Forms\Components\Select::make('action_type')->required()
                                                                                                ->default(ProductStockActionTypeEnum::ADD->value)
                                                                                                ->options(ProductStockActionTypeEnum::allowActionTypes())
                                                                                                ->live(),
                                                                         Forms\Components\TextInput::make('action_stock')->default(null)->live(),

                                                                     ])->inlineLabel(false)
                                                            ->columnSpan('full')
                                                            ->streamlined()
                                                            ->reorderable(false)
                                                            ->addable(false)
                                                            ->deletable(false)
                                           ])
                                    ->fillForm(function ($record) : array {
                                        /**
                                         * @var $sku Model
                                         */

                                        $record->skus->each(function ($sku) {
                                            $sku->action_type = ProductStockActionTypeEnum::ADD;

                                        });

                                        return [
                                            'id'       => $record->id,
                                            'title'    => $record->title,
                                            'outer_id' => $record->outer_id,
                                            'image'    => $record->image,
                                            'skus'     => $record->skus
                                        ];
                                    })
                                    ->action(function (array $data) {

                                        foreach ($data['skus'] ?? [] as $index => $sku) {
                                            $data['skus'][$index]['sku_id'] = $sku['id'];
                                        }

                                        $service = app(StockCommandService::class);

                                        $service->bulk(BulkStockCommand::from($data));

                                    });
    }

    public static function form(Form $form) : Form
    {
        return $form
            ->schema([
                         //
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
            'index' => Pages\ListProductStocks::route('/'),
        ];
    }
}
