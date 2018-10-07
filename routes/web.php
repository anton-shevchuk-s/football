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

Route::get('/', 'League\LeagueController@show');
Route::post('/play', 'League\LeagueController@play_next');
Route::post('/play_all', 'League\LeagueController@play_all');
Route::post('/reset', 'League\LeagueController@reset');
Route::post('/game/{id}', 'League\GameController@update');
