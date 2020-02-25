<?php

/*----------------------------------------------------------
                        InstructorRate
----------------------------------------------------------*/
Route::group(['prefix' => '/instructorrate'] , function () {
    //inserting
    Route::post('/add', 'InstructorRateController@add');
    Route::post('/update/{id}', 'InstructorRateController@update');
    Route::post('/remove/{id}', 'InstructorRateController@delete');
});