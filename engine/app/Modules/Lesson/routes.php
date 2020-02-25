<?php

/*----------------------------------------------------------
Lessons
----------------------------------------------------------*/
Route::group(['prefix' => '/lessons'] , function () {
    Route::get('/{id}', 'LessonControllers@getOne');
    Route::post('/{id}/questions', 'LessonControllers@answerQuestion');
});
