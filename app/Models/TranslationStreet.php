<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TranslationStreet extends Model
{
    protected $table = 'translation_street';

    protected $fillable = [
        'english',
        'chinese'
    ];
}
