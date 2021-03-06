<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\Staff;
use App\Models\User;
use App\Models\Acknowledge;
use App\Models\Dialect;
use App\Models\Race;
use App\Models\Amount;
use App\Models\MembershipFee;
use App\Models\TranslationStreet;
use Auth;
use DB;
use Hash;
use Mail;
use Input;
use Session;
use View;
use URL;
use Carbon\Carbon;

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

			else
      {
        $users[$key]['role_name'] = "Account Supervisor";
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

	public function getMemebershipFee()
	{
		$membership = MembershipFee::all();

		return view('admin.membership-fee', [
			'membership' => $membership
		]);
	}

	public function postUpdateMemebershipFee(Request $request)
	{
	  $input = array_except($request->all(), '_token');

		$membership = MembershipFee::find($input['membership_fee_id']);
		$membership->membership_fee = $input['membership_fee'];
	  $membership->save();

		$yuejuan_same_family = Session::get('yuejuan_same_family');
		$yuejuan_different_family = Session::get('yuejuan_different_family');

		if(count($yuejuan_same_family) > 0)
		{
			Session::forget('samefamily_amount');

			$samefamily_amount = [];

			for($i = 0; $i < count($yuejuan_same_family); $i++)
			{
				$amount = [];

				if(isset($yuejuan_same_family[$i]->paytill_date))
				{
					$myArray = explode('-', $yuejuan_same_family[$i]->paytill_date);

					$count = 1;
					for($j = 1; $j <= 10; $j++)
					{
						$dt = Carbon::create($myArray[0], $myArray[1], $myArray[2], 0);
						$dt = $dt->addYears($count);

						$format = Carbon::parse($dt)->format("Y-m");

						$fee = $membership->membership_fee * $j;
						$amount[$j] = number_format($fee, 2) . ' --- ' . $format;

						$count++;
					}

				}

				array_push($samefamily_amount, $amount);
			}

			Session::put('samefamily_amount', $samefamily_amount);
		}

		if(count($yuejuan_different_family) > 0)
		{
			Session::forget('differentfamily_amount');

			$differentfamily_amount = [];

			for($i = 0; $i < count($yuejuan_different_family); $i++)
			{
				$amount = [];

				if(isset($yuejuan_different_family[$i]->paytill_date))
				{
					$myArray = explode('-', $yuejuan_different_family[$i]->paytill_date);

					$count = 1;
					for($j = 1; $j <= 10; $j++)
					{
						$dt = Carbon::create($myArray[0], $myArray[1], $myArray[2], 0);
						$dt = $dt->addYears($count);

						$format = Carbon::parse($dt)->format("Y-m");

						$fee = $membership->membership_fee * $j;
						$amount[$j] = number_format($fee, 2) . ' --- ' . $format;

						$count++;
					}
				}

				array_push($differentfamily_amount, $amount);
			}

			Session::put('differentfamily_amount', $differentfamily_amount);
		}

	  $request->session()->flash('success', 'Membership Fee has been updated!');
	  return redirect()->back();
	}

	public function getMinimumAmount()
	{
		$amount = Amount::all();

		return view('admin.minimum-amount', [
			'amount' => $amount
		]);
	}

	public function postUpdateMinimumAmount(Request $request)
	{
	  $input = array_except($request->all(), '_token');

		$amount = Amount::find($input['amount_id']);
		$amount->minimum_amount = $input['minimum_amount'];
	  $amount->save();

	  $request->session()->flash('success', 'Minimum Amount has been updated!');
	  return redirect()->back();
	}

	public function getAddressStreetLists()
	{
		return view('admin.all-address-streets');
	}

	public function SearchAddress(Request $request)
	{
		$input = array_except($request->all(), '_token');

		$translation_street = TranslationStreet::where('chinese', 'like', '%' . $input['chinese'] . '%')
													->where('english', 'like', '%' . $input['english'] . '%')
													->where('address_houseno', 'like', '%' . $input['address_houseno'] . '%')
													->where('address_postal', 'like', '%' . $input['address_postal'] . '%')
													->get();

		// dd($translation_street->toArray());

		return view('admin.filter-streets', [
			'translation_street' => $translation_street
		]);
	}

	public function getAddAddress()
	{
		return view('admin.add-address');
	}

	public function postAddAddress(Request $request)
	{
		$input = array_except($request->all(), '_token');

		$check_address_postal = TranslationStreet::where('address_postal', $input['address_postal'])->first();
		$check_address = TranslationStreet::where('english', $input['english'])
										->where('address_houseno', $input['address_houseno'])
										->first();

		if($check_address_postal)
		{
			$request->session()->flash('error', 'Address Postal is already exit.');
      return redirect()->back()->withInput();
		}

		if($check_address)
		{
			$request->session()->flash('error', 'Address House No and English Street Name are already exits.');
      return redirect()->back()->withInput();
		}

		TranslationStreet::create($input);

		$request->session()->flash('success', 'New Address Street has been created!');
	  return redirect()->back();
	}

	public function getEditAddress($id)
	{
		$result = TranslationStreet::find($id);

		if (!$result) {
      return view('errors.503');
    }

		return view('admin.edit-address', [
      'address' => $result
    ]);
	}

	public function updateAddress(Request $request)
	{
		$input = array_except($request->all(), '_token');

		$check_address_postal = TranslationStreet::where('address_postal', $input['address_postal'])
														 ->where('id', '!=', $input['id'])
														 ->first();

		$check_address = TranslationStreet::where('english', $input['english'])
														->where('address_houseno', $input['address_houseno'])
												 		->where('id', '!=', $input['id'])
												 		->first();

		if($check_address_postal)
		{
			$request->session()->flash('error', 'Address Postal is already exit.');
			return redirect()->back()->withInput();
		}

		if($check_address)
		{
			$request->session()->flash('error', 'Address House No and English Street Name are already exits.');
			return redirect()->back()->withInput();
		}

		$result = TranslationStreet::find($input['id']);
		$result->chinese = $input['chinese'];
		$result->english = $input['english'];
		$result->address_houseno = $input['address_houseno'];
		$result->address_postal = $input['address_postal'];

		$result->save();

		$request->session()->flash('success', 'Address is successfully updated!');
		return redirect()->route('address-street-lists-page');
	}

	// Delete Address
	public function deleteAddress(Request $request, $id)
	{
		$result = TranslationStreet::find($id);

    if (!$result) {
      $request->session()->flash('error', 'Selected Address is not found.');
      return redirect()->back();
  	}

    $result->delete();

		$request->session()->flash('success', 'Selected Address has been deleted.');
    return redirect()->back();
	}
}
