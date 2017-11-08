<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SettingQifu extends Model
{
  protected $table = 'setting_qifu';

  protected $primaryKey = "setting_qifu_id";

  protected $fillable = [
    'focusdevotee_id',
    'qifu_id',
    'devotee_id',
    'address_code',
    'year'
  ];
}
