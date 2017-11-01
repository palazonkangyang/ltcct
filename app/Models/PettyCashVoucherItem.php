<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PettyCashVoucherItem extends Model
{
  protected $table = 'pettycash_voucher_item';

  protected $primaryKey = "pettycash_voucher_item_id";

  protected $fillable = [
    'glcode_id',
    'debit_amount',
    'credit_amount',
    'pettycash_voucher_id'
  ];
}
