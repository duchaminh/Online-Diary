<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Privacy extends Model
{
    protected $table="privacy";
    protected $fillable = ['id', 'name'];

}
