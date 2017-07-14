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
}
