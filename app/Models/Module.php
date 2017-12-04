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
}
