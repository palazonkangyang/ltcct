<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemSetting extends Model
{

  protected $table = 'system_setting';

  protected $primaryKey = "system_setting_id";

  protected $fillable = [
    'name',
    'value_text',
    'value_textarea',
    'value_amount',
    'description'
  ];

  public static function getNameOfXiaoZaiPriceHj(){
    return 'xiaozai_price_hj';
  }

  public static function getNameOfXiaoZaiPriceGr(){
    return 'xiaozai_price_gr';
  }

  public static function getNameOfXiaoZaiPriceCompany(){
    return 'xiaozai_price_company';
  }

  public static function getNameOfXiaoZaiPriceStall(){
    return 'xiaozai_price_stall';
  }

  public static function getNameOfXiaoZaiPriceCar(){
    return 'xiaozai_price_car';
  }

  public static function getNameOfXiaoZaiPriceShip(){
    return 'xiaozai_price_ship';
  }

  public static function getNameOfKongDanPriceGr(){
    return 'kongdan_price_gr';
  }

  public static function getValueAmountOfXiaozaiPriceHj(){
      return SystemSetting::where('name',SystemSetting::getNameOfXiaoZaiPriceHj())->pluck('value_amount')->first();
  }

  public static function getValueAmountOfXiaozaiPriceGr(){
      return SystemSetting::where('name',SystemSetting::getNameOfXiaoZaiPriceGr())->pluck('value_amount')->first();
  }

  public static function getValueAmountOfXiaozaiPriceCompany(){
      return SystemSetting::where('name',SystemSetting::getNameOfXiaoZaiPriceCompany())->pluck('value_amount')->first();
  }

  public static function getValueAmountOfXiaozaiPriceStall(){
      return SystemSetting::where('name',SystemSetting::getNameOfXiaoZaiPriceStall())->pluck('value_amount')->first();
  }

  public static function getValueAmountOfXiaozaiPriceCar(){
      return SystemSetting::where('name',SystemSetting::getNameOfXiaoZaiPriceCar())->pluck('value_amount')->first();
  }

  public static function getValueAmountOfXiaozaiPriceShip(){
      return SystemSetting::where('name',SystemSetting::getNameOfXiaoZaiPriceShip())->pluck('value_amount')->first();
  }

  public static function getValueAmountOfKongDanPriceGr(){
      return SystemSetting::where('name',SystemSetting::getNameOfKongDanPriceGr())->pluck('value_amount')->first();
  }


  public static function findValueAmountByName($name){
    return SystemSetting::where('name',$name)->pluck('value_amount')->first();
  }

  public static function isDifferentValue($old_value,$new_value){
    return $old_value != $new_value;
  }

  public static function saveValueAmountByName($name,$new_value){
    $system_setting = SystemSetting::where('name',$name)->first();
    $system_setting->value_amount = $new_value;
    $system_setting->save();
  }

}
