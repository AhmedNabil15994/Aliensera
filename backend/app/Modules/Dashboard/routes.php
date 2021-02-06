<?php

/*----------------------------------------------------------
Dashboard
----------------------------------------------------------*/
Route::group(['prefix' => '/'] , function () {
    Route::get('/', 'DashboardControllers@Dashboard');
    Route::post('/getChartData', 'DashboardControllers@getChartData');
	Route::post('/language', 'DashboardControllers@changeLang');
	Route::get('/stats', 'DashboardControllers@stats');
	Route::get('/downloadStats/{course}', 'DashboardControllers@downloadStats');
	Route::get('/stats/{course}/sendNotification', 'DashboardControllers@sendNotification');
	Route::post('/stats/{course}/sendNotification', 'DashboardControllers@postSendNotification');
});