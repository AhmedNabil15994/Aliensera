<?php

/*----------------------------------------------------------
Lessons
----------------------------------------------------------*/
Route::group(['prefix' => '/lessons'] , function () {
    Route::get('/{id}', 'LessonControllers@getOne');
    Route::post('/{id}/questions', 'LessonControllers@answerQuestion');
});

/*----------------------------------------------------------
Lessons Reminders
----------------------------------------------------------*/
Route::group(['prefix' => '/reminders'] , function () {
    Route::get('/', 'ReminderControllers@index');
    Route::get('/{id}', 'ReminderControllers@getOne');
    Route::post('/update/{id}', 'ReminderControllers@update');
});
