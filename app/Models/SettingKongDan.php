<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SettingKongdan extends Model
{
  protected $table = 'setting_kongdan';

  protected $primaryKey = "setting_kongdan_id";

  protected $fillable = [
    'focusdevotee_id',
    'kongdan_id',
    'devotee_id',
    'address_code',
    'year'
  ];
}
