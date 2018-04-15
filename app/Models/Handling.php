<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Handling extends Model
{
    // use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];  
      
    
	public function product()
	{
		return $this->hasMany('App\Models\Product');
	}

	public function category()
	{
		return $this->hasMany('App\Models\Category');
	}

}
