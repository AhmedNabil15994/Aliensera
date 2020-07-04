<?php

/*----------------------------------------------------------
User Auth
----------------------------------------------------------*/
Route::group(['prefix' => '/'] , function () {
    Route::get('login', 'AuthControllers@login');
    Route::post('login', 'AuthControllers@doLogin');
    Route::post('register', 'AuthControllers@register');
    Route::get('logout', 'AuthControllers@logout');
});