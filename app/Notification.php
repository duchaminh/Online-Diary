<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table="notification";
     public function diary() {
    	return $this->belongsTo('App\Diary','id_diary');
    }

    public function userAvatar($fid)
    {
    	$user= User::find($fid);
    	$info=$user->info;
    	return $info[0]->avatar;
    }
    	
}
