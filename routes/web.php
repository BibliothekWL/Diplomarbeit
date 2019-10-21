<?php

use App\Http\Resources\User as UserResource;
use App\Http\Resources\Books as BooksResource;

use \App\User as User;
use \App\Book;

Route::get('/','PagesController@home');
Route::get('/contact','PagesController@contact');


Auth::routes(['verify' => true]);

Route::get('/home', 'HomeController@index')->name('home');

//Route::get('auth/logout', 'Auth\AuthController@logout');

//Route::patch('/books/{books}/borrow', 'BooksController@addToCart');
Route::get('/books/mybooks', 'UserController@show');

//Route::get('/books/mybooks', '');

Route::resource('books', 'BooksController');
//Route::resource('user', 'UserController');

Route::get('/user/edit','UserController@view');
Route::patch('/user/edit','UserController@update');

//JSON

//all books with json
Route::get('/books/books/json',function(){
    return BooksResource::collection(Book::all());
});

//all books for one user

Route::get('/books/mybooks/json',function(){
    $user = User::findOrFail(auth()->user()->id);
    return BooksResource::collection($user->books);
});
