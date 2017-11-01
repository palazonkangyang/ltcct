<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
  protected $table = 'customer';

  protected $primaryKey = "customer_id";

  protected $fillable = [
    'name',
    'address',
    'contact_no',
    'person_incharge'
  ];
}
