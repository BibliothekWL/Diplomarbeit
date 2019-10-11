<?php

Route::get('/','PagesController@home');
Route::get('/contact','PagesController@contact');


Auth::routes(['verify' => true]);

Route::get('/home', 'HomeController@index')->name('home');

//Route::get('auth/logout', 'Auth\AuthController@logout');

//Route::patch('/books/{books}/borrow', 'BooksController@addToCart');
Route::get('/books/mybooks', 'UserController@show');

Route::resource('books', 'BooksController');
//Route::resource('user', 'UserController');

Route::get('/user/edit','UserController@view');
Route::patch('/user/edit','UserController@update');