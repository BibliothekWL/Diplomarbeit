<?php

namespace App\Http\Controllers;

use Illuminate\Hashing\BcryptHasher;
use Illuminate\Http\Request;

use \App\Book;
use \App\Author;
use App\Http\Resources\User as UserResource;
use \App\User;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
    public function index()
    {

    }

    public function show()
    {
        $user = User::findOrFail(auth()->user()->id);
        return view('user.mybooks', compact('user'));
    }

    /**
     * renders the users current data
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editName()
    {
        $json = file_get_contents('php://input');
        $jsonarray = json_decode($json, true);
        $user = User::findOrFail(auth()->user()->id);
            $user->name = $jsonarray['name'];
            $user->save();
        return json_encode(['status' => '200', 'statusMsg' => 'Success']);
    }
    public function editPassword()
    {
        $json = file_get_contents('php://input');
        $jsonarray = json_decode($json, true);
        $user = User::findOrFail(auth()->user()->id);
        if ($user->password === Hash::make($jsonarray['oldPw'])){
            $user->password = Hash::make($jsonarray['newPw']);
            $user->save();
        }
        else{
            return json_encode(['status' => '400', 'statusMsg' => 'Failed']);
        }
        return json_encode(['status' => '200', 'statusMsg' => 'Success']);
    }

    public function store()
    {
    }

    /**
     * updates a user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function update()
    {
        User::findOrFail(auth()->user()->id)->update(request()->all());
        $user = User::findOrFail(auth()->user()->id);
        return view('user.edit', compact('user'));
    }

    public function view()
    {
        $user = User::findOrFail(auth()->user()->id);
        return view('user.edit', compact('user'));
    }


}
