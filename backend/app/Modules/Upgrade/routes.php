<?php

/*----------------------------------------------------------
Upgrade Requests
----------------------------------------------------------*/
Route::group(['prefix' => '/upgrade'] , function () {
    Route::get('/', 'UpgradeControllers@index');
});