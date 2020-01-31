<?php

/*----------------------------------------------------------
Lessons
----------------------------------------------------------*/
Route::group(['prefix' => '/requests'] , function () {
    Route::get('/', 'RequestControllers@index');
    Route::get('/update/{id}/{status}', 'RequestControllers@update');
    Route::get('/delete/{id}', 'RequestControllers@delete');
});
