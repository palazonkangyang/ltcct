<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\Member;
use App\Models\Devotee;
use App\Models\User;
use App\Models\OptionalAddress;
use App\Models\OptionalVehicle;
use App\Models\FamilyCode;
use App\Models\SpecialRemarks;
use App\Models\GeneralDonation;
use App\Models\GeneralDonationItems;
use App\Models\Receipt;
use App\Models\FestiveEvent;
use App\Models\RelativeFriendLists;
use Auth;
use DB;
use Hash;
use Mail;
use Input;
use Session;
use View;
use URL;
use Carbon\Carbon;

class StaffController extends Controller
{

	public function getDonation()
	{
		$today = Carbon::today();

		$events = FestiveEvent::orderBy('start_at', 'asc')
							->where('start_at', '>', $today)
							->take(2)
							->get();

		return view('staff.donation', [
			'events' => $events
		]);
	}

	public function postDonation(Request $request)
	{
		$input = array_except($request->all(), '_token');

		// Add Relative and Friend Lists
		if(isset($input["other_devotee_id"]))
		{
			// Delete relative and friend lists by focus devotee before saving
			RelativeFriendLists::where('donate_devotee_id', $input['focusdevotee_id'])->delete();

			for($i = 0; $i < count($input["other_devotee_id"]); $i++)
			{
			  $list = [
			    "donate_devotee_id" => $input['focusdevotee_id'],
			    "relative_friend_devotee_id" =>$input["other_devotee_id"][$i],
			    "year" => date('Y')
			  ];

			  RelativeFriendLists::create($list);
			}
		}

		// Modify Receipt At fields
		if(isset($input['receipt_at']))
		{
		  $input_receipt_at = str_replace('/', '-', $input['receipt_at']);
		  $receipt_at = date("Y-m-d", strtotime($input_receipt_at));
		}
		else
		{
		  $receipt_at = $input['receipt_at'];
		}

		$trans_id = GeneralDonation::all()->last()->generaldonation_id;
		$prefix = "T";
		$trans_id += 1;
		$trans_id = $prefix . $trans_id;

		$data = [
		  "trans_no" => $trans_id,
		  "description" => "Xiangyou",
		  "hjgr" => $input['hjgr'],
		  "total_amount" => $input['total_amount'],
		  "mode_payment" => $input['mode_payment'],
		  "cheque_no" => $input['cheque_no'],
		  "receipt_at" =>	$receipt_at,
		  "manualreceipt" => $input['manualreceipt'],
		  "trans_at" => Carbon::now(),
		  "focusdevotee_id" => $input['focusdevotee_id'],
		  "festiveevent_id" => $input['festiveevent_id']
		];

		$general_donation = GeneralDonation::create($data);

		if($general_donation)
		{
			if($input["hjgr"] == "hj")
			{
				$same_receipt = "";
				$count = 0;

				if(isset($input["amount"]))
				{
					// save receipt for same family (1 receipt for printing)
					for($i = 0; $i < count($input["amount"]); $i++)
					{
						$amount = array_sum($input["amount"]);

						if(isset($input["amount"][$i]) && $count < 1)
						{
						  $same_xy_receipt = Receipt::all()->last()->receipt_id;
						  $prefix = "XY";
						  $same_xy_receipt += 1;
						  $same_xy_receipt = $prefix . $same_xy_receipt;

						  $receipt = [
						    "xy_receipt" => $same_xy_receipt,
						    "trans_date" => Carbon::now(),
						    "description" => "Xiangyou",
						    "amount" => $amount,
						    "generaldonation_id" => $general_donation->generaldonation_id
						  ];

						  $same_receipt = Receipt::create($receipt)->receipt_id;

							$count++;
						}

						// save receipt for same family
						if(isset($input["amount"][$i]))
						{
							// Modify fields
							$paid_till_date = str_replace('/', '-', $input['paid_till'][$i]);
							$new_paid_till_date = date("Y-m-d", strtotime($paid_till_date));

							$data = [
								"amount" => $input["amount"][$i],
								"paid_till" => $new_paid_till_date,
								"hjgr" => $input["hjgr_arr"][$i],
								"display" => $input["display"][$i],
								"trans_date" => Carbon::now(),
								"generaldonation_id" => $general_donation->generaldonation_id,
								"devotee_id" => $input["devotee_id"][$i],
								"receipt_id" => $same_receipt
							];

							GeneralDonationItems::create($data);
						}
					}
				}

				if(isset($input["other_amount"]))
				{
					// save receipt for relative and friend lists (1 receipt for printing)
					for($i = 0; $i < count($input["other_amount"]); $i++)
					{

						if(isset($input["other_amount"][$i]))
						{
						  $different_xy_receipt = Receipt::all()->last()->receipt_id;
						  $prefix = "XY";
						  $different_xy_receipt += 1;
						  $different_xy_receipt = $prefix . $different_xy_receipt;

						  $receipt = [
						    "xy_receipt" => $different_xy_receipt,
						    "trans_date" => Carbon::now(),
						    "description" => "Xiangyou",
						    "amount" => $input["other_amount"][$i],
						    "generaldonation_id" => $general_donation->generaldonation_id
						  ];

						  $different_xy_receipt = Receipt::create($receipt)->receipt_id;

							// Modify fields
			        $paid_till = $input['other_paid_till'][$i];
			        $paid_till_date = str_replace('/', '-', $paid_till);
			        $new_paid_till_date = date("Y-m-d", strtotime($paid_till_date));

			        $data = [
			          "amount" => $input["other_amount"][$i],
			          "paid_till" => $new_paid_till_date,
			          "hjgr" => $input["other_hjgr_arr"][$i],
			          "display" => $input["other_display"][$i],
			          "trans_date" => Carbon::now(),
			          "generaldonation_id" => $general_donation->generaldonation_id,
			          "devotee_id" => $input["other_devotee_id"][$i],
			          "receipt_id" => $different_xy_receipt
			        ];

			        GeneralDonationItems::create($data);
						}
					}
				}
			}

			else
			{
				$count = 0;

				if(isset($input["amount"]))
				{
					// save receipt for same family (Individual receipt for printing)
					for($i = 0; $i < count($input["amount"]); $i++)
					{
						if(isset($input["amount"][$i]))
						{
						  $individual_xy_receipt = Receipt::all()->last()->receipt_id;
						  $prefix = "XY";
						  $individual_xy_receipt += 1;
						  $individual_xy_receipt = $prefix . $individual_xy_receipt;

						  $receipt = [
						    "xy_receipt" => $individual_xy_receipt,
						    "trans_date" => Carbon::now(),
						    "description" => "Xiangyou",
						    "amount" => $input["amount"][$i],
						    "generaldonation_id" => $general_donation->generaldonation_id
						  ];

						  $individual_receipt = Receipt::create($receipt)->receipt_id;

							// Modify fields
							$paid_till_date = str_replace('/', '-', $input['paid_till'][$i]);
							$new_paid_till_date = date("Y-m-d", strtotime($paid_till_date));

							$data = [
								"amount" => $input["amount"][$i],
								"paid_till" => $new_paid_till_date,
								"hjgr" => $input["hjgr_arr"][$i],
								"display" => $input["display"][$i],
								"trans_date" => Carbon::now(),
								"generaldonation_id" => $general_donation->generaldonation_id,
								"devotee_id" => $input["devotee_id"][$i],
								"receipt_id" => $individual_receipt
							];

							GeneralDonationItems::create($data);
						}
					}
				}

				if(isset($input["other_amount"]))
				{
					// save receipt for relative and friend lists (Individual receipt for printing)
					for($i = 0; $i < count($input["other_amount"]); $i++)
					{
						if(isset($input["other_amount"][$i]))
						{
						  $individual_xy_receipt = Receipt::all()->last()->receipt_id;
						  $prefix = "XY";
						  $individual_xy_receipt += 1;
						  $individual_xy_receipt = $prefix . $individual_xy_receipt;

						  $receipt = [
						    "xy_receipt" => $individual_xy_receipt,
						    "trans_date" => Carbon::now(),
						    "description" => "Xiangyou",
						    "amount" => $input["other_amount"][$i],
						    "generaldonation_id" => $general_donation->generaldonation_id
						  ];

						  $different_xy_receipt = Receipt::create($receipt)->receipt_id;

							// Modify fields
			        $paid_till_date = str_replace('/', '-', $input['other_paid_till'][$i]);
			        $new_paid_till_date = date("Y-m-d", strtotime($paid_till_date));

			        $data = [
			          "amount" => $input["other_amount"][$i],
			          "paid_till" => $new_paid_till_date,
			          "hjgr" => $input["other_hjgr_arr"][$i],
			          "display" => $input["other_display"][$i],
			          "trans_date" => Carbon::now(),
			          "generaldonation_id" => $general_donation->generaldonation_id,
			          "devotee_id" => $input["other_devotee_id"][$i],
			          "receipt_id" => $different_xy_receipt
			        ];

			        GeneralDonationItems::create($data);
						}
					}
				}

			}
		}

		// remove session
		if(Session::has('relative_friend_lists'))
		{
		  Session::forget('relative_friend_lists');
		}

		// remove session
		if(Session::has('receipts'))
		{
		  Session::forget('receipts');
		}

		// Get Relative and friends lists
		$relative_friend_lists = RelativeFriendLists::leftjoin('devotee', 'devotee.devotee_id', '=', 'relative_friend_lists.relative_friend_devotee_id')
															->where('donate_devotee_id', $input['focusdevotee_id'])
															->select('relative_friend_lists.*', 'devotee.chinese_name', 'devotee.guiyi_name', 'devotee.address_unit1',
															'devotee.address_unit2', 'devotee.address_street', 'devotee.address_building')
															->get();

		// Get Receipt History
		$receipts = Receipt::leftjoin('generaldonation', 'generaldonation.generaldonation_id', '=', 'receipt.generaldonation_id')
								->leftjoin('devotee', 'devotee.devotee_id', '=', 'generaldonation.focusdevotee_id')
								->where('generaldonation.focusdevotee_id', $input['focusdevotee_id'])
								->orderBy('receipt_id', 'desc')
								->select('receipt.*', 'devotee.chinese_name', 'devotee.devotee_id', 'generaldonation.manualreceipt',
								'generaldonation.hjgr as generaldonation_hjgr')
								->get();

		// store session
		if(!Session::has('relative_friend_lists'))
		{
			Session::put('relative_friend_lists', $relative_friend_lists);
		}

		// store session
    if(!Session::has('receipts'))
    {
      Session::put('receipts', $receipts);
    }

		$request->session()->flash('success', 'General Donation is successfully created.');
		return redirect()->back();
	}


	public function getReceipt(Request $request, $receipt_id)
	{
		$receipt = Receipt::join('generaldonation', 'generaldonation.generaldonation_id', '=', 'receipt.generaldonation_id')
					->join('devotee', 'devotee.devotee_id', '=', 'generaldonation.focusdevotee_id')
					->select('receipt.*', 'devotee.chinese_name', 'devotee.devotee_id', 'generaldonation.trans_no')
					->where('receipt.receipt_id', $receipt_id)
					->get();

		// get general donation devotee by generaldonation id
		$donation_devotees = GeneralDonationItems::join('devotee', 'devotee.devotee_id', '=', 'generaldonation_items.devotee_id')
							->select('generaldonation_items.*')
							->addSelect('devotee.chinese_name', 'devotee.address_houseno', 'devotee.address_street', 'devotee.address_unit1',
								'devotee.address_unit2')
							->where('receipt_id', $receipt_id)
							->get();

		$generaldonation = GeneralDonation::find($receipt[0]->generaldonation_id);

		return view('staff.receipt', [
            'receipt' => $receipt,
            'donation_devotees' => $donation_devotees,
            'generaldonation' => $generaldonation
        ]);
	}

	public function getTransaction(Request $request, $generaldonation_id)
	{
		$receipt = Receipt::join('generaldonation', 'generaldonation.generaldonation_id', '=', 'receipt.generaldonation_id')
					->join('devotee', 'devotee.devotee_id', '=', 'generaldonation.focusdevotee_id')
					->select('receipt.*', 'devotee.chinese_name', 'devotee.devotee_id', 'generaldonation.trans_no')
					->where('receipt.generaldonation_id', $generaldonation_id)
					->get();

		$generaldonation_items = GeneralDonationItems::join('devotee', 'devotee.devotee_id', '=', 'generaldonation_items.devotee_id')
									->join('receipt', 'receipt.receipt_id', '=', 'generaldonation_items.receipt_id')
									->select('generaldonation_items.*')
									->addSelect('devotee.chinese_name', 'devotee.address_houseno', 'devotee.address_street',
											'devotee.address_unit1', 'devotee.address_unit2')
									->addSelect('receipt.xy_receipt')
									->where('generaldonation_items.generaldonation_id', $generaldonation_id)
								 	->get();

		$generaldonation = GeneralDonation::find($generaldonation_id);

		// dd($generaldonation_items->toArray());

		return view('staff.transaction-detail', [
			'receipt' => $receipt,
			'generaldonation' => $generaldonation,
			'generaldonation_items' => $generaldonation_items
		]);
	}


	public function getSearchDevotee(Request $request)
	{
		$devotee_id = $_GET['devotee_id'];

		$devotee = Devotee::find($devotee_id);

		return response()->json([
			'devotee' => $devotee
		]);
	}


	public function getCreateFestiveEvent()
	{
			$events = FestiveEvent::orderBy('start_at', 'asc')->get();
			$jobs = Job::orderBy('created_at', 'desc')->get();

			return view('staff.create-festive-event', [
				'jobs' => $jobs,
				'events' => $events
			]);
	}

	public function postCreateFestiveEvent(Request $request)
	{
			$input = array_except($request->all(), '_token', 'display');

			FestiveEvent::truncate();

			for($i = 0; $i < count($input["start_at"]); $i++)
			{

				$start_at = $input["start_at"][$i];
				$new_start_at = str_replace('/', '-', $start_at);

				$end_at = $input['end_at'][$i];
				$new_end_at = str_replace('/', '-', $end_at);

				$data = [
					"event" => $input['event'][$i],
        	"start_at" => date("Y-m-d", strtotime($new_start_at)),
        	"end_at" => date("Y-m-d", strtotime($new_end_at)),
					"lunar_date" => $input['lunar_date'][$i],
					"time" => $input['time'][$i],
					"shuwen_title" => $input['shuwen_title'][$i],
					"display" => $input['display_hidden'][$i]
      	];

				FestiveEvent::create($data);
			}


			$events = FestiveEvent::orderBy('start_at', 'desc')->get();

			$request->session()->flash('success', 'Event Calender has been outdated!');

			return redirect()->back()->with([
				'events' => $events
			]);
	}

}
