<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PettyCashVoucher extends Model
{
  protected $table = 'pettycash_voucher';

  protected $primaryKey = "pettycash_voucher_id";

  protected $fillable = [
    'voucher_no',
    'date',
    'supplier_id',
    'description',
    'payee',
    'total_debit_amount',
    'total_credit_amount',
    'job_id',
    'remark'
  ];
}
