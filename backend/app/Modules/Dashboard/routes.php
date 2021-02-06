<?php

/*----------------------------------------------------------
Dashboard
----------------------------------------------------------*/
Route::group(['prefix' => '/'] , function () {
    Route::get('/', 'DashboardControllers@Dashboard');
    Route::post('/getChartData', 'DashboardControllers@getChartData');
	Route::post('/language', 'DashboardControllers@changeLang');
	Route::get('/stats', 'DashboardControllers@stats');
	Route::get('/downloadStats/{university}/{faculty}/{year}/{course}', 'DashboardControllers@downloadStats');
	Route::get('/stats/{university}/{faculty}/{year}/{course}/sendNotification', 'DashboardControllers@sendNotification');
	Route::post('/stats/{university}/{faculty}/{year}/{course}/sendNotification', 'DashboardControllers@postSendNotification');
});