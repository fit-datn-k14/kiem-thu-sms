<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AccessPermFrontend
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
	    if (!Auth::guard('customer')->check() || !Auth::guard('customer')->user()->status) {
		    return redirect(config('frontend.uri'));
	    }
        return $next($request);
    }
}
