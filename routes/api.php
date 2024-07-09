<?php

use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'admin'],function (){
   \RedJasmine\Product\UI\Http\Admin\ProductAdminRoute::api();

});


Route::group(['prefix' => 'buyer'],function (){
    \RedJasmine\Product\UI\Http\Buyer\ProductBuyerRoute::api();

});
