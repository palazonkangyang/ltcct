<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
  protected $table = 'module';

  protected $primaryKey = "mod_id";

  protected $fillable = [
      'core_module_id',
      'english_name',
      'chinese_name'
  ];

  public static function getAllModuleList(){
    return Module::all();
  }

  public static function getFaHuiModuleList(){
    return Module::where('core_module_id','=','2')
                 ->get();
  }

  public static function getReleasedModuleList(){
    $released_module = array(5,9,10);
    $module_list = Module::getAllModuleList();
    $released_module_list =$module_list->filter(function ($value, $key) use ($released_module) {
      if(in_array($value['mod_id'],$released_module)){
        return $value;
      }
    });
    return $released_module_list;
  }

  public static function getReleasedFaHuiModuleList(){
    $released_module = array(5,9,10);
    $module_list = Module::getFaHuiModuleList();
    $released_module_list =$module_list->filter(function ($value, $key) use ($released_module) {
      if(in_array($value['mod_id'],$released_module)){
        return $value;
      }
    });
    return $released_module_list;
  }

  public static function getDescription($mod_id){
    return Module::where('mod_id',$mod_id)->pluck('description')->first();
  }

  public static function isXiangYou($mod_id){
    return $mod_id == 1;
  }

  public static function isCiJi($mod_id){
    return $mod_id == 2;
  }

  public static function isYueJuan($mod_id){
    return $mod_id == 3;
  }

  public static function isZhuXueJin($mod_id){
    return $mod_id == 4;
  }

  public static function isXiaoZai($mod_id){
    return $mod_id == 5;
  }

  public static function isQianFo($mod_id){
    return $mod_id == 6;
  }

  public static function isDaBei($mod_id){
    return $mod_id == 7;
  }

  public static function isYaoShi($mod_id){
    return $mod_id == 8;
  }

  public static function isQiFu($mod_id){
    return $mod_id == 9;
  }

  public static function isKongDan($mod_id){
    return $mod_id == 10;
  }

  public static function isPuDu($mod_id){
    return $mod_id == 11;
  }

  public static function isChaoDu($mod_id){
    return $mod_id == 12;
  }

  public static function isShouSheng($mod_id){
    return $mod_id == 13;
  }

}
