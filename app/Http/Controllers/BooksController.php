<?php

namespace App\Http\Controllers;

use App\Borrowing;
use App\Cart as Cart;
use App\Http\Resources\Books as BooksResource;
use App\User;
use DB;
use Illuminate\Http\RedirectResponse;
use phpDocumentor\Reflection\Types\Null_;
use \Validator;
use Request;

use App\Http\Controllers\CartsController as CartsController;

include 'CartsController.php';

use \App\Book;
use \App\Author;

use Illuminate\Routing\Redirector;
use PhpParser\Builder\Class_;

class BooksController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $books = Book::all();
        return view('books.index', compact('books'));
    }

    public function create()
    {
        $user = User::findOrFail(auth()->user()->id);
        return view('books.create', compact('user'));
    }

    public function store()
    {
        Book::create(request()->all());
    }

    public function show()
    {

    }

    public function edit(Book $book)
    {
        return view('books.edit', compact('book'));
    }

    /**
     * adds the option to update a book as an admin user
     * @param Book $book
     * @return RedirectResponse|Redirector
     */

    public function update(Book $book)
    {
        $book->update(request()->all());
        return (redirect('/books'));
    }


    /**
     * adds a function to borrow the books that are in your shopping cart
     * @param $books
     * @return RedirectResponse|Redirector
     */
    public function borrowBooks()
    {
        $carts = User::findOrFail(auth()->user()->id)->carts;
        $books = array();
        foreach ($carts as $cart) {
            $book = $cart->book;
            array_push($books, $book);
        }
        foreach ($books as $item) {
            $book = Book::findorFail($item->id);
            $book->borrowed = 1;
            $book->save();

            $borrowing = new Borrowing();
            $borrowing->user_id = auth()->user()->id;
            $borrowing->book_id = $item->id;
            $borrowing->save();
        }

        Cart::where('user_id', auth()->user()->id)->delete();
        return (redirect('borrowing'));
    }

    public function returnBooks()
    {
        $books = User::findOrFail(auth()->user()->id)->books;
        Borrowing::where('user_id', auth()->user()->id)->delete();

        foreach ($books as $book) {
            $book->borrowed = 0;
            $book->save();
        }
        return redirect('/borrowing');
    }

    public function BookValidator()
    {
        $json = file_get_contents('php://input');
        $jsonarray = json_decode($json, true);
        if (Book::where('id', $jsonarray['id'])->get()->count() == 0) {
            return json_encode(['status' => 400, 'statusMessage' => 'update failed']);
        } else {
            DB::table('books')
                ->where('id', $jsonarray['id'])
                ->update($jsonarray);
            return json_encode(['status' => 200, 'statusMessage' => 'updated book']);
        }
    }


    public function deleteBookValidator()
    {
        $json = file_get_contents('php://input');
        $id = json_decode($json, true);
        if (Book::where('id', $id)->get()->count() == 0) {
            return json_encode(['status' => 400, 'statusMessage' => 'delete failed']);
        } else {
            Book::where('id', $id)->delete();
            return json_encode(['status' => 200,'statusMessage' => 'delete successful']);
        }
    }

    public function addBookValidator()
    {
        $json = file_get_contents('php://input');
        $jsonarray = json_decode($json, true);
        $author_id_raw = DB::table('authors')->where('firstname', $jsonarray['authorname'])->pluck('id');
        $author_id = explode("]", explode("[", $author_id_raw)[1])[0];
            if (sizeof($jsonarray) != 0) {
                $book = new Book();
                $book->user_id = auth()->user()->id;
                $book->author_id = 1;
                $book->title = $jsonarray['title'];
                $book->systematik = $jsonarray['systematik'];
                $book->medium = $jsonarray['medium'];
                $book->content = $jsonarray['content'];
                $book->BNR = $jsonarray['BNR'];
                $book->borrowed = 0;
                $book->created_at = Null;
                $book->updated_at = Null;
                $book->save();
                return json_encode(['status' => 200, 'statusMessage' => 'created successfully']);
            } else {
                return json_encode(['status' => 400, 'statusMessage' => 'failed creating']);
            }
        }
}