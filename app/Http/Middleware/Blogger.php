<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Blogger as Bloggers;
use App\Models\Admin;
use Auth;

class Blogger
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
        if (Bloggers::find(['user_id' => Auth::user()->id]) || Admin::find(['user_id' => Auth::user()->id]))
        {
            return $next($request);
        }
        $error = 'You are not an authorized blogger';     
        return response()->json(['error'=> $error], 401);
    }
}
