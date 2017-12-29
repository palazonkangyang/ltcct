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
    'description'
  ];

}
