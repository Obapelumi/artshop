<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        schema::defaultStringLength(191);
        
        Validator::extend('phone', function($attribute, $value, $parameters, $validator) {
                return preg_match('%^(?:(?:\(?(?:00|\+)([1-4]\d\d|[1-9]\d?)\)?)?[\-\.\ \\\/]?)?((?:\(?\d{1,}\)?[\-\.\ \\\/]?){0,})(?:[\-\.\ \\\/]?(?:#|ext\.?|extension|x)[\-\.\ \\\/]?(\d+))?$%i', $value) && strlen($value) >= 10;
            });

        Validator::replacer('phone', function($message, $attribute, $rule, $parameters) {
                return str_replace(':attribute',$attribute, ':attribute is invalid phone number');
            });

        Validator::extend('zip', function($attribute, $value, $parameters, $validator) {
                return preg_match('(\d{6})', $value);
            });

        Validator::replacer('zip', function($message, $attribute, $rule, $parameters) {
                return str_replace(':attribute',$attribute, ':attribute is invalid zip code');
            });



    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
