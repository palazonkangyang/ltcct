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
      $param['mod_list'] = Module::getReleasedModuleList();

      // upon devotee creation, new devotee add family records (including devotee) into sfc table for all module
      $param = SameFamilyCodeController::createSfcFocusDevoteeAddFamilyForAllModule($param);
      $param['var'] = null;
      // each of the family member add new devotees into their records into sfc table for all module
      $param = SameFamilyCodeController::createSfcFamilyAddFocusDevoteeForAllModule($param);
      $param['var'] = null;
      // for all family member, create record into sfc children (sfc_*) table
      $param = SameFamilyCodeController::createSfcChildrenForAllModule($param);
      $param['var'] = null;
    }

    public static function createSfcFocusDevoteeAddFamilyForAllModule($param){
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

    public static function createSfcChildrenForAllModule($param){
      foreach($param['mod_list'] as $index=> $mod){
        $param['var']['mod_id'] = $mod['mod_id'];
        $param = SameFamilyCodeController::createSfcChildrenForEachModule($param);
      }
      return $param;
    }

    public static function createSfcChildrenForEachModule($param){
      foreach($param['sfc_list'] as $index=> $sfc){
        $param['var']['sfc_id'] = $sfc['sfc_id'];
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




    public static function createAllSameFamilyCodeRecord(){
      //SameFamilyCodeController::createSameFamilyCodeRecord(1);  // Xiang You
      //SameFamilyCodeController::createSameFamilyCodeRecord(2);  // Ci Ji
      //SameFamilyCodeController::createSameFamilyCodeRecord(3);  // Yue Juan
      //SameFamilyCodeController::createSameFamilyCodeRecord(4);  // Zhu Xue Jin
      SameFamilyCodeController::createSameFamilyCodeRecord(5,session()->get('focus_devotee')[0]['devotee_id']);  // Xiao Zai Da Fa Hui
      //SameFamilyCodeController::createSameFamilyCodeRecord(6);  // Qian Fo Fa Hui
      //SameFamilyCodeController::createSameFamilyCodeRecord(7);  // Da Bei Fa Hui
      // SameFamilyCodeController::createSameFamilyCodeRecord(8);  // Yao Shi Fa Hui
      SameFamilyCodeController::createSameFamilyCodeRecord(9,session()->get('focus_devotee')[0]['devotee_id']);  // Qi Fu Fa Hui
      SameFamilyCodeController::createSameFamilyCodeRecord(10,session()->get('focus_devotee')[0]['devotee_id']);  // Kong Dan
      //SameFamilyCodeController::createSameFamilyCodeRecord(11);  // Pu Du
      // SameFamilyCodeController::createSameFamilyCodeRecord(12);  // Chao Du
      // SameFamilyCodeController::createSameFamilyCodeRecord(13);  // Shou Sheng Ku Qian
    }

    public static function createSameFamilyCodeRecord($mod_id,$devotee_id){

      // declare variable
      $focus_devotee_id = $devotee_id;
      $familycode_id = session()->get('focus_devotee')[0]['familycode_id'];
      $param = [];
      $param['mod_id'] = $mod_id;
      $param['sfc_list'] = [];

      // create record for focused devotee in Sfc
      $list['devotee_id'] = $focus_devotee_id;
      $list['focusdevotee_id'] = $focus_devotee_id;
      $list['mod_id'] = $mod_id;
      $list['is_checked'] = true;
      $list['year'] = null;
      $list['sfc_id'] = Sfc::create($list)->sfc_id;
      if($mod_id == 5){
        $list['type'] = 'base_home';
        $list['optionaladdress_id'] = null;
        $list['optionalvehicle_id'] = null;
      }
      array_push($param['sfc_list'],$list);

      // Xiao Zai Da Fa Hui
      if($mod_id == 5){
        $oa_list = OptionalAddress::where('devotee_id','=',$focus_devotee_id)
                                  ->get();

        // create record for focused devotee in Sfc with Xiao Zai data
        foreach($oa_list as $oa){
          $list['is_checked'] = false;
          $list['sfc_id'] = Sfc::create($list)->sfc_id;
          $list['optionaladdress_id'] = $oa['optionaladdress_id'];
          $list['optionalvehicle_id'] = null;
          $list['type'] = $oa['type'];
          array_push($param['sfc_list'],$list);
        }

        $ov_list = OptionalVehicle::where('devotee_id','=',$focus_devotee_id)
                                  ->get();

        foreach($ov_list as $ov){
          $list['is_checked'] = false;
          $list['sfc_id'] = Sfc::create($list)->sfc_id;
          $list['optionaladdress_id'] = null;
          $list['optionalvehicle_id'] = $ov['optionalvehicle_id'];
          $list['type'] = $ov['type'];
          array_push($param['sfc_list'],$list);
        }

      }

      // create record for family member of focused devotee
      $sfc_member = Devotee::where('familycode_id','=',$familycode_id)
                           ->where('devotee_id','!=',$focus_devotee_id)
                           ->get();

      foreach($sfc_member as $sfc_m){
        $list['devotee_id'] = $sfc_m['devotee_id'];
        $list['focusdevotee_id'] = $focus_devotee_id;
        $list['mod_id'] = $mod_id;
        $list['is_checked'] = false;
        $list['year'] = null;
        $list['sfc_id'] = Sfc::create($list)->sfc_id;

        // Xiao Zai Da Fa Hui
        if($mod_id == 5){
          $oa_list = OptionalAddress::where('devotee_id','=',$sfc_m['devotee_id'])
                                    ->get();

          // create record for family member of focused devotee in Sfc with Xiao Zai data
          foreach($oa_list as $oa){
            $list['sfc_id'] = Sfc::create($list)->sfc_id;
            $list['optionaladdress_id'] = $oa['optionaladdress_id'];
            $list['optionalvehicle_id'] = null;
            $list['type'] = $oa['type'];
            array_push($param['sfc_list'],$list);
          }

          $ov_list = OptionalVehicle::where('devotee_id','=',$sfc_m['devotee_id'])
                                    ->get();

          foreach($ov_list as $ov){
            $list['sfc_id'] = Sfc::create($list)->sfc_id;
            $list['optionaladdress_id'] = null;
            $list['optionalvehicle_id'] = $ov['optionalvehicle_id'];
            $list['type'] = $ov['type'];
            array_push($param['sfc_list'],$list);
          }

        }

      }

      SameFamilyCodeController::createSameFamilyCodeChildren($param);

    }

    public static function createSameFamilyCodeChildren($param){

      foreach($param['sfc_list'] as $index => $sfc){
        $list['sfc_id'] = $sfc['sfc_id'];

        switch ($param['mod_id']) {
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
          $list['optionaladdress_id'] = $sfc['optionaladdress_id'];
          $list['optionalvehicle_id'] = $sfc['optionalvehicle_id'];
          $list['type'] = $sfc['type'];

          switch ($sfc['type']) {
            case 'base_home':
              $list['hjgr'] = 'hj';
              break;
            case 'home':
              $list['hjgr'] = 'hj';
              break;
            case 'company':
              $list['hjgr'] = null;
              break;
            case 'stall':
              $list['hjgr'] = null;
              break;
            case 'office':
              $list['hjgr'] = 'gr';
              break;
            case 'car':
              $list['hjgr'] = null;
              break;
            case 'ship':
              $list['hjgr'] = null;
              break;
            default:
              $list['hjgr'] = null;
          }

          SfcXiaoZai::create($list);
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
          SfcQiFu::create($list);
          break;

        // Kong Dan
        case 10:
          SfcKongDan::create($list);
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


      } // foreach


    }

    public static function updateAllSameFamilyCodeAfterCreateNewDevotee(){
      //SameFamilyCodeController::updateSameFamilyCodeAfterCreateNewDevotee(1);  // Xiang You
      //SameFamilyCodeController::updateSameFamilyCodeAfterCreateNewDevotee(2);  // Ci Ji
      //SameFamilyCodeController::updateSameFamilyCodeAfterCreateNewDevotee(3);  // Yue Juan
      //SameFamilyCodeController::updateSameFamilyCodeAfterCreateNewDevotee(4);  // Zhu Xue Jin
      SameFamilyCodeController::updateSameFamilyCodeAfterCreateNewDevotee(5);  // Xiao Zai Da Fa Hui
      //SameFamilyCodeController::updateSameFamilyCodeAfterCreateNewDevotee(6);  // Qian Fo Fa Hui
      //SameFamilyCodeController::updateSameFamilyCodeAfterCreateNewDevotee(7);  // Da Bei Fa Hui
      // SameFamilyCodeController::updateSameFamilyCodeAfterCreateNewDevotee(8);  // Yao Shi Fa Hui
      SameFamilyCodeController::updateSameFamilyCodeAfterCreateNewDevotee(9);  // Qi Fu Fa Hui
      SameFamilyCodeController::updateSameFamilyCodeAfterCreateNewDevotee(10);  // Kong Dan
      //SameFamilyCodeController::updateSameFamilyCodeAfterCreateNewDevotee(11);  // Pu Du
      // SameFamilyCodeController::updateSameFamilyCodeAfterCreateNewDevotee(12);  // Chao Du
      // SameFamilyCodeController::updateSameFamilyCodeAfterCreateNewDevotee(13);  // Shou Sheng Ku Qian
    }

    // Main Page > New Devotee
    // After New Devotee has been created into existing same family code,
    // add focus devotee records into family memberm's member list
    public static function updateSameFamilyCodeAfterCreateNewDevotee($mod_id){
      $focus_devotee_id = session()->get('focus_devotee')[0]['devotee_id'];
      $familycode_id = session()->get('focus_devotee')[0]['familycode_id'];

      $sfc_member = Devotee::where('familycode_id','=',$familycode_id)
                           ->where('devotee_id','!=',$focus_devotee_id)
                           ->get();

      foreach($sfc_member as $sfc_m){
        // $list = [
        //   "devotee_id" => $focus_devotee_id,
        //   "focusdevotee_id" => $sfc_m['devotee_id'],
        //   "mod_id" => $mod_id,
        //   "is_checked" => false,
        //   "year" => null
        // ];
        // Sfc::create($list);
        if($mod_id == 5){
        SameFamilyCodeController::createSameFamilyCodeRecord(5,$sfc_m['devotee_id']);
        }
      }



    }

    public function updateSameFamilySetting(Request $request)
    {
      $focus_devotee_id = session()->get('focus_devotee')[0]['devotee_id'];
      $mod_id = $request->mod_id;
      $sfc_id_list = $request->sfc_id;
      $is_checked_list = $request->is_checked;
      $hjgr_list = $request->hjgr;

      foreach ($sfc_id_list as $index => $sfc_id){

        $sfc = Sfc::find($sfc_id);
        $sfc->is_checked = $is_checked_list[$index];
        $sfc->save();
        if($mod_id == 5){
          $sfc_xiaozai = SfcXiaoZai::where('sfc_id','=',$sfc_id)->first();

          $sfc_xiaozai->hjgr = $hjgr_list[$index];

          $sfc_xiaozai->save();

        }
      }

      switch ($request->mod_id) {

      // Xiang You
      case 1:
          //Session::put('same_family_code.xiangyou',SameFamilyCodeController::getSameFamilyCode(1));
        break;

      // Ci Ji
      case 2:
        //Session::put('same_family_code.ciji',SameFamilyCodeController::getSameFamilyCode(2));
        break;

      // Yue Juan
      case 3:
        //Session::put('same_family_code.yuejuan',SameFamilyCodeController::getSameFamilyCode(3));
        break;

      // Zhu Xue Jin
      case 4:

        break;

      // Xiao Zai Da Fa Hui
      case 5:
        Session::put('same_family_code.xiaozai',SameFamilyCodeController::getSameFamilyCode(5));
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
        Session::put('same_family_code.qifu',SameFamilyCodeController::getSameFamilyCode(9));
        break;

      // Kong Dan
      case 10:
        Session::put('same_family_code.kongdan',SameFamilyCodeController::getSameFamilyCode(10));
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

      $request->session()->flash('success', 'Setting for same address is successfully created.');
      return redirect()->back();
    }

    public static function getAllSameFamilyCode(){
      if(Session::has('same_family_code')) { Session::forget('same_family_code'); }
      $same_family_code = [];
      //$same_family_code['xiangyou'] = SameFamilyCodeController::getSameFamilyCode(1);  // Xiang You
      //$same_family_code['ciji'] = SameFamilyCodeController::getSameFamilyCode(2);  // Ci Ji
      //$same_family_code['yuejuan'] = SameFamilyCodeController::getSameFamilyCode(3);  // Yue Juan
      // $same_family_code['zhuxuejin'] = SameFamilyCodeController::getSameFamilyCode(4);  // Zhu Xue Jin
      $same_family_code['xiaozai'] = SameFamilyCodeController::getSameFamilyCode(5);  // Xiao Zai Da Fa Hui
      // $same_family_code['qianfo'] = SameFamilyCodeController::getSameFamilyCode(6);  // Qian Fo Fa Hui
      // $same_family_code['dabei'] = SameFamilyCodeController::getSameFamilyCode(7);  // Da Bei Fa Hui
      // $same_family_code['yaoshi'] = SameFamilyCodeController::getSameFamilyCode(8);  // Yao Shi Fa Hui
      $same_family_code['qifu'] = SameFamilyCodeController::getSameFamilyCode(9);  // Qi Fu Fa Hui
      $same_family_code['kongdan'] = SameFamilyCodeController::getSameFamilyCode(10);  // Kong Dan
      //$same_family_code['pudu'] = SameFamilyCodeController::getSameFamilyCode(11);  // Pu Du
      // $same_family_code['chaodu'] = SameFamilyCodeController::getSameFamilyCode(12);  // Chao Du
      // $same_family_code['shousheng'] = SameFamilyCodeController::getSameFamilyCode(13);  // Shou Sheng Ku Qian

      Session::put('same_family_code', $same_family_code);
      //dd(Session('same_family_code'));
    }

    public static function getSameFamilyCode($mod_id){
      $focus_devotee_id = session()->get('focus_devotee')[0]['devotee_id'];
      $familycode_id = session()->get('focus_devotee')[0]['familycode_id'];

      $param = [];
      $param['mod_id'] = $mod_id;
      $same_family_code = Sfc::leftjoin('devotee','devotee.devotee_id','=','sfc.devotee_id')
                             ->leftjoin('familycode','familycode.familycode_id','=','devotee.familycode_id')
                             ->leftjoin('member','member.member_id','=','devotee.member_id')
                             ->where('sfc.focusdevotee_id',$focus_devotee_id)
                             ->where('sfc.mod_id',$mod_id)
                             ->where('sfc.year',null)
                             ->get();

      if($mod_id == 5){
        $param['sfc_list'] = $same_family_code;
        $same_family_code = SameFamilyCodeController::getSameFamilyCodeXiaoZai($param);
      }
      return $same_family_code;
    }

    public static function getSameFamilyCodeXiaoZai($param){
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
