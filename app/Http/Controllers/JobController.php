<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\User;
use App\Models\Job;
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
    $job = Job::orderBy('created_at', 'desc')->get();

    return view('job.manage-job', [
      'job' => $job
    ]);
  }

  public function postAddNewJob(Request $request)
  {
    $input = array_except($request->all(), '_token');

    if(isset($input['authorized_password']))
    {
      $user = User::find(Auth::user()->id);
      $hashedPassword = $user->password;

      if (Hash::check($input['authorized_password'], $hashedPassword)) {
        $data = [
          "job_reference_no" => $input['job_reference_no'],
          "job_name" => $input['job_name'],
          "job_description" => $input['job_description']
        ];

        $job = Job::create($data);
      }

      else {
        $request->session()->flash('error', "Password did not match. Please Try Again");
        return redirect()->back()->withInput();
      }
    }

    if($job)
    {
      $request->session()->flash('success', 'New Job has been created!');
      return redirect()->back();
    }
  }
}
