
@extends('layouts.app')
<html lang="eng">

<?php


?>
<body>
<h1>books</h1>
<ul>
    {{count($cart)}}
    @foreach ($cart as $book)
        <li> {{$book->title}}</li>

    @endforeach
</ul>
<form method="get" action="">
    {{csrf_field()}}
    <button type="submit">Order Books</button>
</form>
</body>
</html>
