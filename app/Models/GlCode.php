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

  public static function getAll(){
    return GlCode::all();
  }

  public static function getGlCodeOfCashInHand(){
    return 11;
  }

  public static function getTypeName($glcode_id){
    return GlCode::where('glcode_id',$glcode_id)->pluck('type_name')->first();
  }

  public static function getReceiptPrefixByGlCodeId($glcode_id){
    return GlCode::where('glcode_id',$glcode_id)->pluck('receipt_prefix')->first();
  }

  public static function getChineseNameByGlCodeId($glcode_id){
    return GlCode::where('glcode_id',$glcode_id)->pluck('chinese_name')->first();
  }

  public static function getChequeAccountList(){
    return GLCode::whereIn('glcode_id',[7,8,14,15,16])->get();
  }

  public static function getBalance($glcode_id){
    return GLCode::where('glcode_id',$glcode_id)->pluck('balance')->first();
  }

}
