<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductMeta extends Model
{
    // use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];  
      
    
    protected $fillable = [
        'product_id',
        'stock_updated_at',
        'size_type', 
        'sizes', 
        'length', 
        'breadth', 
        'height', 
        'weight', 
        'other_info', 
        'discount'
    ];

    public function product () {
        return $this->belongsTo('App\Models\Product');
    }
}
