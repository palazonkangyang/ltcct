<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RafXiaoZai extends Model
{
  protected $table = 'raf_xiaozai';

  protected $primaryKey = "raf_xiaozai_id";

  protected $fillable = [
    'raf_id',
    'optionaladdress_id',
    'optionalvehicle_id',
    'type',
    'hjgr'
  ];

  public static function getOptionalAddressIdByRafXiaoZaiId($raf_xiaozai_id){
    return RafXiaoZai::where('raf_xiaozai_id','=',$raf_xiaozai_id)->pluck('optionaladdress_id')->first();
  }

  public static function getOptionalVehicleIdByRafXiaoZaiId($raf_xiaozai_id){
    return RafXiaoZai::where('raf_xiaozai_id','=',$raf_xiaozai_id)->pluck('optionalvehicle_id')->first();
  }



}
