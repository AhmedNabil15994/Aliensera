<?php

/*----------------------------------------------------------
Groups
----------------------------------------------------------*/
Route::group(['prefix' => '/groups'] , function () {
    Route::get('/', 'GroupsControllers@index');
    Route::get('/edit/{id}', 'GroupsControllers@edit');
    Route::post('update/{id}', 'GroupsControllers@update');
    Route::get('add', 'GroupsControllers@add');
    Route::post('create', 'GroupsControllers@create');
    Route::get('delete/{id}', 'GroupsControllers@delete');

});
