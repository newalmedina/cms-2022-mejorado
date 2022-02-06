<?php

use Illuminate\Support\Facades\Route;

Route::group(
    [
        'prefix' => '/api/v1',
        'namespace' => 'App\Modules\Centers\Api\v1',
        'as' => 'api.',
        'middleware' => ['auth:api']
    ],
    function () {
        Route::get('centers/list', 'CentersApiController@list')->name('centers.list');
        Route::resource('centers', 'CentersApiController', ['except' => ['create', 'edit']]);
    }
);
