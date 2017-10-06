<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JournalEntry extends Model
{
    protected $table = 'journalentry';

    protected $primaryKey = "journalentry_id";

    protected $fillable = [
        'journalentry_no',
        'date',
        'description',
        'total_debit_amount',
        'total_credit_amount'
    ];
}
