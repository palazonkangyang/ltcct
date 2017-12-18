<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SystemSetting;

class SystemSettingController extends Controller
{
    public function getFaHuiSetting(){
      $data['xiaozai_price_hj'] = SystemSetting::getValueAmountOfXiaozaiPriceHj();
      $data['xiaozai_price_gr'] = SystemSetting::getValueAmountOfXiaozaiPriceGr();
      $data['xiaozai_price_company'] = SystemSetting::getValueAmountOfXiaozaiPriceCompany();
      $data['xiaozai_price_stall'] = SystemSetting::getValueAmountOfXiaozaiPriceStall();
      $data['xiaozai_price_car'] = SystemSetting::getValueAmountOfXiaozaiPriceCar();
      $data['xiaozai_price_ship'] = SystemSetting::getValueAmountOfXiaozaiPriceShip();

      return view('admin.fahui-setting',$data);
    }

    public function postUpdateFaHuiSetting(Request $request){
      $requests = $request->except(['_token']);
      foreach($requests as $name=>$new_value){
        $old_value = SystemSetting::findValueAmountByName($name);
        SystemSetting::isDifferentValue($old_value,$new_value) ? SystemSetting::saveValueAmountByName($name,$new_value) : false ;
      }
      $request->session()->flash('success', 'FaHui Setting has been updated!');
  		return redirect()->back();
    }
}
