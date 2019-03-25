<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
class Comment extends Model
{
    protected $table="comment";
    public function userName($fid)
    {

    	return User::find($fid)->name;
    }
    public function userAvatar($fid)
    {
        $user= User::find($fid);
        $info=$user->info;
        return $info[0]->avatar;
    }
    public function postComment() {
    	return $this->belongsTo('App\Diary');
    }
    public function userInfo() {
        return $this->belongsTo('App\UserInfo','id_user');
    }
    public function user() {
        return $this->belongsTo('App\User','id_user');
    }
}
