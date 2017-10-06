<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\User;
use App\Models\Job;
use App\Models\Expenditure;
use App\Models\APVendor;
use App\Models\PettyCashVoucher;
use App\Models\PaymentVoucher;
use App\Models\GlCode;
use Auth;
use DB;
use Hash;
use Mail;
use Input;
use Session;
use View;
use URL;
use Carbon\Carbon;

class PettyCashController extends Controller
{
  public function getManagePettyCash()
  {
    $pettycash = PettyCashVoucher::leftjoin('expenditure', 'pettycash_voucher.expenditure_id', '=', 'expenditure.expenditure_id')
                 ->select('pettycash_voucher.*', 'expenditure.reference_no as expenditure_reference_no')
                 ->get();

    $glcode = GlCode::where('glcode_id', 11)->pluck('balance');

    $expenditure = Expenditure::all();

    if(count($expenditure) > 0)
    {
      for($i = 0; $i < count($expenditure); $i++)
      {
        $cheque_amount = PaymentVoucher::where('expenditure_id', $expenditure[$i]->expenditure_id)->sum('payment_voucher.cheque_amount');
        $cash_amount = PettyCashVoucher::where('expenditure_id', $expenditure[$i]->expenditure_id)->sum('pettycash_voucher.cash_amount');
        $total_amount = $cheque_amount + $cash_amount;
        $outstanding_total = $expenditure[$i]->credit_total - $total_amount;

        $expenditure[$i]->outstanding_total = $outstanding_total;
      }
    }

    $job = Job::orderBy('created_at', 'desc')->get();

    return view('pettycash.manage-pettycash', [
      'pettycash' => $pettycash,
      'expenditure' => $expenditure,
      'job' => $job,
      'glcode' => $glcode
    ]);
  }

  public function getSupplierName(Request $request)
  {
    $expenditure_id = $_GET['expenditure_id'];

    $expenditure = Expenditure::find($expenditure_id);
    $supplier = APVendor::find($expenditure->supplier);

    $cheque_amount = PaymentVoucher::where('expenditure_id', $expenditure_id)->sum('payment_voucher.cheque_amount');
    $cash_amount = PettyCashVoucher::where('expenditure_id', $expenditure_id)->sum('pettycash_voucher.cash_amount');
    $total_amount = $cheque_amount + $cash_amount;
    $outstanding_total = $expenditure->credit_total - $total_amount;

    return response()->json(array(
      'expenditure' => $expenditure,
	    'supplier' => $supplier,
      'outstanding_total' => $outstanding_total
	  ));
  }

  public function postAddNewPettyCash(Request $request)
  {
    $input = array_except($request->all(), '_token');

    $glcode = GlCode::find(11);
    $glcode->balance -= $input['cash_amount'];
    $glcode->save();

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

        $year = date("y");

        if(count(PettyCashVoucher::all()))
        {
          $pettycash_voucher_id = PettyCashVoucher::all()->last()->pettycash_voucher_id;
          $pettycash_voucher_id = str_pad($pettycash_voucher_id + 1, 4, 0, STR_PAD_LEFT);
        }

        else
        {
          $pettycash_voucher_id = 0;
          $pettycash_voucher_id = str_pad($pettycash_voucher_id + 1, 4, 0, STR_PAD_LEFT);
        }

        $reference_no = 'PC-' . $year . $pettycash_voucher_id;

        $data = [
          "reference_no" => $reference_no,
          "date" => $newDate,
          "expenditure_id" => $input['expenditure_id'],
          "supplier_id" => $input['supplier_id'],
          "glcode_id" => 11,
          "description" => $input['description'],
          "expenditure_total" => $input['expenditure_total'],
          "cash_amount" => $input['cash_amount'],
          "cash_payee" => $input['cash_payee'],
          "job_id" => $input['job_id'],
          "remark" => $input['remark']
        ];

        PettyCashVoucher::create($data);

        $success_msg = $reference_no . ' has been created!';

        $request->session()->flash('success', $success_msg);
        return redirect()->route('manage-pettycash-page');
      }

      else
      {
        $request->session()->flash('error', "Password did not match. Please Try Again");
        return redirect()->back()->withInput();
      }
    }
  }
}
