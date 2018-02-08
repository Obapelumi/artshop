<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Vendor as Vendors;
use App\Models\Admin;
use Auth;

class Vendor
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
        if (Vendors::find(['user_id'=> Auth::user()->id]) || Admin::find(['user_id'=> Auth::user()->id]))
        {
            return $next($request);
        }
        $error = 'please register as a vendor to access this page';     
        return response()->json(['error'=> $error], 401);
    }
}
