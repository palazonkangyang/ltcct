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
use App\Models\Staff;
use App\Models\FestiveEvent;

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
      ReceiptController::createReceipt($param);



      $transaction = Trn::getTransaction($param['receipt']['trn_id']);
      $receipts = Rct::getReceipts($param['receipt']['trn_id']);
      count($receipts) > 1 ? $receipt_no_combine = $receipts->first()['receipt_no'] . ' - ' . $receipts->last()['receipt_no'] : $receipt_no_combine = $receipt_no_combine = $receipts->first()['receipt_no'];
      $loop = intval(ceil(count($receipts) / 6),0);

      if(Trn::isCombinePrinting($param['receipt']['trn_id'])){
        $receipts_of_family = Rct::paginateCombineReceiptOfFamily($receipts);
        $receipts_of_relative = Rct::paginateSingleReceiptOfRelative($receipts);
      }

      elseif(Trn::isIndividualPrinting($param['receipt']['trn_id'])){
        $paginate_receipts = Rct::paginateSingleReceipt($receipts);
        //$receipts_of_family = Rct::paginateSingleReceiptOfFamily($receipts);
        //$receipts_of_relative = Rct::paginateSingleReceiptOfRelative($receipts);
      }

      //dd($paginate_receipts);

      return view('receipt.receipt_xiaozai', [
        'module' => Module::getModule($request['mod_id']),
  			'transaction' => $transaction,
        'staff' => Staff::getStaff(Auth::user()->id),
        'paginate_receipts' => $paginate_receipts,
        'next_event' => FestiveEvent::getNextEvent(),
  			'loop' => $loop,
        'family_address' => AddressController::getAddressByDevoteeId(session()->get('focus_devotee')[0]['devotee_id']),
  			'receipt_no_combine' => $receipt_no_combine,
        'time_now' => Carbon::now('Singapore'),
  			'paid_by_devotee' => Devotee::getDevotee(session()->get('focus_devotee')[0]['devotee_id'])
  		]);
    }


}
