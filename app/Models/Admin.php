<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
	public function user()
	{
		return $this->belongsTo('App\User');
	}

	public function file()
	{
		return $this->hasOne('App\Models\File');
	}
	
	public function case()
	{
		return $this->hasMany('App\Models\Case');
	}

}
