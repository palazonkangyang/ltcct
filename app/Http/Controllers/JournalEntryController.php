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

    $journalentry = JournalEntry::all();

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

          if(isset($input['debit_amount'][$i]))
          {
            $glcode = GlCode::find($input['glcode_id'][$i]);
            $glcode->balance -= $input['debit_amount'][$i];
            $glcode->save();
          }

          if(isset($input['credit_amount'][$i]))
          {
            $glcode = GlCode::find($input['glcode_id'][$i]);
            $glcode->balance += $input['credit_amount'][$i];
            $glcode->save();
          }
        }

        $success_msg = $reference_no . ' has been created!';

        $request->session()->flash('success', $success_msg);
        return redirect()->route('manage-journalentry-page');
      }

      else
      {
        $request->session()->flash('error', "Password did not match. Please Try Again");
        return redirect()->back()->withInput();
      }
    }
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

  public function getDeleteJournalEntry($id)
  {

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
