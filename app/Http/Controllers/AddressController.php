<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Devotee;

class AddressController extends Controller
{
    public static function getAddressByDevoteeId($devotee_id){
      $devotee = Devotee::getDevotee($devotee_id);
      if($devotee->address_postal != NULL){
        return AddressController::getEnglishAddressOfDevotee($devotee);
      }
      else if($devotee->oversea_addr_in_chinese != NULL){
        return AddressController::getChineseAddressOfDevotee($devotee);
      }
    }

    public static function getTranslatedOrOverseaAddressByDevoteeId($devotee_id){
      $devotee = Devotee::getDevotee($devotee_id);
      if($devotee->address_translated != NULL){
        return AddressController::getTranslatedAddressOfDevotee($devotee);
      }
      else if($devotee->oversea_addr_in_chinese != NULL){
        return AddressController::getChineseAddressOfDevotee($devotee);
      }
    }

    public static function getEnglishAddressOfDevotee($devotee){
      return $devotee->address_houseno . "#" . $devotee->address_unit1 . '-' .$devotee->address_unit2 . ", " . $devotee->address_street . ", " . $devotee->address_postal;
    }

    public static function getChineseAddressOfDevotee($devotee){
      return $devotee->oversea_addr_in_chinese;
    }

    public static function getTranslatedAddressOfDevotee($devotee){
      return $devotee->address_translated;
    }
}
