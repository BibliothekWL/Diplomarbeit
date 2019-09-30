@extends('layout')

@section('content')
    <h1>Edit Books</h1>

    <div>
        <form method="" action="/books">
            <input type="text" name="title" placeholder="title" value="{{$book->title}}">
            <input type="text" name="content" placeholder="content" value="{{$book->content}}">
            <input type="text" name="systematik" placeholder="systematik" value="{{$book->systematik}}">
            <input type="text" name="medium" placeholder="medium" value="{{$book->medium}}">
            <input type="number" name="BNR" placeholder="BNR" value="{{$book->BNR}}">

            <button type="submit">Update Book</button>
        </form>
    </div>
@endsection
