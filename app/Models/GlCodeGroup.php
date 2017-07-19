<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GlCodeGroup extends Model
{
    protected $table = 'glcodegroup';

    protected $primaryKey = "glcodegroup_id";

    protected $fillable = [
        'name',
        'description',
        'balancesheet_side',
        'status'
    ];
}
