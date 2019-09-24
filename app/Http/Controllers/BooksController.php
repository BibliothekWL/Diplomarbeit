<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use \App\Book;
use \App\Author;
use \App\AuthorsToBooks;

class BooksController extends Controller
{
    public function index(){
        $books = Book::all();

        return view('books.index', compact('books'));
    }

    public function create(){
        return view('books.create');

    }

    public function storeAuthorsToBooks($authorID,$bookID){
        $authorsToBooks = new AuthorsToBooks();
        $authorsToBooks->author_id = $authorID;
        $authorsToBooks->book_id = $bookID;
        $authorsToBooks->save();
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

    public function show(){

    }

    public function edit(){
        return view('books.edit');
    }


}
