<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\User;
use App\Models\SettingKongdan;
use App\Models\Devotee;
use App\Models\KongdanGeneraldonation;
use App\Models\KongdanReceipt;
use App\Models\FestiveEvent;
use App\Models\GlCode;
use App\Models\JournalEntry;
use App\Models\JournalEntryItem;
use App\Models\Rct;
use Auth;
use DB;
use Hash;
use Mail;
use Input;
use Session;
use View;
use URL;
use Carbon\Carbon;

class FahuiController extends Controller
{
  public function getKongDan()
  {
    $today = Carbon::today();

		$events = FestiveEvent::orderBy('start_at', 'asc')
							->where('start_at', '>', $today)
							->take(1)
							->get();

    return view('fahui.kongdan', [
			'events' => $events
		]);
  }

  // public function postKongDan(Request $request)
  // {
  //   $input = array_except($request->all(), '_token');
	// 	$total_amount = 0;
  //
  //   // Modify Receipt At fields
	//   if(isset($input['receipt_at']))
	//   {
	//     $input_receipt_at = str_replace('/', '-', $input['receipt_at']);
	//     $receipt_at = date("Y-m-d", strtotime($input_receipt_at));
	//   }
	//   else
	//   {
	//     $receipt_at = $input['receipt_at'];
	//   }
  //
  //   if(count(KongdanGeneraldonation::all()) > 0)
  //   {
  //     $trans_id = KongdanGeneraldonation::all()->last()->generaldonation_id;
  //   }
  //
  //   else {
  //     $trans_id = 0;
  //   }
  //
	//   $prefix = "T";
	//   $trans_id += 1;
	//   $trans_id = $prefix . $trans_id;
  //
  //   if(empty($input['festiveevent_id']))
	// 	{
	// 		$input['festiveevent_id'] = 0;
	// 	}
  //
  //   $data = [
	//     "trans_no" => $trans_id,
	//     "description" => "KongDan - 孔诞",
	//     "hjgr" => $input['hjgr'],
	//     "total_amount" => $input['total_amount'],
	//     "mode_payment" => $input['mode_payment'],
	//     "cheque_no" => $input['cheque_no'],
	// 		"nets_no" => $input['nets_no'],
	//     "receipt_at" =>	$receipt_at,
	//     "manualreceipt" => $input['manualreceipt'],
	//     "trans_at" => Carbon::now(),
	//     "focusdevotee_id" => $input['focusdevotee_id'],
	//     "festiveevent_id" => $input['festiveevent_id']
	//   ];
  //
  //   $kongdan_generaldonation = KongdanGeneraldonation::create($data);
  //
  //   if($kongdan_generaldonation)
	// 	{
  //     for($i = 0; $i < count($input['hidden_kongdan_amount']); $i++)
  //     {
  //       if($input['hidden_kongdan_amount'][$i] == 1)
  //       {
  //         $devotee = Devotee::find($input['devotee_id'][$i]);
  //
  //         $devotee->lasttransaction_at = Carbon::now();
  //         $devotee->save();
  //
  //         if(count(KongdanReceipt::all()) > 0)
  //         {
  //           $same_receipt = KongdanReceipt::all()->last()->receipt_id;
  //         }
  //
  //         else {
  //           $result = GlCode::where('glcode_id', '117')->pluck('next_sn_number');
  //           $same_receipt = $result[0];
  //         }
  //
  //         $prefix = GlCode::where('glcode_id', '117')->pluck('receipt_prefix');
  //         $prefix = $prefix[0];
  //         $same_receipt += 1;
  //
  //         $year = date('Y');
  //         $year = substr( $year, -2);
  //
  //         $receipt = str_pad($same_receipt, 4, 0, STR_PAD_LEFT);
  //         $receipt = $prefix . $year . $receipt;
  //
  //         $data = [
  //           "receipt_no" => $receipt,
  //           "trans_date" => Carbon::now(),
  //           "description" => "KongDan - 孔诞",
  //           "amount" => 10,
  //           "glcode_id" => 117,
  //           "devotee_id" => $input['devotee_id'][$i],
  //           "generaldonation_id" => $kongdan_generaldonation->generaldonation_id,
  //           "staff_id" => Auth::user()->id
  //         ];
  //
  //         KongdanReceipt::create($data);
  //       }
  //     }
  //   }
  //
  //   // Create Journal
	// 	$year = date("y");
  //
  //   if(count(JournalEntry::all()))
  //   {
  //     $journalentry_id = JournalEntry::all()->last()->journalentry_id;
  //     $journalentry_id = str_pad($journalentry_id + 1, 4, 0, STR_PAD_LEFT);
  //   }
  //
  //   else
  //   {
  //     $journalentry_id = 0;
  //     $journalentry_id = str_pad($journalentry_id + 1, 4, 0, STR_PAD_LEFT);
  //   }
  //
  //   $reference_no = 'J-' . $year . $journalentry_id;
  //
	// 	$data = [
  //     "journalentry_no" => $reference_no,
  //     "date" => Carbon::now(),
  //     "description" => "Kongdan - 孔诞",
	// 		"devotee_id" => $input['focusdevotee_id'],
  //     "type" => "journal",
  //     "total_debit_amount" => $input['total_amount'],
  //     "total_credit_amount" => $input['total_amount']
  //   ];
  //
	// 	$journalentry = JournalEntry::create($data);
  //
	// 	$data = [
	// 		"glcode_id" => 9,
	// 		"debit_amount" => $input['total_amount'],
	// 		"credit_amount" => null,
	// 		"journalentry_id" => $journalentry->journalentry_id
	// 	];
  //
	// 	JournalEntryItem::create($data);
  //
	// 	$data = [
	// 		"glcode_id" => 117,
	// 		"debit_amount" => null,
	// 		"credit_amount" => $input['total_amount'],
	// 		"journalentry_id" => $journalentry->journalentry_id
	// 	];
  //
	// 	JournalEntryItem::create($data);
  //
  //   // remove session
	//   Session::forget('kongdan_receipts');
  //
  //   $devotee = Devotee::where('devotee_id', $input['focusdevotee_id'])->get();
  //
  //   $kongdan_same_family = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
  //                          ->leftjoin('setting_kongdan', 'devotee.devotee_id', '=', 'setting_kongdan.devotee_id')
  //                          ->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
  //                          ->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
  //                          ->where('devotee.familycode_id', $devotee[0]->familycode_id)
	// 												 ->where('devotee.devotee_id', '!=', $devotee[0]->devotee_id)
  //                          ->where('setting_kongdan.address_code', '=', 'same')
  //                          ->where('setting_kongdan.kongdan_id', '=', '1')
	// 												 ->where('setting_kongdan.focusdevotee_id', '=', $devotee[0]->devotee_id)
  //                          ->select('devotee.*', 'member.member', 'familycode.familycode', 'member.paytill_date', 'specialremarks.devotee_id as specialremarks_devotee_id')
	// 												 ->GroupBy('devotee.devotee_id')
  //                          ->get();
  //
	// 	for($i = 0; $i < count($kongdan_same_family); $i++)
	// 	{
	// 		$hasreceipt = KongdanReceipt::where('devotee_id', $kongdan_same_family[$i]->devotee_id)->get();
  //
	// 		if(count($hasreceipt) > 0)
	// 		{
	// 			$same_xy_receipt = KongdanReceipt::all()
	// 												 ->where('devotee_id', $kongdan_same_family[$i]->devotee_id)
	// 												 ->last()
	// 												 ->xy_receipt;
  //
	// 			$kongdan_same_family[$i]->xyreceipt = $same_xy_receipt;
	// 		}
  //
	// 		else {
	// 			$kongdan_same_family[$i]->xyreceipt = "";
	// 		}
	// 	}
  //
	// 	$kongdan_same_focusdevotee = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
	// 		                           ->leftjoin('setting_kongdan', 'devotee.devotee_id', '=', 'setting_kongdan.devotee_id')
	// 		                           ->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
	// 		                           ->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
	// 		                           ->where('setting_kongdan.address_code', '=', 'same')
	// 		                           ->where('setting_kongdan.kongdan_id', '=', '1')
	// 															 ->where('setting_kongdan.focusdevotee_id', '=', $devotee[0]->devotee_id)
	// 															 ->where('setting_kongdan.devotee_id', '=', $devotee[0]->devotee_id)
	// 		                           ->select('devotee.*', 'member.member', 'familycode.familycode', 'member.paytill_date', 'specialremarks.devotee_id as specialremarks_devotee_id')
	// 															 ->GroupBy('devotee.devotee_id')
	// 		                           ->get();
  //
	// 	for($i = 0; $i < count($kongdan_same_focusdevotee); $i++)
	// 	{
	// 		$hasreceipt = KongdanReceipt::where('devotee_id', $kongdan_same_focusdevotee[0]->devotee_id)->get();
  //
	// 		if(count($hasreceipt) > 0)
	// 		{
	// 			$same_xy_receipt = KongdanReceipt::all()
	// 												 ->where('devotee_id', $kongdan_same_focusdevotee[0]->devotee_id)
	// 												 ->last()
	// 												 ->xy_receipt;
  //
	// 			$kongdan_same_focusdevotee[0]->xyreceipt = $same_xy_receipt;
	// 		}
  //
	// 		else {
	// 			$kongdan_same_focusdevotee[0]->xyreceipt = "";
	// 		}
	// 	}
  //
	// 	$kongdan_different_family = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
	// 										          ->leftjoin('setting_kongdan', 'devotee.devotee_id', '=', 'setting_kongdan.devotee_id')
	// 										          ->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
	// 										          ->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
	// 										          ->where('devotee.devotee_id', '!=', $devotee[0]->devotee_id)
	// 										          ->where('setting_kongdan.address_code', '=', 'different')
	// 										          ->where('setting_kongdan.kongdan_id', '=', '1')
	// 										          ->where('setting_kongdan.focusdevotee_id', '=', $devotee[0]->devotee_id)
	// 										          ->select('devotee.*', 'member.member', 'member.paytill_date', 'specialremarks.devotee_id as specialremarks_devotee_id', 'familycode.familycode')
	// 										          ->GroupBy('devotee.devotee_id')
	// 										          ->get();
  //
	// 	for($i = 0; $i < count($kongdan_different_family); $i++)
	// 	{
	// 		$hasreceipt = KongdanReceipt::where('devotee_id', $kongdan_different_family[$i]->devotee_id)->get();
  //
	// 		if(count($hasreceipt) > 0)
	// 		{
	// 			$same_xy_receipt = KongdanReceipt::all()
	// 												 ->where('devotee_id', $kongdan_different_family[$i]->devotee_id)
	// 												 ->last()
	// 												 ->xy_receipt;
  //
	// 			$kongdan_different_family[$i]->xyreceipt = $same_xy_receipt;
	// 		}
  //
	// 		else {
	// 			$kongdan_different_family[$i]->xyreceipt = "";
	// 		}
	// 	}
  //
  //   $kongdan_receipt_collection = collect();
  //
  //   $kongdan_receipts = KongdanGeneraldonation::leftjoin('devotee', 'devotee.devotee_id', '=', 'kongdan_generaldonation.focusdevotee_id')
  //       								->leftjoin('kongdan_receipt', 'kongdan_receipt.generaldonation_id', '=', 'kongdan_generaldonation.generaldonation_id')
  //       								->where('kongdan_generaldonation.focusdevotee_id', '=', $input['focusdevotee_id'])
  //       								->GroupBy('kongdan_generaldonation.generaldonation_id')
  //       								->where('kongdan_receipt.glcode_id', 117)
  //       								->select('kongdan_generaldonation.*', 'devotee.chinese_name', 'kongdan_receipt.cancelled_date')
  //       								->orderBy('kongdan_generaldonation.generaldonation_id', 'desc')
  //       								->get();
  //
  //   $paidby_otherkongdan_receipts = KongdanReceipt::leftjoin('kongdan_generaldonation', 'kongdan_receipt.generaldonation_id', '=', 'kongdan_generaldonation.generaldonation_id')
	// 																	->leftjoin('devotee', 'devotee.devotee_id', '=', 'kongdan_generaldonation.focusdevotee_id')
	// 																	->where('kongdan_receipt.devotee_id', $input['focusdevotee_id'])
	// 																	->where('kongdan_receipt.glcode_id', 117)
	// 																	->where('kongdan_generaldonation.focusdevotee_id', '!=', $input['focusdevotee_id'])
	// 																	->select('kongdan_generaldonation.*', 'devotee.chinese_name', 'kongdan_receipt.cancelled_date', 'kongdan_receipt.receipt_no')
	// 																	->get();
  //
  //   if(count($kongdan_receipts) > 0)
	// 	{
	// 		for($i = 0; $i < count($kongdan_receipts); $i++)
	// 		{
	// 			$data = KongdanReceipt::where('generaldonation_id', $kongdan_receipts[$i]->generaldonation_id)->pluck('receipt_no');
	// 			$receipt_count = count($data);
  //
	// 			if($receipt_count > 1)
	// 			{
	// 				$kongdan_receipts[$i]->receipt_no = $data[0] . ' - ' . $data[$receipt_count - 1];
	// 			}
	// 			else
	// 			{
	// 				$kongdan_receipts[$i]->receipt_no = $data[0];
	// 			}
	// 		}
	// 	}
  //
  //   $kongdan_receipt_collection = $kongdan_receipt_collection->merge($kongdan_receipts);
	// 	$kongdan_receipt_collection = $kongdan_receipt_collection->merge($paidby_otherkongdan_receipts);
  //
	// 	$kongdan_receipts = $kongdan_receipt_collection->sortByDesc('generaldonation_id');
	// 	$kongdan_receipts->values()->all();
  //
  //   Session::put('kongdan_receipts', $kongdan_receipts);
  //
  //   $kongdan_generaldonation_id = $kongdan_generaldonation->generaldonation_id;
	// 	$hjgr = $kongdan_generaldonation->hjgr;
  //
  //   $result = KongdanReceipt::leftjoin('kongdan_generaldonation', 'kongdan_receipt.generaldonation_id', '=', 'kongdan_generaldonation.generaldonation_id')
	// 						 ->leftjoin('devotee', 'kongdan_receipt.devotee_id', '=', 'devotee.devotee_id')
	// 						 ->leftjoin('user', 'kongdan_receipt.staff_id', '=', 'user.id')
	// 						 ->leftjoin('festiveevent', 'kongdan_generaldonation.festiveevent_id', '=', 'festiveevent.festiveevent_id')
	// 						 ->where('kongdan_generaldonation.generaldonation_id', '=', $kongdan_generaldonation_id)
	// 						 ->select('kongdan_receipt.*', 'devotee.chinese_name', 'devotee.oversea_addr_in_chinese', 'devotee.address_houseno', 'devotee.address_unit1',
	// 						 	'devotee.address_unit2', 'devotee.address_street', 'devotee.address_postal', 'devotee.familycode_id', 'devotee.deceased_year',
	// 						 	'kongdan_generaldonation.focusdevotee_id', 'kongdan_generaldonation.trans_no', 'user.first_name', 'user.last_name',
	// 						 	'festiveevent.start_at', 'festiveevent.time', 'festiveevent.event', 'festiveevent.lunar_date', 'kongdan_generaldonation.mode_payment')
	// 						 ->get();
  //
  //   for($i = 0; $i < count($result); $i++)
	// 	{
	// 		$result[$i]->trans_date = \Carbon\Carbon::parse($result[$i]->trans_date)->format("d/m/Y");
	// 		$result[$i]->start_at = \Carbon\Carbon::parse($result[$i]->start_at)->format("d/m/Y");
	// 	}
  //
  //   $familycode_id = $result[0]->familycode_id;
	// 	$samefamily_no = 0;
  //
	// 	for($i = 0; $i < count($result); $i++)
	// 	{
	// 		if($result[$i]->familycode_id == $familycode_id)
	// 		{
	// 			$samefamily_no += 1;
	// 			$total_amount += intval($result[$i]->amount);
	// 		}
  //
	// 		$familycode_id = $result[$i]->familycode_id;
	// 	}
  //
	// 	$paid_by = Devotee::where('devotee.devotee_id', $result[0]->focusdevotee_id)
	// 						 ->select('chinese_name', 'devotee_id')
	// 						 ->get();
  //
	// 	if($samefamily_no > 6)
	// 	{
	// 		$loop = intval($samefamily_no / 6, 0);
	// 		$modulus = $samefamily_no % 6;
	// 	}
  //
	// 	else
	// 	{
	// 		$loop = 1;
	// 		$modulus = 0;
	// 	}
  //
	// 	if($modulus > 0)
	// 	{
	// 		$loop = $loop + 1;
	// 	}
  //
	// 	$count_familycode = 0;
  //
	// 	for($i = 0; $i < count($result); $i++)
	// 	{
	// 	  $first_familycode = $result[0]->familycode_id;
  //
	// 	  if($first_familycode == $result[$i]->familycode_id)
	// 	  {
	// 	    $count_familycode++;
	// 	  }
	// 	}
  //
	// 	return view('fahui.kongdan_print', [
	// 		'receipts' => $result,
	// 		'print_format' => $hjgr,
	// 		'loop' => $loop,
	// 		'count_familycode' => $count_familycode,
	// 		'samefamily_no' => $samefamily_no,
	// 		'total_amount' => number_format($total_amount, 2),
	// 		'paid_by' => $paid_by
	// 	]);
  // }

  public function postKongdanSameFamilySetting(Request $request)
  {
    $input = array_except($request->all(), '_token');

    SettingKongdan::where('focusdevotee_id', $input['focusdevotee_id'])
												 ->where('address_code', 'same')
                         ->where('year', null)
												 ->delete();

    if(isset($input['focusdevotee_id']))
    {
      for($i = 0; $i < count($input['devotee_id']); $i++)
      {
        $list = [
          "focusdevotee_id" => $input['focusdevotee_id'],
          "kongdan_id" => $input['hidden_kongdan_id'][$i],
          "devotee_id" => $input['devotee_id'][$i],
          "address_code" => "same"
        ];

        SettingKongdan::create($list);
      }
    }

    $devotee = Devotee::find($input['focusdevotee_id']);

    $kongdan_same_family = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
													 ->leftjoin('setting_kongdan', 'devotee.devotee_id', '=', 'setting_kongdan.devotee_id')
													 ->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
											 		 ->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
													 ->where('devotee.familycode_id', $devotee->familycode_id)
													 ->where('devotee.devotee_id', '!=', $input['focusdevotee_id'])
													 ->where('setting_kongdan.focusdevotee_id', '=', $input['focusdevotee_id'])
													 ->where('setting_kongdan.address_code', '=', 'same')
													 ->where('setting_kongdan.kongdan_id', '=', '1')
                           ->where('setting_kongdan.year', null)
													 ->select('devotee.*', 'familycode.familycode', 'member.paytill_date', 'specialremarks.devotee_id as specialremarks_devotee_id')
													 ->GroupBy('devotee.devotee_id')
													 ->get();

    $kongdan_same_focusdevotee = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
                       						->leftjoin('setting_kongdan', 'devotee.devotee_id', '=', 'setting_kongdan.devotee_id')
                       						->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
                       						->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
                       						->where('setting_kongdan.address_code', '=', 'same')
                       						->where('setting_kongdan.kongdan_id', '=', '1')
                                  ->where('setting_kongdan.year', null)
                       						->where('setting_kongdan.focusdevotee_id', '=', $input['focusdevotee_id'])
                       						->where('setting_kongdan.devotee_id', '=', $input['focusdevotee_id'])
                       						->select('devotee.*', 'familycode.familycode', 'member.paytill_date', 'specialremarks.devotee_id as specialremarks_devotee_id')
                       						->GroupBy('devotee.devotee_id')
                       						->get();

    $kongdan_setting_samefamily = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
                                  ->leftjoin('setting_kongdan', 'setting_kongdan.devotee_id', '=', 'devotee.devotee_id')
                                  ->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
                                  ->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
                                  ->where('devotee.devotee_id', '!=', $input['focusdevotee_id'])
                                  ->where('devotee.familycode_id', $devotee->familycode_id)
                                  ->where('setting_kongdan.focusdevotee_id', '=', $input['focusdevotee_id'])
                                  ->where('setting_kongdan.address_code', '=', 'same')
                                  ->select('devotee.*', 'member.paytill_date', 'specialremarks.devotee_id as specialremarks_devotee_id', 'familycode.familycode', 'setting_kongdan.kongdan_id')
                                  ->GroupBy('devotee.devotee_id')
                                  ->get();

    $kongdan_focusdevotee = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
                            ->leftjoin('setting_kongdan', 'devotee.devotee_id', '=', 'setting_kongdan.devotee_id')
                            ->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
                            ->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
                            ->where('devotee.familycode_id', $devotee->familycode_id)
                            ->where('devotee.devotee_id', $input['focusdevotee_id'])
                            ->where('setting_kongdan.year', null)
                            ->where('setting_kongdan.focusdevotee_id', $input['focusdevotee_id'])
                            ->select('devotee.*', 'familycode.familycode', 'member.paytill_date', 'specialremarks.devotee_id as specialremarks_devotee_id', 'setting_kongdan.kongdan_id')
                            ->get();

    Session::put('kongdan_setting_samefamily', $kongdan_setting_samefamily);
    Session::put('kongdan_focusdevotee', $kongdan_focusdevotee);
    Session::put('kongdan_same_family', $kongdan_same_family);
    Session::put('kongdan_same_focusdevotee', $kongdan_same_focusdevotee);

    $request->session()->flash('success', 'Setting for same address is successfully created.');
		return redirect()->back();
  }

  public function postKongdanDifferentFamilySetting(Request $request)
  {
    $input = array_except($request->all(), '_token');

    SettingKongdan::where('focusdevotee_id', $input['focusdevotee_id'])
												 ->where('address_code', 'different')
                         ->where('year', null)
												 ->delete();

    if(isset($input['devotee_id']))
		{
			for($i = 0; $i < count($input['devotee_id']); $i++)
			{
				$list = [
					"focusdevotee_id" => $input['focusdevotee_id'],
	        "kongdan_id" => $input['hidden_kongdan_id'][$i],
					"devotee_id" => $input['devotee_id'][$i],
	        "address_code" => "different",
	        "year" => null
				];

				SettingKongdan::create($list);
			}
		}

    $devotee = Devotee::find($input['focusdevotee_id']);

		$kongdan_different_family = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
																->leftjoin('setting_kongdan', 'devotee.devotee_id', '=', 'setting_kongdan.devotee_id')
																->where('setting_kongdan.address_code', '=', 'different')
																->where('setting_kongdan.kongdan_id', '=', '1')
																->where('setting_kongdan.focusdevotee_id', '=', $input['focusdevotee_id'])
                                ->where('year', null)
																->select('devotee.*', 'familycode.familycode')
																->get();

    $kongdan_setting_differentfamily = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
        															 ->leftjoin('setting_kongdan', 'setting_kongdan.devotee_id', '=', 'devotee.devotee_id')
        															 ->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
        															 ->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
        															 ->where('setting_kongdan.focusdevotee_id', '=', $input['focusdevotee_id'])
        															 ->where('setting_kongdan.address_code', '=', 'different')
                                       ->where('year', null)
        															 ->select('devotee.*', 'member.paytill_date', 'specialremarks.devotee_id as specialremarks_devotee_id', 'familycode.familycode',
        															 'setting_kongdan.kongdan_id')
        															 ->GroupBy('devotee.devotee_id')
        															 ->get();

    Session::put('kongdan_different_family', $kongdan_different_family);
		Session::put('kongdan_setting_differentfamily', $kongdan_setting_differentfamily);

    $request->session()->flash('success', 'Setting for different address is successfully created.');
		return redirect()->back();
  }

  public function getTransactionDetail(Request $request)
  {
    $input = array_except($request->all(), '_token');

    if(isset($input['trans_no']))
		{
			$trans = KongdanGeneraldonation::where('trans_no', $input['trans_no'])->first();

			if(count($trans) > 0)
			{
        $kongdan_generaldonation = new KongdanGeneraldonation;
        $result = $kongdan_generaldonation->searchTransaction($input)->get();
			}

			else
			{
				return response()->json(array(
					 'msg' => 'No Result Found'
				));
			}

			$cancellation = KongdanReceipt::leftjoin('kongdan_generaldonation', 'kongdan_receipt.generaldonation_id', '=', 'kongdan_generaldonation.generaldonation_id')
											->leftjoin('user', 'kongdan_receipt.cancelled_by', '=', 'user.id')
											->where('kongdan_generaldonation.trans_no', $input['trans_no'])
											->select('kongdan_receipt.cancelled_date', 'user.first_name', 'user.last_name')
											->GroupBy('kongdan_generaldonation.generaldonation_id')
											->get();

			if(isset($cancellation[0]->cancelled_date))
			{
				$cancellation[0]->cancelled_date = Carbon::parse($cancellation[0]->cancelled_date)->format("d/m/Y");
			}
		}

    else
		{
			$receipt = KongdanReceipt::where('receipt_no', $input['receipt_no'])->first();

			if(count($receipt) > 0)
			{
        $kongdan_generaldonation = new KongdanGeneraldonation;
        $result = $kongdan_generaldonation->searchTransaction($input)->get();
			}

			else
			{
				return response()->json(array(
			     'msg' => 'No Result Found'
			  ));
			}

			$cancellation = KongdanReceipt::leftjoin('kongdan_generaldonation', 'kongdan_receipt.generaldonation_id', '=', 'kongdan_generaldonation.generaldonation_id')
											->leftjoin('user', 'kongdan_receipt.cancelled_by', '=', 'user.id')
											->where('kongdan_receipt.receipt_no', $input['receipt_no'])
											->select('kongdan_receipt.cancelled_date', 'user.first_name', 'user.last_name')
											->GroupBy('kongdan_generaldonation.generaldonation_id')
											->get();

			if(isset($cancellation[0]->cancelled_date))
			{
				$cancellation[0]->cancelled_date = Carbon::parse($cancellation[0]->cancelled_date)->format("d/m/Y");
			}
		}

    // Check Transaction devotee and focus devotee is the same
		$focusdevotee = Session::get('focus_devotee');

		// if(count($focusdevotee) == 0)
		// {
		// 	return response()->json(array(
	  //     'msg' => 'Please select Focus Devotee.'
	  //   ));
		// }
    //
    // if($focusdevotee[0]->devotee_id != $result[0]->focusdevotee_id)
		// {
		// 	return response()->json(array(
	  //     'msg' => 'Search receipt no or transaction no by focus devotee.'
	  //   ));
		// }

    if(count($result) > 0)
		{
			for($i = 0; $i < count($result); $i++)
			{
				$result[$i]->trans_date = Carbon::parse($result[$i]->trans_date)->format("d/m/Y");
			}

			$focusdevotee = Devotee::where('devotee_id', $result[0]->focusdevotee_id)->pluck('chinese_name');

			return response()->json(array(
	      'transaction' => $result,
				'focusdevotee' => $focusdevotee,
				'cancellation' => $cancellation
	    ));
		}

		return response()->json(array(
      'transaction' => $result
    ));
  }

  public function ReprintDetail(Request $request)
  {
    $input = array_except($request->all(), '_token');
		$total_amount = 0;

    if(isset($input['receipt_no']))
		{
			$receipts = KongdanReceipt::leftjoin('kongdan_generaldonation', 'kongdan_receipt.generaldonation_id', '=', 'kongdan_generaldonation.generaldonation_id')
								 ->leftjoin('devotee', 'kongdan_receipt.devotee_id', '=', 'devotee.devotee_id')
								 ->leftjoin('user', 'kongdan_receipt.staff_id', '=', 'user.id')
								 ->leftjoin('festiveevent', 'kongdan_generaldonation.festiveevent_id', '=', 'festiveevent.festiveevent_id')
								 ->where('kongdan_receipt.receipt_no', '=', $input['receipt_no'])
								 ->select('kongdan_receipt.*', 'devotee.chinese_name', 'devotee.oversea_addr_in_chinese', 'devotee.address_houseno', 'devotee.address_unit1',
								 	'devotee.address_unit2', 'devotee.address_street', 'devotee.address_postal', 'devotee.familycode_id',
								 	'kongdan_generaldonation.focusdevotee_id', 'kongdan_generaldonation.trans_no', 'user.first_name', 'user.last_name',
								 	'festiveevent.start_at', 'festiveevent.time', 'festiveevent.event', 'festiveevent.lunar_date', 'kongdan_generaldonation.mode_payment')
								 ->get();

			$receipts[0]->trans_date = \Carbon\Carbon::parse($receipts[0]->trans_date)->format("d/m/Y");
			$receipts[0]->start_at = \Carbon\Carbon::parse($receipts[0]->start_at)->format("d/m/Y");

			$samefamily_no = 0;
			$print_format = 'hj';

			$paid_by = Devotee::where('devotee.devotee_id', $receipts[0]->focusdevotee_id)
								 ->select('chinese_name', 'devotee_id')
								 ->get();
		}

    if(isset($input['trans_no']))
		{
			$receipts = KongdanReceipt::leftjoin('kongdan_generaldonation', 'kongdan_receipt.generaldonation_id', '=', 'kongdan_generaldonation.generaldonation_id')
								  ->leftjoin('devotee', 'kongdan_receipt.devotee_id', '=', 'devotee.devotee_id')
								  ->leftjoin('user', 'kongdan_receipt.staff_id', '=', 'user.id')
								  ->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
								  ->leftjoin('festiveevent', 'kongdan_generaldonation.festiveevent_id', '=', 'festiveevent.festiveevent_id')
								  ->where('kongdan_generaldonation.trans_no', '=', $input['trans_no'])
								  ->select('kongdan_receipt.*', 'member.paytill_date', 'member.member_id', 'devotee.chinese_name', 'devotee.oversea_addr_in_chinese', 'devotee.address_houseno', 'devotee.address_unit1',
								 	 'devotee.address_unit2', 'devotee.address_street', 'devotee.address_postal', 'devotee.familycode_id',
								 	 'kongdan_generaldonation.focusdevotee_id', 'kongdan_generaldonation.trans_no', 'user.first_name', 'user.last_name',
								 	 'festiveevent.start_at', 'festiveevent.time', 'festiveevent.event', 'festiveevent.lunar_date', 'kongdan_generaldonation.mode_payment')
								  ->get();

			for($i = 0; $i < count($receipts); $i++)
			{
				$receipts[$i]->trans_date = \Carbon\Carbon::parse($receipts[$i]->trans_date)->format("d/m/Y");
				$receipts[$i]->start_at = \Carbon\Carbon::parse($receipts[$i]->start_at)->format("d/m/Y");
			}

			$familycode_id = $receipts[0]->familycode_id;
			$samefamily_no = 0;

			for($i = 0; $i < count($receipts); $i++)
			{
				if($receipts[$i]->familycode_id == $familycode_id)
				{
					$samefamily_no += 1;
					$total_amount += intval($receipts[$i]->amount);
				}

				$familycode_id = $receipts[$i]->familycode_id;
			}

			$print_format = $input['hjgr'];

			$paid_by = Devotee::where('devotee.devotee_id', $receipts[0]->focusdevotee_id)
								 ->select('chinese_name', 'devotee_id')
								 ->get();
		}

    if($samefamily_no > 6)
		{
			$loop = intval($samefamily_no / 6, 0);
			$modulus = $samefamily_no % 6;
		}

		else
		{
			$loop = 1;
			$modulus = 0;
		}

		if($modulus > 0)
		{
			$loop = $loop + 1;
		}

		$count_familycode = 0;

		for($i = 0; $i < count($receipts); $i++)
		{
			$first_familycode = $receipts[0]->familycode_id;

			if($first_familycode == $receipts[$i]->familycode_id)
			{
				$count_familycode++;
			}
		}

    return view('fahui.kongdan_print', [
      'receipts' => $receipts,
      'print_format' => $print_format,
      'samefamily_no' => $samefamily_no,
      'loop' => $loop,
      'count_familycode' => $count_familycode,
      'total_amount' => number_format($total_amount, 2),
      'paid_by' => $paid_by
    ]);
  }

  public function postCancelTransaction(Request $request)
  {
    $input = array_except($request->all(), '_token');

    if(isset($input['authorized_password']))
		{
			$user = User::find(Auth::user()->id);
			$hashedPassword = $user->password;

			if(Hash::check($input['authorized_password'], $hashedPassword))
			{
				$generaldonation = KongdanGeneraldonation::where('trans_no', $input['transaction_no'])->first();

				// Update Cancellation Status
				$kongdan_receipts = KongdanReceipt::where('generaldonation_id', $generaldonation->generaldonation_id)
        									  ->update([
        							        'cancelled_date' => Carbon::now(),
        							        'status' => "cancelled",
        							        'cancelled_by' => Auth::user()->id
        							     ]);

				$cancellation_receipts = KongdanReceipt::leftjoin('user', 'user.id', '=', 'kongdan_receipt.cancelled_by')
																 ->where('kongdan_receipt.generaldonation_id', '=', $generaldonation->generaldonation_id)
																 ->select('kongdan_receipt.cancelled_date', 'user.first_name', 'user.last_name')
																 ->GroupBy('kongdan_receipt.generaldonation_id')
																 ->get();

				$cancelled_date = \Carbon\Carbon::parse($cancellation_receipts[0]->cancelled_date)->format("d/m/Y");

				$focus_devotee = Session::get('focus_devotee');

				if(count($focus_devotee) > 0)
        {
          $kongdan_receipts = KongdanGeneraldonation::leftjoin('devotee', 'devotee.devotee_id', '=', 'kongdan_generaldonation.focusdevotee_id')
          										->leftjoin('kongdan_receipt', 'kongdan_receipt.generaldonation_id', '=', 'kongdan_generaldonation.generaldonation_id')
          										->where('kongdan_generaldonation.focusdevotee_id', '=', $focus_devotee[0]->devotee_id)
          										->where('kongdan_receipt.glcode_id', 117)
          										->GroupBy('kongdan_generaldonation.generaldonation_id')
          										->select('kongdan_generaldonation.*', 'devotee.chinese_name', 'kongdan_receipt.cancelled_date')
          										->orderBy('kongdan_generaldonation.generaldonation_id', 'desc')
          										->get();

  				if(count($kongdan_receipts) > 0)
  				{
  					for($i = 0; $i < count($kongdan_receipts); $i++)
  					{
  						$data = KongdanReceipt::where('generaldonation_id', $kongdan_receipts[$i]->generaldonation_id)->pluck('receipt_no');
  						$receipt_count = count($data);
  						$kongdan_receipts[$i]->receipt_no = $data[0] . ' - ' . $data[$receipt_count - 1];
  					}
  				}

  				Session::put('kongdan_receipts', $kongdan_receipts);
        }

				$request->session()->flash('success', 'The transaction is successfully cancelled.');

				return redirect()->back()->with([
					'cancelled_date' => $cancelled_date,
					'first_name' => $cancellation_receipts[0]->first_name,
					'last_name' => $cancellation_receipts[0]->last_name
				]);
			}
		}
  }

  public function postCancelReplaceTransaction(Request $request)
  {
    $input = array_except($request->all(), '_token');
		$focusdevotee_id = "";

    if(isset($input['authorized_password']))
		{
			$user = User::find(Auth::user()->id);
      $hashedPassword = $user->password;

			if (Hash::check($input['authorized_password'], $hashedPassword))
			{
				if(!empty($input['receipt_no']))
				{
					$receipt = KongdanReceipt::where('receipt_no', $input['receipt_no'])->get();
					$result = KongdanReceipt::find($receipt[0]['receipt_id']);

					$generaldonation = KongdanGeneraldonation::where('generaldonation_id', $receipt[0]['generaldonation_id'])->get();

					$focusdevotee_id = $generaldonation[0]->focusdevotee_id;

					$result->cancelled_date = Carbon::now();
					$result->status = "cancelled";
					$result->cancelled_by = Auth::user()->id;

					$cancellation = $result->save();
				}

				if(!empty($input['trans_no']))
				{
					$generaldonation = KongdanGeneraldonation::where('trans_no', $input['trans_no'])->get();

					$focusdevotee_id = $generaldonation[0]->focusdevotee_id;

					$receipt = KongdanReceipt::where('generaldonation_id', $generaldonation[0]->generaldonation_id)->get();
          $total_devotee = count($receipt);

					for($i = 0; $i < count($receipt); $i++)
					{
						$result = KongdanReceipt::find($receipt[$i]['receipt_id']);

						$result->cancelled_date = Carbon::now();
						$result->status = "cancelled";
						$result->cancelled_by = Auth::user()->id;

						$cancellation = $result->save();
					}
				}

				$focus_devotee = Session::get('focus_devotee');

				$kongdan_receipts = KongdanGeneraldonation::leftjoin('devotee', 'devotee.devotee_id', '=', 'kongdan_generaldonation.focusdevotee_id')
        				            ->leftjoin('kongdan_receipt', 'kongdan_receipt.generaldonation_id', '=', 'kongdan_generaldonation.generaldonation_id')
        				            ->where('kongdan_generaldonation.focusdevotee_id', '=', $focus_devotee[0]->devotee_id)
        				            ->where('kongdan_receipt.glcode_id', 117)
        				            ->GroupBy('kongdan_generaldonation.generaldonation_id')
        				            ->select('kongdan_generaldonation.*', 'devotee.chinese_name', 'kongdan_receipt.cancelled_date')
        				            ->orderBy('kongdan_generaldonation.generaldonation_id', 'desc')
        				            ->get();

				if(count($kongdan_receipts) > 0)
				{
				  for($i = 0; $i < count($kongdan_receipts); $i++)
				  {
				    $data = KongdanReceipt::where('generaldonation_id', $kongdan_receipts[$i]->generaldonation_id)->pluck('receipt_no');
				    $receipt_count = count($data);
				    $kongdan_receipts[$i]->receipt_no = $data[0] . ' - ' . $data[$receipt_count - 1];
				  }
				}

				Session::put('kongdan_receipts', $kongdan_receipts);

				return response()->json(array(
				  'receipt' => $receipt,
          'total_devotee' => $total_devotee
				));
			}

			else
			{
				return response()->json(array(
					'error' => 'not match'
				));
			}
		}
  }

  public function getParticipantList(){

    $participant_list = Rct::leftjoin('module','rct.mod_id','module.mod_id')
                           ->leftjoin('devotee','rct.devotee_id','devotee.devotee_id')
                           ->select('module.chinese_name as module_chinese_name','rct.trans_date as year','rct.devotee_id','devotee.chinese_name as devotee_chinese_name')
                           ->groupBy('module.mod_id',DB::raw('YEAR(rct.trans_date )'),'rct.devotee_id')
                           ->get();


    foreach($participant_list as $index=>$participant){
      $participant['year'] = date("Y", strtotime($participant['year']));
    }

    $checkFahui = $participant_list[0]['module_chinese_name'];
    $checkYear = $participant_list[0]['year'];
    $sn = 1;
    foreach($participant_list as $index=>$participant){
      if($checkFahui == $participant['module_chinese_name'] && $checkYear == $participant['year']){
        $participant['sn'] = $sn;
        $sn ++;
      }

      else{
        $sn = 1;
        $participant['sn'] = $sn;
      }

    }


    return view('fahui.participant-list',[
      'participant_list' => $participant_list
    ]);
  }

}
