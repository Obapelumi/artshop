<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Admin extends Model
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

	public function file()
	{
		return $this->hasOne('App\Models\File');
	}

}
