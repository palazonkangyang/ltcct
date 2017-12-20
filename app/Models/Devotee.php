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
    'email',
    'guiyi_name',
    'guiyi_date',
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
    'member_id',
    'lasttransaction_at'
  ];

  public function focusDevotee($input)
  {
    $devotee = DB::table('devotee');

    $devotee->select(
      'devotee.*',
      'dialect.dialect_name',
      'member.introduced_by1',
      'member.introduced_by2',
      'member.approved_date',
      'member.paytill_date',
      'familycode.familycode',
      'country.country_name',
      'specialremarks.devotee_id as specialremarks_devotee_id'
    );

    $devotee->leftjoin('member', 'devotee.member_id', '=', 'member.member_id');
    $devotee->leftjoin('familycode', 'devotee.familycode_id', '=', 'familycode.familycode_id');
    $devotee->leftjoin('country', 'country.id', '=', 'devotee.nationality');
    $devotee->leftjoin('dialect', 'devotee.dialect', '=', 'dialect.dialect_id');
    $devotee->leftjoin('specialremarks', 'devotee.devotee_id', '=', 'specialremarks.devotee_id');

    if (\Input::get("chinese_name")) {
      $devotee->where('chinese_name', 'like', '%' . $input['chinese_name'] . '%');
      $devotee->orderBy('devotee.devotee_id', 'asc');
    }

    if (\Input::get("english_name")) {
      $devotee->where('english_name', 'like', '%' . $input['english_name'] . '%');
      $devotee->orderBy('devotee.devotee_id', 'asc');
    }

    if (\Input::get("devotee_id")) {
      $devotee->where('devotee.devotee_id', '=', $input['devotee_id']);
      $devotee->orderBy('devotee.devotee_id', 'asc');
    }

    if (\Input::get("member_id")) {
      $devotee->where('devotee.member_id', '=', $input['member_id']);
      $devotee->orderBy('devotee.devotee_id', 'asc');
    }

    if (\Input::get("familycode")) {
      $devotee->where('familycode.familycode', '=', $input['familycode']);
      $devotee->orderBy('devotee.devotee_id', 'asc');
    }

    if (\Input::get("nric")) {
      $devotee->where('nric', 'like', '%' . $input['nric'] . '%');
      $devotee->orderBy('devotee.devotee_id', 'asc');
    }

    if (\Input::get("address_street")) {
      $devotee->where('address_street', 'like', '%' . $input['address_street'] . '%');
      $devotee->orderBy('devotee.address_postal', 'asc');
      $devotee->orderBy('devotee.address_unit1', 'asc');
      $devotee->orderBy('devotee.address_unit2', 'asc');
    }

    if (\Input::get("address_houseno")) {
      $devotee->where('address_houseno', 'like', '%' . $input['address_houseno'] . '%');
      $devotee->orderBy('devotee.devotee_id', 'asc');
    }

    if (\Input::get("adress_unit1")) {
      $devotee->where('adress_unit1', '=', $input['adress_unit1']);
      $devotee->orderBy('devotee.devotee_id', 'asc');
    }

    if (\Input::get("adress_unit2")) {
      $devotee->where('adress_unit2', '=', $input['adress_unit2']);
      $devotee->orderBy('devotee.devotee_id', 'asc');
    }

    if (\Input::get("address_postal")) {
      $devotee->where('address_postal', 'like', '%' . $input['address_postal'] . '%');
      $devotee->orderBy('devotee.devotee_id', 'asc');
    }

    if (\Input::get("contact")) {
      $devotee->where('contact', 'like', '%' . $input['contact'] . '%');
      $devotee->orderBy('devotee.devotee_id', 'asc');
    }

    $devotee->GroupBy('devotee.devotee_id');

    return $devotee;
  }

  // search family code
  public function searchFamilyCode($input)
  {
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

    elseif(isset($input['address_postal']) && isset($input['address_unit1']) && isset($input['address_unit2']))
    {
      $devotee = DB::table('devotee');
      $devotee->select(
        'devotee.devotee_id',
        'devotee.chinese_name',
        'familycode.familycode_id',
        'familycode.familycode'
      );

      $devotee->join('familycode', 'devotee.familycode_id', '=', 'familycode.familycode_id');

      $devotee->where('address_unit1', '=', $input['address_unit1']);
      $devotee->where('address_unit2', '=', $input['address_unit2']);
      $devotee->where('address_postal', '=', $input['address_postal']);
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

      $devotee->where('address_postal', '=', $input['address_postal']);
      $devotee->orderBy('familycode_id', 'asc');
    }

    return $devotee;
  }

  public function searchDevotee($input)
  {
    $devotee = DB::table('devotee');
    $devotee->select(
      'devotee.*'
    );

    if ($input['devotee_id']) {
      $devotee->where('devotee_id', $input['devotee_id']);
    }

    if($input['member_id']) {
      $devotee->where('member_id', $input['member_id']);
    }

    if($input['chinese_name'])
    {
      $devotee->where('chinese_name', 'like', '%' . $input['chinese_name'] . '%');
    }

    return $devotee;
  }

  public static function getDevotee($devotee_id){
    return Devotee::where('devotee_id',$devotee_id)->first();
  }

  public static function isMember($devotee_id){
    return !empty(Devotee::where('devotee_id',$devotee_id)->pluck('member_id')->first());

  }

  public static function getChineseName($devotee_id){
    return Devotee::where('devotee_id',$devotee_id)->pluck('chinese_name')->first();
  }

  public static function getGuiyiName($devotee_id){
    return Devotee::where('devotee_id',$devotee_id)->pluck('guiyi_name')->first();
  }

  public static function isSameDevoteeId($devotee_id_1,$devotee_id_2){
    return $devotee_id_1 == $devotee_id_2;
  }

  public static function isSameFamily($devotee_id_1,$devotee_id_2){
    return Devotee::getFamilyCode($devotee_id_1) == Devotee::getFamilyCode($devotee_id_2);
  }

  public static function isNotSameFamily($devotee_id_1,$devotee_id_2){
    return Devotee::getFamilyCode($devotee_id_1) != Devotee::getFamilyCode($devotee_id_2);
  }

  public static function isRelative($devotee_id_1,$devotee_id_2){
    return Devotee::getFamilyCode($devotee_id_1) != Devotee::getFamilyCode($devotee_id_2);
  }

  public static function getFamilyCode($devotee_id){
    return Devotee::where('devotee_id',$devotee_id)->pluck('familycode_id')->first();
  }

}
