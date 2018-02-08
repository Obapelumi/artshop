<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
	public function user()
	{
		return $this->belongsTo('App\User');
	}

	public function product()
	{
		return $this->hasMany('App\Models\Product');
	}

	public function category()
	{
		return $this->belongsTo('App\Models\Category');
	}

	public function file()
	{
		return $this->hasOne('App\Models\File');
	}

}
