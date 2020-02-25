<?php

/*----------------------------------------------------------
Questions
----------------------------------------------------------*/
Route::group(['prefix' => '/myQuestions'] , function () {
    Route::get('/', 'QuestionControllers@index');
    Route::get('/{id}', 'QuestionControllers@getOne');
});
