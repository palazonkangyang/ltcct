<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class QifuGeneraldonation extends Model
{
  protected $table = 'qifu_generaldonation';
  protected $primaryKey = "generaldonation_id";

  protected $fillable = [
    'trans_no',
    'description',
    'hjgr',
    'total_amount',
    'mode_payment',
    'cheque_no',
    'nets_no',
    'receipt_at',
    'manualreceipt',
    'trans_at',
    'focusdevotee_id',
    'festiveevent_id',
    'donationtype_id'
  ];

  public function searchTransaction($input)
  {
    $receipt = DB::table('qifu_receipt');

    $receipt->select(
      'qifu_receipt.*',
      'qifu_generaldonation.focusdevotee_id',
      'qifu_generaldonation.trans_no',
      'qifu_generaldonation.total_amount',
      'qifu_generaldonation.mode_payment',
      'qifu_generaldonation.cheque_no',
      'qifu_generaldonation.receipt_at',
      'qifu_generaldonation.manualreceipt',
      'user.first_name',
      'user.last_name',
      'devotee.chinese_name',
      'devotee.address_houseno',
      'devotee.address_unit1',
      'devotee.address_unit2',
      'devotee.address_street',
      'devotee.address_postal',
      'devotee.oversea_addr_in_chinese',
      'festiveevent.event'
    );

    $receipt->leftjoin('qifu_generaldonation', 'qifu_receipt.generaldonation_id', '=', 'qifu_generaldonation.generaldonation_id');
    $receipt->leftjoin('devotee', 'qifu_receipt.devotee_id', '=', 'devotee.devotee_id');
    $receipt->leftjoin('user', 'qifu_receipt.staff_id', '=', 'user.id');
    $receipt->leftjoin('festiveevent', 'qifu_generaldonation.festiveevent_id', '=', 'festiveevent.festiveevent_id');

    if (isset($input['trans_no'])) {
      $receipt->where('qifu_generaldonation.trans_no', '=', $input['trans_no']);
    }

    if (isset($input['receipt_no'])) {
      $receipt->where('qifu_receipt.receipt_no', '=', $input['receipt_no']);
    }

    return $receipt;
  }
}
