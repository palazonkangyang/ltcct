<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GlCode extends Model
{
  protected $table = 'glcode';

  protected $primaryKey = "glcode_id";

  protected $fillable = [
    'accountcode',
    'type_name',
    'chinese_name',
    'balance',
    'price',
    'job_id',
    'next_sn_number',
    'receipt_prefix',
    'glcodegroup_id'
  ];

  public static function getReceiptPrefixByGLCodeId($glcode_id){
    return GlCode::where('glcode_id',$glcode_id)->pluck('receipt_prefix')->first();
  }
}
