<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class XiaozaiReceipt extends Model
{
  protected $table = 'xiaozai_receipt';

  protected $primaryKey = "receipt_id";

  protected $fillable = [
    'receipt_no',
    'trans_date',
    'description',
    'type',
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
