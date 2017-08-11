<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Race extends Model
{
    protected $table = 'race';

    protected $primaryKey = "race_id";

    protected $fillable = [
        'race_id',
        'race_name'
    ];
}
