<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use Auth;
use Carbon\Carbon;
use App\Models\Trn;
use App\Models\Rct;
use App\Models\Module;
use App\Models\Devotee;
use App\Models\FestiveEvent;
use App\Models\User;

class TransactionController extends Controller
{
    public function createTransaction(Request $request){

      $param['transaction']['focusdevotee_id'] = session()->get('focus_devotee')[0]['devotee_id'];
      $param['transaction']['festiveevent_id'] = $request['festiveevent_id'];
      $param['transaction']['mod_id'] = $request['mod_id'];
      $param['transaction']['description'] = Module::getDescription($request['mod_id']);
      $param['transaction']['paid_by'] = Devotee::getChineseName(session()->get('focus_devotee')[0]['devotee_id']);
      $param['transaction']['staff_id'] = Auth::user()->id;
      $param['transaction']['attended_by'] = User::getUserName(Auth::user()->id);
      $param['transaction']['receipt'] = null;
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

      $param['var']['is_checked_list'] = $request['is_checked_list'];
      $param['var']['amount_list'] = $request['amount'];
      $param['receipt']['status'] = null;
      $param['var']['item_description_list'] = $request['item_description_list'];
      $param['receipt']['cancelled_by'] = null;
      $param['receipt']['cancelled_date'] = null;
      $param['receipt']['trans_date'] = Carbon::now();

      Module::isXiangYou($request['mod_id']) ? $param['receipt']['hjgr'] = $request['hjgr'] : false ;
      Module::isCiJi($request['mod_id']) ? $param['receipt']['hjgr'] = $request['hjgr'] : false ;
      Module::isXiaoZai($request['mod_id']) ? $param['var']['type_list'] = $request['type'] : false ;
      Module::isXiaoZai($request['mod_id']) ? $param['var']['type_chinese_name_list'] = $request['type_chinese_name_list'] : false ;
      ReceiptController::createReceipt($param);

      Trn::updateReceiptNoOfTransaction($param['receipt']['trn_id']);

      // Trn::printReceipt(,);
      // public static function printReceipt($trn_id){
      //
      // }
      $transaction = Trn::getTransaction($param['receipt']['trn_id']);
      $receipts = Rct::getReceipts($param['receipt']['trn_id']);

      if(Trn::isCombinePrinting($param['receipt']['trn_id'])){
        $receipts_of_family = Rct::getReceiptOfFamily($receipts);
        $paginate_receipts_of_family = Rct::paginateCombineReceipt($receipts_of_family);

        $receipts_of_relative = Rct::getReceiptOfRelative($receipts);
        $paginate_receipts_of_relative = Rct::paginateSingleReceipt($receipts_of_relative);
        $paginate_receipts = array_merge($paginate_receipts_of_family,$paginate_receipts_of_relative);
      }

      elseif(Trn::isIndividualPrinting($param['receipt']['trn_id'])){
        $paginate_receipts = Rct::paginateSingleReceipt($receipts);
      }

      Trn::getTrn(session()->get('focus_devotee')[0]['devotee_id'],$request['mod_id']);
      return view('receipt.receipt_xiaozai', [
        'module' => Module::getModule($request['mod_id']),
  			'transaction' => $transaction,
        'paginate_receipts' => $paginate_receipts,
        'next_event' => FestiveEvent::getNextEvent(),
        'family_address' => AddressController::getAddressByDevoteeId(session()->get('focus_devotee')[0]['devotee_id']),
        'time_now' => Carbon::now('Singapore')
  		]);
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

    public static function ReprintReceipt(Request $request){
      dd($request);
    }



}
