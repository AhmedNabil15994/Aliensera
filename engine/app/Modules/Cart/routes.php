<?php

/*----------------------------------------------------------
                        Carts
----------------------------------------------------------*/
Route::group(['prefix' => '/carts'] , function () {
    //fetching
    Route::get('/', 'CartController@index');

    //inserting
    Route::post('/add', 'CartController@add');
    Route::post('/confirmPayment', 'CartController@confirmPayment');
});