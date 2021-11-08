<?php

Route::group(['prefix' => 'contact', 'middleware' => 'auth.sentinel'], function(){
  // Social Media
  Route::get('/social_media', 'SocialMediaController@index')->name('social_media');
  Route::post('/social_media', 'SocialMediaController@post');

  //Dealer
  Route::get('/dealer','DealerController@index')->name('dealer');
  Route::get('/dealer/create','DealerController@form')->name('dealer');
  Route::get('/dealer/{uuid}','DealerController@view')->name('dealer');
  Route::post('/dealer/post','DealerController@post')->name('dealer');
});
