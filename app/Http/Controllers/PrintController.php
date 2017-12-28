<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PrintController extends Controller
{
    public function printBiaoWen(Request $request){
      $data['display_name_list'] = $request['display_name_list'];
      $data['display_address_list'] = $request['display_address_list'];
      return view('print.biaowen.index',$data);
    }
}
