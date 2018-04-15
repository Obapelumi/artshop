<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    // use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];  
      
    
    protected $fillable = [
        'user_id', 'customer_id', 'cart_id', 'amount_charged', 'trx_id', 'trx_details', 'status', 'remark', 'order_notes',
    ];

    public function user()
    {
    	return $this->belongsTo('App\User');
    }

    public function customer()
    {
        return $this->belongsTo('App\Models\Customer');
    }

	public function product()
	{
		return $this->belongsToMany('App\Models\Product')->withTimestamps();
	}

    public function cart()
    {
        return $this->belongsTo('App\Models\Cart');
    }
}
