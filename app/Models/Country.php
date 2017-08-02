<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $table = 'country';

    protected $fillable = [
        'country_id',
        'country_name',
        'country_code'
    ];
}
