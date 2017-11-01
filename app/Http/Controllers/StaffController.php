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
use App\Models\JournalEntry;
use App\Models\JournalEntryItem;
use App\Models\Job;
use App\Models\SettingGeneralDonation;
use App\Models\Amount;
use App\Models\MembershipFee;
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

		$amount = Amount::all();
		$membership = MembershipFee::all();

		return view('staff.donation', [
			'events' => $events,
			'membership' => $membership,
			'amount' => $amount
		]);
	}

	public function postYuejuanDonation(Request $request)
	{
		$input = array_except($request->all(), '_token');
		$total_amount = 0;
		$entrancefee_credit_amount = 0;
		$monthlysubscription_credit_amount = 0;

		for($i = 0; $i < count($input['amount']); $i++)
		{
			if(isset($input['amount'][$i]))
			{
				$member = Member::find($input['member_id'][$i]);

				if($member)
				{

					if(isset($member->paytill_date))
					{
						$myArray = explode('-', $member->paytill_date);

						$dt = Carbon::create($myArray[0], $myArray[1], $myArray[2], 0);

						$member->paytill_date = $dt->addYears($input['amount'][$i]);
				    $member->save();
					}

					else {
						$member->paytill_date = Carbon::now();
						$member->save();
					}
				}
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

		if(empty($input['festiveevent_id']))
		{
			$input['festiveevent_id'] = 0;
		}

		$data = [
	    "trans_no" => $trans_id,
	    "description" => "General Donation - 月捐",
	    "hjgr" => $input['hjgr'],
	    "total_amount" => $input['total_amount'],
	    "mode_payment" => $input['yuejuan_mode_payment'],
	    "cheque_no" => $input['cheque_no'],
			"nets_no" => $input['nets_no'],
	    "receipt_at" =>	$receipt_at,
	    "manualreceipt" => $input['manualreceipt'],
	    "trans_at" => Carbon::now(),
	    "focusdevotee_id" => $input['focusdevotee_id'],
	    "festiveevent_id" => $input['festiveevent_id']
	  ];

		$general_donation = GeneralDonation::create($data);

		$membership = MembershipFee::all()->first();

		if($general_donation)
		{
			for($i = 0; $i < count($input['amount']); $i++)
			{
				$devotee = Devotee::find($input['devotee_id'][$i]);

				$devotee->lasttransaction_at = Carbon::now();
				$devotee->save();

				if(isset($input['amount'][$i]))
				{
					if(count(Receipt::all()) > 0)
					{
						$same_xy_receipt = Receipt::all()->last()->receipt_id;
					}

					else {
						$result = GlCode::where('glcode_id', '108')->pluck('next_sn_number');
						$same_xy_receipt = $result[0];
					}

					$prefix = GlCode::where('glcode_id', '108')->pluck('receipt_prefix');
					$prefix = $prefix[0];
					$same_xy_receipt += 1;
					// $same_xy_receipt = $prefix . $same_xy_receipt;

					$year = date('Y');
					$year = substr( $year, -2);

					$xy_receipt = str_pad($same_xy_receipt, 4, 0, STR_PAD_LEFT);
					$xy_receipt = $prefix . $year . $xy_receipt;

					if($input['amount'][$i] == 0)
					{
						$input['amount'][$i] = 10;
						$glcode_id = 108;
						$entrancefee_credit_amount += $input['amount'][$i];
					}

					else {
						$input['amount'][$i] = $input['amount'][$i] * $membership->membership_fee;
						$glcode_id = 110;
						$monthlysubscription_credit_amount += $input['amount'][$i];
					}

					$receipt = [
						"xy_receipt" => $xy_receipt,
						"trans_date" => Carbon::now(),
						"description" => "General Donation - 月捐",
						"amount" => $input['amount'][$i],
						"hjgr" => null,
						"display" => null,
						"glcode_id" => $glcode_id,
						"devotee_id" => $input['devotee_id'][$i],
						"generaldonation_id" => $general_donation->generaldonation_id,
						"staff_id" => Auth::user()->id
					];

					Receipt::create($receipt);
				}
			}
		}

		// Create Journal
		$year = date("y");

    if(count(JournalEntry::all()))
    {
      $journalentry_id = JournalEntry::all()->last()->journalentry_id;
      $journalentry_id = str_pad($journalentry_id + 1, 4, 0, STR_PAD_LEFT);
    }

    else
    {
      $journalentry_id = 0;
      $journalentry_id = str_pad($journalentry_id + 1, 4, 0, STR_PAD_LEFT);
    }

    $reference_no = 'J-' . $year . $journalentry_id;

		$data = [
      "journalentry_no" => $reference_no,
      "date" => Carbon::now(),
      "description" => "General Donation - 月捐",
			"devotee_id" => $input['focusdevotee_id'],
      "type" => "journal",
      "total_debit_amount" => $input['total_amount'],
      "total_credit_amount" => $input['total_amount']
    ];

		$journalentry = JournalEntry::create($data);

		$data = [
			"glcode_id" => 9,
			"debit_amount" => $input['total_amount'],
			"credit_amount" => null,
			"journalentry_id" => $journalentry->journalentry_id
		];

		JournalEntryItem::create($data);

		if($entrancefee_credit_amount != 0)
		{
			$data = [
				"glcode_id" => 108,
				"debit_amount" => null,
				"credit_amount" => $entrancefee_credit_amount,
				"journalentry_id" => $journalentry->journalentry_id
			];

			JournalEntryItem::create($data);
		}

		if($monthlysubscription_credit_amount != 0)
		{
			$data = [
				"glcode_id" => 110,
				"debit_amount" => null,
				"credit_amount" => $monthlysubscription_credit_amount,
				"journalentry_id" => $journalentry->journalentry_id
			];

			JournalEntryItem::create($data);
		}

		Session::forget('yuejuan_receipts');
		Session::forget('xianyou_same_family');
		Session::forget('xianyou_different_family');
		Session::forget('yuejuan_same_family');
		Session::forget('yuejuan_different_family');
		Session::forget('samefamily_amount');
		Session::forget('setting_samefamily');
		Session::forget('xianyou_focusdevotee');
		Session::forget('setting_differentfamily');
		Session::forget('differentfamily_amount');

		$focus_devotee = Devotee::find($input['focusdevotee_id']);

		$xianyou_same_family = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
                           ->leftjoin('setting_generaldonation', 'devotee.devotee_id', '=', 'setting_generaldonation.devotee_id')
                           ->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
                           ->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
                           ->where('devotee.familycode_id', $focus_devotee->familycode_id)
													 ->where('devotee.devotee_id', '!=', $focus_devotee->devotee_id)
                           ->where('setting_generaldonation.address_code', '=', 'same')
                           ->where('setting_generaldonation.xiangyou_ciji_id', '=', '1')
													 ->where('setting_generaldonation.focusdevotee_id', '=', $focus_devotee->devotee_id)
                           ->select('devotee.*', 'familycode.familycode', 'member.paytill_date', 'specialremarks.devotee_id as specialremarks_devotee_id')
													 ->GroupBy('devotee.devotee_id')
                           ->get();

		for($i = 0; $i < count($xianyou_same_family); $i++)
		{
			$hasreceipt = Receipt::where('devotee_id', $xianyou_same_family[$i]->devotee_id)
										->where('description', 'General Donation - 香油')
										->get();

			if(count($hasreceipt) > 0)
			{
				$same_xy_receipt = Receipt::all()
													 ->where('devotee_id', $xianyou_same_family[$i]->devotee_id)
													 ->where('description', 'General Donation - 香油')
													 ->last()
													 ->xy_receipt;

				$xianyou_same_family[$i]->xyreceipt = $same_xy_receipt;
			}

			else {
				$xianyou_same_family[$i]->xyreceipt = "";
			}
		}

		$xianyou_same_focusdevotee = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
			 											     ->leftjoin('setting_generaldonation', 'devotee.devotee_id', '=', 'setting_generaldonation.devotee_id')
			 											     ->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
			 											     ->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
			 											     ->where('setting_generaldonation.address_code', '=', 'same')
			 											     ->where('setting_generaldonation.xiangyou_ciji_id', '=', '1')
			 													 ->where('setting_generaldonation.focusdevotee_id', '=', $focus_devotee->devotee_id)
			 													 ->where('setting_generaldonation.devotee_id', '=', $focus_devotee->devotee_id)
			 											     ->select('devotee.*', 'familycode.familycode', 'member.paytill_date', 'specialremarks.devotee_id as specialremarks_devotee_id')
			 													 ->GroupBy('devotee.devotee_id')
			 											     ->get();

		for($i = 0; $i < count($xianyou_same_focusdevotee); $i++)
		{
			$hasreceipt = Receipt::where('devotee_id', $xianyou_same_focusdevotee[0]->devotee_id)
										->where('description', 'General Donation - 香油')
										->get();

			if(count($hasreceipt) > 0)
			{
				$same_xy_receipt = Receipt::all()
													 ->where('devotee_id', $xianyou_same_focusdevotee[0]->devotee_id)
													 ->where('description', 'General Donation - 香油')
													 ->last()
													 ->xy_receipt;

				$xianyou_same_focusdevotee[0]->xyreceipt = $same_xy_receipt;
			}

			else {
				$xianyou_same_focusdevotee[0]->xyreceipt = "";
			}
		}

		$xianyou_different_family = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
											          ->leftjoin('setting_generaldonation', 'devotee.devotee_id', '=', 'setting_generaldonation.devotee_id')
											          ->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
											          ->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
											          ->where('devotee.devotee_id', '!=', $focus_devotee->devotee_id)
											          ->where('setting_generaldonation.address_code', '=', 'different')
											          ->where('setting_generaldonation.xiangyou_ciji_id', '=', '1')
											          ->where('setting_generaldonation.focusdevotee_id', '=', $focus_devotee->devotee_id)
											          ->select('devotee.*', 'member.paytill_date', 'specialremarks.devotee_id as specialremarks_devotee_id', 'familycode.familycode')
											          ->GroupBy('devotee.devotee_id')
											          ->get();

		for($i = 0; $i < count($xianyou_different_family); $i++)
		{
			$hasreceipt = Receipt::where('devotee_id', $xianyou_different_family[$i]->devotee_id)
										->where('description', 'General Donation - 香油')
										->get();

			if(count($hasreceipt) > 0)
			{
				$same_xy_receipt = Receipt::all()
													 ->where('devotee_id', $xianyou_different_family[$i]->devotee_id)
													 ->where('description', 'General Donation - 香油')
													 ->last()
													 ->xy_receipt;

				$xianyou_different_family[$i]->xyreceipt = $same_xy_receipt;
			}

			else {
				$xianyou_different_family[$i]->xyreceipt = "";
			}
		}

		$ciji_same_family = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
                        ->leftjoin('setting_generaldonation', 'devotee.devotee_id', '=', 'setting_generaldonation.devotee_id')
                        ->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
                        ->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
                        ->where('devotee.familycode_id', $focus_devotee->familycode_id)
												->where('devotee.devotee_id', '!=', $focus_devotee->devotee_id)
                        ->where('setting_generaldonation.address_code', '=', 'same')
                        ->where('setting_generaldonation.xiangyou_ciji_id', '=', '1')
												->where('setting_generaldonation.focusdevotee_id', '=', $focus_devotee->devotee_id)
                        ->select('devotee.*', 'familycode.familycode', 'member.paytill_date', 'specialremarks.devotee_id as specialremarks_devotee_id')
												->GroupBy('devotee.devotee_id')
                        ->get();

		for($i = 0; $i < count($ciji_same_family); $i++)
		{
			$hasreceipt = Receipt::where('devotee_id', $ciji_same_family[$i]->devotee_id)
										->where('description', 'General Donation - 慈济')
										->get();

			if(count($hasreceipt) > 0)
			{
				$same_xy_receipt = Receipt::all()
													 ->where('devotee_id', $ciji_same_family[$i]->devotee_id)
													 ->where('description', 'General Donation - 慈济')
													 ->last()
													 ->xy_receipt;

				$ciji_same_family[$i]->xyreceipt = $same_xy_receipt;
			}

			else {
				$ciji_same_family[$i]->xyreceipt = "";
			}
		}

		$ciji_same_focusdevotee = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
			 											  ->leftjoin('setting_generaldonation', 'devotee.devotee_id', '=', 'setting_generaldonation.devotee_id')
			 											  ->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
			 											  ->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
			 											  ->where('setting_generaldonation.address_code', '=', 'same')
			 											  ->where('setting_generaldonation.xiangyou_ciji_id', '=', '1')
			 												->where('setting_generaldonation.focusdevotee_id', '=', $focus_devotee->devotee_id)
			 												->where('setting_generaldonation.devotee_id', '=', $focus_devotee->devotee_id)
			 											  ->select('devotee.*', 'familycode.familycode', 'member.paytill_date', 'specialremarks.devotee_id as specialremarks_devotee_id')
			 												->GroupBy('devotee.devotee_id')
			 											  ->get();

		$hasreceipt = Receipt::where('devotee_id', $ciji_same_focusdevotee[0]->devotee_id)
									->where('description', 'General Donation - 慈济')
									->get();

		if(count($hasreceipt) > 0)
		{
			$same_xy_receipt = Receipt::all()
												 ->where('devotee_id', $ciji_same_focusdevotee[0]->devotee_id)
												 ->where('description', 'General Donation - 慈济')
												 ->last()
												 ->xy_receipt;

			$ciji_same_focusdevotee[0]->xyreceipt = $same_xy_receipt;
		}

		else {
			$ciji_same_focusdevotee[0]->xyreceipt = "";
		}

		$ciji_different_family = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
											       ->leftjoin('setting_generaldonation', 'devotee.devotee_id', '=', 'setting_generaldonation.devotee_id')
											       ->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
											       ->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
											       ->where('devotee.devotee_id', '!=', $focus_devotee->devotee_id)
											       ->where('setting_generaldonation.address_code', '=', 'different')
											       ->where('setting_generaldonation.xiangyou_ciji_id', '=', '1')
											       ->where('setting_generaldonation.focusdevotee_id', '=', $focus_devotee->devotee_id)
											       ->select('devotee.*', 'member.paytill_date', 'specialremarks.devotee_id as specialremarks_devotee_id', 'familycode.familycode')
											       ->GroupBy('devotee.devotee_id')
											       ->get();

		for($i = 0; $i < count($ciji_different_family); $i++)
		{
			$hasreceipt = Receipt::where('devotee_id', $ciji_different_family[$i]->devotee_id)
										->where('description', 'General Donation - 慈济')
										->get();

			if(count($hasreceipt) > 0)
			{
				$same_xy_receipt = Receipt::all()
													 ->where('devotee_id', $ciji_different_family[$i]->devotee_id)
													 ->where('description', 'General Donation - 慈济')
													 ->last()
													 ->xy_receipt;

				$ciji_different_family[$i]->xyreceipt = $same_xy_receipt;
			}

			else {
				$ciji_different_family[$i]->xyreceipt = "";
			}
		}

		$yuejuan_same_family = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
													 ->leftjoin('setting_generaldonation', 'devotee.devotee_id', '=', 'setting_generaldonation.devotee_id')
													 ->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
													 ->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
													 ->where('devotee.familycode_id', $focus_devotee->familycode_id)
													 ->where('devotee.devotee_id', '!=', $focus_devotee->devotee_id)
													 ->where('setting_generaldonation.address_code', '=', 'same')
													 ->where('setting_generaldonation.yuejuan_id', '=', '1')
													 ->where('setting_generaldonation.focusdevotee_id', '=', $focus_devotee->devotee_id)
													 ->select('devotee.*', 'familycode.familycode', 'member.member', 'member.paytill_date', 'specialremarks.devotee_id as specialremarks_devotee_id')
													 ->GroupBy('devotee.devotee_id')
													 ->get();

		for($i = 0; $i < count($yuejuan_same_family); $i++)
		{
			$hasreceipt = Receipt::where('devotee_id', $yuejuan_same_family[$i]->devotee_id)
										->where('description', 'General Donation - 月捐')
										->get();

			if(count($hasreceipt) > 0)
			{
				$same_xy_receipt = Receipt::all()
													 ->where('devotee_id', $yuejuan_same_family[$i]->devotee_id)
													 ->where('description', 'General Donation - 月捐')
													 ->last()
													 ->xy_receipt;

				$yuejuan_same_family[$i]->xyreceipt = $same_xy_receipt;
			}

			else {
				$yuejuan_same_family[$i]->xyreceipt = "";
			}
		}

		$yuejuan_same_focusdevotee = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
																 ->leftjoin('setting_generaldonation', 'devotee.devotee_id', '=', 'setting_generaldonation.devotee_id')
																 ->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
																 ->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
																 ->where('setting_generaldonation.address_code', '=', 'same')
																 ->where('setting_generaldonation.yuejuan_id', '=', '1')
																 ->where('setting_generaldonation.focusdevotee_id', '=', $focus_devotee->devotee_id)
																 ->where('setting_generaldonation.devotee_id', '=', $focus_devotee->devotee_id)
																 ->select('devotee.*', 'familycode.familycode', 'member.member', 'member.paytill_date', 'specialremarks.devotee_id as specialremarks_devotee_id')
																 ->GroupBy('devotee.devotee_id')
																 ->get();

		$hasreceipt = Receipt::where('devotee_id', $yuejuan_same_focusdevotee[0]->devotee_id)
									->where('description', 'General Donation - 月捐')
									->get();

		if(count($hasreceipt) > 0)
		{
			$same_xy_receipt = Receipt::all()
												 ->where('devotee_id', $yuejuan_same_focusdevotee[0]->devotee_id)
												 ->where('description', 'General Donation - 月捐')
												 ->last()
												 ->xy_receipt;

			$yuejuan_same_focusdevotee[0]->xyreceipt = $same_xy_receipt;
		}

		else {
			$yuejuan_same_focusdevotee[0]->xyreceipt = "";
		}

		$yuejuan_different_family = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
																->leftjoin('setting_generaldonation', 'devotee.devotee_id', '=', 'setting_generaldonation.devotee_id')
																->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
																->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
																->where('devotee.devotee_id', '!=', $focus_devotee->devotee_id)
																->where('setting_generaldonation.address_code', '=', 'different')
																->where('setting_generaldonation.yuejuan_id', '=', '1')
																->where('setting_generaldonation.focusdevotee_id', '=', $focus_devotee->devotee_id)
																->select('devotee.*', 'member.member', 'member.paytill_date', 'specialremarks.devotee_id as specialremarks_devotee_id', 'familycode.familycode')
																->GroupBy('devotee.devotee_id')
																->get();

		for($i = 0; $i < count($yuejuan_different_family); $i++)
		{
			$hasreceipt = Receipt::where('devotee_id', $yuejuan_different_family[$i]->devotee_id)
										->where('description', 'General Donation - 月捐')
										->get();

			if(count($hasreceipt) > 0)
			{
				$same_xy_receipt = Receipt::all()
													 ->where('devotee_id', $yuejuan_different_family[$i]->devotee_id)
													 ->where('description', 'General Donation - 月捐')
													 ->last()
													 ->xy_receipt;

				$yuejuan_different_family[$i]->xyreceipt = $same_xy_receipt;
			}

			else {
				$yuejuan_different_family[$i]->xyreceipt = "";
			}
		}

		$result = SettingGeneralDonation::where('focusdevotee_id', $focus_devotee->devotee_id)->get();

		if(count($result) > 0)
		{
			$setting_samefamily = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
																	->leftjoin('setting_generaldonation', 'setting_generaldonation.devotee_id', '=', 'devotee.devotee_id')
																	->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
																	->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
																	->where('devotee.devotee_id', '!=', $focus_devotee->devotee_id)
																	->where('devotee.familycode_id', $focus_devotee->familycode_id)
																	->where('setting_generaldonation.focusdevotee_id', $focus_devotee->devotee_id)
																	->select('devotee.*', 'member.member', 'member.paytill_date', 'specialremarks.devotee_id as specialremarks_devotee_id', 'familycode.familycode', 'setting_generaldonation.xiangyou_ciji_id', 'setting_generaldonation.yuejuan_id')
																	->GroupBy('devotee.devotee_id')
																	->get();

			$nosetting_samefamily = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
															->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
															->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
															->where('devotee.familycode_id', $focus_devotee->familycode_id)
															->where('devotee.devotee_id', '!=', $focus_devotee->devotee_id)
															->select('devotee.*', 'member.member', 'member.paytill_date', 'specialremarks.devotee_id as specialremarks_devotee_id', 'familycode.familycode')
															->GroupBy('devotee.devotee_id')
															->get();

			if(count($nosetting_samefamily) > 0)
			{
				for($i = 0; $i < count($nosetting_samefamily); $i++)
				{
					$nosetting_samefamily[$i]->xiangyou_ciji_id = 0;
					$nosetting_samefamily[$i]->yuejuan_id = 0;
				}

				$setting_samefamily = $nosetting_samefamily->merge($setting_samefamily);
			}
		}

		else
		{
			$setting_samefamily = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
														->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
														->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
														->where('devotee.devotee_id', '!=', $focus_devotee->devotee_id)
														->where('devotee.familycode_id', $focus_devotee->familycode_id)
														->select('devotee.*', 'member.member', 'member.paytill_date', 'specialremarks.devotee_id as specialremarks_devotee_id', 'familycode.familycode')
														->GroupBy('devotee.devotee_id')
														->get();

			 for($i = 0; $i < count($setting_samefamily); $i++)
			 {
				 $setting_samefamily[$i]->xiangyou_ciji_id = 0;
				 $setting_samefamily[$i]->yuejuan_id = 0;
			 }
		}

		$setting = SettingGeneralDonation::where('focusdevotee_id', $focus_devotee->devotee_id)
							 ->where('devotee_id', $focus_devotee->devotee_id)
							 ->get();

		if(count($setting) > 0)
		{
			$xianyou_focusdevotee = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
															->leftjoin('setting_generaldonation', 'devotee.devotee_id', '=', 'setting_generaldonation.devotee_id')
															->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
															->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
															->where('devotee.devotee_id', $focus_devotee->devotee_id)
															->where('setting_generaldonation.focusdevotee_id', $focus_devotee->devotee_id)
															->select('devotee.*', 'familycode.familycode', 'member.paytill_date', 'specialremarks.devotee_id as specialremarks_devotee_id', 'setting_generaldonation.xiangyou_ciji_id', 'setting_generaldonation.yuejuan_id')
															->GroupBy('devotee.devotee_id')
												     	->get();
		}

		else
		{
			$xianyou_focusdevotee = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
															->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
															->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
															->where('devotee.devotee_id', $focus_devotee->devotee_id)
															->select('devotee.*', 'familycode.familycode', 'member.paytill_date', 'specialremarks.devotee_id as specialremarks_devotee_id')
												     	->get();

			$xianyou_focusdevotee[0]->xiangyou_ciji_id = 0;
			$xianyou_focusdevotee[0]->yuejuan_id = 0;
		}

		$setting_differentfamily = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
															  ->leftjoin('setting_generaldonation', 'setting_generaldonation.devotee_id', '=', 'devotee.devotee_id')
																->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
																->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
																->where('setting_generaldonation.focusdevotee_id', '=', $focus_devotee->devotee_id)
																->where('setting_generaldonation.address_code', '=', 'different')
																->select('devotee.*', 'member.paytill_date', 'specialremarks.devotee_id as specialremarks_devotee_id', 'familycode.familycode', 'setting_generaldonation.xiangyou_ciji_id', 'setting_generaldonation.yuejuan_id')
																->GroupBy('devotee.devotee_id')
																->get();

		// Yuejuan Receipts
		$yuejuan_receipt_collection = collect();

		$yuejuan_receipts = GeneralDonation::leftjoin('devotee', 'devotee.devotee_id', '=', 'generaldonation.focusdevotee_id')
												->leftjoin('receipt', 'receipt.generaldonation_id', '=', 'generaldonation.generaldonation_id')
												->where('generaldonation.focusdevotee_id', $input['focusdevotee_id'])
												->whereIn('receipt.glcode_id', array(108, 110))
												->GroupBy('generaldonation.generaldonation_id')
												->select('generaldonation.*', 'devotee.chinese_name')
												->orderBy('generaldonation.generaldonation_id', 'desc')
												->get();

		$paidby_otheryuejuan_receipts = Receipt::leftjoin('generaldonation', 'receipt.generaldonation_id', '=', 'generaldonation.generaldonation_id')
																		->leftjoin('devotee', 'devotee.devotee_id', '=', 'generaldonation.focusdevotee_id')
																		->where('receipt.devotee_id', $input['focusdevotee_id'])
																		->whereIn('receipt.glcode_id', array(108, 110))
																		->where('generaldonation.focusdevotee_id', '!=', $input['focusdevotee_id'])
																		->select('generaldonation.*', 'devotee.chinese_name', 'receipt.cancelled_date', 'receipt.xy_receipt as receipt_no')
																		->get();

		$membership = MembershipFee::all()->first();

		if(count($yuejuan_receipts) > 0)
		{
			for($i = 0; $i < count($yuejuan_receipts); $i++)
			{
				$data = Receipt::where('generaldonation_id', $yuejuan_receipts[$i]->generaldonation_id)->pluck('xy_receipt');

				$receipt_count = count($data);

				if($receipt_count > 1)
				{
					$yuejuan_receipts[$i]->receipt_no = $data[0] . ' - ' . $data[$receipt_count - 1];
				}
				else
				{
					$yuejuan_receipts[$i]->receipt_no = $data[0];
				}
			}
		}

		$yuejuan_receipt_collection = $yuejuan_receipt_collection->merge($yuejuan_receipts);
		$yuejuan_receipt_collection = $yuejuan_receipt_collection->merge($paidby_otheryuejuan_receipts);

		$yuejuan_receipts = $yuejuan_receipt_collection->sortByDesc('generaldonation_id');
		$yuejuan_receipts->values()->all();

		$focusdevotee_amount = [];

		if(count($yuejuan_same_focusdevotee) > 0)
		{
			if(isset($yuejuan_same_focusdevotee[0]->paytill_date))
			{
				$amount = [];

				$myArray = explode('-', $yuejuan_same_focusdevotee[0]->paytill_date);

				$count = 1;
				for($j = 1; $j <= 10; $j++)
				{
					$dt = Carbon::create($myArray[0], $myArray[1], $myArray[2], 0);
					$dt = $dt->addYears($count);

					$format = Carbon::parse($dt)->format("Y-m");

					$fee = $membership->membership_fee * $j;
					$amount[$j] = number_format($fee, 2) . ' --- ' . $format;

					$count++;
				}

				array_push($focusdevotee_amount, $amount);
			}
		}

		$samefamily_amount = [];

		for($i = 0; $i < count($yuejuan_same_family); $i++)
		{
			$amount = [];

			if(isset($yuejuan_same_family[$i]->paytill_date))
			{
				$myArray = explode('-', $yuejuan_same_family[$i]->paytill_date);

				$count = 1;
				for($j = 1; $j <= 10; $j++)
				{
					$dt = Carbon::create($myArray[0], $myArray[1], $myArray[2], 0);
					$dt = $dt->addYears($count);

					$format = Carbon::parse($dt)->format("Y-m");

					$fee = $membership->membership_fee * $j;
					$amount[$j] = number_format($fee, 2) . ' --- ' . $format;

					$count++;
				}
			}

			array_push($samefamily_amount, $amount);
		}

		$differentfamily_amount = [];

		for($i = 0; $i < count($yuejuan_different_family); $i++)
		{
			$amount = [];

			if(isset($yuejuan_different_family[$i]->paytill_date))
			{
				$myArray = explode('-', $yuejuan_different_family[$i]->paytill_date);

				$count = 1;
				for($j = 1; $j <= 10; $j++)
				{
					$dt = Carbon::create($myArray[0], $myArray[1], $myArray[2], 0);
					$dt = $dt->addYears($count);

					$format = Carbon::parse($dt)->format("Y-m");

					$fee = $membership->membership_fee * $j;
					$amount[$j] = number_format($fee, 2) . ' --- ' . $format;

					$count++;
				}
			}

			array_push($differentfamily_amount, $amount);
		}

		Session::put('yuejuan_receipts', $yuejuan_receipts);
		Session::put('xianyou_same_family', $xianyou_same_family);
		Session::put('xianyou_same_focusdevotee', $xianyou_same_focusdevotee);
		Session::put('xianyou_different_family', $xianyou_different_family);
		Session::put('ciji_same_family', $ciji_same_family);
		Session::put('ciji_same_focusdevotee', $ciji_same_focusdevotee);
		Session::put('ciji_different_family', $ciji_different_family);
		Session::put('yuejuan_same_family', $yuejuan_same_family);
		Session::put('yuejuan_same_focusdevotee', $yuejuan_same_focusdevotee);
		Session::put('yuejuan_different_family', $yuejuan_different_family);
		Session::put('setting_samefamily', $setting_samefamily);
		Session::put('xianyou_focusdevotee', $xianyou_focusdevotee);
		Session::put('setting_differentfamily', $setting_differentfamily);
		Session::put('focusdevotee_amount', $focusdevotee_amount);
		Session::put('samefamily_amount', $samefamily_amount);
		Session::put('differentfamily_amount', $differentfamily_amount);

		$generaldonation_id = $general_donation->generaldonation_id;
		$hjgr = $general_donation->hjgr;

		$result = Receipt::leftjoin('generaldonation', 'receipt.generaldonation_id', '=', 'generaldonation.generaldonation_id')
		           ->leftjoin('devotee', 'receipt.devotee_id', '=', 'devotee.devotee_id')
		           ->leftjoin('user', 'receipt.staff_id', '=', 'user.id')
							 ->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
		           ->leftjoin('festiveevent', 'generaldonation.festiveevent_id', '=', 'festiveevent.festiveevent_id')
		           ->where('generaldonation.generaldonation_id', '=', $generaldonation_id)
		           ->select('receipt.*', 'member.paytill_date', 'member.member_id', 'devotee.chinese_name', 'devotee.oversea_addr_in_chinese', 'devotee.address_houseno', 'devotee.address_unit1',
		            'devotee.address_unit2', 'devotee.address_street', 'devotee.address_postal', 'devotee.familycode_id', 'devotee.deceased_year',
		            'generaldonation.focusdevotee_id', 'generaldonation.trans_no', 'user.first_name', 'user.last_name',
		            'festiveevent.start_at', 'festiveevent.time', 'festiveevent.event', 'festiveevent.lunar_date', 'generaldonation.mode_payment')
		           ->get();

		for($i = 0; $i < count($result); $i++)
		{
			if(isset($result[$i]->paytill_date))
			{
				$result[$i]->paid_for = number_format($result[$i]->amount, 2) . ' - ' . Carbon::parse($result[$i]->paytill_date)->format("Y-m");
			}
		}

	  for($i = 0; $i < count($result); $i++)
		{
			$result[$i]->trans_date = \Carbon\Carbon::parse($result[$i]->trans_date)->format("d/m/Y");

			if($result[$i]->start_at)
			{
				$result[$i]->start_at = \Carbon\Carbon::parse($result[$i]->start_at)->format("d/m/Y");
			}
		}

		$familycode_id = $result[0]->familycode_id;
		$samefamily_no = 0;

		for($i = 0; $i < count($result); $i++)
		{
			if($result[$i]->familycode_id == $familycode_id)
			{
				$samefamily_no += 1;
				$total_amount += intval($result[$i]->amount);
			}

			$familycode_id = $result[$i]->familycode_id;
		}

		$paid_by = Devotee::where('devotee.devotee_id', $result[0]->focusdevotee_id)
						 		->select('chinese_name', 'devotee_id')
						 		->get();

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

		for($i = 0; $i < count($result); $i++)
		{
		  $first_familycode = $result[0]->familycode_id;

		  if($first_familycode == $result[$i]->familycode_id)
		  {
		    $count_familycode++;
		  }
		}

		return view('staff.yuejuan-print', [
			'receipts' => $result,
			'print_format' => $hjgr,
			'loop' => $loop,
			'count_familycode' => $count_familycode,
			'samefamily_no' => $samefamily_no,
			'total_amount' => number_format($total_amount, 2),
			'paid_by' => $paid_by
		]);

	}

	public function postCijiDonation(Request $request)
	{
		$input = array_except($request->all(), '_token');
		$total_amount = 0;

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

		if(empty($input['festiveevent_id']))
		{
			$input['festiveevent_id'] = 0;
		}

		$data = [
	    "trans_no" => $trans_id,
	    "description" => "General Donation - 慈济",
	    "hjgr" => $input['hjgr'],
	    "total_amount" => $input['total_amount'],
	    "mode_payment" => $input['ciji_mode_payment'],
	    "cheque_no" => $input['cheque_no'],
			"nets_no" => $input['nets_no'],
	    "receipt_at" =>	$receipt_at,
	    "manualreceipt" => $input['manualreceipt'],
	    "trans_at" => Carbon::now(),
	    "focusdevotee_id" => $input['focusdevotee_id'],
	    "festiveevent_id" => $input['festiveevent_id']
	  ];

		$general_donation = GeneralDonation::create($data);

		if($general_donation)
		{
			if(isset($input['amount']))
			{
				for($i = 0; $i < count($input['amount']); $i++)
				{
					if(isset($input['amount'][$i]))
					{
						$devotee = Devotee::find($input['devotee_id'][$i]);

						$devotee->lasttransaction_at = Carbon::now();
				    $devotee->save();

						if(count(Receipt::all()) > 0)
						{
							$same_xy_receipt = Receipt::all()->last()->receipt_id;
						}

						else {
							$result = GlCode::where('glcode_id', '134')->pluck('next_sn_number');
							$same_xy_receipt = $result[0];
						}

						$prefix = GlCode::where('glcode_id', '134')->pluck('receipt_prefix');
						$prefix = $prefix[0];
						$same_xy_receipt += 1;
						// $same_xy_receipt = $prefix . $same_xy_receipt;

						$year = date('Y');
						$year = substr( $year, -2);

						$xy_receipt = str_pad($same_xy_receipt, 4, 0, STR_PAD_LEFT);
						$xy_receipt = $prefix . $year . $xy_receipt;

						$receipt = [
							"xy_receipt" => $xy_receipt,
							"trans_date" => Carbon::now(),
							"description" => "General Donation - 慈济",
							"amount" => $input['amount'][$i],
							"hjgr" => $input['hjgr_arr'][$i],
							"display" => $input['display'][$i],
							"glcode_id" => 134,
							"devotee_id" => $input['devotee_id'][$i],
							"generaldonation_id" => $general_donation->generaldonation_id,
							"staff_id" => Auth::user()->id
						];

						Receipt::create($receipt);
					}
				}
			}

			if(isset($input['other_amount']))
			{
				for($i = 0; $i < count($input['other_amount']); $i++)
				{
					if(isset($input['other_amount'][$i]))
					{
						$devotee = Devotee::find($input['other_devotee_id'][$i]);

						$devotee->lasttransaction_at = Carbon::now();
				    $devotee->save();

						if(count(Receipt::all()) > 0)
						{
							$same_xy_receipt = Receipt::all()->last()->receipt_id;
						}

						else {
							$result = GlCode::where('glcode_id', '134')->pluck('next_sn_number');
							$same_xy_receipt = $result[0];
						}

						$prefix = GlCode::where('glcode_id', '134')->pluck('receipt_prefix');
						$prefix = $prefix[0];
						$same_xy_receipt += 1;
						// $same_xy_receipt = $prefix . $same_xy_receipt;

						$year = date('Y');
						$year = substr( $year, -2);

						$xy_receipt = str_pad($same_xy_receipt, 4, 0, STR_PAD_LEFT);
						$xy_receipt = $prefix . $year . $xy_receipt;

						$receipt = [
							"xy_receipt" => $xy_receipt,
							"trans_date" => Carbon::now(),
							"description" => "General Donation - 慈济",
							"amount" => $input['other_amount'][$i],
							"hjgr" => $input['other_hjgr_arr'][$i],
							"display" => $input['other_display'][$i],
							"glcode_id" => 134,
							"devotee_id" => $input['other_devotee_id'][$i],
							"generaldonation_id" => $general_donation->generaldonation_id,
							"staff_id" => Auth::user()->id
						];

						Receipt::create($receipt);
					}
				}
			}
		}

		// Create Journal
		$year = date("y");

    if(count(JournalEntry::all()))
    {
      $journalentry_id = JournalEntry::all()->last()->journalentry_id;
      $journalentry_id = str_pad($journalentry_id + 1, 4, 0, STR_PAD_LEFT);
    }

    else
    {
      $journalentry_id = 0;
      $journalentry_id = str_pad($journalentry_id + 1, 4, 0, STR_PAD_LEFT);
    }

    $reference_no = 'J-' . $year . $journalentry_id;

		$data = [
      "journalentry_no" => $reference_no,
      "date" => Carbon::now(),
      "description" => "General Donation - 慈济",
			"devotee_id" => $input['focusdevotee_id'],
      "type" => "journal",
      "total_debit_amount" => $input['total_amount'],
      "total_credit_amount" => $input['total_amount']
    ];

		$journalentry = JournalEntry::create($data);

		$data = [
			"glcode_id" => 9,
			"debit_amount" => $input['total_amount'],
			"credit_amount" => null,
			"journalentry_id" => $journalentry->journalentry_id
		];

		JournalEntryItem::create($data);

		$data = [
			"glcode_id" => 134,
			"debit_amount" => null,
			"credit_amount" => $input['total_amount'],
			"journalentry_id" => $journalentry->journalentry_id
		];

		JournalEntryItem::create($data);

		Session::forget('ciji_receipts');
		Session::forget('ciji_same_focusdevotee');
		Session::forget('ciji_same_family');
		Session::forget('ciji_different_family');

		$devotee = Devotee::find($input['focusdevotee_id']);

		$ciji_same_focusdevotee = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
			                           ->leftjoin('setting_generaldonation', 'devotee.devotee_id', '=', 'setting_generaldonation.devotee_id')
			                           ->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
			                           ->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
			                           ->where('setting_generaldonation.address_code', '=', 'same')
			                           ->where('setting_generaldonation.xiangyou_ciji_id', '=', '1')
																 ->where('setting_generaldonation.focusdevotee_id', '=', $devotee->devotee_id)
																 ->where('setting_generaldonation.devotee_id', '=', $devotee->devotee_id)
			                           ->select('devotee.*', 'member.member', 'familycode.familycode', 'member.paytill_date', 'specialremarks.devotee_id as specialremarks_devotee_id')
																 ->GroupBy('devotee.devotee_id')
			                           ->get();

		$hasreceipt = Receipt::where('devotee_id', $ciji_same_focusdevotee[0]->devotee_id)
									->where('description', 'General Donation - 慈济')
									->get();

		if(count($hasreceipt) > 0)
		{
			$same_xy_receipt = Receipt::all()
												 ->where('devotee_id', $ciji_same_focusdevotee[0]->devotee_id)
												 ->where('description', 'General Donation - 慈济')
												 ->last()
												 ->xy_receipt;

			$ciji_same_focusdevotee[0]->xyreceipt = $same_xy_receipt;
		}

		else {
			$ciji_same_focusdevotee[0]->xyreceipt = "";
		}

		$ciji_same_family = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
                           ->leftjoin('setting_generaldonation', 'devotee.devotee_id', '=', 'setting_generaldonation.devotee_id')
                           ->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
                           ->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
                           ->where('devotee.familycode_id', $devotee->familycode_id)
													 ->where('devotee.devotee_id', '!=', $devotee->devotee_id)
                           ->where('setting_generaldonation.address_code', '=', 'same')
                           ->where('setting_generaldonation.xiangyou_ciji_id', '=', '1')
													 ->where('setting_generaldonation.focusdevotee_id', '=', $devotee->devotee_id)
                           ->select('devotee.*', 'member.member', 'familycode.familycode', 'member.paytill_date', 'specialremarks.devotee_id as specialremarks_devotee_id')
													 ->GroupBy('devotee.devotee_id')
                           ->get();

		for($i = 0; $i < count($ciji_same_family); $i++)
		{
			$hasreceipt = Receipt::where('devotee_id', $ciji_same_family[$i]->devotee_id)
										->where('description', 'General Donation - 慈济')
										->get();

			if(count($hasreceipt) > 0)
			{
				$same_xy_receipt = Receipt::all()
													 ->where('devotee_id', $ciji_same_family[$i]->devotee_id)
													 ->where('description', 'General Donation - 慈济')
													 ->last()
													 ->xy_receipt;

				$ciji_same_family[$i]->xyreceipt = $same_xy_receipt;
			}

			else {
				$ciji_same_family[$i]->xyreceipt = "";
			}
		}

		$ciji_different_family = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
											          ->leftjoin('setting_generaldonation', 'devotee.devotee_id', '=', 'setting_generaldonation.devotee_id')
											          ->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
											          ->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
											          ->where('devotee.devotee_id', '!=', $devotee->devotee_id)
											          ->where('setting_generaldonation.address_code', '=', 'different')
											          ->where('setting_generaldonation.xiangyou_ciji_id', '=', '1')
											          ->where('setting_generaldonation.focusdevotee_id', '=', $devotee->devotee_id)
											          ->select('devotee.*', 'member.member', 'member.paytill_date', 'specialremarks.devotee_id as specialremarks_devotee_id', 'familycode.familycode')
											          ->GroupBy('devotee.devotee_id')
											          ->get();

		for($i = 0; $i < count($ciji_different_family); $i++)
		{
			$hasreceipt = Receipt::where('devotee_id', $ciji_different_family[$i]->devotee_id)
										->where('description', 'General Donation - 慈济')
										->get();

			if(count($hasreceipt) > 0)
			{
				$same_xy_receipt = Receipt::all()
													 ->where('devotee_id', $ciji_different_family[$i]->devotee_id)
													 ->where('description', 'General Donation - 慈济')
													 ->last()
													 ->xy_receipt;

				$ciji_different_family[$i]->xyreceipt = $same_xy_receipt;
			}

			else {
				$ciji_different_family[$i]->xyreceipt = "";
			}
		}

		// Ciji Receipts
		$ciji_receipt_collection = collect();

		$ciji_receipts = GeneralDonation::leftjoin('devotee', 'devotee.devotee_id', '=', 'generaldonation.focusdevotee_id')
										 ->leftjoin('receipt', 'receipt.generaldonation_id', '=', 'generaldonation.generaldonation_id')
										 ->where('generaldonation.focusdevotee_id', $input['focusdevotee_id'])
										 ->where('receipt.glcode_id', 134)
										 ->GroupBy('generaldonation.generaldonation_id')
										 ->select('generaldonation.*', 'devotee.chinese_name', 'receipt.cancelled_date')
										 ->orderBy('generaldonation.generaldonation_id', 'desc')
										 ->get();

		$paidby_otherciji_receipts = Receipt::leftjoin('generaldonation', 'receipt.generaldonation_id', '=', 'generaldonation.generaldonation_id')
																	->leftjoin('devotee', 'devotee.devotee_id', '=', 'generaldonation.focusdevotee_id')
																	->where('receipt.devotee_id', $input['focusdevotee_id'])
																	->where('receipt.glcode_id', 134)
																	->where('generaldonation.focusdevotee_id', '!=', $input['focusdevotee_id'])
																	->select('generaldonation.*', 'devotee.chinese_name', 'receipt.cancelled_date', 'receipt.xy_receipt as receipt_no')
																	->get();

		if(count($ciji_receipts) > 0)
		{
			for($i = 0; $i < count($ciji_receipts); $i++)
			{
				$data = Receipt::where('generaldonation_id', $ciji_receipts[$i]->generaldonation_id)->pluck('xy_receipt');
				$receipt_count = count($data);

				if($receipt_count > 1)
				{
					$ciji_receipts[$i]->receipt_no = $data[0] . ' - ' . $data[$receipt_count - 1];
				}
				else
				{
					$ciji_receipts[$i]->receipt_no = $data[0];
				}
			}
		}

		$ciji_receipt_collection = $ciji_receipt_collection->merge($ciji_receipts);
		$ciji_receipt_collection = $ciji_receipt_collection->merge($paidby_otherciji_receipts);

		$ciji_receipts = $ciji_receipt_collection->sortByDesc('generaldonation_id');
		$ciji_receipts->values()->all();

		Session::put('ciji_receipts', $ciji_receipts);
		Session::put('ciji_same_focusdevotee', $ciji_same_focusdevotee);
		Session::put('ciji_same_family', $ciji_same_family);
		Session::put('ciji_different_family', $ciji_different_family);

		$generaldonation_id = $general_donation->generaldonation_id;
		$hjgr = $general_donation->hjgr;

		$result = Receipt::leftjoin('generaldonation', 'receipt.generaldonation_id', '=', 'generaldonation.generaldonation_id')
		           ->leftjoin('devotee', 'receipt.devotee_id', '=', 'devotee.devotee_id')
		           ->leftjoin('user', 'receipt.staff_id', '=', 'user.id')
		           ->leftjoin('festiveevent', 'generaldonation.festiveevent_id', '=', 'festiveevent.festiveevent_id')
		           ->where('generaldonation.generaldonation_id', '=', $generaldonation_id)
		           ->select('receipt.*', 'devotee.chinese_name', 'devotee.oversea_addr_in_chinese', 'devotee.address_houseno', 'devotee.address_unit1',
		            'devotee.address_unit2', 'devotee.address_street', 'devotee.address_postal', 'devotee.familycode_id', 'devotee.deceased_year',
		            'generaldonation.focusdevotee_id', 'generaldonation.trans_no', 'user.first_name', 'user.last_name',
		            'festiveevent.start_at', 'festiveevent.time', 'festiveevent.event', 'festiveevent.lunar_date', 'generaldonation.mode_payment')
		           ->get();

			for($i = 0; $i < count($result); $i++)
			{
				$result[$i]->trans_date = \Carbon\Carbon::parse($result[$i]->trans_date)->format("d/m/Y");

				if($result[$i]->start_at)
				{
					$result[$i]->start_at = \Carbon\Carbon::parse($result[$i]->start_at)->format("d/m/Y");
				}
			}

			$familycode_id = $result[0]->familycode_id;
			$samefamily_no = 0;

			for($i = 0; $i < count($result); $i++)
			{
				if($result[$i]->familycode_id == $familycode_id)
				{
					$samefamily_no += 1;
					$total_amount += intval($result[$i]->amount);
				}

				$familycode_id = $result[$i]->familycode_id;
			}

			$paid_by = Devotee::where('devotee.devotee_id', $result[0]->focusdevotee_id)
					 		   ->select('chinese_name', 'devotee_id')
					 		   ->get();

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

			for($i = 0; $i < count($result); $i++)
			{
			  $first_familycode = $result[0]->familycode_id;

			  if($first_familycode == $result[$i]->familycode_id)
			  {
			    $count_familycode++;
			  }
			}

			return view('staff.print', [
				'receipts' => $result,
				'print_format' => $hjgr,
				'loop' => $loop,
				'count_familycode' => $count_familycode,
				'samefamily_no' => $samefamily_no,
				'total_amount' => number_format($total_amount, 2),
				'paid_by' => $paid_by
			]);
	}

	public function postDonation(Request $request)
	{
		$input = array_except($request->all(), '_token');
		$total_amount = 0;
		$non_member_credit_amount = 0;
		$member_credit_amount = 0;

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

		if(empty($input['festiveevent_id']))
		{
			$input['festiveevent_id'] = 0;
		}

		$data = [
	    "trans_no" => $trans_id,
	    "description" => "General Donation - 香油",
	    "hjgr" => $input['hjgr'],
	    "total_amount" => $input['total_amount'],
	    "mode_payment" => $input['mode_payment'],
	    "cheque_no" => $input['cheque_no'],
			"nets_no" => $input['nets_no'],
	    "receipt_at" =>	$receipt_at,
	    "manualreceipt" => $input['manualreceipt'],
	    "trans_at" => Carbon::now(),
	    "focusdevotee_id" => $input['focusdevotee_id'],
	    "festiveevent_id" => $input['festiveevent_id']
	  ];

		$general_donation = GeneralDonation::create($data);

		if($general_donation)
		{
			if(isset($input['amount']))
			{
				for($i = 0; $i < count($input['amount']); $i++)
				{
					if(isset($input['amount'][$i]))
					{
						$devotee = Devotee::find($input['devotee_id'][$i]);

						$devotee->lasttransaction_at = Carbon::now();
				    $devotee->save();

						if(count(Receipt::all()) > 0)
						{
							$same_xy_receipt = Receipt::all()->last()->receipt_id;
						}

						else {
							$result = GlCode::where('glcode_id', '119')->pluck('next_sn_number');
							$same_xy_receipt = $result[0];
						}

						$prefix = GlCode::where('glcode_id', '119')->pluck('receipt_prefix');
						$prefix = $prefix[0];
						$same_xy_receipt += 1;

						$year = date('Y');
						$year = substr( $year, -2);

						$xy_receipt = str_pad($same_xy_receipt, 4, 0, STR_PAD_LEFT);
						$xy_receipt = $prefix . $year . $xy_receipt;

						$devotee = Devotee::find($input['devotee_id'][$i]);

						if(isset($devotee->member_id))
					  {
					    $glcode = 119;
							$member_credit_amount += $input['amount'][$i];
					  }

					  else
					  {
					    $glcode = 112;
							$non_member_credit_amount += $input['amount'][$i];
					  }

						$receipt = [
							"xy_receipt" => $xy_receipt,
							"trans_date" => Carbon::now(),
							"description" => "General Donation - 香油",
							"amount" => $input['amount'][$i],
							"hjgr" => $input['hjgr_arr'][$i],
							"display" => $input['display'][$i],
							"glcode_id" => $glcode,
							"devotee_id" => $input['devotee_id'][$i],
							"generaldonation_id" => $general_donation->generaldonation_id,
							"staff_id" => Auth::user()->id
						];

						Receipt::create($receipt);
					}
				}
			}

			if(isset($input['other_amount']))
			{
				for($i = 0; $i < count($input['other_amount']); $i++)
				{
					if(isset($input['other_amount'][$i]))
					{
						$devotee = Devotee::find($input['other_devotee_id'][$i]);

						$devotee->lasttransaction_at = Carbon::now();
				    $devotee->save();

						if(count(Receipt::all()) > 0)
						{
							$same_xy_receipt = Receipt::all()->last()->receipt_id;
						}

						else {
							$result = GlCode::where('glcode_id', '119')->pluck('next_sn_number');
							$same_xy_receipt = $result[0];
						}

						$prefix = GlCode::where('glcode_id', '119')->pluck('receipt_prefix');
						$prefix = $prefix[0];
						$same_xy_receipt += 1;

						$year = date('Y');
						$year = substr( $year, -2);

						$xy_receipt = str_pad($same_xy_receipt, 4, 0, STR_PAD_LEFT);
						$xy_receipt = $prefix . $year . $xy_receipt;

						$devotee = Devotee::find($input['other_devotee_id'][$i]);

						if(isset($devotee->member_id))
					  {
					    $glcode = 119;
							$member_credit_amount += $input['other_amount'][$i];
					  }

					  else
					  {
					    $glcode = 112;
							$non_member_credit_amount += $input['other_amount'][$i];
					  }

						$receipt = [
							"xy_receipt" => $xy_receipt,
							"trans_date" => Carbon::now(),
							"description" => "General Donation - 香油",
							"amount" => $input['other_amount'][$i],
							"hjgr" => $input['other_hjgr_arr'][$i],
							"display" => $input['other_display'][$i],
							"glcode_id" => $glcode,
							"devotee_id" => $input['other_devotee_id'][$i],
							"generaldonation_id" => $general_donation->generaldonation_id,
							"staff_id" => Auth::user()->id
						];

						Receipt::create($receipt);
					}
				}
			}
		}

		// Create Journal
		$year = date("y");

    if(count(JournalEntry::all()))
    {
      $journalentry_id = JournalEntry::all()->last()->journalentry_id;
      $journalentry_id = str_pad($journalentry_id + 1, 4, 0, STR_PAD_LEFT);
    }

    else
    {
      $journalentry_id = 0;
      $journalentry_id = str_pad($journalentry_id + 1, 4, 0, STR_PAD_LEFT);
    }

    $reference_no = 'J-' . $year . $journalentry_id;

		$data = [
      "journalentry_no" => $reference_no,
      "date" => Carbon::now(),
      "description" => "General Donation - 香油",
			"devotee_id" => $input['focusdevotee_id'],
      "type" => "journal",
      "total_debit_amount" => $input['total_amount'],
      "total_credit_amount" => $input['total_amount']
    ];

		$journalentry = JournalEntry::create($data);

		$data = [
			"glcode_id" => 9,
			"debit_amount" => $input['total_amount'],
			"credit_amount" => null,
			"journalentry_id" => $journalentry->journalentry_id
		];

		JournalEntryItem::create($data);

		if($member_credit_amount != 0)
		{
			$data = [
				"glcode_id" => 119,
				"debit_amount" => null,
				"credit_amount" => $member_credit_amount,
				"journalentry_id" => $journalentry->journalentry_id
			];

			JournalEntryItem::create($data);
		}

		if($non_member_credit_amount != 0)
		{
			$data = [
				"glcode_id" => 112,
				"debit_amount" => null,
				"credit_amount" => $non_member_credit_amount,
				"journalentry_id" => $journalentry->journalentry_id
			];

			JournalEntryItem::create($data);
		}

		// remove session
	  Session::forget('receipts');
		Session::forget('xianyou_same_focusdevotee');
		Session::forget('xianyou_same_family');
		Session::forget('xianyou_different_family');

		$devotee = Devotee::find($input['focusdevotee_id']);

		$xianyou_same_focusdevotee = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
			                           ->leftjoin('setting_generaldonation', 'devotee.devotee_id', '=', 'setting_generaldonation.devotee_id')
			                           ->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
			                           ->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
			                           ->where('setting_generaldonation.address_code', '=', 'same')
			                           ->where('setting_generaldonation.xiangyou_ciji_id', '=', '1')
																 ->where('setting_generaldonation.focusdevotee_id', '=', $devotee->devotee_id)
																 ->where('setting_generaldonation.devotee_id', '=', $devotee->devotee_id)
			                           ->select('devotee.*', 'member.member', 'familycode.familycode', 'member.paytill_date', 'specialremarks.devotee_id as specialremarks_devotee_id')
																 ->GroupBy('devotee.devotee_id')
			                           ->get();

		for($i = 0; $i < count($xianyou_same_focusdevotee); $i++)
		{
			$hasreceipt = Receipt::where('devotee_id', $xianyou_same_focusdevotee[0]->devotee_id)
										->where('description', 'General Donation - 香油')
										->get();

			if(count($hasreceipt) > 0)
			{
				$same_xy_receipt = Receipt::all()
													 ->where('devotee_id', $xianyou_same_focusdevotee[0]->devotee_id)
													 ->where('description', 'General Donation - 香油')
													 ->last()
													 ->xy_receipt;

				$xianyou_same_focusdevotee[0]->xyreceipt = $same_xy_receipt;
			}

			else {
				$xianyou_same_focusdevotee[0]->xyreceipt = "";
			}
		}

		$xianyou_same_family = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
                           ->leftjoin('setting_generaldonation', 'devotee.devotee_id', '=', 'setting_generaldonation.devotee_id')
                           ->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
                           ->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
                           ->where('devotee.familycode_id', $devotee->familycode_id)
													 ->where('devotee.devotee_id', '!=', $devotee->devotee_id)
                           ->where('setting_generaldonation.address_code', '=', 'same')
                           ->where('setting_generaldonation.xiangyou_ciji_id', '=', '1')
													 ->where('setting_generaldonation.focusdevotee_id', '=', $devotee->devotee_id)
                           ->select('devotee.*', 'member.member', 'familycode.familycode', 'member.paytill_date', 'specialremarks.devotee_id as specialremarks_devotee_id')
													 ->GroupBy('devotee.devotee_id')
                           ->get();

		for($i = 0; $i < count($xianyou_same_family); $i++)
		{
			$hasreceipt = Receipt::where('devotee_id', $xianyou_same_family[$i]->devotee_id)
										->where('description', 'General Donation - 香油')
										->get();

			if(count($hasreceipt) > 0)
			{
				$same_xy_receipt = Receipt::all()
													 ->where('devotee_id', $xianyou_same_family[$i]->devotee_id)
													 ->where('description', 'General Donation - 香油')
													 ->last()
													 ->xy_receipt;

				$xianyou_same_family[$i]->xyreceipt = $same_xy_receipt;
			}

			else {
				$xianyou_same_family[$i]->xyreceipt = "";
			}
		}

		$xianyou_different_family = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
											          ->leftjoin('setting_generaldonation', 'devotee.devotee_id', '=', 'setting_generaldonation.devotee_id')
											          ->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
											          ->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
											          ->where('devotee.devotee_id', '!=', $devotee->devotee_id)
											          ->where('setting_generaldonation.address_code', '=', 'different')
											          ->where('setting_generaldonation.xiangyou_ciji_id', '=', '1')
											          ->where('setting_generaldonation.focusdevotee_id', '=', $devotee->devotee_id)
											          ->select('devotee.*', 'member.member', 'member.paytill_date', 'specialremarks.devotee_id as specialremarks_devotee_id', 'familycode.familycode')
											          ->GroupBy('devotee.devotee_id')
											          ->get();

		for($i = 0; $i < count($xianyou_different_family); $i++)
		{
			$hasreceipt = Receipt::where('devotee_id', $xianyou_different_family[$i]->devotee_id)
										->where('description', 'General Donation - 香油')
										->get();

			if(count($hasreceipt) > 0)
			{
				$same_xy_receipt = Receipt::all()
													 ->where('devotee_id', $xianyou_different_family[$i]->devotee_id)
													 ->where('description', 'General Donation - 香油')
													 ->last()
													 ->xy_receipt;

				$xianyou_different_family[$i]->xyreceipt = $same_xy_receipt;
			}

			else {
				$xianyou_different_family[$i]->xyreceipt = "";
			}
		}

		// Xianyou Receipts
		$receipt_collection = collect();

		$receipts = GeneralDonation::leftjoin('devotee', 'devotee.devotee_id', '=', 'generaldonation.focusdevotee_id')
								->leftjoin('receipt', 'receipt.generaldonation_id', '=', 'generaldonation.generaldonation_id')
								->where('generaldonation.focusdevotee_id', '=', $input['focusdevotee_id'])
								->GroupBy('generaldonation.generaldonation_id')
								->whereIn('receipt.glcode_id', array(119, 112))
								->select('generaldonation.*', 'devotee.chinese_name','receipt.cancelled_date')
								->orderBy('generaldonation.generaldonation_id', 'desc')
								->get();

		$paidby_otherreceipts = Receipt::leftjoin('generaldonation', 'receipt.generaldonation_id', '=', 'generaldonation.generaldonation_id')
														->leftjoin('devotee', 'devotee.devotee_id', '=', 'generaldonation.focusdevotee_id')
														->where('receipt.devotee_id', $input['focusdevotee_id'])
														->whereIn('receipt.glcode_id', array(119,112))
														->where('generaldonation.focusdevotee_id', '!=', $input['focusdevotee_id'])
														->select('generaldonation.*', 'devotee.chinese_name', 'receipt.cancelled_date', 'receipt.xy_receipt as receipt_no')
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

		$receipt_collection = $receipt_collection->merge($receipts);
		$receipt_collection = $receipt_collection->merge($paidby_otherreceipts);

		$receipts = $receipt_collection->sortByDesc('generaldonation_id');
		$receipts->values()->all();

		Session::put('receipts', $receipts);
		Session::put('xianyou_same_focusdevotee', $xianyou_same_focusdevotee);
		Session::put('xianyou_same_family', $xianyou_same_family);
		Session::put('xianyou_different_family', $xianyou_different_family);

		$generaldonation_id = $general_donation->generaldonation_id;
		$hjgr = $general_donation->hjgr;

		$result = Receipt::leftjoin('generaldonation', 'receipt.generaldonation_id', '=', 'generaldonation.generaldonation_id')
							 ->leftjoin('devotee', 'receipt.devotee_id', '=', 'devotee.devotee_id')
							 ->leftjoin('user', 'receipt.staff_id', '=', 'user.id')
							 ->leftjoin('festiveevent', 'generaldonation.festiveevent_id', '=', 'festiveevent.festiveevent_id')
							 ->where('generaldonation.generaldonation_id', '=', $generaldonation_id)
							 ->select('receipt.*', 'devotee.chinese_name', 'devotee.oversea_addr_in_chinese', 'devotee.address_houseno', 'devotee.address_unit1',
							 	'devotee.address_unit2', 'devotee.address_street', 'devotee.address_postal', 'devotee.familycode_id', 'devotee.deceased_year',
							 	'generaldonation.focusdevotee_id', 'generaldonation.trans_no', 'user.first_name', 'user.last_name',
							 	'festiveevent.start_at', 'festiveevent.time', 'festiveevent.event', 'festiveevent.lunar_date', 'generaldonation.mode_payment')
							 ->get();

		for($i = 0; $i < count($result); $i++)
		{
			$result[$i]->trans_date = \Carbon\Carbon::parse($result[$i]->trans_date)->format("d/m/Y");

			if(isset($result[$i]->start_at))
			{
				$result[$i]->start_at = \Carbon\Carbon::parse($result[$i]->start_at)->format("d/m/Y");
			}
		}

		$familycode_id = $result[0]->familycode_id;
		$samefamily_no = 0;

		for($i = 0; $i < count($result); $i++)
		{
			if($result[$i]->familycode_id == $familycode_id)
			{
				$samefamily_no += 1;
				$total_amount += intval($result[$i]->amount);
			}

			$familycode_id = $result[$i]->familycode_id;
		}

		$paid_by = Devotee::where('devotee.devotee_id', $result[0]->focusdevotee_id)
							 ->select('chinese_name', 'devotee_id')
							 ->get();

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

		for($i = 0; $i < count($result); $i++)
		{
		  $first_familycode = $result[0]->familycode_id;

		  if($first_familycode == $result[$i]->familycode_id)
		  {
		    $count_familycode++;
		  }
		}

		return view('staff.print', [
			'receipts' => $result,
			'print_format' => $hjgr,
			'loop' => $loop,
			'count_familycode' => $count_familycode,
			'samefamily_no' => $samefamily_no,
			'total_amount' => number_format($total_amount, 2),
			'paid_by' => $paid_by
		]);
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

		Session::forget('xianyou_same_family');
		Session::forget('xianyou_same_focusdevotee');
		Session::forget('setting_samefamily');
		Session::forget('nosetting_samefamily');
		Session::forget('xianyou_focusdevotee');
		Session::forget('yuejuan_same_family');
		Session::forget('yuejuan_same_focusdevotee');

		$devotee = Devotee::find($input['focusdevotee_id']);

		$xianyou_same_family = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
													 ->leftjoin('setting_generaldonation', 'devotee.devotee_id', '=', 'setting_generaldonation.devotee_id')
													 ->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
											 		 ->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
													 ->where('devotee.familycode_id', $devotee->familycode_id)
													 ->where('devotee.devotee_id', '!=', $input['focusdevotee_id'])
													 ->where('setting_generaldonation.focusdevotee_id', '=', $input['focusdevotee_id'])
													 ->where('setting_generaldonation.address_code', '=', 'same')
													 ->where('setting_generaldonation.xiangyou_ciji_id', '=', '1')
													 ->select('devotee.*', 'familycode.familycode', 'member.paytill_date', 'specialremarks.devotee_id as specialremarks_devotee_id')
													 ->GroupBy('devotee.devotee_id')
													 ->get();

		for($i = 0; $i < count($xianyou_same_family); $i++)
		{
			$hasreceipt = Receipt::where('devotee_id', $xianyou_same_family[$i]->devotee_id)
										->where('description', 'General Donation - 香油')
										->get();

			if(count($hasreceipt) > 0)
			{
				$same_xy_receipt = Receipt::all()
													 ->where('devotee_id', $xianyou_same_family[$i]->devotee_id)
													 ->where('description', 'General Donation - 香油')
													 ->last()
													 ->xy_receipt;

				$xianyou_same_family[$i]->xyreceipt = $same_xy_receipt;
			}

			else {
				$xianyou_same_family[$i]->xyreceipt = "";
			}
		}

		$xianyou_same_focusdevotee = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
											 			     ->leftjoin('setting_generaldonation', 'devotee.devotee_id', '=', 'setting_generaldonation.devotee_id')
											 			     ->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
											 			     ->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
											 			     ->where('setting_generaldonation.address_code', '=', 'same')
											 			     ->where('setting_generaldonation.xiangyou_ciji_id', '=', '1')
											 					 ->where('setting_generaldonation.focusdevotee_id', '=', $input['focusdevotee_id'])
											 					 ->where('setting_generaldonation.devotee_id', '=', $input['focusdevotee_id'])
											 			     ->select('devotee.*', 'familycode.familycode', 'member.paytill_date', 'specialremarks.devotee_id as specialremarks_devotee_id')
																 ->GroupBy('devotee.devotee_id')
											 			     ->get();

		$hasreceipt = Receipt::where('devotee_id', $xianyou_same_focusdevotee[0]->devotee_id)
									->where('description', 'General Donation - 香油')
									->get();

		if(count($hasreceipt) > 0)
		{
			$same_xy_receipt = Receipt::all()
												 ->where('devotee_id', $xianyou_same_focusdevotee[0]->devotee_id)
												 ->where('description', 'General Donation - 香油')
												 ->last()
												 ->xy_receipt;

			$xianyou_same_focusdevotee[0]->xyreceipt = $same_xy_receipt;
		}

		else {
			$xianyou_same_focusdevotee[0]->xyreceipt = "";
		}

		$ciji_same_family = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
													 ->leftjoin('setting_generaldonation', 'devotee.devotee_id', '=', 'setting_generaldonation.devotee_id')
													 ->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
											 		 ->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
													 ->where('devotee.familycode_id', $devotee->familycode_id)
													 ->where('devotee.devotee_id', '!=', $input['focusdevotee_id'])
													 ->where('setting_generaldonation.focusdevotee_id', '=', $input['focusdevotee_id'])
													 ->where('setting_generaldonation.address_code', '=', 'same')
													 ->where('setting_generaldonation.xiangyou_ciji_id', '=', '1')
													 ->select('devotee.*', 'familycode.familycode', 'member.paytill_date', 'specialremarks.devotee_id as specialremarks_devotee_id')
													 ->GroupBy('devotee.devotee_id')
													 ->get();

		for($i = 0; $i < count($ciji_same_family); $i++)
		{
			$hasreceipt = Receipt::where('devotee_id', $ciji_same_family[$i]->devotee_id)
										->where('description', 'General Donation - 慈济')
										->get();

			if(count($hasreceipt) > 0)
			{
				$same_xy_receipt = Receipt::all()
													 ->where('devotee_id', $ciji_same_family[$i]->devotee_id)
													 ->where('description', 'General Donation - 慈济')
													 ->last()
													 ->xy_receipt;

				$ciji_same_family[$i]->xyreceipt = $same_xy_receipt;
			}

			else {
				$ciji_same_family[$i]->xyreceipt = "";
			}
		}

		$ciji_same_focusdevotee = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
											 			     ->leftjoin('setting_generaldonation', 'devotee.devotee_id', '=', 'setting_generaldonation.devotee_id')
											 			     ->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
											 			     ->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
											 			     ->where('setting_generaldonation.address_code', '=', 'same')
											 			     ->where('setting_generaldonation.xiangyou_ciji_id', '=', '1')
											 					 ->where('setting_generaldonation.focusdevotee_id', '=', $input['focusdevotee_id'])
											 					 ->where('setting_generaldonation.devotee_id', '=', $input['focusdevotee_id'])
											 			     ->select('devotee.*', 'familycode.familycode', 'member.paytill_date', 'specialremarks.devotee_id as specialremarks_devotee_id')
																 ->GroupBy('devotee.devotee_id')
											 			     ->get();

		$hasreceipt = Receipt::where('devotee_id', $ciji_same_focusdevotee[0]->devotee_id)
									->where('description', 'General Donation - 慈济')
									->get();

		if(count($hasreceipt) > 0)
		{
			$same_xy_receipt = Receipt::all()
												 ->where('devotee_id', $ciji_same_focusdevotee[0]->devotee_id)
												 ->where('description', 'General Donation - 慈济')
												 ->last()
												 ->xy_receipt;

			$ciji_same_focusdevotee[0]->xyreceipt = $same_xy_receipt;
		}

		else {
			$ciji_same_focusdevotee[0]->xyreceipt = "";
		}

		$setting_samefamily = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
											 		->leftjoin('setting_generaldonation', 'setting_generaldonation.devotee_id', '=', 'devotee.devotee_id')
													->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
													->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
											 		->where('devotee.devotee_id', '!=', $input['focusdevotee_id'])
											 		->where('devotee.familycode_id', $devotee->familycode_id)
											 		->where('setting_generaldonation.focusdevotee_id', '=', $input['focusdevotee_id'])
											 		->where('setting_generaldonation.address_code', '=', 'same')
											 		->select('devotee.*', 'member.paytill_date', 'specialremarks.devotee_id as specialremarks_devotee_id', 'familycode.familycode', 'setting_generaldonation.xiangyou_ciji_id', 'setting_generaldonation.yuejuan_id')
													->GroupBy('devotee.devotee_id')
											 		->get();

		$xianyou_focusdevotee = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
													  ->leftjoin('setting_generaldonation', 'devotee.devotee_id', '=', 'setting_generaldonation.devotee_id')
													  ->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
													  ->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
													  ->where('devotee.familycode_id', $devotee->familycode_id)
													  ->where('devotee.devotee_id', $input['focusdevotee_id'])
													  ->where('setting_generaldonation.focusdevotee_id', $input['focusdevotee_id'])
													  ->select('devotee.*', 'familycode.familycode', 'member.paytill_date', 'specialremarks.devotee_id as specialremarks_devotee_id', 'setting_generaldonation.xiangyou_ciji_id', 'setting_generaldonation.yuejuan_id')
													  ->get();

		$yuejuan_same_family = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
											 		 ->leftjoin('setting_generaldonation', 'devotee.devotee_id', '=', 'setting_generaldonation.devotee_id')
											 		 ->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
											 		 ->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
											 		 ->where('devotee.familycode_id', $devotee->familycode_id)
													 ->where('devotee.devotee_id', '!=', $input['focusdevotee_id'])
													 ->where('setting_generaldonation.focusdevotee_id', '=', $input['focusdevotee_id'])
											 		 ->where('setting_generaldonation.address_code', '=', 'same')
											 		 ->where('setting_generaldonation.yuejuan_id', '=', '1')
											 		 ->select('devotee.*', 'familycode.familycode', 'member.paytill_date', 'specialremarks.devotee_id as specialremarks_devotee_id')
													 ->GroupBy('devotee.devotee_id')
											 		 ->get();

		for($i = 0; $i < count($yuejuan_same_family); $i++)
		{
			$hasreceipt = Receipt::where('devotee_id', $yuejuan_same_family[$i]->devotee_id)
										->where('description', 'General Donation - 月捐')
										->get();

			if(count($hasreceipt) > 0)
			{
				$same_xy_receipt = Receipt::all()
													 ->where('devotee_id', $yuejuan_same_family[$i]->devotee_id)
													 ->where('description', 'General Donation - 月捐')
													 ->last()
													 ->xy_receipt;

				$yuejuan_same_family[$i]->xyreceipt = $same_xy_receipt;
			}

			else {
				$yuejuan_same_family[$i]->xyreceipt = "";
			}
		}

		$yuejuan_same_focusdevotee = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
							 										->leftjoin('setting_generaldonation', 'devotee.devotee_id', '=', 'setting_generaldonation.devotee_id')
							 										->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
							 										->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
							 										->where('setting_generaldonation.address_code', '=', 'same')
							 										->where('setting_generaldonation.yuejuan_id', '=', '1')
							 										->where('setting_generaldonation.focusdevotee_id', '=', $input['focusdevotee_id'])
							 										->where('setting_generaldonation.devotee_id', '=', $input['focusdevotee_id'])
							 										->select('devotee.*', 'familycode.familycode', 'member.paytill_date', 'specialremarks.devotee_id as specialremarks_devotee_id')
							 										->GroupBy('devotee.devotee_id')
							 										->get();

		$hasreceipt = Receipt::where('devotee_id', $yuejuan_same_focusdevotee[0]->devotee_id)
									->where('description', 'General Donation - 月捐')
									->get();

		if(count($hasreceipt) > 0)
		{
			$same_xy_receipt = Receipt::all()
												 ->where('devotee_id', $yuejuan_same_focusdevotee[0]->devotee_id)
												 ->where('description', 'General Donation - 月捐')
												 ->last()
												 ->xy_receipt;

			$yuejuan_same_focusdevotee[0]->xyreceipt = $same_xy_receipt;
		}

		else {
			$yuejuan_same_focusdevotee[0]->xyreceipt = "";
		}

		$membership = MembershipFee::all()->first();

		$focusdevotee_amount = [];

		if(count($yuejuan_same_focusdevotee) > 0)
		{
			if(isset($yuejuan_same_focusdevotee[0]->paytill_date))
			{
				$amount = [];

				$myArray = explode('-', $yuejuan_same_focusdevotee[0]->paytill_date);

				$count = 1;
				for($j = 1; $j <= 10; $j++)
				{
					$dt = Carbon::create($myArray[0], $myArray[1], $myArray[2], 0);
					$dt = $dt->addYears($count);

					$format = Carbon::parse($dt)->format("Y-m");

					$fee = $membership->membership_fee * $j;
					$amount[$j] = number_format($fee, 2) . ' --- ' . $format;

					$count++;
				}

				array_push($focusdevotee_amount, $amount);
			}
		}

		$samefamily_amount = [];

		for($i = 0; $i < count($yuejuan_same_family); $i++)
		{
			$amount = [];

			if(isset($yuejuan_same_family[$i]->paytill_date))
			{
				$myArray = explode('-', $yuejuan_same_family[$i]->paytill_date);

				$count = 1;
				for($j = 1; $j <= 10; $j++)
				{
					$dt = Carbon::create($myArray[0], $myArray[1], $myArray[2], 0);
					$dt = $dt->addYears($count);

					$format = Carbon::parse($dt)->format("Y-m");

					$fee = $membership->membership_fee * $j;
					$amount[$j] = number_format($fee, 2) . ' --- ' . $format;

					$count++;
				}

			}

			array_push($samefamily_amount, $amount);
		}

		Session::put('xianyou_same_family', $xianyou_same_family);
		Session::put('xianyou_same_focusdevotee', $xianyou_same_focusdevotee);
		Session::put('ciji_same_family', $ciji_same_family);
		Session::put('ciji_same_focusdevotee', $ciji_same_focusdevotee);
		Session::put('yuejuan_same_family', $yuejuan_same_family);
		Session::put('yuejuan_same_focusdevotee', $yuejuan_same_focusdevotee);
		Session::put('setting_samefamily', $setting_samefamily);
		Session::put('xianyou_focusdevotee', $xianyou_focusdevotee);
		Session::put('focusdevotee_amount', $focusdevotee_amount);
		Session::put('samefamily_amount', $samefamily_amount);

		$request->session()->flash('success', 'Setting for same address is successfully created.');
		return redirect()->back();
	}

	public function postDifferentFamilySetting(Request $request)
	{
		$input = array_except($request->all(), '_token');

		SettingGeneralDonation::where('focusdevotee_id', $input['focusdevotee_id'])
												 ->where('address_code', 'different')
												 ->delete();

		if(isset($input['devotee_id']))
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

		if(Session::has('yuejuan_different_family'))
		{
			Session::forget('yuejuan_different_family');
		}

		$devotee = Devotee::find($input['focusdevotee_id']);

		$xianyou_different_family = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
																->leftjoin('setting_generaldonation', 'devotee.devotee_id', '=', 'setting_generaldonation.devotee_id')
																->where('setting_generaldonation.address_code', '=', 'different')
																->where('setting_generaldonation.xiangyou_ciji_id', '=', '1')
																->where('setting_generaldonation.focusdevotee_id', '=', $input['focusdevotee_id'])
																->select('devotee.*', 'familycode.familycode')
																->get();

		for($i = 0; $i < count($xianyou_different_family); $i++)
		{
			$hasreceipt = Receipt::where('devotee_id', $xianyou_different_family[$i]->devotee_id)
										->where('description', 'General Donation - 香油')
										->get();

			if(count($hasreceipt) > 0)
			{
				$same_xy_receipt = Receipt::all()
													 ->where('devotee_id', $xianyou_different_family[$i]->devotee_id)
													 ->where('description', 'General Donation - 香油')
													 ->last()
													 ->xy_receipt;

				$xianyou_different_family[$i]->xyreceipt = $same_xy_receipt;
			}

			else {
				$xianyou_different_family[$i]->xyreceipt = "";
			}
		}

		$ciji_different_family = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
														 ->leftjoin('setting_generaldonation', 'devotee.devotee_id', '=', 'setting_generaldonation.devotee_id')
														 ->where('setting_generaldonation.address_code', '=', 'different')
														 ->where('setting_generaldonation.xiangyou_ciji_id', '=', '1')
														 ->where('setting_generaldonation.focusdevotee_id', '=', $input['focusdevotee_id'])
														 ->select('devotee.*', 'familycode.familycode')
														 ->get();

		for($i = 0; $i < count($ciji_different_family); $i++)
		{
			$hasreceipt = Receipt::where('devotee_id', $ciji_different_family[$i]->devotee_id)
										->where('description', 'General Donation - 慈济')
										->get();

			if(count($hasreceipt) > 0)
			{
				$same_xy_receipt = Receipt::all()
													 ->where('devotee_id', $ciji_different_family[$i]->devotee_id)
													 ->where('description', 'General Donation - 慈济')
													 ->last()
													 ->xy_receipt;

				$ciji_different_family[$i]->xyreceipt = $same_xy_receipt;
			}

			else {
				$ciji_different_family[$i]->xyreceipt = "";
			}
		}

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

		$yuejuan_different_family = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
													 			->leftjoin('setting_generaldonation', 'devotee.devotee_id', '=', 'setting_generaldonation.devotee_id')
																->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
																->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
													 			->where('setting_generaldonation.address_code', '=', 'different')
													 			->where('setting_generaldonation.yuejuan_id', '=', '1')
													 			->where('setting_generaldonation.focusdevotee_id', '=', $input['focusdevotee_id'])
													 			->select('devotee.*', 'member.paytill_date', 'specialremarks.devotee_id as specialremarks_devotee_id', 'familycode.familycode')
																->GroupBy('devotee.devotee_id')
													 			->get();

		for($i = 0; $i < count($yuejuan_different_family); $i++)
		{
			$hasreceipt = Receipt::where('devotee_id', $yuejuan_different_family[$i]->devotee_id)
										->where('description', 'General Donation - 月捐')
										->get();

			if(count($hasreceipt) > 0)
			{
				$same_xy_receipt = Receipt::all()
													 ->where('devotee_id', $yuejuan_different_family[$i]->devotee_id)
													 ->where('description', 'General Donation - 月捐')
													 ->last()
													 ->xy_receipt;

				$yuejuan_different_family[$i]->xyreceipt = $same_xy_receipt;
			}

			else {
				$yuejuan_different_family[$i]->xyreceipt = "";
			}
		}

		$amount = [];
		$differentfamily_amount = [];

		for($i = 0; $i < count($yuejuan_different_family); $i++)
		{
			$amount = [];

			if(isset($yuejuan_different_family[$i]->paytill_date))
			{
				$myArray = explode('-', $yuejuan_different_family[$i]->paytill_date);

				$count = 1;
				for($j = 1; $j <= 10; $j++)
				{
					$dt = Carbon::create($myArray[0], $myArray[1], $myArray[2], 0);
					$dt = $dt->addYears($count);

					$format = Carbon::parse($dt)->format("Y-m");

					$fee = 24 * $j;
					$amount[$j] = number_format($fee, 2) . ' --- ' . $format;

					$count++;
				}
			}

			array_push($differentfamily_amount, $amount);
		}

		Session::put('xianyou_different_family', $xianyou_different_family);
		Session::put('ciji_different_family', $ciji_different_family);
		Session::put('yuejuan_different_family', $yuejuan_different_family);
		Session::put('differentfamily_amount', $differentfamily_amount);
		Session::put('setting_differentfamily', $setting_differentfamily);

		$request->session()->flash('success', 'Setting for different address is successfully created.');
		return redirect()->back();
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
				$generaldonation = GeneralDonation::where('trans_no', $input['transaction_no'])->first();

				// Update Cancellation Status
				$receipts = Receipt::where('generaldonation_id', $generaldonation->generaldonation_id)
									  ->update([
							        'cancelled_date' => Carbon::now(),
							        'status' => "cancelled",
							        'cancelled_by' => Auth::user()->id
							     ]);

				$cancellation_receipts = Receipt::leftjoin('user', 'user.id', '=', 'receipt.cancelled_by')
																 ->where('receipt.generaldonation_id', '=', $generaldonation->generaldonation_id)
																 ->select('receipt.cancelled_date', 'user.first_name', 'user.last_name')
																 ->GroupBy('receipt.generaldonation_id')
																 ->get();

				$cancelled_date = \Carbon\Carbon::parse($cancellation_receipts[0]->cancelled_date)->format("d/m/Y");

				$focus_devotee = Session::get('focus_devotee');

				if(count($focus_devotee) > 0)
				{
					$receipts = GeneralDonation::leftjoin('devotee', 'devotee.devotee_id', '=', 'generaldonation.focusdevotee_id')
											->leftjoin('receipt', 'receipt.generaldonation_id', '=', 'generaldonation.generaldonation_id')
											->where('generaldonation.focusdevotee_id', '=', $focus_devotee[0]->devotee_id)
											->whereIn('receipt.glcode_id', array(119,112))
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
							$receipts[$i]->receipt_no = $data[0] . ' - ' . $data[$receipt_count - 1];
						}
					}

					$ciji_receipts = GeneralDonation::leftjoin('devotee', 'devotee.devotee_id', '=', 'generaldonation.focusdevotee_id')
													 ->leftjoin('receipt', 'receipt.generaldonation_id', '=', 'generaldonation.generaldonation_id')
													 ->where('generaldonation.focusdevotee_id', $focus_devotee[0]->devotee_id)
													 ->where('receipt.glcode_id', 134)
													 ->GroupBy('generaldonation.generaldonation_id')
													 ->select('generaldonation.*', 'devotee.chinese_name', 'receipt.cancelled_date')
													 ->orderBy('generaldonation.generaldonation_id', 'desc')
													 ->get();

					if(count($ciji_receipts) > 0)
					{
						for($i = 0; $i < count($ciji_receipts); $i++)
						{
							$data = Receipt::where('generaldonation_id', $ciji_receipts[$i]->generaldonation_id)->pluck('xy_receipt');
							$receipt_count = count($data);
							$ciji_receipts[$i]->receipt_no = $data[0] . ' - ' . $data[$receipt_count - 1];
						}
					}

					Session::put('receipts', $receipts);
					Session::put('ciji_receipts', $ciji_receipts);
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

	// Xiangyou Cancel & Replace Transaction
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
					$receipt = Receipt::where('xy_receipt', $input['receipt_no'])->get();
					$result = Receipt::find($receipt[0]['receipt_id']);

					$generaldonation = GeneralDonation::where('generaldonation_id', $receipt[0]['generaldonation_id'])
														 ->get();

					$focusdevotee_id = $generaldonation[0]->focusdevotee_id;

					$result->cancelled_date = Carbon::now();
					$result->status = "cancelled";
					$result->cancelled_by = Auth::user()->id;

					$cancellation = $result->save();
				}

				if(!empty($input['trans_no']))
				{

					$generaldonation = GeneralDonation::where('trans_no', $input['trans_no'])
														 ->get();

					$focusdevotee_id = $generaldonation[0]->focusdevotee_id;

					$receipt = Receipt::where('generaldonation_id', $generaldonation[0]->generaldonation_id)->get();

					for($i = 0; $i < count($receipt); $i++)
					{
						$result = Receipt::find($receipt[$i]['receipt_id']);

						$result->cancelled_date = Carbon::now();
						$result->status = "cancelled";
						$result->cancelled_by = Auth::user()->id;

						$cancellation = $result->save();
					}
				}

				$focus_devotee = Session::get('focus_devotee');

				$receipts = GeneralDonation::leftjoin('devotee', 'devotee.devotee_id', '=', 'generaldonation.focusdevotee_id')
				            ->leftjoin('receipt', 'receipt.generaldonation_id', '=', 'generaldonation.generaldonation_id')
				            ->where('generaldonation.focusdevotee_id', '=', $focus_devotee[0]->devotee_id)
				            ->whereIn('receipt.glcode_id', array(119,112))
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
				    $receipts[$i]->receipt_no = $data[0] . ' - ' . $data[$receipt_count - 1];
				  }
				}

				$ciji_receipts = GeneralDonation::leftjoin('devotee', 'devotee.devotee_id', '=', 'generaldonation.focusdevotee_id')
				                 ->leftjoin('receipt', 'receipt.generaldonation_id', '=', 'generaldonation.generaldonation_id')
				                 ->where('generaldonation.focusdevotee_id', $focus_devotee[0]->devotee_id)
				                 ->where('receipt.glcode_id', 134)
				                 ->GroupBy('generaldonation.generaldonation_id')
				                 ->select('generaldonation.*', 'devotee.chinese_name', 'receipt.cancelled_date')
				                 ->orderBy('generaldonation.generaldonation_id', 'desc')
				                 ->get();



				if(count($ciji_receipts) > 0)
				{
				  for($i = 0; $i < count($ciji_receipts); $i++)
				  {
				    $data = Receipt::where('generaldonation_id', $ciji_receipts[$i]->generaldonation_id)->pluck('xy_receipt');
				    $receipt_count = count($data);
				    $ciji_receipts[$i]->receipt_no = $data[0] . ' - ' . $data[$receipt_count - 1];
				  }
				}

				Session::put('receipts', $receipts);
				Session::put('ciji_receipts', $ciji_receipts);

				return response()->json(array(
				  'receipt' => $receipt
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

	// // Get Detail for Receipt ID
	// public function getGeneralDonation($generaldonation_id)
	// {
	// 	// dd($generaldonation_id);
	// 	// remove session
	// 	if(Session::has('cancelled_date'))
	// 	{
	// 		Session::forget('cancelled_date');
	// 	}
	//
	// 	// remove session
	// 	if(Session::has('first_name'))
	// 	{
	// 		Session::forget('first_name');
	// 	}
	//
	// 	// remove session
	// 	if(Session::has('last_name'))
	// 	{
	// 		Session::forget('last_name');
	// 	}
	//
	// 	$receipt = Receipt::leftjoin('generaldonation', 'generaldonation.generaldonation_id', '=', 'receipt.generaldonation_id')
	// 						 ->leftjoin('devotee', 'devotee.devotee_id', '=', 'generaldonation.focusdevotee_id')
	// 						 ->leftjoin('user', 'receipt.staff_id', '=', 'user.id')
	// 						 ->select('receipt.*', 'devotee.chinese_name', 'devotee.devotee_id', 'generaldonation.trans_no', 'user.first_name', 'user.last_name')
	// 						 ->where('receipt.receipt_id', $generaldonation_id)
	// 						 ->get();
	//
	//   // get general donation devotee by generaldonation id
	//   $donation_devotees = GeneralDonationItems::join('devotee', 'devotee.devotee_id', '=', 'generaldonation_items.devotee_id')
	// 										 	 ->select('generaldonation_items.*')
	// 										 	 ->addSelect('devotee.chinese_name', 'devotee.address_houseno', 'devotee.address_street', 'devotee.address_unit1',
	// 												'devotee.address_unit2', 'devotee.address_postal', 'devotee.oversea_addr_in_chinese')
	// 										 	 ->where('receipt_id', $generaldonation_id)
	// 										 	 ->get();
	//
	//   $generaldonation = GeneralDonation::find($receipt[0]->generaldonation_id);
	// 	$festiveevent = FestiveEvent::find($generaldonation->festiveevent_id);
	//
	// 	// if($receipt[0]->status == "cancelled")
	// 	// {
	// 	// 	$cancelled_date = \Carbon\Carbon::parse($receipt[0]->cancelled_date)->format("d/m/Y");
	// 	// 	Session::put('cancelled_date', $cancelled_date);
	// 	// 	Session::put('first_name', $receipt[0]->first_name);
	// 	// 	Session::put('last_name', $receipt[0]->last_name);
	// 	// }
	//
	// 	return view('staff.receiptdetail', [
	// 		'festiveevent' => $festiveevent,
	// 		'receipt' => $receipt,
  //     'donation_devotees' => $donation_devotees,
  //     'generaldonation' => $generaldonation
	// 	]);
	// }

	public function getReceiptDetail($generaldonation_id)
	{
		dd($generaldonation_id);
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
							 ->select('devotee.*', 'member.member', 'member.paytill_date', 'familycode.familycode')
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
							 ->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
							 ->select('devotee.*', 'country.country_name', 'member.member')
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

	public function getTransactionDetail(Request $request)
	{
		$input = array_except($request->all(), '_token');

		if(isset($input['trans_no']))
		{
			$trans = GeneralDonation::where('trans_no', $input['trans_no'])->first();

			if(count($trans) > 0)
			{
				if($trans->description == "General Donation - 月捐")
				{
					$generaldonation = new GeneralDonation;
					$result = $generaldonation->yuejuansearchTransaction($input)->get();

					for($i = 0; $i < count($result); $i++)
					{
					  if(isset($result[$i]->paytill_date))
					  {
					    $result[$i]->paid_for = number_format($result[$i]->amount, 2) . ' - ' . Carbon::parse($result[$i]->paytill_date)->format("Y-m");
					  }
					}
				}

				else
				{
					$generaldonation = new GeneralDonation;
					$result = $generaldonation->searchTransaction($input)->get();
				}
			}

			else
			{
				return response()->json(array(
					 'msg' => 'No Result Found'
				));
			}

			$cancellation = Receipt::leftjoin('generaldonation', 'receipt.generaldonation_id', '=', 'generaldonation.generaldonation_id')
											->leftjoin('user', 'receipt.cancelled_by', '=', 'user.id')
											->where('generaldonation.trans_no', $input['trans_no'])
											->select('receipt.cancelled_date', 'user.first_name', 'user.last_name')
											->GroupBy('generaldonation.generaldonation_id')
											->get();

			if(isset($cancellation[0]->cancelled_date))
			{
				$cancellation[0]->cancelled_date = Carbon::parse($cancellation[0]->cancelled_date)->format("d/m/Y");
			}
		}

		else
		{
			$receipt = Receipt::where('xy_receipt', $input['receipt_no'])->first();

			if(count($receipt) > 0)
			{
				if($receipt->description == "General Donation - 月捐")
				{
					$generaldonation = new GeneralDonation;
					$result = $generaldonation->yuejuansearchTransaction($input)->get();

					for($i = 0; $i < count($result); $i++)
					{
					  if(isset($result[$i]->paytill_date))
					  {
					    $result[$i]->paid_for = number_format($result[$i]->amount, 2) . ' - ' . Carbon::parse($result[$i]->paytill_date)->format("Y-m");
					  }
					}
				}

				else
				{
					$generaldonation = new GeneralDonation;
					$result = $generaldonation->searchTransaction($input)->get();
				}
			}

			else
			{
				return response()->json(array(
			     'msg' => 'No Result Found'
			  ));
			}

			$cancellation = Receipt::leftjoin('generaldonation', 'receipt.generaldonation_id', '=', 'generaldonation.generaldonation_id')
											->leftjoin('user', 'receipt.cancelled_by', '=', 'user.id')
											->where('receipt.xy_receipt', $input['receipt_no'])
											->select('receipt.cancelled_date', 'user.first_name', 'user.last_name')
											->GroupBy('generaldonation.generaldonation_id')
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

	public function getYueJuanTransactionDetail(Request $request)
	{
		$input = array_except($request->all(), '_token');

		$generaldonation = new GeneralDonation;
		$result = $generaldonation->yuejuansearchTransaction($input)->get();

		for($i = 0; $i < count($result); $i++)
		{
		  if(isset($result[$i]->paytill_date))
		  {
		    $result[$i]->paid_for = number_format($result[$i]->amount, 2) . ' - ' . Carbon::parse($result[$i]->paytill_date)->format("Y-m");
		  }
		}

		$cancellation = Receipt::leftjoin('generaldonation', 'receipt.generaldonation_id', '=', 'generaldonation.generaldonation_id')
										->leftjoin('user', 'receipt.cancelled_by', '=', 'user.id')
										->where('generaldonation.trans_no', $input['trans_no'])
										->select('receipt.cancelled_date', 'user.first_name', 'user.last_name')
										->GroupBy('generaldonation.generaldonation_id')
										->get();

		if(isset($cancellation[0]->cancelled_date))
		{
			$cancellation[0]->cancelled_date = Carbon::parse($cancellation[0]->cancelled_date)->format("d/m/Y");
		}

		// Check Transaction devotee and focus devotee is the same
		$focusdevotee = Session::get('focus_devotee');

		if(count($result) == 0)
		{
			return response()->json(array(
	      'msg' => 'No Result Found'
	    ));
		}
		//
		// if($focusdevotee[0]->devotee_id != $result[0]->focusdevotee_id)
		// {
		// 	return response()->json(array(
	  //     'msg' => 'Search same devotee Id'
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

	public function getPrint()
	{
		return view('staff.print');
	}

	public function ReprintDetail(Request $request)
	{
		$input = array_except($request->all(), '_token');
		$total_amount = 0;

		if(isset($input['receipt_no']))
		{
			$receipts = Receipt::leftjoin('generaldonation', 'receipt.generaldonation_id', '=', 'generaldonation.generaldonation_id')
								 ->leftjoin('devotee', 'receipt.devotee_id', '=', 'devotee.devotee_id')
								 ->leftjoin('user', 'receipt.staff_id', '=', 'user.id')
								 ->leftjoin('festiveevent', 'generaldonation.festiveevent_id', '=', 'festiveevent.festiveevent_id')
								 ->where('receipt.xy_receipt', '=', $input['receipt_no'])
								 ->select('receipt.*', 'devotee.chinese_name', 'devotee.oversea_addr_in_chinese', 'devotee.address_houseno', 'devotee.address_unit1',
								 	'devotee.address_unit2', 'devotee.address_street', 'devotee.address_postal', 'devotee.familycode_id',
								 	'generaldonation.focusdevotee_id', 'generaldonation.trans_no', 'user.first_name', 'user.last_name',
								 	'festiveevent.start_at', 'festiveevent.time', 'festiveevent.event', 'festiveevent.lunar_date', 'generaldonation.mode_payment')
								 ->get();

			if(isset($receipts[0]->trans_date))
			{
				$receipts[0]->trans_date = \Carbon\Carbon::parse($receipts[0]->trans_date)->format("d/m/Y");
			}

			if(isset($receipts[0]->start_at))
			{
				$receipts[0]->start_at = \Carbon\Carbon::parse($receipts[0]->start_at)->format("d/m/Y");
			}

			$samefamily_no = 0;
			$print_format = 'hj';

			$paid_by = Devotee::where('devotee.devotee_id', $receipts[0]->focusdevotee_id)
								 ->select('chinese_name', 'devotee_id')
								 ->get();
		}

		if(isset($input['trans_no']))
		{
			$receipts = Receipt::leftjoin('generaldonation', 'receipt.generaldonation_id', '=', 'generaldonation.generaldonation_id')
								 ->leftjoin('devotee', 'receipt.devotee_id', '=', 'devotee.devotee_id')
								 ->leftjoin('user', 'receipt.staff_id', '=', 'user.id')
								 ->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
								 ->leftjoin('festiveevent', 'generaldonation.festiveevent_id', '=', 'festiveevent.festiveevent_id')
								 ->where('generaldonation.trans_no', '=', $input['trans_no'])
								 ->select('receipt.*', 'member.paytill_date', 'member.member_id', 'devotee.chinese_name', 'devotee.oversea_addr_in_chinese', 'devotee.address_houseno', 'devotee.address_unit1',
								 	'devotee.address_unit2', 'devotee.address_street', 'devotee.address_postal', 'devotee.familycode_id',
								 	'generaldonation.focusdevotee_id', 'generaldonation.trans_no', 'user.first_name', 'user.last_name',
								 	'festiveevent.start_at', 'festiveevent.time', 'festiveevent.event', 'festiveevent.lunar_date', 'generaldonation.mode_payment')
								 ->get();

			if($receipts[0]->glcode_id == 110 || $receipts[0]->glcode_id == 108)
			{
				if(isset($receipts[0]->paytill_date))
				{
					for($i = 0; $i < count($receipts); $i++)
					{
					  if(isset($receipts[$i]->paytill_date))
					  {
					    $receipts[$i]->paid_for = number_format($receipts[$i]->amount, 2) . ' - ' . Carbon::parse($receipts[$i]->paytill_date)->format("Y-m");
					  }
					}
				}
			}

			for($i = 0; $i < count($receipts); $i++)
			{
				$receipts[$i]->trans_date = \Carbon\Carbon::parse($receipts[$i]->trans_date)->format("d/m/Y");

				if(isset($receipts[$i]->start_at))
				{
					$receipts[$i]->start_at = \Carbon\Carbon::parse($receipts[$i]->start_at)->format("d/m/Y");
				}
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

		if(isset($receipts[0]->paid_for))
		{
			return view('staff.yuejuan-print', [
			  'receipts' => $receipts,
			  'print_format' => $print_format,
			  'loop' => $loop,
				'count_familycode' => $count_familycode,
			  'samefamily_no' => $samefamily_no,
			  'total_amount' => number_format($total_amount, 2),
			  'paid_by' => $paid_by
			]);
		}

		else
		{
			return view('staff.print', [
				'receipts' => $receipts,
				'print_format' => $print_format,
				'samefamily_no' => $samefamily_no,
				'loop' => $loop,
				'count_familycode' => $count_familycode,
				'total_amount' => number_format($total_amount, 2),
				'paid_by' => $paid_by
			]);
		}
	}

	public function getCreateFestiveEvent()
	{
		$events = FestiveEvent::orderBy('start_at', 'asc')->get();
		$jobs = Job::orderBy('job_id', 'asc')->get();

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
