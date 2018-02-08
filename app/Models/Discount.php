<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
	public function product()
	{
		return $this->belongsToMany('App\Models\Product');
	}

	public function category()
	{
		return $this->belongsToMany('App\Models\Category');
	}

	public function customer()
	{
		$this->belongsToMany('App\Models\Customer');
	}
}
