<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Session;
use App\Http\Controllers\AddressController;
use Carbon\Carbon;

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
    'attended_by',
    'status',
    'cancelled_by',
    'cancelled_date',
    'receipt_at',
    'trans_at'
  ];



  public static function getTransaction($trn_id){
      $transaction = Trn::where('trn_id',$trn_id)->first();
      return $transaction;
  }

  public static function getModId($trn_id){
    return Trn::where('trn_id',$trn_id)->pluck('mod_id')->first();
  }

  public static function getTrnIdByTransNo($trans_no){
    return Trn::where('trans_no',$trans_no)->pluck('trn_id')->first();
  }

  public static function getTrn($devotee_id,$mod_id){
    $focusdevotee_id = session()->get('focus_devotee')[0]['devotee_id'];

    $transactions = collect(new Trn);
    //focus devotee
    $trn_id_list_as_payee = Trn::where('focusdevotee_id',$devotee_id)
                               ->where('mod_id',$mod_id)
                               ->orderBy('trn_id','desc')
                               ->pluck('trn_id');

    //non focus devotee
    $trn_id_list_as_non_payee = Rct::where('devotee_id',$devotee_id)
                      ->where('mod_id',$mod_id)
                      ->groupBy('trn_id')
                      ->orderBy('trn_id','desc')
                      ->pluck('trn_id');
    $trn_id_list = $trn_id_list_as_payee->merge($trn_id_list_as_non_payee)->unique();

    foreach($trn_id_list as $trn_id){

      $trn = Trn::where('trn_id',$trn_id)
                ->first();
      $receipt_list = Rct::where('trn_id',$trn_id)
                         ->get();
      $total_amount = 0;
      $receipt_no_list = [];
      $receipt_no_combine = '';
      if(Devotee::isTransactionPayee($focusdevotee_id,$trn['focusdevotee_id'])){
        count($receipt_list) > 1 ? $receipt_no_combine = $receipt_list->first()['receipt_no'] . ' - ' . $receipt_list->last()['receipt_no'] : $receipt_no_combine = $receipt_list->first()['receipt_no'] ;
        $trn['receipt'] = $receipt_no_combine;
      }

      elseif(Devotee::isNotTransactionPayee($focusdevotee_id,$trn['focusdevotee_id'])){
        foreach($receipt_list as $receipt){
          if($receipt['devotee_id'] == $focusdevotee_id){
            $total_amount = $total_amount + $receipt['amount'];
            array_push($receipt_no_list,$receipt['receipt_no']);
          }

        }

        $trn['total_amount'] = $total_amount;
        count($receipt_no_list) > 1 ? $receipt_no_combine = head($receipt_no_list) . ' - ' . last($receipt_no_list) : $receipt_no_combine = head($receipt_no_list) ;
        $trn['receipt'] = $receipt_no_combine;
      }
      $transactions->push($trn);
    }



    // foreach($rct_list as $rct){
    //   $trn = Trn::where('trn_id',$rct['trn_id'])->first();
    //
    //   if(Devotee::isTransactionPayee($rct['devotee_id'],$trn['focusdevotee_id'])){
    //     $transactions->push($trn);
    //   }
    //
    //   elseif(Devotee::isNotTransactionPayee($rct['devotee_id'],$trn['focusdevotee_id'])){
    //     $transactions->push($trn);
    //   }

    // }


    // $transactions = Trn::where('focusdevotee_id',$devotee_id)
    //                   ->where('mod_id',$mod_id)
    //                   ->orderBy('trn_id','desc')
    //                   ->get();
    // dd($transactions);
    if(Module::isXiaoZai($mod_id)){
      Session::has('transaction.xiaozai') ? Session::forget('transaction.xiaozai') : false;
      Session::put('transaction.xiaozai',$transactions);
    }

    elseif(Module::isKongDan($mod_id)){
      Session::has('transaction.kongdan') ? Session::forget('transaction.kongdan') : false;
      Session::put('transaction.kongdan',$transactions);
    }

    elseif(Module::isQiFu($mod_id)){
      Session::has('transaction.qifu') ? Session::forget('transaction.qifu') : false;
      Session::put('transaction.qifu',$transactions);
    }
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

  public static function isCombinePrinting($receipt_printing_type){
    return $receipt_printing_type == 'one_receipt_printing_for_same_address';
  }

  public static function isIndividualPrinting($receipt_printing_type){
    return $receipt_printing_type == 'individual_receipt_printing';
  }

  public static function printReceipt($trn_id,$receipt_printing_type){
    $transaction = Trn::getTransaction($trn_id);
    $mod_id = Trn::getModId($trn_id);
    $receipts = Rct::getReceipts($trn_id);
    $devotee_id = session()->get('focus_devotee')[0]['devotee_id'];


    if(Trn::isCombinePrinting($receipt_printing_type)){
      $receipts_of_family = Rct::getReceiptOfFamily($receipts);
      $paginate_receipts_of_family = Rct::paginateCombineReceipt($receipts_of_family,$mod_id);

      $receipts_of_relative = Rct::getReceiptOfRelative($receipts);
      $paginate_receipts_of_relative = Rct::paginateSingleReceipt($receipts_of_relative,$mod_id);
      $paginate_receipts = array_merge($paginate_receipts_of_family,$paginate_receipts_of_relative);
    }
    elseif(Trn::isIndividualPrinting($receipt_printing_type)){
      $paginate_receipts = Rct::paginateSingleReceipt($receipts,$mod_id);
    }

    Trn::getTrn($devotee_id,$mod_id);

      return view('receipt.index', [
        'module' => Module::getModule($mod_id),
        'transaction' => $transaction,
        'paginate_receipts' => $paginate_receipts,
        'next_event' => FestiveEvent::getNextEvent(),
        'family_address' => AddressController::getAddressByDevoteeId($devotee_id),
        'time_now' => Carbon::now('Singapore')
      ]);


  }

}
