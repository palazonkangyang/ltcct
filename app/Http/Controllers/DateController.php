<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class DateController extends Controller
{

  public static function getCurrentTimeZone(){
    return 'Singapore';
  }

  public static function getCurrentYearFormatYYYY(){
    return Carbon::now(DateController::getCurrentTimeZone())->year;
  }

  public static function getLastYearFormatYYYY(){
    return Carbon::now(DateController::getCurrentTimeZone())->year - 1;
  }

  public static function getLastTwoYearFormatYYYY(){
    return Carbon::now(DateController::getCurrentTimeZone())->year - 2;
  }
}