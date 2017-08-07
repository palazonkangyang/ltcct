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
        'address_translated',
        'devotee_id'
    ];

    public function devotee()
    {
        return $this->belongsTo( \App\Models\Devotee::class );
    }
}
