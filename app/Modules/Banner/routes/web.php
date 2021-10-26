<?php

Route::group(['prefix' => 'banner', 'middleware' => 'auth.sentinel'], function(){
  Route::get('/', 'BannerController@index')->name('banner');
  Route::get('/create', 'BannerController@form')->name('banner');
  Route::get('/{uuid}', 'BannerController@view')->name('banner');
  Route::get('/delete/{uuid}', 'BannerController@delete')->name('banner');
  Route::post('/post', 'BannerController@post');
});
