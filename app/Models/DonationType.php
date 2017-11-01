<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DonationType extends Model
{
  protected $table = 'donationtype';

  protected $primaryKey = "donationtype_id";

  protected $fillable = [
    'donationtype_name'
  ];
}
