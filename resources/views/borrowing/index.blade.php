@extends('layouts.app')
<html lang="eng">

<?php


?>
<body>
<h1>Borrowed Books</h1>
<ul>
    @foreach ($borrowings as $borrowing)
        <li> {{$borrowing->book_id}} {{$borrowing->user_id}} </li>
    @endforeach
</ul>

<form action="/returnBooks" method="POST">
        {{csrf_field()}}
        {{method_field('Patch')}}
        <button type="submit">Return Books</button>
</form>

</body>
</html>
