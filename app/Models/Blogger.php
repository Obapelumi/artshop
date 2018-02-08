<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blogger extends Model
{
	public function file()
	{
		return $this->hasOne('App\Models\File');
	}

    public function user() 
    {
    	return $this->belongsTo('App\User');
    }

	public function blog()
	{
		return $this->hasMany('App\Models\Blog');
	}

}
