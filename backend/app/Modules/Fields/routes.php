<?php

/*----------------------------------------------------------
Fields
----------------------------------------------------------*/
Route::group(['prefix' => '/fields'] , function () {
    Route::get('/', 'FieldControllers@index');
    Route::get('/edit/{id}', 'FieldControllers@edit');
    Route::post('update/{id}', 'FieldControllers@update');
    Route::get('add', 'FieldControllers@add');
    Route::post('create', 'FieldControllers@create');
    Route::get('delete/{id}', 'FieldControllers@delete');

});
