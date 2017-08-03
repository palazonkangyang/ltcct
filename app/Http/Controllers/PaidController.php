<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\User;
use App\Models\GlCode;
use App\Models\Job;
use App\Models\Expenditure;
use App\Models\Paid;
use Auth;
use DB;
use Hash;
use Mail;
use Input;
use Session;
use View;
use URL;
use Carbon\Carbon;

class PaidController extends Controller
{

  public function getManagePaid()
  {
    $paid = Paid::join('expenditure', 'expenditure.expenditure_id', '=', 'paid.expenditure_id')
            ->select('paid.*', 'expenditure.reference_no as expenditure_reference_no')
            ->get();

    $glcode = GlCode::where('glcodegroup_id', 14)->get();
    $job = Job::orderBy('created_at', 'desc')->get();
    $expenditure = Expenditure::orderBy('created_at', 'desc')->get();

    return view('paid.manage-paid', [
      'paid' => $paid,
      'job' => $job,
      'glcode' => $glcode,
      'expenditure' => $expenditure
    ]);
  }

  public function postAddNewPaid(Request $request)
  {
    $input = array_except($request->all(), '_token');

    // dd($input);

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

        if(isset($input['transaction_date']))
				{
				  $transaction_date = str_replace('/', '-', $input['transaction_date']);
				  $newTransactionDate = date("Y-m-d", strtotime($transaction_date));
				}

				else {
				  $newTransactionDate = $input['transaction_date'];
				}

        if(isset($input['cheque_date']))
				{
				  $cheque_date = str_replace('/', '-', $input['cheque_date']);
				  $newChequeDate = date("Y-m-d", strtotime($cheque_date));
				}

				else {
				  $newChequeDate = $input['cheque_date'];
				}

        if(isset($input['cash_date']))
				{
				  $cash_date = str_replace('/', '-', $input['cash_date']);
				  $newCashDate = date("Y-m-d", strtotime($cash_date));
				}

				else {
				  $newCashDate = $input['cash_date'];
				}

        $data = [
          "reference_no" => $input['reference_no'],
          "date" => $newDate,
          "expenditure_id" => $input['expenditure_id'],
          "supplier" => $input['supplier'],
          "description" => $input['description'],
          "expenditure_total" => $input['expenditure_total'],
          "outstanding_total" => $input['outstanding_total'],
          "amount" => $input['amount'],
          "status" => $input['status'],
          "type" => $input['type'],
          "cash_voucher_no" => $input['cash_voucher_no'],
          "cash_payee" => $input['cash_payee'],
          "transaction_date" => $newTransactionDate,
          "cash_account" => $input['cash_account'],
          "cash_amount" => $input['cash_amount'],
          "cheque_no" => $input['cheque_no'],
          "cheque_account" => $input['cheque_account'],
          "cheque_receipt" => $input['cheque_receipt'],
          "issuing_banking" => $input['issuing_banking'],
          "cheque_from" => $input['cheque_from'],
          "customer" => $input['customer'],
          "cheque_amount" => $input['cheque_amount'],
          "currency" => $input['currency'],
          "cheque_date" => $newChequeDate,
          "cash_date" => $newCashDate,
          "job_id" => $input['job_id'],
          "gl_description" => $input['gl_description'],
          "remark" => $input['remark'],
        ];

        $paid = Paid::create($data);
      }

      else
      {
        $request->session()->flash('error', "Password did not match. Please Try Again");
        return redirect()->back()->withInput();
      }
    }

    if($paid)
    {
      $request->session()->flash('success', 'New Paid has been created!');
      return redirect()->route('manage-paid-page');
    }

  }

  public function getPaidDetail()
  {
    $paid_id = $_GET['paid_id'];
    // $paid_id = 4;

    $paid = Paid::find($paid_id);

    if(isset($paid->date))
    {
      $paid->date = Carbon::parse($paid->date)->format("d/m/Y");
    }

    if(isset($paid->transaction_date))
    {
      $paid->transaction_date = Carbon::parse($paid->transaction_date)->format("d/m/Y");
    }

    if(isset($paid->cheque_date))
    {
      $paid->cheque_date = Carbon::parse($paid->cheque_date)->format("d/m/Y");
    }

    if(isset($paid->cash_date))
    {
      $paid->cash_date = Carbon::parse($paid->cash_date)->format("d/m/Y");
    }

    return response()->json(array(
	    'paid' => $paid,
	  ));
  }

  public function postUpdatePaid(Request $request)
  {
    $input = array_except($request->all(), '_token');

    // dd($input);

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
				  $newDate = $input['edit_date'];
				}

        // Modify fields
				if(isset($input['edit_transaction_date']))
				{
				  $transaction_date = str_replace('/', '-', $input['edit_transaction_date']);
				  $newTransactionDate = date("Y-m-d", strtotime($transaction_date));
				}

				else {
				  $newTransactionDate = $input['edit_transaction_date'];
				}

        // Modify fields
				if(isset($input['edit_cheque_date']))
				{
				  $cheque_date = str_replace('/', '-', $input['edit_cheque_date']);
				  $newChequeDate = date("Y-m-d", strtotime($cheque_date));
				}

				else {
				  $newChequeDate = $input['edit_cheque_date'];
				}

        // Modify fields
				if(isset($input['edit_cash_date']))
				{
				  $cash_payee = str_replace('/', '-', $input['edit_cash_date']);
				  $newCashDate = date("Y-m-d", strtotime($cash_payee));
				}

				else {
				  $newCashDate = $input['edit_cash_date'];
				}

        if(empty($input['edit_cheque_account']))
        {
          $input['edit_cheque_account'] = null;
        }

        $paid = Paid::find($input['edit_paid_id']);

        $paid->reference_no = $input['edit_reference_no'];
        $paid->date = $newDate;
        $paid->expenditure_id = $input['edit_expenditure_id'];
        $paid->supplier = $input['edit_supplier'];
        $paid->description = $input['edit_description'];
        $paid->expenditure_total = $input['edit_expenditure_total'];
        $paid->outstanding_total = $input['edit_outstanding_total'];
        $paid->amount = $input['edit_amount'];
        $paid->status = $input['edit_status'];
        $paid->type = $input['edit_type'];

        // Cash fields
        $paid->cash_voucher_no = $input['edit_cash_voucher_no'];
        $paid->transaction_date = $newTransactionDate;
        $paid->cash_account = $input['edit_cash_account'];
        $paid->cash_amount = $input['edit_cash_amount'];
        $paid->cash_payee = $input['edit_cash_payee'];

        // Cheque fields
        $paid->cheque_no = $input['edit_cheque_no'];
        $paid->cheque_account = $input['edit_cheque_account'];
        $paid->cheque_voucher_no = $input['edit_cheque_voucher_no'];
        $paid->cheque_payee = $input['edit_cheque_payee'];
        $paid->cheque_date = $newChequeDate;
        $paid->cash_date = $newCashDate;

        $paid->job_id = $input['edit_job_id'];
        $paid->gl_description = $input['edit_gl_description'];
        $paid->remark = $input['edit_remark'];

        $result = $paid->save();
      }

      else
      {
        $request->session()->flash('error', "Password did not match. Please Try Again");
        return redirect()->back()->withInput();
      }

    }

    if($result)
    {
      $request->session()->flash('success', 'Paid has been updated!');
      return redirect()->route('manage-paid-page');
    }
  }

}
