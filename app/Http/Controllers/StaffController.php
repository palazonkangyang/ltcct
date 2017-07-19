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

		if(isset($input['receipt_at']))
		{
			$receipt_at = date("Y-m-d", strtotime($input['receipt_at']));
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
				// save receipt for same family (1 receipt for printing)
				$same_xy_receipt = Receipt::all()->last()->receipt_id;
				$prefix = "XY";
				$same_xy_receipt += 1;
				$same_xy_receipt = $prefix . $same_xy_receipt;

				$receipt = [
					"xy_receipt" => $same_xy_receipt,
					"trans_date" => Carbon::now(),
					"description" => "Xiangyou",
					"focusdevotee_id" => $input['focusdevotee_id'],
					"amount" => $input["amount"][0],
					"generaldonation_id" => $general_donation->generaldonation_id,

				];

				$same_receipt = Receipt::create($receipt);

				for($i = 0; $i < count($input['devotee_id']); $i++)
				{
					$data = [
						"amount" => $input["amount"][$i],
						"paid_till" => date("Y-m-d", strtotime($input["paid_till"][$i])),
						"hjgr" => $input["hjgr_arr"][$i],
						"display" => $input["display"][$i],
						"trans_date" => Carbon::now(),
						"generaldonation_id" => $general_donation->generaldonation_id,
						"devotee_id" => $input["devotee_id"][$i],
						"receipt_id" => $same_receipt->receipt_id
					];

					GeneralDonationItems::create($data);
				}

				// save receipt for relatives and friends (1 receipt for printing)
				if(isset($input['other_devotee_id']))
				{
					$different_receipt = "";

					for($i = 0; $i < count($input['other_devotee_id']); $i++)
					{
						$different_xy_receipt = Receipt::all()->last()->receipt_id;
						$prefix = "XY";
						$different_xy_receipt += 1;
						$different_xy_receipt = $prefix . $different_xy_receipt;

						$data2 = [
							"xy_receipt" => $different_xy_receipt,
							"trans_date" => Carbon::now(),
							"description" => "Xiangyou",
							"focusdevotee_id" => $input['focusdevotee_id'],
							"amount" => $input["other_amount"][$i],
							"generaldonation_id" => $general_donation->generaldonation_id,
						];

						$different_receipt = Receipt::create($data2);
					}

					for($i = 0; $i < count($input['other_devotee_id']); $i++)
					{
						$data2 = [
							"amount" => $input["other_amount"][$i],
							"paid_till" => date("Y-m-d", strtotime($input["other_paid_till"][$i])),
							"hjgr" => $input["other_hjgr_arr"][$i],
							"display" => $input["other_display"][$i],
							"trans_date" => Carbon::now(),
							"generaldonation_id" => $general_donation->generaldonation_id,
							"devotee_id" => $input["other_devotee_id"][$i],
							"receipt_id" => $different_receipt->receipt_id
						];

						GeneralDonationItems::create($data2);
					}
				}

			}

			else
			{

				for($i = 0; $i < count($input['devotee_id']); $i++)
				{
					// save receipt for same family (1 receipt for printing)
					$same_xy_receipt = Receipt::all()->last()->receipt_id;
					$prefix = "XY";
					$same_xy_receipt += 1;
					$same_xy_receipt = $prefix . $same_xy_receipt;

					$receipt = [
						"xy_receipt" => $same_xy_receipt,
						"trans_date" => Carbon::now(),
						"description" => "Xiangyou",
						"focusdevotee_id" => $input['focusdevotee_id'],
						"amount" => $input["amount"][$i],
						"generaldonation_id" => $general_donation->generaldonation_id
					];

					$individual_receipt = Receipt::create($receipt);

					$data = [
						"amount" => $input["amount"][$i],
						"paid_till" => date("Y-m-d", strtotime($input["paid_till"][$i])),
						"hjgr" => $input["hjgr_arr"][$i],
						"display" => $input["display"][$i],
						"trans_date" => Carbon::now(),
						"generaldonation_id" => $general_donation->generaldonation_id,
						"devotee_id" => $input["devotee_id"][$i],
						"receipt_id" => $individual_receipt->receipt_id
					];

					GeneralDonationItems::create($data);
				}

				// save receipt for relatives and friends (1 receipt for printing)
				if(isset($input['other_devotee_id']))
				{
					$different_receipt = "";

					for($i = 0; $i < count($input['other_devotee_id']); $i++)
					{
						$different_xy_receipt = Receipt::all()->last()->receipt_id;
						$prefix = "XY";
						$different_xy_receipt += 1;
						$different_xy_receipt = $prefix . $different_xy_receipt;

						$data = [
							"xy_receipt" => $different_xy_receipt,
							"trans_date" => Carbon::now(),
							"description" => "Xiangyou",
							"focusdevotee_id" => $input['focusdevotee_id'],
							"amount" => $input["other_amount"][$i],
							"generaldonation_id" => $general_donation->generaldonation_id,
						];

						$different_receipt = Receipt::create($data);

						$data2 = [
							"amount" => $input["other_amount"][$i],
							"paid_till" => date("Y-m-d", strtotime($input["other_paid_till"][$i])),
							"hjgr" => $input["other_hjgr_arr"][$i],
							"display" => $input["other_display"][$i],
							"trans_date" => Carbon::now(),
							"generaldonation_id" => $general_donation->generaldonation_id,
							"devotee_id" => $input["other_devotee_id"][$i],
							"receipt_id" => $different_receipt->receipt_id
						];

						GeneralDonationItems::create($data2);
					}
				}
			}
		}

		// remove session
		if(Session::has('receipts'))
		{
			Session::forget('receipts');
		}

		// Get Receipt History
       	$receipts = Receipt::leftjoin('generaldonation', 'generaldonation.generaldonation_id', '=', 'receipt.generaldonation_id')
       				->leftjoin('devotee', 'devotee.devotee_id', '=', 'receipt.focusdevotee_id')
       				->where('receipt.focusdevotee_id', $input['focusdevotee_id'])
       				->orderBy('receipt_id', 'desc')
       				->select('receipt.*')
       				->addSelect('devotee.chinese_name')
       				->addSelect('generaldonation.manualreceipt')
       				->get();

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
		$receipt = Receipt::join('devotee', 'devotee.devotee_id', '=', 'receipt.focusdevotee_id')
					->join('generaldonation', 'generaldonation.generaldonation_id', '=', 'receipt.generaldonation_id')
					->select('receipt.*')
					->addSelect('devotee.chinese_name')
					->addSelect('generaldonation.trans_no')
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
		$receipt = Receipt::join('devotee', 'devotee.devotee_id', '=', 'receipt.focusdevotee_id')
					->join('generaldonation', 'generaldonation.generaldonation_id', '=', 'receipt.generaldonation_id')
					->select('receipt.*')
					->addSelect('devotee.chinese_name')
					->addSelect('generaldonation.trans_no')
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
			$events = FestiveEvent::orderBy('start_at', 'desc')->get();

			return view('staff.create-festive-event', [
				'events' => $events
			]);
	}

	public function postCreateFestiveEvent(Request $request)
	{
			$input = array_except($request->all(), '_token', 'display');

			dd($input);

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
