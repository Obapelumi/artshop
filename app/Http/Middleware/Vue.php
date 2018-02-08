<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\URL;
use Config;

class Vue
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
        $url = Url::current();
        $domain = config('app.url');
        $route = substr($url, strpos($url, $domain) + strlen($domain)); 
        if (strpos($request->path(), 'api') !== false) {
            return $next($request);
        }
        if ($route == "") {
            return $next($request);
        }
        else {
            $route = $domain . '/#' . $route;
            return redirect($route);
        }
    }
}
