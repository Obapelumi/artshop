<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserMeta extends Model
{
    protected $fillable = [
        'user_id', 'verification_code'
    ];

    public function user () {
        return  $this->belongsTo('App\User');
    }
}

