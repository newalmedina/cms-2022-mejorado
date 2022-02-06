<?php

use Illuminate\Support\Facades\Route;


Route::group(
    [
        'prefix' => '/api/v1',
        'namespace' => 'App\Modules\Products\Api\v1',
        'as' => 'api.',
        'middleware' => ['auth:api']
    ],
    function () {
        Route::get('products/list', 'ProductsApiController@list')->name('products.list');
        Route::resource('products', 'ProductsApiController', ['except' => ['create', 'edit']]);
    }
);
