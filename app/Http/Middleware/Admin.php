<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Admin as Admins;
use Auth;

class Admin
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
        if (Admins::find(['user_id' => Auth::user()->id]))
        {
            return $next($request);
        }
        $error = 'we love you but admin only';     
        return response()->json(['error'=> $error], 401);
    }
}
