<?php

Route::group(['prefix' => 'wheel', 'middleware' => 'auth.sentinel'], function(){
    // GLOBAL ROUTE CREATE AND UPDATE
    Route::post('/{brand}/create', 'WheelsController@wheelCreateOrUpdateAction');
    Route::post('/{brand}/update', 'WheelsController@wheelCreateOrUpdateAction');

    // PAKO
    Route::get('/pako', 'WheelsController@wheelIndex')->name('pako');
    Route::get('/pako/create', 'WheelsController@wheelCreateFormView')->name('pako');
    Route::get('/pako/{uuid}', 'WheelsController@wheelView')->name('pako');
    // INKO
    Route::get('/inko', 'WheelsController@wheelIndex')->name('inko');
    Route::get('/inko/create', 'WheelsController@wheelCreateFormView')->name('inko');
    Route::get('/inko/{uuid}', 'WheelsController@wheelView')->name('inko');
    // FORTIS
    Route::get('/fortis', 'WheelsController@wheelIndex')->name('fortis');
    Route::get('/fortis/create', 'WheelsController@wheelCreateFormView')->name('fortis');
    Route::get('/fortis/{uuid}', 'WheelsController@wheelView')->name('fortis');
    // AVANTECH
    Route::get('/avantech', 'WheelsController@wheelIndex')->name('avantech');
    Route::get('/avantech/create', 'WheelsController@wheelCreateFormView')->name('avantech');
    Route::get('/avantech/{uuid}', 'WheelsController@wheelView')->name('avantech');
});
