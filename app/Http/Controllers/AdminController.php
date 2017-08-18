<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\Staff;
use App\Models\User;
use App\Models\Acknowledge;
use App\Models\Dialect;
use App\Models\Race;
use Auth;
use DB;
use Hash;
use Mail;
use Input;
use Session;
use View;
use URL;

class AdminController extends Controller
{

	public function index()
	{
		$staff = Staff::get()->first();

		return view('admin/homepage');
	}

    public function dashboard()
    {
      return view('admin/dashboard');
    }

	// Get All Accounts
	public function getAllAccounts()
	{
		$users = User::select("id", "role", "first_name", "last_name", "user_name")
						 ->get();

		foreach($users as $key => $val)
    {

      if($users[$key]['role'] == 1)
      {
        $users[$key]['role_name'] = "Super Admin";
      }

      else if($users[$key]['role'] == 2)
      {
        $users[$key]['role_name'] = "Admin";
      }

      else if($users[$key]['role'] == 3)
      {
        $users[$key]['role_name'] = "Supervisor";
      }

      else if($users[$key]['role'] == 4)
      {
      	$users[$key]['role_name'] = "Account Officer";
      }

      else if($users[$key]['role'] == 5)
      {
        $users[$key]['role_name'] = "Operator";
      }
  	}

		return view('admin.all-accounts', [
      'staffs' => $users
    ]);
	}

	// Add Account
	public function getAddAccount()
	{
		return view('admin.add-account');
	}

	// Create New Account
	public function postAddAccount(Request $request)
	{
		$input = array_except($request->all(), '_token');

		$validator = $this->validate($request, [
      'user_name'	=> 'required|string',
      'password' => 'required',
      'confirm_password' => 'required',
      'role' => 'required'
    ]);

    if ($validator && $validator->fails()) {
      return redirect()->back()
            ->withErrors($validator)
            ->withInput();
    }

		$user = User::where('user_name', $input['user_name'])->first();

		if($user)
		{
			$request->session()->flash('error', "Username has already exist. Please try another username.");
			return redirect()->back()->withInput();
		}

    if ($input['password'] != $input['confirm_password']) {
      $request->session()->flash('error', "Password don't match. Please Try Again");
      return redirect()->back()->withInput();
    }

		$input['password'] = Hash::make($input['password']);

		User::create($input);

		$request->session()->flash('success', 'New User successfully added!');
		return redirect()->route('all-accounts-page');
	}

	// Edit Account
	public function getEditAccount($id)
	{
    $staff = User::find($id);

    if (!$staff) {
      return view('errors.503');
    }

		return view('admin.edit-account', [
      'staff' => $staff
    ]);
	}

	// Change Account
	public function changeAccount(Request $request)
	{
		$input = Input::except('_token');

    // $validator = $this->validate($request, [
    //   'user_name'	=> 'required|string',
    //   'role' => 'required'
    // ]);
		//
    // if ($validator && $validator->fails()) {
    //   return redirect()->back()
    //         ->withErrors($validator)
    //         ->withInput();
    // }

    if(isset($input['password']) && isset($input['confirm_password']))
		{
			if ($input['password'] != $input['confirm_password']) {

	      $request->session()->flash('error', "Password don't match. Please Try Again");
	      return redirect()->back()->withInput();
	    }

			else
			{
				$staff = User::find($input['staff_id']);

		    $staff->first_name = $input['first_name'];
		    $staff->last_name = $input['last_name'];
		    $staff->user_name = $input['user_name'];
		    $staff->password = Hash::make($input['password']);
		    $staff->role = $input['role'];
		    $staff->save();
			}

			$request->session()->flash('success', 'User account has been updated!');
	    return redirect()->route('all-accounts-page');
		}

		else {
			$staff = User::find($input['staff_id']);

			$staff->first_name = $input['first_name'];
			$staff->last_name = $input['last_name'];
			$staff->user_name = $input['user_name'];
			$staff->password = Hash::make($input['password']);
			$staff->role = $input['role'];
			$staff->save();

			$request->session()->flash('success', 'User account has been updated!');
			return redirect()->route('all-accounts-page');
		}
	}

	// Delete Account
	public function deleteAccount(Request $request, $id)
	{
		$staff = User::find($id);

    if (!$staff) {
      $request->session()->flash('error', 'Selected Account is not found.');
      return redirect()->back();
  	}

    $staff->delete();

		$request->session()->flash('success', 'Selected Account has been deleted.');
    return redirect()->back();
	}

	// Admin Login
	public function login()
	{
		$acknowledge = Acknowledge::all();

		return view('admin.login', [
			'acknowledge' => $acknowledge
		]);
	}

	// Login Authentication
	public function postLogin(Request $request)
	{
		$input = Input::except('_token');

		$input = array_except($request->all(), '_token');
		$input['password'] = Hash::make($input['password']);
		$input['role'] = 5;

		$staff = User::create($input);

		return redirect()->back();
	}

  public function logout()
  {
    Auth::logout();
    return redirect()->intended(URL::route('login-page'));
  }

	public function getPreLoginNote()
	{
		$acknowledge = Acknowledge::all();

		return view('admin.acknowledge', [
			'acknowledge' => $acknowledge
		]);
	}

	public function postUpdateAcknowledge(Request $request)
	{
		$input = array_except($request->all(), '_token');

		if(!isset($input['show_prelogin']))
		{
			$input['show_prelogin'] = 0;
		}

		$acknowledge = Acknowledge::find($input['id']);
		$acknowledge->prelogin_notes = $input['prelogin_notes'];
		$acknowledge->show_prelogin = $input['show_prelogin'];

		$acknowledge->save();

		$request->session()->flash('success', 'Acknowledge has been updated!');
		return redirect()->back();

	}

	// Get Add Dialect
	public function getAddDialect()
	{
		return view('admin.add-dialect');
	}

	public function getAllDialects()
	{
		$dialects = Dialect::orderBy('dialect_id', 'desc')->get();

		return view('admin.all-dialects', [
			'dialects' => $dialects
		]);
	}

	// Add Dialect
	public function postAddDialect(Request $request)
	{
		$input = array_except($request->all(), '_token');

		$dialect = Dialect::where('dialect_name', $input['dialect_name'])->first();

		if($dialect)
		{
			$request->session()->flash('error', "Dialect Name is already exist.");
			return redirect()->back()->withInput();
		}

		Dialect::create($input);

		$request->session()->flash('success', 'New Dialect is successfully added!');
		return redirect()->back();
	}

	public function getEditDialect($id)
	{
		$dialect = Dialect::find($id);

		if (!$dialect) {
      return view('errors.503');
    }

		return view('admin.edit-dialect', [
      'dialect' => $dialect
    ]);
	}

	public function updateDialect(Request $request)
	{
		$input = array_except($request->all(), '_token');

		$dialect = Dialect::where('dialect_name', $input['dialect_name'])
							 ->where('dialect_id', '!=', $input['dialect_id'])
							 ->first();

		if($dialect)
		{
			$request->session()->flash('error', "Dialect Name is already exist.");
			return redirect()->back()->withInput();
		}

		$result = Dialect::find($input['dialect_id']);
		$result->dialect_name = $input['dialect_name'];
		$result->save();

		$request->session()->flash('success', 'Dialect is successfully updated!');
		return redirect()->route('all-dialects-page');
	}

	// Delete Dialect
	public function deleteDialect(Request $request, $id)
	{
		$dialect = Dialect::find($id);

    if (!$dialect) {
      $request->session()->flash('error', 'Selected Dialect is not found.');
      return redirect()->back();
  	}

    $dialect->delete();

		$request->session()->flash('success', 'Selected Dialect has been deleted.');
    return redirect()->back();
	}

	public function getAddRace()
	{
		return view('admin.add-race');
	}

	public function getAllRace()
	{
		$race = Race::orderBy('race_id', 'desc')->get();

		return view('admin.all-race', [
			'race' => $race
		]);
	}

	public function postAddRace(Request $request)
	{
		$input = array_except($request->all(), '_token');

		$race = Race::where('race_name', $input['race_name'])->first();

		if($race)
		{
			$request->session()->flash('error', "Race Name is already exist.");
			return redirect()->back()->withInput();
		}

		Race::create($input);

		$request->session()->flash('success', 'New Race is successfully added!');
		return redirect()->back();
	}

	public function getEditRace($id)
	{
		$race = Race::find($id);

		if (!$race) {
      return view('errors.503');
    }

		return view('admin.edit-race', [
      'race' => $race
    ]);
	}

	public function updateRace(Request $request)
	{
		$input = array_except($request->all(), '_token');

		$race = Race::where('race_name', $input['race_name'])
						->where('race_id', '!=', $input['race_id'])
						->first();

		if($race)
		{
			$request->session()->flash('error', "Race Name is already exist.");
			return redirect()->back()->withInput();
		}

		$result = Race::find($input['race_id']);
		$result->race_name = $input['race_name'];
		$result->save();

		$request->session()->flash('success', 'Race is successfully updated!');
		return redirect()->route('all-race-page');
	}

	public function deleteRace(Request $request, $id)
	{
		$race = Race::find($id);

    if (!$race) {
      $request->session()->flash('error', 'Selected Race is not found.');
      return redirect()->back();
  	}

    $race->delete();

		$request->session()->flash('success', 'Selected Race has been deleted.');
    return redirect()->back();
	}
}
