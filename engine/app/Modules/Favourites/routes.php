<?php

/*----------------------------------------------------------
                        Favourites
----------------------------------------------------------*/
Route::group(['prefix' => '/favourites'] , function () {
    //fetching
    Route::get('/', 'FavouritesController@index');

    //inserting
    Route::post('/add', 'FavouritesController@add');
});