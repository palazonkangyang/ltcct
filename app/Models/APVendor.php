<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class APVendor extends Model
{
  protected $table = 'ap_vendor';

  protected $primaryKey = 'ap_vendor_id';

  protected $fillable = [
    'vendor_name',
    'description'
  ];
}
