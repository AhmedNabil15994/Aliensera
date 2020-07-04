<?php

/*----------------------------------------------------------
Quizes
----------------------------------------------------------*/
Route::group(['prefix' => '/quizes'] , function () {
    Route::get('/', 'QuizControllers@index');
    Route::get('edit/{id}', 'QuizControllers@edit');
    Route::post('edit/{id}/addQuestion', 'QuizControllers@addQuestion');
    Route::get('edit/{id}/removeQuestion/{question_id}', 'QuizControllers@removeQuestion');
    Route::post('update/{id}', 'QuizControllers@update');
    Route::get('add', 'QuizControllers@add');
    Route::post('create', 'QuizControllers@create');
    Route::get('delete/{id}', 'QuizControllers@delete');
});
