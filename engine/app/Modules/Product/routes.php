<?php

/*----------------------------------------------------------
                        Products
----------------------------------------------------------*/
Route::group(['prefix' => '/products'] , function () {
    //fetching
    Route::post('/selectProduct/{id}', 'ProductController@selectProduct');
    Route::post('/confirmInvoice/{id}', 'ProductController@confirmInvoice');
});