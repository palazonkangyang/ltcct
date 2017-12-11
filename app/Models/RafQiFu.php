<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class RafQiFu extends Model
{
  protected $table = 'raf_qifu';

  protected $primaryKey = "raf_qifu_id";

  protected $fillable = [
    'raf_id'
  ];
}
