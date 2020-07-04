<?php

/*----------------------------------------------------------
Courses
----------------------------------------------------------*/
Route::group(['prefix' => '/courses'] , function () {
    Route::get('/', 'CoursesControllers@index');
    Route::get('/edit/{id}', 'CoursesControllers@edit');
    Route::get('/view/{id}', 'CoursesControllers@view');
    Route::get('/upgrade/{id}/{status}', 'CoursesControllers@upgrade');
    Route::get('/discussion/{id}', 'CoursesControllers@discussion');
    Route::post('/discussion/{id}/addComment', 'CoursesControllers@addDiscussion');
    Route::post('/removeDiscussion/{id}', 'CoursesControllers@removeDiscussion');
    Route::post('/view/{id}/sortLesson', 'CoursesControllers@sortLesson');
    Route::post('/view/{id}/sortVideo', 'CoursesControllers@sortVideo');
    Route::get('/view/{id}/movableLessons', 'CoursesControllers@movableLessons');
    Route::post('/view/{id}/moveVideo', 'CoursesControllers@moveVideo');
    Route::post('update/{id}', 'CoursesControllers@update');
    Route::get('add', 'CoursesControllers@add');
    Route::post('create', 'CoursesControllers@create');
    Route::get('delete/{id}', 'CoursesControllers@delete');
    Route::get('deleteReview/{id}', 'CoursesControllers@deleteReview');
    Route::get('deleteRate/{id}', 'CoursesControllers@deleteRate');
    Route::get('restore/{id}', 'CoursesControllers@restore');
    Route::get('/images/delete/{id}', 'CoursesControllers@imageDelete');

    Route::get('getUniversities', 'CoursesControllers@getUniversities');
    Route::get('getFaculties/{university_id}', 'CoursesControllers@getFaculties');
    Route::get('getLessons/{course_id}', 'CoursesControllers@getLessons');
});
