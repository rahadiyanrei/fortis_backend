<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'api/province'], function(){
  Route::get('/', 'ProvinceController@list');
});

Route::group(['prefix' => 'api/country'], function(){
  Route::get('/', 'CountryController@list');
});

Route::group(['prefix' => 'api/city'], function(){
  Route::get('/', 'CityController@list');
});