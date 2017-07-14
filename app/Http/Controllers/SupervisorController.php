<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\Devotee;
use App\Models\Member;
use App\Models\OptionalAddress;
use App\Models\OptionalVehicle;
use Auth;
use DB;
use Hash;
use Mail;
use Input;
use Session;
use View;
use URL;
use Response;

class SupervisorController extends Controller
{

	// Add Member Page
	public function getAddMember()
	{
		return view('supervisor.add-member');
	}

	public function postAddMember(Request $request)
	{
		$input = array_except($request->all(), '_token');

		$validator = $this->validate($request, [
            'introduced_by1' => 'required|string',
            'introduced_by2' => 'required|string',
            'approved_date'	=> 'required',
            'cancelled_date' => 'required',
            'reason_for_cancel' => 'required'
        ]);

		if ($validator && $validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Member::create($input);

		$request->session()->flash('success', 'New Member successfully added!');
		return redirect()->back();
	}

	public function getEditMember($member_id)
	{
		$member = Member::find($member_id);

		if (!$member) {
            return view('errors.503');
        }

		return view('supervisor.edit-member', [
            'member' => $member
        ]);
	}


	// Edit Member
	public function postEditMember(Request $request, $member_id)
	{
		$input = Input::except('_token');

		$validator = $this->validate($request, [
            'introduced_by1' => 'required|string',
            'introduced_by2' => 'required|string',
            'approved_date'	=> 'required',
            'cancelled_date' => 'required',
            'reason_for_cancel' => 'required'
        ]);

        if ($validator && $validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $member = Member::find($member_id);

        $member->introduced_by1 = $input['introduced_by1'];
        $member->introduced_by2 = $input['introduced_by2'];
        $member->approved_date = $input['approved_date'];
        $member->cancelled_date = $input['cancelled_date'];
        $member->reason_for_cancel = $input['reason_for_cancel'];
        $member->save();

        $request->session()->flash('success', 'Member account has been updated!');

        return redirect()->route('edit-member-page', $member->member_id);
	}


	// Delete Member
	public function deleteMember(Request $request, $devotee_id)
	{
		$member = Devotee::find($devotee_id);

        $optionAddress = OptionalAddress::where('devotee_id', $devotee_id)->get();
        $optionVehicle = OptionalVehicle::where('devotee_id', $devotee_id)->get();

		if (!$member) {
            $request->session()->flash('error', 'Selected Member is not found.');
            return redirect()->back();
        }

        // $devotee = new Devotee();
        // $member->optionalAddress()->delete();
        // $member->optionalVehicle()->delete();
        // $member->specialRemarks()->delete();
        $member->delete();

        $request->session()->flash('success', 'Member has been deleted.');
        return redirect()->back();
	}
}