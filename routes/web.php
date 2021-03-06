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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/api/init', 'AppController@getInitData');
Route::post('/api/restock', 'SessionRuntimeController@restock');
Route::get('/api/restart', 'SessionRuntimeController@restart');

Route::delete('/api/shop', 'SessionRuntimeController@shopDelete');
Route::post('/api/shop', 'SessionRuntimeController@shopCreate');