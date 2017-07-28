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
        'cancelled_date',
        'status',
        'cancelled_by',
        'generaldonation_id'
    ];
}
