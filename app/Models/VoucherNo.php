<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VoucherNo extends Model
{
  protected $table = 'voucher_no';

  protected $primaryKey = "voucher_no_id";

  protected $fillable = [
    'prefix'
  ];
}
