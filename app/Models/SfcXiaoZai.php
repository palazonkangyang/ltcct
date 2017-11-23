<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SfcXiaoZai extends Model
{
  protected $table = 'sfc_xiaozai';

  protected $primaryKey = "sfc_xiaozai_id";

  protected $fillable = [
    'type'
  ];
}
