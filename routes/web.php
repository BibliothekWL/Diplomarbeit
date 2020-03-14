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
Route::get('/authorlist  ', 'SinglePageController@index');
Route::get('/admin  ', 'SinglePageController@index');


Route::get('/session', function () {
    return json_encode(session()->has('id'));
});

Route::get('/logout/json', '\App\Http\Controllers\Auth\LoginController@logout');

Route::post('/login/json/', '\App\Http\Controllers\Auth\LoginController@authenticate');

Route::post('/user/register', '\App\Http\Controllers\Auth\RegisterController@create');

Route::post('/books/create/json/', 'BooksController@addBookValidator');

Route::post('/books/delete/json/', 'BooksController@deleteBookValidator');

Route::post('/books/edit/json/', 'BooksController@BookValidator');

Route::post('/cart/delete', 'CartsController@destroy');

Route::post('returnBooks', 'BooksController@returnBooks');

Route::post('/books/newest', function () {
    $newest = DB::table('books')->orderBy('created_at', 'desc')->first();
    return json_encode($newest);
});

Route::post('/books/top', function () {
    $top = DB::table('books')->orderBy('borrowCounter', 'desc')->first();
    return json_encode($top);
});

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

Route::get('/getBorrowings', function () {
    return DB::table('books')->where('borrowed', '=',1)
        ->join('users','user_id', '=', 'users.id')
        ->select( 'books.id', 'books.title', 'users.name')
        ->get();
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
        $conditions[] = "systematik_long='$by_systematik'";
    }

    $sql = "";
    if (count($conditions) > 0) {
        $sql .= htmlspecialchars(implode(' AND ', $conditions));
    } else {
        if ($jsonarray["sortDirection"]) {
            return DB::table('books')->orderBy('title')->select()->paginate($jsonarray['item_size']);
        } else {
            return DB::table('books')->orderBy('title', 'desc')->select()->paginate($jsonarray['item_size']);
        }
    }

    if ($jsonarray["sortDirection"]) {
        return DB::table('books')->orderBy('title')->select()->whereRaw(DB::raw($sql))->paginate($jsonarray['item_size']);
    } else {
        return DB::table('books')->orderBy('title', 'desc')->select()->whereRaw(DB::raw($sql))->paginate($jsonarray['item_size']);
    }
});

Route::resource('borrowing', 'BorrowingsController');
Route::resource('books', 'BooksController');
Route::resource('cart', 'CartsController');
//Route::resource('user', 'UserController');

Route::post('/user/editName', 'UserController@editName');
Route::post('/user/editPassword', 'UserController@editPassword');

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
        $conditions[] = "systematik_long='$by_systematik'";
    }

    $sql = "";
    if (count($conditions) > 0) {
        $sql .= htmlspecialchars(implode(' AND ', $conditions) . "AND title LIKE '%" . $by_search . "%'");
    } else {
        $sql .= "title LIKE '%" . $by_search . "%'";
    }

    if ($jsonarray["sortDirection"]) {
        return DB::table('books')->orderBy('title')->select()->whereRaw(DB::raw($sql))->paginate($jsonarray['item_size']);
    } else {
        return DB::table('books')->orderBy('title', 'desc')->select()->whereRaw(DB::raw($sql))->paginate($jsonarray['item_size']);
    }
});

Route::post('/cart/json', function () {
    $json = file_get_contents('php://input');
    $jsonarray = json_decode($json, true);
    $cartArray = Cart::where('user_id', $jsonarray['id'])->get();
    $books = array();
    for ($i = 0; $i < count($cartArray); $i++) {
        $book = Book::where('id', $cartArray[$i]['book_id'])->get();
        array_push($books, $book[0]);
    }
    return $books;
});

Route::get('/systematik/json', function () {
    return Book::orderBy('systematik_long')->get()->pluck('systematik_long')->unique();
});

Route::get('/medium/json', function () {
    return Book::orderBy('medium')->get()->pluck('medium')->unique();
});

Route::get('/authors/json', function () {
    $authorArray = Author::orderBy('name')->get()->unique();
    $authors = array();
    for ($i = 0; $i < count($authorArray); $i++) {
        $author = $authorArray[$i]['name'];
        array_push($authors, $author);
    }
    return $authors;
});

Route::get('/carts/json', function () {
    return Cart::orderBy('book_id')->get()->pluck('book_id')->unique();
});

Route::post('/author/json', function () {
    $json = file_get_contents('php://input');
    $jsonarray = json_decode($json, true);

    return DB::table('authors')->orderBy('name')->select()->paginate($jsonarray['item_size']);
});

Route::post('/author/search', function () {
    $json = file_get_contents('php://input');
    $jsonarray = json_decode($json, true);

    return DB::table('authors')->orderBy('name')->where('name', 'LIKE', '%' . $jsonarray['search'] . '%')->select()->paginate($jsonarray['item_size']);
});

Route::post('/userdata/json', function () {
    $json = file_get_contents('php://input');
    $jsonarray = json_decode($json, true);
    return User::all()->where('id', $jsonarray['id'])->first();
});

//Route::get('/email/verify/{id}/{code}/', 'UserController@verifyEmail');
//Route::get('/email/verify/{id}/{code}', [ 'as' => 'login', 'uses' => 'UserController@verifyEmail']);

Route::post('books/author/json', function () {
    $json = file_get_contents('php://input');
    $jsonarray = json_decode($json, true);

    $authorid = DB::table('authors_books')->where('book_id', $jsonarray['id'])->first()->pluck('author_id');

    $result = DB::table('authors')->where('id', $authorid)->first()->pluck('name');
    return $result;
});

Route::post('/author/edit/', 'AuthorController@edit');
Route::post('/author/create/', 'AuthorController@create');
Route::post('/author/delete/', 'AuthorController@destroy');
