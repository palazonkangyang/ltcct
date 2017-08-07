<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\User;
use App\Models\GeneralDonation;
use Auth;
use DB;
use Hash;
use Mail;
use Input;
use Session;
use View;
use URL;
use Carbon\Carbon;

class ReportController extends Controller
{

  public function getIncomeReport()
  {
    return view('report.income-report');
  }

  public function getReportDetail(Request $request)
  {
    if(isset($_GET['from_date']) && isset($_GET['to_date']))
    {
      $from_date = str_replace('/', '-', $_GET['from_date']);
		  $newFromDate = date("Y-m-d", strtotime($from_date));

      $to_date = str_replace('/', '-', $_GET['to_date']);
		  $newToDate = date("Y-m-d", strtotime($to_date));

      $donation_member = GeneralDonation::join('glcode', 'glcode.glcode_id', 'generaldonation.glcode_id')
                         ->select(DB::raw('sum(total_amount) as `total_amount`'), DB::raw('MONTH(trans_at) month'), 'glcode.type_name')
                         ->where('trans_at', '>=', $newFromDate)
                         ->where('trans_at', '<=', $newToDate)
                         ->where('generaldonation.glcode_id', '=', 8)
                         ->GroupBy('month')
                         ->get();

      $donation_nonmember = GeneralDonation::join('glcode', 'glcode.glcode_id', 'generaldonation.glcode_id')
                          ->select(DB::raw('sum(total_amount) as `total_amount`'), DB::raw('MONTH(trans_at) month'), 'glcode.type_name')
                          ->where('trans_at', '>=', $newFromDate)
                          ->where('trans_at', '<=', $newToDate)
                          ->where('generaldonation.glcode_id', '=', 12)
                          ->GroupBy('month')
                          ->get();
    }

    else
    {
      $donation_member = GeneralDonation::join('glcode', 'glcode.glcode_id', 'generaldonation.glcode_id')
                         ->select(DB::raw('sum(total_amount) as `total_amount`'), DB::raw('MONTH(trans_at) month'), 'glcode.type_name')
                         ->where('generaldonation.glcode_id', '=', 8)
                         ->GroupBy('month')
                         ->get();

    $donation_nonmember = GeneralDonation::join('glcode', 'glcode.glcode_id', 'generaldonation.glcode_id')
                       ->select(DB::raw('sum(total_amount) as `total_amount`'), DB::raw('MONTH(trans_at) month'), 'glcode.type_name')
                       ->where('generaldonation.glcode_id', '=', 12)
                       ->GroupBy('month')
                       ->get();
    }

    return response()->json(array(
      'donation_member' => $donation_member,
      'donation_nonmember' => $donation_nonmember
	  ));
  }

}
