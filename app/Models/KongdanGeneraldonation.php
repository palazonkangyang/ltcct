<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class KongdanGeneraldonation extends Model
{
  protected $table = 'kongdan_generaldonation';
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
    $receipt = DB::table('kongdan_receipt');

    $receipt->select(
        'kongdan_receipt.*',
        'kongdan_generaldonation.focusdevotee_id',
        'kongdan_generaldonation.trans_no',
        'kongdan_generaldonation.total_amount',
        'kongdan_generaldonation.mode_payment',
        'kongdan_generaldonation.cheque_no',
        'kongdan_generaldonation.receipt_at',
        'kongdan_generaldonation.manualreceipt',
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

    $receipt->leftjoin('kongdan_generaldonation', 'kongdan_receipt.generaldonation_id', '=', 'kongdan_generaldonation.generaldonation_id');
    $receipt->leftjoin('devotee', 'kongdan_receipt.devotee_id', '=', 'devotee.devotee_id');
    $receipt->leftjoin('user', 'kongdan_receipt.staff_id', '=', 'user.id');
    $receipt->leftjoin('festiveevent', 'kongdan_generaldonation.festiveevent_id', '=', 'festiveevent.festiveevent_id');

    if (isset($input['trans_no'])) {
        $receipt->where('kongdan_generaldonation.trans_no', '=', $input['trans_no']);
    }

    if (isset($input['receipt_no'])) {
        $receipt->where('kongdan_receipt.receipt_no', '=', $input['receipt_no']);
    }

    return $receipt;
  }
}
