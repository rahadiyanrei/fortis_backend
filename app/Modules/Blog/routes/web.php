<?php

Route::group(['prefix' => 'blog', 'middleware' => 'auth.sentinel'], function(){
  Route::get('/', 'BlogController@welcome')->name('blog');
  Route::get('/create', 'BlogController@form')->name('blog');
  Route::get('/update/{uuid}', 'BlogController@formUpdate')->name('blog');
  Route::post('/post', 'BlogController@post')->name('blog');
  Route::post('/imageUploadContent', 'BlogController@imageUploadContent');
});
