<?php

/*----------------------------------------------------------
Quizs Scores
----------------------------------------------------------*/
Route::group(['prefix' => '/scores'] , function () {
    Route::get('/', 'QuizScoresControllers@index');
});