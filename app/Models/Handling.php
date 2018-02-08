<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Handling extends Model
{
	public function product()
	{
		return $this->hasMany('App\Models\Product');
	}

	public function category()
	{
		return $this->hasMany('App\Models\Category');
	}

}
