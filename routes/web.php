<?php

use App\Http\Resources\User as UserResource;
use App\Http\Resources\Books as BooksResource;
use App\Http\Resources\Cart as CartResource;

use \App\User as User;
use \App\Book as Book;
use \App\Cart as Cart;

/*
 * return all items of cart of current user in json
 */
Route::get('/cart/json',function(){
    return CartResource::collection(Cart::where('user_id', auth()->user()->id)->get());
});

Route::get('/home','SinglePageController@index');
Route::get('/list','SinglePageController@index');
Route::get('/login', 'SinglePageController@index');
Route::get('/register', 'SinglePageController@index');


Route::get('/','PagesController@home');
Route::get('/contact','PagesController@contact');
Route::get('/session',function(){
    return json_encode(session()->has('id'));
});

Route::post('/books/return','BooksController@returnBooks');

Route::post('/books/borrow/', 'BooksController@borrowBooks');

Route::get('/logout/json', '\App\Http\Controllers\Auth\LoginController@logout');

Route::post('/login/json/','\App\Http\Controllers\Auth\LoginController@authenticate');

Route::post('/user/register', '\App\Http\Controllers\Auth\RegisterController@create');

Route::post('/books/create/json/','BooksController@addBookValidator');

Route::post('/books/delete/json/','BooksController@deleteBookValidator');

Route::post('/books/edit/json/','BooksController@BookValidator');
Auth::routes(['verify' => true]);

Route::get('/home', 'HomeController@index')->name('home');

Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');

Route::patch('/books/{books}/borrow', 'CartsController@create');
Route::get('/books/mybooks', 'UserController@show');

Route::post('/books/borrowed', function(){
    $json = file_get_contents('php://input');
    $jsonarray = json_decode($json, true);
    $borrowed_raw = DB::table('books')->where('id', $jsonarray['id'])->pluck('borrowed');
    $borrowed = explode("]", explode("[", $borrowed_raw)[1])[0];
   if($borrowed == 1){
        return json_encode(true);
    } else {
        return json_encode(false);
    }
});

//Route::get('/books/mybooks', '');

Route::patch('/cart/checkout','BooksController@borrowBooks');
/**
 * returns all books with json
 */

Route::get('/books/json', function(){
    return DB::table('books')->paginate(6);
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


Route::post('/books/{books}/edit/jsonvalidate/','BooksController@BookValidator');


