<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JournalEntryItem extends Model
{
    protected $table = 'journalentry_item';

    protected $primaryKey = "journalentry_item_id";

    protected $fillable = [
        'glcode_id',
        'debit_amount',
        'credit_amount',
        'journalentry_id'
    ];
}
