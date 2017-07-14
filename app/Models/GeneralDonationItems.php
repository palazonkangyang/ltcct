<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GeneralDonationItems extends Model
{
    protected $table = 'generaldonation_items';

    protected $primaryKey = "generaldonation_items_id";

    protected $fillable = [
        'gy',
        'amount',
        'paid_till',
        'hjgr',
        'display',
        'trans_date',
        'receipt_at',
        'generaldonation_id',
        'devotee_id',
        'receipt_id'
    ];
}
