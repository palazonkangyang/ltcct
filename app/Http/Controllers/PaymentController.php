<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\User;
use App\Models\Job;
use App\Models\Expenditure;
use App\Models\APVendor;
use App\Models\PettyCashVoucher;
use App\Models\PaymentVoucher;
use App\Models\PaymentVoucherItem;
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

    $payment_voucher = PaymentVoucher::leftjoin('ap_vendor', 'payment_voucher.supplier_id', '=', 'ap_vendor.ap_vendor_id')
                       ->leftjoin('glcode', 'payment_voucher.cheque_account', '=', 'glcode.glcode_id')
                       ->select('payment_voucher.*', 'ap_vendor.vendor_name as supplier', 'glcode.type_name as cheque_account')
                       ->orderBy('payment_voucher.payment_voucher_id')
                       ->get();

    foreach($payment_voucher as $index_pv=>$pv){

      $glcode_list = PaymentVoucherItem::getGlCodeIdListByPaymentVoucherId($pv['payment_voucher_id']);
      $list['gl_description'] = [];
      foreach($glcode_list as $index_gi=>$glcode){
        array_push($list['gl_description'] , GlCode::getChineseNameByGlCodeId($glcode['glcode_id']));

      }
      $pv['gl_description_list'] = $list['gl_description'];
    }


    $glcode = Glcode::where('glcodegroup_id', 4)->get();
    $job = Job::all();
    return view('payment.manage-payment', [
      'voucher_no' => $voucher_no,
      'payment_voucher' => $payment_voucher,
      'job' => $job,
      'glcode' => $glcode,
      'cheque_account_list' => GlCode::getChequeAccountList()
    ]);
  }

  public function getBankName(Request $request)
  {
    $glcode_id = $_GET['glcode_id'];

    $glcode = GlCode::find($glcode_id);
    $type_name = $glcode->type_name;
    $balance = GlCode::getBalance($glcode_id);
    $glcode_id = $glcode->glcode_id;

    return response()->json(array(
      'type_name' => $type_name,
      'balance' => $balance,
      'glcode_id' => $glcode_id
	  ));
  }

  public function getPaymentVoucherDetail()
  {
    $payment_voucher_id = $_GET['payment_voucher_id'];

    $payment_voucher = PaymentVoucher::leftjoin('payment_voucher_item', 'payment_voucher.payment_voucher_id', '=', 'payment_voucher_item.payment_voucher_id')
                       ->leftjoin('glcode as g1', 'payment_voucher_item.glcode_id', '=', 'g1.glcode_id')
                       ->leftjoin('glcode as g2', 'payment_voucher.cheque_account', '=', 'g2.glcode_id')
                       ->leftjoin('job', 'payment_voucher.job_id', '=', 'job.job_id')
                       ->leftjoin('ap_vendor', 'payment_voucher.supplier_id', '=', 'ap_vendor.ap_vendor_id')
                       ->where('payment_voucher.payment_voucher_id', $payment_voucher_id)
                       ->select('payment_voucher.*', 'payment_voucher_item.*', 'g1.type_name as type_name', 'g2.type_name as cheque_account', 'job.job_name',
                        'ap_vendor.vendor_name as supplier')
                       ->get();

    $payment_voucher[0]->date = Carbon::parse($payment_voucher[0]->date)->format("d/m/Y");

    return response()->json(array(
	    'payment_voucher' => $payment_voucher,
	  ));
  }

  public function postAddNewPayment(Request $request)
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
      "cheque_no" => $input['cheque_no'],
      "cheque_account" => $input['cheque_account'],
      "total_debit_amount" => $input['total_debit_amount'],
      "total_credit_amount" => $input['total_credit_amount'],
      "issuing_banking" => $input['issuing_banking'],
      "cheque_from" => $input['cheque_from'],
      "job_id" => $input['job_id'],
      "remark" => $input['remark']
    ];

    $payment_voucher = PaymentVoucher::create($data);

    for($i = 0; $i < count($input['glcode_id']); $i++)
    {
      $data = [
				"glcode_id" => $input['glcode_id'][$i],
				"debit_amount" => $input['debit_amount'][$i],
				"credit_amount" => $input['credit_amount'][$i],
				"payment_voucher_id" => $payment_voucher->payment_voucher_id
			];

			PaymentVoucherItem::create($data);
    }

    $success_msg = $input['voucher_no'] . ' has been created!';

    $request->session()->flash('success', $success_msg);
    return redirect()->route('manage-payment-page');
  }
}
