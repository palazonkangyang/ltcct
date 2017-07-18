<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FestiveEvent extends Model
{
    protected $table = 'festiveevent';

    protected $primaryKey = "festiveevent_id";

    protected $fillable = [
        'event',
        'start_at',
        'end_at',
        'lunar_date',
        'time',
        'shuwen_title',
        'display',
        'letter_template_id'
    ];
}
