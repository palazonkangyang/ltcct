<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Devotee;

class AddressController extends Controller
{
    public static function getAddressByDevoteeId($devotee_id){
      $devotee = Devotee::where('devotee_id','=',$devotee_id)
                             ->first();
      $address = $devotee->address_houseno . "#" . $devotee->address_unit1 . '-' .$devotee->address_unit2 . ", " . $devotee->address_street . ", " . $devotee->address_postal;
      return $address;
    }
}
