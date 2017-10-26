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
use App\Models\KongdanGeneraldonation;
use App\Models\KongdanReceipt;
use App\Models\SettingGeneralDonation;
use App\Models\SettingKongdan;
use App\Models\SettingXiaozai;
use App\Models\XiaozaiGeneraldonation;
use App\Models\XiaozaiReceipt;
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
        				->select('devotee.*', 'member.paytill_date', 'member.member', 'specialremarks.devotee_id as specialremarks_devotee_id', 'familycode.familycode')
								->orderBy('devotee.devotee_id', 'desc')
								->GroupBy('devotee.devotee_id')
        				->get();

		$members = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
								->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
								->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
								->whereNotNull('devotee.member_id')
								->whereNull('deceased_year')
								->orderBy('devotee_id', 'asc')
        				->select('devotee.*', 'member.paytill_date', 'member.member', 'specialremarks.devotee_id as specialremarks_devotee_id', 'familycode.familycode')
								->orderBy('devotee.member_id', 'desc')
								->GroupBy('devotee.devotee_id')
        				->get();

    $deceased_lists = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
											->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
											->whereNotNull('deceased_year')
											->orderBy('devotee_id', 'desc')
        							->select('devotee.*', 'member.member')
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
							 	'member.member', 'member.introduced_by2', 'member.approved_date', 'member.paytill_date')
							 ->where('devotee.devotee_id', $devotee_id)
							 ->get();

		$devotee_lists = Devotee::join('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
										 ->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
					 		       ->where('devotee.familycode_id', $devotee[0]->familycode_id)
					 		       ->where('devotee_id', '!=', $devotee[0]->devotee_id)
					 		       ->orderBy('devotee_id', 'asc')
					 		       ->select('devotee.*', 'member.member', 'familycode.familycode')
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

		// Xianyou
		$xianyou_same_family = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
                           ->leftjoin('setting_generaldonation', 'devotee.devotee_id', '=', 'setting_generaldonation.devotee_id')
                           ->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
                           ->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
                           ->where('devotee.familycode_id', $devotee[0]->familycode_id)
													 ->where('devotee.devotee_id', '!=', $devotee[0]->devotee_id)
                           ->where('setting_generaldonation.address_code', '=', 'same')
                           ->where('setting_generaldonation.xiangyou_ciji_id', '=', '1')
													 ->where('setting_generaldonation.focusdevotee_id', '=', $devotee[0]->devotee_id)
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

		$xianyou_same_focusdevotee = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
			                           ->leftjoin('setting_generaldonation', 'devotee.devotee_id', '=', 'setting_generaldonation.devotee_id')
			                           ->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
			                           ->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
			                           ->where('setting_generaldonation.address_code', '=', 'same')
			                           ->where('setting_generaldonation.xiangyou_ciji_id', '=', '1')
																 ->where('setting_generaldonation.focusdevotee_id', '=', $devotee[0]->devotee_id)
																 ->where('setting_generaldonation.devotee_id', '=', $devotee[0]->devotee_id)
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

		$xianyou_different_family = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
											          ->leftjoin('setting_generaldonation', 'devotee.devotee_id', '=', 'setting_generaldonation.devotee_id')
											          ->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
											          ->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
											          ->where('devotee.devotee_id', '!=', $devotee[0]->devotee_id)
											          ->where('setting_generaldonation.address_code', '=', 'different')
											          ->where('setting_generaldonation.xiangyou_ciji_id', '=', '1')
											          ->where('setting_generaldonation.focusdevotee_id', '=', $devotee[0]->devotee_id)
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

		// Ciji
		$ciji_same_family = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
                           ->leftjoin('setting_generaldonation', 'devotee.devotee_id', '=', 'setting_generaldonation.devotee_id')
                           ->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
                           ->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
                           ->where('devotee.familycode_id', $devotee[0]->familycode_id)
													 ->where('devotee.devotee_id', '!=', $devotee[0]->devotee_id)
                           ->where('setting_generaldonation.address_code', '=', 'same')
                           ->where('setting_generaldonation.xiangyou_ciji_id', '=', '1')
													 ->where('setting_generaldonation.focusdevotee_id', '=', $devotee[0]->devotee_id)
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

		$ciji_same_focusdevotee = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
			                        ->leftjoin('setting_generaldonation', 'devotee.devotee_id', '=', 'setting_generaldonation.devotee_id')
			                        ->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
			                        ->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
			                        ->where('setting_generaldonation.address_code', '=', 'same')
			                        ->where('setting_generaldonation.xiangyou_ciji_id', '=', '1')
															->where('setting_generaldonation.focusdevotee_id', '=', $devotee[0]->devotee_id)
															->where('setting_generaldonation.devotee_id', '=', $devotee[0]->devotee_id)
			                        ->select('devotee.*', 'member.member', 'familycode.familycode', 'member.paytill_date', 'specialremarks.devotee_id as specialremarks_devotee_id')
															->GroupBy('devotee.devotee_id')
			                        ->get();

		for($i = 0; $i < count($ciji_same_focusdevotee); $i++)
		{
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
		}

		$ciji_different_family = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
											          ->leftjoin('setting_generaldonation', 'devotee.devotee_id', '=', 'setting_generaldonation.devotee_id')
											          ->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
											          ->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
											          ->where('devotee.devotee_id', '!=', $devotee[0]->devotee_id)
											          ->where('setting_generaldonation.address_code', '=', 'different')
											          ->where('setting_generaldonation.xiangyou_ciji_id', '=', '1')
											          ->where('setting_generaldonation.focusdevotee_id', '=', $devotee[0]->devotee_id)
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

		// Yuejuan
		 $yuejuan_same_family = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
														->leftjoin('setting_generaldonation', 'devotee.devotee_id', '=', 'setting_generaldonation.devotee_id')
														->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
														->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
														->where('devotee.familycode_id', $devotee[0]->familycode_id)
														->where('devotee.devotee_id', '!=', $devotee[0]->devotee_id)
														->where('setting_generaldonation.address_code', '=', 'same')
														->where('setting_generaldonation.yuejuan_id', '=', '1')
														->where('setting_generaldonation.focusdevotee_id', '=', $devotee[0]->devotee_id)
														->select('devotee.*', 'member.member', 'familycode.familycode', 'member.paytill_date', 'specialremarks.devotee_id as specialremarks_devotee_id')
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
																 ->where('setting_generaldonation.focusdevotee_id', '=', $devotee[0]->devotee_id)
																 ->where('setting_generaldonation.devotee_id', '=', $devotee[0]->devotee_id)
																 ->select('devotee.*', 'member.member', 'familycode.familycode', 'member.paytill_date', 'specialremarks.devotee_id as specialremarks_devotee_id')
																 ->GroupBy('devotee.devotee_id')
																 ->get();

		for($i = 0; $i < count($yuejuan_same_focusdevotee); $i++)
		{
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
		}

		$yuejuan_different_family = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
																->leftjoin('setting_generaldonation', 'devotee.devotee_id', '=', 'setting_generaldonation.devotee_id')
																->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
																->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
																->where('devotee.devotee_id', '!=', $devotee[0]->devotee_id)
																->where('setting_generaldonation.address_code', '=', 'different')
																->where('setting_generaldonation.yuejuan_id', '=', '1')
																->where('setting_generaldonation.focusdevotee_id', '=', $devotee[0]->devotee_id)
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

		// Kongdan
		$kongdan_same_family = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
                           ->leftjoin('setting_kongdan', 'devotee.devotee_id', '=', 'setting_kongdan.devotee_id')
                           ->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
                           ->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
                           ->where('devotee.familycode_id', $devotee[0]->familycode_id)
													 ->where('devotee.devotee_id', '!=', $devotee[0]->devotee_id)
                           ->where('setting_kongdan.address_code', '=', 'same')
                           ->where('setting_kongdan.kongdan_id', '=', '1')
													 ->where('setting_kongdan.focusdevotee_id', '=', $devotee[0]->devotee_id)
                           ->select('devotee.*', 'member.member', 'familycode.familycode', 'member.paytill_date', 'specialremarks.devotee_id as specialremarks_devotee_id')
													 ->GroupBy('devotee.devotee_id')
                           ->get();

		for($i = 0; $i < count($kongdan_same_family); $i++)
		{
			$hasreceipt = KongdanReceipt::where('devotee_id', $kongdan_same_family[$i]->devotee_id)->get();

			if(count($hasreceipt) > 0)
			{
				$same_xy_receipt = KongdanReceipt::all()
													 ->where('devotee_id', $kongdan_same_family[$i]->devotee_id)
													 ->last()
													 ->xy_receipt;

				$kongdan_same_family[$i]->xyreceipt = $same_xy_receipt;
			}

			else {
				$kongdan_same_family[$i]->xyreceipt = "";
			}
		}

		$kongdan_same_focusdevotee = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
			                           ->leftjoin('setting_kongdan', 'devotee.devotee_id', '=', 'setting_kongdan.devotee_id')
			                           ->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
			                           ->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
			                           ->where('setting_kongdan.address_code', '=', 'same')
			                           ->where('setting_kongdan.kongdan_id', '=', '1')
																 ->where('setting_kongdan.focusdevotee_id', '=', $devotee[0]->devotee_id)
																 ->where('setting_kongdan.devotee_id', '=', $devotee[0]->devotee_id)
			                           ->select('devotee.*', 'member.member', 'familycode.familycode', 'member.paytill_date', 'specialremarks.devotee_id as specialremarks_devotee_id')
																 ->GroupBy('devotee.devotee_id')
			                           ->get();

		for($i = 0; $i < count($kongdan_same_focusdevotee); $i++)
		{
			$hasreceipt = KongdanReceipt::where('devotee_id', $kongdan_same_focusdevotee[0]->devotee_id)->get();

			if(count($hasreceipt) > 0)
			{
				$same_xy_receipt = KongdanReceipt::all()
													 ->where('devotee_id', $kongdan_same_focusdevotee[0]->devotee_id)
													 ->last()
													 ->xy_receipt;

				$kongdan_same_focusdevotee[0]->xyreceipt = $same_xy_receipt;
			}

			else {
				$kongdan_same_focusdevotee[0]->xyreceipt = "";
			}
		}

		$kongdan_different_family = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
											          ->leftjoin('setting_kongdan', 'devotee.devotee_id', '=', 'setting_kongdan.devotee_id')
											          ->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
											          ->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
											          ->where('devotee.devotee_id', '!=', $devotee[0]->devotee_id)
											          ->where('setting_kongdan.address_code', '=', 'different')
											          ->where('setting_kongdan.kongdan_id', '=', '1')
											          ->where('setting_kongdan.focusdevotee_id', '=', $devotee[0]->devotee_id)
											          ->select('devotee.*', 'member.member', 'member.paytill_date', 'specialremarks.devotee_id as specialremarks_devotee_id', 'familycode.familycode')
											          ->GroupBy('devotee.devotee_id')
											          ->get();

		for($i = 0; $i < count($kongdan_different_family); $i++)
		{
			$hasreceipt = KongdanReceipt::where('devotee_id', $kongdan_different_family[$i]->devotee_id)->get();

			if(count($hasreceipt) > 0)
			{
				$same_xy_receipt = KongdanReceipt::all()
													 ->where('devotee_id', $kongdan_different_family[$i]->devotee_id)
													 ->last()
													 ->xy_receipt;

				$kongdan_different_family[$i]->xyreceipt = $same_xy_receipt;
			}

			else {
				$kongdan_different_family[$i]->xyreceipt = "";
			}
		}

		// Xiaozai
		$xiaozai_same_focusdevotee = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
			                           ->leftjoin('setting_xiaozai', 'devotee.devotee_id', '=', 'setting_xiaozai.devotee_id')
			                           ->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
			                           ->where('setting_xiaozai.address_code', '=', 'same')
			                           ->where('setting_xiaozai.xiaozai_id', '=', '1')
																 ->where('setting_xiaozai.focusdevotee_id', '=', $devotee[0]->devotee_id)
																 ->where('setting_xiaozai.devotee_id', '=', $devotee[0]->devotee_id)
																 ->where('setting_xiaozai.year', null)
			                           ->select('devotee.*', 'member.member', 'familycode.familycode', 'member.paytill_date', 'setting_xiaozai.type')
			                           ->get();

		for($i = 0; $i < count($xiaozai_same_focusdevotee); $i++)
		{
			if($xiaozai_same_focusdevotee[$i]->type == 'car' || $xiaozai_same_focusdevotee[$i]->type == 'ship')
			{
				$result = OptionalVehicle::where('devotee_id', $devotee[0]->devotee_id)
									->where('type', $xiaozai_same_focusdevotee[$i]->type)
									->pluck('data');

				$xiaozai_same_focusdevotee[$i]->item_description = $result[0];
			}

			elseif($xiaozai_same_focusdevotee[$i]->type == 'home' || $xiaozai_same_focusdevotee[$i]->type == 'company'
						|| $xiaozai_same_focusdevotee[$i]->type == 'stall' || $xiaozai_same_focusdevotee[$i]->type == 'office')
			{
				$result = OptionalAddress::where('devotee_id', $devotee[0]->devotee_id)
									->where('type', $xiaozai_same_focusdevotee[$i]->type)
									->get();

				if(isset($result[0]->address_translated))
				{
					$xiaozai_same_focusdevotee[$i]->item_description = $result[0]->address_translated;
				}
				else
				{
					$xiaozai_same_focusdevotee[$i]->item_description = $result[0]->oversea_address;
				}
			}

			else
			{
				$result = Devotee::find($devotee[0]->devotee_id);

				if(isset($result->oversea_addr_in_chinese))
				{
					$xiaozai_same_focusdevotee[$i]->item_description = $result->oversea_addr_in_chinese;
				}
				elseif (isset($result->address_unit1) && isset($result->address_unit2))
				{
					$xiaozai_same_focusdevotee[$i]->item_description = $result->address_houseno . "#" . $result->address_unit1 . '-' .
																															$result->address_unit2 . ", " . $result->address_street . ", " . $result->address_postal;
				}

				else
				{
					$xiaozai_same_focusdevotee[$i]->item_description = $result->address_houseno . ", " . $result->address_street . ", " . $result->address_postal;
				}
			}
		}

		for($i = 0; $i < count($xiaozai_same_focusdevotee); $i++)
		{
			$hasreceipt = XiaozaiReceipt::where('devotee_id', $xiaozai_same_focusdevotee[$i]->devotee_id)
										->where('type', $xiaozai_same_focusdevotee[$i]->type)
										->get();

			if(count($hasreceipt) > 0)
			{
				$same_xy_receipt = XiaozaiReceipt::all()
													 ->where('devotee_id', $xiaozai_same_focusdevotee[$i]->devotee_id)
													 ->where('type', $xiaozai_same_focusdevotee[$i]->type)
													 ->last()
													 ->receipt_no;

				$xiaozai_same_focusdevotee[$i]->receipt_no = $same_xy_receipt;
			}

			else {
				$xiaozai_same_focusdevotee[$i]->receipt_no = "";
			}
		}

		// Xiangyou Setting Same family
		$result = SettingGeneralDonation::where('focusdevotee_id', $devotee[0]->devotee_id)->get();

		if(count($result) > 0)
		{
			$setting_samefamily = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
														->leftjoin('setting_generaldonation', 'setting_generaldonation.devotee_id', '=', 'devotee.devotee_id')
														->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
														->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
														->where('devotee.devotee_id', '!=', $devotee[0]->devotee_id)
														->where('devotee.familycode_id', $devotee[0]->familycode_id)
														->where('setting_generaldonation.focusdevotee_id', $devotee[0]->devotee_id)
														->select('devotee.*', 'member.member', 'member.paytill_date', 'specialremarks.devotee_id as specialremarks_devotee_id', 'familycode.familycode', 'setting_generaldonation.xiangyou_ciji_id', 'setting_generaldonation.yuejuan_id')
														->GroupBy('devotee.devotee_id')
														->get();

			$nosetting_samefamily = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
															->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
															->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
															->where('devotee.familycode_id', $devotee[0]->familycode_id)
															->where('devotee.devotee_id', '!=', $devotee[0]->devotee_id)
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
														->where('devotee.devotee_id', '!=', $devotee[0]->devotee_id)
														->where('devotee.familycode_id', $devotee[0]->familycode_id)
														->select('devotee.*', 'member.member', 'member.paytill_date', 'specialremarks.devotee_id as specialremarks_devotee_id', 'familycode.familycode')
														->GroupBy('devotee.devotee_id')
														->get();

			 for($i = 0; $i < count($setting_samefamily); $i++)
			 {
				 $setting_samefamily[$i]->xiangyou_ciji_id = 0;
				 $setting_samefamily[$i]->yuejuan_id = 0;
			 }
		}

		// Setting Xiangyou Ciji Yuejuan focusdevotee
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
															->select('devotee.*', 'member.member', 'familycode.familycode', 'member.paytill_date', 'specialremarks.devotee_id as specialremarks_devotee_id', 'setting_generaldonation.xiangyou_ciji_id', 'setting_generaldonation.yuejuan_id')
															->GroupBy('devotee.devotee_id')
												     	->get();
		}

		else
		{
			$xianyou_focusdevotee = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
															->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
															->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
															->where('devotee.devotee_id', $devotee[0]->devotee_id)
															->select('devotee.*', 'familycode.familycode', 'member.member', 'member.paytill_date', 'specialremarks.devotee_id as specialremarks_devotee_id')
												     	->get();

			$xianyou_focusdevotee[0]->xiangyou_ciji_id = 0;
			$xianyou_focusdevotee[0]->yuejuan_id = 0;
		}

		// Fahui Kongdan Setting Same family
		$kongdan_result = SettingKongdan::where('focusdevotee_id', $devotee[0]->devotee_id)->get();

		if(count($kongdan_result) > 0)
		{
			$kongdan_setting_samefamily = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
																		->leftjoin('setting_kongdan', 'setting_kongdan.devotee_id', '=', 'devotee.devotee_id')
																		->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
																		->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
																		->where('devotee.devotee_id', '!=', $devotee[0]->devotee_id)
																		->where('devotee.familycode_id', $devotee[0]->familycode_id)
																		->where('setting_kongdan.focusdevotee_id', $devotee[0]->devotee_id)
																		->where('setting_kongdan.address_code', '=', 'same')
																		->where('setting_kongdan.year', null)
																		->select('devotee.*', 'member.member', 'member.paytill_date', 'specialremarks.devotee_id as specialremarks_devotee_id', 'familycode.familycode', 'setting_kongdan.kongdan_id')
																		->GroupBy('devotee.devotee_id')
																		->get();

			$kongdan_nosetting_samefamily = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
																			->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
																			->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
																			->where('devotee.familycode_id', $devotee[0]->familycode_id)
																			->where('devotee.devotee_id', '!=', $devotee[0]->devotee_id)
																			->select('devotee.*', 'member.member', 'member.paytill_date', 'specialremarks.devotee_id as specialremarks_devotee_id', 'familycode.familycode')
																			->GroupBy('devotee.devotee_id')
																			->get();

			if(count($kongdan_nosetting_samefamily) > 0)
			{
				for($i = 0; $i < count($kongdan_nosetting_samefamily); $i++)
				{
					$kongdan_nosetting_samefamily[$i]->kongdan_id = 0;
				}

				$kongdan_setting_samefamily = $kongdan_nosetting_samefamily->merge($kongdan_setting_samefamily);
			}
		}

		else
		{
			$kongdan_setting_samefamily = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
																		->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
																		->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
																		->where('devotee.devotee_id', '!=', $devotee[0]->devotee_id)
																		->where('devotee.familycode_id', $devotee[0]->familycode_id)
																		->select('devotee.*', 'member.member', 'member.paytill_date', 'specialremarks.devotee_id as specialremarks_devotee_id', 'familycode.familycode')
																		->GroupBy('devotee.devotee_id')
																		->get();

			for($i = 0; $i < count($kongdan_setting_samefamily); $i++)
			{
				$kongdan_setting_samefamily[$i]->kongdan_id = 0;
			}
		}

		$kongdan_setting_differentfamily = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
															         ->leftjoin('setting_kongdan', 'setting_kongdan.devotee_id', '=', 'devotee.devotee_id')
															         ->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
															         ->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
															         ->where('setting_kongdan.focusdevotee_id', '=', $devotee[0]->devotee_id)
															         ->where('setting_kongdan.address_code', '=', 'different')
																			 ->where('setting_kongdan.year', null)
															         ->select('devotee.*', 'member.member', 'member.paytill_date', 'specialremarks.devotee_id as specialremarks_devotee_id', 'familycode.familycode', 'setting_kongdan.kongdan_id')
															         ->GroupBy('devotee.devotee_id')
															         ->get();

			$this_year = date("Y");

			$kongdan_setting_samefamily_last1year = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
																							->leftjoin('setting_kongdan', 'setting_kongdan.devotee_id', '=', 'devotee.devotee_id')
																							->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
																							->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
																							->where('setting_kongdan.focusdevotee_id', '=', $devotee[0]->devotee_id)
																							->where('setting_kongdan.address_code', '=', 'same')
																							->where('setting_kongdan.year', $this_year - 1)
																							->select('devotee.*', 'member.member', 'member.paytill_date', 'specialremarks.devotee_id as specialremarks_devotee_id', 'familycode.familycode', 'setting_kongdan.kongdan_id')
																							->GroupBy('devotee.devotee_id')
																							->get();

			$kongdan_setting_samefamily_last2year = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
																							->leftjoin('setting_kongdan', 'setting_kongdan.devotee_id', '=', 'devotee.devotee_id')
																							->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
																							->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
																							->where('setting_kongdan.focusdevotee_id', '=', $devotee[0]->devotee_id)
																							->where('setting_kongdan.address_code', '=', 'same')
																							->where('setting_kongdan.year', $this_year - 2)
																							->select('devotee.*', 'member.member', 'member.paytill_date', 'specialremarks.devotee_id as specialremarks_devotee_id', 'familycode.familycode', 'setting_kongdan.kongdan_id')
																							->GroupBy('devotee.devotee_id')
																							->get();

			$kongdan_setting_samefamily_last3year = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
																							->leftjoin('setting_kongdan', 'setting_kongdan.devotee_id', '=', 'devotee.devotee_id')
																							->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
																							->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
																							->where('setting_kongdan.focusdevotee_id', '=', $devotee[0]->devotee_id)
																							->where('setting_kongdan.address_code', '=', 'same')
																							->where('setting_kongdan.year', $this_year - 3)
																							->select('devotee.*', 'member.member', 'member.paytill_date', 'specialremarks.devotee_id as specialremarks_devotee_id', 'familycode.familycode', 'setting_kongdan.kongdan_id')
																							->GroupBy('devotee.devotee_id')
																							->get();

			$kongdan_setting_samefamily_last4year = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
																							->leftjoin('setting_kongdan', 'setting_kongdan.devotee_id', '=', 'devotee.devotee_id')
																							->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
																							->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
																							->where('setting_kongdan.focusdevotee_id', '=', $devotee[0]->devotee_id)
																							->where('setting_kongdan.address_code', '=', 'same')
																							->where('setting_kongdan.year', $this_year - 4)
																							->select('devotee.*', 'member.member', 'member.paytill_date', 'specialremarks.devotee_id as specialremarks_devotee_id', 'familycode.familycode', 'setting_kongdan.kongdan_id')
																							->GroupBy('devotee.devotee_id')
																							->get();

			$kongdan_setting_samefamily_last5year = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
																							->leftjoin('setting_kongdan', 'setting_kongdan.devotee_id', '=', 'devotee.devotee_id')
																							->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
																							->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
																							->where('setting_kongdan.focusdevotee_id', '=', $devotee[0]->devotee_id)
																							->where('setting_kongdan.address_code', '=', 'different')
																							->where('setting_kongdan.year', $this_year - 5)
																							->select('devotee.*', 'member.member', 'member.paytill_date', 'specialremarks.devotee_id as specialremarks_devotee_id', 'familycode.familycode', 'setting_kongdan.kongdan_id')
																							->GroupBy('devotee.devotee_id')
																							->get();

			$kongdan_setting_differentfamily_last1year = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
																								   ->leftjoin('setting_kongdan', 'setting_kongdan.devotee_id', '=', 'devotee.devotee_id')
																								   ->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
																								   ->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
																								   ->where('setting_kongdan.focusdevotee_id', '=', $devotee[0]->devotee_id)
																								   ->where('setting_kongdan.address_code', '=', 'different')
																									 ->where('setting_kongdan.year', $this_year - 1)
																								   ->select('devotee.*', 'member.member', 'member.paytill_date', 'specialremarks.devotee_id as specialremarks_devotee_id', 'familycode.familycode', 'setting_kongdan.kongdan_id')
																								   ->GroupBy('devotee.devotee_id')
																								   ->get();

			$kongdan_setting_differentfamily_last2year = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
																								   ->leftjoin('setting_kongdan', 'setting_kongdan.devotee_id', '=', 'devotee.devotee_id')
																								   ->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
																								   ->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
																								   ->where('setting_kongdan.focusdevotee_id', '=', $devotee[0]->devotee_id)
																								   ->where('setting_kongdan.address_code', '=', 'different')
																									 ->where('setting_kongdan.year', $this_year - 2)
																								   ->select('devotee.*', 'member.member', 'member.paytill_date', 'specialremarks.devotee_id as specialremarks_devotee_id', 'familycode.familycode', 'setting_kongdan.kongdan_id')
																								   ->GroupBy('devotee.devotee_id')
																								   ->get();

			$kongdan_setting_differentfamily_last3year = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
																								   ->leftjoin('setting_kongdan', 'setting_kongdan.devotee_id', '=', 'devotee.devotee_id')
																								   ->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
																								   ->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
																								   ->where('setting_kongdan.focusdevotee_id', '=', $devotee[0]->devotee_id)
																								   ->where('setting_kongdan.address_code', '=', 'different')
																									 ->where('setting_kongdan.year', $this_year - 3)
																								   ->select('devotee.*', 'member.member', 'member.paytill_date', 'specialremarks.devotee_id as specialremarks_devotee_id', 'familycode.familycode', 'setting_kongdan.kongdan_id')
																								   ->GroupBy('devotee.devotee_id')
																								   ->get();

			$kongdan_setting_differentfamily_last4year = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
																								   ->leftjoin('setting_kongdan', 'setting_kongdan.devotee_id', '=', 'devotee.devotee_id')
																								   ->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
																								   ->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
																								   ->where('setting_kongdan.focusdevotee_id', '=', $devotee[0]->devotee_id)
																								   ->where('setting_kongdan.address_code', '=', 'different')
																									 ->where('setting_kongdan.year', $this_year - 4)
																								   ->select('devotee.*', 'member.member', 'member.paytill_date', 'specialremarks.devotee_id as specialremarks_devotee_id', 'familycode.familycode', 'setting_kongdan.kongdan_id')
																								   ->GroupBy('devotee.devotee_id')
																								   ->get();

		$kongdan_setting_differentfamily_last5year = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
																									->leftjoin('setting_kongdan', 'setting_kongdan.devotee_id', '=', 'devotee.devotee_id')
																									->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
																									->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
																									->where('setting_kongdan.focusdevotee_id', '=', $devotee[0]->devotee_id)
																									->where('setting_kongdan.address_code', '=', 'different')
																									->where('setting_kongdan.year', $this_year - 5)
																									->select('devotee.*', 'member.member', 'member.paytill_date', 'specialremarks.devotee_id as specialremarks_devotee_id', 'familycode.familycode', 'setting_kongdan.kongdan_id')
																									->GroupBy('devotee.devotee_id')
																									->get();

		// Setting Kongdan focusdevotee
		$setting_kongdan = SettingKongdan::where('focusdevotee_id', $devotee[0]->devotee_id)
											 ->where('devotee_id', $devotee[0]->devotee_id)
											 ->get();

		if(count($setting_kongdan) > 0)
		{
			$kongdan_focusdevotee = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
															->leftjoin('setting_kongdan', 'devotee.devotee_id', '=', 'setting_kongdan.devotee_id')
															->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
															->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
															->where('devotee.devotee_id', $devotee[0]->devotee_id)
															->where('setting_kongdan.focusdevotee_id', $devotee[0]->devotee_id)
															->where('setting_kongdan.year', null)
															->select('devotee.*', 'member.member', 'familycode.familycode', 'member.paytill_date', 'specialremarks.devotee_id as specialremarks_devotee_id', 'setting_kongdan.kongdan_id')
															->GroupBy('devotee.devotee_id')
															->get();
		}

		else
		{
			$kongdan_focusdevotee = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
															->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
															->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
															->where('devotee.devotee_id', $devotee[0]->devotee_id)
															->select('devotee.*', 'familycode.familycode', 'member.member', 'member.paytill_date', 'specialremarks.devotee_id as specialremarks_devotee_id')
												     	->get();

			$kongdan_focusdevotee[0]->kongdan_id = 0;
		}

		// Setting Xiaozai focusdevotee
		$setting_xiaozai = SettingXiaozai::where('focusdevotee_id', $devotee[0]->devotee_id)
											 ->where('devotee_id', $devotee[0]->devotee_id)
											 ->get();

		$xiaozai_focusdevotee_collection = collect();

		if(count($setting_xiaozai) > 0)
		{
			$xiaozai_focusdevotee = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
															->leftjoin('setting_xiaozai', 'devotee.devotee_id', '=', 'setting_xiaozai.devotee_id')
															->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
															->where('devotee.devotee_id', $devotee[0]->devotee_id)
															->where('setting_xiaozai.focusdevotee_id', $devotee[0]->devotee_id)
															->where('setting_xiaozai.year', null)
															->select('devotee.*', 'member.member', 'familycode.familycode', 'member.paytill_date', 'setting_xiaozai.xiaozai_id',
																'setting_xiaozai.type')
															->get();

			$xiaozai_focusdevotee[0]->ops = "";

			$oa_count = 1;
	    $ov_count = 1;

	    for($i = 0; $i < count($xiaozai_focusdevotee); $i++)
			{
				if($xiaozai_focusdevotee[$i]->type == 'car' || $xiaozai_focusdevotee[$i]->type == 'ship')
				{
					$result = OptionalVehicle::where('devotee_id', $devotee[0]->devotee_id)
										->where('type', $xiaozai_focusdevotee[$i]->type)
										->pluck('data');

					$xiaozai_focusdevotee[$i]->item_description = $result[0];
	        $xiaozai_focusdevotee[$i]->ops = "OV#" . $ov_count;

	        $ov_count++;
				}

				elseif($xiaozai_focusdevotee[$i]->type == 'home' || $xiaozai_focusdevotee[$i]->type == 'company'
							|| $xiaozai_focusdevotee[$i]->type == 'stall' || $xiaozai_focusdevotee[$i]->type == 'office')
				{
					$result = OptionalAddress::where('devotee_id', $devotee[0]->devotee_id)
										->where('type', $xiaozai_focusdevotee[$i]->type)
										->get();

					if(isset($result[0]->address_translated))
					{
						$xiaozai_focusdevotee[$i]->item_description = $result[0]->address_translated;
	          $xiaozai_focusdevotee[$i]->ops = "OA#" . $oa_count;
					}
					else
					{
						$xiaozai_focusdevotee[$i]->item_description = $result[0]->oversea_address;
	          $xiaozai_focusdevotee[$i]->ops = "OA#" . $oa_count;
					}

	        $oa_count++;
				}

				else
				{
					$result = Devotee::find($devotee[0]->devotee_id);

					if(isset($result->oversea_addr_in_chinese))
					{
						$xiaozai_focusdevotee[$i]->item_description = $result->oversea_addr_in_chinese;
					}
					elseif (isset($result->address_unit1) && isset($result->address_unit2))
					{
						$xiaozai_focusdevotee[$i]->item_description = $result->address_houseno . "#" . $result->address_unit1 . '-' .
																												 	$result->address_unit2 . ", " . $result->address_street . ", " . $result->address_postal;
					}

					else
					{
						$xiaozai_focusdevotee[$i]->item_description = $result->address_houseno . ", " . $result->address_street . ", " . $result->address_postal;
					}
				}
			}
		}

		else
		{
			$xiaozai_focusdevotee = Devotee::leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
															->leftjoin('familycode', 'familycode.familycode_id' , '=', 'devotee.familycode_id')
															->select('devotee.*', 'member.paytill_date', 'familycode.familycode')
															->where('devotee.devotee_id', $devotee_id)
															->get();

			$xiaozai_focusdevotee[0]->type = "sameaddress";
	    $xiaozai_focusdevotee[0]->ops = "";

			$xiaozai_focusdevotee_collection = $xiaozai_focusdevotee_collection->merge($xiaozai_focusdevotee);

			$optionaladdress_devotee = Devotee::leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
	                							 ->leftjoin('familycode', 'familycode.familycode_id' , '=', 'devotee.familycode_id')
	                               ->leftjoin('optionaladdress', 'devotee.devotee_id', '=', 'optionaladdress.devotee_id')
	                							 ->select('devotee.*', 'member.paytill_date', 'familycode.familycode', 'optionaladdress.type')
	                							 ->where('devotee.devotee_id', $devotee_id)
	                							 ->get();

	    $optionalvehicle_devotee = Devotee::leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
	                							 ->leftjoin('familycode', 'familycode.familycode_id' , '=', 'devotee.familycode_id')
	                               ->leftjoin('optionalvehicle', 'devotee.devotee_id', '=', 'optionalvehicle.devotee_id')
	                							 ->select('devotee.*', 'member.paytill_date', 'familycode.familycode', 'optionalvehicle.type')
	                							 ->where('devotee.devotee_id', $devotee_id)
	                							 ->get();

			if(isset($optionaladdress_devotee[0]->type))
			{
				$xiaozai_focusdevotee_collection = $xiaozai_focusdevotee_collection->merge($optionaladdress_devotee);
			}

			if(isset($optionalvehicle_devotee[0]->type))
			{
				$xiaozai_focusdevotee_collection = $xiaozai_focusdevotee_collection->merge($optionalvehicle_devotee);
			}

			$oa_count = 1;
	    $ov_count = 1;

	    for($i = 0; $i < count($xiaozai_focusdevotee_collection); $i++)
			{
				if($xiaozai_focusdevotee_collection[$i]->type == 'car' || $xiaozai_focusdevotee_collection[$i]->type == 'ship')
				{
					$result = OptionalVehicle::where('devotee_id', $xiaozai_focusdevotee_collection[$i]->devotee_id)
										->where('type', $xiaozai_focusdevotee_collection[$i]->type)
										->pluck('data');

	        $xiaozai_focusdevotee_collection[$i]->ops = "OV#" . $ov_count;
					$xiaozai_focusdevotee_collection[$i]->item_description = $result[0];
					$xiaozai_focusdevotee_collection[$i]->xiaozai_id = 0;

	        $ov_count++;
				}

				elseif($xiaozai_focusdevotee_collection[$i]->type == 'home' || $xiaozai_focusdevotee_collection[$i]->type == 'company'
							|| $xiaozai_focusdevotee_collection[$i]->type == 'stall' || $xiaozai_focusdevotee_collection[$i]->type == 'office')
				{
					$result = OptionalAddress::where('devotee_id', $devotee[0]->devotee_id)
										->where('type', $xiaozai_focusdevotee_collection[$i]->type)
										->get();

					if(isset($result[0]->address_translated))
					{
	          $xiaozai_focusdevotee_collection[$i]->ops = "OA#" . $oa_count;
						$xiaozai_focusdevotee_collection[$i]->item_description = $result[0]->address_translated;
						$xiaozai_focusdevotee_collection[$i]->xiaozai_id = 0;
					}
					else
					{
	          $xiaozai_focusdevotee_collection[$i]->ops = "OA#" . $oa_count;
						$xiaozai_focusdevotee_collection[$i]->item_description = $result[0]->oversea_address;
						$xiaozai_focusdevotee_collection[$i]->xiaozai_id = 0;
					}

	        $oa_count++;
				}

				else
				{
					$result = Devotee::find($devotee[0]->devotee_id);

					if(isset($result->oversea_addr_in_chinese))
					{
						$xiaozai_focusdevotee_collection[$i]->item_description = $result->oversea_addr_in_chinese;
						$xiaozai_focusdevotee_collection[$i]->xiaozai_id = 0;
					}
					elseif (isset($result->address_unit1) && isset($result->address_unit2))
					{
						$xiaozai_focusdevotee_collection[$i]->item_description = $result->address_houseno . "#" . $result->address_unit1 . '-' .
																																 				$result->address_unit2 . ", " . $result->address_street . ", " . $result->address_postal;
					 	$xiaozai_focusdevotee_collection[$i]->xiaozai_id = 0;
					}

					else
					{
						$xiaozai_focusdevotee_collection[$i]->item_description = $result->address_houseno . ", " . $result->address_street . ", " . $result->address_postal;
						$xiaozai_focusdevotee_collection[$i]->xiaozai_id = 0;
					}
				}
			}

			$xiaozai_focusdevotee = $xiaozai_focusdevotee_collection;
		}

		// Xiaozai Different Family
		$xiaozai_different_family = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
		                            ->leftjoin('setting_xiaozai', 'devotee.devotee_id', '=', 'setting_xiaozai.devotee_id')
		                            ->where('setting_xiaozai.address_code', '=', 'different')
		                            ->where('setting_xiaozai.xiaozai_id', '=', '1')
		                            ->where('setting_xiaozai.focusdevotee_id', '=', $devotee_id)
		                            ->where('year', null)
		                            ->select('devotee.*', 'familycode.familycode', 'setting_xiaozai.type')
		                            ->get();

		$oa_count = 1;
		$ov_count = 1;

		for($i = 0; $i < count($xiaozai_different_family); $i++)
		{
		  if($xiaozai_different_family[$i]->type == 'car' || $xiaozai_different_family[$i]->type == 'ship')
		  {
		    $result = OptionalVehicle::where('devotee_id', $xiaozai_different_family[$i]->devotee_id)
		              ->where('type', $xiaozai_different_family[$i]->type)
		              ->pluck('data');

		    $xiaozai_different_family[$i]->item_description = $result[0];
		    $xiaozai_different_family[$i]->ops = "OV#" . $ov_count;

		    $ov_count++;
		  }

		  elseif($xiaozai_different_family[$i]->type == 'home' || $xiaozai_different_family[$i]->type == 'company'
		        || $xiaozai_different_family[$i]->type == 'stall' || $xiaozai_different_family[$i]->type == 'office')
		  {
		    $result = OptionalAddress::where('devotee_id', $xiaozai_different_family[$i]->devotee_id)
		              ->where('type', $xiaozai_different_family[$i]->type)
		              ->get();

		    if(isset($result[0]->address_translated))
		    {
		      $xiaozai_different_family[$i]->item_description = $result[0]->address_translated;
		      $xiaozai_different_family[$i]->ops = "OA#" . $oa_count;
		    }
		    else
		    {
		      $xiaozai_different_family[$i]->item_description = $result[0]->oversea_address;
		      $xiaozai_different_family[$i]->ops = "OA#" . $oa_count;
		    }

		    $oa_count++;
		  }

		  else
		  {
		    $result = Devotee::find($devotee_id);

		    if(isset($result->oversea_addr_in_chinese))
		    {
		      $xiaozai_different_family[$i]->item_description = $result[0]->oversea_addr_in_chinese;
		      $xiaozai_different_family[$i]->ops = "OA#" . $ops_count;
		    }
		    elseif (isset($result->address_unit1) && isset($result->address_unit2))
		    {
		      $xiaozai_different_family[$i]->item_description = $result->address_houseno . "#" . $result->address_unit1 . '-' .
		                                                        $result->address_unit2 . ", " . $result->address_street . ", " . $result->address_postal;
		    }

		    else
		    {
		      $xiaozai_different_family[$i]->item_description = $result->address_houseno . ", " . $result->address_street . ", " . $result->address_postal;
		    }
		  }
		}

		for($i = 0; $i < count($xiaozai_different_family); $i++)
		{
			$hasreceipt = XiaozaiReceipt::where('devotee_id', $xiaozai_different_family[$i]->devotee_id)
										->where('type', $xiaozai_different_family[$i]->type)
										->get();

			if(count($hasreceipt) > 0)
			{
				$same_xy_receipt = XiaozaiReceipt::all()
													 ->where('devotee_id', $xiaozai_different_family[$i]->devotee_id)
													 ->where('type', $xiaozai_different_family[$i]->type)
													 ->last()
													 ->receipt_no;

				$xiaozai_different_family[$i]->receipt_no = $same_xy_receipt;
			}

			else {
				$xiaozai_different_family[$i]->receipt_no = "";
			}
		}

		$xiaozai_setting_samefamily_collection = collect();

		//Fahui Xiaozai Setting Same family
		$xiaozai_result = SettingXiaozai::where('focusdevotee_id', $devotee[0]->devotee_id)->get();

		if(count($xiaozai_result) > 0)
		{
			$xiaozai_setting_samefamily = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
																		->leftjoin('setting_xiaozai', 'setting_xiaozai.devotee_id', '=', 'devotee.devotee_id')
																		->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
																		->where('devotee.devotee_id', '!=', $devotee[0]->devotee_id)
																		->where('devotee.familycode_id', $devotee[0]->familycode_id)
																		->where('setting_xiaozai.focusdevotee_id', $devotee[0]->devotee_id)
																		->where('setting_xiaozai.address_code', '=', 'same')
																		->where('setting_xiaozai.year', null)
																		->select('devotee.*', 'member.member', 'member.paytill_date', 'familycode.familycode', 'setting_xiaozai.xiaozai_id', 'setting_xiaozai.type')
																		->get();

			$xiaozai_nosetting_devotee = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
																	 ->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
																	 ->where('devotee.familycode_id', $devotee[0]->familycode_id)
																	 ->where('devotee.devotee_id', '!=', $devotee[0]->devotee_id)
																	 ->select('devotee.*', 'member.member', 'member.paytill_date', 'familycode.familycode')
																	 ->get();

			for($i = 0; $i < count($xiaozai_nosetting_devotee); $i++)
			{
				$xiaozai_nosetting_devotee[$i]->type = "sameaddress";
			}

			$xiaozai_setting_samefamily_collection = $xiaozai_setting_samefamily_collection->merge($xiaozai_nosetting_devotee);

			$xiaozai_optionaladdress = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
																 ->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
																 ->leftjoin('optionaladdress', 'devotee.devotee_id', '=', 'optionaladdress.devotee_id')
																 ->where('devotee.familycode_id', $devotee[0]->familycode_id)
																 ->where('devotee.devotee_id', '!=', $devotee[0]->devotee_id)
																 ->select('devotee.*', 'member.member', 'member.paytill_date', 'familycode.familycode', 'optionaladdress.type')
																 ->get();

			$xiaozai_setting_samefamily_collection = $xiaozai_setting_samefamily_collection->merge($xiaozai_optionaladdress);

	    $xiaozai_optionalvehicle = Devotee::leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
	                							 ->leftjoin('familycode', 'familycode.familycode_id' , '=', 'devotee.familycode_id')
	                               ->leftjoin('optionalvehicle', 'devotee.devotee_id', '=', 'optionalvehicle.devotee_id')
	                							 ->select('devotee.*', 'member.member', 'member.paytill_date', 'familycode.familycode', 'optionalvehicle.type')
																 ->where('devotee.familycode_id', $devotee[0]->familycode_id)
																 ->where('devotee.devotee_id', '!=', $devotee[0]->devotee_id)
	                							 ->get();

			$xiaozai_setting_samefamily_collection = $xiaozai_setting_samefamily_collection->merge($xiaozai_optionalvehicle);
			$xiaozai_setting_samefamily = $xiaozai_setting_samefamily_collection;

			for($i = 0; $i < count($xiaozai_setting_samefamily); $i++)
			{
				$xiaozai_setting_samefamily[$i]->xiaozai_id = 0;
			}
		}

		else
		{
			$xiaozai_setting_samefamily = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
																		->leftjoin('setting_xiaozai', 'setting_xiaozai.devotee_id', '=', 'devotee.devotee_id')
																		->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
																		->where('devotee.devotee_id', '!=', $devotee[0]->devotee_id)
																		->where('devotee.familycode_id', $devotee[0]->familycode_id)
																		->where('setting_xiaozai.focusdevotee_id', $devotee[0]->devotee_id)
																		->where('setting_xiaozai.address_code', '=', 'same')
																		->where('setting_xiaozai.year', null)
																		->select('devotee.*', 'member.paytill_date', 'familycode.familycode', 'setting_xiaozai.xiaozai_id', 'setting_xiaozai.type')
																		->get();
		}

		$oa_count = 1;
		$ov_count = 1;

		for($i = 0; $i < count($xiaozai_setting_samefamily); $i++)
		{
			if($xiaozai_setting_samefamily[$i]->type == 'car' || $xiaozai_setting_samefamily[$i]->type == 'ship')
			{
				$result = OptionalVehicle::where('devotee_id', $xiaozai_setting_samefamily[$i]->devotee_id)
									->where('type', $xiaozai_setting_samefamily[$i]->type)
									->pluck('data');

				$xiaozai_setting_samefamily[$i]['ops'] = "OV#" . $ov_count;
				$xiaozai_setting_samefamily[$i]['item_description'] = $result[0];

				$ov_count++;
			}

			elseif($xiaozai_setting_samefamily[$i]->type == 'home' || $xiaozai_setting_samefamily[$i]->type == 'company'
						|| $xiaozai_setting_samefamily[$i]->type == 'stall' || $xiaozai_setting_samefamily[$i]->type == 'office')
			{
				$result = OptionalAddress::where('devotee_id', $xiaozai_setting_samefamily[$i]->devotee_id)
									->where('type', $xiaozai_setting_samefamily[$i]->type)
									->get();

				if(isset($result[0]->address_translated))
				{
					$xiaozai_setting_samefamily[$i]->ops = "OA#" . $oa_count;
					$xiaozai_setting_samefamily[$i]->item_description = $result[0]->address_translated;
				}
				else
				{
					$xiaozai_setting_samefamily[$i]->ops = "OA#" . $oa_count;
					$xiaozai_setting_samefamily[$i]->item_description = $result[0]->oversea_address;
				}

				$oa_count++;
			}

			else
			{
				$result = Devotee::find($xiaozai_setting_samefamily[$i]->devotee_id);

				if(isset($result->oversea_addr_in_chinese))
				{
					$xiaozai_setting_samefamily[$i]->item_description = $result->oversea_addr_in_chinese;
				}
				elseif (isset($result->address_unit1) && isset($result->address_unit2))
				{
					$xiaozai_setting_samefamily[$i]->item_description = $result->address_houseno . "#" . $result->address_unit1 . '-' .
																																$result->address_unit2 . ", " . $result->address_street . ", " . $result->address_postal;
				}

				else
				{
					$xiaozai_setting_samefamily[$i]->item_description = $result->address_houseno . ", " . $result->address_street . ", " . $result->address_postal;
				}

				$xiaozai_setting_samefamily[$i]->ops = "";
			}
		}

		$xiaozai_setting_samefamily = $xiaozai_setting_samefamily->sortBy('devotee_id');

		// $xiaozai_setting_samefamily = $xiaozai_setting_samefamily->sortBy(['devotee_id', 'order']);
		$xiaozai_setting_samefamily->values()->all();

		// $xiaozai_setting_samefamily = $xiaozai_setting_samefamily->sort();
		// $xiaozai_setting_samefamily->values()->all();

		// Xiao Same Family
		$xiaozai_same_family = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
													 ->leftjoin('setting_xiaozai', 'devotee.devotee_id', '=', 'setting_xiaozai.devotee_id')
											 		 ->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
													 ->where('devotee.familycode_id', $devotee[0]->familycode_id)
													 ->where('devotee.devotee_id', '!=', $devotee[0]->devotee_id)
													 ->where('setting_xiaozai.focusdevotee_id', '=', $devotee[0]->devotee_id)
													 ->where('setting_xiaozai.address_code', '=', 'same')
													 ->where('setting_xiaozai.xiaozai_id', '=', '1')
                           ->where('setting_xiaozai.year', null)
													 ->select('devotee.*', 'familycode.familycode', 'member.paytill_date', 'setting_xiaozai.xiaozai_id',
                             'setting_xiaozai.type')
													 ->get();

    $oa_count = 1;
		$ov_count = 1;

		for($i = 0; $i < count($xiaozai_same_family); $i++)
		{
			if($xiaozai_same_family[$i]->type == 'car' || $xiaozai_same_family[$i]->type == 'ship')
			{
				$result = OptionalVehicle::where('devotee_id', $xiaozai_same_family[$i]->devotee_id)
									->where('type', $xiaozai_same_family[$i]->type)
									->pluck('data');

				$xiaozai_same_family[$i]->ops = "OV#" . $ov_count;

				if(isset($result[0]))
				{
					$xiaozai_same_family[$i]->item_description = $result[0];
				}

				$ov_count++;
			}

			elseif($xiaozai_same_family[$i]->type == 'home' || $xiaozai_same_family[$i]->type == 'company'
						|| $xiaozai_same_family[$i]->type == 'stall' || $xiaozai_same_family[$i]->type == 'office')
			{
				$result = OptionalAddress::where('devotee_id', $xiaozai_same_family[$i]->devotee_id)
									->where('type', $xiaozai_same_family[$i]->type)
									->get();

				if(isset($result[0]->address_translated))
				{
					$xiaozai_same_family[$i]->ops = "OA#" . $oa_count;
					$xiaozai_same_family[$i]->item_description = $result[0]->address_translated;

          $oa_count++;
				}
				else
				{
					$xiaozai_same_family[$i]->ops = "OA#" . $oa_count;
					$xiaozai_same_family[$i]->item_description = $result[0]->oversea_address;

          $oa_count++;
				}
			}

			else
			{
				$result = Devotee::find($xiaozai_same_family[$i]->devotee_id);

				if(isset($result->oversea_addr_in_chinese))
				{
					$xiaozai_same_family[$i]->item_description = $result->oversea_addr_in_chinese;
					$xiaozai_same_family[$i]->ops = "";
				}
				elseif (isset($result->address_unit1) && isset($result->address_unit2))
				{
					$xiaozai_same_family[$i]->item_description = $result->address_houseno . "#" . $result->address_unit1 . '-' .
																												 $result->address_unit2 . ", " . $result->address_street . ", " . $result->address_postal;
					$xiaozai_same_family[$i]->ops = "";
				}

				else
				{
					$xiaozai_same_family[$i]->item_description = $result->address_houseno . ", " . $result->address_street . ", " . $result->address_postal;
					$xiaozai_same_family[$i]->ops = "";
				}
			}
		}

		// Xiaozai Different family
		$xiaozai_setting_differentfamily = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
        															 ->leftjoin('setting_xiaozai', 'setting_xiaozai.devotee_id', '=', 'devotee.devotee_id')
        															 ->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
        															 ->where('setting_xiaozai.focusdevotee_id', '=', $devotee[0]->devotee_id)
        															 ->where('setting_xiaozai.address_code', '=', 'different')
                                       ->where('year', null)
        															 ->select('devotee.*', 'member.paytill_date', 'familycode.familycode', 'setting_xiaozai.xiaozai_id',
        															 'setting_xiaozai.type')
        															 ->get();

    $oa_count = 1;
    $ov_count = 1;

    for($i = 0; $i < count($xiaozai_setting_differentfamily); $i++)
		{
			if($xiaozai_setting_differentfamily[$i]->type == 'car' || $xiaozai_setting_differentfamily[$i]->type == 'ship')
			{
				$result = OptionalVehicle::where('devotee_id', $xiaozai_setting_differentfamily[$i]->devotee_id)
									->where('type', $xiaozai_setting_differentfamily[$i]->type)
									->pluck('data');

				$xiaozai_setting_differentfamily[$i]->item_description = $result[0];
        $xiaozai_setting_differentfamily[$i]->ops = "OV#" . $ov_count;

        $ov_count++;
			}

			elseif($xiaozai_setting_differentfamily[$i]->type == 'home' || $xiaozai_setting_differentfamily[$i]->type == 'company'
						|| $xiaozai_setting_differentfamily[$i]->type == 'stall' || $xiaozai_setting_differentfamily[$i]->type == 'office')
			{
				$result = OptionalAddress::where('devotee_id', $xiaozai_setting_differentfamily[$i]->devotee_id)
									->where('type', $xiaozai_setting_differentfamily[$i]->type)
									->get();

				if(isset($result[0]->address_translated))
				{
					$xiaozai_setting_differentfamily[$i]->item_description = $result[0]->address_translated;
          $xiaozai_setting_differentfamily[$i]->ops = "OA#" . $oa_count;

					$oa_count++;
				}
				else
				{
					$xiaozai_setting_differentfamily[$i]->item_description = $result[0]->oversea_address;
          $xiaozai_setting_differentfamily[$i]->ops = "OA#" . $oa_count;

					$oa_count++;
				}
			}

			else
			{
				$result = Devotee::find($xiaozai_setting_differentfamily[$i]->devotee_id);

				if(isset($result->oversea_addr_in_chinese))
				{
					$xiaozai_setting_differentfamily[$i]->item_description = $result->oversea_addr_in_chinese;
          $xiaozai_setting_differentfamily[$i]->ops = "OA#" . $oa_count;
				}
				elseif (isset($result->address_unit1) && isset($result->address_unit2))
				{
					$xiaozai_setting_differentfamily[$i]->item_description = $result->address_houseno . "#" . $result->address_unit1 . '-' .
																															 			$result->address_unit2 . ", " . $result->address_street . ", " . $result->address_postal;
				}
				else
				{
					$xiaozai_setting_differentfamily[$i]->item_description = $result->address_houseno . ", " . $result->address_street . ", " . $result->address_postal;
				}
			}
		}

		$this_year = date("Y");

		$xiaozai_setting_samefamily_last1year = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
																						->leftjoin('setting_xiaozai', 'setting_xiaozai.devotee_id', '=', 'devotee.devotee_id')
																						->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
																						->where('setting_xiaozai.focusdevotee_id', '=', $devotee[0]->devotee_id)
																						->where('setting_xiaozai.address_code', '=', 'same')
																						->where('setting_xiaozai.year', $this_year - 1)
																						->select('devotee.*', 'member.member', 'member.paytill_date', 'familycode.familycode', 'setting_xiaozai.xiaozai_id', 'setting_xiaozai.type')
																						->get();

		$oa_count = 1;
    $ov_count = 1;

    for($i = 0; $i < count($xiaozai_setting_samefamily_last1year); $i++)
		{
			if($xiaozai_setting_samefamily_last1year[$i]->type == 'car' || $xiaozai_setting_samefamily_last1year[$i]->type == 'ship')
			{
				$result = OptionalVehicle::where('devotee_id', $xiaozai_setting_samefamily_last1year[0]->devotee_id)
									->where('type', $xiaozai_setting_samefamily_last1year[$i]->type)
									->pluck('data');

				$xiaozai_setting_samefamily_last1year[$i]->item_description = $result[0];
        $xiaozai_setting_samefamily_last1year[$i]->ops = "OV#" . $ov_count;

        $ov_count++;
			}

			elseif($xiaozai_setting_samefamily_last1year[$i]->type == 'home' || $xiaozai_setting_samefamily_last1year[$i]->type == 'company'
						|| $xiaozai_setting_samefamily_last1year[$i]->type == 'stall' || $xiaozai_setting_samefamily_last1year[$i]->type == 'office')
			{
				$result = OptionalAddress::where('devotee_id', $xiaozai_setting_samefamily_last1year[$i]->devotee_id)
									->where('type', $xiaozai_setting_samefamily_last1year[$i]->type)
									->get();

				if(isset($result[0]->address_translated))
				{
					$xiaozai_setting_samefamily_last1year[$i]->item_description = $result[0]->address_translated;
          $xiaozai_setting_samefamily_last1year[$i]->ops = "OA#" . $oa_count;
				}
				else
				{
					$xiaozai_setting_samefamily_last1year[$i]->item_description = $result[0]->oversea_address;
          $xiaozai_setting_samefamily_last1year[$i]->ops = "OA#" . $oa_count;
				}

        $oa_count++;
			}

			else
			{
				$result = Devotee::find($xiaozai_setting_samefamily_last1year[$i]->devotee_id);

				if(isset($result->oversea_addr_in_chinese))
				{
					$xiaozai_setting_samefamily_last1year[$i]->item_description = $result[0]->oversea_addr_in_chinese;
          $xiaozai_setting_samefamily_last1year[$i]->ops = "";
				}
				elseif (isset($result->address_unit1) && isset($result->address_unit2))
				{
					$xiaozai_setting_samefamily_last1year[$i]->item_description = $result->address_houseno . "#" . $result->address_unit1 . '-' .
																															 $result->address_unit2 . ", " . $result->address_street . ", " . $result->address_postal;

					$xiaozai_setting_samefamily_last1year[$i]->ops = "";
				}

				else
				{
					$xiaozai_setting_samefamily_last1year[$i]->item_description = $result->address_houseno . ", " . $result->address_street . ", " . $result->address_postal;
					$xiaozai_setting_samefamily_last1year[$i]->ops = "";
				}
			}
		}

    // for($i = 0; $i < count($xiaozai_setting_samefamily_last1year); $i++)
    // {
    //   if(isset($xiaozai_setting_samefamily_last1year[$i]->lasttransaction_at))
  	// 	{
  	// 		$xiaozai_setting_samefamily_last1year[$i]->lasttransaction_at = \Carbon\Carbon::parse($xiaozai_setting_samefamily_last1year[$i]->lasttransaction_at)->format("d/m/Y");
  	// 	}
		//
  	// 	if(isset($xiaozai_setting_samefamily_last1year[$i]->paytill_date))
  	// 	{
  	// 		$xiaozai_setting_samefamily_last1year[$i]->paytill_date = \Carbon\Carbon::parse($xiaozai_setting_samefamily_last1year[$i]->paytill_date)->format("d/m/Y");
  	// 	}
    // }

		$xiaozai_setting_differentfamily_last1year = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
																									->leftjoin('setting_xiaozai', 'setting_xiaozai.devotee_id', '=', 'devotee.devotee_id')
																									->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
																									->where('setting_xiaozai.focusdevotee_id', '=', $devotee[0]->devotee_id)
																									->where('setting_xiaozai.address_code', '=', 'different')
																									->where('setting_xiaozai.year', $this_year - 1)
																									->select('devotee.*', 'member.member', 'member.paytill_date', 'familycode.familycode', 'setting_xiaozai.xiaozai_id', 'setting_xiaozai.type')
																									->get();

		$oa_count = 1;
    $ov_count = 1;

    for($i = 0; $i < count($xiaozai_setting_differentfamily_last1year); $i++)
		{
			if($xiaozai_setting_differentfamily_last1year[$i]->type == 'car' || $xiaozai_setting_differentfamily_last1year[$i]->type == 'ship')
			{
				$result = OptionalVehicle::where('devotee_id', $xiaozai_setting_differentfamily_last1year[$i]->devotee_id)
									->where('type', $xiaozai_setting_differentfamily_last1year[$i]->type)
									->pluck('data');

				$xiaozai_setting_differentfamily_last1year[$i]->item_description = $result[0];
        $xiaozai_setting_differentfamily_last1year[$i]->ops = "OV#" . $ov_count;

        $ov_count++;
			}

			elseif($xiaozai_setting_differentfamily_last1year[$i]->type == 'home' || $xiaozai_setting_differentfamily_last1year[$i]->type == 'company'
						|| $xiaozai_setting_differentfamily_last1year[$i]->type == 'stall' || $xiaozai_setting_differentfamily_last1year[$i]->type == 'office')
			{
				$result = OptionalAddress::where('devotee_id', $xiaozai_setting_differentfamily_last1year[$i]->devotee_id)
									->where('type', $xiaozai_setting_differentfamily_last1year[$i]->type)
									->get();

				if(isset($result[0]->address_translated))
				{
					$xiaozai_setting_differentfamily_last1year[$i]->item_description = $result[0]->address_translated;
          $xiaozai_setting_differentfamily_last1year[$i]->ops = "OA#" . $oa_count;
				}
				else
				{
					$xiaozai_setting_differentfamily_last1year[$i]->item_description = $result[0]->oversea_address;
          $xiaozai_setting_differentfamily_last1year[$i]->ops = "OA#" . $oa_count;
				}

        $oa_count++;
			}

			else
			{
				$result = Devotee::find($xiaozai_setting_differentfamily_last1year[$i]->devotee_id);

				if(isset($result->oversea_addr_in_chinese))
				{
					$xiaozai_setting_differentfamily_last1year[$i]->item_description = $result[0]->oversea_addr_in_chinese;
          $xiaozai_setting_differentfamily_last1year[$i]->ops = "";
				}
				elseif (isset($result->address_unit1) && isset($result->address_unit2))
				{
					$xiaozai_setting_differentfamily_last1year[$i]->item_description = $result->address_houseno . "#" . $result->address_unit1 . '-' .
																															 $result->address_unit2 . ", " . $result->address_street . ", " . $result->address_postal;

					$xiaozai_setting_differentfamily_last1year[$i]->ops = "";
				}

				else
				{
					$xiaozai_setting_differentfamily_last1year[$i]->item_description = $result->address_houseno . ", " . $result->address_street . ", " . $result->address_postal;
					$xiaozai_setting_differentfamily_last1year[$i]->ops = "";
				}
			}
		}

    // for($i = 0; $i < count($xiaozai_setting_differentfamily_last1year); $i++)
    // {
    //   if(isset($xiaozai_setting_differentfamily_last1year[$i]->lasttransaction_at))
  	// 	{
  	// 		$xiaozai_setting_differentfamily_last1year[$i]->lasttransaction_at = \Carbon\Carbon::parse($xiaozai_setting_differentfamily_last1year[$i]->lasttransaction_at)->format("d/m/Y");
  	// 	}
		//
  	// 	if(isset($xiaozai_setting_differentfamily_last1year[$i]->paytill_date))
  	// 	{
  	// 		$xiaozai_setting_differentfamily_last1year[$i]->paytill_date = \Carbon\Carbon::parse($xiaozai_setting_differentfamily_last1year[$i]->paytill_date)->format("d/m/Y");
  	// 	}
    // }

		$setting_differentfamily = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
											         ->leftjoin('setting_generaldonation', 'setting_generaldonation.devotee_id', '=', 'devotee.devotee_id')
											         ->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
											         ->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
											         ->where('setting_generaldonation.focusdevotee_id', '=', $devotee[0]->devotee_id)
											         ->where('setting_generaldonation.address_code', '=', 'different')
											         ->select('devotee.*', 'member.member', 'member.paytill_date', 'specialremarks.devotee_id as specialremarks_devotee_id', 'familycode.familycode', 'setting_generaldonation.xiangyou_ciji_id', 'setting_generaldonation.yuejuan_id')
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

		// Get Xiangyou Receipts History
		$receipt_collection = collect();

		$receipts = GeneralDonation::leftjoin('devotee', 'devotee.devotee_id', '=', 'generaldonation.focusdevotee_id')
								->leftjoin('receipt', 'receipt.generaldonation_id', '=', 'generaldonation.generaldonation_id')
								->where('generaldonation.focusdevotee_id', '=', $devotee[0]->devotee_id)
								->whereIn('receipt.glcode_id', array(119,112))
								->GroupBy('generaldonation.generaldonation_id')
								->select('generaldonation.*', 'devotee.chinese_name', 'receipt.cancelled_date')
								->orderBy('generaldonation.generaldonation_id', 'desc')
								->get();

		$paidby_otherreceipts = Receipt::leftjoin('generaldonation', 'receipt.generaldonation_id', '=', 'generaldonation.generaldonation_id')
														->leftjoin('devotee', 'devotee.devotee_id', '=', 'generaldonation.focusdevotee_id')
														->where('receipt.devotee_id', $devotee[0]->devotee_id)
														->whereIn('receipt.glcode_id', array(119,112))
														->where('generaldonation.focusdevotee_id', '!=', $devotee[0]->devotee_id)
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

		// Get Ciji Receipts History
		$ciji_receipt_collection = collect();

		$ciji_receipts = GeneralDonation::leftjoin('devotee', 'devotee.devotee_id', '=', 'generaldonation.focusdevotee_id')
										 ->leftjoin('receipt', 'receipt.generaldonation_id', '=', 'generaldonation.generaldonation_id')
										 ->where('generaldonation.focusdevotee_id', $devotee[0]->devotee_id)
										 ->where('receipt.glcode_id', 134)
										 ->GroupBy('generaldonation.generaldonation_id')
										 ->select('generaldonation.*', 'devotee.chinese_name', 'receipt.cancelled_date')
										 ->orderBy('generaldonation.generaldonation_id', 'desc')
										 ->get();

		$paidby_otherciji_receipts = Receipt::leftjoin('generaldonation', 'receipt.generaldonation_id', '=', 'generaldonation.generaldonation_id')
																	->leftjoin('devotee', 'devotee.devotee_id', '=', 'generaldonation.focusdevotee_id')
																	->where('receipt.devotee_id', $devotee[0]->devotee_id)
																	->where('receipt.glcode_id', 134)
																	->where('generaldonation.focusdevotee_id', '!=', $devotee[0]->devotee_id)
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

		// Get Yuejuan Receipts History
		$yuejuan_receipt_collection = collect();

		$yuejuan_receipts = GeneralDonation::leftjoin('devotee', 'devotee.devotee_id', '=', 'generaldonation.focusdevotee_id')
										 ->leftjoin('receipt', 'receipt.generaldonation_id', '=', 'generaldonation.generaldonation_id')
										 ->where('generaldonation.focusdevotee_id', $devotee[0]->devotee_id)
										 ->whereIn('receipt.glcode_id', array(108, 110))
										 ->GroupBy('receipt.generaldonation_id')
										 ->select('generaldonation.*', 'devotee.chinese_name')
										 ->orderBy('generaldonation.generaldonation_id', 'desc')
										 ->get();

		$paidby_otheryuejuan_receipts = Receipt::leftjoin('generaldonation', 'receipt.generaldonation_id', '=', 'generaldonation.generaldonation_id')
																		->leftjoin('devotee', 'devotee.devotee_id', '=', 'generaldonation.focusdevotee_id')
																		->where('receipt.devotee_id', $devotee[0]->devotee_id)
																		->whereIn('receipt.glcode_id', array(108, 110))
																		->where('generaldonation.focusdevotee_id', '!=', $devotee[0]->devotee_id)
																		->select('generaldonation.*', 'devotee.chinese_name', 'receipt.cancelled_date', 'receipt.xy_receipt as receipt_no')
																		->get();

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

		// Get Kongdan Receipts History
		$kongdan_receipt_collection = collect();

		$kongdan_receipts = KongdanGeneraldonation::leftjoin('devotee', 'devotee.devotee_id', '=', 'kongdan_generaldonation.focusdevotee_id')
												->leftjoin('kongdan_receipt', 'kongdan_receipt.generaldonation_id', '=', 'kongdan_generaldonation.generaldonation_id')
												->where('kongdan_generaldonation.focusdevotee_id', '=', $devotee[0]->devotee_id)
												->where('kongdan_receipt.glcode_id', 117)
												->GroupBy('kongdan_generaldonation.generaldonation_id')
												->select('kongdan_generaldonation.*', 'devotee.chinese_name', 'kongdan_receipt.cancelled_date')
												->orderBy('kongdan_generaldonation.generaldonation_id', 'desc')
												->get();

		$paidby_otherkongdan_receipts = KongdanReceipt::leftjoin('kongdan_generaldonation', 'kongdan_receipt.generaldonation_id', '=', 'kongdan_generaldonation.generaldonation_id')
																		->leftjoin('devotee', 'devotee.devotee_id', '=', 'kongdan_generaldonation.focusdevotee_id')
																		->where('kongdan_receipt.devotee_id', $devotee[0]->devotee_id)
																		->where('kongdan_receipt.glcode_id', 117)
																		->where('kongdan_generaldonation.focusdevotee_id', '!=', $devotee[0]->devotee_id)
																		->select('kongdan_generaldonation.*', 'devotee.chinese_name', 'kongdan_receipt.cancelled_date', 'kongdan_receipt.receipt_no')
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

		$kongdan_receipt_collection = $kongdan_receipt_collection->merge($kongdan_receipts);
		$kongdan_receipt_collection = $kongdan_receipt_collection->merge($paidby_otherkongdan_receipts);

		$kongdan_receipts = $kongdan_receipt_collection->sortByDesc('generaldonation_id');
		$kongdan_receipts->values()->all();

		// Get Xiaozai Receipts History
		$xiaozai_receipt_collection = collect();

		$xiaozai_receipts = XiaozaiGeneraldonation::leftjoin('devotee', 'devotee.devotee_id', '=', 'xiaozai_generaldonation.focusdevotee_id')
        								->leftjoin('xiaozai_receipt', 'xiaozai_receipt.generaldonation_id', '=', 'xiaozai_generaldonation.generaldonation_id')
        								->where('xiaozai_generaldonation.focusdevotee_id', '=', $devotee[0]->devotee_id)
        								->GroupBy('xiaozai_generaldonation.generaldonation_id')
        								->whereIn('xiaozai_receipt.glcode_id', array(118, 120))
        								->select('xiaozai_generaldonation.*', 'devotee.chinese_name', 'xiaozai_receipt.cancelled_date')
        								->orderBy('xiaozai_generaldonation.generaldonation_id', 'desc')
        								->get();

		$paidby_otherxiaozai_receipts = XiaozaiReceipt::leftjoin('xiaozai_generaldonation', 'xiaozai_receipt.generaldonation_id', '=', 'xiaozai_generaldonation.generaldonation_id')
																		->leftjoin('devotee', 'devotee.devotee_id', '=', 'xiaozai_generaldonation.focusdevotee_id')
																		->where('xiaozai_receipt.devotee_id', $devotee[0]->devotee_id)
																		->whereIn('xiaozai_receipt.glcode_id', array(118, 120))
																		->where('xiaozai_generaldonation.focusdevotee_id', '!=', $devotee[0]->devotee_id)
																		->select('xiaozai_generaldonation.*', 'devotee.chinese_name', 'xiaozai_receipt.cancelled_date', 'xiaozai_receipt.receipt_no')
																		->get();

    if(count($xiaozai_receipts) > 0)
		{
			for($i = 0; $i < count($xiaozai_receipts); $i++)
			{
				$data = XiaozaiReceipt::where('generaldonation_id', $xiaozai_receipts[$i]->generaldonation_id)->pluck('receipt_no');
				$receipt_count = count($data);

				if($receipt_count > 1)
				{
					$xiaozai_receipts[$i]->receipt_no = $data[0] . ' - ' . $data[$receipt_count - 1];
				}
				else
				{
					$xiaozai_receipts[$i]->receipt_no = $data[0];
				}
			}
		}

		$xiaozai_receipt_collection = $xiaozai_receipt_collection->merge($xiaozai_receipts);
		$xiaozai_receipt_collection = $xiaozai_receipt_collection->merge($paidby_otherxiaozai_receipts);

		$xiaozai_receipts = $xiaozai_receipt_collection->sortByDesc('generaldonation_id');
		$xiaozai_receipts->values()->all();

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
		Session::put('ciji_same_family', $ciji_same_family);
		Session::put('ciji_same_focusdevotee', $ciji_same_focusdevotee);
		Session::put('ciji_different_family', $ciji_different_family);
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

		Session::put('kongdan_same_family', $kongdan_same_family);
		Session::put('kongdan_same_focusdevotee', $kongdan_same_focusdevotee);
		Session::put('kongdan_different_family', $kongdan_different_family);
		Session::put('kongdan_setting_samefamily', $kongdan_setting_samefamily);
		Session::put('kongdan_setting_differentfamily', $kongdan_setting_differentfamily);
		Session::put('kongdan_focusdevotee', $kongdan_focusdevotee);
		Session::put('kongdan_receipts', $kongdan_receipts);

		Session::put('xiaozai_same_focusdevotee', $xiaozai_same_focusdevotee);
		Session::put('xiaozai_same_family', $xiaozai_same_family);
		Session::put('xiaozai_different_family', $xiaozai_different_family);
		Session::put('xiaozai_setting_samefamily', $xiaozai_setting_samefamily);
		Session::put('xiaozai_setting_differentfamily', $xiaozai_setting_differentfamily);
		Session::put('xiaozai_focusdevotee', $xiaozai_focusdevotee);
		Session::put('xiaozai_receipts', $xiaozai_receipts);

		Session::put('kongdan_setting_differentfamily_last1year', $kongdan_setting_differentfamily_last1year);
		Session::put('kongdan_setting_differentfamily_last2year', $kongdan_setting_differentfamily_last2year);
		Session::put('kongdan_setting_differentfamily_last3year', $kongdan_setting_differentfamily_last3year);
		Session::put('kongdan_setting_differentfamily_last4year', $kongdan_setting_differentfamily_last4year);
		Session::put('kongdan_setting_differentfamily_last5year', $kongdan_setting_differentfamily_last5year);

		Session::put('kongdan_setting_samefamily_last1year', $kongdan_setting_samefamily_last1year);
		Session::put('kongdan_setting_samefamily_last2year', $kongdan_setting_samefamily_last2year);
		Session::put('kongdan_setting_samefamily_last3year', $kongdan_setting_samefamily_last3year);
		Session::put('kongdan_setting_samefamily_last4year', $kongdan_setting_samefamily_last4year);
		Session::put('kongdan_setting_samefamily_last5year', $kongdan_setting_samefamily_last5year);

		Session::put('xiaozai_setting_samefamily_last1year', $xiaozai_setting_samefamily_last1year);

		Session::put('xiaozai_setting_differentfamily_last1year', $xiaozai_setting_differentfamily_last1year);

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

					$member_id = Member::all()->last()->member_id;
				  $member_field = str_pad($member_id + 1, 5, 0, STR_PAD_LEFT);
					$member_field = "8" . $member_field;

				  $data = [
						"member" => $member_field,
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
				  // Create New Family Code
				  $familycode_id = FamilyCode::all()->last()->familycode_id;
					$new_familycode_id = str_pad($familycode_id + 1, 5, 0, STR_PAD_LEFT);
					$new_familycode = "FC" . $new_familycode_id;

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
		    // Create New Family Code
		    $familycode_id = FamilyCode::all()->last()->familycode_id;
				$new_familycode_id = str_pad($familycode_id + 1, 5, 0, STR_PAD_LEFT);
				$new_familycode = "FC" . $new_familycode_id;

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

				Session::forget('kongdan_same_family');
				Session::forget('kongdan_different_family');

				// Setting General Donation
				$setting_data = [
				  'focusdevotee_id' => $devotee_id,
					'xiangyou_ciji_id' => 1,
					'yuejuan_id' => 1,
					'devotee_id' => $devotee_id,
					'year' => 2017
				];

				SettingGeneralDonation::create($setting_data);

				// Setting Kongdan
				$setting_kongdan = [
				  'focusdevotee_id' => $devotee_id,
					'kongdan_id' => 1,
					'devotee_id' => $devotee_id
				];

				SettingKongdan::create($setting_kongdan);

				$setting_xiazai = [
					'focusdevotee_id' => $devotee_id,
					'xiaozai_id' => 1,
					'type' => 'sameaddress',
					'devotee_id' => $devotee_id
				];

				SettingXiaozai::create($setting_xiazai);

				for($i = 0; $i < count($input['address_data_hidden']); $i++)
				{
					if(isset($input['address_data_hidden'][$i]))
					{
						$setting_xiazai = [
							'focusdevotee_id' => $devotee_id,
							'xiaozai_id' => 1,
							'type' => $input['address_type'][$i],
							'devotee_id' => $devotee_id
						];

						SettingXiaozai::create($setting_xiazai);
					}
				}

				for($i = 0; $i < count($input['address_oversea_hidden']); $i++)
				{
					if(isset($input['address_oversea_hidden'][$i]))
					{
						$setting_xiazai = [
							'focusdevotee_id' => $devotee_id,
							'xiaozai_id' => 1,
							'type' => $input['address_type'][$i],
							'devotee_id' => $devotee_id
						];

						SettingXiaozai::create($setting_xiazai);
					}
				}

				for($i = 0; $i < count($input['vehicle_data']); $i++)
				{
					if(isset($input['vehicle_data'][$i]))
					{
						$setting_xiazai = [
							'focusdevotee_id' => $devotee_id,
							'xiaozai_id' => 1,
							'type' => $input['vehicle_type'][$i],
							'devotee_id' => $devotee_id
						];

						SettingXiaozai::create($setting_xiazai);
					}
				}

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

		$kongdan_setting_samefamily = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
																	->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
																	->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
																	->where('devotee.devotee_id', '!=', $focus_devotee[0]->devotee_id)
																	->where('devotee.familycode_id', $familycode_id)
																	->select('devotee.*', 'member.paytill_date', 'specialremarks.devotee_id as specialremarks_devotee_id', 'familycode.familycode')
																	->GroupBy('devotee.devotee_id')
																	->get();

		for($i = 0; $i < count($kongdan_setting_samefamily); $i++)
		{
			$kongdan_setting_samefamily[$i]->kongdan_id = 0;
		}

		$kongdan_setting_differentfamily = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
		                                   ->leftjoin('setting_kongdan', 'setting_kongdan.devotee_id', '=', 'devotee.devotee_id')
		                                   ->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
		                                   ->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
		                                   ->where('setting_kongdan.focusdevotee_id', '=', $focus_devotee[0]->devotee_id)
		                                   ->where('setting_kongdan.address_code', '=', 'different')
		                                   ->where('setting_kongdan.year', null)
		                                   ->select('devotee.*', 'member.member', 'member.paytill_date', 'specialremarks.devotee_id as specialremarks_devotee_id', 'familycode.familycode', 'setting_kongdan.kongdan_id')
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

		// Setting Kongdan focusdevotee
		$setting_kongdan = SettingKongdan::where('focusdevotee_id', $focus_devotee[0]->devotee_id)
		                   ->where('devotee_id', $focus_devotee[0]->devotee_id)
		                   ->get();

		if(count($setting_kongdan) > 0)
		{
		  $kongdan_focusdevotee = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
		                          ->leftjoin('setting_kongdan', 'devotee.devotee_id', '=', 'setting_kongdan.devotee_id')
		                          ->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
		                          ->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
		                          ->where('devotee.devotee_id', $focus_devotee[0]->devotee_id)
		                          ->where('setting_kongdan.focusdevotee_id', $focus_devotee[0]->devotee_id)
		                          ->select('devotee.*', 'member.member', 'familycode.familycode', 'member.paytill_date', 'specialremarks.devotee_id as specialremarks_devotee_id', 'setting_kongdan.kongdan_id')
		                          ->GroupBy('devotee.devotee_id')
		                          ->get();
		}

		else
		{
		  $kongdan_focusdevotee = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
		                          ->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
		                          ->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
		                          ->where('devotee.devotee_id', $focus_devotee[0]->devotee_id)
		                          ->select('devotee.*', 'familycode.familycode', 'member.member', 'member.paytill_date', 'specialremarks.devotee_id as specialremarks_devotee_id')
		                          ->get();

		  $kongdan_focusdevotee[0]->kongdan_id = 0;
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

		$kongdan_same_focusdevotee = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
		                             ->leftjoin('setting_kongdan', 'devotee.devotee_id', '=', 'setting_kongdan.devotee_id')
		                             ->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
		                             ->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
		                             ->where('setting_kongdan.address_code', '=', 'same')
		                             ->where('setting_kongdan.kongdan_id', '=', '1')
		                             ->where('setting_kongdan.focusdevotee_id', '=', $focus_devotee[0]->devotee_id)
		                             ->where('setting_kongdan.devotee_id', '=', $focus_devotee[0]->devotee_id)
		                             ->select('devotee.*', 'member.member', 'familycode.familycode', 'member.paytill_date', 'specialremarks.devotee_id as specialremarks_devotee_id')
		                             ->GroupBy('devotee.devotee_id')
		                             ->get();

		$xiaozai_same_focusdevotee = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
		                             ->leftjoin('setting_xiaozai', 'devotee.devotee_id', '=', 'setting_xiaozai.devotee_id')
		                             ->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
		                             ->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
		                             ->where('setting_xiaozai.address_code', '=', 'same')
		                             ->where('setting_xiaozai.xiaozai_id', '=', '1')
		                             ->where('setting_xiaozai.focusdevotee_id', '=', $focus_devotee[0]->devotee_id)
		                             ->where('setting_xiaozai.devotee_id', '=', $focus_devotee[0]->devotee_id)
		                             ->select('devotee.*', 'familycode.familycode', 'member.paytill_date', 'specialremarks.devotee_id as specialremarks_devotee_id')
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

		 // Kongdan Session
		 Session::put('kongdan_focusdevotee', $kongdan_focusdevotee);
		 Session::put('kongdan_same_focusdevotee', $kongdan_same_focusdevotee);
		 Session::put('kongdan_setting_samefamily', $kongdan_setting_samefamily);
		 Session::put('kongdan_setting_differentfamily', $kongdan_setting_differentfamily);

		 // Xiaozai Session
		 Session::put('xiaozai_same_focusdevotee', $xiaozai_same_focusdevotee);

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
					$member_id = Member::all()->last()->member_id;
					$member_field = str_pad($member_id + 1, 5, 0, STR_PAD_LEFT);
					$member_field = "8" . $member_field;

					$data = [
						'member' => $member_field,
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
			             ->select('devotee.*', 'familycode.familycode', 'member.member', 'member.introduced_by1', 'member.introduced_by2', 'member.approved_date',
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

		Session::forget('kongdan_focusdevotee');
		Session::forget('kongdan_setting_samefamily');
		Session::forget('kongdan_setting_differentfamily');
		Session::forget('kongdan_same_focusdevotee');
		Session::forget('kongdan_same_family');
		Session::forget('kongdan_different_family');

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

	// public function getSearchAddressHouseNo(Request $request)
	// {
	// 	$address_houseno = $_GET['address_houseno'];
	//
	// 	$address = TranslationStreet::where('address_houseno', $address_houseno)->first();
	//
	// 	return response()->json(array(
	// 		'address' => $address
	// 	));
	// }

	public function getCheckDevotee(Request $request)
	{
		$input = array_except($request->all(), '_token');

		if(isset($input['address_houseno']))
		{
			if(isset($input['address_unit1']))
			{
				$result = Devotee::where('chinese_name', $input['chinese_name'])
									->where('address_houseno', $input['address_houseno'])
									->where('address_unit1', $input['address_unit1'])
									->where('address_unit2', $input['address_unit2'])
									->where('address_street', $input['address_street'])
									->where('address_postal', $input['address_postal'])
									->first();
			}
			else
			{
				$result = Devotee::where('chinese_name', $input['chinese_name'])
									->where('address_houseno', $input['address_houseno'])
									->where('address_street', $input['address_street'])
									->where('address_postal', $input['address_postal'])
									->first();
			}
		}

		else
		{
			$result = Devotee::where('chinese_name', $input['chinese_name'])
								->where('oversea_addr_in_chinese', $input['oversea_addr_in_chinese'])
								->first();
		}

		if($result)
		{
			return response()->json(array(
				'msg' => 'Same Devotee'
			));
		}

		else
		{
			return response()->json(array(
				'msg' => 'No Devotee'
			));
		}
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
