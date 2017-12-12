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
        $receipt['type_chinese_name'] = RctXiaoZai::getTypeChineseName($receipt['rct_id']);
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

  public static function OLD_paginateSingleReceipt($receipts){
    $receipts_array = [];
    $unique_devotee_ids = $receipts->unique('devotee_id')->pluck('devotee_id');
    foreach($unique_devotee_ids as $unique_devotee_id_index=>$unique_devotee_id){
      $receipts_array[$unique_devotee_id_index]['devotee'] = Devotee::getDevotee($unique_devotee_id);
      $index_count = 0;
      foreach($receipts as $receipt_index=>$receipt){
        if(Devotee::isSameDevoteeId($unique_devotee_id,$receipt['devotee_id'])){
           $receipts_array[$unique_devotee_id_index]['receipt'][$index_count] = $receipt;
           $receipts_array[$unique_devotee_id_index]['receipt'][$index_count]['sn_no'] = $index_count + 1 ;
           $index_count++;
        }
      }
    }



    $paginate_receipts = [];
    foreach($receipts_array as $receipt_index=>$receipt){

      if(count($receipt['receipt']) > 5){
        $chunk_receipts = array_chunk($receipt['receipt'],5);
        foreach($chunk_receipts as $chunk_index=>$chunk_receipt){
          $total_amount = 0;
          foreach($chunk_receipt as $individual_receipt_index=>$individual_receipt){
            $total_amount = $total_amount + $individual_receipt['amount'];
          }
          $list['devotee'] = $receipt['devotee'];
          $list['receipt'] = $chunk_receipt;
          $list['total_amount'] = number_format($total_amount, 2, '.', '');
          $list['first_receipt_no'] = $chunk_receipt[0]['receipt_no'];
          $list['last_receipt_no'] = $chunk_receipt[count($chunk_receipt)-1]['receipt_no'];
          $list['no_of_set'] = count($chunk_receipt);
          array_push($paginate_receipts,$list);
          $list = [];
        }
      }
      elseif(count($receipt['receipt'] <= 5)){
        $total_amount = 0;
        foreach($receipt['receipt'] as $index=>$individual_receipt){
          $total_amount = $total_amount + $individual_receipt['amount'];
          $list['receipt'][$index] = $individual_receipt;
        }
        $list['devotee'] = $receipt['devotee'];
        $list['total_amount'] = number_format($total_amount, 2, '.', '');
        $list['first_receipt_no'] = $receipt['receipt'][0]['receipt_no'];
        $list['last_receipt_no'] = $receipt['receipt'][count($receipt['receipt'])-1]['receipt_no'];
        $list['no_of_set'] = count($receipt['receipt']);
        array_push($paginate_receipts,$list);
        $list = [];
      }
    }
    return $paginate_receipts;
  }

  public static function paginateSingleReceipt($receipts){
    $unique_devotee_ids = $receipts->unique('devotee_id')->pluck('devotee_id');
    $receipts_list = [];
    $devotee_receipts_list = [];
    $current_receipts_list = [];
    foreach($unique_devotee_ids as $unique_devotee_id_index=>$unique_devotee_id){
      $list['receipt']['sn_no'] = null;
      $list['receipt']['name'] = Devotee::getChineseName($unique_devotee_id);
      $list['receipt']['item_description'] = '('.$unique_devotee_id.')';
      $list['receipt']['receipt_no'] = null;
      $list['receipt']['amount'] = null;
      $list['receipt']['is_receipt'] = false;
      $list['receipt']['devotee_id'] = $unique_devotee_id;
      array_push($receipts_list,$list['receipt']);
      array_push($current_receipts_list,$list['receipt']);
      $list = [];
      foreach($receipts as $receipt_index=>$receipt){
        if(Devotee::isSameDevoteeId($unique_devotee_id,$receipt['devotee_id'])){
          $list['receipt']['sn_no'] = null;
          $list['receipt']['name'] =  $receipt['type_chinese_name'];
          $list['receipt']['item_description'] = $receipt['item_description'];
          $list['receipt']['receipt_no'] = $receipt['receipt_no'];
          $list['receipt']['amount'] = $receipt['amount'];
          $list['receipt']['is_receipt'] = true;
          $list['receipt']['devotee_id'] = $receipt['devotee_id'];
          array_push($receipts_list,$list['receipt']);
          array_push($current_receipts_list,$list['receipt']);
          $list = [];
        }

      }
      array_push($devotee_receipts_list,$current_receipts_list);
      $current_receipts_list = [];
    }
    $paginate_combine_receipts = [];
    foreach($devotee_receipts_list as $devotee_receipt_index=>$devotee_receipt){

      $paginate_receipts_list = array_chunk($devotee_receipt,6);
      foreach($paginate_receipts_list as $chunk=>$paginate_receipts){
        $paginate_combine_receipt = [];
        $paginate_combine_receipt['receipt'] = [];
        $total_amount = 0 ;
        $no_of_set = 0;
        foreach($paginate_receipts as $individual_index=>$individual_receipt){
          array_push($paginate_combine_receipt['receipt'],$individual_receipt);
          $list['receipt'] = [];
          $total_amount = $total_amount + $individual_receipt['amount'] ;
          if($individual_receipt['is_receipt'] == true){
            $no_of_set ++;
          }
        }
        $paginate_combine_receipt['first_receipt_no'] = array_first($paginate_receipts, function ($value, $key) {
                                                          return $value['is_receipt'] == true;
                                                        })['receipt_no'];

        $paginate_combine_receipt['last_receipt_no'] = array_last($paginate_receipts, function ($value, $key) {
                                                          return $value['is_receipt'] == true;
                                                        })['receipt_no'];

        $paginate_combine_receipt['no_of_set'] = $no_of_set;
        $paginate_combine_receipt['total_amount'] = $total_amount;
        array_push($paginate_combine_receipts,$paginate_combine_receipt);
        $paginate_combine_receipt = [];
      }
    }

    return $paginate_combine_receipts;
  }

  public static function paginateCombineReceipt($receipts){
    $unique_devotee_ids = $receipts->unique('devotee_id')->pluck('devotee_id');
    $receipts_list = [];
    foreach($unique_devotee_ids as $unique_devotee_id_index=>$unique_devotee_id){
      $list['receipt']['sn_no'] = null;
      $list['receipt']['name'] = Devotee::getChineseName($unique_devotee_id);
      $list['receipt']['item_description'] = '('.$unique_devotee_id.')';
      $list['receipt']['receipt_no'] = null;
      $list['receipt']['amount'] = null;
      $list['receipt']['is_receipt'] = false;
      $list['receipt']['devotee_id'] = $unique_devotee_id;
      array_push($receipts_list,$list['receipt']);
      $list = [];
      foreach($receipts as $receipt_index=>$receipt){
        if(Devotee::isSameDevoteeId($unique_devotee_id,$receipt['devotee_id'])){
          $list['receipt']['sn_no'] = null;
          $list['receipt']['name'] =  $receipt['type_chinese_name'];
          $list['receipt']['item_description'] = $receipt['item_description'];
          $list['receipt']['receipt_no'] = $receipt['receipt_no'];
          $list['receipt']['amount'] = $receipt['amount'];
          $list['receipt']['is_receipt'] = true;
          $list['receipt']['devotee_id'] = $receipt['devotee_id'];
          array_push($receipts_list,$list['receipt']);
          $list = [];
        }
      }
    }
    $paginate_combine_receipts = [];
    $paginate_receipts_list = array_chunk($receipts_list,6);
    foreach($paginate_receipts_list as $chunk=>$paginate_receipts){
      $paginate_combine_receipt = [];
      $paginate_combine_receipt['receipt'] = [];
      $total_amount = 0 ;
      $no_of_set = 0;
      foreach($paginate_receipts as $individual_index=>$individual_receipt){
        array_push($paginate_combine_receipt['receipt'],$individual_receipt);
        $list['receipt'] = [];
        $total_amount = $total_amount + $individual_receipt['amount'] ;
        if($individual_receipt['is_receipt'] == true){
          $no_of_set ++;
        }
      }
      $paginate_combine_receipt['first_receipt_no'] = array_first($paginate_receipts, function ($value, $key) {
                                                        return $value['is_receipt'] == true;
                                                      })['receipt_no'];

      $paginate_combine_receipt['last_receipt_no'] = array_last($paginate_receipts, function ($value, $key) {
                                                        return $value['is_receipt'] == true;
                                                      })['receipt_no'];

      $paginate_combine_receipt['no_of_set'] = $no_of_set;
      $paginate_combine_receipt['total_amount'] = $total_amount;
      array_push($paginate_combine_receipts,$paginate_combine_receipt);
      $paginate_combine_receipt = [];
    }

    return $paginate_combine_receipts;
  }

  public static function getReceiptOfFamily($receipts){
    $receipts_of_family = $receipts->filter(function ($value, $key) {
      if(Devotee::isSameFamily(session()->get('focus_devotee')[0]['devotee_id'],$value['devotee_id'])){
        return $value;
      }
    });

    return $receipts_of_family;
  }

  public static function getReceiptOfRelative($receipts){
    $receipts_of_relatives = $receipts->filter(function ($value, $key) {
      if(Devotee::isRelative(session()->get('focus_devotee')[0]['devotee_id'],$value['devotee_id'])){
        return $value;
      }
    });

    return $receipts_of_relatives;
  }

}
