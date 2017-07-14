<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FestiveEvent extends Model
{
    protected $table = 'festiveevent';

    protected $primaryKey = "festiveevent_id";

    protected $fillable = [
        'title',
        'description',
        'start_at',
        'end_at',
        'letter_template_id'
    ];
}
