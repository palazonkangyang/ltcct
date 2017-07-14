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
		return view('staff.donation');
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

		$data = [
			"trans_no" => "T1620251",
			"description" => "General Donation",
			"hjgr" => $input['hjgr'],
			"total_amount" => $input['total_amount'],
			"mode_payment" => $input['mode_payment'],
			"cheque_no" => $input['cheque_no'],
			"receipt_at" =>	$receipt_at,
			"manualreceipt" => $input['manualreceipt'],
			"trans_at" => Carbon::now(),
			"focusdevotee_id" => $input['focusdevotee_id'],
		];

		$general_donation = GeneralDonation::create($data);

		if($general_donation)
		{
			if($input["hjgr"] == "hj")
			{
				$data = [
					"amount" => $input["amount"][0],
					"paid_till" => date("Y-m-d", strtotime($input["paid_till"][0])),
					"hjgr" => $input["hjgr_arr"][0],
					"display" => $input["display"][0],
					"xy_receipt" => "XY2641",
					"trans_date" => Carbon::now(),
					"generaldonation_id" => $general_donation->generaldonation_id,
					"devotee_id" => $input["devotee_id"][0]
				];

				GeneralDonationItems::create($data);

				if(isset($input['other_devotee_id']))
				{
					for($i = 0; $i < count($input['other_devotee_id']); $i++)
					{

						$data2 = [
							"amount" => $input["other_amount"][$i],
							"paid_till" => date("Y-m-d", strtotime($input["other_paid_till"][$i])),
							"hjgr" => $input["other_hjgr_arr"][$i],
							"display" => $input["other_display"][$i],
							"xy_receipt" => "XY2641",
							"trans_date" => Carbon::now(),
							"generaldonation_id" => $general_donation->generaldonation_id,
							"devotee_id" => $input["other_devotee_id"][$i]
						];

						GeneralDonationItems::create($data2);
					}
				}
			}

			else
			{
				for($i = 0; $i < count($input['devotee_id']); $i++)
				{
					$data = [
						"amount" => $input["amount"][$i],
						"paid_till" => date("Y-m-d", strtotime($input["paid_till"][$i])),
						"hjgr" => $input["hjgr_arr"][$i],
						"display" => $input["display"][$i],
						"xy_receipt" => "XY2641",
						"trans_date" => Carbon::now(),
						"generaldonation_id" => $general_donation->generaldonation_id,
						"devotee_id" => $input["devotee_id"][$i]
					];

					GeneralDonationItems::create($data);
				}

				if(isset($input['other_devotee_id']))
				{
					for($i = 0; $i < count($input['other_devotee_id']); $i++)
					{
						$data2 = [
							"amount" => $input["other_amount"][$i],
							"paid_till" => date("Y-m-d", strtotime($input["other_paid_till"][$i])),
							"hjgr" => $input["other_hjgr_arr"][$i],
							"display" => $input["other_display"][$i],
							"xy_receipt" => "XY2641",
							"trans_date" => Carbon::now(),
							"generaldonation_id" => $general_donation->generaldonation_id,
							"devotee_id" => $input["other_devotee_id"][$i]
						];

						GeneralDonationItems::create($data2);
					}
				}
			}
		}

		for($i = 0; $i < count($input['devotee_id']); $i++)
		{
			$receipt = [
				"xy_receipt" => "XY2641",
				"trans_date" => Carbon::now(),
				"description" => "General Donation",
				"focusdevotee_id" => $input['focusdevotee_id'],
				"hjgr" => $input["hjgr_arr"][$i],
				"amount" => $input["amount"][$i],
				"generaldonation_id" => $general_donation->generaldonation_id,

			];

			Receipt::create($receipt);
		}

		if(isset($input['other_devotee_id']))
		{
			for($i = 0; $i < count($input['other_devotee_id']); $i++)
			{
				$data2 = [
					"xy_receipt" => "XY2641",
					"trans_date" => Carbon::now(),
					"description" => "General Donation",
					"focusdevotee_id" => $input['focusdevotee_id'],
					"hjgr" => $input["other_hjgr_arr"][$i],
					"amount" => $input["other_amount"][$i],
					"generaldonation_id" => $general_donation->generaldonation_id,
				];

				Receipt::create($data2);
			}
		}

		Session::forget('receipts');

		// Get Receipt History
       	$receipts = Receipt::leftjoin('generaldonation', 'generaldonation.generaldonation_id', '=', 'receipt.generaldonation_id')
       				->leftjoin('devotee', 'devotee.devotee_id', '=', 'receipt.focusdevotee_id')
       				->where('receipt.focusdevotee_id', $input['focusdevotee_id'])
       				->orderBy('receipt_id', 'desc')
       				->select('receipt.*')
       				->addSelect('devotee.chinese_name')
       				->addSelect('generaldonation.manualreceipt')
       				->get();

       	if(!Session::has('receipts'))
        {
        	Session::put('receipts', $receipts);
        }

		$request->session()->flash('success', 'General Donation is successfully created.');

        return redirect()->back();
	}


	public function getSearchDevotee(Request $request)
	{
		$devotee_id = $_GET['devotee_id'];

		$devotee = Devotee::find($devotee_id);

		return response()->json([
			'devotee' => $devotee
		]);
	}
}