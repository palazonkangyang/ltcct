<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SfcKongDan extends Model
{
  protected $table = 'sfc_kongdan';

  protected $primaryKey = "sfc_kongdan_id";

  protected $fillable = [
    'sfc_id'
  ];
}
