<?php

/*----------------------------------------------------------
Account
----------------------------------------------------------*/
Route::group(['prefix' => '/accounts'] , function () {
    Route::get('/', 'AccountControllers@index');
    Route::get('/edit/{id}', 'AccountControllers@edit');
    Route::post('update/{id}', 'AccountControllers@update');
    Route::get('add', 'AccountControllers@add');
    Route::post('create', 'AccountControllers@create');
    Route::get('delete/{id}', 'AccountControllers@delete');

});
