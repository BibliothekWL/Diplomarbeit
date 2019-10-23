<?php

use App\Http\Resources\User as UserResource;
use App\Http\Resources\Books as BooksResource;

use \App\User as User;
use \App\Book as Book;

Route::get('/','PagesController@home');
Route::get('/contact','PagesController@contact');

Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');

Auth::routes(['verify' => true]);

Route::get('/home', 'HomeController@index')->name('home');

//Route::get('auth/logout', 'Auth\AuthController@logout');

Route::patch('/books/{books}/borrow', 'CartsController@create');
Route::get('/books/mybooks', 'UserController@show');

//Route::get('/books/mybooks', '');

Route::patch('/cart/checkout','BooksController@borrowBooks');
/**
 * returns all books with json
 */
Route::get('/books/json',function(){

    return BooksResource::collection(Book::all());
});

Route::resource('borrowing', 'BorrowingsController');
Route::resource('books', 'BooksController');
Route::resource('cart', 'CartsController');
//Route::resource('user', 'UserController');

Route::get('/user/edit','UserController@view');
Route::patch('/user/edit','UserController@update');


/**
 * all books for one user with json
*/
Route::get('/books/mybooks/json',function(){
    return BooksResource::collection(User::findOrFail(auth()->user()->id)->books);
});

/*
 * return all items of cart of current user in json
 */
Route::get('/books/cart/json',function(){
    return BooksResource::collection(User::findOrFail(auth()->user()->id)->books);
});



