<?php

/*----------------------------------------------------------
						Chat
----------------------------------------------------------*/
Route::group(['prefix' => '/messages'] , function () {
    Route::get('/', 'ChatControllers@index');
    Route::get('/{id}', 'ChatControllers@getOne');
    Route::get('/with/{id}', 'ChatControllers@chatWith');
    Route::post('/{id}/newMessage', 'ChatControllers@newMessage');
	Route::post('/uploadAttachment', 'ChatControllers@uploadAttachment');
});