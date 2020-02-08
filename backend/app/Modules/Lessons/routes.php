<?php

/*----------------------------------------------------------
Lessons
----------------------------------------------------------*/
Route::group(['prefix' => '/lessons'] , function () {
    Route::get('/', 'LessonControllers@index');
    Route::get('/edit/{id}', 'LessonControllers@edit');
    Route::post('/edit/{id}/uploadVideo', 'LessonControllers@uploadVideo');
    Route::post('/edit/{id}/addQuestion', 'LessonControllers@addQuestion');
    Route::get('/removeQuestion/{id}', 'LessonControllers@removeQuestion');
    Route::get('/removeVideo/{video_id}', 'LessonControllers@removeVideo');
    Route::post('update/{id}', 'LessonControllers@update');
    Route::get('add', 'LessonControllers@add');
    Route::post('create', 'LessonControllers@create');
    Route::get('delete/{id}', 'LessonControllers@delete');
});

Route::group(['prefix' => '/videos'] ,function(){
    Route::get('/{id}/comments', 'LessonControllers@comments');
    Route::get('/{id}/changeStatus', 'LessonControllers@changeStatus');
    Route::post('/{id}/comments/addComment', 'LessonControllers@addComment');
    Route::get('/removeComment/{id}', 'LessonControllers@removeComment');
});