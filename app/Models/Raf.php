<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\DateController;

class Raf extends Model
{
  protected $table = 'raf';

  protected $primaryKey = "raf_id";

  protected $fillable = [
    'devotee_id',
    'focusdevotee_id',
    'mod_id',
    'is_checked',
    'year'
  ];

  public static function isExists($focusdevotee_id,$devotee_id){
    $result = Raf::where('devotee_id',$devotee_id)
                 ->where('focusdevotee_id',$focusdevotee_id)
                 //->where('year',DateController::getCurrentYearFormatYYYY())
                 ->count();

    return $result > 0 ? true : false ;
  }

  public static function isNotExists($focusdevotee_id,$devotee_id){
    $result = Raf::where('devotee_id',$devotee_id)
                 ->where('focusdevotee_id',$focusdevotee_id)
                 //->where('year',DateController::getCurrentYearFormatYYYY())
                 ->count();

    return $result == 0 ? true : false ;
  }

}
