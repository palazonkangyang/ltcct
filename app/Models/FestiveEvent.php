<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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
    'job_id',
    'letter_template_id'
  ];

  public static function getNextEvent(){
    return FestiveEvent::where('end_at','>',Carbon::now())
                       ->first();
  }



}
