<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
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
