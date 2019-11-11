<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\User as UserRessource;
use App\User as User;
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
        $jsonarray = json_decode($json, true);
        if (User::where('email', $jsonarray['email'])->count() == 1) {
            $userID_raw = User::where('email', $jsonarray['email'])->pluck('id');
            $userID = explode("]", explode("[", $userID_raw)[1])[0];
            if (User::findOrFail($userID)->admin == 1) {
                $isAdmin = true;
            } else {
                $isAdmin = false;
            }
            if (Auth::attempt(['email' => $jsonarray['email'], 'password' => $jsonarray['password']])) {
                return json_encode(['status' => '200', 'statusMsg' => 'Logged In', 'isAdmin' => $isAdmin]);
            }
        }
        return json_encode(['status' => '403', 'statusMsg' => 'Email or Password is wrong']);
    }

    public function logout()
    {
        Auth::logout();
        return response()->json(['status' => '200', 'isLoggedIn' => false]);
    }
}

