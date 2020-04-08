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
Route::get('/', 'PostController@index')->name('welcome');

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
        Route::post('/{id}/save', 'PostController@savePost')->name('save');
        Route::post('/{id}/unsave', 'PostController@unSavePost')->name('unsave');
        Route::post('/{id}/comments', 'CommentController@store')->name('comments');

        Route::put('/{id}', 'PostController@update')->name('update');

        Route::delete('/{id}', 'PostController@destroy')->name('destroy');
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
        Route::get('/{id}', 'CommentController@show')->name('show');
        Route::get('/{id}/edit', 'CommentController@edit')->name('edit');

        Route::post('/{id}/pin', 'CommentController@pin')->name('pin');
        Route::post('/{id}/unpin', 'CommentController@unpin')->name('unpin');

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
    Route::middleware(['auth'])->group(function () {
        Route::get('/saved-posts', 'UserController@getSavedPosts')->name('saved_posts');
        Route::get('/{username}/edit', 'UserController@edit')->name('edit');
        Route::get('/{username}/password', 'UserController@editPassword')->name('edit_password');

        Route::put('/{id}', 'UserController@update')->name('update');
        Route::put('/{username}/password', 'UserController@updatePassword')->name('update_password');
    });

    Route::get('/{username}', 'UserController@show')->name('profile');
});

Route::prefix('tags')->name('tag.')->group(function () {
    Route::get('/', 'TagController@index')->name('index');
});

/*
|--------------------------------------------------------------------------
| Notifications Routes
|--------------------------------------------------------------------------
*/
Route::prefix('notifications')->name('notification.')->middleware(['auth'])->group(function () {
    Route::get('/', 'NotificationController@index')->name('index');
    Route::get('/data', 'NotificationController@getNotifications')->name('data');
    Route::get('/count', 'NotificationController@count')->name('count');
    Route::get('/{notif_id}', 'NotificationController@show')->name('show');

    Route::post('/count/reset', 'NotificationController@countReset')->name('count.reset');
    Route::post('/{notif_id}/mark-read', 'NotificationController@markRead')->name('mark-read');
    Route::post('/{notif_id}/mark-unread', 'NotificationController@markUnRead')->name('mark-unread');

    Route::delete('/{notif_id}', 'NotificationController@destroy')->name('destroy');
});
