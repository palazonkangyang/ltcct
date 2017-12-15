<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use App\Models\Devotee;
use App\Models\Module;
use App\Models\Sfc;
use App\Models\SfcXiangYou;
use App\Models\SfcCiji;
use App\Models\SfcYueJuan;
use App\Models\SfcXiaoZai;
use App\Models\SfcQiFu;
use App\Models\SfcKongDan;
use App\Models\OptionalAddress;
use App\Models\OptionalVehicle;

class SameFamilyCodeController extends Controller
{
    public static function stepToCreateSameFamilyCodeAfterCreateNewDevotee(){
      $param['focusdevotee_id'] = session()->get('focus_devotee')[0]['devotee_id'];
      $param['familycode_id'] = session()->get('focus_devotee')[0]['familycode_id'];
      $param['sfc_list'] = collect(new Sfc);
      $param['sfc_xiaozai_list'] = collect(new SfcXiaoZai);
      $param['sfc_qifu_list'] = collect(new SfcQiFu);
      $param['sfc_kongdan_list'] = collect(new SfcKongDan);

      // upon devotee creation, new devotee add family records (including devotee) into sfc table for all module
      $param = SameFamilyCodeController::createSfcFocusDevoteeAddFamilyForAllModule($param);
      $param['var'] = null;
      // each of the family member add new devotees into their records into sfc table for all module
      $param = SameFamilyCodeController::createSfcFamilyAddFocusDevoteeForAllModule($param);
      $param['var'] = null;
      // for all family member, create record into sfc children (sfc_*) table
      $param = SameFamilyCodeController::createSfcChildrenForFamily($param);
      $param['var'] = null;
    }

    public static function createSfcFocusDevoteeAddFamilyForAllModule($param){
      $param['mod_list'] = Module::getReleasedModuleList();
      foreach($param['mod_list'] as $index=> $mod){
        $param['var']['mod_id'] = $mod['mod_id'];
        $param = SameFamilyCodeController::createSfcFocusDevoteeAddFamily($param);
      }
      return $param;
    }

    public static function createSfcFocusDevoteeAddFamily($param){
      $family_list = Devotee::where('familycode_id','=',$param['familycode_id'])
                            ->get();

      // sort focus devotee to top of the family lists
      $focusdevotee_collection = $family_list->pop();
      $family_list = $family_list->reverse()->push($focusdevotee_collection)->reverse();

      $param['var']['focusdevotee_id'] = $param['focusdevotee_id'];
      foreach($family_list as $family){
        $param['var']['devotee_id'] = $family['devotee_id'];
        $param['var']['is_checked'] = ($param['var']['focusdevotee_id'] == $family['devotee_id']) ? true : false;
        $param['var']['year'] = null;
        $param = SameFamilyCodeController::createSfc($param);
      }
      return $param;
    }

    public static function createSfc($param){
      $list['devotee_id'] = $param['var']['devotee_id'];
      $list['focusdevotee_id'] = $param['var']['focusdevotee_id'];
      $list['mod_id'] = $param['var']['mod_id'];
      $list['is_checked'] = $param['var']['is_checked'];
      $list['year'] = $param['var']['year'];
      $list['sfc_id'] = Sfc::create($list)->sfc_id;
      $param['var']['sfc_id'] = $list['sfc_id'];
      $param['sfc_list']->push($list);
      return $param;
    }

    public static function createSfcFamilyAddFocusDevoteeForAllModule($param){
      foreach($param['mod_list'] as $index=> $mod){
        $param['var']['mod_id'] = $mod['mod_id'];
        $param = SameFamilyCodeController::createSfcFamilyAddFocusDevotee($param);
      }
      return $param;
    }

    public static function createSfcFamilyAddFocusDevotee($param){
      $family_list = Devotee::where('familycode_id','=',$param['familycode_id'])
                            ->get();

      // sort focus devotee to top of the family lists
      $focusdevotee_collection = $family_list->pop();
      $family_list = $family_list->reverse()->push($focusdevotee_collection)->reverse();

      foreach($family_list as $family){
        if($family['devotee_id'] != $param['focusdevotee_id']){
          $param['var']['focusdevotee_id'] = $family['devotee_id'];
          $param['var']['devotee_id'] = $param['focusdevotee_id'];
          $param['var']['is_checked'] = false;
          $param['var']['year'] = null;
          $param = SameFamilyCodeController::createSfc($param);
        }
      }
      return $param;
    }

    public static function createSfcChildrenForFamily($param){
      foreach($param['sfc_list'] as $index=> $sfc){
        $param['var']['sfc_id'] = $sfc['sfc_id'];
        $param['var']['mod_id'] = $sfc['mod_id'];
        $param['var']['focusdevotee_id'] = $sfc['focusdevotee_id'];
        $param['var']['devotee_id'] = $sfc['devotee_id'];
        $param = SameFamilyCodeController::createSfcChildren($param);
      }
      return $param;
    }

    public static function createSfcChildren($param){

      $list['sfc_id'] = $param['var']['sfc_id'];
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
        $param = XiaoZaiController::createSfcXiaoZaiFromBaseHome($param);
        $param = XiaoZaiController::createSfcXiaoZaiFromOptionalAddress($param);
        $param = XiaoZaiController::createSfcXiaoZaiFromOptionalVehicle($param);
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
        $param['sfc_qifu_list']->push(SfcQiFu::create($list));
        break;

      // Kong Dan
      case 10:
        $param['sfc_kongdan_list']->push(SfcKongDan::create($list));
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

    public function updateSfcSetting(Request $request)
    {
      $param['var']['focusdevotee_id'] = session()->get('focus_devotee')[0]['devotee_id'];
      $param['var']['mod_id'] = $request->mod_id;
      $param['var']['year'] = null;
      $sfc_id_list = $request->sfc_id;
      $is_checked_list = $request->is_checked;
      $hjgr_list = $request->hjgr;

      foreach ($sfc_id_list as $index => $sfc_id){
        $sfc = Sfc::find($sfc_id);
        $sfc->is_checked = $is_checked_list[$index];
        $sfc->save();
        if($param['var']['mod_id'] == 5){
          $sfc_xiaozai = SfcXiaoZai::where('sfc_id','=',$sfc_id)->first();
          $sfc_xiaozai->hjgr = $hjgr_list[$index];
          $sfc_xiaozai->save();
        }
      }

      SameFamilyCodeController::getSfc($param);

      $request->session()->flash('success', 'Setting for same address is successfully created.');
      return redirect()->back();
    }

    public static function getSfcForAllModule(){
      if(Session::has('same_family_code')) { Session::forget('same_family_code'); }
      $param['var']['focusdevotee_id'] = session()->get('focus_devotee')[0]['devotee_id'];
      $param['mod_list'] = Module::getReleasedModuleList();
      $param['var']['year'] = null;
      foreach($param['mod_list'] as $index=> $mod){
        $param['var']['mod_id'] = $mod['mod_id'];
        SameFamilyCodeController::getSfc($param);
      }
    }

    public static function getSfc($param){
      $sfc_list = Sfc::leftjoin('devotee','devotee.devotee_id','=','sfc.devotee_id')
                     ->leftjoin('familycode','familycode.familycode_id','=','devotee.familycode_id')
                     ->leftjoin('member','member.member_id','=','devotee.member_id')
                     ->where('sfc.focusdevotee_id',$param['var']['focusdevotee_id'])
                     ->where('sfc.mod_id',$param['var']['mod_id'])
                     ->where('sfc.year',$param['var']['year'])
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
          $param['sfc_list'] = $sfc_list;
          $sfc_list = SameFamilyCodeController::getSfcXiaoZai($param);
          $sfc_list = Sfc::sortListByFocusDevotee($sfc_list,$param['var']['focusdevotee_id']);
          Session::put('same_family_code.xiaozai',$sfc_list);
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
          Session::put('same_family_code.qifu',$sfc_list);
          break;

        // Kong Dan
        case 10:
          $param['sfc_list'] = $sfc_list;
          $sfc_list = SameFamilyCodeController::getSfcKongDan($param);
          $sfc_list = Sfc::sortListByFocusDevotee($sfc_list,$param['var']['focusdevotee_id']);
          Session::put('same_family_code.kongdan',$sfc_list);
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

    public static function getSfcKongDan($param){
      foreach($param['sfc_list'] as $index=>$sfc){
        $sfc_kongdan = SfcKongDan::where('sfc_id','=',$sfc['sfc_id'])
                                 ->first();
        $sfc['item_description'] = AddressController::getAddressByDevoteeId($sfc['devotee_id']);
      }
      return $param['sfc_list'];
    }

    public static function getSfcXiaoZai($param){
      foreach($param['sfc_list'] as $index=>$sfc){
        $sfc_xiaozai = SfcXiaoZai::where('sfc_id','=',$sfc['sfc_id'])
                                 ->first();
        $sfc['type'] = $sfc_xiaozai['type'];
        $sfc['hjgr'] = $sfc_xiaozai['hjgr'];

        switch ($sfc['type']) {
          case 'base_home':
            $sfc['optionaladdress'] = null;
            $sfc['optionalvehicle'] = null;
            $sfc['item_description'] = AddressController::getAddressByDevoteeId($sfc['devotee_id']);
            break;
          case 'home':
            $optionaladdress_id = SfcXiaoZai::getOptionalAddressIdBySfcXiaoZaiId($sfc_xiaozai['sfc_xiaozai_id']);
            $sfc['optionaladdress'] = OptionalAddress::getOptionalAddressByOptionalAddressId($optionaladdress_id);
            $sfc['optionalvehicle'] = null;
            $sfc['item_description'] = $sfc['optionaladdress']['address'];
            break;
          case 'company':
            $optionaladdress_id = SfcXiaoZai::getOptionalAddressIdBySfcXiaoZaiId($sfc_xiaozai['sfc_xiaozai_id']);
            $sfc['optionaladdress'] = OptionalAddress::getOptionalAddressByOptionalAddressId($optionaladdress_id);
            $sfc['optionalvehicle'] = null;
            $sfc['item_description'] = $sfc['optionaladdress']['data'] .' @ '. $sfc['optionaladdress']['address'];
            break;
          case 'stall':
            $optionaladdress_id = SfcXiaoZai::getOptionalAddressIdBySfcXiaoZaiId($sfc_xiaozai['sfc_xiaozai_id']);
            $sfc['optionaladdress'] = OptionalAddress::getOptionalAddressByOptionalAddressId($optionaladdress_id);
            $sfc['optionalvehicle'] = null;
            $sfc['item_description'] = $sfc['optionaladdress']['data'] .' @ '. $sfc['optionaladdress']['address'];
            break;
          case 'office':
            $optionaladdress_id = SfcXiaoZai::getOptionalAddressIdBySfcXiaoZaiId($sfc_xiaozai['sfc_xiaozai_id']);
            $sfc['optionaladdress'] = OptionalAddress::getOptionalAddressByOptionalAddressId($optionaladdress_id);
            $sfc['optionalvehicle'] = null;
            $sfc['item_description'] = $sfc['optionaladdress']['address'];
            break;
          case 'car':
            $optionalvehicle_id = SfcXiaoZai::getOptionalVehicleIdBySfcXiaoZaiId($sfc_xiaozai['sfc_xiaozai_id']);
            $sfc['optionaladdress'] = null;
            $sfc['optionalvehicle'] = OptionalVehicle::getOptionalVehicleByOptionalAddressId($optionalvehicle_id);
            $sfc['item_description'] = $sfc['optionalvehicle']['data'];
            break;
          case 'ship':
            $optionalvehicle_id = SfcXiaoZai::getOptionalVehicleIdBySfcXiaoZaiId($sfc_xiaozai['sfc_xiaozai_id']);
            $sfc['optionaladdress'] = null;
            $sfc['optionalvehicle'] = OptionalVehicle::getOptionalVehicleByOptionalAddressId($optionalvehicle_id);
            $sfc['item_description'] = $sfc['optionalvehicle']['data'];
            break;
          default:
            $sfc['optionaladdress'] = null;
            $sfc['optionalvehicle'] = null;
            $sfc['item_description'] = null;
        }
      }
      return $param['sfc_list'];
    }
}
