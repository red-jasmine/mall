<?php

use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'admin'],function (){
    \RedJasmine\Product\UI\Http\Admin\ProductAdminRoute::api();

});
