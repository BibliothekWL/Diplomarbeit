<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use \App\Book;
use \App\Author;
use \App\AuthorsToBooks;
use \App\UsersToBooks;

//include '../../../config/session_helper.php';

class BooksController extends Controller
{
    public function index(){
        $books = Book::all();

        return view('books.index', compact('books'));
    }

    public function create(){
        return view('books.create');

    }
    public function store(){
        $book = new Book();
        $author = new Author();

        $author->firstname = request('firstname');
        $author->surname = request('surname');
        $author->save();

        $book->title = request('title');
        $book->content = request('content');
        $book->systematik = request('systematik');
        $book->medium = request('medium');
        $book->BNR = request('BNR');
        $book->save();

        $this->storeAuthorsToBooks($author->id,$book->id);

        return $this->index();
    }

    public function storeAuthorsToBooks($authorID,$bookID){
        $authorsToBooks = new AuthorsToBooks();
        $authorsToBooks->author_id = $authorID;
        $authorsToBooks->book_id = $bookID;
        $authorsToBooks->save();
    }


    public function show(){
    }

    public function edit($id){
        $book = Book::find($id);
        return view('books.edit',compact('book'));
    }

    public function update($id){
        $this->updateBook($id, request()->all());
        return(redirect('/books'));
    }

    public function updateBook($id, $request){
        $book = Book::find($id);
        $book->title = $request['title'];
        $book->systematik = $request['systematik'];
        $book->medium = $request['medium'];
        $book->content = $request['content'];
        $book->BNR = $request['BNR'];
        $book->save();
        return;
    }

    public function destroy($id){
        $book = Book::find($id)->delete();
        return(redirect('/books/'));
    }

    public function addToCart($id){
        $book = Book::find($id);
        array_push($_SESSION['cart'],$book->id);
        dd($_SESSION['cart']);
    }
    public function borrowBook($id){
        $book = Book::find($id);
        if($book->borrowed == 1){
            dd('this book is already borrowed by someone');
        }
        else{
            $book->borrowed = 1;
            $userID = auth()->user()->id;
            $book->borrower = $userID;
            $book->save();
            $usersToBooks = new \UsersToBooks();
            $usersToBooks->user_id = $userID;
            $usersToBooks->book_id = $book->id;
            $usersToBooks->save();
        }
    }

    /**
     * creates an array filled with book objects from the session variable "cart", which is filled with ids of books
     */
    public function fillCartWithBooks(){
        $cart = array();
        foreach($_SESSION['cart'] as $item ){
            $book = Book::find($item);
            array_push($cart, $book);
        }
    }
}
