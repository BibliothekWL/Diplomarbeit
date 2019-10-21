@extends('layouts.app')
<html lang="eng">

<?php


?>
<body>
<h1>books</h1>
<ul>
    @foreach ($carts as $cart)
        <li> {{$cart->book_id}} {{$cart->user_id}} </li>
    @endforeach
</ul>
</body>
</html>
