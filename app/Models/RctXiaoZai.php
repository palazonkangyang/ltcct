<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RctXiaoZai extends Model
{
  protected $table = 'rct_xiaozai';

  protected $primaryKey = 'rct_id';

  protected $fillable = [
    'rct_id',
    'type'
  ];
}
