<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\User;
use Auth;
use DB;
use Hash;
use Mail;
use Input;
use Session;
use View;
use URL;
use Validator;


class AuthController extends Controller
{
	public function __construct()
    {
        
    }

	public function postAuthenticate(Request $request)
	{
		$auth = false;
        $credentials = $request->only('user_name', 'password');

        $user = User::where('user_name', $credentials['user_name'])->first();
  
        if ( !$user ) {

            return response()->json([
                'auth' => $auth,
                'redirect' => ''
            ]);

        }

        if (Auth::attempt(['user_name' => $credentials['user_name'], 'password' => $credentials['password']])) {
            $auth = true;
        }

        if ($request->ajax() ) {
            return response()->json([
                'auth' => $auth,
                'redirect' => '/operator/index'
            ]);
        } 

        else if ($request->ajax() ) {
            return response()->json([
                'auth' => $auth,
                'redirect' => '/operator/index'
            ]);
        } 

        else {
            if ($user->role == 3 ||  $user->role == 4 || $user->role == 5 ) {
                return redirect()->intended(URL::route('main-page'));
            }else{
            return redirect()->intended(URL::route('all-accounts-page'));
        }
        }
	}

	public function logout()
    {
        Auth::logout();
        return redirect()->intended(URL::route('login-page'));
    }
}