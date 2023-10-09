<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use PHPHtmlParser\Dom;
use PHPHtmlParser\Exceptions\ChildNotFoundException;
use PHPHtmlParser\Exceptions\CircularException;
use PHPHtmlParser\Exceptions\CurlException;
use PHPHtmlParser\Exceptions\NotLoadedException;
use PHPHtmlParser\Exceptions\StrictException;
use QL\QueryList;
use RedJasmine\Item\Models\Item;
use RedJasmine\Item\Services\Items\ItemCreateService;
use RedJasmine\Trade\Helpers\Trade;
use RedJasmine\Trade\Services\TradeCreate;
use RedJasmine\Trade\Services\Validators\TradeBaseValidator;
use RedJasmine\User\Models\User;
use Throwable;

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
        $data      = [
            'seller_type'  => 'seller',
            'seller_uid'   => '111111111111111111111111111111',
            'buyer_type'   => 'buyer',
            'buyer_uid'    => '1',
            'post_fee'     => 20,
            'discount_fee' => 100,
            'title'=>'阿达撒打算啊实打实大撒大所大所多阿萨',
            'items'        => [
                [
                    'iid'   => 1,
                    'num'   => 100,
                    'price' => 3,
                    'title' => '标题',
                ],
                [
                    'iid'   => 1,
                    'num'   => 10000,
                    'price' => 20,
                    'title' => '标题',
                ],
                [
                    'iid'   => 1,
                    'num'   => 100,
                    'price' => 10,
                    'title' => '标题',
                ],
            ],
        ];
        $tradeData = new Trade($data);

        $tradeCreateService = app(TradeCreate::class)->init($tradeData);
        $tradeCreateService->getValidator()->addValidator(new TradeBaseValidator());
        $tradeCreateService->validate();
        $trade = $tradeCreateService->save();
        dd($trade);

    }

    public function handle() : void
    {

        $this->tradeHandle();
        return;


        $cursor = DB::table('regions')->cursor();


        foreach ($cursor as $region) {
            try {
                DB::table('regions')->where('id', $region->id)->update(
                    [
                        'id'        => str_pad($region->id, 6, '0'),
                        'parent_id' => str_pad($region->parent_id, 6, '0'),
                    ]
                );
            } catch (\Throwable $throwable) {

            }

        }


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
                             } catch (\Throwable $throwable) {
                                 $item_props = [];
                             }
                             $item_props_text = '';
                             if (filled($item_props)) {
                                 $item_props_text = self::propsToString($item_props);
                             }
                             try {
                                 $sku_props = json_decode($item->sku_props, true);
                             } catch (\Throwable $throwable) {
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
                             } catch (\Throwable $throwable) {
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
