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
		$devotees = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
						->whereNull('member_id')
						->whereNull('deceased_year')
        				->select('devotee.*')
        				->addSelect('familycode.familycode')
        				->get();

		$members = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
						->whereNotNull('member_id')
						->whereNull('deceased_year')
						->orderBy('devotee_id', 'asc')
        				->select('devotee.*')
        				->addSelect('familycode.familycode')
        				->get();

        $deceased_lists = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
						->whereNotNull('deceased_year')
						->orderBy('devotee_id', 'asc')
        				->select('devotee.*')
        				->addSelect('familycode.familycode')
        				->get();

		return view('operator.index', [
            'members' => $members,
            'devotees' => $devotees,
            'deceased_lists' => $deceased_lists
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
		// $devotee_id = 17;

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

      if (Hash::check($input['authorized_password'], $hashedPassword)) {

		    // Modify fields
		    $dob = $input['dob'];
				$dob_date = str_replace('/', '-', $dob);
				$dobNewDate = date("Y-m-d", strtotime($dob_date));

				if(isset($input['approved_date']))
				{
					$approvedDate = $input['approved_date'];
					$approvedDate_date = str_replace('/', '-', $approvedDate);
					$approveNewDate = date("Y-m-d", strtotime($approvedDate_date));
				}

		    // Save Member
		    if(isset($input['introduced_by1']) && isset($input['introduced_by2']))
		    {
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
				    "address_building" => $input['address_building'],
				    "address_postal" => $input['address_postal'],
				    "address_translated" => $input['address_translated'],
				    "oversea_addr_in_chinese" => $input['oversea_addr_in_chinese'],
				    "nric" => $input['nric'],
				    "deceased_year" => $input['deceased_year'],
				    "dob" => $dobNewDate,
				    "marital_status" => $input['marital_status'],
				    "dialect" => $input['dialect'],
				    "nationality" => $input['nationality'],
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
				        "address_building" => $input['address_building'],
				        "address_postal" => $input['address_postal'],
				        "address_translated" => $input['address_translated'],
				        "oversea_addr_in_chinese" => $input['oversea_addr_in_chinese'],
				        "nric" => $input['nric'],
				        "deceased_year" => $input['deceased_year'],
				        "dob" => $dobNewDate,
				        "marital_status" => $input['marital_status'],
				        "dialect" => $input['dialect'],
				        "nationality" => $input['nationality'],
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
				        "address_building" => $input['address_building'],
				        "address_postal" => $input['address_postal'],
				        "address_translated" => $input['address_translated'],
				        "oversea_addr_in_chinese" => $input['oversea_addr_in_chinese'],
				        "nric" => $input['nric'],
				        "deceased_year" => $input['deceased_year'],
				        "dob" => $dobNewDate,
				        "marital_status" => $input['marital_status'],
				        "dialect" => $input['dialect'],
				        "nationality" => $input['nationality'],
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
				        "address_building" => $input['address_building'],
				        "address_postal" => $input['address_postal'],
				        "address_translated" => $input['address_translated'],
				        "oversea_addr_in_chinese" => $input['oversea_addr_in_chinese'],
				        "nric" => $input['nric'],
				        "deceased_year" => $input['deceased_year'],
				        "dob" => $dobNewDate,
				        "marital_status" => $input['marital_status'],
				        "dialect" => $input['dialect'],
				        "nationality" => $input['nationality'],
				        "familycode_id" => $familycode->familycode_id
				    ];

				    $devotee = Devotee::create($data);
			    	$devotee_id = $devotee->devotee_id;
		        }


		        if($devotee_id != null)
		        {
		        	if(isset($input['address_data'][0]))
		        	{
		        		// Save Optional Address
						for($i = 0; $i < count($input['address_type']); $i++)
						{
							$optional_address = [
								"type" => $input['address_type'][$i],
								"data" => $input['address_data'][$i],
								"devotee_id" => $devotee_id
							];

							OptionalAddress::create($optional_address);
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

		        if($member_id != null)
		        {
		        	$request->session()->flash('success', 'Member account has been created!');
        			return redirect()->back();
		        }

		        else
		        {
					$request->session()->flash('success', 'Devotee account has been created!');
        			return redirect()->back();
		        }
			}

			else
			{
				$request->session()->flash('error', "Password did not match. Please Try Again");
            	return redirect()->back()->withInput();;
			}
		}

		else
		{
			$request->session()->flash('error', "Please enter password. Please Try Again");
            return redirect()->back()->withInput();;
		}
	}

	// Update Devotee
	public function postEditDevotee(Request $request)
	{
		$familycode_id = "";
		$approveNewDate = "";
		$cancelledNewDate = "";

		$input = array_except($request->all(), '_token');

    if(isset($input['authorized_password']))
		{
			$user = User::find(Auth::user()->id);
      $hashedPassword = $user->password;

      if (Hash::check($input['authorized_password'], $hashedPassword)) {

        // Modify fields
				$dob = $input['dob'];
				$dob_date = str_replace('/', '-', $dob);
				$dobNewDate = date("Y-m-d", strtotime($dob_date));

				if(isset($input['approved_date']))
				{
					$approvedDate = $input['approved_date'];
					$approvedDate_date = str_replace('/', '-', $approvedDate);
					$approveNewDate = date("Y-m-d", strtotime($approvedDate_date));
				}

				if(isset($input['cancelled_date']))
				{
					$cancelledDate = $input['cancelled_date'];
					$cancelledDate_date = str_replace('/', '-', $cancelledDate);
					$cancelledNewDate = date("Y-m-d", strtotime($cancelledDate_date));
				}

		   if(isset($input['edit_familycode_id']))
		   {
		     $familycode_id = $input['edit_familycode_id'];
		   }

		   else
		   {
		     $familycode_id = $input['familycode_id'];
		   }

		   if(isset($input['member_id']))
		   {
		   		$member = Member::find($input['member_id']);

		      $member->introduced_by1 = $input['introduced_by1'];
		      $member->introduced_by2 = $input['introduced_by2'];
		      $member->approved_date = $approveNewDate;
		      $member->cancelled_date = $cancelledNewDate;
		      $member->reason_for_cancel = $input['reason_for_cancel'];

		      $member->save();

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
		    	$devotee->address_building = $input['address_building'];
				  $devotee->address_postal = $input['address_postal'];
				  $devotee->address_translated = $input['address_translated'];
				  $devotee->oversea_addr_in_chinese = $input['oversea_addr_in_chinese'];
				  $devotee->nric = $input['nric'];
				  $devotee->deceased_year = $input['deceased_year'];
				  $devotee->dob = $dobNewDate;
				  $devotee->marital_status = $input['marital_status'];
				  $devotee->dialect = $input['dialect'];
				  $devotee->nationality = $input['nationality'];
				  $devotee->familycode_id = $familycode_id;
				  $devotee->member_id = $input['member_id'];

				  $member_result = $devotee->save();
		    }

		    else
		    {
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
				    $devotee->address_building = $input['address_building'];
				    $devotee->address_postal = $input['address_postal'];
				    $devotee->address_translated = $input['address_translated'];
				    $devotee->oversea_addr_in_chinese = $input['oversea_addr_in_chinese'];
				    $devotee->nric = $input['nric'];
				    $devotee->deceased_year = $input['deceased_year'];
				    $devotee->dob = $dobNewDate;
				    $devotee->marital_status = $input['marital_status'];
				    $devotee->dialect = $input['dialect'];
				    $devotee->nationality = $input['nationality'];
				    $devotee->familycode_id = $familycode_id;

				    $devotee_result = $devotee->save();
		    }


		    if(isset($input['address_data'][0]))
				{
					//delete first before saving
		      OptionalAddress::where('devotee_id', $input['devotee_id'])->delete();

				  // Update Optional Address
					for($i = 0; $i < count($input['address_type']); $i++)
					{
						$optional_address = new OptionalAddress;
		        $optional_address->type = $input['address_type'][$i];
		        $optional_address->data = $input['address_data'][$i];
		        $optional_address->devotee_id = $input['devotee_id'];

		        $optional_address->save();
					}
				}

				if(isset($input['vehicle_data'][0]))
				{
					//delete first before saving
		      OptionalVehicle::where('devotee_id', $input['devotee_id'])->delete();

				    // Update Optional Vehicle
					for($i = 0; $i < count($input['vehicle_type']); $i++)
					{
						$optional_vehicle = new OptionalVehicle;
		        $optional_vehicle->type = $input['vehicle_type'][$i];
		        $optional_vehicle->data = $input['vehicle_data'][$i];
		        $optional_vehicle->devotee_id = $input['devotee_id'];

		        $optional_vehicle->save();
					}
				}

				if(isset($input['special_remark'][0]))
				{
					//delete first before saving
		      SpecialRemarks::where('devotee_id', $input['devotee_id'])->delete();

				  // Update Special Remarks
					for($i = 0; $i < count($input['special_remark']); $i++)
					{
						$special_remark = new SpecialRemarks;
		        $special_remark->data = $input['special_remark'][$i];
		        $special_remark->devotee_id = $input['devotee_id'];

		        $special_remark->save();
					}
				}

				if (isset($member_result)) {

		    	$request->session()->flash('success', 'Member Profile is successfully updated.');
		      return redirect()->back();
		    }

		    if(isset($devotee_result))
		    {
		    	$request->session()->flash('success', 'Devotee Profile is successfully updated.');
		      return redirect()->back();
		    }

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

		$devotees = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
						->whereNull('member_id')
						->whereNull('deceased_year')
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
					$request->session()->flash('error', 'There has no record. Please search again.');
					return redirect()->back()->withInput();
				}

				elseif(count($focus_devotee) > 1)
				{
					$request->session()->flash('error', 'There has more than one record. Please search with more details.');
					return redirect()->back()->withInput();
				}

				else {
					// Get Devotee Lists for relocation
	        $familycode_id = $focus_devotee[0]->familycode_id;

	        $devotee_lists = Devotee::join('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
	        				->where('devotee.familycode_id', $familycode_id)
	        				->where('devotee_id', '!=', $focus_devotee[0]->devotee_id)
	        				->orderBy('devotee_id', 'asc')
	        				->select('devotee.*')
	        				->addSelect('familycode.familycode')->get();

					// Get Receipt History
				  $receipts = Receipt::leftjoin('generaldonation', 'generaldonation.generaldonation_id', '=', 'receipt.generaldonation_id')
				         				->leftjoin('devotee', 'devotee.devotee_id', '=', 'receipt.focusdevotee_id')
				         				->where('receipt.focusdevotee_id', $focus_devotee[0]->devotee_id)
				         				->orderBy('receipt_id', 'desc')
				         				->select('receipt.*')
				         				->addSelect('devotee.chinese_name')
				         				->addSelect('generaldonation.manualreceipt')
				         				->get();

												if (!Session::has('focus_devotee'))
												{
										    	Session::put('focus_devotee', $focus_devotee);
												}

								        if(!Session::has('devotee_lists'))
								        {
								        	Session::put('devotee_lists', $devotee_lists);
								        }

								        if(!Session::has('receipts'))
								        {
								        	Session::put('receipts', $receipts);
								        }

												return redirect()->back()->with([
								        	'members' => $members,
								        	'devotees' => $devotees,
								        	'deceased_lists' => $deceased_lists,
								        ]);
				}

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
		Session::forget('receipts');

        return redirect()->back()->with([
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
		$validator = $this->validate($request, [
            'devotee_id' => 'required'
        ]);

        if ($validator && $validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

		$input = Input::except('_token', 'address_houseno', 'address_unit1', 'address_unit2', 'address_street',
								'address_building', 'address_postal', 'nationality', 'oversea_addr_in_chinese');

	    for($i = 0; $i < count($input['devotee_id']); $i++)
	    {
	    	$devotee = Devotee::find($input['devotee_id'][$i]);

		    $devotee->address_houseno = $input['new_address_houseno'];
		    $devotee->address_unit1 = $input['new_address_unit1'];
		    $devotee->address_unit2 = $input['new_address_unit2'];
		    $devotee->address_street = $input['new_address_street'];
		    $devotee->address_building = $input['new_address_building'];
		    $devotee->address_postal = $input['new_address_postal'];
		    $devotee->nationality = $input['new_nationality'];
		    $devotee->oversea_addr_in_chinese = $input['new_oversea_addr_in_chinese'];
			$devotee->save();
	    }

	    $request->session()->flash('success', 'Relocation Devotee(s) has been changed!');
	    return redirect()->back();
	}

	public function getAutocomplete(Request $request)
	{
			$member = Input::get('term');

			$results = array();

			$queries = Member::where('introduced_by1', 'like', '%'.$member.'%')
								 ->orwhere('introduced_by2', 'like', '%'.$member.'%')
								 ->take(5)
								 ->get();

		  foreach ($queries as $query)
			{
				$results[] = [
					'id' => $query->member_id,
					'value' => $query->introduced_by1
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


	public function getAddressTranslate(Request $request)
	{
		$address_street = $_GET['address_street'];

		$address_translate = TranslationStreet::where('english', $address_street)
												 ->get();

		return response()->json(array(
			'address_translate' => $address_translate
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
