<?php

/*----------------------------------------------------------
                        Faculty
----------------------------------------------------------*/
Route::group(['prefix' => '/faculty'] , function () {
    //fetching
    Route::get('/', 'FacultyController@index');
	Route::get('/{id}', 'FacultyController@getOne');
});