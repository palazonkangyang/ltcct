<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\User;
use App\Models\SettingXiaozai;
use App\Models\Devotee;
use App\Models\FestiveEvent;
use App\Models\GlCode;
use App\Models\OptionalAddress;
use App\Models\OptionalVehicle;
use App\Models\XiaozaiGeneraldonation;
use App\Models\XiaozaiReceipt;
use Auth;
use DB;
use Hash;
use Mail;
use Input;
use Session;
use View;
use URL;
use Carbon\Carbon;

class XiaozaiController extends Controller
{
  public function getXiaoZai()
  {
    $today = Carbon::today();

		$events = FestiveEvent::orderBy('start_at', 'asc')
							->where('start_at', '>', $today)
							->take(1)
							->get();

    return view('fahui.xiaozai', [
			'events' => $events
		]);
  }

  public function postXiaozai(Request $request)
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

    if(count(XiaozaiGeneraldonation::all()) > 0)
    {
      $trans_id = XiaozaiGeneraldonation::all()->last()->generaldonation_id;
    }

    else {
      $trans_id = 0;
    }

    $prefix = "T";
	  $trans_id += 1;
	  $trans_id = $prefix . $trans_id;

    if(empty($input['festiveevent_id']))
		{
			$input['festiveevent_id'] = 0;
		}

    $data = [
	    "trans_no" => $trans_id,
	    "description" => "Xiaozai - 消灾",
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

    $xiaozai_generaldonation = XiaozaiGeneraldonation::create($data);

    if($xiaozai_generaldonation)
		{
      for($i = 0; $i < count($input['hidden_xiaozai_amount']); $i++)
      {
        if($input['hidden_xiaozai_amount'][$i] == 1)
        {
          if(count(XiaozaiReceipt::all()) > 0)
          {
            $same_receipt = XiaozaiReceipt::all()->last()->receipt_id;
          }

          else {
            $result = GlCode::where('glcode_id', '118')->pluck('next_sn_number');
            $same_receipt = $result[0];
          }

          $prefix = GlCode::where('glcode_id', '118')->pluck('receipt_prefix');
          $prefix = $prefix[0];
          $same_receipt += 1;

          $year = date('Y');
          $year = substr( $year, -2);

          $receipt = str_pad($same_receipt, 4, 0, STR_PAD_LEFT);
          $receipt = $prefix . $year . $receipt;

          $devotee = Devotee::find($input['devotee_id'][$i]);

          if(isset($devotee->member_id))
          {
            $glcode = 118;
          }

          else
          {
            $glcode = 120;
          }

          $data = [
            "receipt_no" => $receipt,
            "trans_date" => Carbon::now(),
            "description" => "Xiaozai - 消灾",
            "type" => $input['type'][$i],
            "amount" => $input['amount'][$i],
            "glcode_id" => $glcode,
            "devotee_id" => $input['devotee_id'][$i],
            "generaldonation_id" => $xiaozai_generaldonation->generaldonation_id,
            "staff_id" => Auth::user()->id
          ];

          XiaozaiReceipt::create($data);
        }
      }
    }

    // remove session
	  Session::forget('xiaozai_receipts');

    $xiaozai_receipts = XiaozaiGeneraldonation::leftjoin('devotee', 'devotee.devotee_id', '=', 'xiaozai_generaldonation.focusdevotee_id')
        								->leftjoin('xiaozai_receipt', 'xiaozai_receipt.generaldonation_id', '=', 'xiaozai_generaldonation.generaldonation_id')
        								->where('xiaozai_generaldonation.focusdevotee_id', '=', $input['focusdevotee_id'])
        								->GroupBy('xiaozai_generaldonation.generaldonation_id')
        								->whereIn('xiaozai_receipt.glcode_id', array(118, 120))
        								->select('xiaozai_generaldonation.*', 'devotee.chinese_name', 'xiaozai_receipt.cancelled_date')
        								->orderBy('xiaozai_generaldonation.generaldonation_id', 'desc')
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

    Session::put('xiaozai_receipts', $xiaozai_receipts);

    $xiaozai_generaldonation_id = $xiaozai_generaldonation->generaldonation_id;
		$hjgr = $xiaozai_generaldonation->hjgr;

    $result = XiaozaiReceipt::leftjoin('xiaozai_generaldonation', 'xiaozai_receipt.generaldonation_id', '=', 'xiaozai_generaldonation.generaldonation_id')
							->leftjoin('devotee', 'xiaozai_receipt.devotee_id', '=', 'devotee.devotee_id')
							->leftjoin('user', 'xiaozai_receipt.staff_id', '=', 'user.id')
							->leftjoin('festiveevent', 'xiaozai_generaldonation.festiveevent_id', '=', 'festiveevent.festiveevent_id')
							->where('xiaozai_generaldonation.generaldonation_id', '=', $xiaozai_generaldonation_id)
							->select('xiaozai_receipt.*', 'devotee.chinese_name', 'devotee.oversea_addr_in_chinese', 'devotee.address_houseno', 'devotee.address_unit1',
							 	'devotee.address_unit2', 'devotee.address_street', 'devotee.address_postal', 'devotee.familycode_id', 'devotee.deceased_year',
							 	'xiaozai_generaldonation.focusdevotee_id', 'xiaozai_generaldonation.trans_no', 'user.first_name', 'user.last_name',
							 	'festiveevent.start_at', 'festiveevent.time', 'festiveevent.event', 'festiveevent.lunar_date', 'xiaozai_generaldonation.mode_payment')
							->get();

    for($i = 0; $i < count($result); $i++)
		{
			$result[$i]->trans_date = \Carbon\Carbon::parse($result[$i]->trans_date)->format("d/m/Y");
			$result[$i]->start_at = \Carbon\Carbon::parse($result[$i]->start_at)->format("d/m/Y");
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

    return view('fahui.xiaozai_print', [
			'receipts' => $result,
			'print_format' => $hjgr,
			'loop' => $loop,
			'count_familycode' => $count_familycode,
			'samefamily_no' => $samefamily_no,
			'total_amount' => number_format($total_amount, 2),
			'paid_by' => $paid_by
		]);
  }

  public function getInsertDevotee(Request $request)
	{
		$devotee_id = $_GET['devotee_id'];
    $address = $_GET['address'];

    $devotee_collection = collect();

		if($address == 0)
    {
      $devotee = Devotee::leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
  							 ->leftjoin('familycode', 'familycode.familycode_id' , '=', 'devotee.familycode_id')
  							 ->select('devotee.*', 'member.paytill_date', 'familycode.familycode')
  							 ->where('devotee.devotee_id', $devotee_id)
  							 ->get();

      $devotee[0]->type = "sameaddress";
      $devotee[0]->ops = "";

      $devotee_collection = $devotee_collection->merge($devotee);
    }

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

    $devotee_collection = $devotee_collection->merge($optionaladdress_devotee);
    $devotee_collection = $devotee_collection->merge($optionalvehicle_devotee);

    $oa_count = 1;
    $ov_count = 1;

    for($i = 0; $i < count($devotee_collection); $i++)
		{
			if($devotee_collection[$i]['type'] == 'car' || $devotee_collection[$i]['type'] == 'ship')
			{
				$result = OptionalVehicle::where('devotee_id', $devotee_collection[$i]->devotee_id)
									->where('type', $devotee_collection[$i]['type'])
									->pluck('data');

				$devotee_collection[$i]['item_description'] = $result[0];
        $devotee_collection[$i]['ops'] = "OV#" . $ov_count;

        $ov_count++;
			}

			elseif($devotee_collection[$i]['type'] == 'home' || $devotee_collection[$i]['type'] == 'company'
						|| $devotee_collection[$i]['type'] == 'stall' || $devotee_collection[$i]['type'] == 'office')
			{
				$result = OptionalAddress::where('devotee_id', $devotee_id)
									->where('type', $devotee_collection[$i]['type'])
									->get();

				if(isset($result[0]->address_translated))
				{
					$devotee_collection[$i]['item_description'] = $result[0]->address_translated;
          $devotee_collection[$i]['ops'] = "OA#" . $oa_count;
				}
				else
				{
					$devotee_collection[$i]['item_description'] = $result[0]->oversea_address;
          $devotee_collection[$i]['ops'] = "OA#" . $oa_count;
				}

        $oa_count++;
			}

			else
			{
				$result = Devotee::find($devotee[0]->devotee_id);

				if(isset($result->oversea_addr_in_chinese))
				{
					$devotee_collection[$i]['item_description'] = $result[0]->oversea_addr_in_chinese;
          $devotee_collection[$i]['ops'] = "OA#" . $ops_count;
				}
				elseif (isset($result->address_unit1) && isset($result->address_unit2))
				{
					$devotee_collection[$i]['item_description'] = $result->address_houseno . "#" . $result->address_unit1 . '-' .
																															 $result->address_unit2 . ", " . $result->address_street . ", " . $result->address_postal;
				}

				else
				{
					$devotee_collection[$i]['item_description'] = $result->address_houseno . ", " . $result->address_street . ", " . $result->address_postal;
				}
			}
		}

		for($i = 0; $i < count($devotee_collection); $i++)
    {
      if(isset($devotee_collection[$i]->lasttransaction_at))
  		{
  			$devotee_collection[$i]->lasttransaction_at = \Carbon\Carbon::parse($devotee_collection[$i]->lasttransaction_at)->format("d/m/Y");
  		}

  		if(isset($devotee_collection[$i]->paytill_date))
  		{
  			$devotee_collection[$i]->paytill_date = \Carbon\Carbon::parse($devotee_collection[$i]->paytill_date)->format("d/m/Y");
  		}
    }

    for($i = 0; $i < count($devotee_collection); $i++)
    {
      if($devotee_collection[$i]->type == 'sameaddress')
        $devotee_collection[$i]->chinese_type = "合家";

      elseif($devotee_collection[$i]->type == 'individual')
        $devotee_collection[$i]->chinese_type = "个人";

      elseif($devotee_collection[$i]->type == 'home')
        $devotee_collection[$i]->chinese_type = "宅址";

      elseif($devotee_collection[$i]->type == 'company')
        $devotee_collection[$i]->chinese_type = "公司";

      elseif($devotee_collection[$i]->type == 'stall')
        $devotee_collection[$i]->chinese_type = "小贩";

      elseif($devotee_collection[$i]->type == 'office')
        $devotee_collection[$i]->chinese_type = "办公址";

      elseif($devotee_collection[$i]->type == 'car')
        $devotee_collection[$i]->chinese_type = "车辆";

      else
        $devotee_collection[$i]->chinese_type = "船只";
    }

		return response()->json([
			'devotee' => $devotee_collection
		]);
	}

  public function getInsertDevoteeByType(Request $request)
  {
    $devotee_id = $_GET['devotee_id'];
    $type = $_GET['type'];

    $devotee = Devotee::leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
							 ->leftjoin('familycode', 'familycode.familycode_id' , '=', 'devotee.familycode_id')
							 ->select('devotee.*', 'member.paytill_date', 'familycode.familycode')
							 ->where('devotee.devotee_id', $devotee_id)
							 ->get();

    if($type == 'car' || $type == 'ship')
    {
      $devotee = Devotee::leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
  							 ->leftjoin('familycode', 'familycode.familycode_id' , '=', 'devotee.familycode_id')
                 ->leftjoin('optionalvehicle', 'devotee.devotee_id', 'optionalvehicle.devotee_id')
  							 ->select('devotee.*', 'member.paytill_date', 'familycode.familycode', 'optionalvehicle.type',
                  'optionalvehicle.data')
  							 ->where('devotee.devotee_id', $devotee_id)
                 ->where('optionalvehicle.type', $type)
  							 ->get();

      $devotee[0]->item_description = $devotee[0]->data;
      $devotee[0]->ops = "OV#1";
    }

    elseif($type == 'home' || $type == 'company' || $type == 'stall' || $type == 'office')
    {
      $devotee = Devotee::leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
  							 ->leftjoin('familycode', 'familycode.familycode_id' , '=', 'devotee.familycode_id')
                 ->leftjoin('optionaladdress', 'devotee.devotee_id', 'optionaladdress.devotee_id')
  							 ->select('devotee.*', 'member.paytill_date', 'familycode.familycode', 'optionaladdress.type',
                  'optionaladdress.address_translated', 'optionaladdress.oversea_address')
  							 ->where('devotee.devotee_id', $devotee_id)
                 ->where('optionaladdress.type', $type)
  							 ->get();

      if(isset($devotee[0]->address_translated))
      {
        $devotee[0]->item_description = $devotee[0]->address_translated;
        $devotee[0]->ops = "OA#1";
      }
      else
      {
        $devotee[0]->item_description = $devotee[0]->oversea_address;
        $devotee[0]->ops = "OA#1";
      }
    }

    else
    {
      $devotee = Devotee::leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
  							 ->leftjoin('familycode', 'familycode.familycode_id' , '=', 'devotee.familycode_id')
  							 ->select('devotee.*', 'member.paytill_date', 'familycode.familycode')
  							 ->where('devotee.devotee_id', $devotee_id)
  							 ->get();

      if(isset($devotee[0]->oversea_addr_in_chinese))
      {
        $devotee[0]->item_description = $devotee[0]->oversea_address;
        $devotee[0]->ops = "";
        $devotee[0]->type = $type;
      }

      elseif (isset($devotee[0]->address_unit1) && isset($devotee[0]->address_unit2))
      {
        $devotee[0]->item_description = $devotee[0]->address_houseno . "#" . $devotee[0]->address_unit1 . '-' .
                                     $devotee[0]->address_unit2 . ", " . $devotee[0]->address_street . ", " . $devotee[0]->address_postal;
        $devotee[0]->ops = "";
        $devotee[0]->type = $type;
      }

      else
      {
        $devotee[0]->item_description = $devotee[0]->address_houseno . ", " . $devotee[0]->address_street . ", " . $devotee[0]->address_postal;
        $devotee[0]->ops = "";
        $devotee[0]->type = $type;
      }
    }

    if(isset($devotee[0]->lasttransaction_at))
  	{
  		$devotee[0]->lasttransaction_at = \Carbon\Carbon::parse($devotee[0]->lasttransaction_at)->format("d/m/Y");
  	}

  	if(isset($devotee[0]->paytill_date))
  	{
  		$devotee[0]->paytill_date = \Carbon\Carbon::parse($devotee[0]->paytill_date)->format("d/m/Y");
  	}

    if($devotee[0]->type == 'sameaddress')
      $devotee[0]->chinese_type = "合家";

    elseif($devotee[0]->type == 'individual')
      $devotee[0]->chinese_type = "个人";

    elseif($devotee[0]->type == 'home')
      $devotee[0]->chinese_type = "宅址";

    elseif($devotee[0]->type == 'company')
      $devotee[0]->chinese_type = "公司";

    elseif($devotee[0]->type == 'stall')
      $devotee[0]->chinese_type = "小贩";

    elseif($devotee[0]->type == 'office')
      $devotee[0]->chinese_type = "办公址";

    elseif($devotee[0]->type == 'car')
      $devotee[0]->chinese_type = "车辆";

    else
      $devotee[0]->chinese_type = "船只";

		return response()->json([
			'devotee' => $devotee
		]);

  }

  public function postXiaozaiSameFamilySetting(Request $request)
  {
    $input = array_except($request->all(), '_token');

    SettingXiaozai::where('focusdevotee_id', $input['focusdevotee_id'])
												 ->where('address_code', 'same')
                         ->where('year', null)
												 ->delete();

    if(isset($input['focusdevotee_id']))
    {
      for($i = 0; $i < count($input['devotee_id']); $i++)
      {
        $list = [
          "focusdevotee_id" => $input['focusdevotee_id'],
          "type" => $input['type'][$i],
          "xiaozai_id" => $input['hidden_xiaozai_id'][$i],
          "devotee_id" => $input['devotee_id'][$i],
          "address_code" => "same"
        ];

        SettingXiaozai::create($list);
      }
    }

    $devotee = Devotee::find($input['focusdevotee_id']);

    $xiaozai_same_focusdevotee = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
                       						->leftjoin('setting_xiaozai', 'devotee.devotee_id', '=', 'setting_xiaozai.devotee_id')
                       						->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
                       						->where('setting_xiaozai.address_code', '=', 'same')
                       						->where('setting_xiaozai.xiaozai_id', '=', '1')
                                  ->where('setting_xiaozai.year', null)
                       						->where('setting_xiaozai.focusdevotee_id', '=', $input['focusdevotee_id'])
                       						->where('setting_xiaozai.devotee_id', '=', $input['focusdevotee_id'])
                       						->select('devotee.*', 'familycode.familycode', 'member.paytill_date','setting_xiaozai.xiaozai_id',
                                    'setting_xiaozai.type')
                       						->get();

    $oa_count = 1;
		$ov_count = 1;

		for($i = 0; $i < count($xiaozai_same_focusdevotee); $i++)
		{
			if($xiaozai_same_focusdevotee[$i]['type'] == 'car' || $xiaozai_same_focusdevotee[$i]['type'] == 'ship')
			{
				$result = OptionalVehicle::where('devotee_id', $xiaozai_same_focusdevotee[$i]->devotee_id)
									->where('type', $xiaozai_same_focusdevotee[$i]['type'])
									->pluck('data');

				$xiaozai_same_focusdevotee[$i]['ops'] = "OV#" . $ov_count;
				$xiaozai_same_focusdevotee[$i]['item_description'] = $result[0];

				$ov_count++;
			}

			elseif($xiaozai_same_focusdevotee[$i]['type'] == 'home' || $xiaozai_same_focusdevotee[$i]['type'] == 'company'
						|| $xiaozai_same_focusdevotee[$i]['type'] == 'stall' || $xiaozai_same_focusdevotee[$i]['type'] == 'office')
			{
				$result = OptionalAddress::where('devotee_id', $xiaozai_same_focusdevotee[$i]->devotee_id)
									->where('type', $xiaozai_same_focusdevotee[$i]['type'])
									->get();

				if(isset($result[0]->address_translated))
				{
					$xiaozai_same_focusdevotee[$i]['ops'] = "OA#" . $oa_count;
					$xiaozai_same_focusdevotee[$i]['item_description'] = $result[0]->address_translated;
				}
				else
				{
					$xiaozai_same_focusdevotee[$i]['ops'] = "OA#" . $oa_count;
					$xiaozai_same_focusdevotee[$i]['item_description'] = $result[0]->oversea_address;
				}

				$oa_count++;
			}

			else
			{
				$result = Devotee::find($xiaozai_same_focusdevotee[$i]->devotee_id);

				if(isset($result->oversea_addr_in_chinese))
				{
					$xiaozai_same_focusdevotee[$i]['item_description'] = $result->oversea_addr_in_chinese;
					$xiaozai_same_focusdevotee[$i]->ops = "";
				}
				elseif (isset($result->address_unit1) && isset($result->address_unit2))
				{
					$xiaozai_same_focusdevotee[$i]['item_description'] = $result->address_houseno . "#" . $result->address_unit1 . '-' .
																																			$result->address_unit2 . ", " . $result->address_street . ", " . $result->address_postal;
					$xiaozai_same_focusdevotee[$i]->ops = "";
				}

				else
				{
					$xiaozai_same_focusdevotee[$i]['item_description'] = $result->address_houseno . ", " . $result->address_street . ", " . $result->address_postal;
					$xiaozai_same_focusdevotee[$i]->ops = "";
				}
			}
		}

    $xiaozai_same_family = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
													 ->leftjoin('setting_xiaozai', 'devotee.devotee_id', '=', 'setting_xiaozai.devotee_id')
											 		 ->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
													 ->where('devotee.familycode_id', $devotee->familycode_id)
													 ->where('devotee.devotee_id', '!=', $input['focusdevotee_id'])
													 ->where('setting_xiaozai.focusdevotee_id', '=', $input['focusdevotee_id'])
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
			if($xiaozai_same_family[$i]['type'] == 'car' || $xiaozai_same_family[$i]['type'] == 'ship')
			{
				$result = OptionalVehicle::where('devotee_id', $xiaozai_same_family[$i]->devotee_id)
									->where('type', $xiaozai_same_family[$i]['type'])
									->pluck('data');

				$xiaozai_same_family[$i]['ops'] = "OV#" . $ov_count;
				$xiaozai_same_family[$i]['item_description'] = $result[0];

				$ov_count++;
			}

			elseif($xiaozai_same_family[$i]['type'] == 'home' || $xiaozai_same_family[$i]['type'] == 'company'
						|| $xiaozai_same_family[$i]['type'] == 'stall' || $xiaozai_same_family[$i]['type'] == 'office')
			{
				$result = OptionalAddress::where('devotee_id', $xiaozai_same_family[$i]->devotee_id)
									->where('type', $xiaozai_same_family[$i]['type'])
									->get();

				if(isset($result[0]->address_translated))
				{
					$xiaozai_same_family[$i]['ops'] = "OA#" . $oa_count;
					$xiaozai_same_family[$i]['item_description'] = $result[0]->address_translated;

          $oa_count++;
				}
				else
				{
					$xiaozai_same_family[$i]['ops'] = "OA#" . $oa_count;
					$xiaozai_same_family[$i]['item_description'] = $result[0]->oversea_address;

          $oa_count++;
				}
			}

			else
			{
				$result = Devotee::find($xiaozai_same_family[$i]->devotee_id);

				if(isset($result->oversea_addr_in_chinese))
				{
					$xiaozai_same_family[$i]['item_description'] = $result->oversea_addr_in_chinese;
					$xiaozai_same_family[$i]->ops = "";
				}
				elseif (isset($result->address_unit1) && isset($result->address_unit2))
				{
					$xiaozai_same_family[$i]['item_description'] = $result->address_houseno . "#" . $result->address_unit1 . '-' .
																																			$result->address_unit2 . ", " . $result->address_street . ", " . $result->address_postal;
					$xiaozai_same_family[$i]->ops = "";
				}

				else
				{
					$xiaozai_same_family[$i]['item_description'] = $result->address_houseno . ", " . $result->address_street . ", " . $result->address_postal;
					$xiaozai_same_family[$i]->ops = "";
				}
			}
		}

    $xiaozai_focusdevotee = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
                            ->leftjoin('setting_xiaozai', 'devotee.devotee_id', '=', 'setting_xiaozai.devotee_id')
                            ->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
                            ->where('devotee.familycode_id', $devotee->familycode_id)
                            ->where('devotee.devotee_id', $input['focusdevotee_id'])
                            ->where('setting_xiaozai.year', null)
                            ->where('setting_xiaozai.focusdevotee_id', $input['focusdevotee_id'])
                            ->select('devotee.*', 'familycode.familycode', 'member.paytill_date', 'setting_xiaozai.xiaozai_id',
                              'setting_xiaozai.type')
                            ->get();

    $oa_count = 1;
		$ov_count = 1;

		for($i = 0; $i < count($xiaozai_focusdevotee); $i++)
		{
			if($xiaozai_focusdevotee[$i]['type'] == 'car' || $xiaozai_focusdevotee[$i]['type'] == 'ship')
			{
				$result = OptionalVehicle::where('devotee_id', $xiaozai_focusdevotee[$i]->devotee_id)
									->where('type', $xiaozai_focusdevotee[$i]['type'])
									->pluck('data');

				$xiaozai_focusdevotee[$i]['ops'] = "OV#" . $ov_count;
				$xiaozai_focusdevotee[$i]['item_description'] = $result[0];

				$ov_count++;
			}

			elseif($xiaozai_focusdevotee[$i]['type'] == 'home' || $xiaozai_focusdevotee[$i]['type'] == 'company'
						|| $xiaozai_focusdevotee[$i]['type'] == 'stall' || $xiaozai_focusdevotee[$i]['type'] == 'office')
			{
				$result = OptionalAddress::where('devotee_id', $xiaozai_focusdevotee[$i]->devotee_id)
									->where('type', $xiaozai_focusdevotee[$i]['type'])
									->get();

				if(isset($result[0]->address_translated))
				{
					$xiaozai_focusdevotee[$i]['ops'] = "OA#" . $oa_count;
					$xiaozai_focusdevotee[$i]['item_description'] = $result[0]->address_translated;
				}
				else
				{
					$xiaozai_focusdevotee[$i]['ops'] = "OA#" . $oa_count;
					$xiaozai_focusdevotee[$i]['item_description'] = $result[0]->oversea_address;
				}

				$oa_count++;
			}

			else
			{
				$result = Devotee::find($xiaozai_focusdevotee[$i]->devotee_id);

				if(isset($result->oversea_addr_in_chinese))
				{
					$xiaozai_focusdevotee[$i]['item_description'] = $result->oversea_addr_in_chinese;
					$xiaozai_focusdevotee[$i]->ops = "";
				}
				elseif (isset($result->address_unit1) && isset($result->address_unit2))
				{
					$xiaozai_focusdevotee[$i]['item_description'] = $result->address_houseno . "#" . $result->address_unit1 . '-' .
																																			$result->address_unit2 . ", " . $result->address_street . ", " . $result->address_postal;
					$xiaozai_focusdevotee[$i]->ops = "";
				}

				else
				{
					$xiaozai_focusdevotee[$i]['item_description'] = $result->address_houseno . ", " . $result->address_street . ", " . $result->address_postal;
					$xiaozai_focusdevotee[$i]->ops = "";
				}
			}
		}

    $xiaozai_setting_samefamily = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
                                  ->leftjoin('setting_xiaozai', 'setting_xiaozai.devotee_id', '=', 'devotee.devotee_id')
                                  ->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
                                  ->where('devotee.devotee_id', '!=', $input['focusdevotee_id'])
                                  ->where('devotee.familycode_id', $devotee->familycode_id)
                                  ->where('setting_xiaozai.focusdevotee_id', '=', $input['focusdevotee_id'])
                                  ->where('setting_xiaozai.address_code', '=', 'same')
                                  ->select('devotee.*', 'member.paytill_date', 'familycode.familycode', 'setting_xiaozai.xiaozai_id', 'setting_xiaozai.type')
                                  ->get();

    $oa_count = 1;
		$ov_count = 1;

		for($i = 0; $i < count($xiaozai_setting_samefamily); $i++)
		{
			if($xiaozai_setting_samefamily[$i]['type'] == 'car' || $xiaozai_setting_samefamily[$i]['type'] == 'ship')
			{
				$result = OptionalVehicle::where('devotee_id', $xiaozai_setting_samefamily[$i]->devotee_id)
									->where('type', $xiaozai_setting_samefamily[$i]['type'])
									->pluck('data');

				$xiaozai_setting_samefamily[$i]['ops'] = "OV#" . $ov_count;
				$xiaozai_setting_samefamily[$i]['item_description'] = $result[0];

				$ov_count++;
			}

			elseif($xiaozai_setting_samefamily[$i]['type'] == 'home' || $xiaozai_setting_samefamily[$i]['type'] == 'company'
						|| $xiaozai_setting_samefamily[$i]['type'] == 'stall' || $xiaozai_setting_samefamily[$i]['type'] == 'office')
			{
				$result = OptionalAddress::where('devotee_id', $xiaozai_setting_samefamily[$i]->devotee_id)
									->where('type', $xiaozai_setting_samefamily[$i]['type'])
									->get();

				if(isset($result[0]->address_translated))
				{
					$xiaozai_setting_samefamily[$i]['ops'] = "OA#" . $oa_count;
					$xiaozai_setting_samefamily[$i]['item_description'] = $result[0]->address_translated;
				}
				else
				{
					$xiaozai_setting_samefamily[$i]['ops'] = "OA#" . $oa_count;
					$xiaozai_setting_samefamily[$i]['item_description'] = $result[0]->oversea_address;
				}

				$oa_count++;
			}

			else
			{
				$result = Devotee::find($xiaozai_setting_samefamily[$i]->devotee_id);

				if(isset($result->oversea_addr_in_chinese))
				{
					$xiaozai_setting_samefamily[$i]['item_description'] = $result->oversea_addr_in_chinese;
					$xiaozai_setting_samefamily[$i]->ops = "";
				}
				elseif (isset($result->address_unit1) && isset($result->address_unit2))
				{
					$xiaozai_setting_samefamily[$i]['item_description'] = $result->address_houseno . "#" . $result->address_unit1 . '-' .
																																			$result->address_unit2 . ", " . $result->address_street . ", " . $result->address_postal;
					$xiaozai_setting_samefamily[$i]->ops = "";
				}

				else
				{
					$xiaozai_setting_samefamily[$i]['item_description'] = $result->address_houseno . ", " . $result->address_street . ", " . $result->address_postal;
					$xiaozai_setting_samefamily[$i]->ops = "";
				}
			}
		}

    Session::put('xiaozai_setting_samefamily', $xiaozai_setting_samefamily);
    Session::put('xiaozai_focusdevotee', $xiaozai_focusdevotee);
    Session::put('xiaozai_same_focusdevotee', $xiaozai_same_focusdevotee);
    Session::put('xiaozai_same_family', $xiaozai_same_family);

    $request->session()->flash('success', 'Setting for same address is successfully created.');
		return redirect()->back();
  }

  public function postXiaozaiDifferentFamilySetting(Request $request)
  {
    $input = array_except($request->all(), '_token');

    SettingXiaozai::where('focusdevotee_id', $input['focusdevotee_id'])
												 ->where('address_code', 'different')
                         ->where('year', null)
												 ->delete();

    if(isset($input['devotee_id']))
		{
			for($i = 0; $i < count($input['devotee_id']); $i++)
			{
				$list = [
					"focusdevotee_id" => $input['focusdevotee_id'],
          "type" => $input['type'][$i],
	        "xiaozai_id" => $input['hidden_xiaozai_id'][$i],
					"devotee_id" => $input['devotee_id'][$i],
	        "address_code" => "different",
	        "year" => null
				];

				SettingXiaozai::create($list);
			}
		}

    $devotee = Devotee::find($input['focusdevotee_id']);

    $xiaozai_different_family = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
																->leftjoin('setting_xiaozai', 'devotee.devotee_id', '=', 'setting_xiaozai.devotee_id')
																->where('setting_xiaozai.address_code', '=', 'different')
																->where('setting_xiaozai.xiaozai_id', '=', '1')
																->where('setting_xiaozai.focusdevotee_id', '=', $input['focusdevotee_id'])
                                ->where('year', null)
																->select('devotee.*', 'familycode.familycode', 'setting_xiaozai.type')
																->get();

    $oa_count = 1;
    $ov_count = 1;

    for($i = 0; $i < count($xiaozai_different_family); $i++)
		{
			if($xiaozai_different_family[$i]['type'] == 'car' || $xiaozai_different_family[$i]['type'] == 'ship')
			{
				$result = OptionalVehicle::where('devotee_id', $xiaozai_different_family[0]->devotee_id)
									->where('type', $xiaozai_different_family[$i]['type'])
									->pluck('data');

				$xiaozai_different_family[$i]['item_description'] = $result[0];
        $xiaozai_different_family[$i]['ops'] = "OV#" . $ov_count;

        $ov_count++;
			}

			elseif($xiaozai_different_family[$i]['type'] == 'home' || $xiaozai_different_family[$i]['type'] == 'company'
						|| $xiaozai_different_family[$i]['type'] == 'stall' || $xiaozai_different_family[$i]['type'] == 'office')
			{
				$result = OptionalAddress::where('devotee_id', $input['focusdevotee_id'])
									->where('type', $xiaozai_different_family[$i]['type'])
									->get();

				if(isset($result[0]->address_translated))
				{
					$xiaozai_different_family[$i]['item_description'] = $result[0]->address_translated;
          $xiaozai_different_family[$i]['ops'] = "OA#" . $oa_count;
				}
				else
				{
					$xiaozai_different_family[$i]['item_description'] = $result[0]->oversea_address;
          $xiaozai_different_family[$i]['ops'] = "OA#" . $oa_count;
				}

        $oa_count++;
			}

			else
			{
				$result = Devotee::find($input['focusdevotee_id']);

				if(isset($result->oversea_addr_in_chinese))
				{
					$xiaozai_different_family[$i]['item_description'] = $result[0]->oversea_addr_in_chinese;
          $xiaozai_different_family[$i]['ops'] = "OA#" . $ops_count;
				}
				elseif (isset($result->address_unit1) && isset($result->address_unit2))
				{
					$xiaozai_different_family[$i]['item_description'] = $result->address_houseno . "#" . $result->address_unit1 . '-' .
																															 $result->address_unit2 . ", " . $result->address_street . ", " . $result->address_postal;
				}

				else
				{
					$xiaozai_different_family[$i]['item_description'] = $result->address_houseno . ", " . $result->address_street . ", " . $result->address_postal;
				}
			}
		}

    for($i = 0; $i < count($xiaozai_different_family); $i++)
    {
      if(isset($xiaozai_different_family[$i]->lasttransaction_at))
  		{
  			$xiaozai_different_family[$i]->lasttransaction_at = \Carbon\Carbon::parse($xiaozai_different_family[$i]->lasttransaction_at)->format("d/m/Y");
  		}

  		if(isset($xiaozai_different_family[$i]->paytill_date))
  		{
  			$xiaozai_different_family[$i]->paytill_date = \Carbon\Carbon::parse($xiaozai_different_family[$i]->paytill_date)->format("d/m/Y");
  		}
    }

    $xiaozai_setting_differentfamily = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
        															 ->leftjoin('setting_xiaozai', 'setting_xiaozai.devotee_id', '=', 'devotee.devotee_id')
        															 ->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
        															 ->where('setting_xiaozai.focusdevotee_id', '=', $input['focusdevotee_id'])
        															 ->where('setting_xiaozai.address_code', '=', 'different')
                                       ->where('year', null)
        															 ->select('devotee.*', 'member.paytill_date', 'familycode.familycode',
        															 'setting_xiaozai.type', 'setting_xiaozai.xiaozai_id')
        															 ->get();

    $different_oa_count = 1;
    $different_ov_count = 1;

    for($i = 0; $i < count($xiaozai_setting_differentfamily); $i++)
		{
			if($xiaozai_setting_differentfamily[$i]['type'] == 'car' || $xiaozai_setting_differentfamily[$i]['type'] == 'ship')
			{
				$result = OptionalVehicle::where('devotee_id', $xiaozai_setting_differentfamily[0]->devotee_id)
									->where('type', $xiaozai_setting_differentfamily[$i]['type'])
									->pluck('data');

				$xiaozai_setting_differentfamily[$i]['item_description'] = $result[0];
        $xiaozai_setting_differentfamily[$i]['ops'] = "OV#" . $different_ov_count;

        $different_ov_count++;
			}

			elseif($xiaozai_setting_differentfamily[$i]['type'] == 'home' || $xiaozai_setting_differentfamily[$i]['type'] == 'company'
						|| $xiaozai_setting_differentfamily[$i]['type'] == 'stall' || $xiaozai_setting_differentfamily[$i]['type'] == 'office')
			{
				$result = OptionalAddress::where('devotee_id', $xiaozai_setting_differentfamily[$i]['devotee_id'])
									->where('type', $xiaozai_setting_differentfamily[$i]['type'])
									->get();

				if(isset($result[0]->address_translated))
				{
					$xiaozai_setting_differentfamily[$i]['item_description'] = $result[0]->address_translated;
          $xiaozai_setting_differentfamily[$i]['ops'] = "OA#" . $different_oa_count;
				}
				else
				{
					$xiaozai_setting_differentfamily[$i]['item_description'] = $result[0]->oversea_address;
          $xiaozai_setting_differentfamily[$i]['ops'] = "OA#" . $different_oa_count;
				}

        $oa_count++;
			}

			else
			{
				$result = Devotee::find($input['focusdevotee_id']);

				if(isset($result->oversea_addr_in_chinese))
				{
					$xiaozai_setting_differentfamily[$i]['item_description'] = $result[0]->oversea_addr_in_chinese;
          $xiaozai_setting_differentfamily[$i]['ops'] = "";
				}
				elseif (isset($result->address_unit1) && isset($result->address_unit2))
				{
					$xiaozai_setting_differentfamily[$i]['item_description'] = $result->address_houseno . "#" . $result->address_unit1 . '-' .
																															 $result->address_unit2 . ", " . $result->address_street . ", " . $result->address_postal;

          $xiaozai_setting_differentfamily[$i]['ops'] = "";
				}

				else
				{
					$xiaozai_setting_differentfamily[$i]['item_description'] = $result->address_houseno . ", " . $result->address_street . ", " . $result->address_postal;
          $xiaozai_setting_differentfamily[$i]['ops'] = "";
				}
			}
		}

    Session::put('xiaozai_different_family', $xiaozai_different_family);
		Session::put('xiaozai_setting_differentfamily', $xiaozai_setting_differentfamily);

    $request->session()->flash('success', 'Setting for different address is successfully created.');
		return redirect()->back();
  }

  public function getTransactionDetail(Request $request)
  {
    $input = array_except($request->all(), '_token');

    if(isset($input['trans_no']))
		{
			$trans = XiaozaiGeneraldonation::where('trans_no', $input['trans_no'])->first();

			if(count($trans) > 0)
			{
        $xiaozai_generaldonation = new XiaozaiGeneraldonation;
        $result = $xiaozai_generaldonation->searchTransaction($input)->get();
			}

			else
			{
				return response()->json(array(
					 'msg' => 'No Result Found'
				));
			}

      for($i = 0; $i < count($result); $i++)
  		{
  			if($result[$i]->type == 'car' || $result[$i]->type == 'ship')
  			{
  				$optional_result = OptionalVehicle::where('devotee_id', $result[$i]->devotee_id)
  									->where('type', $result[$i]->type)
  									->pluck('data');

  				$result[$i]->item_description = $optional_result[0];
  			}

  			elseif($result[$i]->type == 'home' || $result[$i]->type == 'company'
  						|| $result[$i]->type == 'stall' || $result[$i]->type == 'office')
  			{
  				$optional_result = OptionalAddress::where('devotee_id', $result[$i]->devotee_id)
            								 ->where('type', $result[$i]->type)
            								 ->get();

  				if(isset($optional_result[0]->address_translated))
  				{
  					$result[$i]->item_description = $optional_result[0]->address_translated;
  				}
  				else
  				{
  					$result[$i]->item_description = $optional_result[0]->oversea_address;
  				}
  			}

  			else
  			{
  				$devotee_result = Devotee::find($result[$i]->devotee_id);

  				if(isset($devotee_result->oversea_addr_in_chinese))
  				{
  					$result[$i]->item_description = $devotee_result->oversea_addr_in_chinese;
  				}
  				elseif (isset($devotee_result->address_unit1) && isset($devotee_result->address_unit2))
  				{
  					$result[$i]->item_description = $devotee_result->address_houseno . "#" . $devotee_result->address_unit1 . '-' .
  																					$devotee_result->address_unit2 . ", " . $devotee_result->address_street . ", " . $devotee_result->address_postal;
  				}

  				else
  				{
  					$result[$i]->item_description = $devotee_result->address_houseno . ", " . $devotee_result->address_street . ", " . $devotee_result->address_postal;
  				}
  			}
  		}

      for($i = 0; $i < count($result); $i++)
      {
        if($result[$i]->type == 'sameaddress')
        {
          $result[$i]->chinese_type = "合家";
        }

        elseif($result[$i]->type == 'individual')
        {
          $result[$i]->chinese_type = "个人";
        }

        elseif($result[$i]->type == 'home')
        {
          $result[$i]->chinese_type = "宅址";
        }

        elseif($result[$i]->type == 'company')
        {
          $result[$i]->chinese_type = "公司";
        }

        elseif($result[$i]->type == 'stall')
        {
          $result[$i]->chinese_type = "小贩";
        }

        elseif($result[$i]->type == 'office')
        {
          $result[$i]->chinese_type = "办公址";
        }

        elseif($result[$i]->type == 'car')
        {
          $result[$i]->chinese_type = "车辆";
        }

        else
        {
          $result[$i]->chinese_type = "船只";
        }
      }

			$cancellation = XiaozaiReceipt::leftjoin('xiaozai_generaldonation', 'xiaozai_receipt.generaldonation_id', '=', 'xiaozai_generaldonation.generaldonation_id')
											->leftjoin('user', 'xiaozai_receipt.cancelled_by', '=', 'user.id')
											->where('xiaozai_generaldonation.trans_no', $input['trans_no'])
											->select('xiaozai_receipt.cancelled_date', 'user.first_name', 'user.last_name')
											->GroupBy('xiaozai_generaldonation.generaldonation_id')
											->get();

			if(isset($cancellation[0]->cancelled_date))
			{
				$cancellation[0]->cancelled_date = Carbon::parse($cancellation[0]->cancelled_date)->format("d/m/Y");
			}
		}

    else
		{
			$receipt = XiaozaiReceipt::where('receipt_no', $input['receipt_no'])->first();

			if(count($receipt) > 0)
			{
        $xiaozai_generaldonation = new XiaozaiGeneraldonation;
        $result = $xiaozai_generaldonation->searchTransaction($input)->get();
			}

			else
			{
				return response()->json(array(
			     'msg' => 'No Result Found'
			  ));
			}

      for($i = 0; $i < count($result); $i++)
  		{
  			if($result[$i]->type == 'car' || $result[$i]->type == 'ship')
  			{
  				$optional_result = OptionalVehicle::where('devotee_id', $result[$i]->devotee_id)
  									->where('type', $result[$i]->type)
  									->pluck('data');

  				$result[$i]->item_description = $optional_result[0];
  			}

  			elseif($result[$i]->type == 'home' || $result[$i]->type == 'company'
  						|| $result[$i]->type == 'stall' || $result[$i]->type == 'office')
  			{
  				$optional_result = OptionalAddress::where('devotee_id', $result[$i]->devotee_id)
            								 ->where('type', $result[$i]->type)
            								 ->get();

  				if(isset($optional_result[0]->address_translated))
  				{
  					$result[$i]->item_description = $optional_result[0]->address_translated;
  				}
  				else
  				{
  					$result[$i]->item_description = $optional_result[0]->oversea_address;
  				}
  			}

  			else
  			{
  				$devotee_result = Devotee::find($result[$i]->devotee_id);

  				if(isset($devotee_result->oversea_addr_in_chinese))
  				{
  					$result[$i]->item_description = $devotee_result->oversea_addr_in_chinese;
  				}
  				elseif (isset($devotee_result->address_unit1) && isset($devotee_result->address_unit2))
  				{
  					$result[$i]->item_description = $devotee_result->address_houseno . "#" . $devotee_result->address_unit1 . '-' .
  																					$devotee_result->address_unit2 . ", " . $devotee_result->address_street . ", " . $devotee_result->address_postal;
  				}

  				else
  				{
  					$result[$i]->item_description = $devotee_result->address_houseno . ", " . $devotee_result->address_street . ", " . $devotee_result->address_postal;
  				}
  			}
  		}

      for($i = 0; $i < count($result); $i++)
      {
        if($result[$i]->type == 'sameaddress')
        {
          $result[$i]->chinese_type = "合家";
        }

        elseif($result[$i]->type == 'individual')
        {
          $result[$i]->chinese_type = "个人";
        }

        elseif($result[$i]->type == 'home')
        {
          $result[$i]->chinese_type = "宅址";
        }

        elseif($result[$i]->type == 'company')
        {
          $result[$i]->chinese_type = "公司";
        }

        elseif($result[$i]->type == 'stall')
        {
          $result[$i]->chinese_type = "小贩";
        }

        elseif($result[$i]->type == 'office')
        {
          $result[$i]->chinese_type = "办公址";
        }

        elseif($result[$i]->type == 'car')
        {
          $result[$i]->chinese_type = "车辆";
        }

        else
        {
          $result[$i]->chinese_type = "船只";
        }
      }

			$cancellation = XiaozaiReceipt::leftjoin('xiaozai_generaldonation', 'xiaozai_receipt.generaldonation_id', '=', 'xiaozai_generaldonation.generaldonation_id')
											->leftjoin('user', 'xiaozai_receipt.cancelled_by', '=', 'user.id')
											->where('xiaozai_receipt.receipt_no', $input['receipt_no'])
											->select('xiaozai_receipt.cancelled_date', 'user.first_name', 'user.last_name')
											->GroupBy('xiaozai_generaldonation.generaldonation_id')
											->get();

			if(isset($cancellation[0]->cancelled_date))
			{
				$cancellation[0]->cancelled_date = Carbon::parse($cancellation[0]->cancelled_date)->format("d/m/Y");
			}
		}

    // Check Transaction devotee and focus devotee is the same
		$focusdevotee = Session::get('focus_devotee');

		if(count($focusdevotee) == 0)
		{
			return response()->json(array(
	      'msg' => 'Please select Focus Devotee.'
	    ));
		}

    if($focusdevotee[0]->devotee_id != $result[0]->focusdevotee_id)
		{
			return response()->json(array(
	      'msg' => 'Search receipt no or transaction no by focus devotee.'
	    ));
		}

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
    $samefamily_no = 0;

    if(isset($input['receipt_no']))
		{
			$receipts = XiaozaiReceipt::leftjoin('kongdan_generaldonation', 'xiaozai_receipt.generaldonation_id', '=', 'xiaozai_generaldonation.generaldonation_id')
								 ->leftjoin('devotee', 'xiaozai_receipt.devotee_id', '=', 'devotee.devotee_id')
								 ->leftjoin('user', 'xiaozai_receipt.staff_id', '=', 'user.id')
								 ->leftjoin('festiveevent', 'xiaozai_generaldonation.festiveevent_id', '=', 'festiveevent.festiveevent_id')
								 ->where('xiaozai_receipt.receipt_no', '=', $input['receipt_no'])
								 ->select('xiaozai_receipt.*', 'devotee.chinese_name', 'devotee.oversea_addr_in_chinese', 'devotee.address_houseno', 'devotee.address_unit1',
								 	'devotee.address_unit2', 'devotee.address_street', 'devotee.address_postal', 'devotee.familycode_id',
								 	'xiaozai_generaldonation.focusdevotee_id', 'xiaozai_generaldonation.trans_no', 'user.first_name', 'user.last_name',
								 	'festiveevent.start_at', 'festiveevent.time', 'festiveevent.event', 'festiveevent.lunar_date', 'xiaozai_generaldonation.mode_payment')
								 ->get();

			$receipts[0]->trans_date = \Carbon\Carbon::parse($receipts[0]->trans_date)->format("d/m/Y");
			$receipts[0]->start_at = \Carbon\Carbon::parse($receipts[0]->start_at)->format("d/m/Y");

			$print_format = 'hj';

			$paid_by = Devotee::where('devotee.devotee_id', $receipts[0]->focusdevotee_id)
								 ->select('chinese_name', 'devotee_id')
								 ->get();
		}

    if(isset($input['trans_no']))
		{
			$receipts = XiaozaiReceipt::leftjoin('xiaozai_generaldonation', 'xiaozai_receipt.generaldonation_id', '=', 'xiaozai_generaldonation.generaldonation_id')
								 ->leftjoin('devotee', 'xiaozai_receipt.devotee_id', '=', 'devotee.devotee_id')
								 ->leftjoin('user', 'xiaozai_receipt.staff_id', '=', 'user.id')
								 ->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
								 ->leftjoin('festiveevent', 'xiaozai_generaldonation.festiveevent_id', '=', 'festiveevent.festiveevent_id')
								 ->where('xiaozai_generaldonation.trans_no', '=', $input['trans_no'])
								 ->select('xiaozai_receipt.*', 'member.paytill_date', 'member.member_id', 'devotee.chinese_name', 'devotee.oversea_addr_in_chinese', 'devotee.address_houseno', 'devotee.address_unit1',
								 	'devotee.address_unit2', 'devotee.address_street', 'devotee.address_postal', 'devotee.familycode_id',
								 	'xiaozai_generaldonation.focusdevotee_id', 'xiaozai_generaldonation.trans_no', 'user.first_name', 'user.last_name',
								 	'festiveevent.start_at', 'festiveevent.time', 'festiveevent.event', 'festiveevent.lunar_date', 'xiaozai_generaldonation.mode_payment')
								 ->get();

			for($i = 0; $i < count($receipts); $i++)
			{
				$receipts[$i]->trans_date = \Carbon\Carbon::parse($receipts[$i]->trans_date)->format("d/m/Y");
				$receipts[$i]->start_at = \Carbon\Carbon::parse($receipts[$i]->start_at)->format("d/m/Y");
			}

			$familycode_id = $receipts[0]->familycode_id;

			for($i = 0; $i < count($receipts); $i++)
			{
				if($familycode_id == $receipts[$i]->familycode_id)
				{
					$samefamily_no += 1;
					$total_amount += intval($receipts[$i]->amount);
				}

				// $familycode_id = $receipts[0]->familycode_id;
			}

      for($i = 0; $i < count($receipts); $i++)
      {
        if($receipts[$i]->type == 'sameaddress')
        {
          $receipts[$i]->chinese_type = "合家";
        }

        elseif($receipts[$i]->type == 'individual')
        {
          $receipts[$i]->chinese_type = "个人";
        }

        elseif($receipts[$i]->type == 'home')
        {
          $receipts[$i]->chinese_type = "宅址";
        }

        elseif($receipts[$i]->type == 'company')
        {
          $receipts[$i]->chinese_type = "公司";
        }

        elseif($receipts[$i]->type == 'stall')
        {
          $receipts[$i]->chinese_type = "小贩";
        }

        elseif($receipts[$i]->type == 'office')
        {
          $receipts[$i]->chinese_type = "办公址";
        }

        elseif($receipts[$i]->type == 'car')
        {
          $receipts[$i]->chinese_type = "车辆";
        }

        else
        {
          $receipts[$i]->chinese_type = "船只";
        }
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

    return view('fahui.xiaozai_print', [
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
				$generaldonation = XiaozaiGeneraldonation::where('trans_no', $input['transaction_no'])->first();

				// Update Cancellation Status
				$xiaozai_receipts = XiaozaiReceipt::where('generaldonation_id', $generaldonation->generaldonation_id)
        									  ->update([
        							        'cancelled_date' => Carbon::now(),
        							        'status' => "cancelled",
        							        'cancelled_by' => Auth::user()->id
        							     ]);

				$cancellation_receipts = XiaozaiReceipt::leftjoin('user', 'user.id', '=', 'xiaozai_receipt.cancelled_by')
																 ->where('xiaozai_receipt.generaldonation_id', '=', $generaldonation->generaldonation_id)
																 ->select('xiaozai_receipt.cancelled_date', 'user.first_name', 'user.last_name')
																 ->GroupBy('xiaozai_receipt.generaldonation_id')
																 ->get();

				$cancelled_date = \Carbon\Carbon::parse($cancellation_receipts[0]->cancelled_date)->format("d/m/Y");

				$focus_devotee = Session::get('focus_devotee');

				$xiaozai_receipts = XiaozaiGeneraldonation::leftjoin('devotee', 'devotee.devotee_id', '=', 'xiaozai_generaldonation.focusdevotee_id')
        										->leftjoin('xiaozai_receipt', 'xiaozai_receipt.generaldonation_id', '=', 'xiaozai_generaldonation.generaldonation_id')
        										->where('xiaozai_generaldonation.focusdevotee_id', '=', $focus_devotee[0]->devotee_id)
        										->whereIn('xiaozai_receipt.glcode_id', array(118, 120))
        										->GroupBy('xiaozai_generaldonation.generaldonation_id')
        										->select('xiaozai_generaldonation.*', 'devotee.chinese_name', 'xiaozai_receipt.cancelled_date')
        										->orderBy('xiaozai_generaldonation.generaldonation_id', 'desc')
        										->get();

				if(count($xiaozai_receipts) > 0)
				{
					for($i = 0; $i < count($xiaozai_receipts); $i++)
					{
						$data = XiaozaiReceipt::where('generaldonation_id', $xiaozai_receipts[$i]->generaldonation_id)->pluck('receipt_no');
						$receipt_count = count($data);
						$xiaozai_receipts[$i]->receipt_no = $data[0] . ' - ' . $data[$receipt_count - 1];
					}
				}

				Session::put('xiaozai_receipts', $xiaozai_receipts);
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
					$receipt = XiaozaiReceipt::where('receipt_no', $input['receipt_no'])->get();
					$result = XiaozaiReceipt::find($receipt[0]['receipt_id']);

					$generaldonation = XiaozaiGeneraldonation::where('generaldonation_id', $receipt[0]['generaldonation_id'])->get();

					$focusdevotee_id = $generaldonation[0]->focusdevotee_id;

					$result->cancelled_date = Carbon::now();
					$result->status = "cancelled";
					$result->cancelled_by = Auth::user()->id;

					$cancellation = $result->save();
				}

				if(!empty($input['trans_no']))
				{
					$generaldonation = XiaozaiGeneraldonation::where('trans_no', $input['trans_no'])->get();

					$focusdevotee_id = $generaldonation[0]->focusdevotee_id;

					$receipt = XiaozaiReceipt::where('generaldonation_id', $generaldonation[0]->generaldonation_id)->get();
          $total_devotee = count($receipt);

					for($i = 0; $i < count($receipt); $i++)
					{
						$result = XiaozaiReceipt::find($receipt[$i]['receipt_id']);

						$result->cancelled_date = Carbon::now();
						$result->status = "cancelled";
						$result->cancelled_by = Auth::user()->id;

						$cancellation = $result->save();
					}
				}

				$focus_devotee = Session::get('focus_devotee');

				$xiaozai_receipts = XiaozaiGeneraldonation::leftjoin('devotee', 'devotee.devotee_id', '=', 'xiaozai_generaldonation.focusdevotee_id')
        				            ->leftjoin('xiaozai_receipt', 'xiaozai_receipt.generaldonation_id', '=', 'xiaozai_generaldonation.generaldonation_id')
        				            ->where('xiaozai_generaldonation.focusdevotee_id', '=', $focus_devotee[0]->devotee_id)
        				            ->where('xiaozai_receipt.glcode_id', 117)
        				            ->GroupBy('xiaozai_generaldonation.generaldonation_id')
        				            ->select('xiaozai_generaldonation.*', 'devotee.chinese_name', 'xiaozai_receipt.cancelled_date')
        				            ->orderBy('xiaozai_generaldonation.generaldonation_id', 'desc')
        				            ->get();

				if(count($xiaozai_receipts) > 0)
				{
				  for($i = 0; $i < count($xiaozai_receipts); $i++)
				  {
				    $data = XiaozaiReceipt::where('generaldonation_id', $xiaozai_receipts[$i]->generaldonation_id)->pluck('receipt_no');
				    $receipt_count = count($data);
				    $xiaozai_receipts[$i]->receipt_no = $data[0] . ' - ' . $data[$receipt_count - 1];
				  }
				}

				Session::put('xiaozai_receipts', $xiaozai_receipts);

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
}
