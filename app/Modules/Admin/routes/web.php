<?php

Route::group(['prefix' => 'admin', 'middleware' => 'auth.sentinel'], function(){
    Route::get('/', 'AdminController@index')->name('admin');
    Route::get('/create', 'AdminController@form')->name('admin');
    Route::get('/profile', 'AdminController@profile');
    Route::post('/profile/update', 'AdminController@profileUpdate');
    Route::get('/{id}', 'AdminController@detail')->name('admin');
    Route::get('/ban/{id}', 'AdminController@banned')->name('admin');
    Route::post('/post', 'AdminController@post')->name('admin');
});