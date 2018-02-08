<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
	public function tag()
	{
		return $this->belongsToMany('App\Models\Tag');
	}

	public function handling()
	{
		return $this->belongsTo('App\Models\Handling');
	}

	public function vendor()
	{
		return $this->hasMany('App\Models\Vendor');
	}

	public function customer()
	{
		return $this->belongsToMany('App\Models\Customer');
	}

    public function file()
    {
        return $this->hasOne('App\Models\File');
    }

    public function product()
    {
        return $this->hasMany('App\Models\Product');
    }

	public function discount()
	{
		return $this->belongsToMany('App\Models\Discount');
	}

}
