<?php

namespace App\Http\Controllers;

use App\Borrowing;
use App\Cart as Cart;
use App\Http\Resources\Books as BooksResource;
use App\User;
use Illuminate\Http\RedirectResponse;
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
     * adds the option to delete a book as an admin user
     * @param Book $book
     * @return RedirectResponse|Redirector
     * @throws \Exception
     */
    public function destroy(Book $book)
    {
        $book->delete();
        return (redirect('/books/'));
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
        foreach($carts as $cart){
            $book = $cart->book;
            array_push($books,$book);
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
        return(redirect('borrowing'));
    }

    public function returnBooks(){
        $books = User::findOrFail(auth()->user()->id)->books;
        Borrowing::where('user_id', auth()->user()->id)->delete();

        foreach($books as $book){
            $book->borrowed = 0;
            $book->save();
        }
        return redirect('/borrowing');
    }

    public function createBookValid(){
        $validator = Validator::make(Request::all(), [
            'title' => 'title',
            'systematik' => 'systematik',
            'medium' => 'medium',
            'content' => 'content',
            'BNR' => 'BNR',
        ]);
        if(!$validator->fails()){
            return response('successful',200);
        } else {
            return response('invalid',422);
        }
    }

}
