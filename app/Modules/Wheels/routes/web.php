<?php

Route::group(['prefix' => 'wheel', 'middleware' => 'auth.sentinel'], function(){
    Route::get('/pako', 'WheelsController@pakoIndex')->name('pako');
    Route::get('/inko', 'WheelsController@inkoIndex')->name('inko');
});
