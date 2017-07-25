<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RelativeFriendLists extends Model
{
    protected $table = 'relative_friends_lists';

    protected $primaryKey = "relative_friend_list_id";

    protected $fillable = [
        'devotee_id',
        'year'
    ];
}
