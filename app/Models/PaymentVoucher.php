<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentVoucher extends Model
{
    protected $table = 'payment_voucher';

    protected $primaryKey = "payment_voucher_id";

    protected $fillable = [
        'reference_no',
        'date',
        'expenditure_id',
        'supplier_id',
        'description',
        'expenditure_total',
        'outstanding_total',
        'cheque_no',
        'cheque_account',
        'issuing_banking',
        'cheque_from',
        'cheque_amount',
        'customer',
        'job_id',
        'remark'
    ];
}
