<?php

/*----------------------------------------------------------
Variables
----------------------------------------------------------*/
Route::group(['prefix' => '/variables'] , function () {
    Route::get('/', 'VariablesControllers@index');
    Route::get('edit/{id}', 'VariablesControllers@edit');
    Route::post('update/{id}', 'VariablesControllers@update');
    Route::get('add', 'VariablesControllers@add');
    Route::post('create', 'VariablesControllers@create');
    Route::get('delete/{id}', 'VariablesControllers@delete');
});
