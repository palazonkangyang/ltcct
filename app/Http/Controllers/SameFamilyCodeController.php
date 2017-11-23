<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use App\Models\Sfc;

class SameFamilyCodeController extends Controller
{
    public static function createAllSameFamilyCode(){
      // SameFamilyCodeController::createSameFamilyCode(1);  // Xiang You
      // SameFamilyCodeController::createSameFamilyCode(2);  // Ci Ji
      // SameFamilyCodeController::createSameFamilyCode(3);  // Yue Juan
      // SameFamilyCodeController::createSameFamilyCode(4);  // Zhu Xue Jin
      SameFamilyCodeController::createSameFamilyCode(5);  // Xiao Zai Da Fa Hui
      // SameFamilyCodeController::createSameFamilyCode(6);  // Qian Fo Fa Hui
      // SameFamilyCodeController::createSameFamilyCode(7);  // Da Bei Fa Hui
      // SameFamilyCodeController::createSameFamilyCode(8);  // Yao Shi Fa Hui
      // SameFamilyCodeController::createSameFamilyCode(9);  // Qi Fu Fa Hui
      // SameFamilyCodeController::createSameFamilyCode(10);  // Kong Dan
      // SameFamilyCodeController::createSameFamilyCode(11);  // Pu Du
      // SameFamilyCodeController::createSameFamilyCode(12);  // Chao Du
      // SameFamilyCodeController::createSameFamilyCode(13);  // Shou Sheng Ku Qian

    }

    public static function createSameFamilyCode($mod_id){
      $list = [
        "devotee_id" => 272982,
        "focusdevotee_id" => 272981,
        "mod_id" => $mod_id,
        "is_checked" => false,
        "year" => null
      ];

      Sfc::create($list);

    }



    public static function updateAllSameFamilyCode(){
      // updateSameFamilyCode(1);  // Xiang You
      // updateSameFamilyCode(2);  // Ci Ji
      // updateSameFamilyCode(3);  // Yue Juan
      // updateSameFamilyCode(4);  // Zhu Xue Jin
      SameFamilyCodeController::updateSameFamilyCode(5);  // Xiao Zai Da Fa Hui
      // updateSameFamilyCode(6);  // Qian Fo Fa Hui
      // updateSameFamilyCode(7);  // Da Bei Fa Hui
      // updateSameFamilyCode(8);  // Yao Shi Fa Hui
      // updateSameFamilyCode(9);  // Qi Fu Fa Hui
      //updateSameFamilyCode(10);  // Kong Dan
      //updateSameFamilyCode(11);  // Pu Du
      //updateSameFamilyCode(12);  // Chao Du
      //updateSameFamilyCode(13);  // Shou Sheng Ku Qian

    }

    public static function updateSameFamilyCode($mod_id){
      if(Session::has('same_family_code')) { Session::forget('same_family_code'); }
      $focus_devotee_id = session()->get('focus_devotee')[0]['devotee_id'];
      $familycode_id = session()->get('focus_devotee')[0]['familycode_id'];

      $same_family_code = Sfc::leftjoin('devotee','devotee.devotee_id','=','sfc.devotee_id')
                             ->leftjoin('familycode','familycode.familycode_id','=','devotee.familycode_id')
                             ->leftjoin('member','member.member_id','=','devotee.member_id')
                             ->where('sfc.mod_id',$mod_id)
                             ->where('sfc.year',null)
                             ->get();

      if($mod_id == 5){
        $same_family_code = XiaozaiController::updateSameFamilyCodeUniqueField($same_family_code);
      }

      Session::put('same_family_code', $same_family_code);
    }
}
