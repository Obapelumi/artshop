<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $fillable = [
        'user_id',
        'category_id',
        'customer_id',
        'vendor_id',
        'blogger_id',
        'blog_id',
        'admin_id',
        'product_id',
        'path',
    ];

    public function user()
    {
    	return $this->belongsTo('App\User');
    }

    public function product()
    {
    	return $this->belongsTo('App\Models\Product');
    }

    public function category()
    {
    	return $this->belongsTo('App\Models\Category');
    }

    public function vendor()
    {
        return $this->belongsTo('App\Models\Vendor');
    }

    public function admin()
    {
        return $this->belongsTo('App\Models\Admin');
    }

    public function customer()
    {
        return $this->belongsTo('App\Models\Customer');
    }

    public function blogger()
    {
        return $this->belongsTo('App\Models\Blogger');
    }

    public function blog()
    {
        return $this->belongsTo('App\Models\Blog');
    }
}
