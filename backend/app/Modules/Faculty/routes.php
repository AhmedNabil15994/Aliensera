<?php

/*----------------------------------------------------------
Faculties
----------------------------------------------------------*/
Route::group(['prefix' => '/faculties'] , function () {
    Route::get('/', 'FacultyControllers@index');
    Route::get('/edit/{id}', 'FacultyControllers@edit');
    Route::post('update/{id}', 'FacultyControllers@update');
    Route::get('add', 'FacultyControllers@add');
    Route::post('create', 'FacultyControllers@create');
    Route::get('delete/{id}', 'FacultyControllers@delete');

});
