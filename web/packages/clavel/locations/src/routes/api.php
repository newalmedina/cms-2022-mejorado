<?php

use Illuminate\Support\Facades\Route;

// Modulo Country
Route::group(
    [
        'prefix' => '/api/v1',
        'namespace' => 'Clavel\Locations\Api\v1',
        'as' => 'api.'
    ],
    function () {
        Route::get('countries', 'CountriesApiController@index')->name('countries.index');
        Route::get('countries/list', 'CountriesApiController@list')->name('countries.list');

        Route::group(['middleware' => 'auth:sanctum'], function () {
            Route::resource('countries', 'CountriesApiController', ['except' => ['index', 'create', 'edit']]);
        });
    }
);

// Modulo Ccaa
Route::group(
    [
        'prefix' => '/api/v1',
        'namespace' => 'Clavel\Locations\Api\v1',
        'as' => 'api.'
    ],
    function () {
        Route::get('country/{country}/ccaas', 'CcaasApiController@index')->name('ccaas.index')->where('country', '[0-9]+');
        Route::get('country/{country}/ccaas/list', 'CcaasApiController@list')->name('ccaas.list')->where('country', '[0-9]+');

        Route::group(['middleware' => 'auth:sanctum'], function () {
            Route::apiResource('country.ccaas', 'CcaasApiController', ['except' => ['index']])->shallow();
        });
    }
);

// Modulo Province
Route::group(
    [
        'prefix' => '/api/v1',
        'namespace' => 'Clavel\Locations\Api\v1',
        'as' => 'api.'
    ],
    function () {
        Route::get('country/{country}/provinces', 'ProvincesApiController@index')->name('provinces.index')->where('country', '[0-9]+');
        Route::get('country/{country}/provinces/list', 'ProvincesApiController@list')->name('provinces.list')->where('country', '[0-9]+');

        Route::group(['middleware' => 'auth:sanctum'], function () {
            Route::apiResource('country.provinces', 'ProvincesApiController', ['except' => ['index']])->shallow();
        });
    }
);

// Modulo City
Route::group(
    [
        'prefix' => '/api/v1',
        'namespace' => 'Clavel\Locations\Api\v1',
        'as' => 'api.'
    ],
    function () {
        Route::get('province/{province}/cities', 'CitiesApiController@index')->name('cities.index')->where('province', '[0-9]+');
        Route::get('province/{province}/cities/list', 'CitiesApiController@list')->name('cities.list')->where('province', '[0-9]+');

        Route::group(['middleware' => 'auth:sanctum'], function () {
            Route::apiResource('country.cities', 'CitiesApiController', ['except' => ['index']])->shallow();
        });
    }
);
