<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsLetter extends Model
{
    protected $fillable = [
    	'title', 'slug', 'body', 'sent', 'user_id',
    ];

    public function user () {
    	return $this->belongsTo('App\User');
    }
}
