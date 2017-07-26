<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use Auth;
use DB;
use Hash;
use Mail;
use Input;
use Session;
use View;
use URL;
use Carbon\Carbon;

class JobController extends Controller
{
  public function getJob()
  {
    return view('job.manage-job');
  }
}
