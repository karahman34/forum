<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

// Auth
Auth::routes(['verify' => true]);

// Index
Route::get('/', 'PostController@index');

/*
|--------------------------------------------------------------------------
| Posts Routes
|--------------------------------------------------------------------------
*/
Route::prefix('posts')->name('post.')->group(function () {
    Route::middleware(['auth'])->group(function () {
        Route::get('/create', 'PostController@create')->name('create');

        Route::post('/', 'PostController@store')->name('store');
    });
});
