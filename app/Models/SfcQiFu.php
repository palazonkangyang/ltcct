<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SfcQiFu extends Model
{
  protected $table = 'sfc_qifu';

  protected $primaryKey = "sfc_qifu_id";

  protected $fillable = [
    'sfc_id'
  ];
}
