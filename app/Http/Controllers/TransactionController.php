<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use Auth;
use Carbon\Carbon;
use App\Models\Trn;
use App\Models\Rct;
use App\Models\Module;

class TransactionController extends Controller
{
    public function createTransaction(Request $request){
      $param['transaction']['focusdevotee_id'] = session()->get('focus_devotee')[0]['devotee_id'];
      $param['transaction']['festiveevent_id'] = $request['festiveevent_id'];
      $param['transaction']['mod_id'] = $request['mod_id'];
      $param['transaction']['description'] = Module::getDescription($request['mod_id']);
      $param['transaction']['staff_id'] = Auth::user()->id;
      $param['transaction']['mode_payment'] = $request['mode_payment'];
      $param['transaction']['trans_no'] = Trn::generateTransactionNo();
      $param['transaction']['nets_no'] = $request['nets_no'];
      $param['transaction']['cheque_no'] = $request['cheque_no'];
      $param['transaction']['manualreceipt'] = $request['manualreceipt'];
      $param['transaction']['total_amount'] = $request['total_amount'];
      $param['transaction']['receipt_printing_type'] = $request['receipt_printing_type'];
      $param['transaction']['receipt_at'] = $request['receipt_at'];
      $param['transaction']['trans_at'] = Carbon::now();

      $param['receipt']['trn_id'] = Trn::create($param['transaction'])->trn_id;
      $param['var']['devotee_id_list'] = $request['devotee_id'];
      $param['receipt']['mod_id'] = $request['mod_id'];

      $param['var']['is_checked_list'] = $request['hidden_xiaozai_amount'];
      $param['var']['amount_list'] = $request['amount'];
      $param['receipt']['status'] = null;
      $param['receipt']['cancelled_by'] = null;
      $param['receipt']['cancelled_date'] = null;
      $param['receipt']['trans_date'] = Carbon::now();

      Module::isXiangYou($request['mod_id']) ? $param['receipt']['hjgr'] = $request['hjgr'] : false ;
      Module::isCiJi($request['mod_id']) ? $param['receipt']['hjgr'] = $request['hjgr'] : false ;
      Module::isXiaoZai($request['mod_id']) ? $param['var']['type_list'] = $request['type'] : false ;

      ReceiptController::createReceipt($param);

      $transaction = Trn::getTransaction($param['receipt']['trn_id']);
      $receipt_list = Rct::getReceiptList($param['receipt']['trn_id']);

      count($receipt_list) > 1 ? $receipt_no_combine = $receipt_list->first()['receipt_no'] . ' - ' . $receipt_list->last()['receipt_no'] : $receipt_no_combine;
      $loop = intval(ceil(count($receipt_list) / 6),0);
      dd($receipt_no_combine);

      return view('fahui.xiaozai_print', [
  			'transaction' => $transaction,
        'receipt_list' => $receipt_list,
  			'loop' => $loop,
  			'count_familycode' => $count_familycode, //XY170359 - XY170360
  			'samefamily_no' => $samefamily_no,       //last index of family member
  			'total_amount' => number_format($total_amount, 2),
  			'paid_by' => $paid_by
  		]);
    }


}
