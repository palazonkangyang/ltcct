<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    protected $table = 'receipt';

    protected $primaryKey = "receipt_id";

    protected $fillable = [
        'xy_receipt',
        'trans_date',
        'description',
        'amount',
        'hjgr',
        'display',
        'cancelled_date',
        'status',
        'cancelled_by',
        'glcode_id',
        'devotee_id',
        'generaldonation_id',
        'staff_id'
    ];
}
