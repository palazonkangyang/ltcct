<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\APVendorType;

class VendorTypeController extends Controller
{
  public function getManageVendorType()
  {
    $vendor = APVendorType::all();

    return view('vendor.manage-vendor-type', [
      'vendor' => $vendor
    ]);
  }

  public function postAddNewVendorType(Request $request)
  {
    $input = array_except($request->all(), '_token');

    $vendor_type = APVendorType::where('vendor_type_name', $input['vendor_type_name'])->first();

    if($vendor_type)
    {
      $request->session()->flash('error', "Vendor Type Name is already exist.");
      return redirect()->back()->withInput();
    }

    APVendorType::create($input);

    $request->session()->flash('success', 'New Vendor Type is successfully added!');
    return redirect()->back();
  }

  public function getVendorTypeDetail()
  {
    $vendor_type_id = $_GET['vendor_type_id'];
    $vendor_type = APVendorType::getAPVendorType($vendor_type_id);

    return response()->json(array(
	    'vendor_type' => $vendor_type
	  ));
  }

  public function postUpdateVendorType(Request $request)
  {
    $input = array_except($request->all(), '_token');

    $vendor = APVendorType::where('vendor_type_name', $input['edit_vendor_type_name'])
               ->where('ap_vendor_type_id', '!=', $input['edit_ap_vendor_type_id'])
               ->first();

    if($vendor)
    {
      $request->session()->flash('error', "Vendor Name is already exist.");
      return redirect()->back()->withInput();
    }

    $result = APVendorType::find($input['edit_ap_vendor_type_id']);

    $result->vendor_type_name = $input['edit_vendor_type_name'];
    $result->save();

    $request->session()->flash('success', 'Vendor Type is successfully updated!');
    return redirect()->route('manage-ap-vendor-type-page');
  }

}
