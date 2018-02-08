<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
	public function user()
	{
		return $this->belongsTo('App\User');
	}

	public function category()
	{
		return $this->belongsToMany('App\Models\Category');
	}

	public function file()
	{
		return $this->hasOne('App\Models\File');
	}

	public function order()
	{
		return $this->hasMany('App\Models\Order');
	}

    public function discount()
    {
        return $this->belongsToMany('App\Models\Discount');
    }
    
}
