<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\User;
use App\Models\Job;
use App\Models\Expenditure;
use App\Models\APVendor;
use App\Models\PettyCashVoucher;
use App\Models\PettyCashVoucherItem;
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
    // Create Journal
		$year = date("y");
    $month = date("m");

    $count_payment = count(PaymentVoucher::all());
    $count_pettycash = count(PettyCashVoucher::all());
    $voucher_no = $count_payment + $count_pettycash;

    if($voucher_no)
    {
      $voucher_no_id = str_pad($voucher_no + 1, 3, 0, STR_PAD_LEFT);
    }

    else
    {
      $voucher_no_id = 0;
      $voucher_no_id = str_pad($voucher_no_id + 1, 3, 0, STR_PAD_LEFT);
    }

    $voucher_no = 'PV-' . $year . $month . $voucher_no_id;

    $pettycash_voucher = PettyCashVoucher::leftjoin('ap_vendor', 'pettycash_voucher.supplier_id', '=', 'ap_vendor.ap_vendor_id')
                       ->select('pettycash_voucher.*', 'ap_vendor.vendor_name as supplier')
                       ->orderBy('pettycash_voucher.pettycash_voucher_id')
                       ->get();

    $cash_in_hand = GlCode::select('glcode_id','type_name')->where('glcode_id', 11)->get();

    $glcode = Glcode::where('glcodegroup_id', 4)->get();

    $job = Job::orderBy('created_at', 'desc')->get();

    return view('pettycash.manage-pettycash', [
      'voucher_no' => $voucher_no,
      'pettycash_voucher' => $pettycash_voucher,
      'cash_in_hand' => $cash_in_hand,
      "glcode" => $glcode,
      'job' => $job
    ]);
  }

  // public function getSupplierName(Request $request)
  // {
  //   $expenditure_id = $_GET['expenditure_id'];
  //
  //   $expenditure = Expenditure::find($expenditure_id);
  //   $supplier = APVendor::find($expenditure->supplier);
  //
  //   $cheque_amount = PaymentVoucher::where('expenditure_id', $expenditure_id)->sum('payment_voucher.cheque_amount');
  //   $cash_amount = PettyCashVoucher::where('expenditure_id', $expenditure_id)->sum('pettycash_voucher.cash_amount');
  //   $total_amount = $cheque_amount + $cash_amount;
  //   $outstanding_total = $expenditure->credit_total - $total_amount;
  //
  //   return response()->json(array(
  //     'expenditure' => $expenditure,
	//     'supplier' => $supplier,
  //     'outstanding_total' => $outstanding_total
	//   ));
  // }

  public function getPettyCashVoucherDetail()
  {
    $pettycash_voucher_id = $_GET['pettycash_voucher_id'];
    // $pettycash_voucher_id = 1;

    $pettycash_voucher = PettyCashVoucher::leftjoin('pettycash_voucher_item', 'pettycash_voucher.pettycash_voucher_id', '=', 'pettycash_voucher_item.pettycash_voucher_id')
                       ->leftjoin('glcode', 'pettycash_voucher_item.glcode_id', '=', 'glcode.glcode_id')
                       ->leftjoin('job', 'pettycash_voucher.job_id', '=', 'job.job_id')
                       ->leftjoin('ap_vendor', 'pettycash_voucher.supplier_id', '=', 'ap_vendor.ap_vendor_id')
                       ->where('pettycash_voucher.pettycash_voucher_id', $pettycash_voucher_id)
                       ->select('pettycash_voucher.*', 'pettycash_voucher_item.*', 'glcode.type_name', 'job.job_name', 'ap_vendor.vendor_name as supplier')
                       ->get();

    $pettycash_voucher[0]->date = Carbon::parse($pettycash_voucher[0]->date)->format("d/m/Y");

    return response()->json(array(
	    'pettycash_voucher' => $pettycash_voucher,
	  ));
  }

  public function postAddNewPettyCash(Request $request)
  {
    $input = array_except($request->all(), '_token');

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
      "voucher_no" => $input['voucher_no'],
      "date" => $newDate,
      "supplier_id" => $input['supplier_id'],
      "description" => $input['description'],
      "payee" => $input['payee'],
      "total_debit_amount" => $input['total_debit_amount'],
      "total_credit_amount" => $input['total_credit_amount'],
      "job_id" => $input['job_id'],
      "remark" => $input['remark']
    ];

    $pettycash_voucher = PettyCashVoucher::create($data);

    for($i = 0; $i < count($input['glcode_id']); $i++)
    {
      $data = [
				"glcode_id" => $input['glcode_id'][$i],
				"debit_amount" => $input['debit_amount'][$i],
				"credit_amount" => $input['credit_amount'][$i],
				"pettycash_voucher_id" => $pettycash_voucher->pettycash_voucher_id
			];

			PettyCashVoucherItem::create($data);
    }

    $success_msg = $input['voucher_no'] . ' has been created!';

    $request->session()->flash('success', $success_msg);
    return redirect()->route('manage-pettycash-page');
  }
}
