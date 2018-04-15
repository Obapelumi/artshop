<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Review extends Model
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
		return $this->belongsTo('App\Models\Product');
	}
	
}
