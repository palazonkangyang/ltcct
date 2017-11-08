<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\User;
use App\Models\Devotee;
use App\Models\FestiveEvent;
use App\Models\GlCode;
use App\Models\JournalEntry;
use App\Models\JournalEntryItem;
use App\Models\SettingQifu;
use App\Models\QifuGeneraldonation;
use App\Models\QifuReceipt;
use Auth;
use DB;
use Hash;
use Mail;
use Input;
use Session;
use View;
use URL;
use Carbon\Carbon;

class QiFuController extends Controller
{
  public function getQiFu()
  {
    $today = Carbon::today();

		$events = FestiveEvent::orderBy('start_at', 'asc')
							->where('start_at', '>', $today)
							->take(1)
							->get();

    return view('fahui.qifu', [
			'events' => $events
		]);
  }

  public function postQiFu(Request $request)
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

    if(count(QifuGeneraldonation::all()) > 0)
    {
      $trans_id = QifuGeneraldonation::all()->last()->generaldonation_id;
    }

    else {
      $trans_id = 0;
    }

    $prefix = "T";
	  $trans_id += 1;

    $year = date('Y');
    $year = substr( $year, -2);

    $trans_id = str_pad($trans_id, 4, 0, STR_PAD_LEFT);
    $trans_id = $prefix . $year . $trans_id;

    if(empty($input['festiveevent_id']))
		{
			$input['festiveevent_id'] = 0;
		}

    $data = [
	    "trans_no" => $trans_id,
	    "description" => "QiFu - 祈福",
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

    $qifu_generaldonation = QifuGeneraldonation::create($data);

    if($qifu_generaldonation)
		{
      for($i = 0; $i < count($input['hidden_qifu_amount']); $i++)
      {
        if($input['hidden_qifu_amount'][$i] == 1)
        {
          $devotee = Devotee::find($input['devotee_id'][$i]);

          $devotee->lasttransaction_at = Carbon::now();
          $devotee->save();

          if(count(QifuReceipt::all()) > 0)
          {
            $same_receipt = QifuReceipt::all()->last()->receipt_id;
          }

          else {
            $result = GlCode::where('glcode_id', '136')->pluck('next_sn_number');
            $same_receipt = $result[0];
          }

          $prefix = GlCode::where('glcode_id', '136')->pluck('receipt_prefix');
          $prefix = $prefix[0];
          $same_receipt += 1;

          $year = date('Y');
          $year = substr( $year, -2);

          $receipt = str_pad($same_receipt, 4, 0, STR_PAD_LEFT);
          $receipt = $prefix . $year . $receipt;

          $data = [
            "receipt_no" => $receipt,
            "trans_date" => Carbon::now(),
            "description" => "QiFu - 祈福",
            "amount" => 10,
            "glcode_id" => 136,
            "devotee_id" => $input['devotee_id'][$i],
            "generaldonation_id" => $qifu_generaldonation->generaldonation_id,
            "staff_id" => Auth::user()->id
          ];

          QifuReceipt::create($data);
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
      "description" => "QiFu - 祈福",
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
			"glcode_id" => 136,
			"debit_amount" => null,
			"credit_amount" => $input['total_amount'],
			"journalentry_id" => $journalentry->journalentry_id
		];

		JournalEntryItem::create($data);

    // remove session
	  Session::forget('qifu_receipts');

    $devotee = Devotee::where('devotee_id', $input['focusdevotee_id'])->get();

    $qifu_same_family = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
                        ->leftjoin('setting_qifu', 'devotee.devotee_id', '=', 'setting_qifu.devotee_id')
                        ->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
                        ->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
                        ->where('devotee.familycode_id', $devotee[0]->familycode_id)
												->where('devotee.devotee_id', '!=', $devotee[0]->devotee_id)
                        ->where('setting_qifu.address_code', '=', 'same')
                        ->where('setting_qifu.qifu_id', '=', '1')
												->where('setting_qifu.focusdevotee_id', '=', $devotee[0]->devotee_id)
                        ->select('devotee.*', 'member.member', 'familycode.familycode', 'member.paytill_date', 'specialremarks.devotee_id as specialremarks_devotee_id')
												->GroupBy('devotee.devotee_id')
                        ->get();

		for($i = 0; $i < count($qifu_same_family); $i++)
		{
			$hasreceipt = QifuReceipt::where('devotee_id', $qifu_same_family[$i]->devotee_id)->get();

			if(count($hasreceipt) > 0)
			{
				$same_receipt_no = QifuReceipt::all()
													 ->where('devotee_id', $qifu_same_family[$i]->devotee_id)
													 ->last()
													 ->receipt_no;

				$qifu_same_family[$i]->receipt_no = $same_receipt_no;
			}

			else {
				$qifu_same_family[$i]->receipt_no = "";
			}
		}

    $qifu_same_focusdevotee = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
			                        ->leftjoin('setting_qifu', 'devotee.devotee_id', '=', 'setting_qifu.devotee_id')
			                        ->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
			                        ->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
			                        ->where('setting_qifu.address_code', '=', 'same')
			                        ->where('setting_qifu.qifu_id', '=', '1')
															->where('setting_qifu.focusdevotee_id', '=', $devotee[0]->devotee_id)
															->where('setting_qifu.devotee_id', '=', $devotee[0]->devotee_id)
			                        ->select('devotee.*', 'member.member', 'familycode.familycode', 'member.paytill_date', 'specialremarks.devotee_id as specialremarks_devotee_id')
															->GroupBy('devotee.devotee_id')
			                        ->get();

    $hasreceipt = QifuReceipt::where('devotee_id', $qifu_same_focusdevotee[0]->devotee_id)->get();

    if(count($hasreceipt) > 0)
    {
      $same_receipt_no = QifuReceipt::all()
                         ->where('devotee_id', $qifu_same_focusdevotee[0]->devotee_id)
                         ->last()
                         ->receipt_no;

      $qifu_same_focusdevotee[0]->receipt_no = $same_receipt_no;
    }

    else {
      $qifu_same_focusdevotee[0]->receipt_no = "";
    }

    $qifu_focusdevotee = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
                         ->leftjoin('setting_qifu', 'devotee.devotee_id', '=', 'setting_qifu.devotee_id')
                         ->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
                         ->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
                         ->where('devotee.devotee_id', $devotee[0]->devotee_id)
                         ->where('setting_qifu.focusdevotee_id', $devotee[0]->devotee_id)
                         ->where('setting_qifu.year', null)
                         ->select('devotee.*', 'member.member', 'familycode.familycode', 'member.paytill_date', 'specialremarks.devotee_id as specialremarks_devotee_id', 'setting_qifu.qifu_id')
                         ->GroupBy('devotee.devotee_id')
                         ->get();

    $hasreceipt = QifuReceipt::where('devotee_id', $qifu_focusdevotee[0]->devotee_id)->get();

    if(count($hasreceipt) > 0)
    {
      $same_receipt_no = QifuReceipt::all()
                         ->where('devotee_id', $qifu_focusdevotee[0]->devotee_id)
                         ->last()
                         ->receipt_no;

      $qifu_focusdevotee[0]->receipt_no = $same_receipt_no;
    }

    else {
      $qifu_focusdevotee[0]->receipt_no = "";
    }

    $qifu_setting_differentfamily = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
															      ->leftjoin('setting_qifu', 'setting_qifu.devotee_id', '=', 'devotee.devotee_id')
															      ->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
															      ->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
															      ->where('setting_qifu.focusdevotee_id', '=', $devotee[0]->devotee_id)
															      ->where('setting_qifu.address_code', '=', 'different')
																		->where('setting_qifu.year', null)
															      ->select('devotee.*', 'member.member', 'member.paytill_date', 'specialremarks.devotee_id as specialremarks_devotee_id', 'familycode.familycode', 'setting_qifu.qifu_id')
															      ->GroupBy('devotee.devotee_id')
															      ->get();

    for($i = 0; $i < count($qifu_setting_differentfamily); $i++)
		{
			$hasreceipt = QifuReceipt::where('devotee_id', $qifu_setting_differentfamily[0]->devotee_id)->get();

			if(count($hasreceipt) > 0)
			{
				$same_receipt_no = QifuReceipt::all()
													 ->where('devotee_id', $qifu_setting_differentfamily[0]->devotee_id)
													 ->last()
													 ->receipt_no;

				$qifu_setting_differentfamily[0]->receipt_no = $same_receipt_no;
			}

			else {
				$qifu_setting_differentfamily[0]->receipt_no = "";
			}
		}

    $qifu_different_family = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
											       ->leftjoin('setting_qifu', 'devotee.devotee_id', '=', 'setting_qifu.devotee_id')
											       ->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
											       ->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
											       ->where('devotee.devotee_id', '!=', $devotee[0]->devotee_id)
											       ->where('setting_qifu.address_code', '=', 'different')
											       ->where('setting_qifu.qifu_id', '=', '1')
											       ->where('setting_qifu.focusdevotee_id', '=', $devotee[0]->devotee_id)
											       ->select('devotee.*', 'member.member', 'member.paytill_date', 'specialremarks.devotee_id as specialremarks_devotee_id', 'familycode.familycode')
											       ->GroupBy('devotee.devotee_id')
											       ->get();

		for($i = 0; $i < count($qifu_different_family); $i++)
		{
			$hasreceipt = QifuReceipt::where('devotee_id', $qifu_different_family[$i]->devotee_id)->get();

			if(count($hasreceipt) > 0)
			{
				$same_receipt_no = QifuReceipt::all()
													 ->where('devotee_id', $qifu_different_family[$i]->devotee_id)
													 ->last()
													 ->receipt_no;

				$qifu_different_family[$i]->receipt_no = $same_receipt_no;
			}

			else {
				$qifu_different_family[$i]->receipt_no = "";
			}
		}

    $qifu_receipt_collection = collect();

    $qifu_receipts = QifuGeneraldonation::leftjoin('devotee', 'devotee.devotee_id', '=', 'qifu_generaldonation.focusdevotee_id')
        						 ->leftjoin('qifu_receipt', 'qifu_receipt.generaldonation_id', '=', 'qifu_generaldonation.generaldonation_id')
        						 ->where('qifu_generaldonation.focusdevotee_id', '=', $input['focusdevotee_id'])
        						 ->GroupBy('qifu_generaldonation.generaldonation_id')
        						 ->where('qifu_receipt.glcode_id', 136)
        						 ->select('qifu_generaldonation.*', 'devotee.chinese_name', 'qifu_receipt.cancelled_date')
        						 ->orderBy('qifu_generaldonation.generaldonation_id', 'desc')
        						 ->get();

    $paidby_otherqifu_receipts = QifuGeneraldonation::leftjoin('qifu_receipt', 'qifu_receipt.generaldonation_id', '=', 'qifu_generaldonation.generaldonation_id')
																 ->leftjoin('devotee', 'devotee.devotee_id', '=', 'qifu_generaldonation.focusdevotee_id')
																 ->where('qifu_receipt.devotee_id', $input['focusdevotee_id'])
																 ->where('qifu_receipt.glcode_id', 136)
																 ->where('qifu_generaldonation.focusdevotee_id', '!=', $input['focusdevotee_id'])
																 ->select('qifu_generaldonation.*', 'devotee.chinese_name', 'qifu_receipt.cancelled_date', 'qifu_receipt.receipt_no')
																 ->get();

    if(count($qifu_receipts) > 0)
		{
			for($i = 0; $i < count($qifu_receipts); $i++)
			{
				$data = QifuReceipt::where('generaldonation_id', $qifu_receipts[$i]->generaldonation_id)->pluck('receipt_no');
				$receipt_count = count($data);

				if($receipt_count > 1)
				{
					$qifu_receipts[$i]->receipt_no = $data[0] . ' - ' . $data[$receipt_count - 1];
				}
				else
				{
					$qifu_receipts[$i]->receipt_no = $data[0];
				}
			}
		}

    $qifu_receipt_collection = $qifu_receipt_collection->merge($qifu_receipts);
		$qifu_receipt_collection = $qifu_receipt_collection->merge($paidby_otherqifu_receipts);

    $qifu_receipts = $qifu_receipt_collection->sortByDesc('generaldonation_id');
		$qifu_receipts->values()->all();

    Session::put('qifu_receipts', $qifu_receipts);
    Session::put('qifu_same_focusdevotee', $qifu_same_focusdevotee);
    Session::put('qifu_focusdevotee', $qifu_focusdevotee);
		Session::put('qifu_same_family', $qifu_same_family);
		Session::put('qifu_different_family', $qifu_different_family);
    Session::put('qifu_setting_differentfamily', $qifu_setting_differentfamily);

    $qifu_generaldonation_id = $qifu_generaldonation->generaldonation_id;
		$hjgr = $qifu_generaldonation->hjgr;

    $result = QifuReceipt::leftjoin('qifu_generaldonation', 'qifu_receipt.generaldonation_id', '=', 'qifu_generaldonation.generaldonation_id')
							 ->leftjoin('devotee', 'qifu_receipt.devotee_id', '=', 'devotee.devotee_id')
							 ->leftjoin('user', 'qifu_receipt.staff_id', '=', 'user.id')
							 ->leftjoin('festiveevent', 'qifu_generaldonation.festiveevent_id', '=', 'festiveevent.festiveevent_id')
							 ->where('qifu_generaldonation.generaldonation_id', '=', $qifu_generaldonation_id)
							 ->select('qifu_receipt.*', 'devotee.chinese_name', 'devotee.oversea_addr_in_chinese', 'devotee.address_houseno', 'devotee.address_unit1',
							 	'devotee.address_unit2', 'devotee.address_street', 'devotee.address_postal', 'devotee.familycode_id', 'devotee.deceased_year',
							 	'qifu_generaldonation.focusdevotee_id', 'qifu_generaldonation.trans_no', 'user.first_name', 'user.last_name',
							 	'festiveevent.start_at', 'festiveevent.time', 'festiveevent.event', 'festiveevent.lunar_date', 'qifu_generaldonation.mode_payment')
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

		return view('fahui.qifu_print', [
			'receipts' => $result,
			'print_format' => $hjgr,
			'loop' => $loop,
			'count_familycode' => $count_familycode,
			'samefamily_no' => $samefamily_no,
			'total_amount' => number_format($total_amount, 2),
			'paid_by' => $paid_by
		]);
  }

  public function postQifuSameFamilySetting(Request $request)
  {
    $input = array_except($request->all(), '_token');

    SettingQifu::where('focusdevotee_id', $input['focusdevotee_id'])
								 ->where('address_code', 'same')
                 ->where('year', null)
								 ->delete();

    if(isset($input['focusdevotee_id']))
    {
      for($i = 0; $i < count($input['devotee_id']); $i++)
      {
        $list = [
          "focusdevotee_id" => $input['focusdevotee_id'],
          "qifu_id" => $input['hidden_qifu_id'][$i],
          "devotee_id" => $input['devotee_id'][$i],
          "address_code" => "same"
        ];

        SettingQifu::create($list);
      }
    }

    $devotee = Devotee::find($input['focusdevotee_id']);

    $qifu_same_family = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
											  ->leftjoin('setting_qifu', 'devotee.devotee_id', '=', 'setting_qifu.devotee_id')
												->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
											 	->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
												->where('devotee.familycode_id', $devotee->familycode_id)
												->where('devotee.devotee_id', '!=', $input['focusdevotee_id'])
												->where('setting_qifu.focusdevotee_id', '=', $input['focusdevotee_id'])
												->where('setting_qifu.address_code', '=', 'same')
												->where('setting_qifu.qifu_id', '=', '1')
                        ->where('setting_qifu.year', null)
												->select('devotee.*', 'familycode.familycode', 'member.paytill_date', 'specialremarks.devotee_id as specialremarks_devotee_id')
												->GroupBy('devotee.devotee_id')
												->get();

    $qifu_same_focusdevotee = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
                       				->leftjoin('setting_qifu', 'devotee.devotee_id', '=', 'setting_qifu.devotee_id')
                       				->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
                       				->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
                       				->where('setting_qifu.address_code', '=', 'same')
                       				->where('setting_qifu.qifu_id', '=', '1')
                              ->where('setting_qifu.year', null)
                       				->where('setting_qifu.focusdevotee_id', '=', $input['focusdevotee_id'])
                       				->where('setting_qifu.devotee_id', '=', $input['focusdevotee_id'])
                       				->select('devotee.*', 'familycode.familycode', 'member.paytill_date', 'specialremarks.devotee_id as specialremarks_devotee_id')
                       				->GroupBy('devotee.devotee_id')
                       				->get();

    $qifu_setting_samefamily = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
                                ->leftjoin('setting_qifu', 'setting_qifu.devotee_id', '=', 'devotee.devotee_id')
                                ->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
                                ->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
                                ->where('devotee.devotee_id', '!=', $input['focusdevotee_id'])
                                ->where('devotee.familycode_id', $devotee->familycode_id)
                                ->where('setting_qifu.focusdevotee_id', '=', $input['focusdevotee_id'])
                                ->where('setting_qifu.address_code', '=', 'same')
                                ->select('devotee.*', 'member.paytill_date', 'specialremarks.devotee_id as specialremarks_devotee_id', 'familycode.familycode', 'setting_qifu.qifu_id')
                                ->GroupBy('devotee.devotee_id')
                                ->get();

    $qifu_focusdevotee = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
                         ->leftjoin('setting_qifu', 'devotee.devotee_id', '=', 'setting_qifu.devotee_id')
                         ->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
                         ->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
                         ->where('devotee.familycode_id', $devotee->familycode_id)
                         ->where('devotee.devotee_id', $input['focusdevotee_id'])
                         ->where('setting_qifu.year', null)
                         ->where('setting_qifu.focusdevotee_id', $input['focusdevotee_id'])
                         ->select('devotee.*', 'familycode.familycode', 'member.paytill_date', 'specialremarks.devotee_id as specialremarks_devotee_id', 'setting_qifu.qifu_id')
                         ->get();

    Session::put('qifu_setting_samefamily', $qifu_setting_samefamily);
    Session::put('qifu_focusdevotee', $qifu_focusdevotee);
    Session::put('qifu_same_family', $qifu_same_family);
    Session::put('qifu_same_focusdevotee', $qifu_same_focusdevotee);

    $request->session()->flash('success', 'Setting for same addresses are successfully created.');
		return redirect()->back();
  }

  public function postQifuDifferentFamilySetting(Request $request)
  {
    $input = array_except($request->all(), '_token');

    SettingQifu::where('focusdevotee_id', $input['focusdevotee_id'])
								 ->where('address_code', 'different')
                 ->where('year', null)
								 ->delete();

    if(isset($input['devotee_id']))
		{
			for($i = 0; $i < count($input['devotee_id']); $i++)
			{
				$list = [
					"focusdevotee_id" => $input['focusdevotee_id'],
	        "qifu_id" => $input['hidden_qifu_id'][$i],
					"devotee_id" => $input['devotee_id'][$i],
	        "address_code" => "different",
	        "year" => null
				];

				SettingQifu::create($list);
			}
		}

    $devotee = Devotee::find($input['focusdevotee_id']);

		$qifu_different_family = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
														 ->leftjoin('setting_qifu', 'devotee.devotee_id', '=', 'setting_qifu.devotee_id')
														 ->where('setting_qifu.address_code', '=', 'different')
														 ->where('setting_qifu.qifu_id', '=', '1')
														 ->where('setting_qifu.focusdevotee_id', '=', $input['focusdevotee_id'])
                             ->where('year', null)
														 ->select('devotee.*', 'familycode.familycode')
														 ->get();

    $qifu_setting_differentfamily = Devotee::leftjoin('familycode', 'familycode.familycode_id', '=', 'devotee.familycode_id')
        														->leftjoin('setting_qifu', 'setting_qifu.devotee_id', '=', 'devotee.devotee_id')
        														->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id')
        														->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
        														->where('setting_qifu.focusdevotee_id', '=', $input['focusdevotee_id'])
        														->where('setting_qifu.address_code', '=', 'different')
                                    ->where('year', null)
        														->select('devotee.*', 'member.paytill_date', 'specialremarks.devotee_id as specialremarks_devotee_id', 'familycode.familycode',
        														'setting_qifu.qifu_id')
        														->GroupBy('devotee.devotee_id')
        														->get();

    Session::put('qifu_different_family', $qifu_different_family);
		Session::put('qifu_setting_differentfamily', $qifu_setting_differentfamily);

    $request->session()->flash('success', 'Setting for different addresses are successfully created.');
		return redirect()->back();
  }

  public function getTransactionDetail(Request $request)
  {
    $input = array_except($request->all(), '_token');

    if(isset($input['trans_no']))
		{
			$trans = QifuGeneraldonation::where('trans_no', $input['trans_no'])->first();

			if(count($trans) > 0)
			{
        $qifu_generaldonation = new QifuGeneraldonation;
        $result = $qifu_generaldonation->searchTransaction($input)->get();
			}

			else
			{
				return response()->json(array(
					 'msg' => 'No Result Found'
				));
			}

			$cancellation = QifuReceipt::leftjoin('qifu_generaldonation', 'qifu_receipt.generaldonation_id', '=', 'qifu_generaldonation.generaldonation_id')
											->leftjoin('user', 'qifu_receipt.cancelled_by', '=', 'user.id')
											->where('qifu_generaldonation.trans_no', $input['trans_no'])
											->select('qifu_receipt.cancelled_date', 'user.first_name', 'user.last_name')
											->GroupBy('qifu_generaldonation.generaldonation_id')
											->get();

			if(isset($cancellation[0]->cancelled_date))
			{
				$cancellation[0]->cancelled_date = Carbon::parse($cancellation[0]->cancelled_date)->format("d/m/Y");
			}
		}

    else
		{
			$receipt = QifuReceipt::where('receipt_no', $input['receipt_no'])->first();

			if(count($receipt) > 0)
			{
        $qifu_generaldonation = new QifuGeneraldonation;
        $result = $qifu_generaldonation->searchTransaction($input)->get();
			}

			else
			{
				return response()->json(array(
			     'msg' => 'No Result Found'
			  ));
			}

			$cancellation = QifuReceipt::leftjoin('qifu_generaldonation', 'qifu_receipt.generaldonation_id', '=', 'qifu_generaldonation.generaldonation_id')
											->leftjoin('user', 'qifu_receipt.cancelled_by', '=', 'user.id')
											->where('qifu_receipt.receipt_no', $input['receipt_no'])
											->select('qifu_receipt.cancelled_date', 'user.first_name', 'user.last_name')
											->GroupBy('qifu_generaldonation.generaldonation_id')
											->get();

			if(isset($cancellation[0]->cancelled_date))
			{
				$cancellation[0]->cancelled_date = Carbon::parse($cancellation[0]->cancelled_date)->format("d/m/Y");
			}
		}

    // Check Transaction devotee and focus devotee is the same
		$focusdevotee = Session::get('focus_devotee');

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

    if(isset($input['receipt_no']))
		{
			$receipts = QifuReceipt::leftjoin('qifu_generaldonation', 'qifu_receipt.generaldonation_id', '=', 'qifu_generaldonation.generaldonation_id')
								 ->leftjoin('devotee', 'qifu_receipt.devotee_id', '=', 'devotee.devotee_id')
								 ->leftjoin('user', 'qifu_receipt.staff_id', '=', 'user.id')
								 ->leftjoin('festiveevent', 'qifu_generaldonation.festiveevent_id', '=', 'festiveevent.festiveevent_id')
								 ->where('qifu_receipt.receipt_no', '=', $input['receipt_no'])
								 ->select('qifu_receipt.*', 'devotee.chinese_name', 'devotee.oversea_addr_in_chinese', 'devotee.address_houseno', 'devotee.address_unit1',
								 	'devotee.address_unit2', 'devotee.address_street', 'devotee.address_postal', 'devotee.familycode_id',
								 	'qifu_generaldonation.focusdevotee_id', 'qifu_generaldonation.trans_no', 'user.first_name', 'user.last_name',
								 	'festiveevent.start_at', 'festiveevent.time', 'festiveevent.event', 'festiveevent.lunar_date', 'qifu_generaldonation.mode_payment')
								 ->get();

			$receipts[0]->trans_date = \Carbon\Carbon::parse($receipts[0]->trans_date)->format("d/m/Y");
			$receipts[0]->start_at = \Carbon\Carbon::parse($receipts[0]->start_at)->format("d/m/Y");

			$samefamily_no = 0;
			$print_format = 'hj';

			$paid_by = Devotee::where('devotee.devotee_id', $receipts[0]->focusdevotee_id)
								 ->select('chinese_name', 'devotee_id')
								 ->get();
		}

    if(isset($input['trans_no']))
		{
			$receipts = QifuReceipt::leftjoin('qifu_generaldonation', 'qifu_receipt.generaldonation_id', '=', 'qifu_generaldonation.generaldonation_id')
								  ->leftjoin('devotee', 'qifu_receipt.devotee_id', '=', 'devotee.devotee_id')
								  ->leftjoin('user', 'qifu_receipt.staff_id', '=', 'user.id')
								  ->leftjoin('member', 'devotee.member_id', '=', 'member.member_id')
								  ->leftjoin('festiveevent', 'qifu_generaldonation.festiveevent_id', '=', 'festiveevent.festiveevent_id')
								  ->where('qifu_generaldonation.trans_no', '=', $input['trans_no'])
								  ->select('qifu_receipt.*', 'member.paytill_date', 'member.member_id', 'devotee.chinese_name', 'devotee.oversea_addr_in_chinese', 'devotee.address_houseno', 'devotee.address_unit1',
								 	 'devotee.address_unit2', 'devotee.address_street', 'devotee.address_postal', 'devotee.familycode_id',
								 	 'qifu_generaldonation.focusdevotee_id', 'qifu_generaldonation.trans_no', 'user.first_name', 'user.last_name',
								 	 'festiveevent.start_at', 'festiveevent.time', 'festiveevent.event', 'festiveevent.lunar_date', 'qifu_generaldonation.mode_payment')
								  ->get();

			for($i = 0; $i < count($receipts); $i++)
			{
				$receipts[$i]->trans_date = \Carbon\Carbon::parse($receipts[$i]->trans_date)->format("d/m/Y");
				$receipts[$i]->start_at = \Carbon\Carbon::parse($receipts[$i]->start_at)->format("d/m/Y");
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

    return view('fahui.qifu_print', [
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
				$generaldonation = QifuGeneraldonation::where('trans_no', $input['transaction_no'])->first();

				// Update Cancellation Status
				$qifu_receipts = QifuReceipt::where('generaldonation_id', $generaldonation->generaldonation_id)
        								 ->update([
        							     'cancelled_date' => Carbon::now(),
        							     'status' => "cancelled",
        							     'cancelled_by' => Auth::user()->id
        							   ]);

				$cancellation_receipts = QifuReceipt::leftjoin('user', 'user.id', '=', 'qifu_receipt.cancelled_by')
																 ->where('qifu_receipt.generaldonation_id', '=', $generaldonation->generaldonation_id)
																 ->select('qifu_receipt.cancelled_date', 'user.first_name', 'user.last_name')
																 ->GroupBy('qifu_receipt.generaldonation_id')
																 ->get();

				$cancelled_date = \Carbon\Carbon::parse($cancellation_receipts[0]->cancelled_date)->format("d/m/Y");

				$focus_devotee = Session::get('focus_devotee');

				if(count($focus_devotee) > 0)
        {
          $qifu_receipts = QifuGeneraldonation::leftjoin('devotee', 'devotee.devotee_id', '=', 'qifu_generaldonation.focusdevotee_id')
          										->leftjoin('qifu_receipt', 'qifu_receipt.generaldonation_id', '=', 'qifu_generaldonation.generaldonation_id')
          										->where('qifu_generaldonation.focusdevotee_id', '=', $focus_devotee[0]->devotee_id)
          										->where('qifu_receipt.glcode_id', 136)
          										->GroupBy('qifu_generaldonation.generaldonation_id')
          										->select('qifu_generaldonation.*', 'devotee.chinese_name', 'qifu_receipt.cancelled_date')
          										->orderBy('qifu_generaldonation.generaldonation_id', 'desc')
          										->get();

  				if(count($qifu_receipts) > 0)
  				{
  					for($i = 0; $i < count($qifu_receipts); $i++)
  					{
  						$data = QifuReceipt::where('generaldonation_id', $qifu_receipts[$i]->generaldonation_id)->pluck('receipt_no');
  						$receipt_count = count($data);

              if($receipt_count > 1)
      				{
      					$qifu_receipts[$i]->receipt_no = $data[0] . ' - ' . $data[$receipt_count - 1];
      				}
      				else
      				{
      					$qifu_receipts[$i]->receipt_no = $data[0];
      				}
  					}
  				}

  				Session::put('qifu_receipts', $qifu_receipts);
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
}
