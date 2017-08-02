<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\User;
use App\Models\GlCodeGroup;
use App\Models\GlCode;
use App\Models\Job;
use Auth;
use DB;
use Hash;
use Mail;
use Input;
use Session;
use View;
use URL;
use Carbon\Carbon;

class GlController extends Controller
{

  // Get GL Account Group
  public function getAddNewGlAccountGroup()
  {
    $glaccountgroup = GlCodeGroup::orderBy('created_at', 'desc')->get();

    return view('account.glaccountgroup', [
      'glaccountgroup' => $glaccountgroup
    ]);
  }

  // Add New GL Account Group
  public function postAddNewGlAccountGroup(Request $request)
  {
    $input = array_except($request->all(), '_token');

    if(isset($input['authorized_password']))
    {
      $user = User::find(Auth::user()->id);
      $hashedPassword = $user->password;

      if (Hash::check($input['authorized_password'], $hashedPassword)) {
        $data = [
          "name" => $input['name'],
          "description" => $input['description'],
          "balancesheet_side" => $input['balancesheet_side'],
          "status" => $input['status']
        ];

        $glcodegroup = GlCodeGroup::create($data);
      }

      else {
        $request->session()->flash('error', "Password did not match. Please Try Again");
        return redirect()->back()->withInput();
      }
    }

    if($glcodegroup)
    {
      $request->session()->flash('success', 'New GL account group has been created!');
      return redirect()->route('new-glaccount-group-page');
    }
  }

  // Get GL Account Group
  public function EditGlAccountGroup(Request $request)
  {
    $glaccountgroup_id = $_GET['glaccountgroup_id'];

    $glaccountgroup = GlCodeGroup::find($glaccountgroup_id);

    return response()->json(array(
			'glaccountgroup' => $glaccountgroup
		));
  }

  // Update GL Account Group
  public function UpdateGlAccountGroup(Request $request)
  {
    $input = array_except($request->all(), '_token');

    if(isset($input['authorized_password']))
    {
      $user = User::find(Auth::user()->id);
      $hashedPassword = $user->password;

      if (Hash::check($input['authorized_password'], $hashedPassword)) {

        $glcodegroup = GlCodeGroup::find($input['glcodegroup_id']);

        $glcodegroup->name = $input['edit_name'];
        $glcodegroup->description = $input['edit_description'];
        $result = $glcodegroup->save();
      }

      else {
        $request->session()->flash('error', "Password did not match. Please Try Again");
        return redirect()->back()->withInput();
      }
    }

    if($result)
    {
      $request->session()->flash('success', 'GL account group has been updated!');
      return redirect()->route('new-glaccount-group-page');
    }
  }

  // Get GL Account
  public function getAddNewGlAccount()
  {

    $glaccount = GlCode::leftjoin('glcodegroup', 'glcodegroup.glcodegroup_id', '=', 'glcode.glcodegroup_id')
                 ->select('glcode.*', 'glcodegroup.name as glcodegroup_name')
                 ->orderBy('created_at', 'desc')->get();

    $job = Job::orderBy('created_at', 'desc')->get();

    $glaccountgroup = GlCodeGroup::select('glcodegroup_id', 'name')
                      ->orderBy('name', 'asc')
                      ->get();

    return view('account.glaccount', [
      'job' => $job,
      'glaccount' => $glaccount,
      'glaccountgroup' => $glaccountgroup
    ]);
  }

  // Add New GL Account
  public function postAddNewGlAccount(Request $request)
  {
    $input = array_except($request->all(), '_token');

    if(isset($input['authorized_password']))
    {
      $user = User::find(Auth::user()->id);
      $hashedPassword = $user->password;

      if (Hash::check($input['authorized_password'], $hashedPassword)) {
        $data = [
          "accountcode" => $input['accountcode'],
          "type_name" => $input['type_name'],
          "chinese_name" => $input['chinese_name'],
          "price" => $input['price'],
          "job_id" => $input['job_id'],
          "next_sn_number" => $input['next_sn_number'],
          "receipt_prefix" => $input['receipt_prefix'],
          "glcodegroup_id" => $input['glcodegroup_id']
        ];

        $glcode = GlCode::create($data);
      }

      else {
        $request->session()->flash('error', "Password did not match. Please Try Again");
        return redirect()->back()->withInput();
      }
    }

    if($glcode)
    {
      $request->session()->flash('success', 'New GL account group has been created!');
      return redirect()->back();
    }

  }

  // Get GL Account
  public function EditGlAccount(Request $request)
  {
    $glaccount_id = $_GET['glaccount_id'];

    $glaccount = GlCode::find($glaccount_id);

    return response()->json(array(
			'glaccount' => $glaccount
		));
  }

  // Update Gl Account
  public function UpdateGlAccount(Request $request)
  {
    $input = array_except($request->all(), '_token');

    // dd($input);

    if(isset($input['authorized_password']))
    {
      $user = User::find(Auth::user()->id);
      $hashedPassword = $user->password;

      if (Hash::check($input['authorized_password'], $hashedPassword)) {

        $glcode = GlCode::find($input['edit_glcode_id']);

        $glcode->accountcode = $input['edit_accountcode'];
        $glcode->type_name = $input['edit_type_name'];
        $glcode->chinese_name = $input['edit_chinese_name'];
        $glcode->price = $input['edit_price'];
        $glcode->next_sn_number = $input['edit_next_sn_number'];
        $glcode->receipt_prefix = $input['edit_receipt_prefix'];
        $result = $glcode->save();
      }

      else {
        $request->session()->flash('error', "Password did not match. Please Try Again");
        return redirect()->back()->withInput();
      }
    }

    if($result)
    {
      $request->session()->flash('success', 'GL account has been updated!');
      return redirect()->route('new-glaccount-page');
    }
  }

  // Get Chart All all-accounts
  public function getChartAllAccounts()
  {
    $glcodegroup = GlCodeGroup::orderBy('glcodegroup_id', 'asc')
                   ->get();

    $glcode = GlCode::orderby('glcodegroup_id', 'asc')
              ->get();

    return view('account.chart-all-accounts', [
      'glcodegroup' => $glcodegroup,
      'glcode' => $glcode
    ]);
  }

}
