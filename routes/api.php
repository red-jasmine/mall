<?php

use Illuminate\Support\Facades\Route;


Route::group([ 'prefix' => 'admin' ], function () {
    \RedJasmine\Product\UI\Http\Admin\ProductAdminRoute::api();

});


Route::group([ 'prefix' => 'buyer' ], function () {
    \RedJasmine\Product\UI\Http\Buyer\ProductBuyerRoute::api();

    \RedJasmine\Shopping\UI\Http\Buyer\ShopingBuyerRoute::api();




});


Route::group([ 'prefix' => 'seller' ], function () {
    \RedJasmine\Product\UI\Http\Seller\ProductSellerRoute::api();

    \RedJasmine\Card\UI\Http\Owner\CardOwnerRoute::api();

});
