<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\User;
use App\Models\JournalEntry;
use App\Models\JournalEntryItem;
use App\Models\GlCode;
use Auth;
use DB;
use Hash;
use Mail;
use Input;
use Session;
use View;
use URL;
use Carbon\Carbon;

class JournalController extends Controller
{
  public function getManageJournal()
  {
    $journal_list = JournalEntry::leftjoin('devotee', 'journalentry.devotee_id', '=', 'devotee.devotee_id')
               ->where('journalentry.type', 'journal')
               ->orderBy('journalentry_id', 'desc')
               ->select('journalentry.*', 'devotee.chinese_name as paidby')
               ->get();

    foreach($journal_list as $journal){
      $journal_entry_item_list_of_debit = JournalEntryItem::getListOfDebitByJournalEntryId($journal['journalentry_id']);
      $list['debit_gl_list'] = [];
      foreach($journal_entry_item_list_of_debit as $journal_entry_item_of_debit){
        array_push($list['debit_gl_list'],GlCode::getChineseNameByGlCodeId($journal_entry_item_of_debit['glcode_id']));
      }
      $journal['debit_gl_list'] = $list['debit_gl_list'];

      $journal_entry_item_list_of_credit = JournalEntryItem::getListOfCreditByJournalEntryId($journal['journalentry_id']);
      $list['credit_gl_list'] = [];
      foreach($journal_entry_item_list_of_credit as $journal_entry_item_of_credit){
        array_push($list['credit_gl_list'],GlCode::getChineseNameByGlCodeId($journal_entry_item_of_credit['glcode_id']));
      }
      $journal['debit_gl_list'] = $list['debit_gl_list'];
      $journal['credit_gl_list'] = $list['credit_gl_list'];
    }

    // dd($journal_list[0]['debit_gl'][0]);


    return view('journal.manage-journal', [
      'journal' => $journal_list
    ]);
  }

  public function getJournalDetail()
  {
    $journalentry_id = $_GET['journalentry_id'];

    $journalentry = JournalEntry::leftjoin('journalentry_item', 'journalentry.journalentry_id', '=', 'journalentry_item.journalentry_id')
                    ->leftjoin('glcode', 'journalentry_item.glcode_id', '=', 'glcode.glcode_id')
                    ->leftjoin('devotee', 'journalentry.devotee_id', '=', 'devotee.devotee_id')
                    ->where('journalentry.journalentry_id', $journalentry_id)
                    ->select('journalentry.*', 'journalentry_item.*', 'devotee.chinese_name as paidby', 'glcode.type_name')
                    ->get();

    $journalentry[0]->date = Carbon::parse($journalentry[0]->date)->format("d/m/Y");

    return response()->json(array(
	    'journalentry' => $journalentry,
	  ));
  }
}
