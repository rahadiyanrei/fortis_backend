<?php

Route::group(['prefix' => 'wheel', 'middleware' => 'auth.sentinel'], function(){
    // GLOBAL ROUTE CREATE AND UPDATE
    Route::post('/{brand}/create', 'WheelsController@wheelCreateOrUpdateAction');
    Route::post('/{brand}/update', 'WheelsController@wheelCreateOrUpdateAction');

    // PAKO
    Route::get('/pako', 'WheelsController@wheelIndex')->name('pako');
    Route::get('/pako/create', 'WheelsController@wheelCreateFormView')->name('pako');
    Route::get('/pako/{uuid}', 'WheelsController@wheelView')->name('pako');
    Route::get('/pako/view/{uuid}', 'WheelsController@wheelViewPage')->name('pako');
    // INKO
    Route::get('/inko', 'WheelsController@wheelIndex')->name('inko');
    Route::get('/inko/create', 'WheelsController@wheelCreateFormView')->name('inko');
    Route::get('/inko/{uuid}', 'WheelsController@wheelView')->name('inko');
    Route::get('/inko/view/{uuid}', 'WheelsController@wheelViewPage')->name('inko');
    // FORTIS
    Route::get('/fortis', 'WheelsController@wheelIndex')->name('fortis');
    Route::get('/fortis/create', 'WheelsController@wheelCreateFormView')->name('fortis');
    Route::get('/fortis/{uuid}', 'WheelsController@wheelView')->name('fortis');
    Route::get('/fortis/view/{uuid}', 'WheelsController@wheelViewPage')->name('fortis');
    // AVANTECH
    Route::get('/avantech', 'WheelsController@wheelIndex')->name('avantech');
    Route::get('/avantech/create', 'WheelsController@wheelCreateFormView')->name('avantech');
    Route::get('/avantech/{uuid}', 'WheelsController@wheelView')->name('avantech');
    Route::get('/avantech/view/{uuid}', 'WheelsController@wheelViewPage')->name('avantech');

    Route::get('/{brand}/delete/{uuid}', 'WheelsController@delete')->name('avantech');
});

Route::post('/upload-image','UploadController@upload');
