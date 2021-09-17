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

Route::group(['prefix' => 'api/vehicle_brand'], function(){
  Route::get('/dropdown', 'VehicleBrandAPIController@dropdown');
});

Route::group(['prefix' => 'api/gallery'], function(){
  Route::get('/', 'GalleryAPIController@list');
  Route::get('/dashboard', 'GalleryAPIController@gallery_dashboard');
  Route::get('/{uuid}', 'GalleryAPIController@retrieve');
});