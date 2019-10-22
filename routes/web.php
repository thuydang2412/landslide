<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/test', function () {
    return view('test/sample');
});

Route::get('/maintain', function () {
    return view('site/maintain');
});


Route::group(['namespace' => 'Site', 'middleware' => ['site']], function () {
    Route::get('/', ['uses' => 'HomeController@index']);
    Route::get('/trang-chu', ['uses' => 'HomeController@index']);
    //Route::get('/kml', ['uses' => 'HomeController@kml']);

    Route::get('/thoi-tiet', ['uses' => 'WeatherController@index']);
    Route::get('/thoi-tiet-mobile', ['uses' => 'WeatherController@indexMobile']);

    Route::get('/thoi-tiet/get-station', ['uses' => 'WeatherController@getStation']);
    Route::get('/thoi-tiet/search', ['uses' => 'WeatherController@doSearch']);

    Route::get('/thoi-tiet/get-station-pessl', ['uses' => 'WeatherController@getStationPessl']);
    Route::get('/thoi-tiet/search-pessl', ['uses' => 'WeatherController@doSearchPessl']);


    Route::get('/map', ['uses' => 'HomeController@showMap']);

    Route::get('/tintuc/', ['uses' => 'NewsController@listNews']);
    Route::get('/tintuc/{newsId}', ['uses' => 'NewsController@detailNews']);

    Route::get('/contact', ['uses' => 'HomeController@contact']);

    Route::get('/trang-chu/sub-district', ['uses' => 'HomeController@getAllDistrict']);
    Route::get('/trang-chu/sub-sub-district', ['uses' => 'HomeController@getAllDistrictLv1']);
    Route::get('/trang-chu/station', ['uses' => 'HomeController@getAllStation']);

    Route::get('/trang-chu/point-ly-trinh', ['uses' => 'HomeController@getAllPointLyTrinh']);
    Route::get('/trang-chu/warning-canh-bao', ['uses' => 'HomeController@warningCanhBao']);
});

Route::group(['namespace' => 'Common'], function () {
    Route::get('/place/get-sub-district', ['uses' => 'PlaceController@getSubDistrict']);
    Route::get('/place/get-district', ['uses' => 'PlaceController@getDistrictInfo']);

    Route::get('/precipitation/recent', ['uses' => 'PrecipitationController@getRecent']);
    Route::get('/boundary/subdistrict', ['uses' => 'BoundaryController@getSubDistrictBoundary']);
    Route::get('/map/kmlData', ['uses' => 'MapController@getKmlData']);

    Route::get('/test', ['uses' => 'BoundaryController@getPrecipInfo']);
});


Route::group(['namespace' => 'Admin', 'middleware' => ['admin']], function () {
    Route::get('/admin/login', ['uses' => 'AdminController@login']);
    Route::post('/admin/login', ['uses' => 'AdminController@login']);
    Route::get('/admin/logout', ['uses' => 'AdminController@logout']);
    Route::get('/admin', ['uses' => 'SettingController@setting']);
    Route::get('/admin/map', ['uses' => 'MapController@index']);

    // News
    Route::get('/admin/news/grid', 'NewsController@grid');
    Route::resource('/admin/news', 'NewsController');

    Route::post('/admin/save-color', ['uses' => 'SettingController@saveColor']);

    // User CRUD
    Route::get('/admin/create-user', ['uses' => 'SettingController@createUser']);
    Route::post('/admin/create-user', ['uses' => 'SettingController@createUser']);
    Route::get('/admin/edit-user/{userId}', ['uses' => 'SettingController@editUser']);
    Route::post('/admin/edit-user/{userId}', ['uses' => 'SettingController@editUser']);
    Route::post('/admin/delete-user/{userId}', ['uses' => 'SettingController@deleteUser']);

    // Route
    Route::post('/admin/save-route-point', ['uses' => 'LyTrinhController@save']);
    Route::post('/admin/delete-route-point', ['uses' => 'LyTrinhController@delete']);
});

