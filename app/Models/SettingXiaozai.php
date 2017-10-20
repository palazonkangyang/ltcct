<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SettingXiaozai extends Model
{
    protected $table = 'setting_xiaozai';

    protected $primaryKey = "setting_xiaozai_id";

    protected $fillable = [
        'focusdevotee_id',
        'type',
        'xiaozai_id',
        'devotee_id',
        'address_code',
        'year'
    ];
}
