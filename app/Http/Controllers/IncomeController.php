<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\User;
use App\Models\GeneralDonation;
use App\Models\Receipt;
use Auth;
use DB;
use Hash;
use Mail;
use Input;
use Session;
use View;
use URL;
use Carbon\Carbon;

class IncomeController extends Controller
{
  public function getAllIncomeLists()
  {
    $receipts = GeneralDonation::leftjoin('devotee', 'devotee.devotee_id', '=', 'generaldonation.focusdevotee_id')
								->leftjoin('receipt', 'receipt.generaldonation_id', '=', 'generaldonation.generaldonation_id')
								->GroupBy('generaldonation.generaldonation_id')
								->select('generaldonation.*', 'devotee.chinese_name', 'receipt.cancelled_date')
								->orderBy('generaldonation.generaldonation_id', 'desc')
								->get();

    if(count($receipts) > 0)
    {
      for($i = 0; $i < count($receipts); $i++)
      {
        $data = Receipt::where('generaldonation_id', $receipts[$i]->generaldonation_id)->pluck('xy_receipt');

        $receipt_count = count($data);

        if($receipt_count > 1)
        {
          $receipts[$i]->receipt_no = $data[0] . ' - ' . $data[$receipt_count - 1];
        }
        else
        {
          $receipts[$i]->receipt_no = $data[0];
        }
      }
    }

    return view('income.income-lists', [
      'receipts' => $receipts
    ]);
  }
}
