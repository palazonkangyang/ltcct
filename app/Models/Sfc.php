<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sfc extends Model
{
  protected $table = 'sfc';

  protected $primaryKey = "sfc_id";

  protected $fillable = [
    'devotee_id',
    'focusdevotee_id',
    'mod_id',
    'is_checked',
    'year'
  ];

  public static function getDevoteeIdBySfcId($sfc_id){
    return Sfc::where('sfc_id','=',$sfc_id)->pluck('devotee_id')->first();
  }

  public static function sortListByFocusDevotee($sfc_list,$focusdevotee_id){
    $sfc_focus_devotee= $sfc_list->filter(function ($value, $key) use($focusdevotee_id) {
        if($value['devotee_id'] == $focusdevotee_id ){
        return $value;
      }
    });

    $sfc_family= $sfc_list->filter(function ($value, $key) use($focusdevotee_id) {
        if($value['devotee_id'] != $focusdevotee_id ){
        return $value;
      }
    });

    $sfc_list = $sfc_focus_devotee->merge($sfc_family);

    return $sfc_list;
  }


}
