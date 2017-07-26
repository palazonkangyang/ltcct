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

		// Delete relative and friend lists by focus devotee before saving
		$lists = RelativeFriendLists::where('donate_devotee_id', $input['focusdevotee_id'])->get();
		dd($lists->toArray());
		$lists->delete();

		// Add Relative and Friend Lists
		for($i = 0; $i < count($input["other_devotee_id"]); $i++)
		{
		  $list = [
		    "donate_devotee_id" => $input['focusdevotee_id'],
		    "relative_friend_devotee_id" =>$input["other_devotee_id"][$i],
		    "year" => date('Y')
		  ];

		  RelativeFriendLists::create($list);
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
			$events = FestiveEvent::orderBy('start_at', 'asc')->get();

			return view('staff.create-festive-event', [
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
