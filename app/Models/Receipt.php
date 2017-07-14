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
        'focusdevotee_id',
        'hjgr',
        'amount',
        'manualreceipt',
        'generaldonation_id'
    ];
}
