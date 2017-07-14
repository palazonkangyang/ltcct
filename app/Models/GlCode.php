<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GlCode extends Model
{
    protected $table = 'glcode';

    protected $primaryKey = "glcode_id";

    protected $fillable = [
        'accountcode',
        'description',
        'has_parent',
        'coalevel',
        'haschildren',
        'status',
        'parent_accountid',
        'glcodegroup_id'
    ];
}
