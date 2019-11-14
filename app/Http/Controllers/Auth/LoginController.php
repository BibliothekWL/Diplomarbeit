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
                session(['id' => $userID]);
                return json_encode(['status' => '200', 'statusMsg' => 'Logged In', 'isAdmin' => $isAdmin, 'isLoggedIn' => true]);
            }
        }
        return json_encode(['status' => '403', 'statusMsg' => 'Email or Password is wrong', 'isLoggedIn' => false]);
    }

    public function logout()
    {
        session()->forget('id');
        session()->flush();
        Auth::logout();
        return response()->json(['status' => '200','statusMsg' => 'You have been logged out', 'isLoggedIn' => false]);
    }

//    public function addIsLoggedIn($input){
//        $inputDC = json_decode($input);
//        if(ctype_digit(auth()->user()->id)){
//            return json_encode($inputDC, 'isLoggedIn' => true)
//        }
//        else{
//            return(json++{isLoggedIn = false})
//        }
//}

}

