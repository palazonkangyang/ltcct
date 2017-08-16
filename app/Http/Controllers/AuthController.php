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
			$request->session()->flash('error', 'Username doesn\'t exit!');
			return redirect()->back();
    }

    elseif (Auth::attempt(['user_name' => $credentials['user_name'], 'password' => $credentials['password']])) {
      $auth = true;
    }

		else {
			$request->session()->flash('error', 'Username and Password didn\'t match!');
			return redirect()->back();
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

        // else {
        //     if ($user->role == 3 ||  $user->role == 4 || $user->role == 5 ) {
        //         return redirect()->intended(URL::route('main-page'));
        //     }else{
        //     return redirect()->intended(URL::route('main-page'));
        // }
        // }
				else{
					return redirect()->intended(URL::route('main-page'));
				}
	}

	public function logout()
    {
			// remove session data
			if(Session::has('focus_devotee'))
			{
				Session::forget('focus_devotee');
			}

			if(Session::has('devotee_lists'))
			{
				Session::forget('devotee_lists');
			}

			if(Session::has('xianyou_same_family'))
			{
				Session::forget('xianyou_same_family');
			}

			if(Session::has('xianyou_different_family'))
			{
				Session::forget('xianyou_different_family');
			}

			if(Session::has('setting_samefamily'))
			{
				Session::forget('setting_samefamily');
			}

			if(Session::has('setting_differentfamily'))
			{
				Session::forget('setting_differentfamily');
			}

			if(Session::has('optionaladdresses'))
			{
				Session::forget('optionaladdresses');
			}

			if(Session::has('optionalvehicles'))
			{
				Session::forget('optionalvehicles');
			}

			if(Session::has('specialRemarks'))
			{
				Session::forget('specialRemarks');
			}

			if(!Session::has('receipts'))
			{
				Session::put('receipts', $receipts);
			}

      Auth::logout();
      return redirect()->intended(URL::route('login-page'));
    }
}
