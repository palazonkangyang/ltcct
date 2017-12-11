<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Raf extends Model
{
  protected $table = 'raf';

  protected $primaryKey = "raf_id";

  protected $fillable = [
    'devotee_id',
    'focusdevotee_id',
    'mod_id',
    'is_checked',
    'year'
  ];

}
