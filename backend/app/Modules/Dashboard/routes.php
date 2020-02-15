<?php

/*----------------------------------------------------------
Dashboard
----------------------------------------------------------*/
Route::group(['prefix' => '/'] , function () {
    Route::get('/', 'DashboardControllers@Dashboard');
    Route::post('/getChartData', 'DashboardControllers@getChartData');
	Route::post('/language', 'DashboardControllers@changeLang');
});