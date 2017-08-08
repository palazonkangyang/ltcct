<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Acknowledge extends Model
{
    protected $table = 'acknowledge';

    protected $fillable = [
        'prelogin_notes',
        'show_prelogin'
    ];
}
