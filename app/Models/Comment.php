<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    // use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];  
      
    
    protected $fillable = [
        'user_id', 'blog_id', 'user_name', 'comment', 'reply'
    ];

    public function blog () {
        return $this->belongsTo('App\Models\Blog');
    }

    public function user () {
        return $this->belongsTo('App\User');
    }
}
