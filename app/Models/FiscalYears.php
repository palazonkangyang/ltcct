<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FiscalYears extends Model
{
  protected $table = 'fiscalyears';

  protected $primaryKey = "fiscalyears_id";

  protected $fillable = [
    'status',
    'start_at',
    'end_at'
  ];
}
