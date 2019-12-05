<?php

namespace App\Http\Controllers\Auth;

use App\User as User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class RegisterController extends Controller
{
    use RegistersUsers;
    use Notifiable;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'id' => ['required', 'integer', 'max:8'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     * @return \App\User
     */
    protected function create(){
        $json = file_get_contents('php://input');
        $jsonarray = json_decode($json, true);
            if (sizeof($jsonarray) != 0) {
                $user = new User();
                $user->name = $jsonarray['name'];
                $user->email = $jsonarray['email'];
                $user->password = $jsonarray['password'];
                $user->created_at = now();
                $user->updated_at = now();
                $user->save();
                $user->sendEmailVerificationNotification();
                return json_encode(['status' => 200, 'statusMessage' => 'user creation successful']);
            }
            return json_encode(['status' => 400, 'statusMessage' => 'user creation failed']);
        }
}
