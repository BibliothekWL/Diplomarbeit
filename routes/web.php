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

Route::get('/','PagesController@home');
Route::get('/contact','PagesController@contact');



Auth::routes();

Auth::routes(['verify' => true]);

Route::get('/home', 'HomeController@index')->name('home');

Route::get('auth/logout', 'Auth\AuthController@logout');

Route::resource('books', 'BooksController');
    /*
    Route::get('/books', 'BooksController@index');

    Route::post('/books', 'BooksController@store');

    Route::get('/books/create', 'BooksController@create');

    Route::get('books/{books}', 'BooksController@show');

    Route::get('books/{books}/edit', 'BooksController@edit');

    Route::patch('books/{books}', 'BooksController@update');

    Route::delete('books/{books}', 'BooksController@destroy');
    */
