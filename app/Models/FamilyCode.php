<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FamilyCode extends Model
{
    protected $table = 'familycode';

    protected $primaryKey = "familycode_id";

    protected $fillable = [
        'familycode'
    ];
}
