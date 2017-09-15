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

class ReportController extends Controller
{

  public function getIncomeReport()
  {
    return view('report.income-report');
  }

  public function getReportDetail(Request $request)
  {
    $input = array_except($request->all(), '_token');

    if(isset($input['month']))
    {
      $nmonth = date("m", strtotime($input['month']));
    }

    else
    {
      $nmonth = null;
    }

    if(isset($input['year']) && isset($nmonth))
    {
      $entrance_fees = Receipt::whereNull('cancelled_date')
                          ->where(DB::raw('YEAR(receipt.trans_date)'), '=', $input['year'])
                          ->where(DB::raw('MONTH(receipt.trans_date)'), '=', $nmonth)
                          ->where('receipt.amount', 10)
                          ->where('receipt.glcode_id', 108)
                          ->select(DB::raw('SUM(IF(MONTH(receipt.trans_date) =' . $nmonth . ', receipt.amount, 0))' . $input['month']))
                          ->get();

      $monthly_subscriptions = Receipt::whereNull('cancelled_date')
                                ->where(DB::raw('YEAR(receipt.trans_date)'), '=', $input['year'])
                                ->where(DB::raw('MONTH(receipt.trans_date)'), '=', $nmonth)
                                ->where('receipt.amount', '!=', 10)
                                ->where('receipt.glcode_id', 110)
                                ->select(DB::raw('SUM(IF(MONTH(receipt.trans_date) =' . $nmonth . ', receipt.amount, 0))' . $input['month']))
                                ->get();

      $donation_others = Receipt::whereNull('cancelled_date')
                         ->where(DB::raw('YEAR(receipt.trans_date)'), '=', $input['year'])
                         ->where(DB::raw('MONTH(receipt.trans_date)'), '=', $nmonth)
                         ->where('receipt.glcode_id', 134)
                         ->select(DB::raw('SUM(IF(MONTH(receipt.trans_date) =' . $nmonth . ', receipt.amount, 0))' . $input['month']))
                         ->get();

      $donation_members = Receipt::whereNull('cancelled_date')
                          ->where(DB::raw('YEAR(receipt.trans_date)'), '=', $input['year'])
                          ->where(DB::raw('MONTH(receipt.trans_date)'), '=', $nmonth)
                          ->where('receipt.glcode_id', 119)
                          ->select(DB::raw('SUM(IF(MONTH(receipt.trans_date) =' . $nmonth . ', receipt.amount, 0))' . $input['month']))
                          ->get();

      $donation_non_members = Receipt::whereNull('cancelled_date')
                               ->where(DB::raw('YEAR(receipt.trans_date)'), '=', $input['year'])
                               ->where(DB::raw('MONTH(receipt.trans_date)'), '=', $nmonth)
                               ->where('receipt.glcode_id', 112)
                               ->select(DB::raw('SUM(IF(MONTH(receipt.trans_date) =' . $nmonth . ', receipt.amount, 0))' . $input['month']))
                               ->get();

      $today = \Carbon\Carbon::parse(Carbon::today())->format("d M Y");

      return view('report.income-monthly-report', [
        'entrance_fees' => $entrance_fees,
        'monthly_subscriptions' => $monthly_subscriptions,
        'donation_others' => $donation_others,
        'donation_members' => $donation_members,
        'donation_non_members' => $donation_non_members,
        'year' => $input['year'],
        'month' => $input['month'],
        'today' => $today
      ]);
    }

    else
    {
      $entrance_fees = Receipt::where('receipt.amount', 10)
                       ->whereNull('cancelled_date')
                       ->where(DB::raw('YEAR(receipt.trans_date)'), '=', $input['year'])
                       ->where('receipt.glcode_id', 108)
                       ->select(DB::raw('SUM(IF(MONTH(receipt.trans_date) = 1, receipt.amount, 0)) AS Jan'),
                       DB::raw('SUM(IF(MONTH(receipt.trans_date) = 2, receipt.amount, 0)) AS Feb'),
                       DB::raw('SUM(IF(MONTH(receipt.trans_date) = 3, receipt.amount, 0)) AS Mar'),
                       DB::raw('SUM(IF(MONTH(receipt.trans_date) = 4, receipt.amount, 0)) AS Apr'),
                       DB::raw('SUM(IF(MONTH(receipt.trans_date) = 5, receipt.amount, 0)) AS May'),
                       DB::raw('SUM(IF(MONTH(receipt.trans_date) = 6, receipt.amount, 0)) AS Jun'),
                       DB::raw('SUM(IF(MONTH(receipt.trans_date) = 7, receipt.amount, 0)) AS July'),
                       DB::raw('SUM(IF(MONTH(receipt.trans_date) = 8, receipt.amount, 0)) AS Aug'),
                       DB::raw('SUM(IF(MONTH(receipt.trans_date) = 9, receipt.amount, 0)) AS Sep'),
                       DB::raw('SUM(IF(MONTH(receipt.trans_date) = 10, receipt.amount, 0)) AS Oct'),
                       DB::raw('SUM(IF(MONTH(receipt.trans_date) = 11, receipt.amount, 0)) AS Nov'),
                       DB::raw('SUM(IF(MONTH(receipt.trans_date) = 12, receipt.amount, 0)) AS December'))
                       ->get();

      $monthly_subscriptions = Receipt::whereNull('cancelled_date')
                       ->where('receipt.amount', '!=', 10)
                       ->where(DB::raw('YEAR(receipt.trans_date)'), '=', $input['year'])
                       ->where('receipt.glcode_id', 110)
                       ->select(DB::raw('SUM(IF(MONTH(receipt.trans_date) = 1, receipt.amount, 0)) AS Jan'),
                       DB::raw('SUM(IF(MONTH(receipt.trans_date) = 2, receipt.amount, 0)) AS Feb'),
                       DB::raw('SUM(IF(MONTH(receipt.trans_date) = 3, receipt.amount, 0)) AS Mar'),
                       DB::raw('SUM(IF(MONTH(receipt.trans_date) = 4, receipt.amount, 0)) AS Apr'),
                       DB::raw('SUM(IF(MONTH(receipt.trans_date) = 5, receipt.amount, 0)) AS May'),
                       DB::raw('SUM(IF(MONTH(receipt.trans_date) = 6, receipt.amount, 0)) AS Jun'),
                       DB::raw('SUM(IF(MONTH(receipt.trans_date) = 7, receipt.amount, 0)) AS July'),
                       DB::raw('SUM(IF(MONTH(receipt.trans_date) = 8, receipt.amount, 0)) AS Aug'),
                       DB::raw('SUM(IF(MONTH(receipt.trans_date) = 9, receipt.amount, 0)) AS Sep'),
                       DB::raw('SUM(IF(MONTH(receipt.trans_date) = 10, receipt.amount, 0)) AS Oct'),
                       DB::raw('SUM(IF(MONTH(receipt.trans_date) = 11, receipt.amount, 0)) AS Nov'),
                       DB::raw('SUM(IF(MONTH(receipt.trans_date) = 12, receipt.amount, 0)) AS December'))
                       ->get();

      $donation_others = Receipt::whereNull('cancelled_date')
                          ->where(DB::raw('YEAR(receipt.trans_date)'), '=', $input['year'])
                          ->where('receipt.glcode_id', 134)
                          ->select(DB::raw('SUM(IF(MONTH(receipt.trans_date) = 1, receipt.amount, 0)) AS Jan'),
                            DB::raw('SUM(IF(MONTH(receipt.trans_date) = 2, receipt.amount, 0)) AS Feb'),
                            DB::raw('SUM(IF(MONTH(receipt.trans_date) = 3, receipt.amount, 0)) AS Mar'),
                            DB::raw('SUM(IF(MONTH(receipt.trans_date) = 4, receipt.amount, 0)) AS Apr'),
                            DB::raw('SUM(IF(MONTH(receipt.trans_date) = 5, receipt.amount, 0)) AS May'),
                            DB::raw('SUM(IF(MONTH(receipt.trans_date) = 6, receipt.amount, 0)) AS Jun'),
                            DB::raw('SUM(IF(MONTH(receipt.trans_date) = 7, receipt.amount, 0)) AS July'),
                            DB::raw('SUM(IF(MONTH(receipt.trans_date) = 8, receipt.amount, 0)) AS Aug'),
                            DB::raw('SUM(IF(MONTH(receipt.trans_date) = 9, receipt.amount, 0)) AS Sep'),
                            DB::raw('SUM(IF(MONTH(receipt.trans_date) = 10, receipt.amount, 0)) AS Oct'),
                            DB::raw('SUM(IF(MONTH(receipt.trans_date) = 11, receipt.amount, 0)) AS Nov'),
                            DB::raw('SUM(IF(MONTH(receipt.trans_date) = 12, receipt.amount, 0)) AS December'))
                            ->get();

      $donation_non_members = Receipt::whereNull('cancelled_date')
                               ->where(DB::raw('YEAR(receipt.trans_date)'), '=', $input['year'])
                               ->where('receipt.glcode_id', 112)
                               ->select(DB::raw('SUM(IF(MONTH(receipt.trans_date) = 1, receipt.amount, 0)) AS Jan'),
                               DB::raw('SUM(IF(MONTH(receipt.trans_date) = 2, receipt.amount, 0)) AS Feb'),
                               DB::raw('SUM(IF(MONTH(receipt.trans_date) = 3, receipt.amount, 0)) AS Mar'),
                               DB::raw('SUM(IF(MONTH(receipt.trans_date) = 4, receipt.amount, 0)) AS Apr'),
                               DB::raw('SUM(IF(MONTH(receipt.trans_date) = 5, receipt.amount, 0)) AS May'),
                               DB::raw('SUM(IF(MONTH(receipt.trans_date) = 6, receipt.amount, 0)) AS Jun'),
                               DB::raw('SUM(IF(MONTH(receipt.trans_date) = 7, receipt.amount, 0)) AS July'),
                               DB::raw('SUM(IF(MONTH(receipt.trans_date) = 8, receipt.amount, 0)) AS Aug'),
                               DB::raw('SUM(IF(MONTH(receipt.trans_date) = 9, receipt.amount, 0)) AS Sep'),
                               DB::raw('SUM(IF(MONTH(receipt.trans_date) = 10, receipt.amount, 0)) AS Oct'),
                               DB::raw('SUM(IF(MONTH(receipt.trans_date) = 11, receipt.amount, 0)) AS Nov'),
                               DB::raw('SUM(IF(MONTH(receipt.trans_date) = 12, receipt.amount, 0)) AS December'))
                               ->get();

      $donation_members = Receipt::leftjoin('generaldonation', 'receipt.generaldonation_id', '=', 'generaldonation.generaldonation_id')
                       ->whereNull('cancelled_date')
                       ->where(DB::raw('YEAR(receipt.trans_date)'), '=', $input['year'])
                       ->where('receipt.glcode_id', 119)
                       ->select(DB::raw('SUM(IF(MONTH(receipt.trans_date) = 1, receipt.amount, 0)) AS Jan'),
                       DB::raw('SUM(IF(MONTH(receipt.trans_date) = 2, receipt.amount, 0)) AS Feb'),
                       DB::raw('SUM(IF(MONTH(receipt.trans_date) = 3, receipt.amount, 0)) AS Mar'),
                       DB::raw('SUM(IF(MONTH(receipt.trans_date) = 4, receipt.amount, 0)) AS Apr'),
                       DB::raw('SUM(IF(MONTH(receipt.trans_date) = 5, receipt.amount, 0)) AS May'),
                       DB::raw('SUM(IF(MONTH(receipt.trans_date) = 6, receipt.amount, 0)) AS Jun'),
                       DB::raw('SUM(IF(MONTH(receipt.trans_date) = 7, receipt.amount, 0)) AS July'),
                       DB::raw('SUM(IF(MONTH(receipt.trans_date) = 8, receipt.amount, 0)) AS Aug'),
                       DB::raw('SUM(IF(MONTH(receipt.trans_date) = 9, receipt.amount, 0)) AS Sep'),
                       DB::raw('SUM(IF(MONTH(receipt.trans_date) = 10, receipt.amount, 0)) AS Oct'),
                       DB::raw('SUM(IF(MONTH(receipt.trans_date) = 11, receipt.amount, 0)) AS Nov'),
                       DB::raw('SUM(IF(MONTH(receipt.trans_date) = 12, receipt.amount, 0)) AS December'))
                       ->get();

      $total_generaldonation = Receipt::leftjoin('generaldonation', 'receipt.generaldonation_id', '=', 'generaldonation.generaldonation_id')
                                ->whereNull('cancelled_date')
                                ->where(DB::raw('YEAR(receipt.trans_date)'), '=', $input['year'])
                                ->whereIn('receipt.glcode_id', array(112, 119))
                                ->select(DB::raw('SUM(IF(MONTH(receipt.trans_date) = 1, receipt.amount, 0)) AS Jan'),
                                DB::raw('SUM(IF(MONTH(receipt.trans_date) = 2, receipt.amount, 0)) AS Feb'),
                                DB::raw('SUM(IF(MONTH(receipt.trans_date) = 3, receipt.amount, 0)) AS Mar'),
                                DB::raw('SUM(IF(MONTH(receipt.trans_date) = 4, receipt.amount, 0)) AS Apr'),
                                DB::raw('SUM(IF(MONTH(receipt.trans_date) = 5, receipt.amount, 0)) AS May'),
                                DB::raw('SUM(IF(MONTH(receipt.trans_date) = 6, receipt.amount, 0)) AS Jun'),
                                DB::raw('SUM(IF(MONTH(receipt.trans_date) = 7, receipt.amount, 0)) AS July'),
                                DB::raw('SUM(IF(MONTH(receipt.trans_date) = 8, receipt.amount, 0)) AS Aug'),
                                DB::raw('SUM(IF(MONTH(receipt.trans_date) = 9, receipt.amount, 0)) AS Sep'),
                                DB::raw('SUM(IF(MONTH(receipt.trans_date) = 10, receipt.amount, 0)) AS Oct'),
                                DB::raw('SUM(IF(MONTH(receipt.trans_date) = 11, receipt.amount, 0)) AS Nov'),
                                DB::raw('SUM(IF(MONTH(receipt.trans_date) = 12, receipt.amount, 0)) AS December'))
                                ->get();

      return view('report.income-year-report', [
        'entrance_fees' => $entrance_fees,
        'monthly_subscriptions' => $monthly_subscriptions,
        'donation_others' => $donation_others,
        'donation_members' => $donation_members,
        'donation_non_members' => $donation_non_members,
        'total_generaldonation' => $total_generaldonation,
        'year' => $input['year']
      ]);
    }

  }

  // public function getReportDetail(Request $request)
  // {
  //   if(isset($_GET['from_date']) && isset($_GET['to_date']))
  //   {
  //     $from_date = str_replace('/', '-', $_GET['from_date']);
	// 	  $newFromDate = date("Y-m-d", strtotime($from_date));
  //
  //     $to_date = str_replace('/', '-', $_GET['to_date']);
	// 	  $newToDate = date("Y-m-d", strtotime($to_date));
  //
  //     $donation_member = GeneralDonation::join('glcode', 'glcode.glcode_id', 'generaldonation.glcode_id')
  //                        ->select(DB::raw('sum(total_amount) as `total_amount`'), DB::raw('MONTH(trans_at) month'), 'glcode.type_name')
  //                        ->where('trans_at', '>=', $newFromDate)
  //                        ->where('trans_at', '<=', $newToDate)
  //                        ->where('generaldonation.glcode_id', '=', 8)
  //                        ->GroupBy('month')
  //                        ->get();
  //
  //     $donation_nonmember = GeneralDonation::join('glcode', 'glcode.glcode_id', 'generaldonation.glcode_id')
  //                         ->select(DB::raw('sum(total_amount) as `total_amount`'), DB::raw('MONTH(trans_at) month'), 'glcode.type_name')
  //                         ->where('trans_at', '>=', $newFromDate)
  //                         ->where('trans_at', '<=', $newToDate)
  //                         ->where('generaldonation.glcode_id', '=', 12)
  //                         ->GroupBy('month')
  //                         ->get();
  //   }
  //
  //   else
  //   {
  //     $donation_member = GeneralDonation::join('glcode', 'glcode.glcode_id', 'generaldonation.glcode_id')
  //                        ->select(DB::raw('sum(total_amount) as `total_amount`'), DB::raw('MONTH(trans_at) month'), 'glcode.type_name')
  //                        ->where('generaldonation.glcode_id', '=', 8)
  //                        ->GroupBy('month')
  //                        ->get();
  //
  //     $donation_nonmember = GeneralDonation::join('glcode', 'glcode.glcode_id', 'generaldonation.glcode_id')
  //                      ->select(DB::raw('sum(total_amount) as `total_amount`'), DB::raw('MONTH(trans_at) month'), 'glcode.type_name')
  //                      ->where('generaldonation.glcode_id', '=', 12)
  //                      ->GroupBy('month')
  //                      ->get();
  //   }
  //
  //   return response()->json(array(
  //     'donation_member' => $donation_member,
  //     'donation_nonmember' => $donation_nonmember
	//   ));
  // }

}
