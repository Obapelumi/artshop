<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    protected $fillable = [
        'blogger_id', 'published', 'title', 'slug', 'body', 'seo_key_words', 'user_id'
    ];

    public function user() 
    {
    	return $this->belongsTo('App\User');
    }

    public function blogger()
    {
    	return $this->belongsTo('App\Models\Blogger');
    }

    public function blogTag()
    {
    	return $this->belongsToMany('App\Models\BlogTag', 'blog_tag');
    }

    public function comment () {
        return $this->hasMany('App\Models\Comment');
    }

    public function file()
    {
        return $this->hasOne('App\Models\File');
    }
}
