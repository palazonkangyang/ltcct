<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GeneralDonation extends Model
{
    protected $table = 'generaldonation';

    protected $primaryKey = "generaldonation_id";

    protected $fillable = [
        'trans_no',
        'description',
        'hjgr',
        'total_amount',
        'mode_payment',
        'cheque_no',
        'receipt_at',
        'manualreceipt',
        'trans_at',
        'focusdevotee_id',
        'festiveevent_id',
        'glcode_id',
        'donationtype_id'
    ];
}
