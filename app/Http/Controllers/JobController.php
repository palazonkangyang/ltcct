<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\User;
use App\Models\Job;
use App\Models\FestiveEvent;
use App\Models\GlCode;
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

  public function getJobLists()
  {
    $jobs = Job::orderBy('created_at', 'desc')->get();

    return response()->json(array(
			'job' => $jobs
		));
  }

  public function postAddNewJob(Request $request)
  {
    $input = array_except($request->all(), '_token');

    $result = Job::where('job_name', $input['job_name'])->first();

    if($result)
    {
      $request->session()->flash('error', "Job Name is already exist.");
      return redirect()->back()->withInput();
    }

    $job_id = Job::all()->last()->job_id;
    $job_id += 1;
    $job_reference_no = "J-" . $job_id;

    $data = [
      "job_reference_no" => $job_reference_no,
      "job_name" => $input['job_name'],
      "job_description" => $input['job_description']
    ];

    Job::create($data);

    $request->session()->flash('success', 'New Job has been created!');
    return redirect()->back();
  }

  public function getJobDetail()
  {
    $job_id = $_GET['job_id'];

    $job = Job::find($job_id);

    return response()->json(array(
	    'job' => $job,
	  ));
  }

  public function postUpdateJob(Request $request)
  {
    $input = array_except($request->all(), '_token');

    $result = Job::where('job_name', $input['edit_job_name'])
               ->where('job_id', '!=', $input['edit_job_id'])
               ->first();

    if($result)
    {
      $request->session()->flash('error', "Job Name is already exist.");
      return redirect()->back()->withInput();
    }

    $job = Job::find($input['edit_job_id']);

    $job->job_name = $input['edit_job_name'];
    $job->job_description = $input['edit_job_description'];

    $job->save();

    $request->session()->flash('success', 'Job has been updated!');
    return redirect()->route('manage-job-page');
  }

  public function deleteJob(Request $request, $id)
  {
    $job = job::find($id);

    $festiveevent = FestiveEvent::where('job_id', $job->job_id)->get();
    $glcode = GlCode::where('job_id', $job->job_id)->get();

    // dd($glcode->toArray());

    if(count($festiveevent) > 0)
    {
      $request->session()->flash('error', 'Selected Job cannot be deleted. This job is selected in Festive Event.');
      return redirect()->back();
    }

    if(count($glcode)  > 0)
    {
      $request->session()->flash('error', 'Selected Job cannot be deleted. This job is selected in GL Account.');
      return redirect()->back();
    }

    if (!$job) {
      $request->session()->flash('error', 'Selected Dialect is not found.');
      return redirect()->back();
  	}

    $job->delete();

		$request->session()->flash('success', 'Selected Job has been deleted.');
    return redirect()->back();
  }
}
