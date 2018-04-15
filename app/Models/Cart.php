<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;

class Cart extends Model
{
    // use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];  
      

	protected $fillable = ['user_id', 'cart', 'shipping_cost', 'purchased'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function order()
    {
    	return $this->hasMany('App\Models\Order');
    }

}
