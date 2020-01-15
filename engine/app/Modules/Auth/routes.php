<?php

/*----------------------------------------------------------
User Auth
----------------------------------------------------------*/
Route::group(['prefix' => '/'] , function () {
    Route::post('login', 'AuthController@login');
    Route::post('register', 'AuthController@register');
});
