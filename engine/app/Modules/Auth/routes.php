<?php

/*----------------------------------------------------------
User Auth
----------------------------------------------------------*/
Route::group(['prefix' => '/'] , function () {
    Route::post('login', 'AuthController@login');
    Route::get('getHome', 'HomeControllers@getHomeIOS');
    Route::post('social', 'AuthController@social');
    Route::post('register', 'AuthController@register');
    Route::get('certificates/{id}/downloadCertificate', 'CertificateControllers@download');

    Route::post('get-code', 'AuthController@getCode');
    Route::post('check-email-code', 'AuthController@checkEmailCode');
    Route::post('reset-password', 'AuthController@doResetPassword');
});
