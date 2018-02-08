<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
	public function category()
	{
		return $this->belongsToMany('App\Models\Category');
	}
	
	public function product()
	{
		return $this->belongsToMany('App\Models\Product');
	}
}
