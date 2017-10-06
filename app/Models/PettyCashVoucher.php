<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PettyCashVoucher extends Model
{
    protected $table = 'pettycash_voucher';

    protected $primaryKey = "pettycash_voucher_id";

    protected $fillable = [
        'reference_no',
        'date',
        'expenditure_id',
        'supplier_id',
        'description',
        'expenditure_total',
        'outstanding_total',
        'voucher_no',
        'cash_amount',
        'cash_payee',
        'job_id',
        'remark'
    ];
}
