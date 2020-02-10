<?php

/*----------------------------------------------------------
Pages
----------------------------------------------------------*/
Route::group(['prefix' => '/pages'] , function () {
    Route::get('/', 'PagesControllers@index');
    Route::get('/edit/{id}', 'PagesControllers@edit');
    Route::post('update/{id}', 'PagesControllers@update');
    Route::get('add', 'PagesControllers@add');
    Route::post('create', 'PagesControllers@create');
    Route::get('delete/{id}', 'PagesControllers@delete');

});
