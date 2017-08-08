<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use DB;

class Devotee extends Model
{
    protected $table = 'devotee';

    protected $primaryKey = "devotee_id";

    protected $fillable = [
        'title',
        'chinese_name',
        'english_name',
        'contact',
        'guiyi_name',
        'address_houseno',
        'address_unit1',
        'address_unit2',
        'address_street',
        'address_building',
        'address_postal',
        'address_translated',
        'oversea_addr_in_chinese',
        'nric',
        'deceased_year',
        'dob',
        'marital_status',
        'dialect',
        'other_dialect',
        'race',
        'other_race',
        'nationality',
        'mailer',
        'familycode_id',
        'member_id'
    ];

    public function focusDevotee($input)
    {
        $devotee = DB::table('devotee');

        $devotee->select(
            'devotee.devotee_id',
            'devotee.title',
            'devotee.chinese_name',
            'devotee.english_name',
            'devotee.contact',
            'devotee.guiyi_name',
            'devotee.address_houseno',
            'devotee.address_unit1',
            'devotee.address_unit2',
            'devotee.address_street',
            'devotee.address_building',
            'devotee.address_postal',
            'devotee.address_translated',
            'devotee.oversea_addr_in_chinese',
            'devotee.nric',
            'devotee.deceased_year',
            'devotee.dob',
            'devotee.marital_status',
            'devotee.dialect',
            'devotee.other_dialect',
            'devotee.race',
            'devotee.other_race',
            'devotee.nationality',
            'devotee.familycode_id',
            'devotee.member_id',
            'member.introduced_by1',
            'member.introduced_by2',
            'member.approved_date',
            'familycode.familycode',
            'country.country_name'
        );

        $devotee->leftjoin('member', 'devotee.member_id', '=', 'member.member_id');
        $devotee->leftjoin('familycode', 'devotee.familycode_id', '=', 'familycode.familycode_id');
        $devotee->leftjoin('country', 'country.id', '=', 'devotee.nationality');

        if (\Input::get("chinese_name")) {
            $devotee->where('chinese_name', 'like', '%' . $input['chinese_name'] . '%');
            $devotee->orderBy('devotee.devotee_id', 'asc');
        }

        if (\Input::get("devotee_id")) {
            $devotee->where('devotee_id', '=', $input['devotee_id']);
            $devotee->orderBy('devotee.devotee_id', 'asc');
        }

        if (\Input::get("member_id")) {
            $devotee->where('devotee.member_id', '=', $input['member_id']);
            $devotee->orderBy('devotee.devotee_id', 'asc');
        }

        if (\Input::get("familycode")) {
            $devotee->where('familycode.familycode', 'like', '%' . $input['familycode'] . '%');
            $devotee->orderBy('devotee.devotee_id', 'asc');
        }

        if (\Input::get("nric")) {
            $devotee->where('nric', 'like', '%' . $input['nric'] . '%');
            $devotee->orderBy('devotee.devotee_id', 'asc');
        }

        if (\Input::get("address_street")) {
            $devotee->where('address_street', 'like', '%' . $input['address_street'] . '%');
            $devotee->orderBy('devotee.address_street', 'asc');
        }

        if (\Input::get("address_houseno")) {
            $devotee->where('address_houseno', 'like', '%' . $input['address_houseno'] . '%');
            $devotee->orderBy('devotee.devotee_id', 'asc');
        }

        if (\Input::get("adress_unit1")) {
            $devotee->where('adress_unit1', '=', $input['adress_unit1']);
            $devotee->orderBy('devotee.adress_unit1', 'asc');
        }

        if (\Input::get("adress_unit2")) {
            $devotee->where('adress_unit2', '=', $input['adress_unit2']);
            $devotee->orderBy('devotee.adress_unit2', 'asc');
        }

        if (\Input::get("address_postal")) {
            $devotee->where('address_postal', 'like', '%' . $input['address_postal'] . '%');
            $devotee->orderBy('devotee.address_postal', 'asc');
        }

        if (\Input::get("contact")) {
            $devotee->where('contact', 'like', '%' . $input['contact'] . '%');
            $devotee->orderBy('devotee.devotee_id', 'asc');
        }

        return $devotee;
    }

    // search family code
    public function searchFamilyCode($input)
    {
        // $input['address_houseno'] = 88;
        // $input['address_unit1'] = 77;
        // $input['address_unit2'] = 11;
        // $input['address_street'] = "Ang Mo Kio Street 11";
        // $input['address_postal'] = 102547;

        if(isset($input['oversea_addr_in_chinese']))
        {
          $devotee = DB::table('devotee');
          $devotee->select(
              'devotee.devotee_id',
              'devotee.chinese_name',
              'familycode.familycode_id',
              'familycode.familycode'
          );

          $devotee->join('familycode', 'devotee.familycode_id', '=', 'familycode.familycode_id');

          $devotee->where('oversea_addr_in_chinese', '=', $input['oversea_addr_in_chinese']);
          $devotee->orderBy('familycode_id', 'asc');
        }

        else
        {
          $devotee = DB::table('devotee');
          $devotee->select(
              'devotee.devotee_id',
              'devotee.chinese_name',
              'familycode.familycode_id',
              'familycode.familycode'
          );

          $devotee->join('familycode', 'devotee.familycode_id', '=', 'familycode.familycode_id');

          $devotee->where('address_houseno', '=', $input['address_houseno']);
          $devotee->where('address_unit1', '=', $input['address_unit1']);
          $devotee->where('address_unit2', '=', $input['address_unit2']);
          $devotee->where('address_street', '=', $input['address_street']);
          $devotee->where('address_postal', '=', $input['address_postal']);
          $devotee->orderBy('familycode_id', 'asc');
        }

        return $devotee;
    }

}
