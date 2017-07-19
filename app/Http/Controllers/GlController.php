<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use GlCodeGroup;
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

    dd($input);
  }

}
