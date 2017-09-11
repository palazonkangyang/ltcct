<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class GeneralDonation extends Model
{
  protected $table = 'generaldonation';
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
    'glcode_id',
    'donationtype_id'
  ];

  public function searchTransaction($input)
  {
    $receipt = DB::table('receipt');

    $receipt->select(
        'receipt.*',
        'generaldonation.focusdevotee_id',
        'generaldonation.trans_no',
        'generaldonation.total_amount',
        'generaldonation.mode_payment',
        'generaldonation.cheque_no',
        'generaldonation.receipt_at',
        'generaldonation.manualreceipt',
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

    $receipt->leftjoin('generaldonation', 'receipt.generaldonation_id', '=', 'generaldonation.generaldonation_id');
    $receipt->leftjoin('devotee', 'receipt.devotee_id', '=', 'devotee.devotee_id');
    $receipt->leftjoin('user', 'receipt.staff_id', '=', 'user.id');
    $receipt->leftjoin('festiveevent', 'generaldonation.festiveevent_id', '=', 'festiveevent.festiveevent_id');

    if (isset($input['trans_no'])) {
        $receipt->where('generaldonation.trans_no', '=', $input['trans_no']);
    }

    if (isset($input['receipt_no'])) {
        $receipt->where('receipt.xy_receipt', '=', $input['receipt_no']);
    }

    return $receipt;
  }

  public function yuejuansearchTransaction($input)
  {
    $receipt = DB::table('receipt');

    $receipt->select(
        'receipt.*',
        'generaldonation.focusdevotee_id',
        'generaldonation.trans_no',
        'generaldonation.total_amount',
        'generaldonation.mode_payment',
        'generaldonation.cheque_no',
        'generaldonation.receipt_at',
        'generaldonation.manualreceipt',
        'user.first_name',
        'user.last_name',
        'devotee.chinese_name',
        'devotee.address_houseno',
        'devotee.address_unit1',
        'devotee.address_unit2',
        'devotee.address_street',
        'devotee.address_postal',
        'devotee.member_id',
        'devotee.oversea_addr_in_chinese',
        'festiveevent.event',
        'member.paytill_date'
    );

    $receipt->leftjoin('generaldonation', 'receipt.generaldonation_id', '=', 'generaldonation.generaldonation_id');
    $receipt->leftjoin('devotee', 'receipt.devotee_id', '=', 'devotee.devotee_id');
    $receipt->leftjoin('user', 'receipt.staff_id', '=', 'user.id');
    $receipt->leftjoin('member', 'devotee.member_id', '=', 'member.member_id');
    $receipt->leftjoin('festiveevent', 'generaldonation.festiveevent_id', '=', 'festiveevent.festiveevent_id');

    if (isset($input['trans_no'])) {
        $receipt->where('generaldonation.trans_no', '=', $input['trans_no']);
    }

    if (isset($input['receipt_no'])) {
        $receipt->where('receipt.xy_receipt', '=', $input['receipt_no']);
    }

    return $receipt;
  }
}
