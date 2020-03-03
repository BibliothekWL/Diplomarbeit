<?php

namespace App\Http\Controllers;

use App\Authors_Books;
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

class BooksController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function store()
    {
        Book::create(request()->all());
    }

    public function show()
    {

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
            $counter = $book->borrowCounter;
            $counter1 = $counter+1;
            $book->borrowed = 1;
            $book->user_id = auth()->user()->id;
            $book->borrowCounter = $counter1;
            $book->save();

            $borrowing = new Borrowing();
            $borrowing->user_id = auth()->user()->id;
            $borrowing->book_id = $item->id;
            $borrowing->save();
        }

        Cart::where('user_id', auth()->user()->id)->delete();
        return json_encode(['status' => 200, 'statusMessage' => 'yeah man']);
    }

    public function returnBooks()
    {
        $json = file_get_contents('php://input');
        $jsonarray = json_decode($json, true);
        if (Book::where('id', $jsonarray['id'])->get()->count() == 0) {
            return json_encode(['status' => 400, 'statusMessage' => 'return failed']);
        } else {
//            $books = User::findOrFail(auth()->user()->id)->books;
//            Borrowing::where('user_id', auth()->user()->id)->delete();
//
//            foreach ($books as $book) {
//                $book->borrowed = 0;
//                $book->save();
//            }
            DB::table('books')
                ->where('id', $jsonarray['id'])
                ->update([
                    'borrowed' => 0,
                    'user_id' => 0
                ]);
            return json_encode(['status' => 200, 'statusMessage' => 'return successful']);
        }
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
            return json_encode(['status' => 200, 'statusMessage' => 'delete successful']);
        }
    }

    public function addBookValidator()
    {
        $json = file_get_contents('php://input');
        $jsonarray = json_decode($json, true);
        $author_id_raw = DB::table('authors')->where('name', $jsonarray['authorname'])->pluck('id');
        $author_id = explode("]", explode("[", $author_id_raw)[1])[0];
        if (sizeof($jsonarray) != 0) {
            $book = new Book();
            $book->user_id = 0;
            $book->title = $jsonarray['title'];
            $book->systematik = $jsonarray['systematik'];
            $book->systematik_long = "asd";
            $book->category = "asd";
            $book->medium = $jsonarray['medium'];
            $book->content = $jsonarray['content'];
            $book->BNR = $jsonarray['BNR'];
            $book->borrowed = 0;
            $book->created_at = now();
            $book->updated_at = now();
            $book->save();

            $authors_books = new Authors_Books();
            $authors_books->author_id = $author_id;
            $authors_books->book_id = $book->id;
            $authors_books->save();
            return json_encode(['status' => 200, 'statusMessage' => 'created successfully']);
        } else {
            return json_encode(['status' => 400, 'statusMessage' => 'failed creating']);
        }
    }
}