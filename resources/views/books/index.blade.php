@extends('layouts.app')
<html lang="eng">

<?php


?>
<body>
<h1>books</h1>
<ul>
    @foreach ($books as $book)
        <li> {{$book->title}}
            <form method="get" action="/books/{{$book->id}}/edit">
                {{csrf_field()}}
                <button type="submit">Borrow the Book</button>
            </form>
        </li>
    @endforeach
</ul>
</body>
</html>
