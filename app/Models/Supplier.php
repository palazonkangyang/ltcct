<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $table = 'supplier';

    protected $primaryKey = "supplier_id";

    protected $fillable = [
        'name',
        'address',
        'contact_no',
        'person_incharge'
    ];
}
