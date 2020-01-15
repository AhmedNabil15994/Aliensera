<?php

/*----------------------------------------------------------
Dashboard
----------------------------------------------------------*/
Route::group(['prefix' => '/'] , function () {
    Route::get('/', 'DashboardControllers@Dashboard');
	Route::post('/language', 'DashboardControllers@changeLang');
});