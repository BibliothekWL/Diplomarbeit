<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

use \App\Book;
use \App\Author;
use Illuminate\Routing\Redirector;

class BooksController extends Controller
{
    public function index(){
        $books = Book::all();
        return view('books.index', compact('books'));
    }

    public function create(){
        $user = User::findOrFail(auth()->user()->id);
        return view('books.create', compact('user'));

    }
    public function store(){
       Book::create(request()->all());
    }

    public function show(){

    }

    public function edit(Book $book){
        return view('books.edit',compact('book'));
    }

    /**
     * adds the option to update a book as an admin user
     * @param Book $book
     * @return RedirectResponse|Redirector
     */

    public function update(Book $book){
        $book->update(request()->all());
        return(redirect('/books'));
    }

    /**
     * adds the option to delete a book as an admin user
     * @param Book $book
     * @return RedirectResponse|Redirector
     * @throws \Exception
     */
    public function destroy(Book $book){
        $book->delete();
        return(redirect('/books/'));
    }

    /**
     * checks if a book is already borrowed
     * @param $id
     * @return bool
     */
    public function checkAvailability($id){
        $book = Book::find($id);
        if($book->borrowed == 1){
            dd('this book is already borrowed by someone');
            return false;
        }
        else{
            dd('this book is not borrowed out right now');
            return true;
        }
    }

    /**
     * adds a function to borrow the books that are in your shopping cart
     * @param $books
     */
    public function borrowBooks($books){
        foreach($books as $item){
            if($this->checkAvailability($item)){
                $book = Book::find($item);
                $book->borrowed = 1;
                $book->borrower = auth()->user()->id;
                $book->save();
            }
            else{
                dd('this book is not available right now');
            }
        }
    }

    /**
     * creates an array filled with book objects from the session variable "cart", which is filled with ids of books
     */
    public function fillCartWithBooks(){
        $_SESSION['cartWithBooks'] = array();
        foreach($_SESSION['cart'] as $item ){
            $book = Book::find($item);
            array_push($_SESSION['cartWithBooks'], $book);
        }
    }
}
