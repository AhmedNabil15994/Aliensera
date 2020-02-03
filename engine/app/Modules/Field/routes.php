<?php

/*----------------------------------------------------------
                        Category
----------------------------------------------------------*/
Route::group(['prefix' => '/category'] , function () {
    //fetching
    Route::get('/', 'FieldController@index');
	Route::get('/{id}', 'FieldController@getOne');
});