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

// Auth
Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('login', 'Auth\AuthController@login')->name('login');
    Route::post('logout', 'Auth\AuthController@logout');
    Route::post('refresh', 'Auth\AuthController@refresh');
    Route::post('me', 'Auth\AuthController@me');
    Route::post('register', 'Auth\RegisterController@create');
});

// Genres
Route::get('genres', 'Api\GenresController@index');

// Movies
Route::post('movies/vote', 'Api\MovieController@vote');
Route::post('movies/similar', 'Api\MovieController@similar');
Route::post('movies/comment', 'Api\CommentsController@store');
Route::apiResource('movies', 'Api\MovieController');

// Watchlist
Route::get('watchlist', 'Api\WatchlistController@index');
Route::post('watchlist/{movie}', 'Api\WatchlistController@store');
Route::patch('watchlist/{movie}', 'Api\WatchlistController@update');
Route::delete('watchlist/{movie}', 'Api\WatchlistController@destroy');
