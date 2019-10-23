@extends('layouts.app')
<html lang="eng">

<?php


?>
<body>
<h1>Personal Cart</h1>
<ul>
    @foreach ($carts as $cart)
        <li> {{$cart->book_id}} {{$cart->user_id}} </li>
    @endforeach
</ul>
<form action="/cart/checkout" method="POST">
    {{csrf_field()}}
    {{method_field('PATCH')}}
    <button type="submit">Borrow Books</button>
</form>
</body>
</html>
