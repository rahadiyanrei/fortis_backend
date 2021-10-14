<?php

Route::group(['prefix' => 'admin', 'middleware' => 'auth.sentinel'], function(){
    Route::get('/', 'AdminController@index');
    Route::get('/create', 'AdminController@form');
});