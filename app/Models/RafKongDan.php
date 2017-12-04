<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RafKongDan extends Model
{
  protected $table = 'raf_kongdan';

  protected $primaryKey = "raf_kongdan_id";

  protected $fillable = [
    'raf_id'
  ];
}
