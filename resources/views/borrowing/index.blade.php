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
</body>
</html>
