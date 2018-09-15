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
Route::middleware('auth:api')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/deployments', 'DeploymentsController@index');
    Route::post('/deployments', 'DeploymentsController@store');
    Route::post('/deployments/{id}/launch', 'DeploymentsController@launch');
    Route::post('/deployments/{id}/fly', 'DeploymentsController@fly');
    Route::post('/deployments/{id}/arrive', 'DeploymentsController@arrive');
    Route::post('/deployments/{id}/deploy', 'DeploymentsController@deploy');
});

