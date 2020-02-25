<?php

/*----------------------------------------------------------
                        Feedbacks
----------------------------------------------------------*/
Route::group(['prefix' => '/feedback'] , function () {
    //fetching
    Route::get('/', 'FeedbackController@index');

    //inserting
    Route::post('/add', 'FeedbackController@add');
    Route::post('/update/{id}', 'FeedbackController@update');
    Route::post('/remove/{id}', 'FeedbackController@delete');
});