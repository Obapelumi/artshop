<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Session;

class Cart extends Model
{

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
