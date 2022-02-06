<?php

use Illuminate\Support\Facades\Route;

// Module Crud Generator
Route::group(array('namespace' => 'Clavel\CrudGenerator\Controllers', 'middleware' => ['web']), function () {
    Route::group(array('prefix' => 'admin', "as" => "admin.", 'middleware' => ['web']), function () {
        Route::get('crud-generator/state/{id}', 'CrudGeneratorController@setChangeState')->where('id', '[0-9]+');
        Route::post('crud-generator/generate', 'CrudGeneratorController@generate');
        Route::get('crud-generator/clean/{id}', 'CrudGeneratorController@clean')->where('id', '[0-9]+');
        Route::post("crud-generator/list", 'CrudGeneratorController@getData');
        Route::get('crud-generator/{module_id}/export', 'CrudGeneratorController@export')->where('module_id', '[0-9]+');
        Route::post('crud-generator/import', 'CrudGeneratorController@import');
        Route::resource('crud-generator', 'CrudGeneratorController');

        Route::post("crud-generator/{module_id}/fields/list", 'CrudGeneratorFieldsController@getData')
            ->where('module_id', '[0-9]+');
        Route::post('crud-generator/fields/model', 'CrudGeneratorFieldsController@getModelFields');
        Route::post('crud-generator/{module_id}/fields/createfull', 'CrudGeneratorFieldsController@createFull');

        Route::get('crud-generator/{module_id}/fields/grid', 'CrudGeneratorFieldsController@gridOrdering')->where('module_id', '[0-9]+');
        Route::post('crud-generator/{module_id}/fields/grid', 'CrudGeneratorFieldsController@gridOrderingStore')
            ->where('module_id', '[0-9]+')
            ->name('crud-generator.fields.grid');

        Route::get('crud-generator/{module_id}/fields/table', 'CrudGeneratorFieldsController@tableOrdering')->where('module_id', '[0-9]+');
        Route::post('crud-generator/{module_id}/fields/table', 'CrudGeneratorFieldsController@tableOrderingStore')
            ->where('module_id', '[0-9]+')
            ->name('crud-generator.fields.table');


        Route::resource('crud-generator.fields', 'CrudGeneratorFieldsController');
    });
});
