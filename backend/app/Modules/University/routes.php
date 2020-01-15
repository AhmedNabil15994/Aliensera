<?php

/*----------------------------------------------------------
Universities
----------------------------------------------------------*/
Route::group(['prefix' => '/universities'] , function () {
    Route::get('/', 'UniversityControllers@index');
    Route::get('/edit/{id}', 'UniversityControllers@edit');
    Route::post('update/{id}', 'UniversityControllers@update');
    Route::get('add', 'UniversityControllers@add');
    Route::post('create', 'UniversityControllers@create');
    Route::get('delete/{id}', 'UniversityControllers@delete');

});
