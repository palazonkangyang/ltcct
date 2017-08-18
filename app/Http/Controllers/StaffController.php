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
use App\Models\GlCode;
use App\Models\FestiveEvent;
use App\Models\RelativeFriendLists;
use App\Models\Job;
use App\Models\SettingGeneralDonation;
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
							->take(1)
							->get();

		return view('staff.donation', [
			'events' => $events,
		]);
	}

	public function postDonation(Request $request)
	{
		if(Session::has('cancellation_focusdevotee_xiangyou'))
		{
		  Session::forget('cancellation_focusdevotee_xiangyou');
		}

		if(Session::has('cancellation_sameaddr_xiangyou'))
		{
			Session::forget('cancellation_sameaddr_xiangyou');
		}

		if(Session::has('cancellation_differentaddr_xiangyou'))
		{
			Session::forget('cancellation_differentaddr_xiangyou');
		}

		$c = Session::get('cancellation_differentaddr_xiangyou');

		$input = array_except($request->all(), '_token');

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
		$prefix = "Tyy";
		$trans_id += 1;
		$trans_id = $prefix . $trans_id;

		$devotee = Devotee::find($input['focusdevotee_id']);

		if(isset($devotee->member_id))
		{
			$glcode = 8;
		}

		else
		{
			$glcode = 12;
		}

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
		  "festiveevent_id" => $input['festiveevent_id'],
			"glcode_id" => $glcode
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
							if(count(Receipt::all()) > 0)
							{
								$same_xy_receipt = Receipt::all()->last()->receipt_id;
							}

							else {
								$result = GlCode::where('glcode_id', '8')->pluck('next_sn_number');
								$same_xy_receipt = $result[0];
							}

						  $prefix = GlCode::where('glcode_id', '8')->pluck('receipt_prefix');
							$prefix = $prefix[0];
						  $same_xy_receipt += 1;
						  $same_xy_receipt = $prefix . $same_xy_receipt;

						  $receipt = [
						    "xy_receipt" => $same_xy_receipt,
						    "trans_date" => Carbon::now(),
						    "description" => "Xiangyou",
						    "amount" => $amount,
						    "generaldonation_id" => $general_donation->generaldonation_id,
								"staff_id" => Auth::user()->id
						  ];

						  $same_receipt = Receipt::create($receipt)->receipt_id;

							$count++;
						}

						// save receipt for same family
						if(isset($input["amount"][$i]))
						{
							$data = [
								"amount" => $input["amount"][$i],
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
							if(count(Receipt::all()) > 0)
							{
								$different_xy_receipt = Receipt::all()->last()->receipt_id;
							}

							else {
								$result = GlCode::where('glcode_id', '8')->pluck('next_sn_number');
								$different_xy_receipt = $result[0];
							}

						  $prefix = GlCode::where('glcode_id', '8')->pluck('receipt_prefix');
							$prefix = $prefix[0];
						  $different_xy_receipt += 1;
						  $different_xy_receipt = $prefix . $different_xy_receipt;

						  $receipt = [
						    "xy_receipt" => $different_xy_receipt,
						    "trans_date" => Carbon::now(),
						    "description" => "Xiangyou",
						    "amount" => $input["other_amount"][$i],
						    "generaldonation_id" => $general_donation->generaldonation_id,
								"staff_id" => Auth::user()->id
						  ];

						  $different_xy_receipt = Receipt::create($receipt)->receipt_id;

			        $data = [
			          "amount" => $input["other_amount"][$i],
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
							if(count(Receipt::all()) > 0)
							{
								$individual_xy_receipt = Receipt::all()->last()->receipt_id;
							}

							else {
								$result = GlCode::where('glcode_id', '8')->pluck('next_sn_number');
								$individual_xy_receipt = $result[0];
							}

						  $prefix = GlCode::where('glcode_id', '8')->pluck('receipt_prefix');
							$prefix = $prefix[0];
						  $individual_xy_receipt += 1;
						  $individual_xy_receipt = $prefix . $individual_xy_receipt;

						  $receipt = [
						    "xy_receipt" => $individual_xy_receipt,
						    "trans_date" => Carbon::now(),
						    "description" => "Xiangyou",
						    "amount" => $input["amount"][$i],
						    "generaldonation_id" => $general_donation->generaldonation_id,
								"staff_id" => Auth::user()->id
						  ];

						  $individual_receipt = Receipt::create($receipt)->receipt_id;

							$data = [
								"amount" => $input["amount"][$i],
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
							if(count(Receipt::all()) > 0)
							{
								$individual_xy_receipt = Receipt::all()->last()->receipt_id;
							}

							else {
								$result = GlCode::where('glcode_id', '8')->pluck('next_sn_number');
								$individual_xy_receipt = $result[0];
							}

						  $prefix = GlCode::where('glcode_id', '8')->pluck('receipt_prefix');
							$prefix = $prefix[0];
						  $individual_xy_receipt += 1;
						  $individual_xy_receipt = $prefix . $individual_xy_receipt;

						  $receipt = [
						    "xy_receipt" => $individual_xy_receipt,
						    "trans_date" => Carbon::now(),
						    "description" => "Xiangyou",
						    "amount" => $input["other_amount"][$i],
						    "generaldonation_id" => $general_donation->generaldonation_id,
								"staff_id" => Auth::user()->id
						  ];

						  $different_xy_receipt = Receipt::create($receipt)->receipt_id;

			        $data = [
			          "amount" => $input["other_amount"][$i],
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
		if(Session::has('receipts'))
		{
		  Session::forget('receipts');
		}

		// Get Receipt History
		$receipts = Receipt::leftjoin('generaldonation', 'generaldonation.generaldonation_id', '=', 'receipt.generaldonation_id')
								->leftjoin('devotee', 'devotee.devotee_id', '=', 'generaldonation.focusdevotee_id')
								->where('generaldonation.focusdevotee_id', $input['focusdevotee_id'])
								->orderBy('receipt_id', 'desc')
								->select('receipt.*', 'devotee.chinese_name', 'devotee.devotee_id', 'generaldonation.manualreceipt',
								'generaldonation.hjgr as generaldonation_hjgr', 'generaldonation.trans_no as trans_no')
								->get();

		// store session
    if(!Session::has('receipts'))
    {
      Session::put('receipts', $receipts);
    }

		$request->session()->flash('success', 'General Donation is successfully created.');
		return redirect()->back();
	}

	public function postSameFamilySetting(Request $request)
	{
		$input = array_except($request->all(), '_token');

		SettingGeneralDonation::where('focusdevotee_id', $input['focusdevotee_id'])
												 ->where('address_code', 'same')
												 ->delete();

		if(isset($input['focusdevotee_id']))
		{
			for($i = 0; $i < count($input['devotee_id']); $i++)
			{
				$list = [
					"focusdevotee_id" => $input['focusdevotee_id'],
	        "xiangyou_ciji_id" => $input['hidden_xiangyou_ciji_id'][$i],
	        "yuejuan_id" => $input['hidden_yuejuan_id'][$i],
					"devotee_id" => $input['devotee_id'][$i],
	        "address_code" => "same",
	        "year" => date('Y'),
				];

				SettingGeneralDonation::create($list);
			}
		}

		if(Session::has('xianyou_same_family'))
		{
			Session::forget('xianyou_same_family');
		}

		if(Session::has('setting_samefamily'))
		{
			Session::forget('setting_samefamily');
		}

		$devotee = Devotee::find($input['focusdevotee_id']);

		$xianyou_same_family = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
													 ->leftjoin('setting_generaldonation', 'devotee.devotee_id', '=', 'setting_generaldonation.devotee_id')
													 ->where('devotee.familycode_id', $devotee->familycode_id)
													 ->where('devotee.devotee_id', '!=', $input['focusdevotee_id'])
													 ->where('setting_generaldonation.address_code', '=', 'same')
													 ->where('setting_generaldonation.xiangyou_ciji_id', '=', '1')
													 ->select('devotee.*', 'familycode.familycode')
													 ->get();

		$setting_samefamily = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
											 		->leftjoin('setting_generaldonation', 'setting_generaldonation.devotee_id', '=', 'devotee.devotee_id')
													->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
													->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
											 		->where('devotee.devotee_id', '!=', $input['focusdevotee_id'])
											 		->where('devotee.familycode_id', $devotee->familycode_id)
											 		->where('setting_generaldonation.focusdevotee_id', '=', $input['focusdevotee_id'])
											 		->where('setting_generaldonation.address_code', '=', 'same')
											 		->select('devotee.*', 'member.paytill_date', 'specialremarks.devotee_id as specialremarks_devotee_id', 'familycode.familycode', 'setting_generaldonation.xiangyou_ciji_id', 'setting_generaldonation.yuejuan_id')
											 		->get();

		if(!Session::has('xianyou_same_family'))
		{
			Session::put('xianyou_same_family', $xianyou_same_family);
		}

		if(!Session::has('setting_samefamily'))
		{
			Session::put('setting_samefamily', $setting_samefamily);
		}

		$request->session()->flash('success', 'Setting for same address is successfully created.');
		return redirect()->back();
	}

	public function postDifferentFamilySetting(Request $request)
	{
		$input = array_except($request->all(), '_token');

		SettingGeneralDonation::where('focusdevotee_id', $input['focusdevotee_id'])
												 ->where('address_code', 'different')
												 ->delete();

		if(isset($input['focusdevotee_id']))
		{
			for($i = 0; $i < count($input['devotee_id']); $i++)
			{
				$list = [
					"focusdevotee_id" => $input['focusdevotee_id'],
	        "xiangyou_ciji_id" => $input['hidden_xiangyou_ciji_id'][$i],
	        "yuejuan_id" => $input['hidden_yuejuan_id'][$i],
					"devotee_id" => $input['devotee_id'][$i],
	        "address_code" => "different",
	        "year" => date('Y'),
				];

				SettingGeneralDonation::create($list);
			}
		}

		if(Session::has('xianyou_different_family'))
		{
			Session::forget('xianyou_different_family');
		}

		if(Session::has('setting_differentfamily'))
		{
			Session::forget('setting_differentfamily');
		}

		$devotee = Devotee::find($input['focusdevotee_id']);

		$xianyou_different_family = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
																->leftjoin('setting_generaldonation', 'devotee.devotee_id', '=', 'setting_generaldonation.devotee_id')
																->where('setting_generaldonation.address_code', '=', 'different')
																->where('setting_generaldonation.xiangyou_ciji_id', '=', '1')
																->where('setting_generaldonation.focusdevotee_id', '=', $input['focusdevotee_id'])
																->select('devotee.*', 'familycode.familycode')
																->get();

		$setting_differentfamily = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
															 ->leftjoin('setting_generaldonation', 'setting_generaldonation.devotee_id', '=', 'devotee.devotee_id')
															 ->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
															 ->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
															 ->where('setting_generaldonation.focusdevotee_id', '=', $input['focusdevotee_id'])
															 ->where('setting_generaldonation.address_code', '=', 'different')
															 ->select('devotee.*', 'member.paytill_date', 'specialremarks.devotee_id as specialremarks_devotee_id', 'familycode.familycode',
															 'setting_generaldonation.xiangyou_ciji_id', 'setting_generaldonation.yuejuan_id')
															 ->GroupBy('devotee.devotee_id')
															 ->get();

		if(!Session::has('xianyou_different_family'))
		{
			Session::put('xianyou_different_family', $xianyou_different_family);
		}

		if(!Session::has('setting_differentfamily'))
		{
			Session::put('setting_differentfamily', $setting_differentfamily);
		}

		$request->session()->flash('success', 'Setting for different address is successfully created.');
		return redirect()->back();
	}

	// Receipt Cancellation
	// public function postReceiptCancellation(Request $request)
	// {
	// 	$input = array_except($request->all(), '_token');
	//
	// 	if(isset($input['authorized_password']))
	// 	{
	// 		$user = User::find(Auth::user()->id);
  //     $hashedPassword = $user->password;
	//
	// 		if (Hash::check($input['authorized_password'], $hashedPassword))
	// 		{
	// 			$receipt = Receipt::find($input['receipt_id']);
	//
	// 			$receipt->cancelled_date = Carbon::now();
	// 			$receipt->status = "cancelled";
	// 			$receipt->cancelled_by = Auth::user()->id;
	//
	// 			$result = $receipt->save();
	//
	// 			if($result)
	// 			{
	// 				$receiptdetail = Receipt::join('user', 'user.id', '=', 'receipt.cancelled_by')
	// 								->where('receipt.receipt_id', $input['receipt_id'])
	// 								->select('receipt.*', 'user.first_name', 'user.last_name')
	// 								->get();
	//
	// 				$cancelled_date = \Carbon\Carbon::parse($receiptdetail[0]->cancelled_date)->format("d/m/Y");
	//
	// 				Session::put('cancelled_date', $cancelled_date);
	// 				Session::put('first_name', $receiptdetail[0]->first_name);
	// 				Session::put('last_name', $receiptdetail[0]->last_name);
	//
	// 				return redirect()->back();
	// 			}
	// 		}
	//
	// 		else
	// 		{
	// 			$request->session()->flash('error', "Password did not match. Please Try Again");
	// 			return redirect()->back();
	// 		}
	// 	}
	// }

	public function postReceiptCancellation(Request $request)
	{
		$input = array_except($request->all(), '_token');

		if(isset($input['authorized_password']))
		{
			$user = User::find(Auth::user()->id);
      $hashedPassword = $user->password;

			if (Hash::check($input['authorized_password'], $hashedPassword))
			{
				$receipt = Receipt::find($input['receipt_id']);

				$receipt->cancelled_date = Carbon::now();
				$receipt->status = "cancelled";
				$receipt->cancelled_by = Auth::user()->id;

				$result = $receipt->save();

				if($result)
				{
					// $generaldonation_items = GeneralDonationItems::where('receipt')
					$cancellation_lists = Devotee::leftjoin('generaldonation_items', 'devotee.devotee_id', '=', 'generaldonation_items.devotee_id')
																				->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
																				->where('generaldonation_items.receipt_id', '=', $receipt->receipt_id)
																				->select('devotee.*', 'generaldonation_items.amount', 'generaldonation_items.hjgr',
																				'generaldonation_items.display', 'member.paytill_date')
																				->get();

					$focus_devotee = Session::get('focus_devotee');

					$xianyou_same_family = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
			                           ->leftjoin('setting_generaldonation', 'devotee.devotee_id', '=', 'setting_generaldonation.devotee_id')
			                           ->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
			                           ->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
			                           ->where('devotee.familycode_id', $focus_devotee[0]->familycode_id)
			                           ->where('devotee.devotee_id', '!=', $focus_devotee[0]->devotee_id)
			                           ->where('setting_generaldonation.address_code', '=', 'same')
			                           ->where('setting_generaldonation.xiangyou_ciji_id', '=', '1')
			                           ->select('devotee.*', 'familycode.familycode', 'member.paytill_date', 'specialremarks.devotee_id as specialremarks_devotee_id')
			                           ->get();

					$xianyou_different_family = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
													            ->leftjoin('setting_generaldonation', 'devotee.devotee_id', '=', 'setting_generaldonation.devotee_id')
													            ->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
													            ->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
													            ->where('devotee.devotee_id', '!=', $focus_devotee[0]->devotee_id)
													            ->where('setting_generaldonation.address_code', '=', 'different')
													            ->where('setting_generaldonation.xiangyou_ciji_id', '=', '1')
													            ->where('setting_generaldonation.focusdevotee_id', '=', $focus_devotee[0]->devotee_id)
													            ->select('devotee.*', 'member.paytill_date', 'specialremarks.devotee_id as specialremarks_devotee_id', 'familycode.familycode')
													            ->GroupBy('devotee.devotee_id')
													            ->get();

					if(Session::has('cancellation_focusdevotee_xiangyou'))
					{
						Session::forget('cancellation_focusdevotee_xiangyou');
					}

					if(Session::has('cancellation_sameaddr_xiangyou'))
					{
						Session::forget('cancellation_sameaddr_xiangyou');
					}

					if(Session::has('cancellation_differentaddr_xiangyou'))
					{
						Session::forget('cancellation_differentaddr_xiangyou');
					}

					for($i = 0; $i < count($cancellation_lists); $i++)
					{
						if($cancellation_lists[$i]->devotee_id == $focus_devotee[0]->devotee_id)
						{
							$focus_devotee[0]->amount = $cancellation_lists[$i]->amount;
							$focus_devotee[0]->hjgr = $cancellation_lists[$i]->hjgr;
							$focus_devotee[0]->display = $cancellation_lists[$i]->display;
						}
						else {
							$focus_devotee[0]->amount = null;
							$focus_devotee[0]->hjgr = null;
							$focus_devotee[0]->display = null;
						}
					}

					for($i = 0; $i < count($cancellation_lists); $i++)
					{
						for($j = 0; $j < count($xianyou_same_family); $j++)
						{
							if($cancellation_lists[$i]->devotee_id == $xianyou_same_family[$j]->devotee_id)
							{
								$xianyou_same_family[$j]->amount = $cancellation_lists[$i]->amount;
								$xianyou_same_family[$j]->hjgr = $cancellation_lists[$i]->hjgr;
								$xianyou_same_family[$j]->display = $cancellation_lists[$i]->display;
							}
						}
					}

					for($i = 0; $i < count($cancellation_lists); $i++)
					{
						for($j = 0; $j < count($xianyou_different_family); $j++)
						{
							if($cancellation_lists[$i]->devotee_id == $xianyou_different_family[$j]->devotee_id)
							{
								$xianyou_different_family[$j]->amount = $cancellation_lists[$i]->amount;
								$xianyou_different_family[$j]->hjgr = $cancellation_lists[$i]->hjgr;
								$xianyou_different_family[$j]->display = $cancellation_lists[$i]->display;
							}
						}
					}

					Session::put('cancellation_focusdevotee_xiangyou', $focus_devotee);
					Session::put('cancellation_sameaddr_xiangyou', $xianyou_same_family);
					Session::put('cancellation_differentaddr_xiangyou', $xianyou_different_family);

					return redirect()->route('get-donation-page');
				}
			}

			else
			{
				$request->session()->flash('error', "Password did not match. Please Try Again");
				return redirect()->back();
			}
		}
	}

	// Print Receipt
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
								'devotee.address_unit2', 'devotee.address_postal', 'devotee.oversea_addr_in_chinese')
							->where('receipt_id', $receipt_id)
							->get();

		$generaldonation = GeneralDonation::find($receipt[0]->generaldonation_id);

		$festiveevent = FestiveEvent::find($generaldonation->festiveevent_id);

		return view('staff.receipt', [
			'festiveevent' => $festiveevent,
      'receipt' => $receipt,
      'donation_devotees' => $donation_devotees,
      'generaldonation' => $generaldonation
    ]);
	}

	// Get Detail for Receipt ID
	public function getReceiptDetail($receipt_id)
	{
		// remove session
		if(Session::has('cancelled_date'))
		{
			Session::forget('cancelled_date');
		}

		// remove session
		if(Session::has('first_name'))
		{
			Session::forget('first_name');
		}

		// remove session
		if(Session::has('last_name'))
		{
			Session::forget('last_name');
		}

		$receipt = Receipt::leftjoin('generaldonation', 'generaldonation.generaldonation_id', '=', 'receipt.generaldonation_id')
							 ->leftjoin('devotee', 'devotee.devotee_id', '=', 'generaldonation.focusdevotee_id')
							 ->leftjoin('user', 'receipt.staff_id', '=', 'user.id')
							 ->select('receipt.*', 'devotee.chinese_name', 'devotee.devotee_id', 'generaldonation.trans_no', 'user.first_name', 'user.last_name')
							 ->where('receipt.receipt_id', $receipt_id)
							 ->get();

	  // get general donation devotee by generaldonation id
	  $donation_devotees = GeneralDonationItems::join('devotee', 'devotee.devotee_id', '=', 'generaldonation_items.devotee_id')
											 	 ->select('generaldonation_items.*')
											 	 ->addSelect('devotee.chinese_name', 'devotee.address_houseno', 'devotee.address_street', 'devotee.address_unit1',
													'devotee.address_unit2', 'devotee.address_postal', 'devotee.oversea_addr_in_chinese')
											 	 ->where('receipt_id', $receipt_id)
											 	 ->get();

	  $generaldonation = GeneralDonation::find($receipt[0]->generaldonation_id);
		$festiveevent = FestiveEvent::find($generaldonation->festiveevent_id);

		if($receipt[0]->status == "cancelled")
		{
			$cancelled_date = \Carbon\Carbon::parse($receipt[0]->cancelled_date)->format("d/m/Y");
			Session::put('cancelled_date', $cancelled_date);
			Session::put('first_name', $receipt[0]->first_name);
			Session::put('last_name', $receipt[0]->last_name);
		}

		return view('staff.receiptdetail', [
			'festiveevent' => $festiveevent,
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

	public function getInsertDevotee(Request $request)
	{
		$devotee_id = $_GET['devotee_id'];

		$devotee = Devotee::leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
							 ->leftjoin('familycode', 'familycode.familycode_id' , '=', 'devotee.familycode_id')
							 ->select('devotee.*', 'member.paytill_date', 'familycode.familycode')
							 ->where('devotee.devotee_id', $devotee_id)
							 ->get();

		if(isset($devotee[0]->lasttransaction_at))
		{
			$devotee[0]->lasttransaction_at = \Carbon\Carbon::parse($devotee[0]->lasttransaction_at)->format("d/m/Y");
		}

		if(isset($devotee[0]->paytill_date))
		{
			$devotee[0]->paytill_date = \Carbon\Carbon::parse($devotee[0]->paytill_date)->format("d/m/Y");
		}

		return response()->json([
			'devotee' => $devotee
		]);
	}


	public function getSearchDevoteeID(Request $request)
	{
		$devotee_id = $_GET['devotee_id'];

		$devotee = Devotee::leftjoin('country', 'devotee.nationality', '=', 'country.id')
							 ->select('devotee.*', 'country.country_name')
							 ->where('devotee.devotee_id', $devotee_id)
							 ->get();

		return response()->json([
			'devotee' => $devotee
		]);
	}

	public function getSearchDevotee(Request $request)
	{

		$input = array_except($request->all(), '_token');

		// Find Devotee List
		$devotee = new Devotee;
    $result = $devotee->searchDevotee($input)->get();

		return response()->json(array(
      'devotee' => $result
    ));
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
					"job_id" => $input['job_id'][$i],
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

			$request->session()->flash('success', 'Event Calender has been updated!');

			return redirect()->back()->with([
				'events' => $events
			]);
	}

}
