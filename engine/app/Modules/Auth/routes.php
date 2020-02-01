<?php

/*----------------------------------------------------------
User Auth
----------------------------------------------------------*/
Route::group(['prefix' => '/'] , function () {
    Route::post('login', 'AuthController@login');
    Route::post('send-code', 'AuthController@sendCode');
    Route::post('check-code', 'AuthController@checkCode');
    Route::post('register', 'AuthController@register');

    Route::post('get-code', 'AuthController@getCode');
    Route::post('check-email-code', 'AuthController@checkEmailCode');
    Route::post('reset-password', 'AuthController@doResetPassword');
});
