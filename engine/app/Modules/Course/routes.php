<?php

/*----------------------------------------------------------
Courses
----------------------------------------------------------*/
Route::group(['prefix' => '/courses'] , function () {
    Route::get('/', 'CourseControllers@index');
    Route::get('/{id}', 'CourseControllers@getOne');
    Route::post('/{id}/enroll', 'CourseControllers@enroll');
    Route::post('/{id}/addDiscussion', 'CourseControllers@addDiscussion');
    Route::post('/{id}/updateDiscussion/{disucssion_id}', 'CourseControllers@updateDiscussion');
    Route::post('/{id}/removeDiscussion/{disucssion_id}', 'CourseControllers@removeDiscussion');
   
});
