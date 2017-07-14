<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FestiveEventType extends Model
{
    protected $table = 'festiveeventtype';

    protected $primaryKey = "festiveeventtype_id";

    protected $fillable = [
        'name',
        'description'
    ];
}
