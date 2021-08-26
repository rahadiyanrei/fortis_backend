<?php

Route::group(['prefix' => 'contact', 'middleware' => 'auth.sentinel'], function(){
  Route::get('/social_media', 'SocialMediaController@index')->name('social_media');
  Route::post('/social_media', 'SocialMediaController@post');
});
