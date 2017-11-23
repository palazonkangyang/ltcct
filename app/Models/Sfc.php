<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sfc extends Model
{
  protected $table = 'sfc';

  protected $primaryKey = "sfc_id";

  protected $fillable = [
    'devotee_id',
    'focusdevotee_id',
    'mod_id',
    'is_checked',
    'year'
  ];
}
