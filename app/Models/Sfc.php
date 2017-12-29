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


}
