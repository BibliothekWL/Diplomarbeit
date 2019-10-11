@extends('layouts.app')

@section('content')
    <h1>Edit Books</h1>

    <div>
        <form method="POST" action="/books/{{$book->id}}">
            {{method_field('PATCH')}}
            {{csrf_field()}}
            <input type="text" name="title" placeholder="title" value="{{$book->title}}">
            <input type="text" name="content" placeholder="content" value="{{$book->content}}">
            <input type="text" name="systematik" placeholder="systematik" value="{{$book->systematik}}">
            <input type="text" name="medium" placeholder="medium" value="{{$book->medium}}">
            <input type="number" name="BNR" placeholder="BNR" value="{{$book->BNR}}">

            <button type="submit">Update Book</button>
        </form>
        <form method="post" action="/books/{{$book->id}}">
            {{method_field('DELETE')}}
            {{csrf_field()}}
            <button type="submit">Delete Book</button>
        </form>

        <form method="post" action="/books/{{$book->id}}/borrow">
            {{method_field('PATCH')}}
            {{csrf_field()}}
            <button type="submit">Place in shopping cart</button>
        </form>
    </div>
@endsection
