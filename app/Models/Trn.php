<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Session;

class Trn extends Model
{
  protected $table = 'trn';

  protected $primaryKey = "trn_id";

  protected $fillable = [
    'focusdevotee_id',
    'festiveevent_id',
    'mod_id',
    'staff_id',
    'receipt',
    'description',
    'paid_by',
    'mode_payment',
    'trans_no',
    'nets_no',
    'cheque_no',
    'manualreceipt',
    'total_amount',
    'hjgr',
    'receipt_printing_type',
    'receipt_at',
    'trans_at'
  ];

  public static function getTransaction($trn_id){
      if(Session::has('transaction')) { Session::forget('transaction'); }
      $transaction = Trn::where('trn_id',$trn_id)->first();
      return $transaction;
  }

  public static function getTransactionOfDevotee($devotee_id,$mod_id){
      if(Session::has('transaction.xiaozai')) { Session::forget('transaction.xiaozai'); }
      $transactions = Trn::where('focusdevotee_id',$devotee_id)
                        ->where('mod_id',$mod_id)
                        ->orderBy('trn_id','desc')
                        ->get();

      Module::isXiaoZai($mod_id) ? Session::put('transaction.xiaozai',$transactions) : false;
      return $transactions;
  }

  public static function updateReceiptNoOfTransaction($trn_id){
    if(Session::has('transaction.xiaozai')) { Session::forget('transaction.xiaozai'); }

      $receipts = Rct::where('trn_id',$trn_id)
                     ->get();

      $transaction = Trn::find($trn_id);
      count($receipts) > 1 ? $transaction->receipt = $receipts->first()['receipt_no'] . ' - ' . $receipts->last()['receipt_no'] : $transaction->receipt = $receipt_no_combine = $receipts->first()['receipt_no'];
      $transaction->save();
  }

  public static function generateTransactionNo(){
    if(count(Trn::all()) > 0)
    {
      $trans_no = Trn::all()->last()->trn_id;
    }

    else {
      $trans_no = 0;
    }

    $prefix = "T";
    $trans_no += 1;
    $trans_no = sprintf("%05d", $trans_no);
    $trans_no = $prefix . $trans_no;
    return $trans_no;
  }

  public static function isCombinePrinting($trn_id){
    return Trn::where('trn_id',$trn_id)->pluck('receipt_printing_type')->first() == 'one_receipt_printing_for_same_address';
  }

  public static function isIndividualPrinting($trn_id){
    return Trn::where('trn_id',$trn_id)->pluck('receipt_printing_type')->first() == 'individual_receipt_printing';
  }

}
