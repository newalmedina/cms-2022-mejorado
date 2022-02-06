<?php

use Illuminate\Support\Facades\Route;

// Modulo Country
Route::group(
    [
        'namespace' => 'Clavel\Locations\Controllers',
        'middleware' => ['web']
    ],
    function () {
        Route::group(array('prefix' => 'admin', 'middleware' => ['web']), function () {
            Route::get("countries/export", 'AdminCountriesController@generateExcel');
            Route::get('countries/state/{id}', 'AdminCountriesController@setChangeState')->where('id', '[0-9]+');
            Route::post("countries/list", 'AdminCountriesController@getData');
            Route::post("countries/delete-selected", 'AdminCountriesController@destroySelected');
            Route::resource('countries', 'AdminCountriesController');
        });
    }
);


// Modulo Ccaa
Route::group(
    [
        'namespace' => 'Clavel\Locations\Controllers',
        'middleware' => ['web']
    ],
    function () {
        Route::group(array('prefix' => 'admin', 'middleware' => ['web']), function () {
            Route::get("ccaas/export", 'AdminCcaasController@generateExcel');
            Route::get('ccaas/state/{id}', 'AdminCcaasController@setChangeState')->where('id', '[0-9]+');
            Route::post("ccaas/list", 'AdminCcaasController@getData');
            Route::post("ccaas/delete-selected", 'AdminCcaasController@destroySelected');
            Route::resource('ccaas', 'AdminCcaasController');
        });
    }
);


// Modulo Province
Route::group(
    [
        'namespace' => 'Clavel\Locations\Controllers',
        'middleware' => ['web']
    ],
    function () {
        Route::group(array('prefix' => 'admin', 'middleware' => ['web']), function () {
            Route::get("provinces/export", 'AdminProvincesController@generateExcel');
            Route::get('provinces/state/{id}', 'AdminProvincesController@setChangeState')->where('id', '[0-9]+');
            Route::post("provinces/list", 'AdminProvincesController@getData');
            Route::post("provinces/delete-selected", 'AdminProvincesController@destroySelected');
            Route::resource('provinces', 'AdminProvincesController');
        });
    }
);



// Modulo City
Route::group(
    [
        'namespace' => 'Clavel\Locations\Controllers',
        'middleware' => ['web']
    ],
    function () {
        Route::group(array('prefix' => 'admin', 'middleware' => ['web']), function () {
            Route::get("cities/export", 'AdminCitiesController@generateExcel');
            Route::get('cities/state/{id}', 'AdminCitiesController@setChangeState')->where('id', '[0-9]+');
            Route::post("cities/list", 'AdminCitiesController@getData');
            Route::post("cities/delete-selected", 'AdminCitiesController@destroySelected');
            Route::resource('cities', 'AdminCitiesController');
        });
    }
);
