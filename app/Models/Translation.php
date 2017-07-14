<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Translation extends Model
{
    protected $table = 'translation';

    protected $primaryKey = "translation_id";

    protected $fillable = [
        'from_en',
        'to_cn'
    ];
}
