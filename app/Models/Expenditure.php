<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expenditure extends Model
{
  protected $table = 'expenditure';

  protected $primaryKey = "expenditure_id";
  
  protected $fillable = [
    'reference_no',
    'date',
    'supplier',
    'description',
    'glcode_id',
    'credit_total',
    'status'
  ];
}
