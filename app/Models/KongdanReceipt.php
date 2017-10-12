<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KongdanReceipt extends Model
{
    protected $table = 'kongdan_receipt';

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
