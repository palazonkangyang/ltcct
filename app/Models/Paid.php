<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paid extends Model
{
    protected $table = 'paid';

    protected $primaryKey = "paid_id";

    protected $fillable = [
        'paid_id',
        'purchase_id',
        'amount',
        'paid_at',
        'description',
        'mode_payment',
        'cheque_no',
        'manual_receipt',
        'receipt_at'
    ];
}
