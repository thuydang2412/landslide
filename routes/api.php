<?php

use Illuminate\Http\Request;

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

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');


Route::group(['namespace' => 'Api'], function () {
    Route::get('/luongmua', ['uses' => 'ApiController@test']);
    Route::post('/send-notification', ['uses' => 'ApiController@sendNotification']);
    Route::get('/point-ly-trinh', ['uses' => 'ApiController@getAllPointLyTrinh']);
    Route::get('/route-warning-mobile', ['uses' => 'ApiController@getAllRouteWarningMobile']);

    Route::post('/route-warning', ['uses' => 'ApiController@getRouteWarning']);


});
