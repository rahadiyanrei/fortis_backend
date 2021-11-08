<?php


Route::group(['prefix' => 'apparel', 'middleware' => 'auth.sentinel'], function(){
  Route::get('/', 'ApparelController@list');
  Route::get('/create', 'ApparelController@form');
  Route::post('/post', 'ApparelController@post');
  Route::get('/{uuid}', 'ApparelController@view');
  Route::get('/delete/{uuid}', 'ApparelController@delete');
});
