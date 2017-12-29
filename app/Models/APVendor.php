<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class APVendor extends Model
{
  protected $table = 'ap_vendor';

  protected $primaryKey = 'ap_vendor_id';

  protected $fillable = [
    'vendor_code',
    'vendor_name',
    'ap_vendor_type_id',
    'contact_information',
    'description',
    'glcode_id_list'
  ];

  public static function getGlcodeIdList($ap_vendor_id){
    $glcode_id_list = APVendor::where('ap_vendor_id',$ap_vendor_id)->pluck('glcode_id_list')->first();
    return $glcode_id_list != NULL ? explode(',',$glcode_id_list) : [];
  }

  public static function getGlAccountNameList($glcode_list){
    $gl_account_list = [];
    foreach($glcode_list as $glcode){
      array_push($gl_account_list,GlCode::getTypeName($glcode));
    }
    return $gl_account_list;
  }

}
