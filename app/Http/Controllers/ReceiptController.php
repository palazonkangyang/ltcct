<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rct;
use App\Models\Module;
use App\Models\RctXiaoZai;
use App\Models\Devotee;

class ReceiptController extends Controller
{
    public static function createReceipt($param){
      foreach($param['var']['devotee_id_list'] as $index=>$devotee_id){
        if($param['var']['is_checked_list'][$index] == true){
          $param['receipt']['devotee_id'] = $devotee_id;
          $param['receipt']['devotee_chinese_name'] = Devotee::getChineseName($devotee_id);
          $param['receipt']['glcode_id'] = Rct::getGLCode($devotee_id , $param['receipt']['mod_id']);
          $param['receipt']['receipt_no'] = Rct::generateReceiptNo($param['receipt']['mod_id'],$param['receipt']['glcode_id']);
          $param['receipt']['amount'] = $param['var']['amount_list'][$index];
          $param['receipt']['item_description'] = $param['var']['item_description_list'][$index];
          $param['receipt_children']['rct_id'] = Rct::create($param['receipt'])->rct_id;
          $mod_id = $param['receipt']['mod_id'];

          if(Module::isXiangYou($mod_id)){

          }

          elseif (Module::isCiJi($mod_id)){

          }

          elseif (Module::isYueJuan($mod_id)){

          }

          elseif (Module::isZhuXueJin($mod_id)){

          }

          elseif (Module::isXiaoZai($mod_id)){
            $param['receipt_children']['type'] = $param['var']['type_list'][$index];
            $param['receipt_children']['type_chinese_name'] = $param['var']['type_chinese_name_list'][$index];
            RctXiaoZai::create($param['receipt_children']);
          }

          elseif (Module::isQianFo($mod_id)){

          }

          elseif (Module::isDaBei($mod_id)){

          }

          elseif (Module::isYaoShi($mod_id)){

          }

          elseif (Module::isQiFu($mod_id)){

          }

          elseif (Module::isKongDan($mod_id)){

            RctKongDan::create($param['receipt_children']);
          }

          elseif (Module::isPuDu($mod_id)){

          }

          elseif (Module::isChaoDu($mod_id)){

          }

          elseif (Module::isShouSheng($mod_id)){

          }
        }
      }

    }

}
