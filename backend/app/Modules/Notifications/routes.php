<?php

/*----------------------------------------------------------
Notifications
----------------------------------------------------------*/
Route::group(['prefix' => '/notifications'] , function () {
    Route::get('/', 'NotificationsControllers@index');
    Route::post('create', 'NotificationsControllers@create');

});
