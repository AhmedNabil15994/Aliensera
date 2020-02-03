<?php

/*----------------------------------------------------------
                        Feedbacks
----------------------------------------------------------*/
Route::group(['prefix' => '/feedback'] , function () {
    //fetching
    Route::get('/', 'FeedbackController@index');

    //inserting
    Route::post('/add', 'FeedbackController@add');
});