<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlogTag extends Model
{
    public function blog()
    {
    	return $this->belongsToMany('App\Models\Blog', 'blog_tag');
    }
}
