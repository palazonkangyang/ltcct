<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpecialRemarks extends Model
{
    protected $table = 'specialremarks';

    protected $primaryKey = "specialremarks_id";

    protected $fillable = [
        'data',
        'devotee_id'
    ];

    public function devotee()
    {
        return $this->belongsTo( \App\Models\Devotee::class );
    }
}
