<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class APVendorType extends Model
{
  protected $table = 'ap_vendor_type';

  protected $primaryKey = 'ap_vendor_type_id';

  protected $fillable = [
    'vendor_type_name'
  ];

  public static function getAll(){
    return APVendorType::all();
  }

  public static function getAPVendorType($ap_vendor_type_id){
    return APVendorType::where('ap_vendor_type_id',$ap_vendor_type_id)->first();
  }

  public static function getVendorTypeName($ap_vendor_type_id){
    return APVendorType::where('ap_vendor_type_id',$ap_vendor_type_id)->pluck('vendor_type_name')->first();
  }
}
