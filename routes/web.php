<?php

use App\Http\Resources\Books as BooksResource;
use App\Http\Resources\Cart as CartResource;
use App\Http\Resources\User as UserResource;
use App\Http\Resources\Author as AuthorResource;

use \App\User as User;
use \App\Cart as Cart;
use \App\Book as Book;
use \App\Author as Author;


Auth::routes(['verify' => true]);
/*
 * return all items of cart of current user in json
 */
Route::get('/home', 'SinglePageController@index');
Route::get('/', 'SinglePageController@index');
Route::get('/list', 'SinglePageController@index');
Route::get('/login', 'SinglePageController@index');
Route::get('/register', 'SinglePageController@index');
Route::get('/myBooks', 'SinglePageController@index');
Route::get('/warenkorb  ', 'SinglePageController@index');
Route::get('/profil  ', 'SinglePageController@index');

Route::get('/session', function () {
    return json_encode(session()->has('id'));
});

Route::get('/logout/json', '\App\Http\Controllers\Auth\LoginController@logout');

Route::post('/login/json/', '\App\Http\Controllers\Auth\LoginController@authenticate');

Route::post('/user/register', '\App\Http\Controllers\Auth\RegisterController@create');

Route::post('/books/create/json/', 'BooksController@addBookValidator');

Route::post('/books/delete/json/', 'BooksController@deleteBookValidator');

Route::post('/books/edit/json/', 'BooksController@BookValidator');

Route::post('returnBooks', 'BooksController@returnBooks');


Route::post('/books/borrowed', function () {
    $json = file_get_contents('php://input');
    $jsonarray = json_decode($json, true);
    $borrowed_raw = DB::table('books')->where('id', $jsonarray['id'])->pluck('borrowed');
    $borrowed = explode("]", explode("[", $borrowed_raw)[1])[0];
    if ($borrowed == 1) {
        return json_encode(true);
    } else {
        return json_encode(false);
    }
});

Route::post('/books/borrow', 'CartsController@create');
Route::get('/books/mybooks', 'UserController@show');

Route::post('/getBook', function () {
    $json = file_get_contents('php://input');
    $jsonarray = json_decode($json, true);
    $book = Book::where('id', $jsonarray['id'])->first();
    return $book;
});

//Route::get('/books/mybooks', '');

Route::get('/cart/checkout', 'BooksController@borrowBooks');
/**
 * returns all books with json
 */

Route::post('/books/json', function () {
    $json = file_get_contents('php://input');
    $jsonarray = json_decode($json, true);

    $by_medium = $jsonarray['medium'];
    $by_systematik = $jsonarray['systematik'];
    $conditions = array();

    if (!($jsonarray['medium'] == null)) {
        $conditions[] = "medium='$by_medium'";
    }
    if (!($jsonarray['systematik'] == null)) {
        $conditions[] = "systematik='$by_systematik'";
    }

    $sql = "";
    if (count($conditions) > 0) {
        $sql .= htmlspecialchars(implode(' AND ', $conditions));
    } else {
        if($jsonarray["sortDirection"]) {
            return DB::table('books')->orderBy('title')->select()->paginate(6);
        } else{
            return DB::table('books')->orderBy('title', 'desc')->select()->paginate(6);
        }
    }

    if($jsonarray["sortDirection"]) {
        return DB::table('books')->orderBy('title')->select()->whereRaw(DB::raw($sql))->paginate(6);
    } else{
        return DB::table('books')->orderBy('title', 'desc')->select()->whereRaw(DB::raw($sql))->paginate(6);
    }
});

Route::resource('borrowing', 'BorrowingsController');
Route::resource('books', 'BooksController');
Route::resource('cart', 'CartsController');
//Route::resource('user', 'UserController');

Route::get('/user/edit', 'UserController@view');
Route::patch('/user/edit', 'UserController@update');

/**
 * all books for one user with json
 */
Route::get('/books/mybooks/json', function () {
    return BooksResource::collection(User::findOrFail(auth()->user()->id)->books);
});


Route::post('/books/{books}/edit/jsonvalidate/', 'BooksController@BookValidator');

Route::post('/books/search', function () {
    $json = file_get_contents('php://input');
    $jsonarray = json_decode($json, true);

    $by_medium = $jsonarray['medium'];
    $by_systematik = $jsonarray['systematik'];
    $by_search = $jsonarray['search'];
    $conditions = array();

    if (!($jsonarray['medium'] == null)) {
        $conditions[] = "medium='$by_medium'";
    }
    if (!($jsonarray['systematik'] == null)) {
        $conditions[] = "systematik='$by_systematik'";
    }

    $sql = "";
    if (count($conditions) > 0) {
        $sql .= htmlspecialchars(implode(' AND ', $conditions) . "AND title LIKE '%" . $by_search . "%'");
    } else {
        $sql .= "title LIKE '%" . $by_search . "%'";
    }

    if($jsonarray["sortDirection"]) {
        return DB::table('books')->orderBy('title')->select()->whereRaw(DB::raw($sql))->paginate(6);
    } else{
        return DB::table('books')->orderBy('title', 'desc')->select()->whereRaw(DB::raw($sql))->paginate(6);
    }
});

Route::post('/cart/json', function () {
    $json = file_get_contents('php://input');
    $jsonarray = json_decode($json, true);
    $cartArray = Cart::where('user_id', $jsonarray['id'])->get();
    $books = array();
    for ($i = 0; $i < count($cartArray); $i++) {
        $book = Book::findOrFail($cartArray[$i]['book_id']);
        array_push($books, $book);
    }
    return $books;
});

Route::get('/systematik/json', function () {
    return Book::orderBy('systematik')->get()->pluck('systematik')->unique();
});

Route::get('/medium/json', function () {
    return Book::orderBy('medium')->get()->pluck('medium')->unique();
});

Route::get('/author/json', function () {
    return Author::all();
});

Route::post('/userdata/json', function () {
    $json = file_get_contents('php://input');
    $jsonarray = json_decode($json, true);
    return User::all()->where('id', $jsonarray['id'])->first();
});

//Route::get('/email/verify/{id}/{code}/', 'UserController@verifyEmail');
//Route::get('/email/verify/{id}/{code}', [ 'as' => 'login', 'uses' => 'UserController@verifyEmail']);
