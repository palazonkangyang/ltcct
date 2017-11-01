<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Amount extends Model
{
  protected $table = 'amount';

  protected $primaryKey = "amount_id";

  protected $fillable = [
    'minimum_amount'
  ];
}
