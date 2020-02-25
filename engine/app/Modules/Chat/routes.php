<?php

/*----------------------------------------------------------
						Chat
----------------------------------------------------------*/
Route::group(['prefix' => '/messages'] , function () {
    Route::get('/', 'ChatControllers@index');
    Route::get('/{id}', 'ChatControllers@getOne');
    Route::post('/newMessage/{id}', 'ChatControllers@newMessage');
	Route::post('/uploadAttachment/{id}', 'ChatControllers@uploadAttachment');
});