<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\User;
use App\Models\GeneralDonation;
use App\Models\Receipt;
use App\Models\Expenditure;
use App\Models\Paid;
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

class ReportController extends Controller
{

  public function getIncomeReport()
  {
    return view('report.income-report');
  }

  public function getCashflowReport()
  {
    return view('report.cashflow-report');
  }

  public function getTrialBalanceReport()
  {
    return view('report.trialbalance-report');
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

    // INCOME
    $income_glcode = Glcode::where('glcodegroup_id', 8)->get();

    if(isset($input['year']) && isset($nmonth))
    {
      $income_collection = collect();
      $total_income_collection = collect();
      $percentage = array();

      for($i = 0; $i < count($income_glcode); $i++)
      {
        $income = GlCode::leftjoin('glcodegroup', 'glcode.glcodegroup_id', '=', 'glcodegroup.glcodegroup_id')
                  ->leftjoin('receipt', 'glcode.glcode_id', '=', 'receipt.glcode_id')
                  ->whereNull('cancelled_date')
                  ->where('receipt.glcode_id', $income_glcode[$i]->glcode_id)
                  ->where(DB::raw('YEAR(receipt.trans_date)'), '=', $input['year'])
                  ->where(DB::raw('MONTH(receipt.trans_date)'), '=', $nmonth)
                  ->select(DB::raw('SUM(IF(YEAR(receipt.trans_date) =' . $input['year'] . ', receipt.amount, 0))' .  $input['month']),
                  'glcode.accountcode', 'glcode.type_name')
                  ->get();

        $income_collection = $income_collection->merge($income);
      }

      for($i = 0; $i < count($income_glcode); $i++)
      {
        $total_income = GlCode::leftjoin('glcodegroup', 'glcode.glcodegroup_id', '=', 'glcodegroup.glcodegroup_id')
                  ->leftjoin('receipt', 'glcode.glcode_id', '=', 'receipt.glcode_id')
                  ->whereNull('cancelled_date')
                  ->where('receipt.glcode_id', $income_glcode[$i]->glcode_id)
                  ->where(DB::raw('YEAR(receipt.trans_date)'), '=', $input['year'])
                  ->select(DB::raw('SUM(IF(YEAR(receipt.trans_date) =' . $input['year'] . ', receipt.amount, 0)) AS total'),
                  'glcode.accountcode', 'glcode.type_name')
                  ->get();

        $total_income_collection = $total_income_collection->merge($total_income);
      }

      for($i = 0; $i < count($income_collection); $i++)
      {
        if(isset($income_collection[$i]->$input['month']))
        {
          $percentage[$i] = ($income_collection[$i]->$input['month'] / $total_income_collection[$i]->total) * 100;
        }

        else
        {
          $percentage[$i] = 0.0;
        }
      }

      $today = \Carbon\Carbon::parse(Carbon::today())->format("d M Y");

      return view('report.income-monthly-report', [
        'income' => $income_collection,
        'total_income' => $total_income_collection,
        'percentage' => $percentage,
        'year' => $input['year'],
        'month' => $input['month'],
        'today' => $today
      ]);
    }

    else
    {
      $income_collection = collect();

      for($i = 0; $i < count($income_glcode); $i++)
      {
        $income = GlCode::leftjoin('glcodegroup', 'glcode.glcodegroup_id', '=', 'glcodegroup.glcodegroup_id')
                  ->leftjoin('receipt', 'glcode.glcode_id', '=', 'receipt.glcode_id')
                  ->whereNull('cancelled_date')
                  ->where(DB::raw('YEAR(receipt.trans_date)'), '=', $input['year'])
                  ->where('receipt.glcode_id', $income_glcode[$i]->glcode_id)
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
                  DB::raw('SUM(IF(MONTH(receipt.trans_date) = 12, receipt.amount, 0)) AS December'),
                  'glcode.type_name')
                  ->get();

        $income_collection = $income_collection->merge($income);
      }

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
        'income' => $income_collection,
        'donation_members' => $donation_members,
        'donation_non_members' => $donation_non_members,
        'total_generaldonation' => $total_generaldonation,
        'year' => $input['year']
      ]);
    }
  }

  public function getCashflowReportDetail(Request $request)
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
      $expenses_collection = collect();
      $total_expenses_collection = collect();

      // Expense
      $expenses_glcode = Glcode::where('glcodegroup_id', 4)->get();

      for($i = 0; $i < count($expenses_glcode); $i++)
      {
        $expenses = Glcode::leftjoin('glcodegroup', 'glcode.glcodegroup_id', '=', 'glcodegroup.glcodegroup_id')
                    ->leftjoin('expenditure', 'glcode.glcode_id', '=', 'expenditure.glcode_id')
                    ->leftjoin('paid', 'expenditure.expenditure_id', '=', 'paid.expenditure_id')
                    ->where('expenditure.glcode_id', $expenses_glcode[$i]->glcode_id)
                    ->where(DB::raw('YEAR(paid.date)'), '=', $input['year'])
                    ->where(DB::raw('MONTH(paid.date)'), '=', $nmonth)
                    ->select(DB::raw('SUM(IF(YEAR(paid.date) =' . $input['year'] . ', paid.cash_amount, 0))' . $input['month']),
                    'glcode.accountcode', 'glcode.type_name')
                    ->get();

        $expenses_collection = $expenses_collection->merge($expenses);
      }

      for($i = 0; $i < count($expenses_glcode); $i++)
      {
        $total_expenses = Glcode::leftjoin('glcodegroup', 'glcode.glcodegroup_id', '=', 'glcodegroup.glcodegroup_id')
                          ->leftjoin('expenditure', 'glcode.glcode_id', '=', 'expenditure.glcode_id')
                          ->leftjoin('paid', 'expenditure.expenditure_id', '=', 'paid.expenditure_id')
                          ->where('expenditure.glcode_id', $expenses_glcode[$i]->glcode_id)
                          ->where(DB::raw('YEAR(paid.date)'), '=', $input['year'])
                          ->select(DB::raw('SUM(IF(YEAR(paid.date) =' . $input['year'] . ', paid.cash_amount, 0)) AS total'))
                          ->get();

        $total_expenses_collection = $total_expenses_collection->merge($total_expenses);
      }

      $entrance_fees = GlCode::leftjoin('glcodegroup', 'glcode.glcodegroup_id', '=', 'glcodegroup.glcodegroup_id')
                        ->leftjoin('receipt', 'glcode.glcode_id', '=', 'receipt.glcode_id')
                        ->whereNull('cancelled_date')
                        ->where('receipt.glcode_id', 108)
                        ->where(DB::raw('YEAR(receipt.trans_date)'), '=', $input['year'])
                        ->where(DB::raw('MONTH(receipt.trans_date)'), '=', $nmonth)
                        ->select(DB::raw('SUM(IF(YEAR(receipt.trans_date) =' . $input['year'] . ', receipt.amount, 0))' . $input['month']),
                        'glcode.accountcode', 'glcode.type_name')
                        ->get();

      $total_entrance_fees = GlCode::leftjoin('glcodegroup', 'glcode.glcodegroup_id', '=', 'glcodegroup.glcodegroup_id')
                             ->leftjoin('receipt', 'glcode.glcode_id', '=', 'receipt.glcode_id')
                             ->whereNull('cancelled_date')
                             ->where('receipt.glcode_id', 108)
                             ->where(DB::raw('YEAR(receipt.trans_date)'), '=', $input['year'])
                             ->select(DB::raw('SUM(IF(YEAR(receipt.trans_date) =' . $input['year'] . ', receipt.amount, 0)) AS total'),
                             'glcode.accountcode', 'glcode.type_name')
                             ->get();

      $monthly_subscription = GlCode::leftjoin('glcodegroup', 'glcode.glcodegroup_id', '=', 'glcodegroup.glcodegroup_id')
                              ->leftjoin('receipt', 'glcode.glcode_id', '=', 'receipt.glcode_id')
                              ->whereNull('cancelled_date')
                              ->where('receipt.glcode_id', 110)
                              ->where(DB::raw('YEAR(receipt.trans_date)'), '=', $input['year'])
                              ->where(DB::raw('MONTH(receipt.trans_date)'), '=', $nmonth)
                              ->select(DB::raw('SUM(IF(YEAR(receipt.trans_date) =' . $input['year'] . ', receipt.amount, 0))' . $input['month']),
                              'glcode.accountcode', 'glcode.type_name')
                              ->get();

      $total_monthly_subscription = GlCode::leftjoin('glcodegroup', 'glcode.glcodegroup_id', '=', 'glcodegroup.glcodegroup_id')
                                      ->leftjoin('receipt', 'glcode.glcode_id', '=', 'receipt.glcode_id')
                                      ->whereNull('cancelled_date')
                                      ->where('receipt.glcode_id', 110)
                                      ->where(DB::raw('YEAR(receipt.trans_date)'), '=', $input['year'])
                                      ->select(DB::raw('SUM(IF(YEAR(receipt.trans_date) =' . $input['year'] . ', receipt.amount, 0)) AS total'),
                                      'glcode.accountcode', 'glcode.type_name')
                                      ->get();

      $donation_non_members = GlCode::leftjoin('glcodegroup', 'glcode.glcodegroup_id', '=', 'glcodegroup.glcodegroup_id')
                              ->leftjoin('receipt', 'glcode.glcode_id', '=', 'receipt.glcode_id')
                              ->whereNull('cancelled_date')
                              ->where('receipt.glcode_id', 112)
                              ->where(DB::raw('YEAR(receipt.trans_date)'), '=', $input['year'])
                              ->where(DB::raw('MONTH(receipt.trans_date)'), '=', $nmonth)
                              ->select(DB::raw('SUM(IF(YEAR(receipt.trans_date) =' . $input['year'] . ', receipt.amount, 0))' . $input['month']),
                              'glcode.accountcode', 'glcode.type_name')
                              ->get();

      $total_donation_non_members = GlCode::leftjoin('glcodegroup', 'glcode.glcodegroup_id', '=', 'glcodegroup.glcodegroup_id')
                                    ->leftjoin('receipt', 'glcode.glcode_id', '=', 'receipt.glcode_id')
                                    ->whereNull('cancelled_date')
                                    ->where('receipt.glcode_id', 112)
                                    ->where(DB::raw('YEAR(receipt.trans_date)'), '=', $input['year'])
                                    ->select(DB::raw('SUM(IF(YEAR(receipt.trans_date) =' . $input['year'] . ', receipt.amount, 0)) AS total'),
                                    'glcode.accountcode', 'glcode.type_name')
                                    ->get();

      $total_monthly_revenue = (int)$entrance_fees[0]->$input['month'] +
                               (int)$monthly_subscription[0]->$input['month'] +
                               (int)$donation_non_members[0]->$input['month'];

      $total_yearly_revenue = (int)$total_entrance_fees[0]->total +
                               (int)$total_monthly_subscription[0]->total +
                               (int)$total_donation_non_members[0]->total;

      $today = \Carbon\Carbon::parse(Carbon::today())->format("d M Y");

      return view('report.cashflow-monthly-report', [
        'expenses' => $expenses_collection,
        'total_expenses' => $total_expenses_collection,
        'entrance_fees' => $entrance_fees,
        'total_entrance_fees' => $total_entrance_fees,
        'monthly_subscription' => $monthly_subscription,
        'total_monthly_subscription' => $total_monthly_subscription,
        'donation_non_members' => $donation_non_members,
        'total_donation_non_members' => $total_donation_non_members,
        'total_monthly_revenue' => $total_monthly_revenue,
        'total_yearly_revenue' => $total_yearly_revenue,
        'year' => $input['year'],
        'month' => $input['month'],
        'today' => $today
      ]);
    }

    else
    {
      $expenses_collection = collect();

      // Expense
      $expenses_glcode = Glcode::where('glcodegroup_id', 4)->get();

      for($i = 0; $i < count($expenses_glcode); $i++)
      {
        $expenses = Glcode::leftjoin('glcodegroup', 'glcode.glcodegroup_id', '=', 'glcodegroup.glcodegroup_id')
                    ->leftjoin('expenditure', 'glcode.glcode_id', '=', 'expenditure.glcode_id')
                    ->leftjoin('paid', 'expenditure.expenditure_id', '=', 'paid.expenditure_id')
                    ->where('expenditure.glcode_id', $expenses_glcode[$i]->glcode_id)
                    ->where(DB::raw('YEAR(paid.date)'), '=', $input['year'])
                    ->select(DB::raw('SUM(IF(MONTH(paid.date) = 1, paid.cash_amount, 0)) AS Jan'),
                    DB::raw('SUM(IF(MONTH(paid.date) = 2, paid.cash_amount, 0)) AS Feb'),
                    DB::raw('SUM(IF(MONTH(paid.date) = 3, paid.cash_amount, 0)) AS Mar'),
                    DB::raw('SUM(IF(MONTH(paid.date) = 4, paid.cash_amount, 0)) AS Apr'),
                    DB::raw('SUM(IF(MONTH(paid.date) = 5, paid.cash_amount, 0)) AS May'),
                    DB::raw('SUM(IF(MONTH(paid.date) = 6, paid.cash_amount, 0)) AS Jun'),
                    DB::raw('SUM(IF(MONTH(paid.date) = 7, paid.cash_amount, 0)) AS July'),
                    DB::raw('SUM(IF(MONTH(paid.date) = 8, paid.cash_amount, 0)) AS Aug'),
                    DB::raw('SUM(IF(MONTH(paid.date) = 9, paid.cash_amount, 0)) AS Sep'),
                    DB::raw('SUM(IF(MONTH(paid.date) = 10, paid.cash_amount, 0)) AS Oct'),
                    DB::raw('SUM(IF(MONTH(paid.date) = 11, paid.cash_amount, 0)) AS Nov'),
                    DB::raw('SUM(IF(MONTH(paid.date) = 12, paid.cash_amount, 0)) AS December'),
                    'glcode.type_name')
                    ->get();

        $expenses_collection = $expenses_collection->merge($expenses);
      }

      return view('report.cashflow-year-report', [
        'expenses' => $expenses_collection,
        'year' => $input['year']
      ]);
    }
  }

  public function getTrialBalanceReportDetail(Request $request)
  {
    $input = array_except($request->all(), '_token');

    $expenses_collection = collect();
    $income_collection = collect();

    $expenses_glcode = Glcode::where('glcodegroup_id', 4)->get();

    for($i = 0; $i < count($expenses_glcode); $i++)
    {
      $expenses = Glcode::leftjoin('glcodegroup', 'glcode.glcodegroup_id', '=', 'glcodegroup.glcodegroup_id')
                  ->leftjoin('expenditure', 'glcode.glcode_id', '=', 'expenditure.glcode_id')
                  ->leftjoin('paid', 'expenditure.expenditure_id', '=', 'paid.expenditure_id')
                  ->where('expenditure.glcode_id', $expenses_glcode[$i]->glcode_id)
                  ->select(DB::raw('SUM(IF(YEAR(paid.date) =' . $input['year'] . ', paid.cash_amount, 0)) AS total'),
                  'glcode.accountcode', 'glcode.type_name')
                  ->get();

      $expenses_collection = $expenses_collection->merge($expenses);
    }

    $income_glcode = Glcode::where('glcodegroup_id', 8)->get();

    for($i = 0; $i < count($income_glcode); $i++)
    {
      $income = GlCode::leftjoin('glcodegroup', 'glcode.glcodegroup_id', '=', 'glcodegroup.glcodegroup_id')
                ->leftjoin('receipt', 'glcode.glcode_id', '=', 'receipt.glcode_id')
                ->whereNull('cancelled_date')
                ->where('receipt.glcode_id', $income_glcode[$i]->glcode_id)
                ->select(DB::raw('SUM(IF(YEAR(receipt.trans_date) =' . $input['year'] . ', receipt.amount, 0)) AS total'),
                'glcode.accountcode', 'glcode.type_name')
                ->get();

      $income_collection = $income_collection->merge($income);
    }

    return view('report.trialbalance-year-report',[
      'expenses' => $expenses_collection,
      'income' => $income_collection
    ]);
  }

}
