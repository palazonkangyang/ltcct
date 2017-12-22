<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use Auth;
use Hash;
use Carbon\Carbon;
use App\Models\Trn;
use App\Models\Rct;
use App\Models\Module;
use App\Models\Devotee;
use App\Models\FestiveEvent;
use App\Models\User;
use App\Models\RctXiaoZai;

class TransactionController extends Controller
{
    public function createTransaction(Request $request){
      // dd($request);
      // dd(session()->get('relative_and_friends'));
      $trans_no_to_cancel = $request['trans_no_to_cancel'];

      $param['transaction']['focusdevotee_id'] = session()->get('focus_devotee')[0]['devotee_id'];
      $param['transaction']['festiveevent_id'] = $request['festiveevent_id'];
      $param['transaction']['mod_id'] = $request['mod_id'];
      $param['transaction']['description'] = Module::getDescription($request['mod_id']);
      $param['transaction']['paid_by'] = Devotee::getChineseName(session()->get('focus_devotee')[0]['devotee_id']);
      $param['transaction']['staff_id'] = Auth::user()->id;
      $param['transaction']['attended_by'] = User::getUserName(Auth::user()->id);
      $param['transaction']['receipt'] = NULL;
      $param['transaction']['mode_payment'] = $request['mode_payment'];
      $param['transaction']['trans_no'] = Trn::generateTransactionNo();
      $param['transaction']['nets_no'] = $request['nets_no'];
      $param['transaction']['cheque_no'] = $request['cheque_no'];
      $param['transaction']['manualreceipt'] = $request['manualreceipt'];
      $param['transaction']['total_amount'] = $request['total_amount'];
      $param['transaction']['receipt_printing_type'] = $request['receipt_printing_type'];
      $param['transaction']['receipt_at'] = $request['receipt_at'];
      $param['transaction']['status'] = NULL;
      $param['transaction']['cancelled_by'] = NULL;
      $param['transaction']['cancelled_date'] = NULL;
      $param['transaction']['trans_at'] = Carbon::now();

      $param['receipt']['trn_id'] = Trn::create($param['transaction'])->trn_id;
      $param['var']['devotee_id_list'] = $request['devotee_id'];
      $param['receipt']['mod_id'] = $request['mod_id'];

      $param['var']['is_checked_list'] = $request['is_checked_list'];
      $param['var']['amount_list'] = $request['amount'];
      $param['receipt']['status'] = null;
      $param['var']['item_description_list'] = $request['item_description_list'];
      $param['receipt']['cancelled_by'] = null;
      $param['receipt']['cancelled_date'] = null;
      $param['receipt']['trans_date'] = Carbon::now();

      $trans_no_to_cancel != "" ? TransactionController::cancelTransactionByTransNo($trans_no_to_cancel,$request['mod_id']) : false;

      Module::isXiangYou($request['mod_id']) ? $param['receipt']['hjgr'] = $request['hjgr'] : false ;
      Module::isCiJi($request['mod_id']) ? $param['receipt']['hjgr'] = $request['hjgr'] : false ;
      Module::isXiaoZai($request['mod_id']) ? $param['var']['type_list'] = $request['type'] : false ;
      Module::isXiaoZai($request['mod_id']) ? $param['var']['type_chinese_name_list'] = $request['type_chinese_name_list'] : false ;
      ReceiptController::createReceipt($param);

      Trn::updateReceiptNoOfTransaction($param['receipt']['trn_id']);

      $view = Trn::printReceipt($param['receipt']['trn_id'],$param['transaction']['receipt_printing_type']);
      return $view;
    }

    public function getTransactionDetail(Request $request)
    {
      $receipt_no = $request['receipt_no'];
      $trans_no = $request['trans_no'];

      if($trans_no != null){
        $transaction = Trn::where('trans_no',$trans_no)
                          ->first();
      }

      elseif($receipt_no != null){
        $trn_id = Rct::where('receipt_no',$receipt_no)
                     ->pluck('trn_id')
                     ->first();
        $transaction = Trn::where('trn_id',$trn_id)
                           ->first();
      }

      $transaction['full_name'] = User::getUserName($transaction['cancelled_by']);

      $next_event = FestiveEvent::getNextEvent();

      $receipts = Rct::getReceipts($transaction['trn_id']);

      return response()->json(array(
        'transaction' => $transaction,
        'next_event' => $next_event,
        'receipts' => $receipts,
      ));
    }

    public static function getTrnForAllModule(){
      Session::has('transaction') ? Session::forget('transaction') : false;
      $focusdevotee_id = session()->get('focus_devotee')[0]['devotee_id'];
      $mod_list = Module::getReleasedModuleList();
      foreach($mod_list as $index=> $mod){
        Trn::getTrn($focusdevotee_id,$mod->mod_id);
      }
    }

    public static function reprintReceipt(Request $request){
      $trans_no = $request['trans_no'];
      $receipt_no = $request['receipt_no'];
      $receipt_printing_type = $request['receipt_printing_type'];

      if($trans_no != null){
        $trn_id= Trn::getTrnIdByTransNo($trans_no);
      }

      elseif($receipt_no != null){
        $trn_id = Rct::getTrnIdByReceiptNo($receipt_no);
      }

      $view = Trn::printReceipt($trn_id,$receipt_printing_type);
      return $view;
    }

    public static function cancelTransactionByTransNo($trans_no,$mod_id){
      $devotee_id = session()->get('focus_devotee')[0]['devotee_id'];

      Trn::where('trans_no',$trans_no)
         ->update([
         'status' => 'cancelled' ,
         'cancelled_date' => Carbon::now() ,
         'cancelled_by' =>  Auth::user()->id
         ]);
      Trn::getTrn($devotee_id,$mod_id);
    }

    public static function cancelTransaction(Request $request){
      $authorized_password = $request['authorized_password'];
      $transaction_no = $request['transaction_no'];
      $devotee_id = session()->get('focus_devotee')[0]['devotee_id'];
      $mod_id = $request['mod_id'] ;
      $user = User::find(Auth::user()->id);
      $hashedPassword = $user->password;

      if(Hash::check($authorized_password,$hashedPassword))
      {
        Trn::where('trans_no',$transaction_no)
           ->update([
           'status' => 'cancelled' ,
           'cancelled_date' => Carbon::now() ,
           'cancelled_by' =>  Auth::user()->id
           ]);

        Trn::getTrn($devotee_id,$mod_id);

        $request->session()->flash('success', 'The transaction is successfully cancelled.');

        return redirect()->back()->with([

        ]);
      }

      else{
        $request->session()->flash("error", "Password don't match. Please Try Again");

        return redirect()->back()->with([

        ]);
      }
    }

    public function cancelAndReplaceTransaction(Request $request)
    {
      $authorized_password = $request['authorized_password'];
      $mod_id = $request['mod_id'];
      $trans_no = $request['trans_no'];
      $user = User::find(Auth::user()->id);
      $hashedPassword = $user->password;

      if (Hash::check($authorized_password, $hashedPassword)){
        $transaction = Trn::where('trans_no',$trans_no)->first();
        $receipt = Rct::where('trn_id',$transaction['trn_id'])->get();
        if(Module::isXiaoZai($mod_id)){
          foreach($receipt as $rct){
            $rct['type'] = RctXiaoZai::getType($rct['rct_id']);
            $rct['type_chinese_name'] = RctXiaoZai::getTypeChineseName($rct['rct_id']);
          }
        }

        return response()->json(array(
          "transaction" => $transaction,
          "receipt" => $receipt,
          "error" => ""
        ));

      }

      else{
        return response()->json(array(
          "error" => "Password don't match. Please Try Again"
        ));
      }




      // $focus_devotee_id = session()->get('focus_devotee')[0]['devotee_id'];
      // $input = array_except($request->all(), '_token');
  		// $focusdevotee_id = "";
      //
      // if(isset($input['authorized_password']))
  		// {
  		// 	$user = User::find(Auth::user()->id);
      //   $hashedPassword = $user->password;
      //
  		// 	if (Hash::check($input['authorized_password'], $hashedPassword))
  		// 	{
  		// 		if(!empty($input['receipt_no']))
  		// 		{
  		// 			$receipt = XiaozaiReceipt::where('receipt_no', $input['receipt_no'])->get();
  		// 			$result = XiaozaiReceipt::find($receipt[0]['receipt_id']);
      //
  		// 			$generaldonation = XiaozaiGeneraldonation::where('generaldonation_id', $receipt[0]['generaldonation_id'])->get();
      //
  		// 			$focusdevotee_id = $generaldonation[0]->focusdevotee_id;
      //
  		// 			$result->cancelled_date = Carbon::now();
  		// 			$result->status = "cancelled";
  		// 			$result->cancelled_by = Auth::user()->id;
      //
  		// 			$cancellation = $result->save();
  		// 		}
      //
  		// 		if(!empty($input['trans_no']))
  		// 		{
  		// 			$generaldonation = XiaozaiGeneraldonation::where('trans_no', $input['trans_no'])->get();
      //
  		// 			$focusdevotee_id = $generaldonation[0]->focusdevotee_id;
      //
  		// 			$receipt = XiaozaiReceipt::where('generaldonation_id', $generaldonation[0]->generaldonation_id)->get();
      //       $total_devotee = count($receipt);
      //
  		// 			for($i = 0; $i < count($receipt); $i++)
  		// 			{
  		// 				$result = XiaozaiReceipt::find($receipt[$i]['receipt_id']);
      //
  		// 				$result->cancelled_date = Carbon::now();
  		// 				$result->status = "cancelled";
  		// 				$result->cancelled_by = Auth::user()->id;
      //
  		// 				$cancellation = $result->save();
  		// 			}
  		// 		}
      //
  		// 		$focus_devotee = Session::get('focus_devotee');
      //
  		// 		$xiaozai_receipts = XiaozaiGeneraldonation::leftjoin('devotee', 'devotee.devotee_id', '=', 'xiaozai_generaldonation.focusdevotee_id')
      //     				            ->leftjoin('xiaozai_receipt', 'xiaozai_receipt.generaldonation_id', '=', 'xiaozai_generaldonation.generaldonation_id')
      //     				            ->where('xiaozai_generaldonation.focusdevotee_id', '=', $focus_devotee[0]->devotee_id)
      //     				            ->where('xiaozai_receipt.glcode_id', 117)
      //     				            ->GroupBy('xiaozai_generaldonation.generaldonation_id')
      //     				            ->select('xiaozai_generaldonation.*', 'devotee.chinese_name', 'xiaozai_receipt.cancelled_date')
      //     				            ->orderBy('xiaozai_generaldonation.generaldonation_id', 'desc')
      //     				            ->get();
      //
  		// 		if(count($xiaozai_receipts) > 0)
  		// 		{
  		// 		  for($i = 0; $i < count($xiaozai_receipts); $i++)
  		// 		  {
  		// 		    $data = XiaozaiReceipt::where('generaldonation_id', $xiaozai_receipts[$i]->generaldonation_id)->pluck('receipt_no');
  		// 		    $receipt_count = count($data);
  		// 		    $xiaozai_receipts[$i]->receipt_no = $data[0] . ' - ' . $data[$receipt_count - 1];
  		// 		  }
  		// 		}
      //
  		// 		Session::put('xiaozai_receipts', $xiaozai_receipts);
      //
  		// 		return response()->json(array(
  		// 		  'receipt' => $receipt,
      //       'total_devotee' => $total_devotee
  		// 		));
  		// 	}
      //
  		// 	else
  		// 	{
  		// 		return response()->json(array(
  		// 			'error' => 'not match'
  		// 		));
  		// 	}
  		// }
    }

}
