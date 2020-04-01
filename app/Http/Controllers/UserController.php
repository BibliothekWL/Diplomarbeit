<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Contracts\View\Factory;
use \App\User;
use Illuminate\Support\Facades\Hash as Hash;
use Illuminate\View\View;


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
     * @return Factory|View
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


        //return json_encode(['status' => '400', 'statusMsg' => $jsonarray['oldPw']]);
        if (Hash::check($jsonarray['oldPw'], $user->password)) {
            $user->password = Hash::make($jsonarray['newPw']);
            $user->save();
            return json_encode(['status' => '200', 'statusMsg' => 'Success']);
        }
        else{
            return json_encode(['status' => '400', 'statusMsg' => 'Failed']);
        }
    }

    public function store()
    {
    }

    /**
     * updates a user
     * @return Factory|View
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
