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
        'nationality',
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
            'devotee.oversea_addr_in_chinese',
            'devotee.nric',
            'devotee.deceased_year',
            'devotee.dob',
            'devotee.marital_status',
            'devotee.dialect',
            'devotee.nationality',
            'devotee.familycode_id',
            'devotee.member_id',
            'member.introduced_by1',
            'member.introduced_by2',
            'member.approved_date',
            'familycode.familycode'
        );

        $devotee->leftjoin('member', 'devotee.member_id', '=', 'member.member_id');
        $devotee->leftjoin('familycode', 'devotee.familycode_id', '=', 'familycode.familycode_id');

        if (\Input::get("chinese_name")) {
            $devotee->where('chinese_name', '=', $input['chinese_name']);
        }

        if (\Input::get("english_name")) {
            $devotee->where('english_name', '=', $input['english_name']);
        }

        if (\Input::get("guiyi_name")) {
            $devotee->where('guiyi_name', '=', $input['guiyi_name']);
        }

        return $devotee;
    }


    public function searchFamilyCode($input)
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
        $devotee->where('address_building', '=', $input['address_building']);
        $devotee->where('address_postal', '=', $input['address_postal']);

        return $devotee;
    }

    // public function optionaladdress()
    // {
    //     return $this->hasMany( \App\Models\OptionalAddress::class );
    // }

    // public function optionalvehicle()
    // {
    //     return $this->hasMany( \App\Models\OptionalVehicle::class );
    // }
}
