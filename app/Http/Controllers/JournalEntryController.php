<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\User;
use App\Models\JournalEntry;
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
    $journalentry = JournalEntry::join('glcode as gl1', 'gl1.glcode_id', '=', 'journalentry.debit')
                    ->join('glcode as gl2', 'gl2.glcode_id', '=', 'journalentry.credit')
                    ->select('journalentry.*', 'gl1.type_name as debit_account', 'gl2.type_name as credit_account')
                    ->get();

    return view('journalentry.manage-journalentry', [
      'glcode' => $glcode,
      'journalentry' => $journalentry
    ]);
  }

  public function postAddNewJournalentry(Request $request)
  {
    $input = array_except($request->all(), '_token');

    if(isset($input['authorized_password']))
    {
      $user = User::find(Auth::user()->id);
      $hashedPassword = $user->password;

      if (Hash::check($input['authorized_password'], $hashedPassword))
      {
        // Modify fields
				if(isset($input['date']))
				{
				  $date = str_replace('/', '-', $input['date']);
				  $newDate = date("Y-m-d", strtotime($date));
				}

				else {
				  $newDate = "";
				}

        $data = [
          "journalentry_no" => $input['journalentry_no'],
          "date" => $newDate,
          "description" => $input['description'],
          "debit" => $input['debit'],
          "credit" => $input['credit']
        ];

        // dd($data);

        $journalentry = JournalEntry::create($data);
      }

      else
      {
        $request->session()->flash('error', "Password did not match. Please Try Again");
        return redirect()->back()->withInput();
      }

    }

    if($journalentry)
    {
      $request->session()->flash('success', 'New Journal Entry has been created!');
      return redirect()->route('manage-journalentry-page');
    }

    dd($input);
  }

  public function getJournalEntryDetail()
  {
    $journalentry_id = $_GET['journalentry_id'];

    $journalentry = JournalEntry::find($journalentry_id);

    $journalentry->date = Carbon::parse($journalentry->date)->format("d/m/Y");

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
}
