<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vendor extends Model
{
    // use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];  
      
    
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
