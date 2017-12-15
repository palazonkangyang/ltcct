<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RctKongDan extends Model
{
  protected $table = 'rct_kongdan';

  protected $primaryKey = 'rct_id';

  protected $fillable = [
    'rct_id'
  ];
}
