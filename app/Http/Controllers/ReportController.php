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
    $non_members = GeneralDonation::leftjoin('glcode', 'generaldonation.glcode_id', '=', 'glcode.glcode_id')
                    ->where('generaldonation.glcode_id', 112)
                    ->select(DB::raw('SUM(IF(MONTH(generaldonation.trans_at) = 1, generaldonation.total_amount, 0)) AS Jan'),
                    DB::raw('SUM(IF(MONTH(generaldonation.trans_at) = 2, generaldonation.total_amount, 0)) AS Feb'),
                    DB::raw('SUM(IF(MONTH(generaldonation.trans_at) = 3, generaldonation.total_amount, 0)) AS Mar'),
                    DB::raw('SUM(IF(MONTH(generaldonation.trans_at) = 4, generaldonation.total_amount, 0)) AS Apr'),
                    DB::raw('SUM(IF(MONTH(generaldonation.trans_at) = 5, generaldonation.total_amount, 0)) AS May'),
                    DB::raw('SUM(IF(MONTH(generaldonation.trans_at) = 6, generaldonation.total_amount, 0)) AS Jun'),
                    DB::raw('SUM(IF(MONTH(generaldonation.trans_at) = 7, generaldonation.total_amount, 0)) AS July'),
                    DB::raw('SUM(IF(MONTH(generaldonation.trans_at) = 8, generaldonation.total_amount, 0)) AS Aug'),
                    DB::raw('SUM(IF(MONTH(generaldonation.trans_at) = 9, generaldonation.total_amount, 0)) AS Sep'),
                    DB::raw('SUM(IF(MONTH(generaldonation.trans_at) = 10, generaldonation.total_amount, 0)) AS Oct'),
                    DB::raw('SUM(IF(MONTH(generaldonation.trans_at) = 11, generaldonation.total_amount, 0)) AS Nov'),
                    DB::raw('SUM(IF(MONTH(generaldonation.trans_at) = 12, generaldonation.total_amount, 0)) AS December'))
                    ->get();

    $members = GeneralDonation::leftjoin('glcode', 'generaldonation.glcode_id', '=', 'glcode.glcode_id')
              ->where('generaldonation.glcode_id', 119)
              ->select(DB::raw('SUM(IF(MONTH(generaldonation.trans_at) = 1, generaldonation.total_amount, 0)) AS Jan'),
              DB::raw('SUM(IF(MONTH(generaldonation.trans_at) = 2, generaldonation.total_amount, 0)) AS Feb'),
              DB::raw('SUM(IF(MONTH(generaldonation.trans_at) = 3, generaldonation.total_amount, 0)) AS Mar'),
              DB::raw('SUM(IF(MONTH(generaldonation.trans_at) = 4, generaldonation.total_amount, 0)) AS Apr'),
              DB::raw('SUM(IF(MONTH(generaldonation.trans_at) = 5, generaldonation.total_amount, 0)) AS May'),
              DB::raw('SUM(IF(MONTH(generaldonation.trans_at) = 6, generaldonation.total_amount, 0)) AS Jun'),
              DB::raw('SUM(IF(MONTH(generaldonation.trans_at) = 7, generaldonation.total_amount, 0)) AS July'),
              DB::raw('SUM(IF(MONTH(generaldonation.trans_at) = 8, generaldonation.total_amount, 0)) AS Aug'),
              DB::raw('SUM(IF(MONTH(generaldonation.trans_at) = 9, generaldonation.total_amount, 0)) AS Sep'),
              DB::raw('SUM(IF(MONTH(generaldonation.trans_at) = 10, generaldonation.total_amount, 0)) AS Oct'),
              DB::raw('SUM(IF(MONTH(generaldonation.trans_at) = 11, generaldonation.total_amount, 0)) AS Nov'),
              DB::raw('SUM(IF(MONTH(generaldonation.trans_at) = 12, generaldonation.total_amount, 0)) AS December'))
              ->get();

    return view('report.income-report', [
      'members' => $members,
      'non_members' => $non_members
    ]);
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
