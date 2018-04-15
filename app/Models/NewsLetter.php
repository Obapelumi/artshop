<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NewsLetter extends Model
{
    // use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];  
      
    
    protected $fillable = [
    	'title', 'slug', 'body', 'sent', 'user_id',
    ];

    public function user () {
    	return $this->belongsTo('App\User');
    }
}
