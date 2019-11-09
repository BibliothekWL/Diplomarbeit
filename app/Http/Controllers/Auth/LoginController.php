<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\User;
use Auth;
use DB;
use Hash;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use \Validator;
use Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function authenticate(){
        $json = file_get_contents('php://input');
        $jsonarray = json_decode($json,true);
        $emailAdmin=DB::table('users')->where('id',21)->get()->pluck('email');
        $pwAdmin=DB::table('users')->where('id',21)->get()->pluck('password');
        $isAdmin = true;
if($jsonarray['email'] = $emailAdmin && Hash::make($jsonarray['password']) == $pwAdmin) {
if (Auth::attempt(['email' => $jsonarray['email'], 'password' => $jsonarray['password']])) {
return json_encode(['status' => '200', 'statusMsg' => 'Logged In', 'isAdmin' => $isAdmin]);
} else {
    return json_encode(['status' => '403', 'statusMsg' => 'User does not exist']);
}
} else {
    $isAdmin = false;
    if (Auth::attempt(['email' => $jsonarray['email'], 'password' => $jsonarray['password']])) {
        return json_encode(['status' => '200', 'statusMsg' => 'Logged In', 'isAdmin' => $isAdmin]);
    } else {
        return json_encode(['status' => '403', 'statusMsg' => 'User does not exist1']);
    }
}
}

}

