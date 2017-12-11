<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\GLCode;

class Rct extends Model
{
  protected $table = 'rct';

  protected $primaryKey = "rct_id";

  protected $fillable = [
    'trn_id',
    'devotee_id',
    'glcode_id',
    'mod_id',
    'receipt_no',
    'hjgr',
    'amount',
    'item_description',
    'status',
    'cancelled_by',
    'cancelled_date',
    'trans_date'
  ];

  public static function getReceipts($trn_id){

    $receipts = Rct::where('trn_id',$trn_id)->get();

    foreach($receipts as $index=>$receipt){
      if(Module::isXiaoZai($receipt['mod_id'])){
        $receipt['type'] = RctXiaoZai::getType($receipt['rct_id']);
      }
    }

    return $receipts;
  }

  public static function getGLCode($devotee_id,$mod_id){

    $devoteeIsMember = Devotee::isMember($devotee_id);

    if(Module::isXiangYou($mod_id)){

    }

    elseif(Module::isCiJi($mod_id)){

    }

    elseif(Module::isYueJuan($mod_id)){

    }

    elseif(Module::isZhuXueJin($mod_id)){

    }

    elseif(Module::isXiaoZai($mod_id)){
      return $devoteeIsMember ? 118 : 120;
    }

    elseif(Module::isQianFo($mod_id)){

    }

    elseif(Module::isDaBei($mod_id)){

    }

    elseif(Module::isYaoShi($mod_id)){

    }

    elseif(Module::isQiFu($mod_id)){

    }

    elseif(Module::isKongDan($mod_id)){

    }

    elseif(Module::isPuDu($mod_id)){

    }

    elseif(Module::isChaoDu($mod_id)){

    }

    elseif(Module::isShouSheng($mod_id)){

    }
  }

  public static function generateReceiptNo($mod_id,$glcode_id){

    if(Rct::hasReceiptOfModule($mod_id)){
      $last_receipt_no = Rct::getLastReceiptNoOfModule($mod_id);
      $last_receipt_no_running_number = substr($last_receipt_no, -4);
    }

    elseif(Rct::hasReceiptOfModule($mod_id) == false) {
      $last_receipt_no = null;
      $last_receipt_no_running_number = 0;
    }

    $receipt_prefix = GLCode::getReceiptPrefixByGLCodeId($glcode_id);
    $year = date('y');
    $next_receipt_no_number = $last_receipt_no_running_number + 1;
    $next_receipt_no_number = str_pad($next_receipt_no_number, 4, 0, STR_PAD_LEFT);
    $receipt_no = $receipt_prefix . $year . $next_receipt_no_number;

    return $receipt_no;

  }

  public static function hasReceiptOfModule($mod_id){
    return (count(Rct::where('mod_id',$mod_id)->get()) > 0) ? true : false ;
  }

  public static function getLastReceiptNoOfModule($mod_id){
    return Rct::where('mod_id',$mod_id)->orderBy('rct_id','desc')->pluck('receipt_no')->first();
  }

  public static function paginateSingleReceipt($receipts){

    // unique devotee
    $receipts_array = [];

    $unique_devotee_ids = $receipts->unique('devotee_id')->pluck('devotee_id');
    foreach($unique_devotee_ids as $index1=>$unique_devotee_id){
      $receipts_array[$index1]['devotee'] = Devotee::getDevotee($unique_devotee_id);

      $index_count = 0;
      foreach($receipts as $index2=>$receipt){
        if(Devotee::isSameDevoteeId($unique_devotee_id,$receipt['devotee_id'])){
           $receipts_array[$index1]['receipt'][$index_count] = $receipt;
           $index_count++;
        }
      }
    }

    $receipts_array[0]['receipt'] = array_chunk($receipts_array[0]['receipt'],6);

    //dd($receipts_array);



  }

  public static function paginateCombineReceiptOfFamily($receipts){
    $receipts_of_family = $receipts->filter(function ($value, $key) {
      if(Devotee::isSameFamily(session()->get('focus_devotee')[0]['devotee_id'],$value['devotee_id'])){
        return $value;
      }
    });

    // to be implement for combine algorithm

    return $receipts_of_family;
  }

  public static function paginateSingleReceiptOfFamily($receipts){
    $receipts_of_family = $receipts->filter(function ($value, $key) {
      if(Devotee::isSameFamily(session()->get('focus_devotee')[0]['devotee_id'],$value['devotee_id'])){
        return $value;
      }
    });

    $receipts_collect = collect(new Rct);
    dd($receipts_collect);

    $unique_devotee_ids = $receipts_of_family->unique('devotee_id')->pluck('devotee_id');

    foreach($receipts_of_family as $index=>$receipt){

    }

    // foreach($unique_devotee_ids){
    //
    // }

    return $receipts_of_family;
  }

  public static function paginateSingleReceiptOfRelative($receipts){
    $receipts_of_relatives = $receipts->filter(function ($value, $key) {
      if(Devotee::isRelative(session()->get('focus_devotee')[0]['devotee_id'],$value['devotee_id'])){
        return $value;
      }
    });

    return $receipts_of_relatives;
  }




}
