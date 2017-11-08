<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QifuReceipt extends Model
{
  protected $table = 'qifu_receipt';

  protected $primaryKey = "receipt_id";

  protected $fillable = [
    'receipt_no',
    'trans_date',
    'description',
    'amount',
    'hjgr',
    'cancelled_date',
    'status',
    'cancelled_by',
    'glcode_id',
    'devotee_id',
    'generaldonation_id',
    'staff_id'
  ];
}
