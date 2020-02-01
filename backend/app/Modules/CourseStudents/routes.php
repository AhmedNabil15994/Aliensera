<?php

/*----------------------------------------------------------
Course Students
----------------------------------------------------------*/
Route::group(['prefix' => '/courseStudents'] , function () {
    Route::get('/', 'CourseStudentControllers@index');
});