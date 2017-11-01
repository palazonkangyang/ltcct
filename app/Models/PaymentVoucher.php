<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentVoucher extends Model
{
  protected $table = 'payment_voucher';

  protected $primaryKey = "payment_voucher_id";

  protected $fillable = [
    'voucher_no',
    'date',
    'supplier_id',
    'description',
    'cheque_no',
    'cheque_account',
    'total_debit_amount',
    'total_credit_amount',
    'issuing_banking',
    'cheque_from',
    'job_id',
    'remark'
  ];
}
