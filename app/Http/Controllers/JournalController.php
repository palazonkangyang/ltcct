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
    $journal = JournalEntry::leftjoin('devotee', 'journalentry.devotee_id', '=', 'devotee.devotee_id')
               ->where('journalentry.type', 'journal')
               ->orderBy('journalentry_id', 'desc')
               ->select('journalentry.*', 'devotee.chinese_name as paidby')
               ->get();

    return view('journal.manage-journal', [
      'journal' => $journal
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
