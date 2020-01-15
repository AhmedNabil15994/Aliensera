<?php

	Route::get('/getUserData', 'UsersControllers@getUserData');
	Route::post('/updateUserData', 'UsersControllers@updateUserData');
	Route::post('/logout', 'AuthController@logout');
