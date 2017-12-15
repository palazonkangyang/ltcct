<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\User;
use App\Models\APVendor;
use App\Models\Expenditure;
use App\Models\PaymentVoucher;
use App\Models\PettyCashVoucher;
use Auth;
use DB;
use Hash;
use Mail;
use Input;
use Session;
use View;
use URL;
use Carbon\Carbon;

class VendorController extends Controller
{
  public function getManageVendor()
  {
    $vendor = APVendor::all();

    $payment_amount = [];
    $cash_amount = [];

    for($i = 0; $i < count($vendor); $i++)
    {
      $expenditure = Expenditure::where('supplier', $vendor[$i]->ap_vendor_id)
                     ->orderBy('supplier', 'asc')
                     ->where('status', '!=', 'draft')
                     ->sum('credit_total');

      $vendor[$i]->total = $expenditure;
    }

    //$vendor_type_list = APVendorType::getAll();

    return view('vendor.manage-vendor', [
      'vendor' => $vendor
      //'vendor_type_list' => $vendor_type_list
    ]);
  }

  public function postAddNewVendor(Request $request)
  {
    //dd($request);
    $input = array_except($request->all(), '_token');

    $vendor = APVendor::where('vendor_name', $input['vendor_name'])->first();

    if($vendor)
    {
      $request->session()->flash('error', "Vendor Name is already exist.");
      return redirect()->back()->withInput();
    }

    APVendor::create($input);

    $request->session()->flash('success', 'New Vendor is successfully added!');
    return redirect()->back();
  }

  public function getVendorDetail()
  {
    $vendor_id = $_GET['vendor_id'];
    $vendor_history = collect();

    $vendor = APVendor::find($vendor_id);

    $payment_voucher = PaymentVoucher::leftjoin('ap_vendor', 'payment_voucher.supplier_id', '=', 'ap_vendor.ap_vendor_id')
                       ->where('supplier_id', $vendor_id)
                       ->select('payment_voucher.payment_voucher_id as voucher_id','payment_voucher.voucher_no', 'payment_voucher.date',
                       'payment_voucher.description', 'payment_voucher.total_debit_amount', 'payment_voucher.total_credit_amount',
                       'ap_vendor.vendor_name as supplier')
                       ->get();

    for($i = 0; $i < count($payment_voucher); $i++)
    {
      $payment_voucher[$i]->type = "payment";
    }

    $pettycash_voucher = PettyCashVoucher::leftjoin('ap_vendor', 'pettycash_voucher.supplier_id', '=', 'ap_vendor.ap_vendor_id')
                         ->where('supplier_id', $vendor_id)
                         ->select('pettycash_voucher.pettycash_voucher_id as voucher_id','pettycash_voucher.voucher_no', 'pettycash_voucher.date',
                         'pettycash_voucher.description', 'pettycash_voucher.total_debit_amount', 'pettycash_voucher.total_credit_amount',
                         'ap_vendor.vendor_name as supplier')
                         ->get();

    for($i = 0; $i < count($pettycash_voucher); $i++)
    {
      $pettycash_voucher[$i]->type = "pettycash";
    }

    $vendor_history = $vendor_history->merge($payment_voucher);
    $vendor_history = $vendor_history->merge($pettycash_voucher);

    for($i= 0; $i < count($vendor_history); $i++)
    {
      $vendor_history[$i]->date = Carbon::parse($vendor_history[$i]->date)->format("d/m/Y");
    }

    return response()->json(array(
	    'vendor' => $vendor,
      'vendor_history' => $vendor_history
	  ));
  }

  public function postUpdateVendor(Request $request)
  {
    $input = array_except($request->all(), '_token');

    $vendor = APVendor::where('vendor_name', $input['edit_vendor_name'])
               ->where('ap_vendor_id', '!=', $input['edit_ap_vendor_id'])
               ->first();

    if($vendor)
    {
      $request->session()->flash('error', "Vendor Name is already exist.");
      return redirect()->back()->withInput();
    }

    $result = APVendor::find($input['edit_ap_vendor_id']);
    $result->vendor_name = $input['edit_vendor_name'];
    $result->description = $input['edit_description'];
    $result->save();

    $request->session()->flash('success', 'Vendor is successfully updated!');
    return redirect()->route('manage-ap-vendor-page');
  }

  public function deleteVendor(Request $request, $id)
  {
    $result = APVendor::find($id);

    if (!$result) {
      $request->session()->flash('error', 'Selected Vendor is not found.');
      return redirect()->back();
  	}

    $result->delete();

		$request->session()->flash('success', 'Selected Vendor has been deleted.');
    return redirect()->back();
  }
}
