<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OptionalAddress extends Model
{
    protected $table = 'optionaladdress';

    protected $primaryKey = "optionaladdress_id";

    protected $fillable = [
        'type',
        'data',
        'address',
        'oversea_address',
        'address_translated',
        'devotee_id'
    ];

    public function devotee()
    {
        return $this->belongsTo( \App\Models\Devotee::class );
    }

    public static function getOptionalAddressByDevoteeId($devotee_id){
      return OptionalAddress::where('devotee_id','=',$devotee_id)->get();
    }

    public static function getOptionalAddressByOptionalAddressId($optionaladdress_id){
      return OptionalAddress::where('optionaladdress_id','=',$optionaladdress_id)
                            ->first();
    }


}
