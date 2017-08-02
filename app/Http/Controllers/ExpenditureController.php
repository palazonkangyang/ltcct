<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\User;
use App\Models\Expenditure;
use Auth;
use DB;
use Hash;
use Mail;
use Input;
use Session;
use View;
use URL;
use Carbon\Carbon;

class ExpenditureController extends Controller
{

  public function getManageExpenditure()
  {

    $expenditure = Expenditure::orderBy('created_at', 'desc')->get();

    return view('expenditure.manage-expenditure', [
      'expenditure' => $expenditure
    ]);
  }

  public function postAddNewExpenditure(Request $request)
  {
    $input = array_except($request->all(), '_token');

    if(isset($input['authorized_password']))
    {
      $user = User::find(Auth::user()->id);
      $hashedPassword = $user->password;

      if (Hash::check($input['authorized_password'], $hashedPassword))
      {
        // Modify fields
				if(isset($input['date']))
				{
				  $date = str_replace('/', '-', $input['date']);
				  $newDate = date("Y-m-d", strtotime($date));
				}

				else {
				  $newDate = "";
				}

        $data = [
          "reference_no" => $input['reference_no'],
          "date" => $newDate,
          "supplier" => $input['supplier'],
          "description" => $input['description'],
          "credit_total" => $input['credit_total'],
          "status" => $input['status']
        ];

        $expenditure = Expenditure::create($data);
      }

      else
      {
        $request->session()->flash('error', "Password did not match. Please Try Again");
        return redirect()->back()->withInput();
      }
    }

    if($expenditure)
    {
      $request->session()->flash('success', 'New Expenditure has been created!');
      return redirect()->route('manage-expenditure-page');
    }
  }

  public function getExpenditureDetail()
  {
    $expenditure_id = $_GET['expenditure_id'];

    $expenditure = Expenditure::find($expenditure_id);

    $expenditure->date = Carbon::parse($expenditure->date)->format("d/m/Y");

    return response()->json(array(
	    'expenditure' => $expenditure,
	  ));
  }

  public function postUpdateExpenditure(Request $request)
  {
    $input = array_except($request->all(), '_token');

    if(isset($input['edit_authorized_password']))
    {
      $user = User::find(Auth::user()->id);
      $hashedPassword = $user->password;

      if (Hash::check($input['edit_authorized_password'], $hashedPassword))
      {
        // Modify fields
				if(isset($input['edit_date']))
				{
				  $date = str_replace('/', '-', $input['edit_date']);
				  $newDate = date("Y-m-d", strtotime($date));
				}

				else {
				  $newDate = "";
				}

        $expenditure = Expenditure::find($input['edit_expenditure_id']);

        $expenditure->reference_no = $input['edit_reference_no'];
        $expenditure->date = $newDate;
        $expenditure->supplier = $input['edit_supplier'];
        $expenditure->description = $input['edit_description'];
        $expenditure->credit_total = $input['edit_credit_total'];
        $expenditure->status = $input['edit_status'];

        $result = $expenditure->save();
      }

      else
      {
        $request->session()->flash('error', "Password did not match. Please Try Again");
        return redirect()->back()->withInput();
      }
    }

    if($result)
    {
      $request->session()->flash('success', 'Expenditure has been updated!');
      return redirect()->route('manage-expenditure-page');
    }
  }
}
