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

        Route::get('/{id}/edit', 'PostController@edit')->name('edit');

        Route::post('/', 'PostController@store')->name('store');
    
        Route::put('/{id}', 'PostController@update')->name('update');

        Route::delete('/{id}', 'PostController@destroy')->name('destroy');

        Route::post('/{id}/comments', 'CommentController@store')->name('comments');
    });

    Route::get('/{id}', 'PostController@show')->name('show');
    Route::get('/{id}/comments', 'PostController@getComments')->name('comments');

    Route::post('/{id}/seen', 'PostController@incrementSeen')->name('seen');
});

/*
|--------------------------------------------------------------------------
| Coments Routes
|--------------------------------------------------------------------------
*/
Route::prefix('comments')->name('comment.')->group(function () {
    Route::middleware(['auth'])->group(function () {
        Route::get('/{id}/edit', 'CommentController@edit')->name('edit');

        Route::put('/{id}', 'CommentController@update')->name('update');
        
        Route::delete('/{id}', 'CommentController@destroy')->name('destroy');
    });
});

/*
|--------------------------------------------------------------------------
| Users Routes
|--------------------------------------------------------------------------
*/
Route::prefix('users')->name('user.')->group(function () {
    Route::get('/{username}', 'UserController@show')->name('profile');

    Route::middleware(['auth'])->group(function () {
        Route::get('/{username}/edit', 'UserController@edit')->name('edit');
        
        Route::put('/{id}', 'UserController@update')->name('update');
    });
});
