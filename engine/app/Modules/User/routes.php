<?php

	Route::get('/instructors', 'UsersControllers@getInstructors');
	Route::get('/instructors/{id}', 'UsersControllers@getOneInstructor');
	Route::get('/getUserData', 'UsersControllers@getUserData');
	Route::post('/updateUserData', 'UsersControllers@updateUserData');
	Route::post('/logout', 'AuthController@logout');
