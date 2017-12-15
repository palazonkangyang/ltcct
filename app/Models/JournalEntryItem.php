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

  public static function getListOfDebitByJournalEntryId($journalentry_id){
    return JournalEntryItem::where('journalentry_id',$journalentry_id)->where('debit_amount','!=',NULL)->get();
  }

  public static function getListOfCreditByJournalEntryId($journalentry_id){
    return JournalEntryItem::where('journalentry_id',$journalentry_id)->where('credit_amount','!=',NULL)->get();
  }
}
