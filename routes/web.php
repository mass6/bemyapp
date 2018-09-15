<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\AED;
use App\AedClosed;
use App\OpenHours;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/aeds', 'MapController@index');

Route::post('/', 'CallForHelpController@store');

Route::get('test', function () {

    // return AED::with('hours')->where('aed_id', 1000022371)->first();
    // return $aed->available === 'Ja' ? 1 : 0;
    //
    // AedClosed::chunk(100, function ($aeds) {
    //     $aeds->each(function($aed, $index) {
    //         $available = $aed->available === 'Ja' ? 1 : 0;
    //         $aed->available = $available;
    //         $aed->save();
    //     });
    // });
    //
    // return 'done';
});

Route::get('/mapbox', function () {
    return view('mapbox');
});

Route::get('map', function (){
    return view('map');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

