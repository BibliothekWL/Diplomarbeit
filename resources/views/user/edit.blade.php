@extends('layouts.app')

@section('content')
    <h1>Edit User</h1>

    <div>
        <form method="POST" action="/user/edit">
            {{csrf_field()}}
            {{method_field('PATCH')}}

            <input type="text" name="name" placeholder="name" value="{{$user->name}}">
            <button type="submit">Update Credentials</button>
        </form>
    </div>
@endsection
