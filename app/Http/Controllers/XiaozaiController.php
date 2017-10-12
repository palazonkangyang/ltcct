<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\User;
use App\Models\SettingKongdan;
use App\Models\Devotee;
use Auth;
use DB;
use Hash;
use Mail;
use Input;
use Session;
use View;
use URL;
use Carbon\Carbon;

class XiaozaiController extends Controller
{
  public function getXiaoZai()
  {
    return view('fahui.xiaozai');
  }
}
