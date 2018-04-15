<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
    
    public function meta () {
        return $this->hasOne('App\Models\UserMeta');
    }

    public function vendor()
    {
        return $this->hasOne('App\Models\Vendor');
    }

    public function customer()
    {
        return $this->hasOne('App\Models\Customer');
    }

    public function admin()
    {
        return $this->hasOne('App\Models\Admin');
    }

    public function order()
    {
        return $this->hasMany('App\Models\Order');
    }

    public function review()
    {
        return $this->hasMany('App\Models\Review');
    }

    public function file()
    {
        return $this->hasMany('App\Models\File');
    }

    public function cart()
    {
        return $this->hasMany('App\Models\Cart');
    }

    public function blog()
    {
        return $this->hasManyThrough('App\Models\Blog', 'App\Models\Blogger');
    }

    public function blogger()
    {
        return $this->hasOne('App\Models\Blogger');
    }    

    public function comment () {
        return $this->hasMany('App\Models\Comment');
    }

    public function newsletter()
    {
        return $this->hasMany('App\Models\NewsLetter');
    }    

}
