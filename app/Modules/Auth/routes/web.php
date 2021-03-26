<?php

Route::group(['prefix' => 'auth'], function(){
    Route::get('/', 'AuthController@welcome');
    Route::post('/', 'AuthController@login');
});