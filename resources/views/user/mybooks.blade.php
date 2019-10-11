@extends('layouts.app')
<html lang="eng">

<?php


?>
<body>
<h1>user</h1>

@if ($user->books->count())

    @foreach($user->books as $book)
        <div>
            {{$book->title}}
        </div>
    @endforeach

@endif
</body>
</html>


