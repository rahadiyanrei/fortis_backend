<?php

Route::group(['prefix' => 'gallery', 'middleware' => 'auth.sentinel'], function(){
  Route::get('/', 'GalleryController@welcome')->name('gallery');
  Route::get('/create', 'GalleryController@formCreate')->name('gallery');
  Route::get('/update/{uuid}', 'GalleryController@formUpdate')->name('gallery');
  Route::get('/delete/{uuid}', 'GalleryController@delete')->name('gallery');
  Route::post('/post', 'GalleryController@post')->name('gallery');
});
