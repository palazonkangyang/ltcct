<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SettingGeneralDonation extends Model
{
  protected $table = 'setting_generaldonation';

  protected $primaryKey = "setting_generaldonation_id";

  protected $fillable = [
    'focusdevotee_id',
    'xiangyou_ciji_id',
    'yuejuan_id',
    'devotee_id',
    'address_code',
    'year'
  ];
}
