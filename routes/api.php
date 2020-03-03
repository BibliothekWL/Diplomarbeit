<?php

use Illuminate\Http\Request;
use \App\Author as Author;


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

Route::middleware('auth:api')
    ->get('/user', function (Request $request) {
        return $request->user();
    });

Route::post('/login/json/', '\App\Http\Controllers\Auth\LoginController@login');

Route::post('/books/create/json/', 'BooksController@BookValidator');

Route::post('/books/edit/json', 'BooksController@BookValidator');

Route::post('books/delete/json', 'BooksController@deleteBookValidator');
