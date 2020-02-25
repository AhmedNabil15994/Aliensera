<?php

/*----------------------------------------------------------
Videos
----------------------------------------------------------*/
Route::group(['prefix' => '/videos'] , function () {
    Route::get('/{id}', 'VideoControllers@getOne');
    Route::post('/{id}/add', 'VideoControllers@addComment');
    Route::post('/{id}/view', 'VideoControllers@view');
    Route::post('/{id}/update/{comment_id}', 'VideoControllers@updateComment');
    Route::post('/{id}/remove/{comment_id}', 'VideoControllers@removeComment');
});
