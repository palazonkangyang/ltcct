<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;


class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{
	use Authenticatable, CanResetPassword;

    protected $table = 'user';

    protected $fillable = [
    	'role',
    	'first_name',
    	'last_name',
    	'user_name',
        'password'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];
}
