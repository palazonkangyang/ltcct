<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paid extends Model
{
    protected $table = 'paid';

    protected $primaryKey = "paid_id";

    protected $fillable = [
        'paid_id',
        'purchase_id',
        'reference_no',
        'date',
        'expenditure_id',
        'supplier',
        'description',
        'expenditure_total',
        'outstanding_total',
        'amount',
        'status',
        'type',
        'voucher_no',
        'payee',
        'transaction_date',
        'cash_account',
        'cash_amount',
        'cheque_no',
        'cheque_account',
        'cheque_voucher_no',
        'cheque_receipt',
        'issuing_banking',
        'cheque_from',
        'customer',
        'cheque_amount',
        'currency',
        'cheque_date',
        'cash_date',
        'job_id',
        'gl_description',
        'remark'
    ];
}
