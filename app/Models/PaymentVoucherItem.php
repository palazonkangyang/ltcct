<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentVoucherItem extends Model
{
  protected $table = 'payment_voucher_item';

  protected $primaryKey = "payment_voucher_item_id";

  protected $fillable = [
    'glcode_id',
    'debit_amount',
    'credit_amount',
    'payment_voucher_id'
  ];

  public static function getGlCodeIdListByPaymentVoucherId($payment_voucher_id){
    return PaymentVoucherItem::where('payment_voucher_id',$payment_voucher_id)->where('credit_amount','!=',NULL)->select('glcode_id')->get();
  }
}
