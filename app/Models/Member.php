<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $table = 'member';

    protected $primaryKey = "member_id";

    protected $fillable = [
        'introduced_by1',
        'introduced_by2',
        'approved_date',
        'cancelled_date',
        'reason_for_cancel',
        'paytill_date',
        'member'
    ];
}
