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
use App\Models\TranslationStreet;
use App\Models\Receipt;
use App\Models\RelativeFriendLists;
use App\Models\Country;
use App\Models\FestiveEvent;
use App\Models\Dialect;
use App\Models\Race;
use App\Models\GeneralDonation;
use App\Models\SettingGeneralDonation;
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

class OperatorController extends Controller
{
	// Home Page
	public function index()
	{
		$devotees = Devotee::leftjoin('familycode', 'devotee.familycode_id', '=', 'familycode.familycode_id')
								->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
								->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
        				->select('devotee.*', 'member.paytill_date', 'specialremarks.devotee_id as specialremarks_devotee_id', 'familycode.familycode')
								->orderBy('devotee.devotee_id', 'desc')
								->GroupBy('devotee.devotee_id')
        				->get();

		$members = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
								->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
								->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
								->whereNotNull('devotee.member_id')
								->whereNull('deceased_year')
								->orderBy('devotee_id', 'asc')
        				->select('devotee.*', 'member.paytill_date', 'specialremarks.devotee_id as specialremarks_devotee_id', 'familycode.familycode')
								->orderBy('devotee.member_id', 'desc')
								->GroupBy('devotee.devotee_id')
        				->get();

    $deceased_lists = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
											->whereNotNull('deceased_year')
											->orderBy('devotee_id', 'desc')
        							->select('devotee.*')
        							->addSelect('familycode.familycode')
        							->get();

		$countries = Country::orderBy('country_name', 'asc')->get();
		$dialects = Dialect::all();
		$races = Race::all();

		return view('operator.index', [
            'members' => $members,
            'devotees' => $devotees,
            'deceased_lists' => $deceased_lists,
						'countries' => $countries,
						'dialects' => $dialects,
						'races' => $races
        ]);
	}

	public function getDevoteeByID(Request $request, $devotee_id)
	{

		// remove session data
		Session::forget('focus_devotee');
		Session::forget('searchfocus_devotee');
		Session::forget('devotee_lists');
		Session::forget('focusdevotee_specialremarks');
		Session::forget('xianyou_same_family');
		Session::forget('xianyou_different_family');
		Session::forget('setting_samefamily');
		Session::forget('nosetting_samefamily');
		Session::forget('xianyou_focusdevotee');
		Session::forget('setting_differentfamily');
		Session::forget('optionaladdresses');
		Session::forget('optionalvehicles');
		Session::forget('specialRemarks');
		Session::forget('receipts');
		Session::forget('ciji_receipts');
		Session::forget('yuejuan_receipts');
		Session::forget('focusdevotee_amount');
		Session::forget('samefamily_amount');
		Session::forget('differentfamily_amount');

	  $devotee = Devotee::leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
							 ->leftjoin('familycode', 'devotee.familycode_id', '=', 'familycode.familycode_id')
							 ->leftjoin('dialect', 'devotee.dialect', '=', 'dialect.dialect_id')
							 ->select('devotee.*', 'dialect.dialect_name', 'familycode.familycode', 'member.introduced_by1',
							 	'member.introduced_by2', 'member.approved_date', 'member.paytill_date')
							 ->where('devotee.devotee_id', $devotee_id)
							 ->get();


		$devotee_lists = Devotee::join('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
					 		       ->where('devotee.familycode_id', $devotee[0]->familycode_id)
					 		       ->where('devotee_id', '!=', $devotee[0]->devotee_id)
					 		       ->orderBy('devotee_id', 'asc')
					 		       ->select('devotee.*', 'familycode.familycode')
					 		       ->get();

		if(isset($devotee[0]->dob))
		{
			$devotee[0]->dob = Carbon::parse($devotee[0]->dob)->format("d/m/Y");
		}

		if(isset($devotee[0]->approved_date))
		{
			$devotee[0]->approved_date = Carbon::parse($devotee[0]->approved_date)->format("d/m/Y");
		}

		$focusdevotee_specialremarks = Devotee::leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
		          											->where('devotee.devotee_id', $devotee_id)
		          											->get();

		$devotee[0]->specialremarks_id = $focusdevotee_specialremarks[0]->devotee_id;

		$xianyou_same_family = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
                           ->leftjoin('setting_generaldonation', 'devotee.devotee_id', '=', 'setting_generaldonation.devotee_id')
                           ->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
                           ->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
                           ->where('devotee.familycode_id', $devotee[0]->familycode_id)
													 ->where('devotee.devotee_id', '!=', $devotee[0]->devotee_id)
                           ->where('setting_generaldonation.address_code', '=', 'same')
                           ->where('setting_generaldonation.xiangyou_ciji_id', '=', '1')
													 ->where('setting_generaldonation.focusdevotee_id', '=', $devotee[0]->devotee_id)
                           ->select('devotee.*', 'familycode.familycode', 'member.paytill_date', 'specialremarks.devotee_id as specialremarks_devotee_id')
													 ->GroupBy('devotee.devotee_id')
                           ->get();

		$xianyou_same_focusdevotee = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
			                           ->leftjoin('setting_generaldonation', 'devotee.devotee_id', '=', 'setting_generaldonation.devotee_id')
			                           ->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
			                           ->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
			                           ->where('setting_generaldonation.address_code', '=', 'same')
			                           ->where('setting_generaldonation.xiangyou_ciji_id', '=', '1')
																 ->where('setting_generaldonation.focusdevotee_id', '=', $devotee[0]->devotee_id)
																 ->where('setting_generaldonation.devotee_id', '=', $devotee[0]->devotee_id)
			                           ->select('devotee.*', 'familycode.familycode', 'member.paytill_date', 'specialremarks.devotee_id as specialremarks_devotee_id')
																 ->GroupBy('devotee.devotee_id')
			                           ->get();

		$xianyou_different_family = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
											          ->leftjoin('setting_generaldonation', 'devotee.devotee_id', '=', 'setting_generaldonation.devotee_id')
											          ->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
											          ->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
											          ->where('devotee.devotee_id', '!=', $devotee[0]->devotee_id)
											          ->where('setting_generaldonation.address_code', '=', 'different')
											          ->where('setting_generaldonation.xiangyou_ciji_id', '=', '1')
											          ->where('setting_generaldonation.focusdevotee_id', '=', $devotee[0]->devotee_id)
											          ->select('devotee.*', 'member.paytill_date', 'specialremarks.devotee_id as specialremarks_devotee_id', 'familycode.familycode')
											          ->GroupBy('devotee.devotee_id')
											          ->get();

		 $yuejuan_same_family = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
														->leftjoin('setting_generaldonation', 'devotee.devotee_id', '=', 'setting_generaldonation.devotee_id')
														->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
														->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
														->where('devotee.familycode_id', $devotee[0]->familycode_id)
														->where('devotee.devotee_id', '!=', $devotee[0]->devotee_id)
														->where('setting_generaldonation.address_code', '=', 'same')
														->where('setting_generaldonation.yuejuan_id', '=', '1')
														->where('setting_generaldonation.focusdevotee_id', '=', $devotee[0]->devotee_id)
														->select('devotee.*', 'familycode.familycode', 'member.paytill_date', 'specialremarks.devotee_id as specialremarks_devotee_id')
														->GroupBy('devotee.devotee_id')
														->get();

		$yuejuan_same_focusdevotee = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
																 ->leftjoin('setting_generaldonation', 'devotee.devotee_id', '=', 'setting_generaldonation.devotee_id')
																 ->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
																 ->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
																 ->where('setting_generaldonation.address_code', '=', 'same')
																 ->where('setting_generaldonation.yuejuan_id', '=', '1')
																 ->where('setting_generaldonation.focusdevotee_id', '=', $devotee[0]->devotee_id)
																 ->where('setting_generaldonation.devotee_id', '=', $devotee[0]->devotee_id)
																 ->select('devotee.*', 'familycode.familycode', 'member.paytill_date', 'specialremarks.devotee_id as specialremarks_devotee_id')
																 ->GroupBy('devotee.devotee_id')
																 ->get();

		$yuejuan_different_family = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
																->leftjoin('setting_generaldonation', 'devotee.devotee_id', '=', 'setting_generaldonation.devotee_id')
																->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
																->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
																->where('devotee.devotee_id', '!=', $devotee[0]->devotee_id)
																->where('setting_generaldonation.address_code', '=', 'different')
																->where('setting_generaldonation.yuejuan_id', '=', '1')
																->where('setting_generaldonation.focusdevotee_id', '=', $devotee[0]->devotee_id)
																->select('devotee.*', 'member.paytill_date', 'specialremarks.devotee_id as specialremarks_devotee_id', 'familycode.familycode')
																->GroupBy('devotee.devotee_id')
																->get();

		$result = SettingGeneralDonation::where('focusdevotee_id', $devotee[0]->devotee_id)
							->get();

		if(count($result) > 0)
		{
			$setting_samefamily = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
																	->leftjoin('setting_generaldonation', 'setting_generaldonation.devotee_id', '=', 'devotee.devotee_id')
																	->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
																	->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
																	->where('devotee.devotee_id', '!=', $devotee[0]->devotee_id)
																	->where('devotee.familycode_id', $devotee[0]->familycode_id)
																	->where('setting_generaldonation.focusdevotee_id', $devotee[0]->devotee_id)
																	->select('devotee.*', 'member.paytill_date', 'specialremarks.devotee_id as specialremarks_devotee_id', 'familycode.familycode', 'setting_generaldonation.xiangyou_ciji_id', 'setting_generaldonation.yuejuan_id')
																	->GroupBy('devotee.devotee_id')
																	->get();

			$nosetting_samefamily = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
															->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
															->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
															->where('devotee.familycode_id', $devotee[0]->familycode_id)
															->where('devotee.devotee_id', '!=', $devotee[0]->devotee_id)
															->select('devotee.*', 'member.paytill_date', 'specialremarks.devotee_id as specialremarks_devotee_id', 'familycode.familycode')
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
														->where('devotee.devotee_id', '!=', $devotee[0]->devotee_id)
														->where('devotee.familycode_id', $devotee[0]->familycode_id)
														->select('devotee.*', 'member.paytill_date', 'specialremarks.devotee_id as specialremarks_devotee_id', 'familycode.familycode')
														->GroupBy('devotee.devotee_id')
														->get();

			 for($i = 0; $i < count($setting_samefamily); $i++)
			 {
				 $setting_samefamily[$i]->xiangyou_ciji_id = 0;
				 $setting_samefamily[$i]->yuejuan_id = 0;
			 }
		}

		$setting = SettingGeneralDonation::where('focusdevotee_id', $devotee[0]->devotee_id)
							 ->where('devotee_id', $devotee[0]->devotee_id)
							 ->get();

		if(count($setting) > 0)
		{
			$xianyou_focusdevotee = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
															->leftjoin('setting_generaldonation', 'devotee.devotee_id', '=', 'setting_generaldonation.devotee_id')
															->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
															->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
															->where('devotee.devotee_id', $devotee[0]->devotee_id)
															->where('setting_generaldonation.focusdevotee_id', $devotee[0]->devotee_id)
															->select('devotee.*', 'familycode.familycode', 'member.paytill_date', 'specialremarks.devotee_id as specialremarks_devotee_id', 'setting_generaldonation.xiangyou_ciji_id', 'setting_generaldonation.yuejuan_id')
															->GroupBy('devotee.devotee_id')
												     	->get();
		}

		else
		{
			$xianyou_focusdevotee = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
															->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
															->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
															->where('devotee.devotee_id', $devotee[0]->devotee_id)
															->select('devotee.*', 'familycode.familycode', 'member.paytill_date', 'specialremarks.devotee_id as specialremarks_devotee_id')
												     	->get();

			$xianyou_focusdevotee[0]->xiangyou_ciji_id = 0;
			$xianyou_focusdevotee[0]->yuejuan_id = 0;
		}

		$setting_differentfamily = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
											         ->leftjoin('setting_generaldonation', 'setting_generaldonation.devotee_id', '=', 'devotee.devotee_id')
											         ->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
											         ->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
											         ->where('setting_generaldonation.focusdevotee_id', '=', $devotee[0]->devotee_id)
											         ->where('setting_generaldonation.address_code', '=', 'different')
											         ->select('devotee.*', 'member.paytill_date', 'specialremarks.devotee_id as specialremarks_devotee_id', 'familycode.familycode', 'setting_generaldonation.xiangyou_ciji_id', 'setting_generaldonation.yuejuan_id')
											         ->GroupBy('devotee.devotee_id')
											         ->get();

		$membership = MembershipFee::all()->first();

		$focudevotee_amount = [];

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

				array_push($focudevotee_amount, $amount);
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

		// Get Receipt History
		$receipts = GeneralDonation::leftjoin('devotee', 'devotee.devotee_id', '=', 'generaldonation.focusdevotee_id')
								->leftjoin('receipt', 'receipt.generaldonation_id', '=', 'generaldonation.generaldonation_id')
								->where('generaldonation.focusdevotee_id', '=', $devotee[0]->devotee_id)
								->whereIn('receipt.glcode_id', array(119,112))
								->GroupBy('generaldonation.generaldonation_id')
								->select('generaldonation.*', 'devotee.chinese_name')
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
										 ->where('generaldonation.focusdevotee_id', $devotee[0]->devotee_id)
										 ->where('receipt.glcode_id', 134)
										 ->GroupBy('generaldonation.generaldonation_id')
										 ->select('generaldonation.*', 'devotee.chinese_name')
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

		$yuejuan_receipts = GeneralDonation::leftjoin('devotee', 'devotee.devotee_id', '=', 'generaldonation.focusdevotee_id')
										 ->leftjoin('receipt', 'receipt.generaldonation_id', '=', 'generaldonation.generaldonation_id')
										 ->where('generaldonation.focusdevotee_id', $devotee[0]->devotee_id)
										 ->whereIn('receipt.glcode_id', array(108, 110))
										 ->GroupBy('receipt.generaldonation_id')
										 ->select('generaldonation.*', 'devotee.chinese_name')
										 ->orderBy('generaldonation.generaldonation_id', 'desc')
										 ->get();

		if(count($yuejuan_receipts) > 0)
		{
			for($i = 0; $i < count($yuejuan_receipts); $i++)
			{
				$data = Receipt::where('generaldonation_id', $yuejuan_receipts[$i]->generaldonation_id)->pluck('xy_receipt');

				$receipt_count = count($data);
				$yuejuan_receipts[$i]->receipt_no = $data[0] . ' - ' . $data[$receipt_count - 1];
			}
		}

	  $optionaladdresses = OptionalAddress::where('devotee_id', $devotee_id)->get();
	  $optionalvehicles = OptionalVehicle::where('devotee_id', $devotee_id)->get();
	  $specialRemarks = SpecialRemarks::where('devotee_id', $devotee_id)->get();

		if(isset($nosetting_samefamily))
		{
			if(count($setting_samefamily) != count($nosetting_samefamily))
			{
				Session::put('nosetting_samefamily', $nosetting_samefamily);
			}
		}

	  Session::put('focus_devotee', $devotee);
		Session::put('searchfocus_devotee', $devotee);
		Session::put('focusdevotee_specialremarks', $focusdevotee_specialremarks);
		Session::put('devotee_lists', $devotee_lists);
		Session::put('xianyou_same_family', $xianyou_same_family);
		Session::put('xianyou_same_focusdevotee', $xianyou_same_focusdevotee);
		Session::put('xianyou_different_family', $xianyou_different_family);
		Session::put('yuejuan_same_family', $yuejuan_same_family);
		Session::put('yuejuan_same_focusdevotee', $yuejuan_same_focusdevotee);
		Session::put('yuejuan_different_family', $yuejuan_different_family);
		Session::put('setting_samefamily', $setting_samefamily);
		Session::put('xianyou_focusdevotee', $xianyou_focusdevotee);
		Session::put('setting_differentfamily', $setting_differentfamily);
		Session::put('receipts', $receipts);
		Session::put('ciji_receipts', $ciji_receipts);
		Session::put('yuejuan_receipts', $yuejuan_receipts);
		Session::put('optionaladdresses', $optionaladdresses);
		Session::put('optionalvehicles', $optionalvehicles);
		Session::put('specialRemarks', $specialRemarks);
		Session::put('focusdevotee_amount', $focudevotee_amount);
		Session::put('samefamily_amount', $samefamily_amount);
		Session::put('differentfamily_amount', $differentfamily_amount);

		$today = Carbon::today();

		$events = FestiveEvent::orderBy('start_at', 'asc')
							->where('start_at', '>', $today)
							->take(1)
							->get();

		return redirect()->route('get-donation-page', [
			'events' => $events
		]);

	}

	// Get Search Family Code
	public function getSearchFamilyCode(Request $request)
	{
		$input = array_except($request->all(), '_token');

		// Find Focus Devotee
		$devotee = new Devotee;
    $result = $devotee->searchFamilyCode($input)->get();

    return response()->json(array(
      'familycode' => $result
    ));
	}

	// Get Devotee Detail
	public function getDevoteeDetail(Request $request)
	{
		$devotee_id = $_GET['devotee_id'];

		$devotee = Devotee::find($devotee_id);
		$optionaladdresses = OptionalAddress::where('devotee_id', $devotee_id)->get();
		$optionalvehicles = OptionalVehicle::where('devotee_id', $devotee_id)->get();
		$specialRemarks = SpecialRemarks::where('devotee_id', $devotee_id)->get();

		$devotee->dob = Carbon::parse($devotee->dob)->format("m/d/Y");

		return response()->json(array(
			'devotee' => $devotee,
			'optionaladdresses' => $optionaladdresses,
			'optionalvehicles' => $optionalvehicles,
			'specialRemarks' => $specialRemarks
		));
	}

	// Get Member Detail
	public function getMemberDetail(Request $request)
	{
		$devotee_id = $_GET['devotee_id'];
		$member_id = $_GET['member_id'];

		$devotee = Devotee::find($devotee_id);
		$member = Member::find($member_id);
		$optionaladdresses = OptionalAddress::where('devotee_id', $devotee_id)->get();
		$optionalvehicles = OptionalVehicle::where('devotee_id', $devotee_id)->get();
		$specialRemarks = SpecialRemarks::where('devotee_id', $devotee_id)->get();

		$devotee->dob = Carbon::parse($devotee->dob)->format("d/m/Y");

		if(isset($member->approved_date))
		{
			$member->approved_date = Carbon::parse($member->approved_date)->format("d/m/Y");
		}

		if(isset($member->cancelled_date))
		{
			$member->cancelled_date = Carbon::parse($member->cancelled_date)->format("d/m/Y");
		}

		return response()->json(array(
			'devotee' => $devotee,
			'member' => $member,
			'optionaladdresses' => $optionaladdresses,
			'optionalvehicles' => $optionalvehicles,
			'specialRemarks' => $specialRemarks
		));
	}

	public function getFocusDevoteeDetail(Request $request)
	{
	  $devotee_id = $_GET['devotee_id'];

		Session::forget('focus_devotee');
		Session::forget('devotee_lists');
		Session::forget('optionaladdresses');
		Session::forget('optionalvehicles');
		Session::forget('specialRemarks');

	  $devotee = Devotee::leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
							 ->leftjoin('familycode', 'devotee.familycode_id', '=', 'familycode.familycode_id')
							 ->select('devotee.*', 'familycode.familycode', 'member.introduced_by1', 'member.introduced_by2', 'member.approved_date')
							 ->where('devotee.devotee_id', $devotee_id)
							 ->get();

		$devotee_lists = Devotee::join('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
					 		       ->where('devotee.familycode_id', $devotee[0]->familycode_id)
					 		       ->where('devotee_id', '!=', $devotee[0]->devotee_id)
					 		       ->orderBy('devotee_id', 'asc')
					 		       ->select('devotee.*')
					 		       ->addSelect('familycode.familycode')->get();

		if(isset($devotee[0]->dob))
		{
			$devotee[0]->dob = Carbon::parse($devotee[0]->dob)->format("d/m/Y");
		}

		if(isset($devotee[0]->approved_date))
		{
			$devotee[0]->approved_date = Carbon::parse($devotee[0]->approved_date)->format("d/m/Y");
		}

	  $optionaladdresses = OptionalAddress::where('devotee_id', $devotee_id)->get();
	  $optionalvehicles = OptionalVehicle::where('devotee_id', $devotee_id)->get();
	  $specialRemarks = SpecialRemarks::where('devotee_id', $devotee_id)->get();

	  Session::put('focus_devotee', $devotee);
		Session::put('devotee_lists', $devotee_lists);
		Session::put('optionaladdresses', $optionaladdresses);
		Session::put('optionalvehicles', $optionalvehicles);
		Session::put('specialRemarks', $specialRemarks);

	  return response()->json(array(
	    'devotee' => $devotee,
	    'optionaladdresses' => $optionaladdresses,
	    'optionalvehicles' => $optionalvehicles,
	    'specialRemarks' => $specialRemarks
	  ));
	}


	// Add New Devotee
	public function postAddDevotee(Request $request)
	{
		$member_id = "";
		$devotee_id = "";
		$approveNewDate = "";
		$input = array_except($request->all(), '_token');

    if(isset($input['authorized_password']))
		{
			$user = User::find(Auth::user()->id);
      $hashedPassword = $user->password;

      if (Hash::check($input['authorized_password'], $hashedPassword))
			{
				if(isset($input['other_dialect']))
				{
					$result = Dialect::where('dialect_name', $input['other_dialect'])->first();

					if($result)
					{
						$request->session()->flash('error', "Dialect Name is already exist.");
						return redirect()->back()->withInput();
					}
					else
					{
						$data = [
							'dialect_name' => $input['other_dialect']
						];

						$dialect = Dialect::create($data);
						$dialect_id = $dialect->dialect_id;
					}
				}

				else
				{
					$dialect_id = $input['dialect'];
				}

				if(isset($input['other_race']))
				{
					$data = [
						'race_name' => $input['other_race']
					];

					$race = Race::create($data);
					$race_id = $race->race_id;
				}

				else
				{
					$race_id = $input['race'];
				}

				// Modify fields
				if(isset($input['dob']))
				{
				  $dob_date = str_replace('/', '-', $input['dob']);
				  $dobNewDate = date("Y-m-d", strtotime($dob_date));
				}

				else {
				  $dobNewDate = $input['dob'];
				}

				// Save Member
				if(isset($input['introduced_by1']) && isset($input['introduced_by2']))
				{
				  if(isset($input['approved_date']))
				  {
				    $approvedDate_date = str_replace('/', '-', $input['approved_date']);
				    $approveNewDate = date("Y-m-d", strtotime($approvedDate_date));
				  }

				  else {
				    $approveNewDate = $input['approved_date'];
				  }

				  $data = [
				    "introduced_by1" => $input['introduced_by1'],
				    "introduced_by2" => $input['introduced_by2'],
				    "approved_date" => $approveNewDate
				  ];

				  $member = Member::create($data);
				  $member_id = $member['member_id'];
				}

				if($member_id != null && isset($input['familycode_id']))
				{
				  $data = [
				    "title" => $input['title'],
				    "chinese_name" => $input['chinese_name'],
				    "english_name" => $input['english_name'],
				    "contact" => $input['contact'],
				    "guiyi_name" => $input['guiyi_name'],
				    "address_houseno" => $input['address_houseno'],
				    "address_unit1" => $input['address_unit1'],
				    "address_unit2" => $input['address_unit2'],
				    "address_street" => $input['address_street'],
				    "address_postal" => $input['address_postal'],
				    "address_translated" => $input['address_translated'],
				    "oversea_addr_in_chinese" => $input['oversea_addr_in_chinese'],
				    "nric" => $input['nric'],
				    "deceased_year" => $input['deceased_year'],
				    "dob" => $dobNewDate,
				    "marital_status" => $input['marital_status'],
				    "dialect" => $dialect_id,
						"race" => $race_id,
				    "nationality" => $input['nationality'],
						"mailer" => $input['mailer'],
				    "familycode_id" => $input['familycode_id'],
				    "member_id" => $member_id
				  ];

				  $devotee = Devotee::create($data);
				  $devotee_id = $devotee->devotee_id;
				}

				elseif($member_id != null)
				{
				  // Create Family Code
				  $familycode_id = FamilyCode::all()->last()->familycode_id;
				  $new_familycode_id = $familycode_id + 1;
				  $new_familycode = "F" . $new_familycode_id;

				  $familycode_data = [
				    "familycode" => $new_familycode
				  ];

				  $familycode = FamilyCode::create($familycode_data);

				 $data = [
				   "title" => $input['title'],
				   "chinese_name" => $input['chinese_name'],
				   "english_name" => $input['english_name'],
				   "contact" => $input['contact'],
				   "guiyi_name" => $input['guiyi_name'],
				   "address_houseno" => $input['address_houseno'],
				   "address_unit1" => $input['address_unit1'],
				   "address_unit2" => $input['address_unit2'],
				   "address_street" => $input['address_street'],
				   "address_postal" => $input['address_postal'],
				   "address_translated" => $input['address_translated'],
				   "oversea_addr_in_chinese" => $input['oversea_addr_in_chinese'],
				   "nric" => $input['nric'],
				   "deceased_year" => $input['deceased_year'],
				   "dob" => $dobNewDate,
				   "marital_status" => $input['marital_status'],
					 "dialect" => $dialect_id,
					 "race" => $race_id,
				   "nationality" => $input['nationality'],
					 "mailer" => $input['mailer'],
				   "familycode_id" => $familycode->familycode_id,
				   "member_id" => $member_id
				 ];

				 $devotee = Devotee::create($data);
				 $devotee_id = $devotee->devotee_id;
				}

				elseif(isset($input['familycode_id']))
		    {

		      $data = [
		        "title" => $input['title'],
		        "chinese_name" => $input['chinese_name'],
		        "english_name" => $input['english_name'],
		        "contact" => $input['contact'],
		        "guiyi_name" => $input['guiyi_name'],
		        "address_houseno" => $input['address_houseno'],
		        "address_unit1" => $input['address_unit1'],
		        "address_unit2" => $input['address_unit2'],
		        "address_street" => $input['address_street'],
		        "address_postal" => $input['address_postal'],
		        "address_translated" => $input['address_translated'],
		        "oversea_addr_in_chinese" => $input['oversea_addr_in_chinese'],
		        "nric" => $input['nric'],
		        "deceased_year" => $input['deceased_year'],
		        "dob" => $dobNewDate,
		        "marital_status" => $input['marital_status'],
						"dialect" => $dialect_id,
						"race" => $race_id,
		        "nationality" => $input['nationality'],
						"mailer" => $input['mailer'],
		        "familycode_id" => $input['familycode_id']
		    ];

		    $devotee = Devotee::create($data);
		    $devotee_id = $devotee->devotee_id;
		  }

			else
		  {
		    // Create Family Code
		    $familycode_id = FamilyCode::all()->last()->familycode_id;
		    $new_familycode_id = $familycode_id + 1;
		    $new_familycode = "F" . $new_familycode_id;

			  $familycode_data = [
			    "familycode" => $new_familycode
			  ];

		  	$familycode = FamilyCode::create($familycode_data);

		    $data = [
		      "title" => $input['title'],
		      "chinese_name" => $input['chinese_name'],
		      "english_name" => $input['english_name'],
		      "contact" => $input['contact'],
		      "guiyi_name" => $input['guiyi_name'],
		      "address_houseno" => $input['address_houseno'],
		      "address_unit1" => $input['address_unit1'],
		      "address_unit2" => $input['address_unit2'],
		      "address_street" => $input['address_street'],
		      "address_postal" => $input['address_postal'],
		      "address_translated" => $input['address_translated'],
		      "oversea_addr_in_chinese" => $input['oversea_addr_in_chinese'],
		      "nric" => $input['nric'],
		      "deceased_year" => $input['deceased_year'],
		      "dob" => $dobNewDate,
		      "marital_status" => $input['marital_status'],
					"dialect" => $dialect_id,
					"race" => $race_id,
		      "nationality" => $input['nationality'],
					"mailer" => $input['mailer'],
		      "familycode_id" => $familycode->familycode_id
		    ];

		    $devotee = Devotee::create($data);
		    $devotee_id = $devotee->devotee_id;
		    }

				if($devotee_id != null)
		    {
						// Save Optional Address
				    for($i = 0; $i < count($input['address_type']); $i++)
				    {
				      if($input['address_type'][$i] == 'company' || $input['address_type'][$i] == 'stall')
							{
								if(isset($input['address_data_hidden'][$i]))
								{
									$address = $input['address_data_hidden'][$i];
									$address_translated = $input['address_translated_hidden'][$i];
								}
								else
								{
									$address = null;
									$address_translated = null;
								}

								if (isset($input['address_oversea_hidden'][$i])) {
									$oversea_address = $input['address_oversea_hidden'][$i];
								}
								else
								{
									$oversea_address = null;
								}

								$optional_address = [
					        "type" => $input['address_type'][$i],
					        "data" => $input['address_data'][$i],
									"address" => $address,
									"oversea_address" => $oversea_address,
									"address_translated" => $address_translated,
					        "devotee_id" => $devotee_id
					      ];

					      OptionalAddress::create($optional_address);
							}

							else
							{
								if(isset($input['address_data_hidden'][$i]))
								{
									$address = $input['address_data_hidden'][$i];
									$address_translated = $input['address_translated_hidden'][$i];
								}
								else
								{
									$address = null;
									$address_translated = null;
								}

								if (isset($input['address_oversea_hidden'][$i])) {
									$oversea_address = $input['address_oversea_hidden'][$i];
								}
								else
								{
									$oversea_address = null;
								}

								$optional_address1 = [
					        "type" => $input['address_type'][$i],
									"address" => $address,
									"oversea_address" => $oversea_address,
									"address_translated" => $address_translated,
					        "devotee_id" => $devotee_id
					      ];

					      OptionalAddress::create($optional_address1);
							}
				    }


		      if(isset($input['vehicle_data'][0]))
		      {
		        // Save Optional Vehicle
				    for($i = 0; $i < count($input['vehicle_type']); $i++)
				    {
				      $optional_vehicle = [
				        "type" => $input['vehicle_type'][$i],
				        "data" => $input['vehicle_data'][$i],
				        "devotee_id" => $devotee_id
				      ];

				      OptionalVehicle::create($optional_vehicle);
				    }
		      }

		      if(isset($input['special_remark'][0]))
		      {
		        // Save Special Remarks
		        for($i = 0; $i < count($input['special_remark']); $i++)
		        {
		          $specialRemark = [
		            "data" => $input['special_remark'][$i],
		            "devotee_id" => $devotee_id
		          ];

		          SpecialRemarks::create($specialRemark);
		        }
		      }
		    }

				// remove session data
				if(Session::has('focus_devotee')) { Session::forget('focus_devotee'); }
				if(Session::has('focusdevotee_specialremarks')) { Session::forget('focusdevotee_specialremarks'); }
				if(Session::has('devotee_lists')) { Session::forget('devotee_lists'); }
				if(Session::has('xianyou_same_family')) { Session::forget('xianyou_same_family'); }
				if(Session::has('xianyou_same_focusdevotee')) { Session::forget('xianyou_same_focusdevotee'); }
				if(Session::has('yuejuan_same_family')) { Session::forget('yuejuan_same_family'); }
				if(Session::has('xianyou_different_family')) { Session::forget('xianyou_different_family'); }
				if(Session::has('setting_samefamily')) { Session::forget('setting_samefamily'); }
				if(Session::has('setting_differentfamily')) { Session::forget('setting_differentfamily'); }
				if(Session::has('optionaladdresses')) { Session::forget('optionaladdresses'); }
				if(Session::has('optionalvehicles')) { Session::forget('optionalvehicles'); }
				if(Session::has('specialRemarks')) { Session::forget('specialRemarks'); }
				if(Session::has('receipts')) { Session::forget('receipts'); }
				if(Session::has('ciji_receipts')) { Session::forget('ciji_receipts'); }
				if(Session::has('yuejuan_receipts')) { Session::forget('yuejuan_receipts'); }
				if(Session::has('xianyou_different_family')) { Session::forget('xianyou_different_family'); }
				if(Session::has('yuejuan_different_family')) { Session::forget('yuejuan_different_family'); }

				$setting_data = [
				  'focusdevotee_id' => $devotee_id,
					'xiangyou_ciji_id' => 1,
					'yuejuan_id' => 1,
					'devotee_id' => $devotee_id
				];

				SettingGeneralDonation::create($setting_data);

				$focus_devotee = Devotee::leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
									 ->leftjoin('familycode', 'devotee.familycode_id', '=', 'familycode.familycode_id')
									 ->leftjoin('dialect', 'devotee.dialect', '=', 'dialect.dialect_id')
									 ->select('devotee.*', 'dialect.dialect_name', 'familycode.familycode', 'member.introduced_by1',
										'member.introduced_by2', 'member.approved_date', 'member.paytill_date')
									 ->where('devotee.devotee_id', $devotee_id)
									 ->get();

				$focusdevotee_specialremarks = Devotee::leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
				          ->where('devotee.devotee_id', $focus_devotee[0]->devotee_id)
				          ->get();

				$focus_devotee[0]->specialremarks_id = $focusdevotee_specialremarks[0]->devotee_id;

				if(isset($focus_devotee[0]->dob))
				{
				  $focus_devotee[0]->dob = Carbon::parse($focus_devotee[0]->dob)->format("d/m/Y");
				}

				if(isset($focus_devotee[0]->approved_date))
				{
				  $focus_devotee[0]->approved_date = Carbon::parse($focus_devotee[0]->approved_date)->format("d/m/Y");
				}

				// Get Devotee Lists for relocation
				$familycode_id = $focus_devotee[0]->familycode_id;

				$devotee_lists = Devotee::join('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
				                  ->where('devotee.familycode_id', $familycode_id)
				                  ->where('devotee_id', '!=', $focus_devotee[0]->devotee_id)
				                  ->orderBy('devotee_id', 'asc')
				                  ->select('devotee.*')
				                  ->addSelect('familycode.familycode')->get();

			// $xianyou_same_family = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
			// 										   ->leftjoin('setting_generaldonation', 'devotee.devotee_id', '=', 'setting_generaldonation.devotee_id')
			// 										   ->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
			// 										   ->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
			// 										   ->where('devotee.familycode_id', $familycode_id)
			// 										   ->where('devotee.devotee_id', '!=', $focus_devotee[0]->devotee_id)
			// 										   ->where('setting_generaldonation.address_code', '=', 'same')
			// 										   ->where('setting_generaldonation.xiangyou_ciji_id', '=', '1')
			// 										   ->select('devotee.*', 'familycode.familycode', 'member.paytill_date', 'specialremarks.devotee_id as specialremarks_devotee_id')
			// 										   ->get();

			// $xianyou_different_family = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
			// 							 				      ->leftjoin('setting_generaldonation', 'devotee.devotee_id', '=', 'setting_generaldonation.devotee_id')
			// 							 				      ->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
			// 							 				      ->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
			// 							 				      ->where('devotee.devotee_id', '!=', $focus_devotee[0]->devotee_id)
			// 							 				      ->where('setting_generaldonation.address_code', '=', 'different')
			// 							 				      ->where('setting_generaldonation.xiangyou_ciji_id', '=', '1')
			// 							 				      ->where('setting_generaldonation.focusdevotee_id', '=', $focus_devotee[0]->devotee_id)
			// 							 				      ->select('devotee.*', 'member.paytill_date', 'specialremarks.devotee_id as specialremarks_devotee_id', 'familycode.familycode')
			// 							 				      ->GroupBy('devotee.devotee_id')
			// 							 				      ->get();

			$setting_samefamily = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
														->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
														->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
														->where('devotee.devotee_id', '!=', $focus_devotee[0]->devotee_id)
														->where('devotee.familycode_id', $familycode_id)
														->select('devotee.*', 'member.paytill_date', 'specialremarks.devotee_id as specialremarks_devotee_id', 'familycode.familycode')
														->GroupBy('devotee.devotee_id')
														->get();

		for($i = 0; $i < count($setting_samefamily); $i++)
		{
			$setting_samefamily[$i]->xiangyou_ciji_id = 0;
			$setting_samefamily[$i]->yuejuan_id = 0;
		}

		 $setting_differentfamily = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
																->leftjoin('setting_generaldonation', 'setting_generaldonation.devotee_id', '=', 'devotee.devotee_id')
																->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
																->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
																->where('setting_generaldonation.focusdevotee_id', '=', $focus_devotee[0]->devotee_id)
																->where('setting_generaldonation.address_code', '=', 'different')
																->select('devotee.*', 'member.paytill_date', 'specialremarks.devotee_id as specialremarks_devotee_id', 'familycode.familycode', 'setting_generaldonation.xiangyou_ciji_id', 'setting_generaldonation.yuejuan_id')
																->GroupBy('devotee.devotee_id')
																->get();

		$receipts = Receipt::leftjoin('generaldonation', 'generaldonation.generaldonation_id', '=', 'receipt.generaldonation_id')
								->leftjoin('devotee', 'devotee.devotee_id', '=', 'generaldonation.focusdevotee_id')
								->where('generaldonation.focusdevotee_id', $focus_devotee[0]->devotee_id)
								->orderBy('receipt_id', 'desc')
								->select('receipt.*', 'devotee.chinese_name', 'devotee.devotee_id', 'generaldonation.manualreceipt',
									'generaldonation.hjgr as generaldonation_hjgr', 'generaldonation.trans_no as trans_no', 'generaldonation.generaldonation_id')
								->orderBy('generaldonation.generaldonation_id', 'desc')
								->get();

		$setting = SettingGeneralDonation::where('focusdevotee_id', $focus_devotee[0]->devotee_id)
							 ->where('devotee_id', $focus_devotee[0]->devotee_id)
							 ->get();

		if(count($setting) > 0)
		{
			$xianyou_focusdevotee = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
								              ->leftjoin('setting_generaldonation', 'devotee.devotee_id', '=', 'setting_generaldonation.devotee_id')
								              ->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
								              ->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
								              ->where('devotee.devotee_id', $focus_devotee[0]->devotee_id)
								              ->where('setting_generaldonation.focusdevotee_id', $focus_devotee[0]->devotee_id)
								              ->select('devotee.*', 'familycode.familycode', 'member.paytill_date', 'specialremarks.devotee_id as specialremarks_devotee_id', 'setting_generaldonation.xiangyou_ciji_id', 'setting_generaldonation.yuejuan_id')
								              ->GroupBy('devotee.devotee_id')
								              ->get();
		}

		else
		{
			$xianyou_focusdevotee = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
								              ->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
								              ->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
								              ->where('devotee.devotee_id', $focus_devotee[0]->devotee_id)
								              ->select('devotee.*', 'familycode.familycode', 'member.paytill_date', 'specialremarks.devotee_id as specialremarks_devotee_id')
								              ->get();

			$xianyou_focusdevotee[0]->xiangyou_ciji_id = 0;
			$xianyou_focusdevotee[0]->yuejuan_id = 0;
		}

		$xianyou_same_focusdevotee = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
		                             ->leftjoin('setting_generaldonation', 'devotee.devotee_id', '=', 'setting_generaldonation.devotee_id')
		                             ->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
		                             ->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
		                             ->where('setting_generaldonation.address_code', '=', 'same')
		                             ->where('setting_generaldonation.xiangyou_ciji_id', '=', '1')
		                             ->where('setting_generaldonation.focusdevotee_id', '=', $focus_devotee[0]->devotee_id)
		                             ->where('setting_generaldonation.devotee_id', '=', $focus_devotee[0]->devotee_id)
		                             ->select('devotee.*', 'familycode.familycode', 'member.paytill_date', 'specialremarks.devotee_id as specialremarks_devotee_id')
		                             ->GroupBy('devotee.devotee_id')
		                             ->get();

		$yuejuan_same_focusdevotee = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
		                             ->leftjoin('setting_generaldonation', 'devotee.devotee_id', '=', 'setting_generaldonation.devotee_id')
		                             ->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
		                             ->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
		                             ->where('setting_generaldonation.address_code', '=', 'same')
		                             ->where('setting_generaldonation.yuejuan_id', '=', '1')
		                             ->where('setting_generaldonation.focusdevotee_id', '=', $focus_devotee[0]->devotee_id)
		                             ->where('setting_generaldonation.devotee_id', '=', $focus_devotee[0]->devotee_id)
		                             ->select('devotee.*', 'familycode.familycode', 'member.paytill_date', 'specialremarks.devotee_id as specialremarks_devotee_id')
		                             ->GroupBy('devotee.devotee_id')
		                             ->get();

		$optionaladdresses = OptionalAddress::where('devotee_id', $focus_devotee[0]->devotee_id)->get();
		$optionalvehicles = OptionalVehicle::where('devotee_id', $focus_devotee[0]->devotee_id)->get();
		$specialRemarks = SpecialRemarks::where('devotee_id', $focus_devotee[0]->devotee_id)->get();

		$membership = MembershipFee::all()->first();
		$fee = $membership->membership_fee;

		$focudevotee_amount = [];

		if(isset($focus_devotee[0]->paytill_date))
		{
			$amount = [];

			$myArray = explode('-', $focus_devotee[0]->paytill_date);

			$count = 1;
			for($j = 1; $j <= 10; $j++)
			{
				$dt = Carbon::create($myArray[0], $myArray[1], $myArray[2], 0);
				$dt = $dt->addYears($count);

				$format = Carbon::parse($dt)->format("Y-m");

				$fee = $fee * $j;
				$amount[$j] = number_format($fee, 2) . ' --- ' . $format;

				$count++;
			}

			array_push($focudevotee_amount, $amount);
		 }

		 Session::put('focus_devotee', $focus_devotee);
		 Session::put('focusdevotee_specialremarks', $focusdevotee_specialremarks);
		 Session::put('devotee_lists', $devotee_lists);
		//  Session::put('xianyou_same_family', $xianyou_same_family);
		 Session::put('xianyou_focusdevotee', $xianyou_focusdevotee);
		 Session::put('xianyou_same_focusdevotee', $xianyou_same_focusdevotee);
		 Session::put('yuejuan_same_focusdevotee', $yuejuan_same_focusdevotee);
		 Session::put('setting_samefamily', $setting_samefamily);
		 Session::put('setting_differentfamily', $setting_differentfamily);
		 Session::put('optionaladdresses', $optionaladdresses);
		 Session::put('optionalvehicles', $optionalvehicles);
		 Session::put('specialRemarks', $specialRemarks);
		 Session::put('receipts', $receipts);
		 Session::put('focusdevotee_amount', $focudevotee_amount);

		 $today = Carbon::today();

		 $events = FestiveEvent::orderBy('start_at', 'asc')
								->where('start_at', '>', $today)
								->take(1)
								->get();

			if($member_id != null)
		  {
		    $request->session()->flash('success', 'Member account has been created!');
				return redirect()->route('get-donation-page', [
					'events' => $events
				]);
		  }

		  else
		  {
		    $request->session()->flash('success', 'Devotee account has been created!');
				return redirect()->route('get-donation-page', [
					'events' => $events
				]);
		  }
		}

		else
		{
			$request->session()->flash('error', "Password did not match. Please Try Again");
      return redirect()->back()->withInput();
		}
	}

		else
		{
			$request->session()->flash('error', "Please enter password. Please Try Again");
            return redirect()->back()->withInput();
		}
	}

	// Update Devotee
	public function postEditDevotee(Request $request)
	{
		$familycode_id = "";
		$approveNewDate = "";
		$cancelledNewDate = "";
		$reason_for_cancel = "";

		$input = array_except($request->all(), '_token');

		if(isset($input['authorized_password']))
		{
			$user = User::find(Auth::user()->id);
		  $hashedPassword = $user->password;

			if (Hash::check($input['authorized_password'], $hashedPassword))
			{
				if(isset($input['other_dialect']))
				{
				  $data = [
				  	'dialect_name' => $input['other_dialect']
				  ];

				  $dialect = Dialect::create($data);
				  $dialect_id = $dialect->dialect_id;
				}

				else
				{
				  $dialect_id = $input['dialect'];
				}

				if(isset($input['other_race']))
				{
				 $data = [
				   'race_name' => $input['other_race']
				 ];

				 $race = Race::create($data);
				 $race_id = $race->race_id;
				}

				else
				{
				  $race_id = $input['race'];
				}

				// Modify fields
			  if(isset($input['dob']))
			  {
			    $dob_date = str_replace('/', '-', $input['dob']);
			    $dobNewDate = date("Y-m-d", strtotime($dob_date));
			  }

			  else {
			    $dobNewDate = $input['dob'];
			  }

				if(isset($input['approved_date']))
			  {
			    $approvedDate_date = str_replace('/', '-', $input['approved_date']);
			    $approveNewDate = date("Y-m-d", strtotime($approvedDate_date));
			  }

				else
				{
					$approveNewDate = "";
				}

				if(isset($input['cancelled_date']))
			  {
			    $cancelledDate_date = str_replace('/', '-', $input['cancelled_date']);
			    $cancelledNewDate = date("Y-m-d", strtotime($cancelledDate_date));
			  }

				else
				{
					$cancelledNewDate = "";
				}

				if(isset($input['reason_for_cancel']))
				{
					$reason_for_cancel = $input['reason_for_cancel'];
				}

				else
				{
					$reason_for_cancel = "";
				}

				if(isset($input['edit_familycode_id']))
			  {
			    $familycode_id = $input['edit_familycode_id'];
			  }

			  else
			  {
			    $familycode_id = $input['familycode_id'];
			  }

				if(isset($input['introduced_by1']) && isset($input['introduced_by2']))
				{
					$data = [
					  "introduced_by1" => $input['introduced_by1'],
					  "introduced_by2" => $input['introduced_by2'],
					  "approved_date" => $approveNewDate,
						"cancelled_date" => $cancelledNewDate,
				    "reason_for_cancel" => $reason_for_cancel
					];

					$member = Member::create($data);
					$member_id = $member['member_id'];
				}

				elseif (isset($input['cancelled_date'])) {

					$member = Member::find($input['member_id']);

			    $member->cancelled_date = $cancelledNewDate;
			    $member->reason_for_cancel = $input['reason_for_cancel'];

			    $member->save();

					$member_id = $input['member_id'];
				}

				else
				{
					$member_id = $input['member_id'];
				}

				$devotee = Devotee::find($input['devotee_id']);

			  $devotee->title = $input['title'];
			  $devotee->chinese_name = $input['chinese_name'];
			  $devotee->english_name = $input['english_name'];
			  $devotee->contact = $input['contact'];
			  $devotee->guiyi_name = $input['guiyi_name'];
			  $devotee->address_houseno = $input['address_houseno'];
			  $devotee->address_unit1 = $input['address_unit1'];
			  $devotee->address_unit2 = $input['address_unit2'];
			  $devotee->address_street = $input['address_street'];
			  $devotee->address_postal = $input['address_postal'];
			  $devotee->address_translated = $input['address_translated'];
			  $devotee->oversea_addr_in_chinese = $input['oversea_addr_in_chinese'];
			  $devotee->nric = $input['nric'];
			  $devotee->deceased_year = $input['deceased_year'];
			  $devotee->dob = $dobNewDate;
			  $devotee->marital_status = $input['marital_status'];
			  $devotee->dialect = $dialect_id;
				$devotee->race = $race_id;
				$devotee->mailer = $input['mailer'];
			  $devotee->nationality = $input['nationality'];
			  $devotee->familycode_id = $familycode_id;
			  $devotee->member_id = $member_id;
				$devotee->save();

			//delete first before saving
			OptionalAddress::where('devotee_id', $input['devotee_id'])->delete();

			// Save Optional Address
			for($i = 0; $i < count($input['address_type']); $i++)
			{
				if($input['address_type'][$i] == 'company' || $input['address_type'][$i] == 'stall')
				{
					if(isset($input['address_data_hidden'][$i]))
					{
						$address = $input['address_data_hidden'][$i];
						$address_translated = $input['address_translated_hidden'][$i];
					}
					else
					{
						$address = null;
						$address_translated = null;
					}

					if (isset($input['address_oversea_hidden'][$i])) {
						$oversea_address = $input['address_oversea_hidden'][$i];
					}
					else
					{
						$oversea_address = null;
					}

					$optional_address = new OptionalAddress;
				  $optional_address->type = $input['address_type'][$i];
				  $optional_address->data = $input['address_data'][$i];
					$optional_address->address = $address;
					$optional_address->oversea_address = $oversea_address;
					$optional_address->address_translated = $address_translated;
				  $optional_address->devotee_id = $input['devotee_id'];

				  $optional_address->save();
				}

				else
				{
					if(isset($input['address_data_hidden'][$i]))
					{
						$address = $input['address_data_hidden'][$i];
						$address_translated = $input['address_translated_hidden'][$i];
					}
					else
					{
						$address = null;
						$address_translated = null;
					}

					if (isset($input['address_oversea_hidden'][$i])) {
						$oversea_address = $input['address_oversea_hidden'][$i];
					}
					else
					{
						$oversea_address = null;
					}

					$optional_address = new OptionalAddress;
				  $optional_address->type = $input['address_type'][$i];
				  $optional_address->address = $address;
					$optional_address->oversea_address = $oversea_address;
					$optional_address->address_translated = $address_translated;
				  $optional_address->devotee_id = $input['devotee_id'];

				  $optional_address->save();
				}
			}

			//delete first before saving
			OptionalVehicle::where('devotee_id', $input['devotee_id'])->delete();

		  if(isset($input['vehicle_data'][0]))
		  {
				for($i = 0; $i < count($input['vehicle_type']); $i++)
				{
					$optional_vehicle = new OptionalVehicle;
				  $optional_vehicle->type = $input['vehicle_type'][$i];
				  $optional_vehicle->data = $input['vehicle_data'][$i];
				  $optional_vehicle->devotee_id = $input['devotee_id'];

				  $optional_vehicle->save();
				}
		  }

			//delete first before saving
			SpecialRemarks::where('devotee_id', $input['devotee_id'])->delete();

			// Update Special Remarks
		  if(isset($input['special_remark'][0]))
		  {
		    for($i = 0; $i < count($input['special_remark']); $i++)
		    {
					$special_remark = new SpecialRemarks;
				  $special_remark->data = $input['special_remark'][$i];
				  $special_remark->devotee_id = $input['devotee_id'];

				  $special_remark->save();
		     }
		   }

			 $devotee = Devotee::leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
			             ->leftjoin('familycode', 'devotee.familycode_id', '=', 'familycode.familycode_id')
									 ->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
			             ->select('devotee.*', 'familycode.familycode', 'member.introduced_by1', 'member.introduced_by2', 'member.approved_date',
									 'specialremarks.devotee_id as specialremarks_devotee_id')
			             ->where('devotee.devotee_id', $input['devotee_id'])
									 ->GroupBy('devotee.devotee_id')
			             ->get();

				if(isset($devotee[0]->dob))
				{
					$devotee[0]->dob = Carbon::parse($devotee[0]->dob)->format("d/m/Y");
				}

				if(isset($devotee[0]->approved_date))
				{
					$devotee[0]->approved_date = Carbon::parse($devotee[0]->approved_date)->format("d/m/Y");
				}

				$optionaladdresses = OptionalAddress::where('devotee_id', '=', $input['devotee_id'])->get();
				$optionalvehicles = OptionalVehicle::where('devotee_id', '=', $input['devotee_id'])->get();
				$special_remark = SpecialRemarks::where('devotee_id', '=', $input['devotee_id'])->get();

				$focusdevotee_specialremarks = Devotee::leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
				          ->where('devotee.devotee_id', $input['devotee_id'])
				          ->get();

				$devotee[0]->specialremarks_id = $focusdevotee_specialremarks[0]->devotee_id;

				Session::forget('focus_devotee');
				Session::forget('optionaladdresses');
				Session::forget('optionalvehicles');
				Session::forget('specialRemarks');
				Session::forget('focusdevotee_specialremarks');

				Session::put('focus_devotee', $devotee);
				Session::put('optionaladdresses', $optionaladdresses);
				Session::put('optionalvehicles', $optionalvehicles);
				Session::put('specialRemarks', $special_remark);
				Session::put('focusdevotee_specialremarks', $focusdevotee_specialremarks);

				$request->session()->flash('success', 'Profile is successfully updated.');
				return redirect()->back();
			}

			else
			{
				$request->session()->flash('error', "Password did not match. Please Try Again");
			  return redirect()->back()->withInput();
			}
		}
	}

	// Edit Devotee
	public function getEditDevotee($devotee_id)
	{
		$devotee = Devotee::find($devotee_id);

		$optionaladdresses = OptionalAddress::where('devotee_id', '=', $devotee->devotee_id)->get();
		$optionalvehicles = OptionalVehicle::where('devotee_id', '=', $devotee->devotee_id)->get();
		$special_remark = SpecialRemarks::where('devotee_id', '=', $devotee->devotee_id)->get();

		return view('operator.edit-devotee', [
            'devotee' => $devotee,
            'optionaladdresses' => $optionaladdresses,
            'optionalvehicles' => $optionalvehicles,
            'special_remark' => $special_remark
        ]);
	}

	// Focus Devotee
	public function getFocusDevotee(Request $request)
	{
		// remove session data
		Session::forget('focus_devotee');
		Session::forget('devotee_lists');
		Session::forget('focusdevotee_specialremarks');
		Session::forget('xianyou_same_family');
		Session::forget('xianyou_different_family');
		Session::forget('setting_samefamily');
		Session::forget('nosetting_samefamily');
		Session::forget('xianyou_focusdevotee');
		Session::forget('setting_differentfamily');
		Session::forget('optionaladdresses');
		Session::forget('optionalvehicles');
		Session::forget('specialRemarks');
		Session::forget('receipts');
		Session::forget('ciji_receipts');
		Session::forget('yuejuan_receipts');
		Session::forget('focusdevotee_amount');
		Session::forget('samefamily_amount');
		Session::forget('differentfamily_amount');

		$devotees = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
        				->select('devotee.*')
        				->addSelect('familycode.familycode')->get();

		$members = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
								->whereNotNull('member_id')
								->whereNull('deceased_year')
								->orderBy('devotee_id', 'asc')
        				->select('devotee.*')
        				->addSelect('familycode.familycode')->get();

  	$deceased_lists = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
											->whereNotNull('deceased_year')
											->orderBy('devotee_id', 'asc')
					        		->select('devotee.*')
					        		->addSelect('familycode.familycode')->get();

		$input = Input::except('_token');

		$devotee = new Devotee;
    $focus_devotee = $devotee->focusDevotee($input)->get();

		if(count($focus_devotee) == 0)
		{
			return redirect()->route('main-page')->withInput();
		}

		elseif(count($focus_devotee) == 1)
		{
			$focusdevotee_specialremarks = Devotee::leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
								->where('devotee.devotee_id', $focus_devotee[0]->devotee_id)
								->get();

			$focus_devotee[0]->specialremarks_id = $focusdevotee_specialremarks[0]->devotee_id;

			if(isset($focus_devotee[0]->dob))
			{
				$focus_devotee[0]->dob = Carbon::parse($focus_devotee[0]->dob)->format("d/m/Y");
			}

			if(isset($focus_devotee[0]->approved_date))
			{
				$focus_devotee[0]->approved_date = Carbon::parse($focus_devotee[0]->approved_date)->format("d/m/Y");
			}

			// Get Devotee Lists for relocation
	    $familycode_id = $focus_devotee[0]->familycode_id;

	    $devotee_lists = Devotee::join('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
	        							->where('devotee.familycode_id', $familycode_id)
	        							->where('devotee.devotee_id', '!=', $focus_devotee[0]->devotee_id)
	        							->orderBy('devotee.devotee_id', 'asc')
	        							->select('devotee.*', 'familycode.familycode')
	        							->get();

			Session::put('searchfocus_devotee', $focus_devotee);
			Session::put('devotee_lists', $devotee_lists);
		}

		else {

			if(isset($focus_devotee[0]->dob))
			{
				$focus_devotee[0]->dob = Carbon::parse($focus_devotee[0]->dob)->format("d/m/Y");
			}

			if(isset($focus_devotee[0]->approved_date))
			{
				$focus_devotee[0]->approved_date = Carbon::parse($focus_devotee[0]->approved_date)->format("d/m/Y");
			}

			// Get Devotee Lists for relocation
	    $familycode_id = $focus_devotee[0]->familycode_id;

	    $devotee_lists = Devotee::join('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
	        							->where('devotee.familycode_id', $familycode_id)
	        							->where('devotee_id', '!=', $focus_devotee[0]->devotee_id)
	        							->orderBy('devotee_id', 'asc')
	        							->select('devotee.*')
	        							->addSelect('familycode.familycode')->get();

			// Get Relative and friends lists
			$relative_friend_lists = RelativeFriendLists::leftjoin('devotee', 'devotee.devotee_id', '=', 'relative_friend_lists.relative_friend_devotee_id')
																->where('donate_devotee_id', $focus_devotee[0]->devotee_id)
																->select('relative_friend_lists.*', 'devotee.chinese_name', 'devotee.guiyi_name', 'devotee.address_unit1',
																'devotee.address_unit2', 'devotee.address_street', 'devotee.address_building')
																->get();

			Session::put('searchfocus_devotee', $focus_devotee);
			Session::put('devotee_lists', $devotee_lists);
		}

		return redirect()->route('main-page')->withInput()->with([
			'members' => $members,
			'devotees' => $devotees,
			'deceased_lists' => $deceased_lists,
		]);
	}

	// New Search Devotee
	public function getRemoveFocusDevotee(Request $request)
	{
		$devotees = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
								->whereNull('member_id')
								->whereNull('deceased_year')
        				->select('devotee.*')
        				->addSelect('familycode.familycode')->paginate(50);

		$members = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
							 ->whereNotNull('member_id')
							 ->whereNull('deceased_year')
							 ->orderBy('devotee_id', 'asc')
        			 ->select('devotee.*')
        			 ->addSelect('familycode.familycode')->paginate(50);

    $deceased_lists = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
											->whereNotNull('deceased_year')
											->orderBy('devotee_id', 'asc')
        							->select('devotee.*')
        							->addSelect('familycode.familycode')->paginate(50);

		Session::forget('focus_devotee');
		Session::forget('devotee_lists');
		Session::forget('xianyou_same_family');
		Session::forget('xianyou_same_focusdevotee');
		Session::forget('xianyou_different_family');
		Session::forget('setting_samefamily');
		Session::forget('nosetting_samefamily');
		Session::forget('xianyou_focusdevotee');
		Session::forget('yuejuan_same_family');
		Session::forget('yuejuan_different_family');
		Session::forget('setting_differentfamily');
		Session::forget('receipts');
		Session::forget('ciji_receipts');
		Session::forget('yuejuan_receipts');
		Session::forget('optionaladdresses');
		Session::forget('optionalvehicles');
		Session::forget('specialRemarks');
		Session::forget('focusdevotee_amount');
		Session::forget('samefamily_amount');
		Session::forget('differentfamily_amount');

    return redirect()->route('main-page')->with([
      'members' => $members,
      'devotees' => $devotees,
      'deceased_lists' => $deceased_lists
   ]);
	}

	// Get JSON by Focus Devotee
	public function getJSONFocusDevotee(Request $request)
	{
		$chinese_name = $_GET['chinese_name'];

		// Find Focus Devotee
		$devotee = new Devotee;
    $focus_devotee = $devotee->focusDevotee($chinese_name)->get();

    // Get Devotee Lists for relocation
    $familycode_id = $focus_devotee[0]->familycode_id;

    $devotee_lists = Devotee::join('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
        						 ->where('devotee.familycode_id', $familycode_id)
        						 ->select('devotee.*')
        						 ->addSelect('familycode.familycode')->get();

    return response()->json(array(
      'focus_devotee' => $focus_devotee,
      'devotee_lists' => $devotee_lists
    ));
	}

	// Relocation Devotees
	public function postRelocationDevotees(Request $request)
	{
			$relocation_familycode_id = "";
			$input = Input::except('_token', 'address_houseno', 'address_unit1', 'address_unit2', 'address_street',
								'address_building', 'address_postal', 'nationality', 'oversea_addr_in_chinese');

			$devotee = Devotee::where('familycode_id', $input['familycode_id'])
								 ->get();

			if(count($input['relocation_devotee_id']) == count($devotee))
			{
				$relocation_familycode_id = $input['familycode_id'];
			}

			if(isset($input['relocation_familycode_id']))
			{
				$relocation_familycode_id = $input['relocation_familycode_id'];
			}

			if($relocation_familycode_id == "") {

				$familycode_id = FamilyCode::all()->last()->familycode_id;
				$new_familycode_id = $familycode_id + 1;
				$new_familycode = "F" . $new_familycode_id;

				$familycode_data = [
					"familycode" => $new_familycode
				];

				$familycode = FamilyCode::create($familycode_data);
				$relocation_familycode_id = $familycode->familycode_id;
			}

			$user = User::find(Auth::user()->id);
			$hashedPassword = $user->password;

	    if(Hash::check($input['authorized_password'], $hashedPassword))
			{
				for($i = 0; $i < count($input['relocation_devotee_id']); $i++)
		    {
		    	$devotee = Devotee::find($input['relocation_devotee_id'][$i]);

			    $devotee->address_houseno = $input['new_address_houseno'];
			    $devotee->address_unit1 = $input['new_address_unit1'];
			    $devotee->address_unit2 = $input['new_address_unit2'];
			    $devotee->address_street = $input['new_address_street'];
			    $devotee->address_postal = $input['new_address_postal'];
			    $devotee->oversea_addr_in_chinese = $input['new_oversea_addr_in_chinese'];
					$devotee->familycode_id = $relocation_familycode_id;
					$devotee->save();
		    }

				$session_focus_devotee = Session::get('focus_devotee');

				// remove session data
				Session::forget('focus_devotee');
				Session::forget('devotee_lists');
				Session::forget('xianyou_same_family');
				Session::forget('xianyou_same_focusdevotee');
				Session::forget('yuejuan_same_family');
				Session::forget('yuejuan_same_focusdevotee');
				Session::forget('setting_samefamily');
				Session::forget('nosetting_samefamily');
				Session::forget('xianyou_focusdevotee');

				if($session_focus_devotee[0]->member_id != null)
				{
					$focus_devotee = Devotee::join('member', 'member.member_id', '=', 'devotee.member_id')
													 ->join('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
													 ->where('devotee.devotee_id', $session_focus_devotee[0]->devotee_id)
													 ->select('devotee.*', 'member.introduced_by1', 'member.introduced_by2', 'member.approved_date', 'familycode.familycode')
													 ->get();
				}

				else {
					$focus_devotee = Devotee::join('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
													 ->where('devotee.devotee_id', $session_focus_devotee[0]->devotee_id)
													 ->select('devotee.*', 'familycode.familycode')
													 ->get();
				}

				$devotee_lists = Devotee::join('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
				        ->where('devotee.familycode_id', $focus_devotee[0]->familycode_id)
				        ->where('devotee_id', '!=', $focus_devotee[0]->devotee_id)
				        ->orderBy('devotee_id', 'asc')
				        ->select('devotee.*')
				        ->addSelect('familycode.familycode')->get();

			  if(isset($focus_devotee[0]->dob))
			 	{
					$focus_devotee[0]->dob = Carbon::parse($focus_devotee[0]->dob)->format("d/m/Y");
				}

				// Update All Session Data
				$xianyou_same_family = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
				                       ->leftjoin('setting_generaldonation', 'devotee.devotee_id', '=', 'setting_generaldonation.devotee_id')
				                       ->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
				                       ->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
				                       ->where('devotee.familycode_id', $focus_devotee[0]->familycode_id)
				                       ->where('devotee.devotee_id', '!=', $focus_devotee[0]->devotee_id)
				                       ->where('setting_generaldonation.address_code', '=', 'same')
				                       ->where('setting_generaldonation.xiangyou_ciji_id', '=', '1')
				                       ->where('setting_generaldonation.focusdevotee_id', '=', $focus_devotee[0]->devotee_id)
				                       ->select('devotee.*', 'familycode.familycode', 'member.paytill_date', 'specialremarks.devotee_id as specialremarks_devotee_id')
				                       ->GroupBy('devotee.devotee_id')
				                       ->get();

				$xianyou_same_focusdevotee = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
											 				       ->leftjoin('setting_generaldonation', 'devotee.devotee_id', '=', 'setting_generaldonation.devotee_id')
											 				       ->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
											 				       ->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
											 				       ->where('setting_generaldonation.address_code', '=', 'same')
											 				       ->where('setting_generaldonation.xiangyou_ciji_id', '=', '1')
											 				       ->where('setting_generaldonation.focusdevotee_id', '=', $focus_devotee[0]->devotee_id)
											 				       ->where('setting_generaldonation.devotee_id', '=', $focus_devotee[0]->devotee_id)
											 				       ->select('devotee.*', 'familycode.familycode', 'member.paytill_date', 'specialremarks.devotee_id as specialremarks_devotee_id')
											 				       ->GroupBy('devotee.devotee_id')
											 				       ->get();

				$yuejuan_same_family = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
									 						 ->leftjoin('setting_generaldonation', 'devotee.devotee_id', '=', 'setting_generaldonation.devotee_id')
									 						 ->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
									 						 ->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
									 						 ->where('devotee.familycode_id', $focus_devotee[0]->familycode_id)
									 						 ->where('setting_generaldonation.address_code', '=', 'same')
									 						 ->where('setting_generaldonation.yuejuan_id', '=', '1')
									 						 ->where('setting_generaldonation.focusdevotee_id', '=', $focus_devotee[0]->devotee_id)
									 						 ->select('devotee.*', 'familycode.familycode', 'member.paytill_date', 'specialremarks.devotee_id as specialremarks_devotee_id')
									 						 ->GroupBy('devotee.devotee_id')
									 						 ->get();

				$yuejuan_same_focusdevotee = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
															       ->leftjoin('setting_generaldonation', 'devotee.devotee_id', '=', 'setting_generaldonation.devotee_id')
															       ->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
															       ->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
															       ->where('setting_generaldonation.address_code', '=', 'same')
															       ->where('setting_generaldonation.yuejuan_id', '=', '1')
															       ->where('setting_generaldonation.focusdevotee_id', '=', $focus_devotee[0]->devotee_id)
															       ->where('setting_generaldonation.devotee_id', '=', $focus_devotee[0]->devotee_id)
															       ->select('devotee.*', 'familycode.familycode', 'member.paytill_date', 'specialremarks.devotee_id as specialremarks_devotee_id')
															       ->GroupBy('devotee.devotee_id')
															       ->get();

				$result = SettingGeneralDonation::where('focusdevotee_id', $focus_devotee[0]->devotee_id)->get();

			 	if(count($result) > 0)
			 	{
			 		$setting_samefamily = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
			 													->leftjoin('setting_generaldonation', 'setting_generaldonation.devotee_id', '=', 'devotee.devotee_id')
			 													->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
			 													->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
			 													->where('devotee.devotee_id', '!=', $focus_devotee[0]->devotee_id)
			 													->where('devotee.familycode_id', $focus_devotee[0]->familycode_id)
			 													->where('setting_generaldonation.focusdevotee_id', $focus_devotee[0]->devotee_id)
			 													->select('devotee.*', 'member.paytill_date', 'specialremarks.devotee_id as specialremarks_devotee_id', 'familycode.familycode', 'setting_generaldonation.xiangyou_ciji_id', 'setting_generaldonation.yuejuan_id')
			 													->GroupBy('devotee.devotee_id')
			 													->get();

			 	  $nosetting_samefamily = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
			 														->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
			 														->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
			 														->where('devotee.familycode_id', $focus_devotee[0]->familycode_id)
			 														->where('devotee.devotee_id', '!=', $focus_devotee[0]->devotee_id)
			 														->select('devotee.*', 'member.paytill_date', 'specialremarks.devotee_id as specialremarks_devotee_id', 'familycode.familycode')
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
			 													->where('devotee.devotee_id', '!=', $focus_devotee[0]->devotee_id)
			 													->where('devotee.familycode_id', $focus_devotee[0]->familycode_id)
			 													->select('devotee.*', 'member.paytill_date', 'specialremarks.devotee_id as specialremarks_devotee_id', 'familycode.familycode')
			 													->GroupBy('devotee.devotee_id')
			 													->get();

			 		for($i = 0; $i < count($setting_samefamily); $i++)
			 		{
			 			$setting_samefamily[$i]->xiangyou_ciji_id = 0;
			 			$setting_samefamily[$i]->yuejuan_id = 0;
			 		}
			 	}

				$setting = SettingGeneralDonation::where('focusdevotee_id', $focus_devotee[0]->devotee_id)
				           ->where('devotee_id', $focus_devotee[0]->devotee_id)
				           ->get();

				if(count($setting) > 0)
				{
				  $xianyou_focusdevotee = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
				                          ->leftjoin('setting_generaldonation', 'devotee.devotee_id', '=', 'setting_generaldonation.devotee_id')
				                          ->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
				                          ->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
				                          ->where('devotee.devotee_id', $focus_devotee[0]->devotee_id)
				                          ->where('setting_generaldonation.focusdevotee_id', $focus_devotee[0]->devotee_id)
				                          ->select('devotee.*', 'familycode.familycode', 'member.paytill_date', 'specialremarks.devotee_id as specialremarks_devotee_id', 'setting_generaldonation.xiangyou_ciji_id', 'setting_generaldonation.yuejuan_id')
				                          ->GroupBy('devotee.devotee_id')
				                          ->get();
				}

				else
				{
				  $xianyou_focusdevotee = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
				                          ->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
				                          ->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
				                          ->where('devotee.devotee_id', $focus_devotee[0]->devotee_id)
				                          ->select('devotee.*', 'familycode.familycode', 'member.paytill_date', 'specialremarks.devotee_id as specialremarks_devotee_id')
				                          ->get();

				  $xianyou_focusdevotee[0]->xiangyou_ciji_id = 0;
				  $xianyou_focusdevotee[0]->yuejuan_id = 0;
				}

				Session::put('focus_devotee', $focus_devotee);
				Session::put('devotee_lists', $devotee_lists);
				Session::put('xianyou_same_family', $xianyou_same_family);
				Session::put('xianyou_same_focusdevotee', $xianyou_same_focusdevotee);
				Session::put('yuejuan_same_family', $yuejuan_same_family);
				Session::put('yuejuan_same_focusdevotee', $yuejuan_same_focusdevotee);
				Session::put('setting_samefamily', $setting_samefamily);
				Session::put('xianyou_focusdevotee', $xianyou_focusdevotee);

				$request->session()->flash('success', 'Relocation Devotee(s) has been changed!');
			  return redirect()->back();
			}

			else
			{
				$request->session()->flash('error', 'Password did not match. Please Try Again');
		    return redirect()->back()->withInput();
			}
	}

	public function getAutocomplete(Request $request)
	{
		$member = Input::get('term');
		$results = array();

		$queries = Devotee::where('member_id', 'like', '%'.$member.'%')
							 ->whereNotNull('member_id')
							 ->take(5)
							 ->get();

		foreach ($queries as $query)
		{
			$results[] = [
				'id' => $query->member_id,
				'value' => $query->member_id
			];
		}
		return response()->json($results);
	}

	public function getAutocomplete2(Request $request)
	{
		$member = Input::get('term');
		$results = array();

		$queries = Member::where('introduced_by2', 'like', '%'.$member.'%')
							 ->take(5)
							 ->get();

		foreach ($queries as $query)
		{
			$results[] = [
				'id' => $query->member_id,
				'value' => $query->introduced_by2
			];
		}
		return response()->json($results);
	}

	public function getAddressStreet(Request $request)
	{
		$address_street = Input::get('term');
		$results = array();

		$queries = TranslationStreet::where('english', 'like', '%'.$address_street.'%')
							 ->take(5)
							 ->GroupBy('english')
							 ->get();

		foreach ($queries as $query)
		{
			$results[] = [
				'id' => $query->id,
				'value' => $query->english
			];
		}
		return response()->json($results);
	}

	public function getAddressPostal(Request $request)
	{
		$address_postal = Input::get('term');
		$results = array();

		$queries = TranslationStreet::where('address_postal', 'like', '%'.$address_postal.'%')
							 ->take(5)
							 ->get();

		foreach ($queries as $query)
		{
			$results[] = [
				'id' => $query->id,
				'value' => $query->address_postal
			];
		}
		return response()->json($results);
	}

	public function getTranslateAddress(Request $request)
	{
		$address_postal = $_GET['address_postal'];
		// $address_postal = 752357;

		$translate_street = TranslationStreet::where('address_postal', $address_postal)->get();

		return response()->json(array(
			'translate_street' => $translate_street
		));
	}

	public function getPopulateAddressPostal(Request $request)
	{
		$address_houseno = $_GET['address_houseno'];
		$address_street = $_GET['address_street'];

		// $address_houseno = '357B';
		// $address_street = 'Admiralty Drive';

		$address = TranslationStreet::where('address_houseno', $address_houseno)
							 ->where('english', $address_street)
							 ->get();

		return response()->json(array(
			'address' => $address
		));
	}


	public function getAddressTranslate(Request $request)
	{
		$address_street = $_GET['address_street'];

		$address_translate = TranslationStreet::where('english', $address_street)
												 ->get();

		return response()->json(array(
			'address_translate' => $address_translate
		));
	}

	// Search Dialect
	public function getSearchDialect(Request $request)
	{
		$other_dialect = $_GET['other_dialect'];

		$dialect = Dialect::where('dialect_name', $other_dialect)->first();

		return response()->json(array(
			'dialect' => $dialect
		));
	}

	// Search Race
	public function getSearchRace(Request $request)
	{
		$other_race = $_GET['other_race'];

		$race = Race::where('race_name', $other_race)->first();

		return response()->json(array(
			'race' => $race
		));
	}


	// Delete Devotee
	public function deleteDevotee(Request $request, $devotee_id)
	{
		$devotee = Devotee::findorfail($devotee_id);

		if (!$devotee) {
            $request->session()->flash('error', 'Selected Devotee is not found.');
            return redirect()->back();
        }

        $devotee->delete();

        $request->session()->flash('success', 'Devotee account has been deleted!');
        return redirect()->back();
	}
}
