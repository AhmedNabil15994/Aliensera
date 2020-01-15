<?php

/*----------------------------------------------------------
Users
----------------------------------------------------------*/
Route::group(['prefix' => '/users'] , function () {
    Route::get('/', 'UsersControllers@index');
    Route::get('edit/{id}', 'UsersControllers@edit');
    Route::get('view/{id}', 'UsersControllers@view');
    Route::post('update/{id}', 'UsersControllers@update');
    Route::get('add', 'UsersControllers@add');
    Route::post('create', 'UsersControllers@create');
    Route::get('delete/{id}', 'UsersControllers@delete');
    Route::get('restore/{id}', 'UsersControllers@restore');
});

//Users Online
Route::get('usersOnlineCount/{group}', 'UsersControllers@getCountUsersOnline');
Route::get('usersOnline/{group}', 'UsersControllers@getUsersOnline');

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
