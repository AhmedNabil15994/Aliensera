<?php

/*----------------------------------------------------------
Courses
----------------------------------------------------------*/
Route::group(['prefix' => '/courses'] , function () {
    Route::get('/', 'CourseControllers@index');
    Route::get('/{id}', 'CourseControllers@getOne');
   
});
