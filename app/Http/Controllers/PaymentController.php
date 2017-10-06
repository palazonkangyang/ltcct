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

class PaymentController extends Controller
{
  public function getManagePayment()
  {
    $payment_voucher = PaymentVoucher::leftjoin('expenditure', 'payment_voucher.expenditure_id', '=', 'expenditure.expenditure_id')
                       ->leftjoin('ap_vendor', 'payment_voucher.supplier_id', '=', 'ap_vendor.ap_vendor_id')
                       ->leftjoin('glcode', 'payment_voucher.cheque_account', '=', 'glcode.glcode_id')
                       ->select('payment_voucher.*', 'expenditure.reference_no as expenditure_reference_no', 'ap_vendor.vendor_name',
                       'glcode.type_name as cheque_account')
                       ->get();

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

    return view('payment.manage-payment', [
      'payment_voucher' => $payment_voucher,
      'expenditure' => $expenditure,
      'job' => $job
    ]);
  }

  public function getBalance(Request $request)
  {
    $glcode_id = $_GET['glcode_id'];

    $glcode = GlCode::find($glcode_id);

    return response()->json(array(
      'glcode' => $glcode
	  ));
  }

  public function postAddNewPayment(Request $request)
  {
    $input = array_except($request->all(), '_token');

    $glcode = GlCode::find($input['cheque_account']);
    $glcode->balance -= $input['cheque_amount'];
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

        if(count(PaymentVoucher::all()))
        {
          $payment_voucher_id = PaymentVoucher::all()->last()->payment_voucher_id;
          $payment_voucher_id = str_pad($payment_voucher_id + 1, 4, 0, STR_PAD_LEFT);
        }

        else
        {
          $payment_voucher_id = 0;
          $payment_voucher_id = str_pad($payment_voucher_id + 1, 4, 0, STR_PAD_LEFT);
        }

        $reference_no = 'PV-' . $year . $payment_voucher_id;

        $data = [
          "reference_no" => $reference_no,
          "date" => $newDate,
          "expenditure_id" => $input['expenditure_id'],
          "supplier_id" => $input['supplier_id'],
          "description" => $input['description'],
          "expenditure_total" => $input['expenditure_total'],
          "cheque_no" => $input['cheque_no'],
          "cheque_account" => $input['cheque_account'],
          "issuing_banking" => $input['issuing_banking'],
          "cheque_from" => $input['cheque_from'],
          "cheque_amount" => $input['cheque_amount'],
          "job_id" => $input['job_id'],
          "remark" => $input['remark'],
        ];

        PaymentVoucher::create($data);

        $success_msg = $reference_no . ' has been created!';

        $request->session()->flash('success', $success_msg);
        return redirect()->route('manage-payment-page');
      }

      else
      {
        $request->session()->flash('error', "Password did not match. Please Try Again");
        return redirect()->back()->withInput();
      }
    }

  }
}
