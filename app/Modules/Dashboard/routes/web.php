<?php


Route::group(['prefix' => 'dashboard', 'middleware' => 'auth.sentinel'], function(){
    Route::get('/', 'DashboardController@welcome');
});
