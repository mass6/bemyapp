<?php



Route::get('/', function () {
    return view('welcome');
});

Route::get('/aed-find', 'MapController@test');
Route::get('/deployments/latest', 'MapController@latest');
Route::get('/deployments/{id}', 'MapController@show')->name('map.show');
Route::get('/test',function (){
    return view('test');
});

Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');


