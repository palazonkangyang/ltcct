<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MembershipFee extends Model
{
    protected $table = 'membership_fee';

    protected $primaryKey = "membership_fee_id";

    protected $fillable = [
        'membership_fee'
    ];
}
