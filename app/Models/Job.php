<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    protected $table = 'job';

    protected $primaryKey = "job_id";

    protected $fillable = [
        'job_reference_no',
        'job_name',
        'job_description'
    ];
}
