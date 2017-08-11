<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dialect extends Model
{
    protected $table = 'dialect';

    protected $primaryKey = "dialect_id";

    protected $fillable = [
        'dialect_id',
        'dialect_name'
    ];
}
