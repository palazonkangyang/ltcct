<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class XiaozaiGeneraldonation extends Model
{
  protected $table = 'xiaozai_generaldonation';
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
    $receipt = DB::table('xiaozai_receipt');

    $receipt->select(
      'xiaozai_receipt.*',
      'xiaozai_generaldonation.focusdevotee_id',
      'xiaozai_generaldonation.trans_no',
      'xiaozai_generaldonation.total_amount',
      'xiaozai_generaldonation.mode_payment',
      'xiaozai_generaldonation.cheque_no',
      'xiaozai_generaldonation.receipt_at',
      'xiaozai_generaldonation.manualreceipt',
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

    $receipt->leftjoin('xiaozai_generaldonation', 'xiaozai_receipt.generaldonation_id', '=', 'xiaozai_generaldonation.generaldonation_id');
    $receipt->leftjoin('devotee', 'xiaozai_receipt.devotee_id', '=', 'devotee.devotee_id');
    $receipt->leftjoin('user', 'xiaozai_receipt.staff_id', '=', 'user.id');
    $receipt->leftjoin('festiveevent', 'xiaozai_generaldonation.festiveevent_id', '=', 'festiveevent.festiveevent_id');

    if (isset($input['trans_no'])) {
      $receipt->where('xiaozai_generaldonation.trans_no', '=', $input['trans_no']);
    }

    if (isset($input['receipt_no'])) {
      $receipt->where('xiaozai_receipt.receipt_no', '=', $input['receipt_no']);
    }

    return $receipt;
  }
}
