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
    'description',
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
      Session::put('transaction',$transaction);
      return $transaction;
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

}
