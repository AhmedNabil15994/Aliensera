<?php

/*----------------------------------------------------------
                        University
----------------------------------------------------------*/
Route::group(['prefix' => '/university'] , function () {
    //fetching
    Route::get('/', 'UniversityController@index');
	Route::get('/{id}', 'UniversityController@getOne');
});