<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Raf;
use App\Models\RafXiaoZai;
use App\Models\RafQiFu;
use App\Models\RafKongDan;
use App\Models\Module;
use App\Models\OptionalAddress;
use App\Models\OptionalVehicle;
use Session;

class RelativeAndFriendsController extends Controller
{

  public static function addRelativeAndFriends(Request $request){
    $param['raf_list'] = collect(new Raf);
    $param['raf_xiaozai_list'] = collect(new RafXiaoZai);
    $param['raf_qifu_list'] = collect(new RafQiFu);
    $param['raf_kongdan_list'] = collect(new RafKongDan);
    $param['var']['devotee_id'] = $request['devotee_id'];
    $param['var']['focusdevotee_id'] = session()->get('focus_devotee')[0]['devotee_id'];
    $param['var']['is_checked'] = false;
    $param['var']['year'] = null;
    RelativeAndFriendsController::createRafForAllModule($param);
    RelativeAndFriendsController::getRafForAllModule();
  }

  public static function createRafForAllModule(){
    $param['mod_list'] = Module::getReleasedFaHuiModuleList();
    foreach($param['mod_list'] as $index=> $mod){
      $param['var']['mod_id'] = $mod['mod_id'];
      $param = RelativeAndFriendsController::deleteExistingAndCreateRaf($param);
      $param = RelativeAndFriendsController::createRafChildren($param);
    }
    return $param;
  }

  public static function createRaf($param){
    $list['devotee_id'] = $param['var']['devotee_id'];
    $list['focusdevotee_id'] = $param['var']['focusdevotee_id'];
    $list['mod_id'] = $param['var']['mod_id'];
    $list['is_checked'] = $param['var']['is_checked'];
    $list['year'] = $param['var']['year'];
    $list['raf_id'] = Raf::create($list)->raf_id;
    $param['var']['raf_id'] = $list['raf_id'];
    $param['raf_list']->push($list);
    return $param;
  }

  public static function deleteExistingAndCreateRaf($param){
    $list['devotee_id'] = $param['var']['devotee_id'];
    $list['focusdevotee_id'] = $param['var']['focusdevotee_id'];
    $list['mod_id'] = $param['var']['mod_id'];
    $list['is_checked'] = $param['var']['is_checked'];
    $list['year'] = $param['var']['year'];
    $exist_raf_list = Raf::where('devotee_id','=',$list['devotee_id'])
                     ->where('focusdevotee_id','=',$list['focusdevotee_id'])
                     ->where('mod_id','=',$list['mod_id'])
                     ->get();

    // delete existing records in children table
    foreach($exist_raf_list as $index=>$exist_raf){
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
        RafXiaoZai::where('raf_id','=',$exist_raf['raf_id'])
               ->delete();
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
        RafQiFu::where('raf_id','=',$exist_raf['raf_id'])
               ->delete();
        break;

      // Kong Dan
      case 10:
        RafKongDan::where('raf_id','=',$exist_raf['raf_id'])
                  ->delete();
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

      // delete record in Raf
      Raf::where('raf_id','=',$exist_raf['raf_id'])
         ->delete();
    }

    $list['raf_id'] = Raf::create($list)->raf_id;
    $param['var']['raf_id'] = $list['raf_id'];
    $param['raf_list']->push($list);

    return $param;
  }

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
    $param['var']['year'] = null;
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
                   ->where('raf.year',$param['var']['year'])
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
          $raf['item_description'] = $raf['optionaladdress']['address'];
          break;
        case 'company':
          $optionaladdress_id = RafXiaoZai::getOptionalAddressIdByRafXiaoZaiId($raf_xiaozai['raf_xiaozai_id']);
          $raf['optionaladdress'] = OptionalAddress::getOptionalAddressByOptionalAddressId($optionaladdress_id);
          $raf['optionalvehicle'] = null;
          $raf['item_description'] = $raf['optionaladdress']['data'] .' @ '. $raf['optionaladdress']['address'];
          break;
        case 'stall':
          $optionaladdress_id = RafXiaoZai::getOptionalAddressIdByRafXiaoZaiId($raf_xiaozai['raf_xiaozai_id']);
          $raf['optionaladdress'] = OptionalAddress::getOptionalAddressByOptionalAddressId($optionaladdress_id);
          $raf['optionalvehicle'] = null;
          $raf['item_description'] = $raf['optionaladdress']['data'] .' @ '. $raf['optionaladdress']['address'];
          break;
        case 'office':
          $optionaladdress_id = RafXiaoZai::getOptionalAddressIdByRafXiaoZaiId($raf_xiaozai['raf_xiaozai_id']);
          $raf['optionaladdress'] = OptionalAddress::getOptionalAddressByOptionalAddressId($optionaladdress_id);
          $raf['optionalvehicle'] = null;
          $raf['item_description'] = $raf['optionaladdress']['address'];
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


}
