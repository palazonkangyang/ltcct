<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RelativeFriendLists extends Model
{
    protected $table = 'relative_friend_lists';

    protected $primaryKey = "relative_friend_list_id";

    protected $fillable = [
        'donate_devotee_id',
        'relative_friend_devotee_id',
        'year'
    ];
}
