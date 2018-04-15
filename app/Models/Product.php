<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    // use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];  
      
    
	protected $fillable = [
		'vendor_id', 
		'category_id', 
		'handling_id', 
		'name', 
		'slug', 
		'brief_detail', 
		'full_detail', 
		'price', 
		'stock'
	];

	public function meta () {
		return $this->hasOne('App\Models\ProductMeta');
	}

	public function vendor()
	{
		return $this->belongsTo('App\Models\Vendor');
	}

	public function handling()
	{
		return $this->belongsTo('App\Models\Handling');
	}

	public function review()
	{
		return $this->hasMany('App\Models\Review');
	}

	public function order()
	{
		return $this->belongsToMany('App\Models\Order')->withTimestamps();
	}

	public function tag()
	{
		return $this->belongsToMany('App\Models\Tag');
	}

    public function file()
    {
        return $this->hasMany('App\Models\File');
    }

    public function category()
    {
    	return $this->belongsTo('App\Models\Category');
    }

    public function discount()
    {
    	return $this->belongsToMany('App\Models\Discount');
    }
}
