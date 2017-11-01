<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
// use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
// use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class Staff extends Model implements CanResetPasswordContract
{
	use Authenticatable, CanResetPassword;

	protected $table = 'staff';

	protected $primaryKey = "staff_id";

	protected $fillable = [
		'role',
		'first_name',
		'last_name',
		'user_name',
		'password'
	];
}
