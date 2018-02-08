<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductMeta extends Model
{
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
