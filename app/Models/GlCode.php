<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GlCode extends Model
{
    protected $table = 'glcode';

    protected $primaryKey = "glcode_id";

    protected $fillable = [
        'accountcode',
        'type_name',
        'chinese_name',
        'price',
        'job_id',
        'next_sn_number',
        'receipt_prefix',
        'glcodegroup_id'
    ];
}
