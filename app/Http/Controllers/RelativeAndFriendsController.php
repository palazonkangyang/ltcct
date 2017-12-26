<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use App\Models\Raf;
use App\Models\RafXiaoZai;
use App\Models\RafQiFu;
use App\Models\RafKongDan;
use App\Models\Trn;
use App\Models\Rct;
use App\Models\Module;
use App\Models\OptionalAddress;
use App\Models\OptionalVehicle;
use App\Models\Devotee;
use Session;

class RelativeAndFriendsController extends Controller
{
  // public static function insertRelativeAndFriends(Request $request){
  //   $devotee_id = $request['devotee_id'];
  //   $mod_id = $request['mod_id'];
  //
  //   return RelativeAndFriendsController::generateRaf($devotee_id,$mod_id);
  // }

  // public static function generateRaf($devotee_id,$mod_id){
  //   $focusdevotee_id = session()->get('focus_devotee')[0]['devotee_id'];
  //   $is_checked = false;
  //   $year = DateController::getCurrentYearFormatYYYY();
  //
  //   $is_checked_list = [];
  //   $chinese_name_list = [];
  //   $devotee_id_list = [];
  //   $register_by_list = [];
  //   $guiyi_id_list = [];
  //   $gy_list = [];
  //   $ops_list = [];
  //   $type_list = [];
  //   $item_description_list = [];
  //   $paid_by_list = [];
  //   $trans_date_list = [];
  //
  //   $is_checked = 0;
  //   $chinese_name = Devotee::getChineseName($devotee_id);
  //   $register_by = '';
  //   $guiyi_id = '';
  //   Devotee::getGuiyiName($devotee_id) != null ? $gy = Devotee::getGuiyiName($devotee_id) : $gy ='' ;
  //   $ops = '';
  //   $type = 'base_home';
  //   $item_description = AddressController::getAddressByDevoteeId($devotee_id);
  //   $paid_by = '';
  //   $trans_date = '';
  //
  //   /* add own address*/
  //   array_push($is_checked_list,$is_checked);
  //   array_push($chinese_name_list,$chinese_name);
  //   array_push($devotee_id_list,$devotee_id);
  //   array_push($register_by_list,$register_by);
  //   array_push($guiyi_id_list,$guiyi_id);
  //   array_push($gy_list,$gy);
  //   array_push($ops_list,$ops);
  //   array_push($type_list,$type);
  //   array_push($item_description_list,$item_description);
  //   array_push($paid_by_list,$paid_by);
  //   array_push($trans_date_list,$trans_date);
  //
  //   return response()->json([
  //     'is_checked_list' => $is_checked_list,
  //     'chinese_name_list' => $chinese_name_list ,
  //     'devotee_id_list' => $devotee_id_list ,
  //     'register_by_list' => $register_by_list ,
  //     'guiyi_id_list' => $guiyi_id_list ,
  //     'gy_list' => $gy_list ,
  //     'ops_list' => $ops_list ,
  //     'type_list' => $type_list ,
  //     'item_description_list' => $item_description_list ,
  //     'paid_by_list' => $paid_by_list ,
  //     'trans_date_list' => $trans_date_list
  //   ]);
  //
  // }

  public static function insertRelativeAndFriends(Request $request){
    $focusdevotee_id = session()->get('focus_devotee')[0]['devotee_id'];
    $devotee_id = $request['devotee_id'];
    if(Devotee::isNotSameFamily($focusdevotee_id,$devotee_id) && Raf::isNotExists($focusdevotee_id,$devotee_id)){
      $param['raf_list'] = collect(new Raf);
      $param['raf_xiaozai_list'] = collect(new RafXiaoZai);
      $param['raf_qifu_list'] = collect(new RafQiFu);
      $param['raf_kongdan_list'] = collect(new RafKongDan);
      $param['var']['mod_id'] = $request['mod_id'];
      $param['var']['devotee_id'] = $request['devotee_id'];
      $param['var']['focusdevotee_id'] = session()->get('focus_devotee')[0]['devotee_id'];
      $param['var']['is_checked'] = false;
      $param['var']['year'] = DateController::getCurrentYearFormatYYYY();

      RelativeAndFriendsController::createRafForAllModule($param);
      RelativeAndFriendsController::getRafForAllModule();

      return response()->json([
        'error' => ''
      ]);
    }

    elseif(Devotee::isSameFamily($focusdevotee_id,$devotee_id)){
      return response()->json([
        'error' => 'The devotee is the same family member'
      ]);
    }

    elseif(Raf::isExists($focusdevotee_id,$devotee_id)){
      return response()->json([
        'error' => 'The devotee is already in Relative and Friends List'
      ]);
    }

      // switch ($param['var']['mod_id']) {
      // // Xiang You
      // case 1:
      //
      //   break;
      //
      // // Ci Ji
      // case 2:
      //
      //   break;
      //
      // // Yue Juan
      // case 3:
      //
      //   break;
      //
      // // Zhu Xue Jin
      // case 4:
      //
      //   break;
      //
      // // Xiao Zai Da Fa Hui
      // case 5:
      //   return response()->json([
      //     'devotee' => Session()->get('relative_and_friends.xiaozai')
      //   ]);
      //
      //   break;
      //
      // // Qian Fo Fa Hui
      // case 6:
      //
      //   break;
      //
      // // Da Bei Fa Hui
      // case 7:
      //
      //   break;
      //
      // // Yao Shi Fa Hui
      // case 8:
      //
      //   break;
      //
      // // Qi Fu Fa Hui
      // case 9:
      //   return response()->json([
      //     'devotee' => Session()->get('relative_and_friends.fahui')
      //   ]);
      //   break;
      //
      // // Kong Dan
      // case 10:
      //   return response()->json([
      //     'devotee' => Session()->get('relative_and_friends.kongdan')
      //   ]);
      //   break;
      //
      // // Pu Du
      // case 11:
      //
      //   break;
      //
      // // Chao Du
      // case 12:
      //
      //   break;
      //
      // // Shou Sheng Ku Qian
      // case 13:
      //
      //   break;
      //
      // default:
      // }

    //$request->session()->flash('success', 'Setting for different addresses are successfully created.');
    // return redirect()->back();
  }

  public static function insertRelativeAndFriendsFromHistory(Request $request){

    $devotee_id_list = $request['devotee_id_list'];

    foreach($devotee_id_list as $devotee_id){
      $focusdevotee_id = session()->get('focus_devotee')[0]['devotee_id'];
      if(Raf::isExists($focusdevotee_id,$devotee_id)){
        return response()->json([
          'error' => 'Some of the devotee are already exist in Relative and Friends List'
        ]);
      }
    }

    foreach($devotee_id_list as $devotee_id){
      $focusdevotee_id = session()->get('focus_devotee')[0]['devotee_id'];
      $param['raf_list'] = collect(new Raf);
      $param['raf_xiaozai_list'] = collect(new RafXiaoZai);
      $param['raf_qifu_list'] = collect(new RafQiFu);
      $param['raf_kongdan_list'] = collect(new RafKongDan);
      $param['var']['mod_id'] = $request['mod_id'];
      $param['var']['devotee_id'] = $devotee_id;
      $param['var']['focusdevotee_id'] = session()->get('focus_devotee')[0]['devotee_id'];
      $param['var']['is_checked'] = false;
      //$param['var']['year'] = DateController::getLastYearFormatYYYY();

      RelativeAndFriendsController::createRafForAllModule($param);
      RelativeAndFriendsController::getRafForAllModule();
    }

    return response()->json([
      'error' => ''
    ]);
  }

  public static function createRafForAllModule($param){
    $param['mod_list'] = Module::getReleasedFaHuiModuleList();
    foreach($param['mod_list'] as $index=> $mod){
      $param['var']['mod_id'] = $mod['mod_id'];
      $param = RelativeAndFriendsController::createRaf($param);
      $param = RelativeAndFriendsController::createRafChildren($param);
    }
    return $param;
  }

  public static function createRaf($param){
    $list['devotee_id'] = $param['var']['devotee_id'];
    $list['focusdevotee_id'] = $param['var']['focusdevotee_id'];
    $list['mod_id'] = $param['var']['mod_id'];
    $list['is_checked'] = $param['var']['is_checked'];
    $list['year'] = DateController::getCurrentYearFormatYYYY();
    $list['raf_id'] = Raf::create($list)->raf_id;
    $param['var']['raf_id'] = $list['raf_id'];
    $param['raf_list']->push($list);
    return $param;
  }

  // public static function deleteExistingAndCreateRaf($param){
  //   $list['devotee_id'] = $param['var']['devotee_id'];
  //   $list['focusdevotee_id'] = $param['var']['focusdevotee_id'];
  //   $list['mod_id'] = $param['var']['mod_id'];
  //   $list['is_checked'] = $param['var']['is_checked'];
  //   $list['year'] = $param['var']['year'];
  //   $exist_raf_list = Raf::where('devotee_id','=',$list['devotee_id'])
  //                    ->where('focusdevotee_id','=',$list['focusdevotee_id'])
  //                    ->where('mod_id','=',$list['mod_id'])
  //                    ->where('mod_id','=',$list['year'])
  //                    ->get();
  //
  //   // delete existing records in children table
  //   foreach($exist_raf_list as $index=>$exist_raf){
  //     switch ($param['var']['mod_id']) {
  //     // Xiang You
  //     case 1:
  //
  //       break;
  //
  //     // Ci Ji
  //     case 2:
  //
  //       break;
  //
  //     // Yue Juan
  //     case 3:
  //
  //       break;
  //
  //     // Zhu Xue Jin
  //     case 4:
  //
  //       break;
  //
  //     // Xiao Zai Da Fa Hui
  //     case 5:
  //       RafXiaoZai::where('raf_id','=',$exist_raf['raf_id'])
  //              ->delete();
  //       break;
  //
  //     // Qian Fo Fa Hui
  //     case 6:
  //
  //       break;
  //
  //     // Da Bei Fa Hui
  //     case 7:
  //
  //       break;
  //
  //     // Yao Shi Fa Hui
  //     case 8:
  //
  //       break;
  //
  //     // Qi Fu Fa Hui
  //     case 9:
  //       RafQiFu::where('raf_id','=',$exist_raf['raf_id'])
  //              ->delete();
  //       break;
  //
  //     // Kong Dan
  //     case 10:
  //       RafKongDan::where('raf_id','=',$exist_raf['raf_id'])
  //                 ->delete();
  //       break;
  //
  //     // Pu Du
  //     case 11:
  //
  //       break;
  //
  //     // Chao Du
  //     case 12:
  //
  //       break;
  //
  //     // Shou Sheng Ku Qian
  //     case 13:
  //
  //       break;
  //
  //     default:
  //
  //     }
  //
  //     // delete record in Raf
  //     Raf::where('raf_id','=',$exist_raf['raf_id'])
  //        ->delete();
  //   }
  //
  //   $list['raf_id'] = Raf::create($list)->raf_id;
  //   $param['var']['raf_id'] = $list['raf_id'];
  //   $param['raf_list']->push($list);
  //
  //   return $param;
  // }

  public static function createRafChildren($param){
    $list['raf_id'] = $param['var']['raf_id'];

    switch ($param['var']['mod_id']) {
    // Xiang You
    case 1:

      break;

    // Ci Ji
    case 2:

      break;

    // Yue Juan
    case 3:

      break;

    // Zhu Xue Jin
    case 4:

      break;

    // Xiao Zai Da Fa Hui
    case 5:
      $param = XiaoZaiController::createRafXiaoZaiFromBaseHome($param);
      $param = XiaoZaiController::createRafXiaoZaiFromOptionalAddress($param);
      $param = XiaoZaiController::createRafXiaoZaiFromOptionalVehicle($param);
      break;

    // Qian Fo Fa Hui
    case 6:

      break;

    // Da Bei Fa Hui
    case 7:

      break;

    // Yao Shi Fa Hui
    case 8:

      break;

    // Qi Fu Fa Hui
    case 9:
      $param['raf_qifu_list']->push(RafQiFu::create($list));
      break;

    // Kong Dan
    case 10:
      $param['raf_kongdan_list']->push(RafKongDan::create($list));
      break;

    // Pu Du
    case 11:

      break;

    // Chao Du
    case 12:

      break;

    // Shou Sheng Ku Qian
    case 13:

      break;

    default:

    }
    return $param;
  }

  public static function getRafForAllModule(){
    if(Session::has('relative_and_friends')) { Session::forget('relative_and_friends'); }
    $param['var']['focusdevotee_id'] = session()->get('focus_devotee')[0]['devotee_id'];
    $param['mod_list'] = Module::getReleasedFaHuiModuleList();
    $param['var']['year'] = DateController::getCurrentYearFormatYYYY();
    foreach($param['mod_list'] as $index=> $mod){
      $param['var']['mod_id'] = $mod['mod_id'];
      RelativeAndFriendsController::getRaf($param);
    }
  }

  public static function getRaf($param){
    $raf_list = Raf::leftjoin('devotee','devotee.devotee_id','=','raf.devotee_id')
                   ->leftjoin('familycode','familycode.familycode_id','=','devotee.familycode_id')
                   ->leftjoin('member','member.member_id','=','devotee.member_id')
                   ->where('raf.focusdevotee_id',$param['var']['focusdevotee_id'])
                   ->where('raf.mod_id',$param['var']['mod_id'])
                   ->get();

    switch ($param['var']['mod_id']) {
      // Xiang You
      case 1:

        break;

      // Ci Ji
      case 2:

        break;

      // Yue Juan
      case 3:

        break;

      // Zhu Xue Jin
      case 4:

        break;

      // Xiao Zai Da Fa Hui
      case 5:
        $param['raf_list'] = $raf_list;
        $raf_list = RelativeAndFriendsController::getRafXiaoZai($param);
        $raf_focus_devotee= $raf_list->filter(function ($value, $key) use($param) {
            if($value['devotee_id'] == $param['var']['focusdevotee_id'] ){
            return $value;
          }
        });

        $raf_family= $raf_list->filter(function ($value, $key) use($param) {
            if($value['devotee_id'] != $param['var']['focusdevotee_id'] ){
            return $value;
          }
        });

        $raf_list = $raf_focus_devotee->merge($raf_family);

        Session::put('relative_and_friends.xiaozai',$raf_list);
        break;

      // Qian Fo Fa Hui
      case 6:

        break;

      // Da Bei Fa Hui
      case 7:

        break;

      // Yao Shi Fa Hui
      case 8:

        break;

      // Qi Fu Fa Hui
      case 9:
        Session::put('relative_and_friends.qifu',$raf_list);
        break;

      // Kong Dan
      case 10:
        Session::put('relative_and_friends.kongdan',$raf_list);
        break;

      // Pu Du
      case 11:

        break;

      // Chao Du
      case 12:

        break;

      // Shou Sheng Ku Qian
      case 13:

        break;

      default:
    }
  }

  public static function getRafXiaoZai($param){
    foreach($param['raf_list'] as $index=>$raf){
      $raf_xiaozai = RafXiaoZai::where('raf_id','=',$raf['raf_id'])
                               ->first();
      $raf['type'] = $raf_xiaozai['type'];
      $raf['hjgr'] = $raf_xiaozai['hjgr'];

      switch ($raf['type']) {
        case 'base_home':
          $raf['optionaladdress'] = null;
          $raf['optionalvehicle'] = null;
          $raf['item_description'] = AddressController::getAddressByDevoteeId($raf['devotee_id']);
          break;
        case 'home':
          $optionaladdress_id = RafXiaoZai::getOptionalAddressIdByRafXiaoZaiId($raf_xiaozai['raf_xiaozai_id']);
          $raf['optionaladdress'] = OptionalAddress::getOptionalAddressByOptionalAddressId($optionaladdress_id);
          $raf['optionalvehicle'] = null;
          $raf['optionaladdress']['address'] != NULL ? $raf['item_description'] = $raf['optionaladdress']['address'] : $raf['item_description'] = $raf['optionaladdress']['oversea_address'];
          break;
        case 'company':
          $optionaladdress_id = RafXiaoZai::getOptionalAddressIdByRafXiaoZaiId($raf_xiaozai['raf_xiaozai_id']);
          $raf['optionaladdress'] = OptionalAddress::getOptionalAddressByOptionalAddressId($optionaladdress_id);
          $raf['optionalvehicle'] = null;
          $raf['optionaladdress']['address'] != NULL ? $raf['item_description'] = $raf['optionaladdress']['data'] .' @ '. $raf['optionaladdress']['address'] : $raf['item_description'] = $raf['optionaladdress']['data'] .' @ '. $raf['optionaladdress']['oversea_address'];
          break;
        case 'stall':
          $optionaladdress_id = RafXiaoZai::getOptionalAddressIdByRafXiaoZaiId($raf_xiaozai['raf_xiaozai_id']);
          $raf['optionaladdress'] = OptionalAddress::getOptionalAddressByOptionalAddressId($optionaladdress_id);
          $raf['optionalvehicle'] = null;
          $raf['optionaladdress']['address'] != NULL ? $raf['item_description'] = $raf['optionaladdress']['data'] .' @ '. $raf['optionaladdress']['address'] : $raf['item_description'] = $raf['optionaladdress']['data'] .' @ '. $raf['optionaladdress']['oversea_address'];
          break;
        case 'office':
          $optionaladdress_id = RafXiaoZai::getOptionalAddressIdByRafXiaoZaiId($raf_xiaozai['raf_xiaozai_id']);
          $raf['optionaladdress'] = OptionalAddress::getOptionalAddressByOptionalAddressId($optionaladdress_id);
          $raf['optionalvehicle'] = null;
          $raf['optionaladdress']['address'] != NULL ? $raf['item_description'] = $raf['optionaladdress']['address'] : $raf['item_description'] = $raf['optionaladdress']['oversea_address'];
          break;
        case 'car':
          $optionalvehicle_id = RafXiaoZai::getOptionalVehicleIdByRafXiaoZaiId($raf_xiaozai['raf_xiaozai_id']);
          $raf['optionaladdress'] = null;
          $raf['optionalvehicle'] = OptionalVehicle::getOptionalVehicleByOptionalAddressId($optionalvehicle_id);
          $raf['item_description'] = $raf['optionalvehicle']['data'];
          break;
        case 'ship':
          $optionalvehicle_id = RafXiaoZai::getOptionalVehicleIdByRafXiaoZaiId($raf_xiaozai['raf_xiaozai_id']);
          $raf['optionaladdress'] = null;
          $raf['optionalvehicle'] = OptionalVehicle::getOptionalVehicleByOptionalAddressId($optionalvehicle_id);
          $raf['item_description'] = $raf['optionalvehicle']['data'];
          break;
        default:
          $raf['optionaladdress'] = null;
          $raf['optionalvehicle'] = null;
          $raf['item_description'] = null;
      }
    }
    return $param['raf_list'];
  }

  public static function getRafHistoryForAllModule(){
    if(Session::has('relative_and_friends_history')) { Session::forget('relative_and_friends_history'); }
    $focusdevotee_id = session()->get('focus_devotee')[0]['devotee_id'];
    $mod_list = Module::getReleasedFaHuiModuleList();
    foreach($mod_list as $index=> $mod){
      $mod_id = $mod['mod_id'];
      RelativeAndFriendsController::getRafHistory($focusdevotee_id,$mod_id);
    }
  }

  public static function getRafHistory($focusdevotee_id,$mod_id){

    // $raf_list = Raf::leftjoin('devotee','devotee.devotee_id','=','raf.devotee_id')
    //                ->leftjoin('familycode','familycode.familycode_id','=','devotee.familycode_id')
    //                ->leftjoin('member','member.member_id','=','devotee.member_id')
    //                ->where('raf.focusdevotee_id',$focusdevotee_id)
    //                ->where('raf.mod_id',$mod_id)
    //                ->get();

   $transaction_list = Trn::where('focusdevotee_id',$focusdevotee_id)
                          ->where('mod_id',$mod_id)
                          ->where(DB::raw('YEAR(trans_at)'),DateController::getLastYearFormatYYYY())
                          ->get();

   $relative_and_friends_id_list = collect();

   foreach($transaction_list as $transaction){
     $receipt_list = Rct::where('trn_id',$transaction['trn_id'])
                        ->get();

     foreach($receipt_list as $receipt){
       Devotee::isRelative($receipt['devotee_id'],$transaction['focusdevotee_id']) ? $relative_and_friends_id_list->push($receipt['devotee_id']) : false ;
     }
   }

    $relative_and_friends_id_list = $relative_and_friends_id_list->unique()->sort();

    $raf_list = Rct::leftjoin('devotee','devotee.devotee_id','=','rct.devotee_id')
                   ->leftjoin('familycode','familycode.familycode_id','=','devotee.familycode_id')
                   ->leftjoin('member','member.member_id','=','devotee.member_id')
                   ->whereIn('rct.devotee_id',$relative_and_friends_id_list)
                   ->get()
                   ->unique('devotee_id');

    foreach($raf_list as $raf){
      $raf['item_description'] = AddressController::getAddressByDevoteeId($raf['devotee_id']);
    }

    switch ($mod_id) {
      // Xiang You
      case 1:

        break;

      // Ci Ji
      case 2:

        break;

      // Yue Juan
      case 3:

        break;

      // Zhu Xue Jin
      case 4:

        break;

      // Xiao Zai Da Fa Hui
      case 5:
        // $param['raf_list'] = $raf_list;
        // $raf_list = RelativeAndFriendsController::getRafXiaoZai($param);
        // $raf_focus_devotee= $raf_list->filter(function ($value, $key) use($focusdevotee_id) {
        //     if($value['devotee_id'] == $focusdevotee_id ){
        //     return $value;
        //   }
        // });
        //
        // $raf_family= $raf_list->filter(function ($value, $key) use($focusdevotee_id) {
        //     if($value['devotee_id'] != $focusdevotee_id ){
        //     return $value;
        //   }
        // });
        //
        // $raf_list = $raf_focus_devotee->merge($raf_family);

        Session::put('relative_and_friends_history.xiaozai',$raf_list);
        break;

      // Qian Fo Fa Hui
      case 6:

        break;

      // Da Bei Fa Hui
      case 7:

        break;

      // Yao Shi Fa Hui
      case 8:

        break;

      // Qi Fu Fa Hui
      case 9:
        Session::put('relative_and_friends_history.qifu',$raf_list);
        break;

      // Kong Dan
      case 10:
        Session::put('relative_and_friends_history.kongdan',$raf_list);
        break;

      // Pu Du
      case 11:

        break;

      // Chao Du
      case 12:

        break;

      // Shou Sheng Ku Qian
      case 13:

        break;

      default:
    }
  }

  public function updateRafSetting(Request $request)
  {
      // dd($request);
      $param['var']['focusdevotee_id'] = session()->get('focus_devotee')[0]['devotee_id'];
      $param['var']['mod_id'] = $request->mod_id;
      $raf_id_list = $request->raf_id;
      $is_checked_list = $request->is_checked;
      Module::isXiaoZai($param['var']['mod_id']) ? $hjgr_list = $request->hjgr : false;

      if($raf_id_list != []){
        foreach ($raf_id_list as $index => $raf_id){
          $raf = Raf::find($raf_id);
          $raf->is_checked = $is_checked_list[$index];
          $raf->save();
          if($param['var']['mod_id'] == 5){
            $raf_xiaozai = RafXiaoZai::where('raf_id','=',$raf_id)->first();
            $raf_xiaozai->hjgr = $hjgr_list[$index];
            $raf_xiaozai->save();
          }
        }
      }

    RelativeAndFriendsController::getRaf($param);

    $request->session()->flash('success', 'Setting for relative and friends is successfully updated.');

    return redirect()->back();

  }

  public static function deleteRelativeAndFriends(Request $request){
    $devotee_id = $request['devotee_id'];
    RelativeAndFriendsController::deleteRafForAllModule($devotee_id);
    return response()->json([

    ]);

    //$request->session()->flash('success', 'Setting for different address is successfully created.');
    //return redirect()->back();
  }

  public static function deleteRafForAllModule($devotee_id){
    $focusdevotee_id = session()->get('focus_devotee')[0]['devotee_id'];

    $raf_list = Raf::where('devotee_id', $devotee_id)
                   ->where('focusdevotee_id', $focusdevotee_id)
                   //->where('year', DateController::getCurrentYearFormatYYYY())
                   ->select('raf_id','mod_id')
                   ->get();

    foreach($raf_list as $raf){
      Module::isXiaoZai($raf->mod_id) ? RafXiaoZai::where('raf_id', $raf->raf_id)->delete() : false;
      Module::isKongDan($raf->mod_id) ? RafKongDan::where('raf_id', $raf->raf_id)->delete() : false;
      Module::isQiFu($raf->mod_id) ? RafQiFu::where('raf_id', $raf->raf_id)->delete() : false;
      Raf::where('raf_id', $raf->raf_id)->delete();
    }
    RelativeAndFriendsController::getRafForAllModule();
  }


}
