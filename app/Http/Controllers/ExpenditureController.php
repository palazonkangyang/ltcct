<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\User;
use App\Models\Expenditure;
use App\Models\APVendor;
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

class ExpenditureController extends Controller
{

  public function getManageExpenditure()
  {

    $expenditure = Expenditure::leftjoin('ap_vendor', 'expenditure.supplier', '=', 'ap_vendor.ap_vendor_id')
                   ->select('expenditure.*', 'ap_vendor.vendor_name')
                   ->orderBy('created_at', 'desc')->get();

    $glcode = Glcode::where('glcodegroup_id', 4)->get();

    return view('expenditure.manage-expenditure', [
      'expenditure' => $expenditure,
      'glcode' => $glcode
    ]);
  }

  public function postAddNewExpenditure(Request $request)
  {
    $input = array_except($request->all(), '_token');

    if(isset($input['supplier_id']))
    {
      $supplier_id = $input['supplier_id'];
    }
    else
    {
      $vendor = APVendor::where('vendor_name', $input['supplier'])
                ->where('ap_vendor_id', '!=', $input['supplier_id'])
                ->first();

      if($vendor)
      {
        $request->session()->flash('error', "Vendor Name is already exist.");
        return redirect()->back()->withInput();
      }

      $data = [
        "vendor_name" => $input['supplier']
      ];

      $result = APVendor::create($data);

      $supplier_id = $result->ap_vendor_id;
    }

    // Modify fields
    if(isset($input['date']))
    {
      $date = str_replace('/', '-', $input['date']);
      $newDate = date("Y-m-d", strtotime($date));
    }

    else {
      $newDate = "";
    }

    $year = date("y");

    if(count(Expenditure::all()))
    {
      $expenditure_id = Expenditure::all()->last()->expenditure_id;
      $expenditure_id = str_pad($expenditure_id + 1, 4, 0, STR_PAD_LEFT);
    }

    else
    {
      $expenditure_id = 0;
      $expenditure_id = str_pad($expenditure_id + 1, 4, 0, STR_PAD_LEFT);
    }

    $reference_no = 'EXP-' . $year . $expenditure_id;

    $data = [
      "reference_no" => $reference_no,
      "date" => $newDate,
      "supplier" => $supplier_id,
      "description" => $input['description'],
      "glcode_id" => $input['glcode_id'],
      "credit_total" => $input['credit_total'],
      "status" => $input['status']
    ];

    Expenditure::create($data);

    $success_msg = $reference_no . ' has been created!';

    $request->session()->flash('success', $success_msg);
    return redirect()->route('manage-expenditure-page');
  }

  public function getExpenditureDetail()
  {
    $expenditure_id = $_GET['expenditure_id'];

    $expenditure = Expenditure::leftjoin('ap_vendor', 'expenditure.supplier', '=', 'ap_vendor.ap_vendor_id')
                   ->select('expenditure.*', 'ap_vendor.vendor_name as supplier')
                   ->where('expenditure_id', $expenditure_id)
                   ->get();

    $expenditure[0]->date = Carbon::parse($expenditure[0]->date)->format("d/m/Y");

    return response()->json(array(
	    'expenditure' => $expenditure,
	  ));
  }

  public function postUpdateExpenditure(Request $request)
  {
    $input = array_except($request->all(), '_token');

    // Modify fields
    if(isset($input['edit_date']))
    {
      $date = str_replace('/', '-', $input['edit_date']);
      $newDate = date("Y-m-d", strtotime($date));
    }

    else {
      $newDate = "";
    }

    $expenditure = Expenditure::find($input['edit_expenditure_id']);

    $expenditure->reference_no = $input['edit_reference_no'];
    $expenditure->date = $newDate;
    $expenditure->supplier = $input['edit_supplier'];
    $expenditure->description = $input['edit_description'];
    $expenditure->credit_total = $input['edit_credit_total'];
    $expenditure->status = $input['edit_status'];

    $result = $expenditure->save();

    if($result)
    {
      $request->session()->flash('success', 'Expenditure has been updated!');
      return redirect()->route('manage-expenditure-page');
    }
  }

  public function deleteExpenditure(Request $request, $id)
  {
    $result = Expenditure::find($id);

    if (!$result) {
      $request->session()->flash('error', 'Selected Expenditure is not found.');
      return redirect()->back();
  	}

    $result->delete();

		$request->session()->flash('success', 'Selected Expenditure has been deleted.');
    return redirect()->back();
  }

  public function getSearchSupplier(Request $request)
  {
    $supplier = Input::get('term');
    $results = array();

    $queries = APVendor::where('vendor_name', 'like', '%'.$supplier.'%')
               ->take(5)
               ->get();

    foreach ($queries as $query)
    {
      $results[] = [
        'id' => $query->ap_vendor_id,
        'value' => $query->vendor_name
      ];
    }
    return response()->json($results);
  }
}
