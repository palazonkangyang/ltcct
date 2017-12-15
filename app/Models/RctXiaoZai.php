<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RctXiaoZai extends Model
{
  protected $table = 'rct_xiaozai';

  protected $primaryKey = 'rct_id';

  protected $fillable = [
    'rct_id',
    'type',
    'type_chinese_name'
  ];

  public static function getType($rct_id){
    return RctXiaoZai::where('rct_id',$rct_id)->pluck('type')->first();
  }

  public static function getTypeChineseName($rct_id){
    return RctXiaoZai::where('rct_id',$rct_id)->pluck('type_chinese_name')->first();
  }

}
