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

  public function getSettlementReport()
  {
    $user = User::all();

    $glcode = GlCode::whereIn('glcode_id', array(108, 110, 112, 119, 134))
              ->select('glcode_id', 'type_name')
              ->get();

    return view('report.settlement-report', [
      'attendedby' => $user,
      'glcode' => $glcode
    ]);
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

      $ocbc_account = GlCode::leftjoin('glcodegroup', 'glcode.glcodegroup_id', '=', 'glcodegroup.glcodegroup_id')
                        ->leftjoin('payment_voucher', 'glcode.glcode_id', '=', 'payment_voucher.cheque_account')
                        ->where('payment_voucher.cheque_account', 7)
                        ->where(DB::raw('YEAR(payment_voucher.date)'), '=', $input['year'])
                        ->where(DB::raw('MONTH(payment_voucher.date)'), '=', $nmonth)
                        ->select(DB::raw('SUM(IF(YEAR(payment_voucher.date) =' . $input['year'] . ', payment_voucher.cheque_amount, 0))' . $input['month']),
                        'glcode.accountcode', 'glcode.type_name')
                        ->get();

      $total_ocbc_account = GlCode::leftjoin('glcodegroup', 'glcode.glcodegroup_id', '=', 'glcodegroup.glcodegroup_id')
                              ->leftjoin('payment_voucher', 'glcode.glcode_id', '=', 'payment_voucher.cheque_account')
                              ->where('payment_voucher.cheque_account', 7)
                              ->where(DB::raw('YEAR(payment_voucher.date)'), '=', $input['year'])
                              ->select(DB::raw('SUM(IF(YEAR(payment_voucher.date) =' . $input['year'] . ', payment_voucher.cheque_amount, 0)) AS total'),
                                'glcode.accountcode', 'glcode.type_name')
                              ->get();

      $ocbc_account2 = GlCode::leftjoin('glcodegroup', 'glcode.glcodegroup_id', '=', 'glcodegroup.glcodegroup_id')
                        ->leftjoin('payment_voucher', 'glcode.glcode_id', '=', 'payment_voucher.cheque_account')
                        ->where('payment_voucher.cheque_account', 8)
                        ->where(DB::raw('YEAR(payment_voucher.date)'), '=', $input['year'])
                        ->where(DB::raw('MONTH(payment_voucher.date)'), '=', $nmonth)
                        ->select(DB::raw('SUM(IF(YEAR(payment_voucher.date) =' . $input['year'] . ', payment_voucher.cheque_amount, 0))' . $input['month']),
                        'glcode.accountcode', 'glcode.type_name')
                        ->get();

      $total_ocbc_account2 = GlCode::leftjoin('glcodegroup', 'glcode.glcodegroup_id', '=', 'glcodegroup.glcodegroup_id')
                            ->leftjoin('payment_voucher', 'glcode.glcode_id', '=', 'payment_voucher.cheque_account')
                            ->where('payment_voucher.cheque_account', 8)
                            ->where(DB::raw('YEAR(payment_voucher.date)'), '=', $input['year'])
                            ->select(DB::raw('SUM(IF(YEAR(payment_voucher.date) =' . $input['year'] . ', payment_voucher.cheque_amount, 0)) AS total'),
                              'glcode.accountcode', 'glcode.type_name')
                            ->get();

      $cash_on_hand = GlCode::leftjoin('glcodegroup', 'glcode.glcodegroup_id', '=', 'glcodegroup.glcodegroup_id')
                      ->leftjoin('pettycash_voucher', 'glcode.glcode_id', '=', 'pettycash_voucher.glcode_id')
                      ->where(DB::raw('YEAR(pettycash_voucher.date)'), '=', $input['year'])
                      ->where(DB::raw('MONTH(pettycash_voucher.date)'), '=', $nmonth)
                      ->select(DB::raw('SUM(IF(YEAR(pettycash_voucher.date) =' . $input['year'] . ', pettycash_voucher.cash_amount, 0))' . $input['month']),
                        'glcode.accountcode', 'glcode.type_name')
                      ->get();

      $cash_on_hand[0]->type_name = "Beginning Cash On Hand Total";

      $total_cash_on_hand = GlCode::leftjoin('glcodegroup', 'glcode.glcodegroup_id', '=', 'glcodegroup.glcodegroup_id')
                             ->leftjoin('pettycash_voucher', 'glcode.glcode_id', '=', 'pettycash_voucher.glcode_id')
                             ->where(DB::raw('YEAR(pettycash_voucher.date)'), '=', $input['year'])
                             ->select(DB::raw('SUM(IF(YEAR(pettycash_voucher.date) =' . $input['year'] . ', pettycash_voucher.cash_amount, 0)) AS total'),
                              'glcode.accountcode', 'glcode.type_name')
                             ->get();

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
        'ocbc_account' => $ocbc_account,
        'total_ocbc_account' => $total_ocbc_account,
        'ocbc_account2' => $ocbc_account2,
        'total_ocbc_account2' => $total_ocbc_account2,
        'cash_on_hand' => $cash_on_hand,
        'total_cash_on_hand' => $total_cash_on_hand,
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

      $entrance_fees = GlCode::leftjoin('glcodegroup', 'glcode.glcodegroup_id', '=', 'glcodegroup.glcodegroup_id')
                        ->leftjoin('receipt', 'glcode.glcode_id', '=', 'receipt.glcode_id')
                        ->whereNull('cancelled_date')
                        ->where('receipt.glcode_id', 108)
                        ->where(DB::raw('YEAR(receipt.trans_date)'), '=', $input['year'])
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

      $monthly_subscription = GlCode::leftjoin('glcodegroup', 'glcode.glcodegroup_id', '=', 'glcodegroup.glcodegroup_id')
                              ->leftjoin('receipt', 'glcode.glcode_id', '=', 'receipt.glcode_id')
                              ->whereNull('cancelled_date')
                              ->where('receipt.glcode_id', 110)
                              ->where(DB::raw('YEAR(receipt.trans_date)'), '=', $input['year'])
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

      $ocbc_account = GlCode::leftjoin('glcodegroup', 'glcode.glcodegroup_id', '=', 'glcodegroup.glcodegroup_id')
                      ->leftjoin('payment_voucher', 'glcode.glcode_id', '=', 'payment_voucher.cheque_account')
                      ->where('payment_voucher.cheque_account', 7)
                      ->where(DB::raw('YEAR(payment_voucher.date)'), '=', $input['year'])
                      ->select(DB::raw('SUM(IF(MONTH(payment_voucher.date) = 1, payment_voucher.cheque_amount, 0)) AS Jan'),
                      DB::raw('SUM(IF(MONTH(payment_voucher.date) = 2, payment_voucher.cheque_amount, 0)) AS Feb'),
                      DB::raw('SUM(IF(MONTH(payment_voucher.date) = 3, payment_voucher.cheque_amount, 0)) AS Mar'),
                      DB::raw('SUM(IF(MONTH(payment_voucher.date) = 4, payment_voucher.cheque_amount, 0)) AS Apr'),
                      DB::raw('SUM(IF(MONTH(payment_voucher.date) = 5, payment_voucher.cheque_amount, 0)) AS May'),
                      DB::raw('SUM(IF(MONTH(payment_voucher.date) = 6, payment_voucher.cheque_amount, 0)) AS Jun'),
                      DB::raw('SUM(IF(MONTH(payment_voucher.date) = 7, payment_voucher.cheque_amount, 0)) AS July'),
                      DB::raw('SUM(IF(MONTH(payment_voucher.date) = 8, payment_voucher.cheque_amount, 0)) AS Aug'),
                      DB::raw('SUM(IF(MONTH(payment_voucher.date) = 9, payment_voucher.cheque_amount, 0)) AS Sep'),
                      DB::raw('SUM(IF(MONTH(payment_voucher.date) = 10, payment_voucher.cheque_amount, 0)) AS Oct'),
                      DB::raw('SUM(IF(MONTH(payment_voucher.date) = 11, payment_voucher.cheque_amount, 0)) AS Nov'),
                      DB::raw('SUM(IF(MONTH(payment_voucher.date) = 12, payment_voucher.cheque_amount, 0)) AS December'),
                      'glcode.type_name')
                      ->get();

      $ocbc_account2 = GlCode::leftjoin('glcodegroup', 'glcode.glcodegroup_id', '=', 'glcodegroup.glcodegroup_id')
                        ->leftjoin('payment_voucher', 'glcode.glcode_id', '=', 'payment_voucher.cheque_account')
                        ->where('payment_voucher.cheque_account', 8)
                        ->where(DB::raw('YEAR(payment_voucher.date)'), '=', $input['year'])
                        ->select(DB::raw('SUM(IF(MONTH(payment_voucher.date) = 1, payment_voucher.cheque_amount, 0)) AS Jan'),
                        DB::raw('SUM(IF(MONTH(payment_voucher.date) = 2, payment_voucher.cheque_amount, 0)) AS Feb'),
                        DB::raw('SUM(IF(MONTH(payment_voucher.date) = 3, payment_voucher.cheque_amount, 0)) AS Mar'),
                        DB::raw('SUM(IF(MONTH(payment_voucher.date) = 4, payment_voucher.cheque_amount, 0)) AS Apr'),
                        DB::raw('SUM(IF(MONTH(payment_voucher.date) = 5, payment_voucher.cheque_amount, 0)) AS May'),
                        DB::raw('SUM(IF(MONTH(payment_voucher.date) = 6, payment_voucher.cheque_amount, 0)) AS Jun'),
                        DB::raw('SUM(IF(MONTH(payment_voucher.date) = 7, payment_voucher.cheque_amount, 0)) AS July'),
                        DB::raw('SUM(IF(MONTH(payment_voucher.date) = 8, payment_voucher.cheque_amount, 0)) AS Aug'),
                        DB::raw('SUM(IF(MONTH(payment_voucher.date) = 9, payment_voucher.cheque_amount, 0)) AS Sep'),
                        DB::raw('SUM(IF(MONTH(payment_voucher.date) = 10, payment_voucher.cheque_amount, 0)) AS Oct'),
                        DB::raw('SUM(IF(MONTH(payment_voucher.date) = 11, payment_voucher.cheque_amount, 0)) AS Nov'),
                        DB::raw('SUM(IF(MONTH(payment_voucher.date) = 12, payment_voucher.cheque_amount, 0)) AS December'),
                        'glcode.type_name')
                        ->get();

      $cash_on_hand = GlCode::leftjoin('glcodegroup', 'glcode.glcodegroup_id', '=', 'glcodegroup.glcodegroup_id')
                        ->leftjoin('pettycash_voucher', 'glcode.glcode_id', '=', 'pettycash_voucher.glcode_id')
                        ->where('pettycash_voucher.glcode_id', 11)
                        ->where(DB::raw('YEAR(pettycash_voucher.date)'), '=', $input['year'])
                        ->select(DB::raw('SUM(IF(MONTH(pettycash_voucher.date) = 1, pettycash_voucher.cash_amount, 0)) AS Jan'),
                        DB::raw('SUM(IF(MONTH(pettycash_voucher.date) = 2, pettycash_voucher.cash_amount, 0)) AS Feb'),
                        DB::raw('SUM(IF(MONTH(pettycash_voucher.date) = 3, pettycash_voucher.cash_amount, 0)) AS Mar'),
                        DB::raw('SUM(IF(MONTH(pettycash_voucher.date) = 4, pettycash_voucher.cash_amount, 0)) AS Apr'),
                        DB::raw('SUM(IF(MONTH(pettycash_voucher.date) = 5, pettycash_voucher.cash_amount, 0)) AS May'),
                        DB::raw('SUM(IF(MONTH(pettycash_voucher.date) = 6, pettycash_voucher.cash_amount, 0)) AS Jun'),
                        DB::raw('SUM(IF(MONTH(pettycash_voucher.date) = 7, pettycash_voucher.cash_amount, 0)) AS July'),
                        DB::raw('SUM(IF(MONTH(pettycash_voucher.date) = 8, pettycash_voucher.cash_amount, 0)) AS Aug'),
                        DB::raw('SUM(IF(MONTH(pettycash_voucher.date) = 9, pettycash_voucher.cash_amount, 0)) AS Sep'),
                        DB::raw('SUM(IF(MONTH(pettycash_voucher.date) = 10, pettycash_voucher.cash_amount, 0)) AS Oct'),
                        DB::raw('SUM(IF(MONTH(pettycash_voucher.date) = 11, pettycash_voucher.cash_amount, 0)) AS Nov'),
                        DB::raw('SUM(IF(MONTH(pettycash_voucher.date) = 12, pettycash_voucher.cash_amount, 0)) AS December'),
                        'glcode.type_name')
                        ->get();

      $donation_non_members = GlCode::leftjoin('glcodegroup', 'glcode.glcodegroup_id', '=', 'glcodegroup.glcodegroup_id')
                              ->leftjoin('receipt', 'glcode.glcode_id', '=', 'receipt.glcode_id')
                              ->whereNull('cancelled_date')
                              ->where('receipt.glcode_id', 112)
                              ->where(DB::raw('YEAR(receipt.trans_date)'), '=', $input['year'])
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

      return view('report.cashflow-year-report', [
        'expenses' => $expenses_collection,
        'entrance_fees' => $entrance_fees,
        'monthly_subscription' => $monthly_subscription,
        'ocbc_account' => $ocbc_account,
        'ocbc_account2' => $ocbc_account2,
        'cash_on_hand' => $cash_on_hand,
        'donation_non_members' => $donation_non_members,
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

  public function getSettlementReportDetail(Request $request)
  {
    $input = array_except($request->all(), '_token');

    $result_collection = collect();

    $date = str_replace('/', '-', $input['date']);
    $date = date("Y-m-d", strtotime($date) );

    $mode = ['cash', 'cheque', 'nets', 'receipt'];
    $glcode_array = [108, 110, 112, 119, 134];
    $total_cash = 0;
    $total_nets = 0;
    $total_cheque = 0;
    $total_amount = 0;
    $total_receipt = 0;

    if($input['type'] == 0)
    {
      for($i = 0; $i < count($glcode_array); $i++)
      {
        $result = Receipt::rightjoin('generaldonation', 'receipt.generaldonation_id', '=', 'generaldonation.generaldonation_id')
                  ->rightjoin('glcode', 'receipt.glcode_id', '=', 'glcode.glcode_id')
                  ->where('receipt.staff_id', $input['staff_id'])
                  ->where('receipt.trans_date', $date)
                  ->where('receipt.glcode_id', $glcode_array[$i])
                  ->select(DB::raw('SUM(IF(generaldonation.mode_payment = "cash", receipt.amount, 0)) AS cash'),
                  DB::raw('SUM(IF(generaldonation.mode_payment = "cheque", receipt.amount, 0)) AS cheque'),
                  DB::raw('SUM(IF(generaldonation.mode_payment = "nets", receipt.amount, 0)) AS nets'),
                  DB::raw('SUM(IF(generaldonation.mode_payment = "receipt", receipt.amount, 0)) AS receipt'),
                  'glcode.type_name')
                  ->get();

        $result_collection = $result_collection->merge($result);
      }

      for($i = 0; $i < count($result_collection); $i++)
      {
        if(isset($result_collection[$i]->cash))
        {
          $result_collection[$i]->amount += $result_collection[$i]->cash + $result_collection[$i]->cheque + $result_collection[$i]->nets + $result_collection[$i]->receipt;

          $total_cash += $result_collection[$i]->cash;
          $total_nets += $result_collection[$i]->nets;
          $total_cheque += $result_collection[$i]->cheque;
          $total_receipt += $result_collection[$i]->receipt;

          $total_amount = $total_cash + $total_nets + $total_cheque + $total_receipt;
        }

        else
        {
          $result_collection[$i]->amount = 0;
        }
      }

      $user_name = User::where('id', $input['staff_id'])->pluck('user_name');

      return view('report.settlement-report-detail', [
        'result' => $result_collection,
        'date' => $input['date'],
        'todaydate' => $date,
        'attendedby' => $user_name[0],
        'total_cash' => $total_cash,
        'total_nets' => $total_nets,
        'total_cheque' => $total_cheque,
        'total_receipt' => $total_receipt,
        'total_amount' => $total_amount
      ]);
    }

    else
    {
      $result = Receipt::rightjoin('generaldonation', 'receipt.generaldonation_id', '=', 'generaldonation.generaldonation_id')
                ->rightjoin('glcode', 'receipt.glcode_id', '=', 'glcode.glcode_id')
                ->where('receipt.staff_id', $input['staff_id'])
                ->where('receipt.trans_date', $date)
                ->where('receipt.glcode_id', $input['type'])
                ->select(DB::raw('SUM(IF(generaldonation.mode_payment = "cash", receipt.amount, 0)) AS cash'),
                DB::raw('SUM(IF(generaldonation.mode_payment = "cheque", receipt.amount, 0)) AS cheque'),
                DB::raw('SUM(IF(generaldonation.mode_payment = "nets", receipt.amount, 0)) AS nets'),
                'glcode.type_name')
                ->get();

        // $result = Receipt::rightjoin('generaldonation', 'receipt.generaldonation_id', '=', 'generaldonation.generaldonation_id')
        //           ->rightjoin('glcode', 'receipt.glcode_id', '=', 'glcode.glcode_id')
        //           ->where('receipt.staff_id', $input['staff_id'])
        //           ->where('receipt.trans_date', $date)
        //           ->where('receipt.glcode_id', $input['type'])
        //           ->where('generaldonation.mode_payment', $mode[$i])
        //           ->select(DB::raw('SUM(receipt.amount) as ' . $mode[$i] . '_total'), 'glcode.type_name')
        //           ->get();
        //
        $result_collection = $result_collection->merge($result);

        $type_name = Glcode::where('glcode_id', $input['type'])->get();


      $user_name = User::where('id', $input['staff_id'])->pluck('user_name');

      return view('report.settlement-report-by-type', [
        'result' => $result_collection,
        'date' => $input['date'],
        'type_name' => $type_name,
        'attendedby' => $user_name[0]
      ]);
    }
  }


}
