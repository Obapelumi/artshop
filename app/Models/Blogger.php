<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Blogger extends Model
{
	// use SoftDeletes;

	/**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
	protected $dates = ['deleted_at'];
	
	public function file()
	{
		return $this->hasOne('App\Models\File');
	}

    public function user() 
    {
    	return $this->belongsTo('App\User');
    }

	public function blog()
	{
		return $this->hasMany('App\Models\Blog');
	}

}
