<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OptionalVehicle extends Model
{
    protected $table = 'optionalvehicle';

    protected $primaryKey = "optionalvehicle_id";

    protected $fillable = [
        'type',
        'data',
        'devotee_id'
    ];

    public function devotee()
    {
        return $this->belongsTo( \App\Models\Devotee::class );
    }

    public static function getOptionalVehicleByDevoteeId($param){
      return OptionalVehicle::where('devotee_id','=',$param['var']['devotee_id'])
                            ->get();
    }

    public static function getOptionalVehicleByOptionalAddressId($optionalvehicle_id){
      return OptionalVehicle::where('optionalvehicle_id','=',$optionalvehicle_id)
                            ->first();
    }





}
