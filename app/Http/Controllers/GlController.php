<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\User;
use App\Models\GlCodeGroup;
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

  public function getAddNewGlAccount()
  {
    return view('account.glaccountgroup');
  }

  // Add New GL Accont Group
  public function postAddNewGlAccount(Request $request)
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
      return redirect()->back();
    }
  }

}
