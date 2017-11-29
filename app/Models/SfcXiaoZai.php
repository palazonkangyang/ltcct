<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SfcXiaoZai extends Model
{
  protected $table = 'sfc_xiaozai';

  protected $primaryKey = "sfc_xiaozai_id";

  protected $fillable = [
    'sfc_id',
    'optionaladdress_id',
    'optionalvehicle_id',
    'type',
    'hjgr'
  ];

  public static function getOptionalAddressIdBySfcXiaoZaiId($sfc_xiaozai_id){
    return SfcXiaoZai::where('sfc_xiaozai_id','=',$sfc_xiaozai_id)->pluck('optionaladdress_id')->first();
  }

  public static function getOptionalVehicleIdBySfcXiaoZaiId($sfc_xiaozai_id){
    return SfcXiaoZai::where('sfc_xiaozai_id','=',$sfc_xiaozai_id)->pluck('optionalvehicle_id')->first();
  }
}
