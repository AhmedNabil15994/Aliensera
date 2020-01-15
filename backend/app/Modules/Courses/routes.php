<?php

/*----------------------------------------------------------
Courses
----------------------------------------------------------*/
Route::group(['prefix' => '/courses'] , function () {
    Route::get('/', 'CoursesControllers@index');
    Route::get('/edit/{id}', 'CoursesControllers@edit');
    Route::get('/view/{id}', 'CoursesControllers@view');
    Route::post('update/{id}', 'CoursesControllers@update');
    Route::get('add', 'CoursesControllers@add');
    Route::post('create', 'CoursesControllers@create');
    Route::get('delete/{id}', 'CoursesControllers@delete');
    Route::get('deleteReview/{id}', 'CoursesControllers@deleteReview');
    Route::get('restore/{id}', 'CoursesControllers@restore');
    Route::get('/images/delete/{id}', 'CoursesControllers@imageDelete');

    Route::get('getUniversities', 'CoursesControllers@getUniversities');
    Route::get('getFaculties/{university_id}', 'CoursesControllers@getFaculties');
});
