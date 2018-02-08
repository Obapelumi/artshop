<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Customer as Customers;
use App\Models\Admin;
use Auth;

class Customer
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check() && (Customers::find(['user_id' => Auth::user()->id]) || Admin::find(['user_id' => Auth::user()->id])))
        {
            return $next($request);
        }
        $error = 'you need to complete your profile to view this page';     
        return response()->json(['error'=> $error], 401);
    }
}
