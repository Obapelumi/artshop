<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlogTag extends Model
{
    // use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];  
      
    public function blog()
    {
    	return $this->belongsToMany('App\Models\Blog', 'blog_tag');
    }
}
