<?php

/*----------------------------------------------------------
Pages
----------------------------------------------------------*/
Route::group(['prefix' => '/pages'] , function () {
    Route::get('/{id}', 'PagesControllers@index');
});
