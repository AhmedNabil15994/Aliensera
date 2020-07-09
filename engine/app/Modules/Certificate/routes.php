<?php

/*----------------------------------------------------------
Certificates
----------------------------------------------------------*/
Route::group(['prefix' => '/certificates'] , function () {
    Route::get('/', 'CertificateControllers@index');
    Route::get('/{id}', 'CertificateControllers@getCertificate');
});