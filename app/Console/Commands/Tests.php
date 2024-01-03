<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use PHPHtmlParser\Dom;
use PHPHtmlParser\Exceptions\ChildNotFoundException;
use PHPHtmlParser\Exceptions\CircularException;
use PHPHtmlParser\Exceptions\CurlException;
use PHPHtmlParser\Exceptions\NotLoadedException;
use PHPHtmlParser\Exceptions\StrictException;
use QL\QueryList;
use RedJasmine\Address\Address;
use RedJasmine\Address\Models\Address as AddressModel;
use RedJasmine\Item\Models\Item;
use RedJasmine\Item\Services\Items\ItemCreateService;
use RedJasmine\Order\Enums\Orders\OrderStatusEnum;
use RedJasmine\Order\Enums\Orders\OrderTypeEnum;
use RedJasmine\Order\Enums\Orders\PaymentStatusEnum;
use RedJasmine\Order\Enums\Orders\ShippingTypeEnum;
use RedJasmine\Order\Models\OrderAddress;
use RedJasmine\Order\Models\OrderProduct;
use RedJasmine\Order\OrderService;
use RedJasmine\Order\Services\Orders\Pipelines\Products\ProductCategoryApplying;
use RedJasmine\Order\ValueObjects\OrderProductObject;
use RedJasmine\Product\Enums\Category\CategoryStatusEnum;
use RedJasmine\Product\Enums\Product\ProductTypeEnum;
use RedJasmine\Product\Enums\Stock\ProductStockChangeTypeEnum;
use RedJasmine\Product\Services\Category\ProductCategoryService;
use RedJasmine\Product\Services\Product\ProductService;
use RedJasmine\Product\Services\Product\ProductStock;
use RedJasmine\Product\Services\Product\Stock\StockChannelObject;
use RedJasmine\Product\Services\Product\Stock\StockChanneObject;
use RedJasmine\Support\Helpers\UserObjectBuilder;
use RedJasmine\Support\Services\SystemUser;
use RedJasmine\Trade\Helpers\Trade;
use RedJasmine\Trade\Services\TradeCreate;
use RedJasmine\Trade\Services\Validators\TradeBaseValidator;
use RedJasmine\User\Models\User;
use Spatie\Browsershot\Browsershot;
use Throwable;
use function app;

class Tests extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tests';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';


    public function tradeHandle()
    {


    }

    public function handle()
    {
        $service = app(OrderService::class);
        $service->setOperator(new SystemUser());


        $product2 = [
            'shipping_type'     => 'CDK',
            'product_type'      => 'system',
            'product_id'        => 2,
            'sku_id'            => 0,
            'price'             => '20',
            'num'               => 2,
            'title'             => 'B',
            'image'             => '',
            'cost_price'        => '4',
            'discount_amount'   => '4',
            'tools'             => [
                'form' => [ 'a' => 1 ]
            ],
            'DiscountBreakdown' => [
                [ 'name' => '满20减10' ],
            ],
            //'category_id'       => 377942835138326
            'info'              => [
                'seller_remarks' => '卖家备注'
            ],

        ];


        $creator = $service->creator();
        $product = [
            'shipping_type'   => 'CDK',
            'product_type'    => 'system',
            'product_id'      => 1,
            'sku_id'          => 0,
            'price'           => '120',
            'num'             => 1,
            'title'           => 'A',
            'image'           => '',
            'cost_price'      => '18',
            'tax_amount'      => '12',
            'discount_amount' => '12',
        ];

        $address = AddressModel::find(1);

        $order = [
            'order_type'      => OrderTypeEnum::MALL->value,
            'shipping_type'   => ShippingTypeEnum::VIRTUAL->value,
            'order_status'    => OrderStatusEnum::WAIT_BUYER_PAY->value,
            'payment_status'  => PaymentStatusEnum::WAIT_PAY->value,
            'shipping_status' => null,
            'refund_status'   => null,
            'rate_status'     => null,
            'freight_amount'  => 0,
            'discount_amount' => 0,
            'client_type'     => 'Console',
            'client_ip'       => request()->getClientIp(),
            'channel_type'    => null,
            'channel_id'      => null,
            'store_type'      => null,
            'store_id'        => null,
            'guide_type'      => null,
            'guide_id'        => null,
            'email'           => null,
            'password'        => null,
            'info'            => [
                'seller_remarks' => '订单卖家备注-买家不可见',
                'seller_message' => '订单卖家留言-买家可见',
                'buyer_remarks'  => '订单买家备注-卖家不可见',
                'buyer_message'  => '订单买家留言-卖家可见',
                'seller_extends' => '{"json":"1"}',
                'other_extends'  => null,
            ],
            'address'         => $address->toArray(),
        ];

        $productModel  = OrderProduct::makeParameters($product);
        $product2Model = OrderProduct::makeParameters($product2);

        $creator->setSeller(new SystemUser());
        $creator->setBuyer(User::find(383142919024923));
        // 设置订单参数
        $creator->setOrderParameters($order);

        $creator->addProduct($productModel);
        $creator->addProduct($product2Model);


        // 添加应用管道
        // /$creator->addInitPipelines();

        dd($creator->create()->toArray());

    }

    public function handle33()
    {


        $service = app(ProductService::class);
        $service->setOperator(new UserObjectBuilder([ 'type' => 'admin', 'uid' => 0 ]));

        $changeStock = rand(-100, 100);
        $changeStock = 200;
        // $skuID        = 380060972386593;
        $skuID        = 382551881693164;
        $stockService = $service->stock();

        $channel1 = new StockChannelObject('activity', 1);
        $channel2 = new StockChannelObject('activity', 2);
        //$stockService->channel()->create($skuID,3,$channel1);
        // $stockService->lock($skuID,120,ProductStockChangeTypeEnum::SALE);
        //$stockService->checklock($skuID,1,ProductStockChangeTypeEnum::SALE,$channel1);
        //$stockService->checkLock($skuID,1,ProductStockChangeTypeEnum::SALE);
        //$stockService->setStock($skuID, 58, ProductStockChangeTypeEnum::SELLER);
        //$stockService->channel()->create($skuID,10,$channel1);
        //$stockService->channel()->create($skuID,10,$channel2);
        //$stockService->channel()->add($skuID,10,$channel2);
        $stockService->sub($skuID, 1, ProductStockChangeTypeEnum::SALE, null, true);
        dd();
        // 对逻辑库存操作
        // $stockService->sub($skuID, 2, ProductStockChangeTypeEnum::SALE, $channel1);
        // $stockService->sub($skuID, 2, ProductStockChangeTypeEnum::SALE, $channel1, true);
        // $stockService->unlock($skuID, 1, ProductStockChangeTypeEnum::SALE, $channel1);
        // dd();
        //$stockService->sub($skuID, 2, ProductStockChangeTypeEnum::SALE, $channel2);
        //$stockService->sub($skuID, 2, ProductStockChangeTypeEnum::SALE, $channel2, true);
        //$stockService->unlock($skuID, 1, ProductStockChangeTypeEnum::SALE, $channel2);

        // 对 实际库存操作
        //$stockService->sub($skuID, 2, ProductStockChangeTypeEnum::SALE);
        //$stockService->sub($skuID, 2, ProductStockChangeTypeEnum::SALE, null, true);
        //$stockService->unlock($skuID, 1, ProductStockChangeTypeEnum::SALE);
        //$stockService->sub($skuID, 72, ProductStockChangeTypeEnum::SELLER);
        //$stockService->add($skuID, 72, ProductStockChangeTypeEnum::SALE);
        dd(1);
        //$stockService->changeStock(ProductStockChangeTypeEnum::SELLER, $skuID, $changeStock);
        //$channelStock = $stockService->channel()->create($skuID,10,$channel);

        //$result = $stockService->setStock(ProductStockChangeTypeEnum::SELLER, $skuID, 888);
        // $result = $stockService->add(ProductStockChangeTypeEnum::SELLER, $skuID, rand(1,10));

        dd();

        $result = $stockService->unlock($skuID, 2, ProductStockChangeTypeEnum::SALE);
        // $result = $stockService->unlock($skuID, 1, ProductStockChangeTypeEnum::SALE);
        dd($result);

        return $this->testCategory();

        $product = [
            'product_type'       => ProductTypeEnum::GOODS->value,
            'shipping_type'      => ShippingTypeEnum::LOGISTICS,
            'title'              => '商品名称',
            'has_skus'           => 0,
            'image'              => 'https://gw.alicdn.com/bao/uploaded/i4/125105796/O1CN01fj8CxM1sgcTuyWZ52_!!0-saturn_solar.jpg_300x300q90.jpg_.webp',
            'category_id'        => null,
            'seller_category_id' => null,
            'brand_id'           => null,
            'postage_id'         => null,
            'keywords'           => '测试 好后 萨达撒',


            'freight_payer' => 0,
            'sub_stock'     => 0,
            'delivery_time' => 0,
            'quantity'      => ProductService::MAX_QUANTITY,
            'sold_quantity' => 0,
            'vip'           => 0,
            'points'        => 0,
            'status'        => 0,

            'barcode'      => '55876464',
            'outer_id'     => 'ES-844',
            'price'        => '1',
            'market_price' => '1',
            'cost_price'   => '1',
            'min'          => 0,
            'max'          => 0,
            'multiple'     => 1,

            'info' => [
                'desc'        => '',
                'web_detail'  => '',
                'wap_detail'  => '',
                'images'      => [],
                'videos'      => [],
                'weight'      => '',
                'width'       => '',
                'height'      => '',
                'length'      => '',
                'size'        => '',
                'basic_props' => [],
                'sku_props'   => [],
                'remarks'     => '',
                'extends'     => [],
            ],
            'skus' => [

                [
                    'properties' => '',//销售属性
                ],

            ],


        ];


    }

    public function testCategory()
    {

        $service = app(ProductCategoryService::class);
        $service->setOperator(new SystemUser());

        $attributes      = [
            'parent_id'  => 0,
            'name'       => '测试',
            'group_name' => '',
            'sort'       => 1,
            'is_leaf'    => 0,
            'status'     => CategoryStatusEnum::ENABLE,
            'extends'    => [],
        ];
        $ProductCategory = $service->create($attributes);
        dd($ProductCategory);


    }

    /**
     * Execute the console command.
     */
    public function handle2() : void
    {
        $creator = $owner = User::find(1);
        $service = app(ItemCreateService::class);
        $result  = DB::connection('bbc')->table('item')
                     ->where('type', 1)
//                    ->where('is_skus',1)
//                     ->where('item.iid',10443654938)
                     ->join('item_info', 'item.iid', '=', 'item_info.iid')
                     ->orderBy('item.iid')
                     ->chunk(100, function ($items) use ($creator, $owner, $service) {


                         foreach ($items as $item) {


                             try {
                                 $images = json_decode($item->images, true);
                             } catch (Throwable $throwable) {
                                 $images = [];
                             }
                             // 构件商品属性
                             try {
                                 $item_props = json_decode($item->item_props, true);
                             } catch (Throwable $throwable) {
                                 $item_props = [];
                             }
                             $item_props_text = '';
                             if (filled($item_props)) {
                                 $item_props_text = self::propsToString($item_props);
                             }
                             try {
                                 $sku_props = json_decode($item->sku_props, true);
                             } catch (Throwable $throwable) {
                                 $sku_props = [];
                             }
                             $sku_props_text = '';
                             if (filled($sku_props)) {
                                 $sku_props_text = self::propsToString($sku_props);
                             }


                             $itemInputs = [
                                 'title'          => $item->title,
                                 'cid'            => $item->cid,// 类目
                                 'price'          => bcadd($item->price, 0, 2), // 价格
                                 //'market_price' => '', // 市场价
                                 //'cost_price'   => '', // 成本价
                                 'price_type'     => 1, // 价格类型
                                 'quantity'       => 10000, // 价格类型
                                 'is_skus_parent' => (int)$item->is_skus, // 是否为SKU
                                 'web_detail'     => $item->web_detail,
                                 'wap_detail'     => $item->wap_detail,
                                 'image'          => $item->image,
                                 'images'         => $images,
                                 'item_props'     => $item_props_text,
                                 'sku_props'      => $sku_props_text,
                                 'skus'           => [],
                             ];


                             if ($itemInputs['is_skus_parent'] === 1 && filled($sku_props_text)) {
                                 // 查询SKU
                                 $skus = DB::connection('bbc')->table('item_sku')
                                           ->select([ 'iid', 'sku_id', 'price', 'properties', 'image' ])
                                           ->where('iid', $item->iid)
                                           ->where('status', 1)
                                           ->get();

                                 $newSkuList = [];
                                 foreach ($skus as $sku) {
                                     $newSkuList[] = [
                                         'image' => $sku->image,
                                         'price' => bcadd($sku->price, 0, 2),
                                         'props' => $sku->properties,
                                     ];
                                 }
                                 $itemInputs['skus'] = $newSkuList;
                             }


                             try {
                                 $service->create($itemInputs, $owner, $creator);
                             } catch (Throwable $throwable) {
                                 dump($item->iid . ';创建失败' . $throwable->getMessage());
//                                    dump($itemInputs);
//                                 throw $throwable;
                                 continue;
                             }
                             dump('创建成功');


                         }

                     },      'iid', true);
        dd($result);
    }


    public static function propsToString(array $props) : string
    {
        $propTexts = [];
        foreach ($props as $prop) {
            $values = [];
            foreach ($prop['values'] as $value) {
                $values[] = $value['vid'];
            }
            $propTexts[] = $prop['pid'] . ':' . implode(',', $values);
        }
        return implode(';', $propTexts);
    }
}
