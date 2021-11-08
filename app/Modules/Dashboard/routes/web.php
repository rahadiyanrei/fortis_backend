<?php


Route::group(['prefix' => 'dashboard', 'middleware' => 'auth.sentinel'], function(){
    Route::get('/', 'DashboardController@welcome');
    Route::get('/wheel', 'DashboardController@wheel');
    Route::get('/blog', 'DashboardController@blog');
    Route::get('/gallery', 'DashboardController@gallery');
});
