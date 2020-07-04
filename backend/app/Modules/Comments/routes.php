<?php

/*----------------------------------------------------------
comments
----------------------------------------------------------*/
Route::group(['prefix' => '/comments'] , function () {
    Route::get('/', 'CommentControllers@index');
});
