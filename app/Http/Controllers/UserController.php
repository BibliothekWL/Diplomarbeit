<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use \App\Book;
use \App\Author;
use App\Http\Resources\User as UserResource;
use \App\User;


class UserController extends Controller
{
    public function index(){

    }
    
    public function show(){
        $user = User::findOrFail(auth()->user()->id);
        return view('user.mybooks', compact('user'));
    }

    /**
     * renders the users current data
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(){
        $user = User::findOrFail(auth()->user()->id);
        return view('user.edit', compact('user'));
    }

    public function store(){}

    /**
     * updates a user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function update(){
        User::findOrFail(auth()->user()->id)->update(request()->all());
        $user = User::findOrFail(auth()->user()->id);
        return view('user.edit', compact('user'));
    }

    public function view(){
        $user = User::findOrFail(auth()->user()->id);
        return view('user.edit', compact('user'));
    }


}
