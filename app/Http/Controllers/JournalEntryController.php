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

class JournalEntryController extends Controller
{
  public function getManageJournalEntry()
  {
    $glcode = GlCode::all();

    $journalentry = JournalEntry::where('journalentry.type', 'journalentry')
                    ->orderBy('journalentry_id', 'desc')->get();

    foreach($journalentry as $journal){
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

    return view('journalentry.manage-journalentry', [
      'glcode' => $glcode,
      'journalentry' => $journalentry
    ]);
  }

  public function postAddNewJournalentry(Request $request)
  {
    $input = array_except($request->all(), '_token');

    // Modify fields
    if(isset($input['date']))
    {
      $date = str_replace('/', '-', $input['date']);
      $newDate = date("Y-m-d", strtotime($date));
    }

    else {
      $newDate = "";
    }

    $year = date("y");

    if(count(JournalEntry::all()))
    {
      $journalentry_id = JournalEntry::all()->last()->journalentry_id;
      $journalentry_id = str_pad($journalentry_id + 1, 4, 0, STR_PAD_LEFT);
    }

    else
    {
      $journalentry_id = 0;
      $journalentry_id = str_pad($journalentry_id + 1, 4, 0, STR_PAD_LEFT);
    }

    $reference_no = 'JE-' . $year . $journalentry_id;

    $data = [
      "journalentry_no" => $reference_no,
      "date" => $newDate,
      "description" => $input['description'],
      "type" => 'journalentry',
      "total_debit_amount" => $input['total_debit_amount'],
      "total_credit_amount" => $input['total_credit_amount']
    ];

    $journalentry = JournalEntry::create($data);

    for($i = 0; $i < count($input['glcode_id']); $i++)
    {
      $data = [
        "glcode_id" => $input['glcode_id'][$i],
        "debit_amount" => $input['debit_amount'][$i],
        "credit_amount" => $input['credit_amount'][$i],
        "journalentry_id" => $journalentry->journalentry_id
      ];

      JournalEntryItem::create($data);
    }

    $success_msg = $reference_no . ' has been created!';

    $request->session()->flash('success', $success_msg);
    return redirect()->route('manage-journalentry-page');
  }

  public function getJournalEntryDetail()
  {
    $journalentry_id = $_GET['journalentry_id'];

    $journalentry = JournalEntry::leftjoin('journalentry_item', 'journalentry.journalentry_id', '=', 'journalentry_item.journalentry_id')
                    ->leftjoin('glcode', 'journalentry_item.glcode_id', '=', 'glcode.glcode_id')
                    ->where('journalentry.journalentry_id', $journalentry_id)
                    ->select('journalentry.*', 'journalentry_item.*', 'glcode.type_name')
                    ->get();

    $journalentry[0]->date = Carbon::parse($journalentry[0]->date)->format("d/m/Y");

    return response()->json(array(
	    'journalentry' => $journalentry,
	  ));
  }

  public function postUpdateJournalentry(Request $request)
  {
    $input = array_except($request->all(), '_token');

    if(isset($input['edit_authorized_password']))
    {
      $user = User::find(Auth::user()->id);
      $hashedPassword = $user->password;

      if (Hash::check($input['edit_authorized_password'], $hashedPassword))
      {
        // Modify fields
				if(isset($input['edit_date']))
				{
				  $date = str_replace('/', '-', $input['edit_date']);
				  $newDate = date("Y-m-d", strtotime($date));
				}

				else {
				  $newDate = "";
				}

        $journalentry = JournalEntry::find($input['edit_journalentry_id']);

        $journalentry->journalentry_no = $input['edit_journalentry_no'];
        $journalentry->date = $newDate;
        $journalentry->description = $input['edit_description'];
        $journalentry->debit = $input['edit_debit'];
        $journalentry->credit = $input['edit_credit'];

        $result = $journalentry->save();
      }

      else
      {
        $request->session()->flash('error', "Password did not match. Please Try Again");
        return redirect()->back()->withInput();
      }
    }

    if($result)
    {
      $request->session()->flash('success', 'Journal Entry has been updated!');
      return redirect()->route('manage-journalentry-page');
    }
  }

  public function getBalance(Request $request)
  {
    $glcode_id = $_GET['glcode_id'];

    $glcode = GlCode::find($glcode_id);

    return response()->json(array(
      'glcode' => $glcode
	  ));
  }
}
